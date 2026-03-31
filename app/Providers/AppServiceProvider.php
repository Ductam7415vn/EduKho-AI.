<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Services\AI\AiServiceManager;
use App\Services\AI\LlmServiceInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AiServiceManager::class);
        $this->app->bind(LlmServiceInterface::class, AiServiceManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto initialize database if needed
        $this->initializeDatabase();
        
        // Force HTTPS when APP_URL uses https (behind reverse proxy / Cloudflare Tunnel)
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Use Tailwind CSS pagination
        Paginator::useTailwind();

        // Rate limiting for login attempts
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
        });

        // Rate limiting for password reset attempts
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email') . '|' . $request->ip());
        });

        // Rate limiting for 2FA challenge verification attempts
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by((string) $request->session()->get('2fa:user_id') . '|' . $request->ip());
        });

        // Rate limiting for registration attempts
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Rate limiting for email verification resend
        RateLimiter::for('verification', function (Request $request) {
            return Limit::perMinute(2)->by($request->input('email') . '|' . $request->ip());
        });
    }
    
    /**
     * Initialize database if needed
     */
    private function initializeDatabase(): void
    {
        if (config('database.default') === 'sqlite' && !file_exists(database_path('database.sqlite'))) {
            // Create SQLite database file
            touch(database_path('database.sqlite'));
        }
        
        try {
            // Check if database needs initialization
            if (!Schema::hasTable('users')) {
                // Run migrations and seeders
                Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            } else {
                // Check if admin user exists
                $adminExists = DB::table('users')->where('email', 'admin@truong.edu.vn')->exists();
                if (!$adminExists) {
                    // Database exists but no admin, re-seed
                    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
                }
            }
        } catch (\Exception $e) {
            // Database initialization failed, but let the app continue
            // The error will be shown when accessing the app
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset {--force : Force the operation to run without confirmation}';
    protected $description = 'Reset database (drop all tables, migrate and seed)';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will delete all data. Are you sure?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Resetting database...');
        
        // Drop all tables and re-run migrations with seeding
        Artisan::call('migrate:fresh', ['--seed' => true]);
        
        $this->info('Database has been reset successfully!');
        $this->info('Login credentials:');
        $this->info('Admin: admin@truong.edu.vn / password');
        $this->info('Teacher: lan.tran@truong.edu.vn / password');
    }
}
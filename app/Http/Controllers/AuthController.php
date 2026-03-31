<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                
                return back()->withErrors([
                    'email' => 'Email của bạn chưa được xác thực. Vui lòng kiểm tra email để xác thực tài khoản.',
                ])->withInput($request->only('email'));
            }

            // Check if 2FA is enabled
            if ($user->hasTwoFactorEnabled()) {
                // Logout temporarily and store user ID in session for 2FA
                Auth::logout();
                $request->session()->put('2fa:user_id', $user->id);
                $request->session()->put('2fa:remember', $request->boolean('remember'));

                return redirect()->route('two-factor.challenge');
            }

            $request->session()->regenerate();
            ActivityLogger::logLogin();

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        ActivityLogger::logLogout();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

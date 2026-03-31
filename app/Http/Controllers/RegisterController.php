<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        $departments = Department::orderBy('name')->get();
        return view('auth.register', compact('departments'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            // Create user (inactive until email verified)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'department_id' => $validated['department_id'],
                'password' => Hash::make($validated['password']),
                'role' => 'teacher', // Default role for new registrations
                'is_active' => false, // Inactive until email verified
                'notification_settings' => User::defaultNotificationSettings(),
            ]);

            // Generate verification token
            $token = Str::random(60);
            
            DB::table('email_verifications')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send verification email
            $this->sendVerificationEmail($user, $token);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withErrors(['error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.'])
                ->withInput();
        }
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Link xác thực không hợp lệ.']);
        }

        $verification = DB::table('email_verifications')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$verification) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Link xác thực không hợp lệ hoặc đã hết hạn.']);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($verification->created_at) > 24) {
            DB::table('email_verifications')->where('email', $email)->delete();
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Link xác thực đã hết hạn. Vui lòng đăng ký lại.']);
        }

        DB::beginTransaction();
        try {
            $user = User::where('email', $email)->first();
            
            if ($user && !$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                $user->update(['is_active' => true]);
                
                // Delete verification record
                DB::table('email_verifications')->where('email', $email)->delete();
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Email đã được xác thực thành công! Bạn có thể đăng nhập ngay.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Có lỗi xảy ra khi xác thực email.']);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()
                ->withErrors(['email' => 'Email này đã được xác thực.']);
        }

        // Delete old verification records
        DB::table('email_verifications')->where('email', $user->email)->delete();

        // Generate new token
        $token = Str::random(60);
        
        DB::table('email_verifications')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send verification email
        $this->sendVerificationEmail($user, $token);

        return back()
            ->with('success', 'Email xác thực đã được gửi lại.');
    }

    /**
     * Send verification email
     */
    protected function sendVerificationEmail(User $user, string $token)
    {
        $verificationUrl = url('/verify-email?token=' . $token . '&email=' . urlencode($user->email));

        Mail::send('emails.verify-email', [
            'name' => $user->name,
            'verificationUrl' => $verificationUrl,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Xác thực email - Hệ thống Quản lý Thiết bị');
        });
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user
     */
    public function start(User $user)
    {
        $admin = Auth::user();

        if (!$admin->isAdmin()) {
            abort(403);
        }

        // Cannot impersonate yourself
        if ($admin->id === $user->id) {
            return back()->with('error', 'Khong the gia lap chinh ban than.');
        }

        // Cannot impersonate another admin
        if ($user->isAdmin()) {
            return back()->with('error', 'Khong the gia lap nguoi dung co quyen admin.');
        }

        // Store the original admin ID in session
        session(['impersonator_id' => $admin->id]);

        // Log the impersonation
        ActivityLogger::log('impersonate_start', $user, [
            'impersonated_user' => $user->name,
            'impersonated_email' => $user->email,
        ]);

        // Login as the target user
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('info', "Dang gia lap nguoi dung: {$user->name}");
    }

    /**
     * Stop impersonating and return to admin account
     */
    public function stop()
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('dashboard');
        }

        $impersonatedUser = Auth::user();
        $admin = User::find($impersonatorId);

        if (!$admin) {
            session()->forget('impersonator_id');
            Auth::logout();
            return redirect()->route('login');
        }

        // Log the stop impersonation
        ActivityLogger::log('impersonate_stop', $impersonatedUser, [
            'impersonated_user' => $impersonatedUser->name,
        ]);

        // Clear impersonation session
        session()->forget('impersonator_id');

        // Login back as admin
        Auth::login($admin);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Da dung gia lap nguoi dung.');
    }
}

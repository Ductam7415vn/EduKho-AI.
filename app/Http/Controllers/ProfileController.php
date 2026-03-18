<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function show()
    {
        $user = Auth::user()->load('department');

        return view('profile.show', compact('user'));
    }

    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user()->load('department');

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Cap nhat thong tin thanh cong.');
    }

    /**
     * Show the change password form
     */
    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Doi mat khau thanh cong.');
    }

    /**
     * Show notification settings form
     */
    public function showNotifications()
    {
        $user = Auth::user();
        $defaults = \App\Models\User::defaultNotificationSettings();
        $settings = array_merge($defaults, $user->notification_settings ?? []);

        return view('profile.notifications', compact('settings'));
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $settingKeys = [
            'email_borrow_approved',
            'email_borrow_rejected',
            'email_borrow_overdue',
            'email_borrow_reminder',
            'email_pending_approval',
        ];

        $settings = [];
        foreach ($settingKeys as $key) {
            $settings[$key] = $request->has($key);
        }

        Auth::user()->update([
            'notification_settings' => $settings,
        ]);

        return redirect()
            ->route('profile.notifications')
            ->with('success', 'Cai dat thong bao da duoc cap nhat.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {}

    /**
     * Show 2FA setup page
     */
    public function show()
    {
        $user = Auth::user();

        return view('profile.two-factor', [
            'user' => $user,
            'enabled' => $user->hasTwoFactorEnabled(),
        ]);
    }

    /**
     * Start enabling 2FA
     */
    public function enable()
    {
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.two-factor')
                ->with('error', 'Xac thuc 2 yeu to da duoc bat.');
        }

        // Generate a new secret
        $secret = $this->twoFactorService->generateSecret();

        // Store temporarily (not confirmed yet)
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        $qrCodeUrl = $this->twoFactorService->getQrCodeUrl($user, $secret);

        return view('profile.two-factor-setup', [
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Confirm 2FA setup by verifying a code
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return redirect()->route('profile.two-factor')
                ->with('error', 'Vui long bat dau quy trinh thiet lap 2FA truoc.');
        }

        if (!$this->twoFactorService->verifyCode($user->two_factor_secret, $request->code)) {
            return back()->with('error', 'Ma xac thuc khong chinh xac. Vui long thu lai.');
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        return redirect()->route('profile.two-factor')
            ->with('success', 'Xac thuc 2 yeu to da duoc bat thanh cong!');
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.two-factor')
                ->with('error', 'Xac thuc 2 yeu to chua duoc bat.');
        }

        if (!$this->twoFactorService->verifyCode($user->two_factor_secret, $request->code)) {
            return back()->with('error', 'Ma xac thuc khong chinh xac.');
        }

        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('profile.two-factor')
            ->with('success', 'Xac thuc 2 yeu to da duoc tat.');
    }

    /**
     * Show 2FA challenge page during login
     */
    public function challenge()
    {
        if (!session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify 2FA code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa:user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            session()->forget('2fa:user_id');
            return redirect()->route('login');
        }

        if (!$this->twoFactorService->verifyCode($user->two_factor_secret, $request->code)) {
            return back()->with('error', 'Ma xac thuc khong chinh xac. Vui long thu lai.');
        }

        // Clear the 2FA session
        session()->forget('2fa:user_id');

        // Login the user
        Auth::login($user, session('2fa:remember', false));
        session()->forget('2fa:remember');
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}

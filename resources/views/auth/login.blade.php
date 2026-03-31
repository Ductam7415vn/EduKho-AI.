@extends('layouts.guest')

@section('title', __('messages.auth.login') . ' - ' . config('app.name'))
@section('body_class', 'font-sans antialiased login-page')

@push('styles')
<style>
    .login-page {
        min-height: 100vh;
    }

    .login-page .guest-shell {
        min-height: 100vh;
        align-items: stretch;
        padding: clamp(14px, 2vw, 28px);
    }

    .login-page .guest-main {
        width: 100%;
        max-width: none;
        display: flex;
    }

    .login-stage {
        width: 100%;
        min-height: calc(100vh - clamp(28px, 4vw, 56px));
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        border-radius: 30px;
        overflow: hidden;
        border: 1px solid color-mix(in srgb, var(--line) 84%, transparent);
        box-shadow: 0 24px 56px rgba(15, 23, 42, 0.16);
        background: color-mix(in srgb, var(--surface) 94%, white);
    }

    .login-story {
        position: relative;
        padding: 44px 40px;
        color: #ecfeff;
        background:
            radial-gradient(circle at 18% 20%, rgba(45, 212, 191, 0.32), transparent 35%),
            radial-gradient(circle at 82% 84%, rgba(56, 189, 248, 0.28), transparent 38%),
            linear-gradient(150deg, #0b2f4b 0%, #0f4c81 48%, #0f766e 100%);
    }

    .login-story::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: 0.26;
        background-image:
            linear-gradient(rgba(236, 254, 255, 0.22) 1px, transparent 1px),
            linear-gradient(90deg, rgba(236, 254, 255, 0.22) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    .login-story > * {
        position: relative;
        z-index: 1;
    }

    .login-story-kicker {
        display: inline-flex;
        font-size: 0.72rem;
        line-height: 1rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.22em;
        color: rgba(236, 254, 255, 0.78);
    }

    .login-story-title {
        margin-top: 1rem;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.9rem, 4vw, 2.4rem);
        line-height: 1.18;
        font-weight: 700;
        letter-spacing: -0.015em;
        color: #f8fafc;
    }

    .login-story-copy {
        margin-top: 1rem;
        max-width: 36ch;
        font-size: 0.97rem;
        color: rgba(226, 232, 240, 0.88);
    }

    .login-story-pills {
        margin-top: 1.75rem;
        display: grid;
        gap: 0.7rem;
    }

    .login-story-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #e0f2fe;
        border: 1px solid rgba(186, 230, 253, 0.28);
        background: rgba(8, 47, 73, 0.34);
    }

    .login-story-pill svg {
        width: 16px;
        height: 16px;
        color: #67e8f9;
    }

    .login-story-footnote {
        margin-top: 1.75rem;
        font-size: 0.76rem;
        color: rgba(226, 232, 240, 0.76);
    }

    .login-panel {
        padding: 38px 34px;
        background:
            radial-gradient(circle at 100% 0%, rgba(15, 118, 110, 0.08), transparent 34%),
            linear-gradient(180deg, color-mix(in srgb, var(--surface) 96%, white), var(--surface));
    }

    .login-panel-head {
        margin-bottom: 1.5rem;
    }

    .login-panel-kicker {
        font-size: 0.72rem;
        line-height: 1rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.16em;
        color: color-mix(in srgb, var(--brand) 78%, var(--text-secondary));
    }

    .login-panel-title {
        margin-top: 0.5rem;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 1.7rem;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.015em;
        color: var(--text-primary);
    }

    .login-panel-copy {
        margin-top: 0.5rem;
        font-size: 0.93rem;
        color: var(--text-secondary);
    }

    .login-alert-success {
        margin-bottom: 1.25rem;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        color: #065f46;
        border: 1px solid #86efac;
        background: #ecfdf5;
    }

    .login-form {
        display: grid;
        gap: 1.1rem;
    }

    .login-field {
        display: grid;
        gap: 0.38rem;
    }

    .login-field-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .login-input-wrap {
        position: relative;
    }

    .login-field-icon {
        pointer-events: none;
        position: absolute;
        inset: 0;
        right: auto;
        display: flex;
        align-items: center;
        padding-left: 0.75rem;
        color: #64748b;
    }

    .login-input {
        width: 100%;
        border-radius: 0.75rem;
        padding: 0.72rem 0.8rem 0.72rem 2.45rem;
        font-size: 0.92rem;
        border: 1px solid var(--line);
        background: color-mix(in srgb, var(--surface-soft) 80%, white);
        color: var(--text-primary);
        transition: all 0.2s ease;
    }

    .login-input:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.18);
        outline: none;
    }

    .login-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .login-remember {
        display: inline-flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .login-forgot {
        font-size: 0.86rem;
        font-weight: 600;
        color: color-mix(in srgb, var(--brand) 88%, var(--accent));
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .login-forgot:hover {
        color: color-mix(in srgb, var(--brand-strong) 90%, var(--accent));
    }

    .login-submit {
        display: inline-flex;
        width: 100%;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        padding: 0.78rem 1rem;
        border: none;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: white;
        transition: all 0.2s ease;
        background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
        box-shadow: 0 10px 26px rgba(15, 76, 129, 0.28);
    }

    .login-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 32px rgba(15, 76, 129, 0.34);
    }

    .login-submit:active {
        transform: translateY(0);
    }

    .login-demo {
        margin-top: 1.25rem;
        border-radius: 0.75rem;
        padding: 0.75rem 0.85rem;
        font-size: 0.86rem;
        color: #0c4a6e;
        border: 1px solid #bae6fd;
        background: linear-gradient(140deg, #f0f9ff 0%, #ecfeff 100%);
    }

    .dark .login-stage {
        border-color: rgba(71, 85, 105, 0.62);
        box-shadow: 0 24px 56px rgba(2, 6, 23, 0.46);
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.95), rgba(17, 24, 39, 0.97));
    }

    .dark .login-story {
        color: #e2e8f0;
        background:
            radial-gradient(circle at 18% 20%, rgba(45, 212, 191, 0.24), transparent 35%),
            radial-gradient(circle at 82% 84%, rgba(56, 189, 248, 0.22), transparent 38%),
            linear-gradient(150deg, #08192e 0%, #0d355a 48%, #0b4d4b 100%);
    }

    .dark .login-story-kicker {
        color: rgba(207, 250, 254, 0.76);
    }

    .dark .login-story-copy,
    .dark .login-story-footnote {
        color: rgba(203, 213, 225, 0.9);
    }

    .dark .login-story-pill {
        color: #bae6fd;
        border-color: rgba(103, 232, 249, 0.3);
        background: rgba(6, 24, 45, 0.48);
    }

    .dark .login-panel {
        background:
            radial-gradient(circle at 100% 0%, rgba(45, 212, 191, 0.1), transparent 34%),
            linear-gradient(180deg, rgba(15, 23, 42, 0.95), rgba(17, 24, 39, 0.98));
    }

    .dark .login-field-icon {
        color: #94a3b8;
    }

    .dark .login-input {
        border-color: rgba(71, 85, 105, 0.74);
        background: rgba(15, 23, 42, 0.85);
        color: #e2e8f0;
    }

    .dark .login-input:focus {
        border-color: #2dd4bf;
        box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.2);
    }

    .dark .login-demo {
        color: #bae6fd;
        border-color: rgba(103, 232, 249, 0.32);
        background: linear-gradient(140deg, rgba(12, 74, 110, 0.32), rgba(15, 23, 42, 0.76));
    }

    .dark .login-alert-success {
        color: #6ee7b7;
        border-color: rgba(52, 211, 153, 0.36);
        background: rgba(6, 78, 59, 0.4);
    }

    @media (max-width: 960px) {
        .login-page .guest-shell {
            padding: 0;
        }

        .login-stage {
            min-height: 100vh;
            grid-template-columns: 1fr;
            border-radius: 0;
        }

        .login-story {
            padding: 32px 26px;
        }

        .login-panel {
            padding: 30px 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-stage animate-fade-in-up">
    <aside class="login-story">
        <p class="login-story-kicker">{{ __('messages.login_page.system_title') }}</p>
        <h1 class="login-story-title">{{ __('messages.login_page.tagline') }}</h1>
        <p class="login-story-copy">
            {{ __('messages.login_page.description') }}
        </p>

        <div class="login-story-pills">
            <span class="login-story-pill">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h5M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                </svg>
                {{ __('messages.login_page.feature_borrow') }}
            </span>
            <span class="login-story-pill">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('messages.login_page.feature_report') }}
            </span>
            <span class="login-story-pill">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1H8a2 2 0 00-2 2v3h12v-3a2 2 0 00-2-2h-1v-1c0-1.657-1.343-3-3-3zm0-4a7 7 0 00-7 7v1a4 4 0 00-3 3.874V19a2 2 0 002 2h16a2 2 0 002-2v-3.126A4 4 0 0019 12v-1a7 7 0 00-7-7z"/>
                </svg>
                {{ __('messages.login_page.feature_secure') }}
            </span>
        </div>

        <p class="login-story-footnote">
            {{ __('messages.login_page.footnote') }}
        </p>
    </aside>

    <section class="login-panel">
        <div class="login-panel-head">
            <p class="login-panel-kicker">{{ __('messages.auth.system_account') }}</p>
            <h2 class="login-panel-title">{{ __('messages.auth.login') }}</h2>
            <p class="login-panel-copy">{{ __('messages.auth.enter_info') }}</p>
        </div>

        @if(session('success'))
        <div class="login-alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div class="login-field">
                <label for="email" class="login-field-label">Email</label>
                <div class="login-input-wrap">
                    <span class="login-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </span>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        value="{{ old('email') }}"
                        class="login-input"
                        placeholder="email@truong.edu.vn"
                    >
                </div>
                @error('email')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="login-field">
                <label for="password" class="login-field-label">{{ __('messages.auth.password') }}</label>
                <div class="login-input-wrap">
                    <span class="login-field-icon">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="login-input"
                        placeholder="••••••••"
                    >
                </div>
                @error('password')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="login-meta">
                <label class="login-remember">
                    <input id="remember" name="remember" type="checkbox" @checked(old('remember')) class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                    <span class="ml-2">{{ __('messages.auth.remember_me') }}</span>
                </label>
                <a href="{{ route('password.request') }}" class="login-forgot">
                    {{ __('messages.auth.forgot_password') }}
                </a>
            </div>

            <button type="submit" class="login-submit">{{ __('messages.auth.login') }}</button>
        </form>

        <div class="login-demo">
            <span class="font-semibold">{{ __('messages.login_page.demo_account') }}:</span> admin@truong.edu.vn / password
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-500">
                    Đăng ký ngay
                </a>
            </p>
        </div>
    </section>
</div>
@endsection

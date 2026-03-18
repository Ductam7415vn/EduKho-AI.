@extends('layouts.app')

@section('title', __('messages.profile.two_factor_setup_title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.password_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.profile.two_factor_setup_title') }}</h2>
        <p class="resource-copy">{{ __('messages.profile.two_factor_setup_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('profile.two-factor') }}" class="btn-secondary">{{ __('messages.profile.back_to_2fa') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body space-y-6">
            <div>
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.profile.step_1_title') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    {{ __('messages.profile.step_1_description') }}
                </p>
                <div class="flex justify-center">
                    <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-48 h-48 border border-gray-200 dark:border-gray-700 rounded-xl">
                </div>
            </div>

            <div>
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.profile.manual_entry') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ __('messages.profile.manual_entry_description') }}</p>
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4 text-center border border-gray-200 dark:border-gray-700">
                    <code class="text-lg font-mono tracking-wider text-gray-900 dark:text-gray-100">{{ $secret }}</code>
                </div>
            </div>

            <div class="pt-2">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ __('messages.profile.step_2_title') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('messages.profile.step_2_description') }}</p>

                <form method="POST" action="{{ route('profile.two-factor.confirm') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="code" class="form-label">{{ __('messages.profile.verification_code') }}</label>
                        <input type="text" name="code" id="code" maxlength="6" pattern="[0-9]{6}" class="form-input w-48 text-center text-lg tracking-widest" placeholder="000000" required autofocus>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="btn-primary">{{ __('messages.profile.confirm_enable_2fa') }}</button>
                        <a href="{{ route('profile.two-factor') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

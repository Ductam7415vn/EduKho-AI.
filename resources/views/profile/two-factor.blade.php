@extends('layouts.app')

@section('title', __('messages.profile.two_factor'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.password_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.profile.two_factor_title') }}</h2>
        <p class="resource-copy">{{ __('messages.profile.two_factor_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.profile.back_to_profile') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            @if($enabled)
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/40 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.profile.two_factor_enabled') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.two_factor_enabled_desc') }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50/90 dark:border-amber-800/60 dark:bg-amber-900/20 p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700 dark:text-amber-300">
                                {{ __('messages.profile.two_factor_warning') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.two-factor.disable') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="code" class="form-label">{{ __('messages.profile.verification_code') }}</label>
                        <input type="text" name="code" id="code" maxlength="6" pattern="[0-9]{6}" class="form-input w-48" placeholder="000000" required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-danger">{{ __('messages.profile.disable_two_factor') }}</button>
                </form>
            @else
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.profile.two_factor_disabled') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.two_factor_disabled_desc') }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-cyan-200 bg-cyan-50/90 dark:border-cyan-800/60 dark:bg-cyan-900/20 p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-cyan-700 dark:text-cyan-300">
                                {{ __('messages.profile.two_factor_info') }}
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.two-factor.enable') }}" class="btn-primary">{{ __('messages.profile.enable_two_factor') }}</a>
            @endif
        </div>
    </section>
</div>
@endsection

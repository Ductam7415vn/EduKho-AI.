@extends('layouts.app')

@section('title', __('messages.profile.password_title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.password_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.profile.password_title') }}</h2>
        <p class="resource-copy">{{ __('messages.profile.password_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.profile.back_to_profile') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="POST" action="{{ route('profile.password.update') }}" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="form-label">{{ __('messages.profile.current_password') }}</label>
                <input type="password" name="current_password" id="current_password" class="form-input @error('current_password') border-red-500 @enderror" required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">{{ __('messages.profile.new_password') }}</label>
                <input type="password" name="password" id="password" class="form-input @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('messages.profile.password_hint') }}</p>
            </div>

            <div>
                <label for="password_confirmation" class="form-label">{{ __('messages.profile.confirm_password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.profile.change_password') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', __('messages.profile.edit_title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.profile.edit_title') }}</h2>
        <p class="resource-copy">{{ __('messages.profile.edit_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.profile.back_to_profile') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="POST" action="{{ route('profile.update') }}" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label">{{ __('messages.profile.name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-input @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label">{{ __('messages.profile.email') }}</label>
                <input type="email" id="email" value="{{ $user->email }}" class="form-input bg-gray-100 dark:bg-gray-800" disabled>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('messages.profile.email_readonly') }}</p>
            </div>

            <div>
                <label for="phone" class="form-label">{{ __('messages.profile.phone') }}</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-input @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('messages.profile.department') }}</label>
                <input type="text" value="{{ $user->department?->name ?? __('messages.profile.not_assigned') }}" class="form-input bg-gray-100 dark:bg-gray-800" disabled>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('messages.profile.department_note') }}</p>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.profile.save_changes') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

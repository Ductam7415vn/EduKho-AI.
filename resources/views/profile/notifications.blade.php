@extends('layouts.app')

@section('title', __('messages.profile.notification_title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.notification_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.profile.notification_title') }}</h2>
        <p class="resource-copy">{{ __('messages.profile.notification_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.profile.back_to_profile') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="POST" action="{{ route('profile.notifications.update') }}" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.profile.notification_borrow') }}</h3>
                <div class="space-y-4">
                    <label class="flex items-start rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                        <input type="checkbox" name="email_borrow_approved" value="1" {{ $settings['email_borrow_approved'] ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="ml-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.profile.borrow_approved') }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.borrow_approved_desc') }}</span>
                        </span>
                    </label>

                    <label class="flex items-start rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                        <input type="checkbox" name="email_borrow_rejected" value="1" {{ $settings['email_borrow_rejected'] ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="ml-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.profile.borrow_rejected') }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.borrow_rejected_desc') }}</span>
                        </span>
                    </label>

                    <label class="flex items-start rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                        <input type="checkbox" name="email_borrow_overdue" value="1" {{ $settings['email_borrow_overdue'] ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="ml-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.profile.borrow_overdue') }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.borrow_overdue_desc') }}</span>
                        </span>
                    </label>

                    <label class="flex items-start rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                        <input type="checkbox" name="email_borrow_reminder" value="1" {{ $settings['email_borrow_reminder'] ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="ml-3">
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.profile.borrow_reminder') }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.borrow_reminder_desc') }}</span>
                        </span>
                    </label>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="pb-2">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.profile.notification_admin') }}</h3>
                <label class="flex items-start rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                    <input type="checkbox" name="email_pending_approval" value="1" {{ $settings['email_pending_approval'] ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                    <span class="ml-3">
                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.profile.pending_approval') }}</span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400">{{ __('messages.profile.pending_approval_desc') }}</span>
                    </span>
                </label>
            </div>
            @endif

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('profile.show') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.profile.save_settings') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

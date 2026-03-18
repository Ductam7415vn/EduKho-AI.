@extends('layouts.app')

@section('title', __('messages.activity_log.detail_title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.activity_log.detail_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.activity_log.detail_title') }}</h2>
        <p class="resource-copy">{{ __('messages.activity_log.detail_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('admin.activity-logs.index') }}" class="btn-secondary">{{ __('messages.activity_log.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.time') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $activityLog->created_at->format('d/m/Y H:i:s') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.user') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($activityLog->user)
                            {{ $activityLog->user->name }} ({{ $activityLog->user->email }})
                        @else
                            {{ __('messages.activity_log.guest') }}
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.action') }}</dt>
                    <dd class="mt-1">
                        @php
                            $actionColors = [
                                'login' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'logout' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                'borrow_create' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
                                'borrow_return' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                                'borrow_approve' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'borrow_reject' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
                            ];
                        @endphp
                        <span class="table-pill {{ $actionColors[$activityLog->action] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $activityLog->action }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.subject') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($activityLog->subject_type && $activityLog->subject_id)
                            {{ class_basename($activityLog->subject_type) }} #{{ $activityLog->subject_id }}
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.ip_address') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $activityLog->ip_address ?? '-' }}</dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.user_agent') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 break-all">{{ $activityLog->user_agent ?? '-' }}</dd>
                </div>

                @if($activityLog->properties)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.activity_log.extra_data') }}</dt>
                        <dd class="mt-2">
                            <pre class="rounded-xl p-4 text-sm overflow-x-auto border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </section>
</div>
@endsection

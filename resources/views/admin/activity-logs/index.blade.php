@extends('layouts.app')

@section('title', __('messages.activity_log.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.nav.admin') }}</p>
        <h2 class="resource-title">{{ __('messages.activity_log.title') }}</h2>
    </section>

    <!-- Filters -->
    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="user_id" class="form-label">{{ __('messages.activity_log.user') }}</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">{{ __('messages.activity_log.all') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="action" class="form-label">{{ __('messages.activity_log.action') }}</label>
                <select name="action" id="action" class="form-select">
                    <option value="">{{ __('messages.activity_log.all') }}</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ __('messages.activity_log.actions.' . $action) ?? $action }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date_from" class="form-label">{{ __('messages.activity_log.from_date') }}</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>

            <div>
                <label for="date_to" class="form-label">{{ __('messages.activity_log.to_date') }}</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-primary">{{ __('messages.equipment.filter') }}</button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn-secondary">{{ __('messages.activity_log.clear_filter') }}</a>
            </div>
        </form>
    </section>

    <!-- Activity Logs Table -->
    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.activity_log.title') }}</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.activity_log.time') }}</th>
                        <th>{{ __('messages.activity_log.user') }}</th>
                        <th>{{ __('messages.activity_log.action') }}</th>
                        <th>{{ __('messages.activity_log.subject') }}</th>
                        <th>{{ __('messages.activity_log.ip') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-gray-600 dark:text-gray-300">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $log->user?->name ?? __('messages.activity_log.guest') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->user?->email }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'login' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                        'logout' => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300',
                                        'borrow_create' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                                        'borrow_return' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300',
                                        'borrow_approve' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                        'borrow_reject' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300',
                                    ];
                                @endphp
                                <span class="table-pill {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300' }}">
                                    {{ __('messages.activity_log.actions.' . $log->action) ?? $log->action }}
                                </span>
                            </td>
                            <td class="text-gray-600 dark:text-gray-300">
                                @if($log->subject_type && $log->subject_id)
                                    {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-gray-600 dark:text-gray-300">
                                {{ $log->ip_address }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.activity-logs.show', $log) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                                    {{ __('messages.activity_log.details') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                {{ __('messages.activity_log.no_logs') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
</div>
@endsection

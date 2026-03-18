@extends('layouts.app')

@section('title', __('messages.scheduled_report.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.report.title') }}</p>
        <h2 class="resource-title">{{ __('messages.scheduled_report.title') }}</h2>
        <div class="resource-actions">
            <a href="{{ route('admin.scheduled-reports.create') }}" class="btn-primary">{{ __('messages.scheduled_report.create') }}</a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.scheduled_report.name') }}</th>
                        <th>{{ __('messages.scheduled_report.report_type') }}</th>
                        <th>{{ __('messages.scheduled_report.frequency') }}</th>
                        <th>{{ __('messages.scheduled_report.next_run') }}</th>
                        <th>{{ __('messages.scheduled_report.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reports as $report)
                    <tr>
                        <td>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $report->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ count($report->recipients) }} {{ __('messages.scheduled_report.recipients') }}</div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $report->report_type_label }}
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $report->frequency_label }} - {{ $report->send_time }}
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $report->next_run_at ? $report->next_run_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            <span class="table-pill {{ $report->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                {{ $report->is_active ? __('messages.scheduled_report.active') : __('messages.scheduled_report.paused') }}
                            </span>
                        </td>
                        <td class="text-right space-x-2">
                            <form action="{{ route('admin.scheduled-reports.toggle', $report) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold {{ $report->is_active ? 'text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300' : 'text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300' }}">
                                    {{ $report->is_active ? __('messages.scheduled_report.pause') : __('messages.scheduled_report.activate') }}
                                </button>
                            </form>
                            <a href="{{ route('admin.scheduled-reports.edit', $report) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.scheduled_report.edit') }}</a>
                            <form action="{{ route('admin.scheduled-reports.destroy', $report) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300"
                                        onclick="return confirm('{{ __('messages.scheduled_report.delete_confirm') }}')">{{ __('messages.scheduled_report.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2">{{ __('messages.scheduled_report.no_reports') }}</p>
                            <a href="{{ route('admin.scheduled-reports.create') }}" class="font-semibold text-teal-700 hover:underline dark:text-teal-300 mt-2 inline-block">{{ __('messages.scheduled_report.create') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="mt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection

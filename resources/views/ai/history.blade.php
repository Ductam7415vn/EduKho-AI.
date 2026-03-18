@extends('layouts.app')

@section('title', __('messages.ai.history_title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.ai.history_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.ai.history_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.ai.history_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.ai.total_records') }}: {{ number_format($logs->total()) }}</span>
            <span class="meta-chip">{{ __('messages.ai.page') }}: {{ $logs->currentPage() }}/{{ $logs->lastPage() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('ai.chat') }}" class="btn-primary">{{ __('messages.ai.new_chat') }}</a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.ai.time') }}</th>
                        <th>{{ __('messages.ai.request') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.ai.processing_time') }}</th>
                        <th>{{ __('messages.ai.borrow_record') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="font-medium text-gray-900 dark:text-gray-100 max-w-md truncate">{{ Str::limit($log->user_message, 100) }}</td>
                        <td>
                            <span class="table-pill
                                @if($log->status === 'success') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                @elseif($log->status === 'fallback') bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300
                                @else bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300
                                @endif">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td>{{ $log->response_time_ms ? $log->response_time_ms . 'ms' : '-' }}</td>
                        <td>
                            @if($log->borrow_record_id)
                            <a href="{{ route('borrow.show', $log->borrow_record_id) }}" class="font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                                #{{ $log->borrow_record_id }}
                            </a>
                            @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">{{ __('messages.ai.no_history') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection

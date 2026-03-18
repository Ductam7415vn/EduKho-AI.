@extends('layouts.app')

@section('title', __('messages.report.mau02_title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.report.mau02_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.report.mau02_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.report.mau02_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.report.total_records') }}: {{ number_format($records->total()) }}</span>
            @if(request('status'))
            <span class="meta-chip">{{ __('messages.status') }}: {{ request('status') }}</span>
            @endif
            <span class="meta-chip">{{ __('messages.report.page') }}: {{ $records->currentPage() }}/{{ $records->lastPage() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.reports.export.mau02', request()->all()) }}" class="btn-success">{{ __('messages.report.export_excel') }}</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.reports.borrow-tracking') }}" method="GET" class="flex flex-wrap gap-3">
            <input type="date" name="from" value="{{ request('from', now()->startOfMonth()->format('Y-m-d')) }}" class="form-input w-auto">
            <input type="date" name="to" value="{{ request('to', now()->format('Y-m-d')) }}" class="form-input w-auto">
            <select name="status" class="form-select w-auto min-w-[180px]">
                <option value="">{{ __('messages.report.all_status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.borrow.borrowed') }}</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>{{ __('messages.borrow.returned') }}</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('messages.borrow.overdue') }}</option>
            </select>
            <button type="submit" class="btn-secondary">{{ __('messages.report.filter') }}</button>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.report.stt') }}</th>
                        <th>{{ __('messages.borrow.borrow_date') }}</th>
                        <th>{{ __('messages.borrow.teacher') }}</th>
                        <th>{{ __('messages.equipment.title') }}</th>
                        <th>{{ __('messages.report.class_period') }}</th>
                        <th>{{ __('messages.report.deadline') }}</th>
                        <th>{{ __('messages.report.return_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $index => $record)
                    <tr>
                        <td>{{ $records->firstItem() + $index }}</td>
                        <td>{{ $record->borrow_date->format('d/m/Y') }}</td>
                        <td>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $record->user->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $record->user->department?->name }}</div>
                        </td>
                        <td>
                            @foreach($record->details as $detail)
                            <div>{{ $detail->equipmentItem->equipment->name }}</div>
                            @endforeach
                        </td>
                        <td>{{ $record->class_name }} / T{{ $record->period }}</td>
                        <td>{{ $record->expected_return_date->format('d/m/Y') }}</td>
                        <td>{{ $record->actual_return_date?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <span class="table-pill
                                @if($record->status === 'returned') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                @elseif($record->status === 'overdue') bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300
                                @else bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300
                                @endif">
                                @if($record->status === 'returned') {{ __('messages.borrow.returned') }}
                                @elseif($record->status === 'overdue') {{ __('messages.borrow.overdue') }}
                                @else {{ __('messages.borrow.borrowed') }}
                                @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">{{ __('messages.report.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $records->links() }}
    </div>
</div>
@endsection

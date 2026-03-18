@extends('layouts.app')

@section('title', __('messages.borrow.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.borrow.title') }}</p>
        <h2 class="resource-title">{{ __('messages.borrow.list_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.borrow.list_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.borrow.total') }}: {{ number_format($borrows->total()) }}</span>
            <span class="meta-chip">{{ __('messages.borrow.page') }}: {{ $borrows->currentPage() }}/{{ $borrows->lastPage() }}</span>
            @if(request('status'))
            <span class="meta-chip">{{ __('messages.borrow.filter_status') }}: {{ request('status') }}</span>
            @endif
        </div>
        <div class="resource-actions">
            <a href="{{ route('ical.borrows') }}" class="btn-info">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                iCal
            </a>
            <a href="{{ route('export.borrows') }}" class="btn-success">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                CSV
            </a>
            <a href="{{ route('borrow.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.borrow.create_new') }}
            </a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('borrow.index') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="status" class="form-select w-auto min-w-[180px]">
                <option value="">{{ __('messages.borrow.all_status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.borrow.borrowed') }}</option>
                <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>{{ __('messages.borrow.returned') }}</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('messages.borrow.overdue') }}</option>
            </select>
            <select name="approval_status" class="form-select w-auto min-w-[180px]">
                <option value="">{{ __('messages.borrow.all_approval') }}</option>
                <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>{{ __('messages.borrow.pending') }}</option>
                <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>{{ __('messages.borrow.approved') }}</option>
                <option value="auto_approved" {{ request('approval_status') === 'auto_approved' ? 'selected' : '' }}>{{ __('messages.borrow.auto_approved') }}</option>
                <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>{{ __('messages.borrow.rejected') }}</option>
            </select>
            <button type="submit" class="btn-secondary">{{ __('messages.equipment.filter') }}</button>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 140ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.borrow.code') }}</th>
                        @if(auth()->user()->isAdmin())
                        <th>{{ __('messages.borrow.teacher') }}</th>
                        @endif
                        <th>{{ __('messages.borrow.equipment') }}</th>
                        <th>{{ __('messages.borrow.borrow_date') }}</th>
                        <th>{{ __('messages.borrow.status') }}</th>
                        <th>{{ __('messages.borrow.approval_status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($borrows as $borrow)
                    <tr>
                        <td class="font-semibold text-gray-900 dark:text-white">#{{ $borrow->id }}</td>
                        @if(auth()->user()->isAdmin())
                        <td>{{ $borrow->user->name }}</td>
                        @endif
                        <td>
                            @foreach($borrow->details as $detail)
                            <div class="text-gray-900 dark:text-gray-100">{{ $detail->equipmentItem->equipment->name }}</div>
                            @endforeach
                        </td>
                        <td>
                            <div>{{ $borrow->borrow_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.borrow.period') }} {{ $borrow->period }}</div>
                        </td>
                        <td>
                            <span class="table-pill
                                @if($borrow->status === 'active') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300
                                @elseif($borrow->status === 'returned') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                                @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                                @endif">
                                {{ $borrow->status === 'active' ? __('messages.borrow.borrowed') : ($borrow->status === 'returned' ? __('messages.borrow.returned') : __('messages.borrow.overdue')) }}
                            </span>
                        </td>
                        <td>
                            <span class="table-pill
                                @if($borrow->approval_status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300
                                @elseif(in_array($borrow->approval_status, ['approved', 'auto_approved'])) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                                @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                                @endif">
                                @if($borrow->approval_status === 'pending') {{ __('messages.borrow.pending') }}
                                @elseif($borrow->approval_status === 'auto_approved') {{ __('messages.borrow.auto_approved') }}
                                @elseif($borrow->approval_status === 'approved') {{ __('messages.borrow.approved') }}
                                @else {{ __('messages.borrow.rejected') }}
                                @endif
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('borrow.show', $borrow) }}" class="font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="empty-state">
                            {{ __('messages.borrow.no_records') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $borrows->links() }}
    </div>
</div>
@endsection

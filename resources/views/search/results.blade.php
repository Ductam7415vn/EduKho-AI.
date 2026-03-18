@extends('layouts.app')

@section('title', __('messages.search_page.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.search_page.kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.search_page.headline') }}</h2>
        <p class="resource-copy">
            {{ __('messages.search_page.description') }}
        </p>
        @if($query)
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.search_page.keyword') }}: {{ $query }}</span>
            <span class="meta-chip">{{ __('messages.search_page.equipment_count') }}: {{ $equipments->count() }}</span>
            <span class="meta-chip">{{ __('messages.search_page.borrow_count') }}: {{ $borrows->count() }}</span>
        </div>
        @endif
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-3">
            <div class="relative">
                <input type="text" name="q" placeholder="{{ __('messages.search_page.placeholder') }}" value="{{ $query }}" class="form-input pl-10">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <button type="submit" class="btn-primary">{{ __('messages.search_page.submit') }}</button>
        </form>

        @if($query)
        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
            {{ __('messages.search_page.found') }} <span class="font-semibold">{{ $equipments->count() }}</span> {{ __('messages.search_page.equipment') }} {{ __('messages.search_page.and') }}
            <span class="font-semibold">{{ $borrows->count() }}</span> {{ __('messages.search_page.borrow') }} {{ __('messages.search_page.for') }} "<span class="font-semibold">{{ $query }}</span>".
        </p>
        @endif
    </section>

    @if($query)
        @if($equipments->count() > 0)
        <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.equipment.title') }}</h2>
                <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ $equipments->count() }} {{ __('messages.search_page.results') }}</span>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($equipments as $equipment)
                <a href="{{ route('equipment.show', $equipment) }}" class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $equipment->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('messages.search_page.code') }}: {{ $equipment->base_code }} | {{ $equipment->category_subject }} | {{ __('messages.search_page.grade') }}: {{ $equipment->grade_level }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if($equipment->is_digital)
                            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ __('messages.equipment.type_digital') }}</span>
                            @else
                            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                {{ $equipment->availableCount() }}/{{ $equipment->totalCount() }} {{ __('messages.equipment.available') }}
                            </span>
                            @endif
                            @if($equipment->isHighSecurity())
                            <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.equipment.high_security') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        @if($borrows->count() > 0)
        <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 160ms;">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.borrow.title') }}</h2>
                <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ $borrows->count() }} {{ __('messages.search_page.results') }}</span>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($borrows as $borrow)
                <a href="{{ route('borrow.show', $borrow) }}" class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ __('messages.borrow.record') }} #{{ str_pad($borrow->id, 6, '0', STR_PAD_LEFT) }}
                                @if(auth()->user()->isAdmin())
                                - {{ $borrow->user->name }}
                                @endif
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $borrow->lesson_name ?? __('messages.search_page.no_lesson_name') }} | {{ __('messages.borrow.class') }} {{ $borrow->class_name }} | {{ $borrow->subject }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                {{ __('messages.borrow.borrow_date') }}: {{ $borrow->borrow_date->format('d/m/Y') }} | {{ __('messages.borrow.period') }}: {{ $borrow->period }}
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if($borrow->status === 'active')
                                <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ __('messages.borrow.borrowed') }}</span>
                            @elseif($borrow->status === 'returned')
                                <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ __('messages.borrow.returned') }}</span>
                            @elseif($borrow->status === 'overdue')
                                <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.borrow.overdue') }}</span>
                            @endif

                            @if($borrow->approval_status === 'pending')
                                <span class="table-pill bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">{{ __('messages.borrow.pending') }}</span>
                            @elseif(in_array($borrow->approval_status, ['approved', 'auto_approved']))
                                <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ __('messages.borrow.approved') }}</span>
                            @elseif($borrow->approval_status === 'rejected')
                                <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.borrow.rejected') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        @if($equipments->count() === 0 && $borrows->count() === 0)
        <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
            <div class="card-body empty-state">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.search_page.no_results') }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('messages.search_page.no_match') }} "<span class="font-semibold">{{ $query }}</span>".
                </p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.search_page.try_other') }}</p>
            </div>
        </section>
        @endif
    @else
    <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="card-body empty-state">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.search_page.headline') }}</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ __('messages.search_page.search_help') }}
            </p>
        </div>
    </section>
    @endif
</div>
@endsection

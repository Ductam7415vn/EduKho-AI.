@extends('layouts.app')

@section('title', __('messages.equipment.list'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.equipment.warehouse') }}</p>
        <h2 class="resource-title">{{ __('messages.equipment.list_subtitle') }}</h2>
        <p class="resource-copy">
            {{ __('messages.equipment.list_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.equipment.total_results') }}: {{ number_format($equipments->total()) }}</span>
            <span class="meta-chip">{{ __('messages.equipment.total_subjects') }}: {{ count($subjects) }}</span>
            <span class="meta-chip">{{ __('messages.equipment.page') }}: {{ $equipments->currentPage() }}/{{ $equipments->lastPage() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('export.equipment') }}" class="btn-success">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('messages.equipment.export') }}
            </a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('equipment.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">{{ __('messages.search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.equipment.name') }}..." class="form-input">
            </div>
            <div>
                <label class="form-label">{{ __('messages.equipment.subject') }}</label>
                <select name="subject" class="form-select">
                    <option value="">{{ __('messages.all') }}</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject }}" {{ request('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ __('messages.equipment.type') }}</label>
                <select name="type" class="form-select">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>{{ __('messages.equipment.type_physical') }}</option>
                    <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>{{ __('messages.equipment.type_digital') }}</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary w-full">{{ __('messages.equipment.filter') }}</button>
            </div>
        </form>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 140ms;">
        @forelse($equipments as $equipment)
        <article class="card">
            <div class="p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-display text-lg font-bold text-gray-900 dark:text-white">{{ $equipment->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.equipment.code') }}: {{ $equipment->base_code }}</p>
                    </div>
                    @if($equipment->isHighSecurity())
                    <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.equipment.high_security') }}</span>
                    @endif
                </div>

                <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <p><span class="font-semibold text-gray-800 dark:text-gray-200">{{ __('messages.equipment.subject') }}:</span> {{ $equipment->category_subject }}</p>
                    <p><span class="font-semibold text-gray-800 dark:text-gray-200">{{ __('messages.equipment.grade') }}:</span> {{ $equipment->grade_level }}</p>
                    <p><span class="font-semibold text-gray-800 dark:text-gray-200">{{ __('messages.equipment.unit') }}:</span> {{ $equipment->unit }}</p>
                </div>

                <div class="mt-5 flex items-end justify-between gap-3">
                    <div>
                        <span class="font-display text-3xl font-bold {{ $equipment->isLowStock() ? 'text-rose-600 dark:text-rose-400' : 'text-teal-700 dark:text-teal-300' }}">
                            {{ $equipment->availableCount() }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">/ {{ $equipment->totalCount() }} {{ __('messages.equipment.available') }}</span>
                        @if($equipment->isLowStock())
                        <span class="ml-2 table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.equipment.low_stock') }}</span>
                        @endif
                    </div>
                    <a href="{{ route('equipment.show', $equipment) }}" class="dashboard-section-link">{{ __('messages.equipment.detail') }}</a>
                </div>
            </div>

            @if(!$equipment->is_digital && $equipment->availableCount() > 0)
            <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800/40">
                <a href="{{ route('borrow.create', ['equipment' => $equipment->id]) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                    {{ __('messages.equipment.register_borrow') }}
                </a>
            </div>
            @endif
        </article>
        @empty
        <div class="col-span-full empty-state">
            <p>{{ __('messages.equipment.not_found') }}</p>
        </div>
        @endforelse
    </section>

    <div class="mt-4">
        {{ $equipments->links() }}
    </div>
</div>
@endsection

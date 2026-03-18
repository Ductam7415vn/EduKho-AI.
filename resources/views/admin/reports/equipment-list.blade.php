@extends('layouts.app')

@section('title', __('messages.report.mau01_title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.report.mau01_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.report.mau01_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.report.mau01_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.report.total_types') }}: {{ number_format($equipments->count()) }}</span>
            @if(request('subject'))
            <span class="meta-chip">{{ __('messages.report.filtering') }}: {{ request('subject') }}</span>
            @endif
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.reports.export.mau01') }}" class="btn-success">{{ __('messages.report.export_excel') }}</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.reports.equipment-list') }}" method="GET" class="flex flex-wrap gap-3">
            <select name="subject" class="form-select w-auto min-w-[220px]">
                <option value="">{{ __('messages.report.all_subjects') }}</option>
                @foreach($equipments->pluck('category_subject')->unique() as $subject)
                <option value="{{ $subject }}" {{ request('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">{{ __('messages.report.filter') }}</button>
        </form>
    </section>

    @forelse($equipments->groupBy('category_subject') as $subject => $items)
    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ $subject }}</h3>
            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ $items->count() }} {{ __('messages.equipment.title') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.report.stt') }}</th>
                        <th>{{ __('messages.equipment.code') }}</th>
                        <th>{{ __('messages.equipment.name') }}</th>
                        <th>{{ __('messages.report.unit_short') }}</th>
                        <th>{{ __('messages.report.total') }}</th>
                        <th>{{ __('messages.report.ready') }}</th>
                        <th>{{ __('messages.report.borrowed') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($items as $index => $eq)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-mono text-gray-700 dark:text-gray-300">{{ $eq->base_code }}</td>
                        <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $eq->name }}</td>
                        <td>{{ $eq->unit }}</td>
                        <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $eq->items->count() }}</td>
                        <td>
                            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                {{ $eq->items->where('status', 'available')->count() }}
                            </span>
                        </td>
                        <td>
                            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">
                                {{ $eq->items->where('status', 'borrowed')->count() }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @empty
    <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="card-body empty-state">
            <p>{{ __('messages.report.no_equipment_data') }}</p>
        </div>
    </section>
    @endforelse
</div>
@endsection

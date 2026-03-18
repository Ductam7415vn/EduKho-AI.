@extends('layouts.app')

@section('title', __('messages.report.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.report.summary') }}</p>
        <h2 class="resource-title">{{ __('messages.report.title_full') }}</h2>
        <p class="resource-copy">
            {{ __('messages.report.description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.report.equipment_types') }}: {{ number_format($stats['total_equipment_types']) }}</span>
            <span class="meta-chip">{{ __('messages.report.total_items') }}: {{ number_format($stats['total_items']) }}</span>
            <span class="meta-chip">{{ __('messages.report.currently_borrowed') }}: {{ number_format($stats['total_borrowed']) }}</span>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 animate-fade-in-up" style="animation-delay: 80ms;">
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.equipment_types') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-teal-700 dark:text-teal-300">{{ $stats['total_equipment_types'] }}</p>
        </article>
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.total_items') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-cyan-700 dark:text-cyan-300">{{ $stats['total_items'] }}</p>
        </article>
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.currently_borrowed') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-indigo-700 dark:text-indigo-300">{{ $stats['total_borrowed'] }}</p>
        </article>
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.this_month') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['total_borrows_this_month'] }}</p>
        </article>
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.overdue') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-rose-700 dark:text-rose-300">{{ $stats['overdue_count'] }}</p>
        </article>
        <article class="stat-card">
            <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.report.high_security') }}</p>
            <p class="mt-3 font-display text-3xl font-bold text-amber-700 dark:text-amber-300">{{ $stats['high_security_items'] }}</p>
        </article>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 140ms;">
        <article class="card">
            <div class="card-header">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.report.equipment_report') }}</h3>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('admin.reports.equipment-list') }}" class="dashboard-list-row">
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ __('messages.report.equipment_list_by_subject') }}</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admin.reports.export.mau01') }}" class="dashboard-list-row">
                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ __('messages.report.export_excel_mau01') }}</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
        </article>

        <article class="card">
            <div class="card-header">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.report.borrow_report') }}</h3>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('admin.reports.borrow-tracking') }}" class="dashboard-list-row">
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ __('messages.report.borrow_tracking') }}</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('admin.reports.export.mau02') }}" class="dashboard-list-row">
                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">{{ __('messages.report.export_excel_mau02') }}</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
        </article>
    </section>
</div>
@endsection

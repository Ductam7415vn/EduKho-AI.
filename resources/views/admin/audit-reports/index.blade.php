@extends('layouts.app')

@section('title', __('messages.audit.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.report.title') }}</p>
        <h2 class="resource-title">{{ __('messages.audit.title') }}</h2>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 80ms;">
        <!-- Inventory Audit -->
        <a href="{{ route('admin.audit-reports.inventory') }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="bg-cyan-100 dark:bg-cyan-900/40 rounded-lg p-3">
                    <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.audit.inventory') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.audit.inventory_desc') }}</p>
                </div>
            </div>
        </a>

        <!-- Borrow Audit -->
        <a href="{{ route('admin.audit-reports.borrow') }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="bg-emerald-100 dark:bg-emerald-900/40 rounded-lg p-3">
                    <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.audit.borrow_audit') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.audit.borrow_desc') }}</p>
                </div>
            </div>
        </a>

        <!-- Maintenance Audit -->
        <a href="{{ route('admin.audit-reports.maintenance') }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="bg-amber-100 dark:bg-amber-900/40 rounded-lg p-3">
                    <svg class="w-8 h-8 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.audit.maintenance') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.audit.maintenance_desc') }}</p>
                </div>
            </div>
        </a>

        <!-- Activity Audit -->
        <a href="{{ route('admin.audit-reports.activity') }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="bg-violet-100 dark:bg-violet-900/40 rounded-lg p-3">
                    <svg class="w-8 h-8 text-violet-600 dark:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.audit.activity') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.audit.activity_desc') }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="rounded-xl border border-cyan-200 bg-cyan-50/90 dark:border-cyan-800/60 dark:bg-cyan-900/20 p-4 animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="flex">
            <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-cyan-800 dark:text-cyan-200">{{ __('messages.audit.guide_title') }}</h3>
                <p class="mt-1 text-sm text-cyan-700 dark:text-cyan-300">
                    {{ __('messages.audit.guide_description') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

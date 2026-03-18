@extends('layouts.app')

@section('title', __('messages.damage_report.title'))

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.damage_report.title') }}</h2>
        <a href="{{ route('admin.damage-reports.create') }}" class="btn-primary">{{ __('messages.damage_report.create') }}</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-yellow-600 dark:text-yellow-400">{{ __('messages.damage_report.pending') }}</p>
                    <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-green-600 dark:text-green-400">{{ __('messages.damage_report.resolved') }}</p>
                    <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $stats['resolved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-red-600 dark:text-red-400">{{ __('messages.damage_report.total_estimated') }}</p>
                    <p class="text-2xl font-bold text-red-800 dark:text-red-200">{{ number_format($stats['total_cost']) }} VND</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.status') }}</label>
                <select name="status" class="form-input text-sm" onchange="this.form.submit()">
                    <option value="">{{ __('messages.damage_report.all') }}</option>
                    <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>{{ __('messages.damage_report.reported') }}</option>
                    <option value="investigating" {{ request('status') === 'investigating' ? 'selected' : '' }}>{{ __('messages.damage_report.investigating') }}</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>{{ __('messages.damage_report.resolved') }}</option>
                    <option value="written_off" {{ request('status') === 'written_off' ? 'selected' : '' }}>{{ __('messages.damage_report.written_off') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.damage_report.severity') }}</label>
                <select name="severity" class="form-input text-sm" onchange="this.form.submit()">
                    <option value="">{{ __('messages.damage_report.all') }}</option>
                    <option value="minor" {{ request('severity') === 'minor' ? 'selected' : '' }}>{{ __('messages.damage_report.minor') }}</option>
                    <option value="moderate" {{ request('severity') === 'moderate' ? 'selected' : '' }}>{{ __('messages.damage_report.moderate') }}</option>
                    <option value="severe" {{ request('severity') === 'severe' ? 'selected' : '' }}>{{ __('messages.damage_report.severe') }}</option>
                    <option value="total_loss" {{ request('severity') === 'total_loss' ? 'selected' : '' }}>{{ __('messages.damage_report.total_loss') }}</option>
                </select>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.damage_report.code') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.damage_report.equipment') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.damage_report.incident_date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.damage_report.severity') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.damage_report.estimated_cost') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($reports as $report)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $report->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report->equipmentItem->equipment->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $report->equipmentItem->specific_code }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $report->incident_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($report->severity === 'minor') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                            @elseif($report->severity === 'moderate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                            @elseif($report->severity === 'severe') bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300
                            @else bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300
                            @endif">
                            {{ $report->severity_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $report->estimated_cost ? number_format($report->estimated_cost) . ' VND' : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($report->status === 'reported') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                            @elseif($report->status === 'investigating') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                            @elseif($report->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endif">
                            {{ $report->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.damage-reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">{{ __('messages.damage_report.view_detail') }}</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2">{{ __('messages.damage_report.no_reports') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $reports->links() }}
</div>
@endsection

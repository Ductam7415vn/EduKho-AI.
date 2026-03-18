@extends('layouts.app')

@section('title', __('messages.damage_report.title') . ' #' . $damageReport->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.damage_report.title') }} #{{ $damageReport->id }}</h2>
            <p class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.created_at') }} {{ $damageReport->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="flex space-x-2">
            @if($damageReport->status === 'reported')
            <form action="{{ route('admin.damage-reports.investigate', $damageReport) }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary">{{ __('messages.damage_report.start_investigating') }}</button>
            </form>
            @endif
            <a href="{{ route('admin.damage-reports.index') }}" class="btn-secondary">{{ __('messages.damage_report.back') }}</a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="rounded-lg p-4 border
        @if($damageReport->status === 'reported') bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
        @elseif($damageReport->status === 'investigating') bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800
        @elseif($damageReport->status === 'resolved') bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800
        @else bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700
        @endif">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @if($damageReport->status === 'reported')
                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-yellow-800 dark:text-yellow-200 font-medium">{{ __('messages.damage_report.reported_waiting') }}</span>
                @elseif($damageReport->status === 'investigating')
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="text-blue-800 dark:text-blue-200 font-medium">{{ __('messages.damage_report.investigating') }}</span>
                @elseif($damageReport->status === 'resolved')
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800 dark:text-green-200 font-medium">{{ __('messages.damage_report.resolved') }} - {{ $damageReport->resolved_at->format('d/m/Y') }}</span>
                @else
                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ __('messages.damage_report.written_off') }} - {{ $damageReport->resolved_at->format('d/m/Y') }}</span>
                @endif
            </div>
            <span class="px-3 py-1 text-sm rounded-full
                @if($damageReport->severity === 'minor') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                @elseif($damageReport->severity === 'moderate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                @elseif($damageReport->severity === 'severe') bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-300
                @else bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300
                @endif">
                {{ $damageReport->severity_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Equipment Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">{{ __('messages.damage_report.equipment') }}</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.equipment_name') }}:</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->equipmentItem->equipment->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.item_code') }}:</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->equipmentItem->specific_code }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.current_status') }}:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($damageReport->equipmentItem->status === 'available') bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                            @elseif($damageReport->equipmentItem->status === 'maintenance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                            @else bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300
                            @endif">
                            {{ ucfirst($damageReport->equipmentItem->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
            <a href="{{ route('equipment.show', $damageReport->equipmentItem->equipment) }}"
               class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mt-4 inline-block">{{ __('messages.damage_report.view_equipment') }}</a>
        </div>

        <!-- Incident Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">{{ __('messages.damage_report.incident_info') }}</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.incident_date') }}:</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->incident_date->format('d/m/Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.reporter') }}:</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->reporter->name }}</dd>
                </div>
                @if($damageReport->estimated_cost)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.estimated_cost') }}:</dt>
                    <dd class="font-medium text-red-600 dark:text-red-400">{{ number_format($damageReport->estimated_cost) }} VND</dd>
                </div>
                @endif
                @if($damageReport->borrowRecord)
                <div class="flex justify-between">
                    <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.related_borrow') }}:</dt>
                    <dd>
                        <a href="{{ route('borrow.show', $damageReport->borrowRecord) }}"
                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">#{{ $damageReport->borrowRecord->id }}</a>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">({{ $damageReport->borrowRecord->user->name }})</span>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">{{ __('messages.damage_report.description') }}</h3>
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $damageReport->description }}</p>

        @if($damageReport->cause)
        <div class="mt-4 pt-4 border-t dark:border-gray-700">
            <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ __('messages.damage_report.cause') }}:</h4>
            <p class="text-gray-700 dark:text-gray-300">{{ $damageReport->cause }}</p>
        </div>
        @endif
    </div>

    <!-- Resolution (if resolved) -->
    @if($damageReport->isResolved())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">{{ __('messages.damage_report.resolution_result') }}</h3>
        <dl class="space-y-3">
            <div>
                <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.resolver') }}:</dt>
                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->resolver?->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.damage_report.resolved_date') }}:</dt>
                <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $damageReport->resolved_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.damage_report.resolution_notes') }}:</dt>
                <dd class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $damageReport->resolution_notes }}</dd>
            </div>
        </dl>
    </div>
    @endif

    <!-- Resolution Form (if pending) -->
    @if($damageReport->isPending())
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">{{ __('messages.damage_report.handle_report') }}</h3>
        <form action="{{ route('admin.damage-reports.resolve', $damageReport) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="resolution_type" class="form-label">{{ __('messages.damage_report.resolution_type') }} <span class="text-red-500">*</span></label>
                <select name="resolution_type" id="resolution_type" required class="form-input">
                    <option value="resolved">{{ __('messages.damage_report.resolved_type') }}</option>
                    <option value="written_off">{{ __('messages.damage_report.written_off_type') }}</option>
                </select>
            </div>

            <div>
                <label for="resolution_notes" class="form-label">{{ __('messages.damage_report.resolution_notes') }} <span class="text-red-500">*</span></label>
                <textarea name="resolution_notes" id="resolution_notes" rows="3" required class="form-input"
                          placeholder="{{ __('messages.damage_report.resolution_notes_placeholder') }}"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">{{ __('messages.damage_report.complete_resolution') }}</button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', __('messages.inventory.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.nav.admin') }}</p>
        <h2 class="resource-title">{{ __('messages.inventory.history') }}</h2>
        <div class="resource-actions">
            <a href="{{ route('admin.inventory.increase') }}" class="btn-success">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.inventory.increase') }}
            </a>
            <a href="{{ route('admin.inventory.decrease') }}" class="btn-danger">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
                {{ __('messages.inventory.decrease') }}
            </a>
        </div>
    </section>

    <!-- Filters -->
    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="equipment_id" class="form-select w-auto">
                <option value="">{{ __('messages.inventory.all_equipment') }}</option>
                @foreach($equipments as $eq)
                <option value="{{ $eq->id }}" {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>{{ $eq->name }}</option>
                @endforeach
            </select>
            <select name="type" class="form-select w-auto">
                <option value="">{{ __('messages.inventory.all_types') }}</option>
                <option value="increase" {{ request('type') === 'increase' ? 'selected' : '' }}>{{ __('messages.inventory.increase') }}</option>
                <option value="decrease" {{ request('type') === 'decrease' ? 'selected' : '' }}>{{ __('messages.inventory.decrease') }}</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-auto" placeholder="{{ __('messages.inventory.from_date') }}">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-auto" placeholder="{{ __('messages.inventory.to_date') }}">
            <button type="submit" class="btn-secondary">{{ __('messages.equipment.filter') }}</button>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.inventory.action_date') }}</th>
                        <th>{{ __('messages.equipment.title') }}</th>
                        <th>{{ __('messages.equipment.type') }}</th>
                        <th>{{ __('messages.inventory.qty') }}</th>
                        <th>{{ __('messages.inventory.reason') }}</th>
                        <th>{{ __('messages.inventory.performer') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-gray-600 dark:text-gray-300">{{ $log->action_date->format('d/m/Y') }}</td>
                        <td class="font-semibold text-gray-900 dark:text-white">{{ $log->equipment->name }}</td>
                        <td>
                            <span class="table-pill {{ $log->type === 'increase' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' }}">
                                {{ $log->type === 'increase' ? __('messages.inventory.increase') : __('messages.inventory.decrease') }}
                            </span>
                        </td>
                        <td class="font-semibold {{ $log->type === 'increase' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $log->type === 'increase' ? '+' : '-' }}{{ $log->quantity }}
                        </td>
                        <td class="text-gray-600 dark:text-gray-300">{{ $log->reason }}</td>
                        <td class="text-gray-600 dark:text-gray-300">{{ $log->performer?->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">{{ __('messages.inventory.no_history') }}</td>
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

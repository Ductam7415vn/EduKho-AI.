@extends('layouts.app')

@section('title', __('messages.transfer.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.inventory.title') }}</p>
        <h2 class="resource-title">{{ __('messages.transfer.title') }}</h2>
        <div class="resource-actions">
            <a href="{{ route('admin.transfers.create') }}" class="btn-primary">{{ __('messages.transfer.create') }}</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="GET" class="flex flex-wrap gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.transfer.from_date') }}</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.transfer.to_date') }}</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-secondary">{{ __('messages.transfer.filter') }}</button>
            </div>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.transfer.equipment') }}</th>
                        <th>{{ __('messages.transfer.from_room') }}</th>
                        <th>{{ __('messages.transfer.to_room') }}</th>
                        <th>{{ __('messages.transfer.transfer_date') }}</th>
                        <th>{{ __('messages.transfer.transferred_by') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transfers as $transfer)
                    <tr>
                        <td>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $transfer->equipmentItem->equipment->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transfer->equipmentItem->specific_code }}</div>
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $transfer->fromRoom?->name ?? __('messages.transfer.not_assigned') }}
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $transfer->toRoom?->name ?? __('messages.transfer.not_assigned') }}
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $transfer->transfer_date->format('d/m/Y') }}
                        </td>
                        <td class="text-gray-500 dark:text-gray-400">
                            {{ $transfer->transferredBy->name }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.transfers.show', $transfer) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.transfer.detail') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <p class="mt-2">{{ __('messages.transfer.no_transfers') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="mt-4">
        {{ $transfers->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $room->name)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.room.detail') }}</p>
        <h2 class="resource-title">{{ $room->name }}</h2>
        <p class="resource-copy">{{ $room->location }}</p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.room.type') }}: {{ $room->type === 'warehouse' ? __('messages.room.warehouse') : __('messages.room.lab') }}</span>
            <span class="meta-chip">{{ __('messages.equipment.title') }}: {{ $room->equipmentItems->count() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('{{ __('messages.room.delete_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">{{ __('messages.delete') }}</button>
            </form>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 80ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.room.info') }}</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">{{ __('messages.room.type') }}:</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ $room->type === 'warehouse' ? __('messages.room.warehouse') : __('messages.room.lab') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">{{ __('messages.room.manager') }}:</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ $room->manager?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">{{ __('messages.room.capacity') }}:</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ $room->capacity ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">{{ __('messages.equipment.title') }}:</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ $room->equipmentItems->count() }}</dd></div>
                </dl>
            </div>
        </article>

        <article class="lg:col-span-2 data-table-wrap">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.room.equipment_in_room') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.equipment.code') }}</th>
                            <th>{{ __('messages.equipment.name') }}</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($room->equipmentItems as $item)
                        <tr>
                            <td>{{ $item->specific_code }}</td>
                            <td class="font-medium text-gray-900 dark:text-gray-100">{{ $item->equipment->name }}</td>
                            <td>
                                <span class="table-pill
                                    @if($item->status === 'available') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                    @elseif($item->status === 'borrowed') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300
                                    @else bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                    @if($item->status === 'available') {{ __('messages.equipment.ready') }}
                                    @elseif($item->status === 'borrowed') {{ __('messages.equipment.borrowed') }}
                                    @elseif($item->status === 'maintenance') {{ __('messages.equipment.maintenance') }}
                                    @else {{ ucfirst($item->status) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="empty-state">{{ __('messages.room.no_equipment') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</div>
@endsection

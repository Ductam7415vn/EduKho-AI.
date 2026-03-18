@extends('layouts.app')

@section('title', __('messages.room.title'))

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.room.title') }}</h2>
        <a href="{{ route('admin.rooms.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            {{ __('messages.room.add') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ $room->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $room->location }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $room->type === 'warehouse' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ $room->type === 'warehouse' ? __('messages.room.warehouse') : __('messages.room.practice_room') }}
                    </span>
                </div>

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.room.manager') }}:</span>
                        <span class="font-medium">{{ $room->manager?->name ?? __('messages.room.not_assigned') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.room.equipment_count') }}:</span>
                        <span class="font-medium">{{ $room->equipment_items_count }}</span>
                    </div>
                    @if($room->capacity)
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.room.capacity') }}:</span>
                        <span class="font-medium">{{ $room->capacity }} {{ __('messages.room.people') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-2">
                <a href="{{ route('admin.rooms.show', $room) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">{{ __('messages.view') }}</a>
                <a href="{{ route('admin.rooms.edit', $room) }}" class="text-gray-600 hover:text-gray-900 text-sm">{{ __('messages.edit') }}</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

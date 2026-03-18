@extends('layouts.app')

@section('title', __('messages.inventory.increase'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.inventory.management') }}</p>
        <h2 class="resource-title">{{ __('messages.inventory.increase_equipment') }}</h2>
        <p class="resource-copy">{{ __('messages.inventory.increase_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">{{ __('messages.inventory.back_to_history') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="card-body space-y-6">
            @csrf
            <input type="hidden" name="type" value="increase">

            <div>
                <label class="form-label">{{ __('messages.equipment.title') }} <span class="text-red-500">*</span></label>
                <select name="equipment_id" required class="form-select">
                    <option value="">{{ __('messages.inventory.select_equipment') }}</option>
                    @foreach($equipments as $eq)
                    <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>{{ $eq->name }} ({{ __('messages.inventory.current_have') }}: {{ $eq->totalCount() }})</option>
                    @endforeach
                </select>
                @error('equipment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.inventory.quantity_increase') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="form-input">
                    @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.inventory.import_to_room') }} <span class="text-red-500">*</span></label>
                    <select name="room_id" required class="form-select">
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">{{ __('messages.inventory.reason') }} <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="2" required class="form-input" placeholder="{{ __('messages.inventory.reason_placeholder_increase') }}">{{ old('reason') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.inventory.import_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="action_date" value="{{ old('action_date', now()->format('Y-m-d')) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.inventory.document_ref') }}</label>
                    <input type="text" name="document_ref" value="{{ old('document_ref') }}" class="form-input" placeholder="{{ __('messages.inventory.document_placeholder') }}">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-success">{{ __('messages.inventory.increase') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

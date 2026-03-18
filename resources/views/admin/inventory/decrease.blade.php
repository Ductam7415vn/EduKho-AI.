@extends('layouts.app')

@section('title', __('messages.inventory.decrease'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-rose-50 dark:bg-rose-900/20">
            <h2 class="text-xl font-semibold text-rose-800 dark:text-rose-200">{{ __('messages.inventory.decrease_equipment') }}</h2>
            <p class="text-sm text-rose-600 dark:text-rose-300">{{ __('messages.inventory.decrease_warning') }}</p>
        </div>
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <input type="hidden" name="type" value="decrease">

            <div>
                <label class="form-label">{{ __('messages.equipment.title') }} <span class="text-red-500">*</span></label>
                <select name="equipment_id" required class="form-select">
                    <option value="">{{ __('messages.inventory.select_equipment') }}</option>
                    @foreach($equipments as $eq)
                    <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>{{ $eq->name }} ({{ __('messages.inventory.available') }}: {{ $eq->available_count }})</option>
                    @endforeach
                </select>
                @error('equipment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">{{ __('messages.inventory.quantity_decrease') }} <span class="text-red-500">*</span></label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="form-input">
                @error('quantity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">{{ __('messages.inventory.reason') }} <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="2" required class="form-input" placeholder="{{ __('messages.inventory.reason_placeholder_decrease') }}">{{ old('reason') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.inventory.decrease_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="action_date" value="{{ old('action_date', now()->format('Y-m-d')) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.inventory.document_ref') }}</label>
                    <input type="text" name="document_ref" value="{{ old('document_ref') }}" class="form-input" placeholder="{{ __('messages.inventory.document_placeholder') }}">
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-danger">{{ __('messages.inventory.decrease') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

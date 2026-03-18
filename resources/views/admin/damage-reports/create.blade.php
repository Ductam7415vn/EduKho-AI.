@extends('layouts.app')

@section('title', __('messages.damage_report.create'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('messages.damage_report.create') }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.damage-reports.store') }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="equipment_item_id" class="form-label">{{ __('messages.damage_report.equipment') }} <span class="text-red-500">*</span></label>
                <select name="equipment_item_id" id="equipment_item_id" required class="form-input">
                    <option value="">{{ __('messages.damage_report.select_equipment') }}</option>
                    @foreach($equipmentItems as $item)
                    <option value="{{ $item->id }}" {{ (old('equipment_item_id') ?? $selectedItem?->id) == $item->id ? 'selected' : '' }}>
                        {{ $item->specific_code }} - {{ $item->equipment->name }} ({{ ucfirst($item->status) }})
                    </option>
                    @endforeach
                </select>
                @error('equipment_item_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="incident_date" class="form-label">{{ __('messages.damage_report.incident_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="incident_date" id="incident_date"
                           value="{{ old('incident_date', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}" required class="form-input">
                    @error('incident_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="severity" class="form-label">{{ __('messages.damage_report.severity') }} <span class="text-red-500">*</span></label>
                    <select name="severity" id="severity" required class="form-input">
                        <option value="minor" {{ old('severity') === 'minor' ? 'selected' : '' }}>{{ __('messages.damage_report.minor_desc') }}</option>
                        <option value="moderate" {{ old('severity') === 'moderate' ? 'selected' : '' }}>{{ __('messages.damage_report.moderate_desc') }}</option>
                        <option value="severe" {{ old('severity') === 'severe' ? 'selected' : '' }}>{{ __('messages.damage_report.severe_desc') }}</option>
                        <option value="total_loss" {{ old('severity') === 'total_loss' ? 'selected' : '' }}>{{ __('messages.damage_report.total_loss_desc') }}</option>
                    </select>
                    @error('severity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="form-label">{{ __('messages.damage_report.description') }} <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="4" required class="form-input"
                          placeholder="{{ __('messages.damage_report.description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cause" class="form-label">{{ __('messages.damage_report.cause') }}</label>
                <textarea name="cause" id="cause" rows="2" class="form-input"
                          placeholder="{{ __('messages.damage_report.cause_placeholder') }}">{{ old('cause') }}</textarea>
                @error('cause')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="estimated_cost" class="form-label">{{ __('messages.damage_report.estimated_cost') }} (VND)</label>
                <input type="number" name="estimated_cost" id="estimated_cost"
                       value="{{ old('estimated_cost') }}" min="0" class="form-input"
                       placeholder="{{ __('messages.damage_report.cost_placeholder') }}">
                @error('estimated_cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="borrow_record_id" class="form-label">{{ __('messages.damage_report.related_borrow') }}</label>
                <input type="number" name="borrow_record_id" id="borrow_record_id"
                       value="{{ old('borrow_record_id') }}" class="form-input"
                       placeholder="{{ __('messages.damage_report.related_borrow_placeholder') }}">
                @error('borrow_record_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.damage-reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.damage_report.create_report') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

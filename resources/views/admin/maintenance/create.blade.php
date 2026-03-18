@extends('layouts.app')

@section('title', __('messages.maintenance.schedule'))

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.maintenance.create') }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.maintenance.store') }}" class="p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="equipment_item_id" class="form-label">{{ __('messages.maintenance.equipment') }}</label>
                    <select name="equipment_item_id" id="equipment_item_id" required class="form-select @error('equipment_item_id') border-red-500 @enderror">
                        <option value="">{{ __('messages.maintenance.select_equipment') }}</option>
                        @foreach($equipmentItems as $item)
                            <option value="{{ $item->id }}" {{ old('equipment_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->equipment->name }} - {{ $item->specific_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_item_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="form-label">{{ __('messages.maintenance.main_title') }}</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="form-input @error('title') border-red-500 @enderror"
                           placeholder="{{ __('messages.maintenance.title_placeholder') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="form-label">{{ __('messages.maintenance.type') }}</label>
                        <select name="type" id="type" required class="form-select">
                            <option value="preventive" {{ old('type') === 'preventive' ? 'selected' : '' }}>{{ __('messages.maintenance.type_preventive') }}</option>
                            <option value="corrective" {{ old('type') === 'corrective' ? 'selected' : '' }}>{{ __('messages.maintenance.type_corrective') }}</option>
                            <option value="inspection" {{ old('type') === 'inspection' ? 'selected' : '' }}>{{ __('messages.maintenance.type_inspection') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="form-label">{{ __('messages.maintenance.priority') }}</label>
                        <select name="priority" id="priority" required class="form-select">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>{{ __('messages.maintenance.priority_low') }}</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>{{ __('messages.maintenance.priority_medium') }}</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('messages.maintenance.priority_high') }}</option>
                            <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>{{ __('messages.maintenance.priority_urgent') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="scheduled_date" class="form-label">{{ __('messages.maintenance.scheduled_date') }}</label>
                        <input type="date" name="scheduled_date" id="scheduled_date"
                               value="{{ old('scheduled_date', date('Y-m-d')) }}" required
                               min="{{ date('Y-m-d') }}"
                               class="form-input @error('scheduled_date') border-red-500 @enderror">
                        @error('scheduled_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="form-label">{{ __('messages.maintenance.detailed_description') }}</label>
                    <textarea name="description" id="description" rows="3" class="form-input"
                              placeholder="{{ __('messages.maintenance.description_placeholder') }}">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.maintenance.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn-primary">{{ __('messages.maintenance.schedule') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

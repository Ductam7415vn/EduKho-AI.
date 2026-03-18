@extends('layouts.app')

@section('title', __('messages.equipment.add'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.equipment.inventory_management') }}</p>
        <h2 class="resource-title">{{ __('messages.equipment.add_new') }}</h2>
        <p class="resource-copy">
            {{ __('messages.equipment.add_description') }}
        </p>
        <div class="resource-actions">
            <a href="{{ route('equipment.index') }}" class="btn-secondary">{{ __('messages.equipment.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.equipment.store') }}" method="POST" class="card-body space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">{{ __('messages.equipment.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="{{ __('messages.equipment.name_placeholder') }}">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.code') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="base_code" value="{{ old('base_code') }}" required class="form-input" placeholder="{{ __('messages.equipment.code_placeholder') }}">
                    @error('base_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.unit') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', 'Cái') }}" required class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.equipment.subject') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="category_subject" value="{{ old('category_subject') }}" required class="form-input" placeholder="{{ __('messages.equipment.subject_placeholder') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.grade') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="grade_level" value="{{ old('grade_level', 'All') }}" required class="form-input" placeholder="{{ __('messages.equipment.grade_placeholder') }}">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.unit_price') }}</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.equipment.security_level') }} <span class="text-red-500">*</span></label>
                    <select name="security_level" required class="form-select">
                        <option value="normal" {{ old('security_level') === 'normal' ? 'selected' : '' }}>{{ __('messages.equipment.security_normal') }}</option>
                        <option value="high_security" {{ old('security_level') === 'high_security' ? 'selected' : '' }}>{{ __('messages.equipment.security_high') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.origin') }}</label>
                    <input type="text" name="origin" value="{{ old('origin') }}" class="form-input" placeholder="{{ __('messages.equipment.origin_placeholder') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/40 px-4 py-3">
                    <input type="checkbox" name="is_digital" value="1" {{ old('is_digital') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.equipment.type_digital') }}</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/40 px-4 py-3">
                    <input type="checkbox" name="is_fixed_asset" value="1" {{ old('is_fixed_asset') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ __('messages.equipment.is_fixed_asset') }}</span>
                </label>
            </div>

            <div>
                <label class="form-label">{{ __('messages.description') }}</label>
                <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
            </div>

            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 space-y-4">
                <div>
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.equipment.initial_import') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.equipment.initial_description') }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('messages.equipment.initial_quantity') }}</label>
                        <input type="number" name="initial_quantity" value="{{ old('initial_quantity', 0) }}" min="0" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.equipment.import_to_room') }}</label>
                        <select name="room_id" class="form-select">
                            <option value="">{{ __('messages.equipment.select_room') }}</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('equipment.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.equipment.add_equipment') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

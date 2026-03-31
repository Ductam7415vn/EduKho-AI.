@extends('layouts.app')

@section('title', __('messages.equipment.edit'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.equipment.inventory_management') }}</p>
        <h2 class="resource-title">{{ __('messages.update') }} {{ $equipment->name }}</h2>
        <p class="resource-copy">
            {{ __('messages.equipment.edit_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.equipment.code') }}: {{ $equipment->base_code }}</span>
            <span class="meta-chip">{{ __('messages.equipment.available') }}: {{ $equipment->availableCount() }}/{{ $equipment->totalCount() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('equipment.show', $equipment) }}" class="btn-secondary">{{ __('messages.room.back_to_detail') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="form-label">{{ __('messages.equipment.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $equipment->name) }}" required class="form-input">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.code') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="base_code" value="{{ old('base_code', $equipment->base_code) }}" required class="form-input">
                    @error('base_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.unit') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $equipment->unit) }}" required class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.equipment.subject') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="category_subject" value="{{ old('category_subject', $equipment->category_subject) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.grade') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="grade_level" value="{{ old('grade_level', $equipment->grade_level) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.unit_price') }}</label>
                    <input type="number" name="price" value="{{ old('price', $equipment->price) }}" min="0" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.equipment.security_level') }} <span class="text-red-500">*</span></label>
                    <select name="security_level" required class="form-select">
                        <option value="normal" {{ old('security_level', $equipment->security_level) === 'normal' ? 'selected' : '' }}>{{ __('messages.equipment.security_normal') }}</option>
                        <option value="high_security" {{ old('security_level', $equipment->security_level) === 'high_security' ? 'selected' : '' }}>{{ __('messages.equipment.security_high') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.equipment.origin') }}</label>
                    <input type="text" name="origin" value="{{ old('origin', $equipment->origin) }}" class="form-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/40 px-4 py-3">
                    <input type="hidden" name="is_digital" value="0">
                    <input type="checkbox" name="is_digital" value="1" {{ old('is_digital', $equipment->is_digital) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="text-sm font-medium text-inherit">{{ __('messages.equipment.type_digital') }}</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/40 px-4 py-3">
                    <input type="hidden" name="is_fixed_asset" value="0">
                    <input type="checkbox" name="is_fixed_asset" value="1" {{ old('is_fixed_asset', $equipment->is_fixed_asset) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="text-sm font-medium text-inherit">{{ __('messages.equipment.is_fixed_asset') }}</span>
                </label>
            </div>

            <div>
                <label class="form-label">{{ __('messages.description') }}</label>
                <textarea name="description" rows="3" class="form-input">{{ old('description', $equipment->description) }}</textarea>
            </div>

            <div>
                <label class="form-label">{{ __('messages.equipment.image') }}</label>
                @if($equipment->image_url)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ __('messages.equipment.current_image') }}:</p>
                    <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" class="w-full max-w-md rounded-lg shadow-lg">
                </div>
                @endif
                <input type="file" name="image" class="form-input" accept="image/*" onchange="previewImage(event)">
                @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-sm text-gray-500">{{ __('messages.equipment.image_hint') }}</p>
                <div id="imagePreview" class="mt-2"></div>
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('equipment.show', $equipment) }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.equipment.save_changes') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full max-w-md rounded-lg shadow-lg';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush

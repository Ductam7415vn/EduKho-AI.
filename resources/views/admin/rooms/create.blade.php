@extends('layouts.app')

@section('title', __('messages.room.add'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.room.management') }}</p>
        <h2 class="resource-title">{{ __('messages.room.add_new') }}</h2>
        <p class="resource-copy">{{ __('messages.room.add_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">{{ __('messages.room.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.rooms.store') }}" method="POST" class="card-body space-y-6">
            @csrf
            <div>
                <label class="form-label">{{ __('messages.room.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="{{ __('messages.room.name_placeholder') }}">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.room.type') }} <span class="text-red-500">*</span></label>
                    <select name="type" required class="form-select">
                        <option value="warehouse" {{ old('type') === 'warehouse' ? 'selected' : '' }}>{{ __('messages.room.warehouse') }}</option>
                        <option value="lab" {{ old('type') === 'lab' ? 'selected' : '' }}>{{ __('messages.room.lab') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.room.manager') }}</label>
                    <select name="manager_id" class="form-select">
                        <option value="">{{ __('messages.room.select_manager') }}</option>
                        @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">{{ __('messages.room.location') }}</label>
                <input type="text" name="location" value="{{ old('location') }}" class="form-input" placeholder="{{ __('messages.room.location_placeholder') }}">
            </div>
            <div>
                <label class="form-label">{{ __('messages.room.capacity_people') }}</label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" min="0" class="form-input">
            </div>
            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.room.add_room') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

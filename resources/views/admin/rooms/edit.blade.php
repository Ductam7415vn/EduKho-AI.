@extends('layouts.app')

@section('title', __('messages.room.edit'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.room.management') }}</p>
        <h2 class="resource-title">{{ __('messages.edit') }} {{ $room->name }}</h2>
        <p class="resource-copy">{{ __('messages.room.edit_description') }}</p>
        <div class="resource-actions">
            <a href="{{ route('admin.rooms.show', $room) }}" class="btn-secondary">{{ __('messages.room.back_to_detail') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('admin.rooms.update', $room) }}" method="POST" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">{{ __('messages.room.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $room->name) }}" required class="form-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.room.type') }}</label>
                    <select name="type" required class="form-select">
                        <option value="warehouse" {{ old('type', $room->type) === 'warehouse' ? 'selected' : '' }}>{{ __('messages.room.warehouse') }}</option>
                        <option value="lab" {{ old('type', $room->type) === 'lab' ? 'selected' : '' }}>{{ __('messages.room.lab') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.room.manager') }}</label>
                    <select name="manager_id" class="form-select">
                        <option value="">{{ __('messages.room.select_manager') }}</option>
                        @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id', $room->manager_id) == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="form-label">{{ __('messages.room.location') }}</label>
                <input type="text" name="location" value="{{ old('location', $room->location) }}" class="form-input">
            </div>

            <div>
                <label class="form-label">{{ __('messages.room.capacity') }}</label>
                <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" class="form-input">
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.save') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

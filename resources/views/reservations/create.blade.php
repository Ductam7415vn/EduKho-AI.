@extends('layouts.app')

@section('title', __('messages.reservation.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.reservation.kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.reservation.create') }}</h2>
        <p class="resource-copy">
            {{ __('messages.reservation.create_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.reservation.available_count') }}: {{ $equipments->count() }}</span>
            <span class="meta-chip">{{ __('messages.reservation.future_only') }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('reservations.index') }}" class="btn-secondary">{{ __('messages.reservation.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="POST" action="{{ route('reservations.store') }}" class="card-body space-y-6">
            @csrf

            <div>
                <label for="equipment_id" class="form-label">{{ __('messages.reservation.equipment') }} <span class="text-red-500">*</span></label>
                <select name="equipment_id" id="equipment_id" required class="form-select">
                    <option value="">{{ __('messages.reservation.select_equipment') }}</option>
                    @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}"
                            {{ (old('equipment_id') ?? $selectedEquipment?->id) == $equipment->id ? 'selected' : '' }}
                            data-available="{{ $equipment->items->count() }}">
                        {{ $equipment->name }} ({{ $equipment->items->count() }} {{ $equipment->unit }} {{ __('messages.reservation.available') }})
                    </option>
                    @endforeach
                </select>
                @error('equipment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="quantity" class="form-label">{{ __('messages.reservation.quantity') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" required class="form-input">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reserved_date" class="form-label">{{ __('messages.reservation.reserved_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="reserved_date" id="reserved_date" value="{{ old('reserved_date') }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}" required class="form-input">
                    @error('reserved_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="period" class="form-label">{{ __('messages.reservation.period') }}</label>
                    <select name="period" id="period" class="form-select">
                        <option value="">{{ __('messages.reservation.select_period') }}</option>
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('period') == $i ? 'selected' : '' }}>{{ __('messages.borrow.period') }} {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="class_name" class="form-label">{{ __('messages.reservation.class') }}</label>
                    <input type="text" name="class_name" id="class_name" value="{{ old('class_name') }}" class="form-input" placeholder="{{ __('messages.reservation.class_placeholder') }}">
                </div>
            </div>

            <div>
                <label for="subject" class="form-label">{{ __('messages.reservation.subject') }}</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="form-input" placeholder="{{ __('messages.reservation.subject_placeholder') }}">
            </div>

            <div>
                <label for="lesson_name" class="form-label">{{ __('messages.reservation.lesson') }}</label>
                <input type="text" name="lesson_name" id="lesson_name" value="{{ old('lesson_name') }}" class="form-input" placeholder="{{ __('messages.reservation.lesson_placeholder') }}">
            </div>

            <div>
                <label for="notes" class="form-label">{{ __('messages.reservation.notes') }}</label>
                <textarea name="notes" id="notes" rows="3" class="form-input" placeholder="{{ __('messages.reservation.notes_placeholder') }}">{{ old('notes') }}</textarea>
            </div>

            <div class="rounded-2xl border border-cyan-200 bg-cyan-50/90 dark:border-cyan-800/60 dark:bg-cyan-900/20 p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-cyan-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-cyan-800 dark:text-cyan-200">
                        <p class="font-semibold">{{ __('messages.reservation.note_title') }}:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>{{ __('messages.reservation.note_1') }}</li>
                            <li>{{ __('messages.reservation.note_2') }}</li>
                            <li>{{ __('messages.reservation.note_3') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('reservations.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.reservation.submit') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

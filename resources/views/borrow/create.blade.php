@extends('layouts.app')

@section('title', __('messages.borrow.register'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.borrow.title') }}</p>
        <h2 class="resource-title">{{ __('messages.borrow.register') }}</h2>
        <p class="resource-copy">
            {{ __('messages.borrow.register_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.borrow.options') }}: {{ $equipments->count() }} {{ __('messages.equipment.title') }}</span>
            @if(!empty($prefill['borrow_date']))
            <span class="meta-chip">{{ __('messages.borrow.borrow_date') }}: {{ $prefill['borrow_date'] }}</span>
            @endif
        </div>
        <div class="resource-actions">
            <a href="{{ route('borrow.index') }}" class="btn-secondary">{{ __('messages.borrow.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('borrow.store') }}" method="POST" class="card-body space-y-6">
            @csrf

            <div>
                <label class="form-label">{{ __('messages.equipment.title') }} <span class="text-red-500">*</span></label>
                <select name="equipment_id" required class="form-select">
                    <option value="">{{ __('messages.borrow.select_equipment') }}</option>
                    @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}"
                        {{ old('equipment_id', $prefill['equipment_id'] ?? '') == $equipment->id ? 'selected' : '' }}
                        data-available="{{ $equipment->items->count() }}"
                        data-security="{{ $equipment->security_level }}">
                        {{ $equipment->name }} ({{ __('messages.borrow.remaining') }} {{ $equipment->items->count() }} {{ $equipment->unit }})
                        @if($equipment->isHighSecurity()) - {{ __('messages.equipment.high_security') }} @endif
                    </option>
                    @endforeach
                </select>
                @error('equipment_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.borrow.quantity') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity', $prefill['quantity'] ?? 1) }}" required class="form-input">
                    @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.borrow.period_class') }} <span class="text-red-500">*</span></label>
                    <select name="period" required class="form-select">
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('period', $prefill['period'] ?? '') == $i ? 'selected' : '' }}>
                            {{ __('messages.borrow.period') }} {{ $i }} {{ $i <= 5 ? '(' . __('messages.borrow.morning') . ')' : '(' . __('messages.borrow.afternoon') . ')' }}
                        </option>
                        @endfor
                    </select>
                    @error('period')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.borrow.borrow_date') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="borrow_date" value="{{ old('borrow_date', $prefill['borrow_date'] ?? now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required class="form-input">
                    @error('borrow_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.borrow.expected_return') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="expected_return_date" value="{{ old('expected_return_date', $prefill['expected_return_date'] ?? now()->addDay()->format('Y-m-d')) }}" required class="form-input">
                    @error('expected_return_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.borrow.class') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="class_name" value="{{ old('class_name', $prefill['class_name'] ?? '') }}" placeholder="{{ __('messages.borrow.class_placeholder') }}" required class="form-input">
                    @error('class_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.borrow.subject') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject', $prefill['subject'] ?? '') }}" placeholder="{{ __('messages.borrow.subject_placeholder') }}" required class="form-input">
                    @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">{{ __('messages.borrow.lesson_name') }}</label>
                <input type="text" name="lesson_name" value="{{ old('lesson_name', $prefill['lesson_name'] ?? '') }}" placeholder="{{ __('messages.borrow.lesson_placeholder') }}" class="form-input">
                @error('lesson_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('messages.borrow.notes') }}</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="{{ __('messages.borrow.notes_placeholder') }}">{{ old('notes') }}</textarea>
            </div>

            <div id="securityWarning" class="hidden rounded-2xl border border-amber-200 bg-amber-50/90 dark:border-amber-800/60 dark:bg-amber-900/20 p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-200">{{ __('messages.borrow.high_security_warning') }}</h3>
                        <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">{{ __('messages.borrow.high_security_notice') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('borrow.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.borrow.submit') }}</button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
const equipmentSelect = document.querySelector('select[name="equipment_id"]');
const warning = document.getElementById('securityWarning');

if (equipmentSelect && warning) {
    const toggleSecurityWarning = () => {
        const option = equipmentSelect.options[equipmentSelect.selectedIndex];
        if (option && option.dataset.security === 'high_security') {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    };

    equipmentSelect.addEventListener('change', toggleSecurityWarning);
    toggleSecurityWarning();
}
</script>
@endpush

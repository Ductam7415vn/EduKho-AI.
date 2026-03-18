@extends('layouts.app')

@section('title', 'Sua ke hoach')

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Ke hoach giang day</p>
        <h2 class="resource-title">Cap nhat ke hoach</h2>
        <p class="resource-copy">
            Dieu chinh noi dung tiet day va so luong thiet bi can dung truoc khi tien hanh dang ky muon.
        </p>
        <div class="resource-actions">
            <a href="{{ route('teaching-plans.show', $teachingPlan) }}" class="btn-secondary">Quay lai chi tiet</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('teaching-plans.update', $teachingPlan) }}" method="POST" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Mon hoc <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject', $teachingPlan->subject) }}" required class="form-input">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tuan <span class="text-red-500">*</span></label>
                    <input type="number" name="week" value="{{ old('week', $teachingPlan->week) }}" min="1" max="52" required class="form-input">
                    @error('week')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Ten bai day <span class="text-red-500">*</span></label>
                <input type="text" name="lesson_name" value="{{ old('lesson_name', $teachingPlan->lesson_name) }}" required class="form-input">
                @error('lesson_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Ngay du kien <span class="text-red-500">*</span></label>
                    <input type="date" name="planned_date" value="{{ old('planned_date', $teachingPlan->planned_date->format('Y-m-d')) }}" required class="form-input">
                    @error('planned_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tiet <span class="text-red-500">*</span></label>
                    <select name="period" required class="form-select">
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('period', $teachingPlan->period) == $i ? 'selected' : '' }}>Tiet {{ $i }}</option>
                        @endfor
                    </select>
                    @error('period')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Thiet bi <span class="text-red-500">*</span></label>
                <select name="equipment_id" required class="form-select">
                    @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}" {{ old('equipment_id', $teachingPlan->equipment_id) == $equipment->id ? 'selected' : '' }}>
                        {{ $equipment->name }}
                    </option>
                    @endforeach
                </select>
                @error('equipment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">So luong can <span class="text-red-500">*</span></label>
                <input type="number" name="quantity_needed" value="{{ old('quantity_needed', $teachingPlan->quantity_needed) }}" min="1" required class="form-input">
                @error('quantity_needed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Ghi chu</label>
                <textarea name="notes" rows="3" class="form-input">{{ old('notes', $teachingPlan->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('teaching-plans.show', $teachingPlan) }}" class="btn-secondary">Huy</a>
                <button type="submit" class="btn-primary">Luu thay doi</button>
            </div>
        </form>
    </section>
</div>
@endsection

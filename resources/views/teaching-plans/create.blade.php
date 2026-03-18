@extends('layouts.app')

@section('title', 'Tao ke hoach giang day')

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Ke hoach giang day</p>
        <h2 class="resource-title">Tao ke hoach moi</h2>
        <p class="resource-copy">
            Khai bao tiet day, ngay du kien va thiet bi can dung de chuan bi truoc cho viec dang ky muon.
        </p>
        <div class="resource-actions">
            <a href="{{ route('teaching-plans.index') }}" class="btn-secondary">Quay lai danh sach</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form action="{{ route('teaching-plans.store') }}" method="POST" class="card-body space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Mon hoc <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required class="form-input" placeholder="VD: Vat ly">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tuan <span class="text-red-500">*</span></label>
                    <input type="number" name="week" value="{{ old('week', now()->weekOfYear) }}" min="1" max="52" required class="form-input">
                    @error('week')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Ten bai day <span class="text-red-500">*</span></label>
                <input type="text" name="lesson_name" value="{{ old('lesson_name') }}" required class="form-input" placeholder="VD: Dinh luat Om">
                @error('lesson_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Ngay du kien <span class="text-red-500">*</span></label>
                    <input type="date" name="planned_date" value="{{ old('planned_date') }}" min="{{ now()->format('Y-m-d') }}" required class="form-input">
                    @error('planned_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Tiet <span class="text-red-500">*</span></label>
                    <select name="period" required class="form-select">
                        @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('period') == $i ? 'selected' : '' }}>Tiet {{ $i }} {{ $i <= 5 ? '(Sang)' : '(Chieu)' }}</option>
                        @endfor
                    </select>
                    @error('period')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label">Thiet bi can dung <span class="text-red-500">*</span></label>
                <select name="equipment_id" required class="form-select">
                    <option value="">-- Chon thiet bi --</option>
                    @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}" {{ old('equipment_id') == $equipment->id ? 'selected' : '' }}>
                        {{ $equipment->name }} (Con {{ $equipment->availableCount() }} {{ $equipment->unit }})
                    </option>
                    @endforeach
                </select>
                @error('equipment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">So luong can <span class="text-red-500">*</span></label>
                <input type="number" name="quantity_needed" value="{{ old('quantity_needed', 1) }}" min="1" required class="form-input">
                @error('quantity_needed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Ghi chu</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="Ghi chu them (neu co)">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('teaching-plans.index') }}" class="btn-secondary">Huy</a>
                <button type="submit" class="btn-primary">Tao ke hoach</button>
            </div>
        </form>
    </section>
</div>
@endsection

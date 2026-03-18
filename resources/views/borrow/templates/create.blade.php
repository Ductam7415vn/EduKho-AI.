@extends('layouts.app')

@section('title', 'Tao mau phieu muon')

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Mau dang ky</p>
        <h2 class="resource-title">Tao mau phieu muon moi</h2>
        <p class="resource-copy">
            Luu thong tin thuong dung de su dung lai nhanh khi tao phieu muon trong cac tiet day sau.
        </p>
        <div class="resource-actions">
            <a href="{{ route('borrow.templates.index') }}" class="btn-secondary">Quay lai danh sach</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="POST" action="{{ route('borrow.templates.store') }}" class="card-body space-y-6">
            @csrf

            <div>
                <label for="name" class="form-label">Ten mau <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Thi nghiem Hoa 10, Thuc hanh Ly...">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="equipment_id" class="form-label">Thiet bi <span class="text-red-500">*</span></label>
                    <select name="equipment_id" id="equipment_id" required class="form-select">
                        <option value="">Chon thiet bi</option>
                        @foreach($equipments as $equipment)
                            <option value="{{ $equipment->id }}" {{ old('equipment_id') == $equipment->id ? 'selected' : '' }}>
                                {{ $equipment->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="form-label">So luong <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" max="50" required class="form-input">
                    @error('quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="class_name" class="form-label">Lop</label>
                    <input type="text" name="class_name" id="class_name" value="{{ old('class_name') }}" class="form-input" placeholder="VD: 10A1, 11B2...">
                </div>

                <div>
                    <label for="subject" class="form-label">Mon hoc</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="form-input" placeholder="VD: Vat ly, Hoa hoc...">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="lesson_name" class="form-label">Ten bai hoc</label>
                    <input type="text" name="lesson_name" id="lesson_name" value="{{ old('lesson_name') }}" class="form-input" placeholder="VD: Bai 5: Dinh luat Ohm...">
                </div>

                <div>
                    <label for="period" class="form-label">Tiet</label>
                    <select name="period" id="period" class="form-select">
                        <option value="">Chon tiet</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('period') == $i ? 'selected' : '' }}>Tiet {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div>
                <label for="notes" class="form-label">Ghi chu</label>
                <textarea name="notes" id="notes" rows="2" class="form-input" placeholder="Ghi chu them...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('borrow.templates.index') }}" class="btn-secondary">Huy</a>
                <button type="submit" class="btn-primary">Luu mau</button>
            </div>
        </form>
    </section>
</div>
@endsection

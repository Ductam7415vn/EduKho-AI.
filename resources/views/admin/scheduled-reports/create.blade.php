@extends('layouts.app')

@section('title', 'Tao bao cao tu dong')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Tao bao cao tu dong</h2>
        </div>

        <form method="POST" action="{{ route('admin.scheduled-reports.store') }}" class="p-6 space-y-6" x-data="{ frequency: '{{ old('frequency', 'weekly') }}' }">
            @csrf

            <div>
                <label for="name" class="form-label">Ten bao cao <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input"
                       placeholder="Vi du: Bao cao ton kho hang tuan">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="report_type" class="form-label">Loai bao cao <span class="text-red-500">*</span></label>
                <select name="report_type" id="report_type" required class="form-input">
                    <option value="equipment_list" {{ old('report_type') === 'equipment_list' ? 'selected' : '' }}>Danh sach thiet bi</option>
                    <option value="borrow_tracking" {{ old('report_type') === 'borrow_tracking' ? 'selected' : '' }}>Theo doi muon tra</option>
                    <option value="inventory_summary" {{ old('report_type') === 'inventory_summary' ? 'selected' : '' }}>Tong hop ton kho</option>
                    <option value="overdue_report" {{ old('report_type') === 'overdue_report' ? 'selected' : '' }}>Qua han tra</option>
                    <option value="maintenance_report" {{ old('report_type') === 'maintenance_report' ? 'selected' : '' }}>Bao tri thiet bi</option>
                </select>
                @error('report_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="frequency" class="form-label">Tan suat <span class="text-red-500">*</span></label>
                    <select name="frequency" id="frequency" required class="form-input" x-model="frequency">
                        <option value="daily">Hang ngay</option>
                        <option value="weekly">Hang tuan</option>
                        <option value="monthly">Hang thang</option>
                    </select>
                </div>

                <div>
                    <label for="send_time" class="form-label">Gio gui <span class="text-red-500">*</span></label>
                    <input type="time" name="send_time" id="send_time" value="{{ old('send_time', '08:00') }}" required class="form-input">
                </div>
            </div>

            <div x-show="frequency === 'weekly'" x-cloak>
                <label for="day_of_week" class="form-label">Ngay trong tuan</label>
                <select name="day_of_week" id="day_of_week" class="form-input">
                    <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Thu Hai</option>
                    <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Thu Ba</option>
                    <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Thu Tu</option>
                    <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Thu Nam</option>
                    <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Thu Sau</option>
                    <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Thu Bay</option>
                    <option value="0" {{ old('day_of_week') == 0 ? 'selected' : '' }}>Chu Nhat</option>
                </select>
            </div>

            <div x-show="frequency === 'monthly'" x-cloak>
                <label for="day_of_month" class="form-label">Ngay trong thang</label>
                <select name="day_of_month" id="day_of_month" class="form-input">
                    @for($i = 1; $i <= 28; $i++)
                    <option value="{{ $i }}" {{ old('day_of_month') == $i ? 'selected' : '' }}>Ngay {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="recipients" class="form-label">Nguoi nhan <span class="text-red-500">*</span></label>
                <textarea name="recipients" id="recipients" rows="3" required class="form-input"
                          placeholder="Nhap email, phan cach bang dau phay. Vi du: admin@school.edu.vn, truong@school.edu.vn">{{ old('recipients') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Nhap danh sach email, phan cach bang dau phay</p>
                @error('recipients')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.scheduled-reports.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Huy</a>
                <button type="submit" class="btn-primary">Tao bao cao</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Chuyen thiet bi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Chuyen thiet bi</h2>
        </div>

        <form method="POST" action="{{ route('admin.transfers.store') }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="equipment_item_id" class="form-label">Thiet bi <span class="text-red-500">*</span></label>
                <select name="equipment_item_id" id="equipment_item_id" required class="form-input">
                    <option value="">-- Chon thiet bi --</option>
                    @foreach($equipmentItems as $item)
                    <option value="{{ $item->id }}"
                            {{ (old('equipment_item_id') ?? $selectedItem?->id) == $item->id ? 'selected' : '' }}
                            data-room="{{ $item->room_id }}">
                        {{ $item->specific_code }} - {{ $item->equipment->name }}
                        @if($item->room)
                        ({{ $item->room->name }})
                        @else
                        (Chua xep phong)
                        @endif
                    </option>
                    @endforeach
                </select>
                @error('equipment_item_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="to_room_id" class="form-label">Chuyen den phong <span class="text-red-500">*</span></label>
                <select name="to_room_id" id="to_room_id" required class="form-input">
                    <option value="">-- Chon phong dich --</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('to_room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} ({{ $room->type }})
                    </option>
                    @endforeach
                </select>
                @error('to_room_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="transfer_date" class="form-label">Ngay chuyen <span class="text-red-500">*</span></label>
                <input type="date" name="transfer_date" id="transfer_date"
                       value="{{ old('transfer_date', now()->format('Y-m-d')) }}"
                       max="{{ now()->format('Y-m-d') }}" required class="form-input">
                @error('transfer_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reason" class="form-label">Ly do chuyen</label>
                <input type="text" name="reason" id="reason" value="{{ old('reason') }}" class="form-input"
                       placeholder="Vi du: Phan bo lai thiet bi, sua chua, ...">
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="form-label">Ghi chu</label>
                <textarea name="notes" id="notes" rows="3" class="form-input"
                          placeholder="Ghi chu them...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.transfers.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Huy</a>
                <button type="submit" class="btn-primary">Chuyen thiet bi</button>
            </div>
        </form>
    </div>
</div>
@endsection

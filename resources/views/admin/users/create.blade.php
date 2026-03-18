@extends('layouts.app')

@section('title', __('messages.user.add'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('messages.user.add_new') }}</h2>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label class="form-label">{{ __('messages.user.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.role') }} <span class="text-red-500">*</span></label>
                    <select name="role" required class="form-select">
                        <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>{{ __('messages.user.teacher') }}</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('messages.user.admin') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.department') }}</label>
                    <select name="department_id" class="form-select">
                        <option value="">{{ __('messages.user.select_department') }}</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.password') }} <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="form-input">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.password_confirm') }} <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.add') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

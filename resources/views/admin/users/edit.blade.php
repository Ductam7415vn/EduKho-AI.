@extends('layouts.app')

@section('title', __('messages.user.edit'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('messages.edit') }}: {{ $user->name }}</h2>
        </div>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6 space-y-6">
            @csrf @method('PUT')
            <div>
                <label class="form-label">{{ __('messages.user.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.email') }} <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.role') }}</label>
                    <select name="role" class="form-select">
                        <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>{{ __('messages.user.teacher') }}</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('messages.user.admin') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.department') }}</label>
                    <select name="department_id" class="form-select">
                        <option value="">{{ __('messages.user.select_department') }}</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('messages.user.password_new') }}</label>
                    <input type="password" name="password" class="form-input">
                </div>
                <div>
                    <label class="form-label">{{ __('messages.user.password_confirm') }}</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('messages.user.activate_account') }}</label>
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

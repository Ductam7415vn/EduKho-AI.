@extends('layouts.app')

@section('title', __('messages.department.add'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.department.add_new') }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.departments.store') }}" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="form-label">{{ __('messages.department.name') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input"
                       placeholder="{{ __('messages.department.name_placeholder') }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="form-label">{{ __('messages.department.description') }}</label>
                <textarea name="description" id="description" rows="3" class="form-input"
                          placeholder="{{ __('messages.department.description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.departments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-primary">{{ __('messages.department.create') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

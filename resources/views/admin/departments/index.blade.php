@extends('layouts.app')

@section('title', __('messages.department.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.nav.admin') }}</p>
        <h2 class="resource-title">{{ __('messages.department.title') }}</h2>
        <p class="resource-copy">{{ __('messages.department.list') }}</p>
        <div class="resource-actions">
            <a href="{{ route('admin.departments.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.department.add') }}
            </a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.department.name') }}</th>
                        <th>{{ __('messages.department.description') }}</th>
                        <th>{{ __('messages.department.teacher_count') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($departments as $department)
                    <tr>
                        <td>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $department->name }}</div>
                        </td>
                        <td class="text-gray-600 dark:text-gray-300">
                            {{ Str::limit($department->description, 100) ?? '-' }}
                        </td>
                        <td>
                            <span class="table-pill bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                {{ $department->teachers_count }} {{ __('messages.department.teachers') }}
                            </span>
                        </td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('admin.departments.show', $department) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.view') }}</a>
                            <a href="{{ route('admin.departments.edit', $department) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.edit') }}</a>
                            @if($department->users_count === 0)
                            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300"
                                        onclick="return confirm('{{ __('messages.department.delete_confirm') }}')">{{ __('messages.delete') }}</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="mt-2">{{ __('messages.department.no_department') }}</p>
                            <a href="{{ route('admin.departments.create') }}" class="text-teal-600 hover:text-teal-800 dark:text-teal-400 dark:hover:text-teal-300 mt-2 inline-block">{{ __('messages.department.add') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $department->name)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.department.title') }}</p>
        <h2 class="resource-title">{{ $department->name }}</h2>
        @if($department->description)
        <p class="resource-copy">{{ $department->description }}</p>
        @endif
        <div class="resource-actions">
            <a href="{{ route('admin.departments.edit', $department) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
            <a href="{{ route('admin.departments.index') }}" class="btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </section>

    <!-- Stats -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-4 animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
            <div class="card-body flex items-center">
                <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-blue-600 dark:text-blue-400">{{ __('messages.department.total_members') }}</p>
                    <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $department->users->count() }}</p>
                </div>
            </div>
        </div>
        <div class="card bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
            <div class="card-body flex items-center">
                <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-green-600 dark:text-green-400">{{ __('messages.department.active_members') }}</p>
                    <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $department->users->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="card bg-gray-50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700">
            <div class="card-body flex items-center">
                <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.department.inactive_members') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $department->users->where('is_active', false)->count() }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Members List -->
    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.department.members_list') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.user.name') }}</th>
                        <th>{{ __('messages.user.email') }}</th>
                        <th>{{ __('messages.user.role') }}</th>
                        <th>{{ __('messages.user.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($department->users as $user)
                    <tr>
                        <td>
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                        </td>
                        <td class="text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                        <td>
                            <span class="table-pill {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' }}">
                                {{ $user->role === 'admin' ? __('messages.user.admin') : __('messages.user.teacher') }}
                            </span>
                        </td>
                        <td>
                            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' }}">
                                {{ $user->is_active ? __('messages.user.active') : __('messages.user.inactive') }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            {{ __('messages.department.no_members') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

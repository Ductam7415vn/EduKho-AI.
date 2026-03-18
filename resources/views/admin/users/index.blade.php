@extends('layouts.app')

@section('title', __('messages.user.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.nav.admin') }}</p>
        <h2 class="resource-title">{{ __('messages.user.title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.user.list') }}
        </p>
        <div class="resource-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.user.add') }}
            </a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.user.name') }}</th>
                        <th>{{ __('messages.user.email') }}</th>
                        <th>{{ __('messages.user.role') }}</th>
                        <th>{{ __('messages.user.department') }}</th>
                        <th>{{ __('messages.user.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
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
                        <td class="text-gray-600 dark:text-gray-300">{{ $user->department?->name ?? '-' }}</td>
                        <td>
                            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' }}">
                                {{ $user->is_active ? __('messages.user.active') : __('messages.user.inactive') }}
                            </span>
                        </td>
                        <td class="text-right space-x-2">
                            @if(!$user->isAdmin() && $user->id !== auth()->id())
                            <form action="{{ route('admin.impersonate.start', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300" title="{{ __('messages.user.impersonate') }}">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.view') }}</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">{{ __('messages.edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection

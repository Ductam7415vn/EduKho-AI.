@extends('layouts.app')

@section('title', __('messages.profile.title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.profile.kicker') }}</p>
        <h2 class="resource-title">{{ $user->name }}</h2>
        <p class="resource-copy">{{ $user->role === 'admin' ? __('messages.user.admin') : __('messages.user.teacher') }}</p>
        <div class="resource-meta">
            <span class="meta-chip">{{ $user->email }}</span>
            <span class="meta-chip">{{ $user->department?->name ?? __('messages.profile.not_assigned') }}</span>
            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' }}">
                {{ $user->is_active ? __('messages.profile.active') : __('messages.profile.inactive') }}
            </span>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-cyan-100 dark:bg-cyan-900/40 flex items-center justify-center">
                    <span class="text-2xl font-bold text-cyan-700 dark:text-cyan-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h3 class="font-display text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->role === 'admin' ? __('messages.user.admin') : __('messages.user.teacher') }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.profile.email') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.profile.phone') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->phone ?? __('messages.profile.not_updated') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.profile.department') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->department?->name ?? __('messages.profile.not_assigned') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.profile.status') }}</dt>
                        <dd class="mt-1">
                            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' }}">
                                {{ $user->is_active ? __('messages.profile.active') : __('messages.profile.inactive') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.profile.created_at') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-6 flex flex-wrap gap-2">
                <a href="{{ route('profile.edit') }}" class="btn-primary">{{ __('messages.profile.edit_info') }}</a>
                <a href="{{ route('profile.password') }}" class="btn-secondary">{{ __('messages.profile.change_password') }}</a>
                <a href="{{ route('profile.notifications') }}" class="btn-secondary">{{ __('messages.profile.notifications') }}</a>
                <a href="{{ route('profile.two-factor') }}" class="btn-secondary">
                    @if($user->hasTwoFactorEnabled())
                        <svg class="w-4 h-4 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    {{ __('messages.profile.two_factor') }}
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

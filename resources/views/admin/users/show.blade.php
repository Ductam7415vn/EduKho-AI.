@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.user.management') }}</p>
        <h2 class="resource-title">{{ $user->name }}</h2>
        <p class="resource-copy">{{ $user->email }}</p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.user.role') }}: {{ $user->role === 'admin' ? __('messages.user.admin') : __('messages.user.teacher') }}</span>
            <span class="meta-chip">{{ __('messages.user.department') }}: {{ $user->department?->name ?? '-' }}</span>
            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' }}">
                {{ $user->is_active ? __('messages.user.active') : __('messages.user.inactive') }}
            </span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('messages.user.delete_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">{{ __('messages.delete') }}</button>
            </form>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 80ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.user.info') }}</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.user.email') }}:</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.user.phone') }}:</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $user->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.user.role') }}:</dt>
                        <dd>
                            <span class="table-pill {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300' }}">
                                {{ $user->role === 'admin' ? __('messages.user.admin') : __('messages.user.teacher') }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.user.department') }}:</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $user->department?->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.user.status') }}:</dt>
                        <dd>
                            <span class="table-pill {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' }}">
                                {{ $user->is_active ? __('messages.user.active') : __('messages.user.inactive') }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </article>

        <article class="lg:col-span-2 data-table-wrap">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.user.recent_borrows') }}</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($user->borrowRecords as $borrow)
                <div class="px-6 py-3 flex justify-between items-center gap-4">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">#{{ $borrow->id }} - {{ $borrow->borrow_date->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $borrow->class_name }} - {{ __('messages.borrow.period') }} {{ $borrow->period }}</p>
                    </div>
                    <span class="table-pill {{ $borrow->status === 'returned' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300' }}">
                        {{ $borrow->status === 'returned' ? __('messages.borrow.returned') : __('messages.borrow.borrowed') }}
                    </span>
                </div>
                @empty
                <p class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">{{ __('messages.user.no_borrows') }}</p>
                @endforelse
            </div>
        </article>
    </section>
</div>
@endsection

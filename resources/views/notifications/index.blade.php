@extends('layouts.app')

@section('title', __('messages.notification.title'))

@section('content')
<div class="resource-shell max-w-5xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.notification.system') }}</p>
        <h2 class="resource-title">{{ __('messages.notification.your_notifications') }}</h2>
        <p class="resource-copy">
            {{ __('messages.notification.description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.notification.total') }}: {{ number_format($notifications->total()) }}</span>
            <span class="meta-chip">{{ __('messages.notification.unread') }}: {{ $notifications->where('read_at', null)->count() }}</span>
        </div>
        @if($notifications->where('read_at', null)->count() > 0)
        <div class="resource-actions">
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary">{{ __('messages.notification.mark_all_read') }}</button>
            </form>
        </div>
        @endif
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        @if($notifications->isEmpty())
        <div class="card-body empty-state">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="mt-4">{{ __('messages.notification.no_notifications') }}</p>
        </div>
        @else
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($notifications as $notification)
            <article class="px-6 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/40 {{ is_null($notification->read_at) ? 'bg-cyan-50/70 dark:bg-cyan-900/15' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        @php
                            $type = $notification->data['type'] ?? 'info';
                            $icon = match($type) {
                                'borrow_approved' => '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'borrow_rejected' => '<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                'pending_approval' => '<svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                default => '<svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                            };
                        @endphp
                        <div class="flex items-center gap-3">
                            {!! $icon !!}
                            <p class="text-sm {{ is_null($notification->read_at) ? 'font-semibold text-gray-900 dark:text-gray-100' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $notification->data['message'] ?? __('messages.notification.new_notification') }}
                            </p>
                            @if(is_null($notification->read_at))
                            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ __('messages.notification.new') }}</span>
                            @endif
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            @if(isset($notification->data['borrow_record_id']))
                            <a href="{{ route('borrow.show', $notification->data['borrow_record_id']) }}" class="font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                                {{ __('messages.notification.view_borrow') }}
                            </a>
                            @endif
                        </div>
                    </div>

                    @if(is_null($notification->read_at))
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('messages.notification.mark_read') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('messages.notification.read') }}</span>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $notifications->links() }}
        </div>
        @endif
    </section>
</div>
@endsection

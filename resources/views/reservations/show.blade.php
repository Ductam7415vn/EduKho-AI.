@extends('layouts.app')

@section('title', __('messages.reservation.detail') . ' #' . $reservation->id)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.reservation.detail') }}</p>
        <h2 class="resource-title">{{ __('messages.reservation.title') }} #{{ $reservation->id }}</h2>
        <p class="resource-copy">{{ __('messages.reservation.created_at') }} {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.reservation.date') }}: {{ $reservation->reserved_date->format('d/m/Y') }}</span>
            <span class="table-pill
                @if($reservation->status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300
                @elseif($reservation->status === 'confirmed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                @elseif($reservation->status === 'cancelled') bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                @else bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300
                @endif">
                @if($reservation->status === 'pending') {{ __('messages.reservation.pending') }}
                @elseif($reservation->status === 'confirmed') {{ __('messages.reservation.confirmed') }}
                @elseif($reservation->status === 'cancelled') {{ __('messages.reservation.cancelled') }}
                @else {{ __('messages.reservation.converted') }}
                @endif
            </span>
        </div>
        <div class="resource-actions">
            @if($reservation->canBeConverted())
            <form action="{{ route('reservations.convert', $reservation) }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">{{ __('messages.reservation.borrow_now') }}</button>
            </form>
            @endif

            @if(auth()->user()->isAdmin() && $reservation->isPending())
            <form action="{{ route('reservations.confirm', $reservation) }}" method="POST">
                @csrf
                <button type="submit" class="btn-success">{{ __('messages.reservation.confirm') }}</button>
            </form>
            @endif

            @if(in_array($reservation->status, ['pending', 'confirmed']))
            <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('{{ __('messages.reservation.cancel_confirm') }}')">{{ __('messages.reservation.cancel_reservation') }}</button>
            </form>
            @endif

            <a href="{{ route('reservations.index') }}" class="btn-secondary">{{ __('messages.reservation.back') }}</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="rounded-xl px-4 py-3 border
            @if($reservation->status === 'pending') bg-amber-50 border-amber-200 dark:bg-amber-900/20 dark:border-amber-800/50
            @elseif($reservation->status === 'confirmed') bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800/50
            @elseif($reservation->status === 'cancelled') bg-rose-50 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800/50
            @elseif($reservation->status === 'converted') bg-cyan-50 border-cyan-200 dark:bg-cyan-900/20 dark:border-cyan-800/50
            @endif">
            <div class="flex items-center gap-2">
                @if($reservation->status === 'pending')
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium text-amber-800 dark:text-amber-200">{{ __('messages.reservation.waiting_confirm') }}</span>
                @elseif($reservation->status === 'confirmed')
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium text-emerald-800 dark:text-emerald-200">{{ __('messages.reservation.confirmed') }} - {{ $reservation->confirmed_at->format('d/m/Y H:i') }}</span>
                @elseif($reservation->status === 'cancelled')
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium text-rose-800 dark:text-rose-200">{{ __('messages.reservation.cancelled') }} - {{ $reservation->cancelled_at->format('d/m/Y H:i') }}</span>
                @elseif($reservation->status === 'converted')
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="font-medium text-cyan-800 dark:text-cyan-200">
                    {{ __('messages.reservation.converted_to_borrow') }}
                    @if($reservation->borrowRecord)
                    <a href="{{ route('borrow.show', $reservation->borrowRecord) }}" class="underline">#{{ $reservation->borrowRecord->id }}</a>
                    @endif
                </span>
                @endif
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 140ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.reservation.info') }}</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.reservation.reserved_date') }}:</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->reserved_date->format('d/m/Y') }}</dd>
                    </div>
                    @if($reservation->period)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.reservation.period') }}:</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ __('messages.borrow.period') }} {{ $reservation->period }}</dd>
                    </div>
                    @endif
                    @if($reservation->class_name)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.reservation.class') }}:</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->class_name }}</dd>
                    </div>
                    @endif
                    @if($reservation->subject)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.reservation.subject') }}:</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->subject }}</dd>
                    </div>
                    @endif
                    @if($reservation->lesson_name)
                    <div class="flex justify-between gap-3">
                        <dt class="text-gray-500 dark:text-gray-400">{{ __('messages.reservation.lesson') }}:</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->lesson_name }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.equipment.title') }}</h3>
                <div class="flex items-start gap-4">
                    <div class="rounded-xl bg-cyan-100 dark:bg-cyan-900/35 p-3">
                        <svg class="w-8 h-8 text-cyan-600 dark:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $reservation->equipment->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.equipment.code') }}: {{ $reservation->equipment->base_code }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.reservation.quantity') }}: {{ $reservation->quantity }} {{ $reservation->equipment->unit }}</p>
                        <a href="{{ route('equipment.show', $reservation->equipment) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 mt-2 inline-block">{{ __('messages.reservation.view_equipment') }}</a>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="grid grid-cols-1 gap-6 animate-fade-in-up" style="animation-delay: 180ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.reservation.reserver') }}</h3>
                <div class="flex items-center gap-4">
                    <div class="rounded-full p-3 bg-gray-100 dark:bg-gray-800">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->user->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->user->email }}</p>
                    </div>
                </div>
            </div>
        </article>

        @if($reservation->notes)
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.note') }}</h3>
                <p class="text-gray-700 dark:text-gray-300">{{ $reservation->notes }}</p>
            </div>
        </article>
        @endif
    </section>
</div>
@endsection

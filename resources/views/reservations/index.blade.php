@extends('layouts.app')

@section('title', __('messages.reservation.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.reservation.kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.reservation.list_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.reservation.list_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.reservation.total') }}: {{ number_format($reservations->total()) }}</span>
            <span class="meta-chip">{{ __('messages.reservation.page') }}: {{ $reservations->currentPage() }}/{{ $reservations->lastPage() }}</span>
            @if(request('status'))
            <span class="meta-chip">{{ __('messages.reservation.filter_status') }}: {{ request('status') }}</span>
            @endif
        </div>
        <div class="resource-actions">
            <a href="{{ route('ical.reservations') }}" class="btn-info">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                iCal
            </a>
            <a href="{{ route('reservations.create') }}" class="btn-primary">{{ __('messages.reservation.create_new') }}</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="GET" class="flex flex-wrap gap-3">
            <div>
                <label class="form-label">{{ __('messages.status') }}</label>
                <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                    <option value="">{{ __('messages.all') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.reservation.pending') }}</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('messages.reservation.confirmed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('messages.reservation.cancelled') }}</option>
                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>{{ __('messages.reservation.converted') }}</option>
                </select>
            </div>
            <div>
                <label class="form-label">{{ __('messages.date') }}</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input text-sm" onchange="this.form.submit()">
            </div>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 140ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.damage_report.code') }}</th>
                        @if(auth()->user()->isAdmin())
                        <th>{{ __('messages.borrow.teacher') }}</th>
                        @endif
                        <th>{{ __('messages.equipment.title') }}</th>
                        <th>{{ __('messages.reservation.quantity') }}</th>
                        <th>{{ __('messages.reservation.reserved_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($reservations as $reservation)
                    <tr>
                        <td class="font-semibold text-gray-900 dark:text-white">#{{ $reservation->id }}</td>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->user->name }}</div>
                        </td>
                        @endif
                        <td>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $reservation->equipment->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $reservation->equipment->base_code }}</div>
                        </td>
                        <td>{{ $reservation->quantity }} {{ $reservation->equipment->unit }}</td>
                        <td>
                            <div>{{ $reservation->reserved_date->format('d/m/Y') }}</div>
                            @if($reservation->period)
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.borrow.period') }} {{ $reservation->period }}</div>
                            @endif
                        </td>
                        <td>
                            @if($reservation->status === 'pending')
                                <span class="table-pill bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">{{ __('messages.reservation.pending') }}</span>
                            @elseif($reservation->status === 'confirmed')
                                <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">{{ __('messages.reservation.confirmed') }}</span>
                            @elseif($reservation->status === 'cancelled')
                                <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">{{ __('messages.reservation.cancelled') }}</span>
                            @elseif($reservation->status === 'converted')
                                <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300">{{ __('messages.reservation.converted') }}</span>
                            @endif
                        </td>
                        <td class="text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('reservations.show', $reservation) }}" class="font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 text-sm">{{ __('messages.view') }}</a>

                            @if($reservation->canBeConverted())
                            <form action="{{ route('reservations.convert', $reservation) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-700 hover:text-emerald-800 dark:text-emerald-300 dark:hover:text-emerald-200 text-sm font-semibold">{{ __('messages.reservation.borrow_now') }}</button>
                            </form>
                            @endif

                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                            <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-700 hover:text-rose-800 dark:text-rose-300 dark:hover:text-rose-200 text-sm font-semibold"
                                        onclick="return confirm('{{ __('messages.reservation.cancel_confirm') }}')">{{ __('messages.reservation.cancel') }}</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2">{{ __('messages.reservation.no_reservations') }}</p>
                            <a href="{{ route('reservations.create') }}" class="mt-2 inline-block font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.reservation.reserve_equipment') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{ $reservations->links() }}
</div>
@endsection

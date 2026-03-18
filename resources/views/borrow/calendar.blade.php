@extends('layouts.app')

@section('title', __('messages.borrow.calendar_title'))

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    .fc .fc-toolbar-title {
        font-family: "Space Grotesk", sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .fc .fc-button-primary {
        border-radius: 0.75rem;
        border-color: transparent;
        background: linear-gradient(130deg, #0f766e 0%, #0f4c81 100%);
        box-shadow: 0 8px 20px rgba(15, 76, 129, 0.22);
    }

    .fc .fc-button-primary:not(:disabled):hover {
        filter: brightness(1.06);
    }

    .fc .fc-daygrid-event,
    .fc .fc-timegrid-event {
        border-radius: 0.5rem;
        border: none;
        font-weight: 600;
    }

    .fc .fc-daygrid-day.fc-day-today,
    .fc .fc-timegrid-col.fc-day-today {
        background: rgba(14, 116, 144, 0.08);
    }
</style>
@endpush

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.borrow.calendar_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.borrow.calendar_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.borrow.calendar_description') }}
        </p>
        <div class="resource-meta">
            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ __('messages.borrow.approved') }}</span>
            <span class="table-pill bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">{{ __('messages.borrow.pending') }}</span>
            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ __('messages.borrow.returned') }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('borrow.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.borrow.register') }}
            </a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <div id="calendar"></div>
    </section>
</div>

<div id="eventModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="card max-w-md w-full mx-4">
        <div class="card-body">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">{{ __('messages.borrow.detail') }}</h3>
                <button onclick="document.getElementById('eventModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-2 text-sm text-gray-600 dark:text-gray-300"></div>
            <div class="mt-6 flex justify-end">
                <a id="modalLink" href="#" class="btn-primary">{{ __('messages.view') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '/api/borrow/calendar/events',
        eventClick: function(info) {
            const event = info.event;
            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalContent').innerHTML = `
                <p><strong>{{ __('messages.borrow.period') }}:</strong> {{ __('messages.borrow.period') }} ${event.extendedProps.period}</p>
                <p><strong>{{ __('messages.borrow.class') }}:</strong> ${event.extendedProps.class_name}</p>
                <p><strong>{{ __('messages.status') }}:</strong> ${event.extendedProps.status}</p>
                <p><strong>{{ __('messages.borrow.from') }}:</strong> ${event.startStr}</p>
                <p><strong>{{ __('messages.borrow.to') }}:</strong> ${event.endStr}</p>
            `;
            document.getElementById('modalLink').href = '/borrow/' + event.id;
            document.getElementById('eventModal').classList.remove('hidden');
        },
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        }
    });
    calendar.render();
});
</script>
@endpush

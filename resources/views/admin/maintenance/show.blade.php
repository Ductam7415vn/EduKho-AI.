@extends('layouts.app')

@section('title', __('messages.maintenance.detail'))

@section('content')
<div class="resource-shell max-w-5xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.maintenance.title') }}</p>
        <h2 class="resource-title">{{ __('messages.maintenance.detail_title') }}</h2>
        <p class="resource-copy">{{ $maintenance->title }}</p>
        <div class="resource-meta">
            @php
                $statusColors = ['scheduled' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300', 'in_progress' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300', 'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300', 'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'];
                $priorityColors = ['low' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300', 'medium' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300', 'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300', 'urgent' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300'];
            @endphp
            <span class="table-pill {{ $statusColors[$maintenance->status] }}">
                @if($maintenance->status === 'scheduled') {{ __('messages.maintenance.scheduled') }}
                @elseif($maintenance->status === 'in_progress') {{ __('messages.maintenance.in_progress') }}
                @elseif($maintenance->status === 'completed') {{ __('messages.maintenance.completed') }}
                @else {{ __('messages.maintenance.cancelled') }}
                @endif
            </span>
            <span class="table-pill {{ $priorityColors[$maintenance->priority] }}">
                @if($maintenance->priority === 'low') {{ __('messages.maintenance.priority_low') }}
                @elseif($maintenance->priority === 'medium') {{ __('messages.maintenance.priority_medium') }}
                @elseif($maintenance->priority === 'high') {{ __('messages.maintenance.priority_high') }}
                @else {{ __('messages.maintenance.priority_urgent') }}
                @endif
            </span>
            @if($maintenance->isOverdue())
            <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.maintenance.overdue') }}</span>
            @endif
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.maintenance.index') }}" class="btn-secondary">{{ __('messages.maintenance.back') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.equipment') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $maintenance->equipmentItem->equipment->name }}
                        <span class="text-gray-500 dark:text-gray-400">({{ $maintenance->equipmentItem->specific_code }})</span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.type') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($maintenance->type === 'preventive') {{ __('messages.maintenance.type_preventive') }}
                        @elseif($maintenance->type === 'corrective') {{ __('messages.maintenance.type_corrective') }}
                        @else {{ __('messages.maintenance.type_inspection') }}
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.scheduled_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->scheduled_date->format('d/m/Y') }}</dd>
                </div>

                @if($maintenance->completed_date)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.completed_date') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->completed_date->format('d/m/Y') }}</dd>
                </div>
                @endif

                @if($maintenance->cost)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.cost') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($maintenance->cost) }} VND</dd>
                </div>
                @endif

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.creator') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->creator->name }}</dd>
                </div>

                @if($maintenance->completer)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.completer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->completer->name }}</dd>
                </div>
                @endif

                @if($maintenance->description)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.description') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->description }}</dd>
                </div>
                @endif

                @if($maintenance->notes)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.maintenance.completion_notes') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $maintenance->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </section>

    @if($maintenance->isScheduled())
    <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.maintenance.actions') }}</h3>
            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.maintenance.start', $maintenance) }}">
                    @csrf
                    <button type="submit" class="btn-primary">{{ __('messages.maintenance.start_maintenance') }}</button>
                </form>
                <form method="POST" action="{{ route('admin.maintenance.cancel', $maintenance) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary" onclick="return confirm('{{ __('messages.maintenance.cancel_confirm') }}')">
                        {{ __('messages.maintenance.cancel_schedule') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
    @endif

    @if($maintenance->isInProgress())
    <section class="card animate-fade-in-up" style="animation-delay: 140ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.maintenance.complete_maintenance') }}</h3>
            <form method="POST" action="{{ route('admin.maintenance.complete', $maintenance) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="notes" class="form-label">{{ __('messages.maintenance.result_notes') }}</label>
                        <textarea name="notes" id="notes" rows="3" class="form-input" placeholder="{{ __('messages.maintenance.result_notes_placeholder') }}"></textarea>
                    </div>
                    <div>
                        <label for="cost" class="form-label">{{ __('messages.maintenance.cost_label') }}</label>
                        <input type="number" name="cost" id="cost" min="0" class="form-input w-56" placeholder="0">
                    </div>
                    <button type="submit" class="btn-primary">{{ __('messages.maintenance.confirm_complete') }}</button>
                </div>
            </form>
        </div>
    </section>
    @endif
</div>
@endsection

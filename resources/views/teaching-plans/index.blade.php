@extends('layouts.app')

@section('title', __('messages.teaching_plan.title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.teaching_plan.title') }}</p>
        <h2 class="resource-title">{{ __('messages.teaching_plan.list_title') }}</h2>
        <p class="resource-copy">
            {{ __('messages.teaching_plan.list_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.teaching_plan.total_plans') }}: {{ number_format($plans->total()) }}</span>
            <span class="meta-chip">{{ __('messages.teaching_plan.page') }}: {{ $plans->currentPage() }}/{{ $plans->lastPage() }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('teaching-plans.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.teaching_plan.create') }}
            </a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.teaching_plan.week') }}</th>
                        <th>{{ __('messages.teaching_plan.date') }}</th>
                        <th>{{ __('messages.teaching_plan.subject_lesson') }}</th>
                        <th>{{ __('messages.teaching_plan.equipment') }}</th>
                        <th>{{ __('messages.teaching_plan.quantity') }}</th>
                        <th>{{ __('messages.teaching_plan.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($plans as $plan)
                    <tr class="{{ $plan->planned_date < now() ? 'bg-gray-50/70 dark:bg-gray-800/35' : '' }}">
                        <td>{{ __('messages.teaching_plan.week') }} {{ $plan->week }}</td>
                        <td>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $plan->planned_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.teaching_plan.period') }} {{ $plan->period }}</div>
                        </td>
                        <td>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $plan->subject }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plan->lesson_name }}</p>
                        </td>
                        <td>{{ $plan->equipment->name }}</td>
                        <td>{{ $plan->quantity_needed }} {{ $plan->equipment->unit }}</td>
                        <td>
                            @if($plan->hasBorrowRecord())
                            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ __('messages.teaching_plan.registered') }}</span>
                            @elseif($plan->planned_date < now())
                            <span class="table-pill bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">{{ __('messages.teaching_plan.passed') }}</span>
                            @else
                            <span class="table-pill bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">{{ __('messages.teaching_plan.not_registered') }}</span>
                            @endif
                        </td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('teaching-plans.show', $plan) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.teaching_plan.view') }}</a>
                            @if(!$plan->hasBorrowRecord() && $plan->planned_date >= now())
                            <a href="{{ route('borrow.create', ['plan' => $plan->id]) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-300 dark:hover:text-emerald-200">{{ __('messages.teaching_plan.borrow') }}</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            {{ __('messages.teaching_plan.no_plans') }}
                            <a href="{{ route('teaching-plans.create') }}" class="font-semibold text-teal-700 hover:underline dark:text-teal-300">{{ __('messages.teaching_plan.create_new') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $plans->links() }}
    </div>
</div>
@endsection

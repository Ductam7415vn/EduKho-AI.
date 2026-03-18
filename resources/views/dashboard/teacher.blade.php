@extends('layouts.app')

@section('title', __('messages.dashboard.teacher.title'))

@section('content')
<div class="dashboard-shell">
    <section class="dashboard-hero animate-fade-in-up">
        <p class="dashboard-kicker">{{ __('messages.dashboard.teacher.kicker') }}</p>
        <h2 class="dashboard-title">{{ __('messages.dashboard.teacher.headline') }}</h2>
        <p class="dashboard-copy">
            {{ __('messages.dashboard.teacher.description') }}
        </p>
        <div class="dashboard-hero-meta">
            <span class="meta-chip">{{ __('messages.dashboard.teacher.active_borrows') }}: {{ $stats['active_borrows'] }}</span>
            <span class="meta-chip">{{ __('messages.dashboard.teacher.pending') }}: {{ $stats['pending_approvals'] }}</span>
            <span class="meta-chip">{{ __('messages.today') }}: {{ now()->format('d/m/Y') }}</span>
        </div>
    </section>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="stat-card stat-card-indigo group animate-fade-in-up" style="animation-delay: 0ms;">
            <div class="flex items-center gap-3">
                <div class="icon-container icon-container-indigo group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_borrows'] }}</h4>
                    <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.total_records') }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-emerald group animate-fade-in-up" style="animation-delay: 100ms;">
            <div class="flex items-center gap-3">
                <div class="icon-container icon-container-blue group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active_borrows'] }}</h4>
                    <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.borrowing') }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-amber group animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="flex items-center gap-3">
                <div class="icon-container icon-container-emerald group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['returned_borrows'] }}</h4>
                    <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.returned') }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-rose group animate-fade-in-up" style="animation-delay: 300ms;">
            <div class="flex items-center gap-3">
                <div class="icon-container icon-container-amber group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_approvals'] }}</h4>
                    <p class="text-xs uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.pending') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <a href="{{ route('borrow.create') }}" class="quick-action quick-action-indigo animate-fade-in-up" style="animation-delay: 400ms;">
            <div class="p-3 rounded-xl bg-white/20 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">{{ __('messages.dashboard.teacher.register_borrow') }}</h3>
                <p class="text-white/80 text-sm">{{ __('messages.dashboard.teacher.create_new_borrow') }}</p>
            </div>
            <svg class="w-6 h-6 ml-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('ai.chat') }}" class="quick-action quick-action-purple animate-fade-in-up" style="animation-delay: 500ms;">
            <div class="p-3 rounded-xl bg-white/20 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">{{ __('messages.dashboard.teacher.ai_assistant') }}</h3>
                <p class="text-white/80 text-sm">{{ __('messages.dashboard.teacher.schedule_naturally') }}</p>
            </div>
            <svg class="w-6 h-6 ml-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('equipment.index') }}" class="quick-action quick-action-emerald animate-fade-in-up" style="animation-delay: 600ms;">
            <div class="p-3 rounded-xl bg-white/20 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">{{ __('messages.dashboard.teacher.search_inventory') }}</h3>
                <p class="text-white/80 text-sm">{{ __('messages.dashboard.teacher.view_available') }}</p>
            </div>
            <svg class="w-6 h-6 ml-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card animate-fade-in-up" style="animation-delay: 700ms;">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2 animate-pulse"></span>
                    {{ __('messages.dashboard.teacher.currently_borrowing') }}
                </h3>
            </div>
            <div class="p-6">
                @forelse($activeBorrows as $borrow)
                <div class="dashboard-list-row">
                    <div class="flex items-center space-x-3">
                        <div class="dashboard-avatar">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            @foreach($borrow->details as $detail)
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $detail->equipmentItem->equipment->name }}</p>
                            @endforeach
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.return_before') }}: {{ $borrow->expected_return_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('borrow.show', $borrow) }}" class="px-3 py-1.5 text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 hover:bg-teal-50 dark:hover:bg-teal-900/30 rounded-lg transition-colors">
                        {{ __('messages.dashboard.teacher.detail') }}
                    </a>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.no_borrows') }}</p>
                    <a href="{{ route('borrow.create') }}" class="inline-flex items-center mt-3 text-sm text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 font-semibold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('messages.dashboard.teacher.create_new_borrow') }}
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <div class="card animate-fade-in-up" style="animation-delay: 800ms;">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-teal-500 mr-2"></span>
                    {{ __('messages.dashboard.teacher.upcoming_plans') }}
                </h3>
                <a href="{{ route('teaching-plans.index') }}" class="dashboard-section-link group">
                    {{ __('messages.dashboard.admin.view_all') }}
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="p-6">
                @forelse($myPlans as $plan)
                <div class="dashboard-list-row">
                    <div class="flex items-center space-x-3">
                        <div class="dashboard-avatar bg-gradient-to-br from-cyan-500 to-teal-600">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $plan->lesson_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plan->planned_date->format('d/m/Y') }} - {{ __('messages.borrow.period') }} {{ $plan->period }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $plan->equipment->name }} ({{ $plan->quantity_needed }} {{ $plan->equipment->unit }})</p>
                        </div>
                    </div>
                    @if(!$plan->hasBorrowRecord())
                    <a href="{{ route('borrow.create', ['plan' => $plan->id]) }}" class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg gradient-primary hover:shadow-lg transition-all duration-200 hover:translate-y-[-1px]">
                        {{ __('messages.dashboard.teacher.create_record') }}
                    </a>
                    @else
                    <span class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg">
                        {{ __('messages.dashboard.teacher.registered') }}
                    </span>
                    @endif
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-teal-100 dark:bg-teal-900/50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.no_plans') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="chart-container chart-container-indigo animate-fade-in-up" style="animation-delay: 900ms;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="dashboard-section-title">
                <span class="w-2 h-2 rounded-full bg-sky-500 mr-2"></span>
                {{ __('messages.dashboard.teacher.my_borrows_chart') }}
            </h3>
        </div>
        <canvas id="myBorrowsChart" height="100"></canvas>
    </div>

    <div class="card animate-fade-in-up" style="animation-delay: 1000ms;">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="dashboard-section-title">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                {{ __('messages.dashboard.teacher.recent_history') }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('messages.borrow.equipment') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('messages.borrow.borrow_date') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('messages.borrow.period') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">{{ __('messages.borrow.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($myBorrows as $borrow)
                    <tr class="hover:bg-teal-50/60 dark:hover:bg-teal-900/20 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-sky-600 flex items-center justify-center flex-shrink-0 mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <div>
                                    @foreach($borrow->details as $detail)
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $detail->equipmentItem->equipment->name }}</span>
                                    @if(!$loop->last)<br>@endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ __('messages.borrow.period') }} {{ $borrow->period }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-medium rounded-full
                                @if($borrow->status === 'active') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300
                                @elseif($borrow->status === 'returned') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                                @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                                @endif">
                                {{ $borrow->status === 'active' ? __('messages.borrow.borrowed') : ($borrow->status === 'returned' ? __('messages.borrow.returned') : __('messages.borrow.overdue')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('borrow.show', $borrow) }}" class="text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 font-semibold">{{ __('messages.view') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.teacher.no_history') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const axisColor = isDark ? '#cbd5e1' : '#475569';
    const gridColor = isDark ? 'rgba(148, 163, 184, 0.18)' : 'rgba(15, 23, 42, 0.08)';
    const tooltipBg = isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(15, 23, 42, 0.9)';

    const monthlyData = @json($monthlyBorrows);
    const monthNames = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];

    Chart.defaults.font.family = 'Manrope, system-ui, sans-serif';
    Chart.defaults.color = axisColor;

    const ctx = document.getElementById('myBorrowsChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 120);
    gradient.addColorStop(0, 'rgba(2, 132, 199, 0.38)');
    gradient.addColorStop(1, 'rgba(2, 132, 199, 0.06)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => monthNames[d.month - 1] + '/' + d.year),
            datasets: [{
                label: '{{ __('messages.dashboard.borrow_count') }}',
                data: monthlyData.map(d => d.count),
                backgroundColor: gradient,
                borderColor: 'rgb(2, 132, 199)',
                borderWidth: 1.5,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: axisColor },
                    grid: { color: gridColor }
                },
                x: {
                    ticks: { color: axisColor },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush

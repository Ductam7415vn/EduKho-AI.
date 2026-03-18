@extends('layouts.app')

@section('title', __('messages.dashboard.admin.title'))

@section('content')
<div class="dashboard-shell">
    <section class="dashboard-hero animate-fade-in-up">
        <p class="dashboard-kicker">{{ __('messages.dashboard.admin.kicker') }}</p>
        <h2 class="dashboard-title">{{ __('messages.dashboard.admin.headline') }}</h2>
        <p class="dashboard-copy">
            {{ __('messages.dashboard.admin.description') }}
        </p>
        <div class="dashboard-hero-meta">
            <span class="meta-chip">{{ __('messages.updated') }}: {{ now()->format('d/m/Y') }}</span>
            <span class="meta-chip">{{ __('messages.dashboard.admin.pending_approvals') }}: {{ number_format($stats['pending_approvals']) }}</span>
            <span class="meta-chip">{{ __('messages.dashboard.admin.overdue') }}: {{ number_format($stats['overdue_records']) }}</span>
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card stat-card-indigo group animate-fade-in-up" style="animation-delay: 0ms;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.admin.equipment_categories') }}</p>
                    <h4 class="font-display text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_equipment']) }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('messages.dashboard.admin.equipment_types') }}</p>
                </div>
                <div class="icon-container icon-container-indigo group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300">+12% {{ __('messages.dashboard.admin.compared_previous') }}</span>
            </div>
        </div>

        <div class="stat-card stat-card-emerald group animate-fade-in-up" style="animation-delay: 100ms;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.admin.available_today') }}</p>
                    <h4 class="font-display text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['available_items']) }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('messages.dashboard.admin.ready_to_borrow') }}</p>
                </div>
                <div class="icon-container icon-container-emerald group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">+8% {{ __('messages.dashboard.admin.available_percent') }}</span>
            </div>
        </div>

        <div class="stat-card stat-card-amber group animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.admin.tasks_to_approve') }}</p>
                    <h4 class="font-display text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['pending_approvals']) }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('messages.dashboard.admin.waiting_approval') }}</p>
                </div>
                <div class="icon-container icon-container-amber group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($stats['pending_approvals'] > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 animate-pulse">{{ __('messages.dashboard.admin.need_action') }}</span>
                @else
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ __('messages.dashboard.admin.stable') }}</span>
                @endif
            </div>
        </div>

        <div class="stat-card stat-card-rose group animate-fade-in-up" style="animation-delay: 300ms;">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ __('messages.dashboard.admin.late_return_risk') }}</p>
                    <h4 class="font-display text-4xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['overdue_records']) }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('messages.dashboard.admin.overdue_return') }}</p>
                </div>
                <div class="icon-container icon-container-rose group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($stats['overdue_records'] > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 animate-pulse">{{ __('messages.dashboard.admin.warning') }}</span>
                @else
                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ __('messages.dashboard.admin.no_overdue') }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="chart-container chart-container-indigo animate-fade-in-up" style="animation-delay: 400ms;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-sky-500 mr-2"></span>
                    {{ __('messages.dashboard.admin.monthly_borrows') }}
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">12 {{ __('messages.dashboard.admin.months') }}</span>
            </div>
            <canvas id="monthlyBorrowsChart" height="200"></canvas>
        </div>

        <div class="chart-container chart-container-emerald animate-fade-in-up" style="animation-delay: 500ms;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-teal-500 mr-2"></span>
                    {{ __('messages.dashboard.admin.equipment_by_subject') }}
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">{{ __('messages.dashboard.admin.overview') }}</span>
            </div>
            <canvas id="equipmentBySubjectChart" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="chart-container animate-fade-in-up" style="animation-delay: 600ms;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    {{ __('messages.dashboard.admin.equipment_status') }}
                </h3>
            </div>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        <div class="chart-container lg:col-span-2 animate-fade-in-up" style="animation-delay: 700ms;">
            <div class="flex items-center justify-between mb-6">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2"></span>
                    {{ __('messages.dashboard.admin.most_borrowed') }}
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">{{ __('messages.dashboard.admin.top') }} 10</span>
            </div>
            <canvas id="topBorrowedChart" height="150"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card animate-fade-in-up" style="animation-delay: 800ms;">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2"></span>
                    {{ __('messages.dashboard.admin.recent_borrows') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentBorrows as $borrow)
                    <div class="dashboard-list-row">
                        <div class="flex items-center space-x-3">
                            <div class="dashboard-avatar">
                                {{ substr($borrow->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $borrow->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $borrow->borrow_date->format('d/m/Y') }} - {{ __('messages.borrow.period') }} {{ $borrow->period }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 text-xs font-medium rounded-full
                            @if($borrow->status === 'active') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300
                            @elseif($borrow->status === 'returned') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                            @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                            @endif">
                            {{ $borrow->status === 'active' ? __('messages.borrow.borrowed') : ($borrow->status === 'returned' ? __('messages.borrow.returned') : __('messages.borrow.overdue')) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.admin.no_borrows') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card animate-fade-in-up" style="animation-delay: 900ms;">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="dashboard-section-title">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span>
                    {{ __('messages.dashboard.admin.pending_approval') }}
                </h3>
                <a href="{{ route('admin.approvals.index') }}" class="dashboard-section-link group">
                    {{ __('messages.dashboard.admin.view_all') }}
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($pendingApprovals as $pending)
                    <div class="dashboard-list-row">
                        <div class="flex items-center space-x-3">
                            <div class="dashboard-avatar bg-gradient-to-br from-amber-500 to-orange-500">
                                {{ substr($pending->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $pending->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-[220px]">
                                    @foreach($pending->details as $detail)
                                    {{ $detail->equipmentItem->equipment->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('admin.approvals.approve', $pending) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-400 dark:hover:bg-emerald-900 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('messages.dashboard.admin.no_pending') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
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

    const colors = {
        primary: 'rgb(15, 76, 129)',
        primarySoft: 'rgba(15, 76, 129, 0.16)',
        success: 'rgb(15, 118, 110)',
        successSoft: 'rgba(15, 118, 110, 0.16)',
        warning: 'rgb(180, 83, 9)',
        danger: 'rgb(190, 18, 60)',
        info: 'rgb(14, 116, 144)',
        sky: 'rgb(2, 132, 199)',
        emerald: 'rgb(5, 150, 105)',
        amber: 'rgb(245, 158, 11)',
        rose: 'rgb(244, 63, 94)',
    };

    const colorArray = [
        colors.primary,
        colors.success,
        colors.warning,
        colors.info,
        colors.sky,
        colors.emerald,
        colors.amber,
        colors.rose,
    ];

    Chart.defaults.font.family = 'Manrope, system-ui, sans-serif';
    Chart.defaults.color = axisColor;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 20;
    Chart.defaults.plugins.legend.labels.color = axisColor;

    const monthNames = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];
    const monthlyData = @json($chartData['monthlyBorrows']);

    const monthlyCtx = document.getElementById('monthlyBorrowsChart').getContext('2d');
    const monthlyGradient = monthlyCtx.createLinearGradient(0, 0, 0, 220);
    monthlyGradient.addColorStop(0, 'rgba(2, 132, 199, 0.32)');
    monthlyGradient.addColorStop(1, 'rgba(2, 132, 199, 0.02)');

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => monthNames[d.month - 1] + '/' + d.year),
            datasets: [{
                label: '{{ __('messages.dashboard.borrow_count') }}',
                data: monthlyData.map(d => d.count),
                borderColor: colors.sky,
                backgroundColor: monthlyGradient,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: colors.sky,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
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

    const subjectData = @json($chartData['equipmentBySubject']);
    new Chart(document.getElementById('equipmentBySubjectChart'), {
        type: 'bar',
        data: {
            labels: subjectData.map(d => d.category_subject),
            datasets: [{
                label: '{{ __('messages.dashboard.quantity') }}',
                data: subjectData.map(d => d.count),
                backgroundColor: colorArray.slice(0, subjectData.length),
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
                    padding: 12,
                    cornerRadius: 8,
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

    const statusData = @json($chartData['statusDistribution']);
    const statusLabels = {
        'available': '{{ __('messages.dashboard.status.available') }}',
        'borrowed': '{{ __('messages.dashboard.status.borrowed') }}',
        'maintenance': '{{ __('messages.dashboard.status.maintenance') }}',
        'broken': '{{ __('messages.dashboard.status.broken') }}',
        'lost': '{{ __('messages.dashboard.status.lost') }}'
    };

    const statusColors = {
        'available': colors.emerald,
        'borrowed': colors.info,
        'maintenance': colors.amber,
        'broken': colors.rose,
        'lost': colors.danger,
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(d => statusLabels[d.status] || d.status),
            datasets: [{
                data: statusData.map(d => d.count),
                backgroundColor: statusData.map(d => statusColors[d.status] || colors.primary),
                borderWidth: 0,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20, color: axisColor }
                },
                tooltip: {
                    backgroundColor: tooltipBg,
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });

    const topData = @json($chartData['topBorrowed']);
    new Chart(document.getElementById('topBorrowedChart'), {
        type: 'bar',
        data: {
            labels: topData.map(d => d.name.length > 20 ? d.name.substring(0, 20) + '...' : d.name),
            datasets: [{
                label: '{{ __('messages.dashboard.borrow_count') }}',
                data: topData.map(d => d.borrow_count),
                backgroundColor: colors.primary,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    padding: 12,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: axisColor },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: axisColor },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush

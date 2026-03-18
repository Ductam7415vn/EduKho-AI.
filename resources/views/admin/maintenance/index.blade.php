@extends('layouts.app')

@section('title', 'Bao tri thiet bi')

@section('content')
<div class="resource-shell">
    @php
        $statusLabels = [
            'scheduled' => 'Da len lich',
            'in_progress' => 'Dang xu ly',
            'completed' => 'Da hoan thanh',
            'cancelled' => 'Da huy',
        ];
        $statusClasses = [
            'scheduled' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
            'in_progress' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
        ];
        $priorityLabels = [
            'low' => 'Thap',
            'medium' => 'Trung binh',
            'high' => 'Cao',
            'urgent' => 'Khan',
        ];
        $priorityClasses = [
            'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
            'medium' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
            'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
            'urgent' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300',
        ];
    @endphp

    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Quan tri thiet bi</p>
        <h2 class="resource-title">Bao tri thiet bi</h2>
        <p class="resource-copy">Theo doi lich bao tri, uu tien xu ly va cap nhat trang thai thiet bi dang duoc sua chua.</p>
        <div class="resource-actions">
            <a href="{{ route('admin.maintenance.create') }}" class="btn-primary">Tao lich bao tri</a>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 animate-fade-in-up" style="animation-delay: 60ms;">
        <article class="card">
            <div class="card-body">
                <p class="text-sm text-gray-500 dark:text-gray-400">Da len lich</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['scheduled']) }}</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <p class="text-sm text-gray-500 dark:text-gray-400">Dang xu ly</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['in_progress']) }}</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <p class="text-sm text-gray-500 dark:text-gray-400">Qua han</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['overdue']) }}</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <p class="text-sm text-gray-500 dark:text-gray-400">Hoan thanh thang nay</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['completed_this_month']) }}</p>
            </div>
        </article>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 100ms;">
        <form method="GET" class="flex flex-wrap gap-3">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trang thai</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Tat ca</option>
                    @foreach($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loai</label>
                <select id="type" name="type" class="form-select">
                    <option value="">Tat ca</option>
                    <option value="preventive" @selected(request('type') === 'preventive')>Phong ngua</option>
                    <option value="corrective" @selected(request('type') === 'corrective')>Khac phuc</option>
                    <option value="inspection" @selected(request('type') === 'inspection')>Kiem tra</option>
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uu tien</label>
                <select id="priority" name="priority" class="form-select">
                    <option value="">Tat ca</option>
                    @foreach($priorityLabels as $value => $label)
                        <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-secondary">Loc</button>
                @if(request()->hasAny(['status', 'type', 'priority']))
                    <a href="{{ route('admin.maintenance.index') }}" class="btn-secondary">Bo loc</a>
                @endif
            </div>
        </form>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 140ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cong viec</th>
                        <th>Thiet bi</th>
                        <th>Ngay du kien</th>
                        <th>Uu tien</th>
                        <th>Trang thai</th>
                        <th>Nguoi tao</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($schedules as $schedule)
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $schedule->title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if($schedule->type === 'preventive') Phong ngua
                                    @elseif($schedule->type === 'corrective') Khac phuc
                                    @else Kiem tra
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $schedule->equipmentItem->equipment->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $schedule->equipmentItem->specific_code }}</div>
                            </td>
                            <td class="text-gray-600 dark:text-gray-300">
                                {{ $schedule->scheduled_date->format('d/m/Y') }}
                                @if($schedule->isOverdue())
                                    <div class="text-xs text-rose-600 dark:text-rose-300">Qua han</div>
                                @endif
                            </td>
                            <td>
                                <span class="table-pill {{ $priorityClasses[$schedule->priority] }}">
                                    {{ $priorityLabels[$schedule->priority] }}
                                </span>
                            </td>
                            <td>
                                <span class="table-pill {{ $statusClasses[$schedule->status] }}">
                                    {{ $statusLabels[$schedule->status] }}
                                </span>
                            </td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $schedule->creator->name }}</td>
                            <td class="text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.maintenance.show', $schedule) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">Xem</a>
                                    @if($schedule->isScheduled())
                                        <form method="POST" action="{{ route('admin.maintenance.start', $schedule) }}">
                                            @csrf
                                            <button type="submit" class="text-sm font-semibold text-amber-700 hover:text-amber-800 dark:text-amber-300 dark:hover:text-amber-200">Bat dau</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.maintenance.cancel', $schedule) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-rose-700 hover:text-rose-800 dark:text-rose-300 dark:hover:text-rose-200" onclick="return confirm('Ban co chac muon huy lich bao tri nay?')">Huy</button>
                                        </form>
                                    @elseif($schedule->isInProgress())
                                        <a href="{{ route('admin.maintenance.show', $schedule) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-300 dark:hover:text-emerald-200">Hoan thanh</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <p>Chua co lich bao tri nao phu hop voi bo loc hien tai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $schedules->links() }}
    </div>
</div>
@endsection

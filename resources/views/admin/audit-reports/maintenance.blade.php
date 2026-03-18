@extends('layouts.app')

@section('title', 'Audit bao tri')

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Audit report</p>
        <h2 class="resource-title">Bao tri va hu hong</h2>
        <p class="resource-copy">Theo doi khoi luong bao tri, tinh hinh hu hong va tong chi phi lien quan den van hanh thiet bi.</p>
        <div class="resource-actions">
            <a href="{{ route('admin.audit-reports.export', ['type' => 'maintenance', 'from_date' => request('from_date', \Illuminate\Support\Carbon::parse($fromDate)->format('Y-m-d')), 'to_date' => request('to_date', \Illuminate\Support\Carbon::parse($toDate)->format('Y-m-d'))]) }}" class="btn-primary">Export CSV</a>
        </div>
    </section>

    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <form method="GET" class="flex flex-wrap gap-3">
            <div>
                <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tu ngay</label>
                <input id="from_date" name="from_date" type="date" value="{{ request('from_date', \Illuminate\Support\Carbon::parse($fromDate)->format('Y-m-d')) }}" class="form-input">
            </div>
            <div>
                <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Den ngay</label>
                <input id="to_date" name="to_date" type="date" value="{{ request('to_date', \Illuminate\Support\Carbon::parse($toDate)->format('Y-m-d')) }}" class="form-input">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-secondary">Loc</button>
            </div>
        </form>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 animate-fade-in-up" style="animation-delay: 120ms;">
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Da len lich</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($maintenanceStats->get('scheduled')->count ?? 0) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Dang xu ly</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($maintenanceStats->get('in_progress')->count ?? 0) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Chi phi bao tri</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCost) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Chi phi hu hong</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalDamageCost, 2) }}</p></div></article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 animate-fade-in-up" style="animation-delay: 160ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Thong ke bao tri</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Trang thai</th>
                                <th class="text-right">So luong</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($maintenanceStats as $status => $row)
                                <tr>
                                    <td>{{ $status }}</td>
                                    <td class="text-right">{{ number_format($row->count) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="empty-state">Khong co lich bao tri trong khoang nay.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Thong ke hu hong</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Muc do</th>
                                <th class="text-right">So luong</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($damageStats as $severity => $row)
                                <tr>
                                    <td>{{ $severity }}</td>
                                    <td class="text-right">{{ number_format($row->count) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="empty-state">Khong co bao cao hu hong trong khoang nay.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

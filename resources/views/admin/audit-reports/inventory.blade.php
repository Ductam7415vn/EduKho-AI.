@extends('layouts.app')

@section('title', 'Audit ton kho')

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Audit report</p>
        <h2 class="resource-title">Kiem ke ton kho</h2>
        <p class="resource-copy">Tong hop so lieu ton kho, gia tri uoc tinh va danh sach thiet bi can uu tien theo doi.</p>
        <div class="resource-actions">
            <a href="{{ route('admin.audit-reports.export', ['type' => 'inventory', 'from_date' => request('from_date', \Illuminate\Support\Carbon::parse($fromDate)->format('Y-m-d')), 'to_date' => request('to_date', \Illuminate\Support\Carbon::parse($toDate)->format('Y-m-d'))]) }}" class="btn-primary">Export CSV</a>
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
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Loai thiet bi</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['total_types']) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Tong item</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['total_items']) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">San sang</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['available']) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Tong gia tri</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalValue) }}</p></div></article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 animate-fade-in-up" style="animation-delay: 160ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Bien dong ton kho</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-4 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">
                        <p class="text-sm">Tang kho</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($inventoryChanges->get('increase')->total ?? 0) }}</p>
                    </div>
                    <div class="rounded-2xl bg-rose-50 px-4 py-4 text-rose-800 dark:bg-rose-900/20 dark:text-rose-200">
                        <p class="text-sm">Giam kho</p>
                        <p class="mt-2 text-2xl font-bold">{{ number_format($inventoryChanges->get('decrease')->total ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Trang thai item</h3>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white px-4 py-4 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"><p class="text-sm text-gray-500 dark:text-gray-400">Dang muon</p><p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['borrowed']) }}</p></div>
                    <div class="rounded-2xl bg-white px-4 py-4 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"><p class="text-sm text-gray-500 dark:text-gray-400">Bao tri</p><p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['maintenance']) }}</p></div>
                    <div class="rounded-2xl bg-white px-4 py-4 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"><p class="text-sm text-gray-500 dark:text-gray-400">Hong</p><p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['broken']) }}</p></div>
                    <div class="rounded-2xl bg-white px-4 py-4 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"><p class="text-sm text-gray-500 dark:text-gray-400">Mat</p><p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($equipmentStats['lost']) }}</p></div>
                </div>
            </div>
        </article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 animate-fade-in-up" style="animation-delay: 200ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Top gia tri cao</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thiet bi</th>
                                <th>Mon hoc</th>
                                <th class="text-right">Gia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($highValueItems as $equipment)
                                <tr>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->category_subject }}</td>
                                    <td class="text-right">{{ number_format($equipment->price ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Khong co du lieu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Canh bao sap het</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thiet bi</th>
                                <th>Con lai</th>
                                <th>Nguong</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lowStockItems as $equipment)
                                <tr>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->availableCount() }}</td>
                                    <td>{{ $equipment->low_stock_threshold ?? 2 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Khong co thiet bi nao dang o muc sap het.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Audit muon tra')

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Audit report</p>
        <h2 class="resource-title">Kiem soat muon tra</h2>
        <p class="resource-copy">Tong hop luong muon tra, nguoi muon nhieu va cac phieu dang qua han.</p>
        <div class="resource-actions">
            <a href="{{ route('admin.audit-reports.export', ['type' => 'borrow', 'from_date' => request('from_date', \Illuminate\Support\Carbon::parse($fromDate)->format('Y-m-d')), 'to_date' => request('to_date', \Illuminate\Support\Carbon::parse($toDate)->format('Y-m-d'))]) }}" class="btn-primary">Export CSV</a>
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
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Dang muon</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($borrowStats->get('active')->count ?? 0) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Da tra</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($borrowStats->get('returned')->count ?? 0) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Qua han</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($borrowStats->get('overdue')->count ?? 0) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Nguoi muon noi bat</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($topBorrowers->count()) }}</p></div></article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 animate-fade-in-up" style="animation-delay: 160ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Top nguoi muon</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nguoi dung</th>
                                <th>Email</th>
                                <th class="text-right">So phieu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($topBorrowers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-right">{{ $user->borrow_records_count }}</td>
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
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Xu huong theo thang</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thang</th>
                                <th class="text-right">So luot</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($monthlyTrend as $trend)
                                <tr>
                                    <td>{{ $trend->label }}</td>
                                    <td class="text-right">{{ number_format($trend->count) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="empty-state">Khong co du lieu trong khoang thoi gian nay.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 200ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Danh sach qua han</h3>
            <div class="mt-4 overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ma phieu</th>
                            <th>Nguoi muon</th>
                            <th>Thiet bi</th>
                            <th>Han tra</th>
                            <th>Trang thai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($overdueRecords as $record)
                            <tr>
                                <td>#{{ $record->id }}</td>
                                <td>{{ $record->user->name }}</td>
                                <td>{{ $record->details->map(fn ($detail) => $detail->equipmentItem->equipment->name)->join(', ') }}</td>
                                <td>{{ $record->expected_return_date->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($record->status) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state">Khong co phieu qua han.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

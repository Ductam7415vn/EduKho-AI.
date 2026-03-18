@extends('layouts.app')

@section('title', 'Audit hoat dong')

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Audit report</p>
        <h2 class="resource-title">Hoat dong nguoi dung</h2>
        <p class="resource-copy">Tong hop cac hanh dong he thong ghi nhan, nguoi dung tich cuc va so lan dang nhap dang xuat.</p>
        <div class="resource-actions">
            <a href="{{ route('admin.audit-reports.export', ['type' => 'activity', 'from_date' => request('from_date', \Illuminate\Support\Carbon::parse($fromDate)->format('Y-m-d')), 'to_date' => request('to_date', \Illuminate\Support\Carbon::parse($toDate)->format('Y-m-d'))]) }}" class="btn-primary">Export CSV</a>
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
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Tong hanh dong</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($activityByAction->sum('count')) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Dang nhap</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($loginAttempts) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Dang xuat</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($logoutCount) }}</p></div></article>
        <article class="card"><div class="card-body"><p class="text-sm text-gray-500 dark:text-gray-400">Nguoi dung tich cuc</p><p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($mostActiveUsers->count()) }}</p></div></article>
    </section>

    <section class="grid gap-4 lg:grid-cols-2 animate-fade-in-up" style="animation-delay: 160ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Theo hanh dong</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Hanh dong</th>
                                <th class="text-right">So lan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($activityByAction as $row)
                                <tr>
                                    <td>{{ $row->action }}</td>
                                    <td class="text-right">{{ number_format($row->count) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="empty-state">Khong co log trong khoang nay.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Nguoi dung tich cuc</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nguoi dung</th>
                                <th>Email</th>
                                <th class="text-right">So log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($mostActiveUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-right">{{ number_format($user->activity_logs_count) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="empty-state">Chua co du lieu nguoi dung tich cuc.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

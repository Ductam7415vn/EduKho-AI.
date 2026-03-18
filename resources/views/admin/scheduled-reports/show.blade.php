@extends('layouts.app')

@section('title', 'Chi tiet bao cao tu dong')

@section('content')
<div class="resource-shell max-w-5xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Quan tri bao cao</p>
        <h2 class="resource-title">Chi tiet bao cao tu dong</h2>
        <p class="resource-copy">{{ $scheduledReport->name }}</p>
        <div class="resource-meta">
            <span class="table-pill {{ $scheduledReport->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                {{ $scheduledReport->is_active ? 'Dang hoat dong' : 'Tam dung' }}
            </span>
            <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">
                {{ $scheduledReport->frequency_label }}
            </span>
            <span class="table-pill bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                {{ $scheduledReport->report_type_label }}
            </span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.scheduled-reports.edit', $scheduledReport) }}" class="btn-primary">Chinh sua</a>
            <a href="{{ route('admin.scheduled-reports.index') }}" class="btn-secondary">Quay lai</a>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-[2fr,1fr] animate-fade-in-up" style="animation-delay: 80ms;">
        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Thong tin cau hinh</h3>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nguoi tao</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Loai bao cao</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->report_type_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tan suat</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->frequency_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gio gui</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->send_time }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lan gui cuoi</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->last_sent_at?->format('d/m/Y H:i') ?? 'Chua gui' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lan chay tiep theo</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $scheduledReport->next_run_at?->format('d/m/Y H:i') ?? 'Chua tinh' }}</dd>
                    </div>
                    @if($scheduledReport->frequency === 'weekly')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngay trong tuan</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @php
                                    $days = [0 => 'Chu Nhat', 1 => 'Thu Hai', 2 => 'Thu Ba', 3 => 'Thu Tu', 4 => 'Thu Nam', 5 => 'Thu Sau', 6 => 'Thu Bay'];
                                @endphp
                                {{ $days[$scheduledReport->day_of_week ?? 1] }}
                            </dd>
                        </div>
                    @endif
                    @if($scheduledReport->frequency === 'monthly')
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngay trong thang</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">Ngay {{ $scheduledReport->day_of_month ?? 1 }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </article>

        <article class="card">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Thao tac</h3>
                <div class="mt-4 space-y-3">
                    <form method="POST" action="{{ route('admin.scheduled-reports.toggle', $scheduledReport) }}">
                        @csrf
                        <button type="submit" class="btn-secondary w-full">
                            {{ $scheduledReport->is_active ? 'Tam dung bao cao' : 'Kich hoat bao cao' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.scheduled-reports.destroy', $scheduledReport) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full" onclick="return confirm('Ban co chac muon xoa bao cao tu dong nay?')">
                            Xoa bao cao
                        </button>
                    </form>
                </div>
            </div>
        </article>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Danh sach nguoi nhan</h3>
            <div class="mt-4 flex flex-wrap gap-2">
                @forelse($scheduledReport->recipients as $recipient)
                    <span class="table-pill bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">{{ $recipient }}</span>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Chua co nguoi nhan nao.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 160ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">Bo loc da luu</h3>
            @if(!empty($scheduledReport->filters))
                <pre class="mt-4 overflow-x-auto rounded-2xl bg-gray-950/95 px-4 py-4 text-sm text-cyan-100">{{ json_encode($scheduledReport->filters, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            @else
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Bao cao nay chua luu bo loc rieng.</p>
            @endif
        </div>
    </section>
</div>
@endsection

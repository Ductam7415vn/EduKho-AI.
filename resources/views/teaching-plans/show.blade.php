@extends('layouts.app')

@section('title', 'Chi tiet ke hoach')

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Chi tiet ke hoach</p>
        <h2 class="resource-title">{{ $teachingPlan->lesson_name }}</h2>
        <p class="resource-copy">{{ $teachingPlan->subject }} - Tuan {{ $teachingPlan->week }}</p>
        <div class="resource-meta">
            <span class="meta-chip">Ngay: {{ $teachingPlan->planned_date->format('d/m/Y') }}</span>
            <span class="meta-chip">Tiet: {{ $teachingPlan->period }}</span>
            @if($teachingPlan->hasBorrowRecord())
            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Da dang ky</span>
            @elseif($teachingPlan->planned_date < now())
            <span class="table-pill bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">Da qua</span>
            @else
            <span class="table-pill bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Chua dang ky</span>
            @endif
        </div>
        <div class="resource-actions">
            @if(!$teachingPlan->hasBorrowRecord() && $teachingPlan->planned_date >= now())
            <a href="{{ route('teaching-plans.edit', $teachingPlan) }}" class="btn-secondary">Sua</a>
            <a href="{{ route('borrow.create', ['plan' => $teachingPlan->id]) }}" class="btn-primary">Dang ky muon</a>
            @endif
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">Thong tin ke hoach</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Mon hoc</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teachingPlan->subject }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Tuan</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">Tuan {{ $teachingPlan->week }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Ngay du kien</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teachingPlan->planned_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Tiet</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">Tiet {{ $teachingPlan->period }} {{ $teachingPlan->period <= 5 ? '(Sang)' : '(Chieu)' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Thiet bi</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teachingPlan->equipment->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">So luong can</dt>
                    <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $teachingPlan->quantity_needed }} {{ $teachingPlan->equipment->unit }}</dd>
                </div>
            </dl>

            @if($teachingPlan->notes)
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">Ghi chu</dt>
                <dd class="text-gray-700 dark:text-gray-300">{{ $teachingPlan->notes }}</dd>
            </div>
            @endif
        </div>
    </section>

    @if($teachingPlan->borrowRecord)
    <section class="card animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-emerald-800 dark:text-emerald-300 mb-2">Da dang ky muon</h3>
            <p class="text-emerald-700 dark:text-emerald-300/90 mb-4">Ke hoach nay da duoc dang ky muon thiet bi.</p>
            <a href="{{ route('borrow.show', $teachingPlan->borrowRecord) }}" class="btn-success">
                Xem phieu muon #{{ $teachingPlan->borrowRecord->id }}
            </a>
        </div>
    </section>
    @endif

    <div class="flex justify-between">
        <a href="{{ route('teaching-plans.index') }}" class="btn-secondary">Quay lai</a>

        @if(!$teachingPlan->hasBorrowRecord())
        <form action="{{ route('teaching-plans.destroy', $teachingPlan) }}" method="POST" onsubmit="return confirm('Ban co chac muon xoa ke hoach nay?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Xoa ke hoach</button>
        </form>
        @endif
    </div>
</div>
@endsection

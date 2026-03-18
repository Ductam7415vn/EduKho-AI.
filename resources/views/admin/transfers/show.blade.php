@extends('layouts.app')

@section('title', 'Chi tiet dieu chuyen thiet bi')

@section('content')
<div class="resource-shell max-w-5xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Quan tri kho</p>
        <h2 class="resource-title">Chi tiet dieu chuyen thiet bi</h2>
        <p class="resource-copy">{{ $transfer->equipmentItem->equipment->name }} - {{ $transfer->equipmentItem->specific_code }}</p>
        <div class="resource-meta">
            <span class="meta-chip">Ngay chuyen: {{ $transfer->transfer_date->format('d/m/Y') }}</span>
            <span class="meta-chip">Nguoi thuc hien: {{ $transfer->transferredBy->name }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.transfers.item-history', $transfer->equipmentItem) }}" class="btn-primary">Xem lich su item</a>
            <a href="{{ route('admin.transfers.index') }}" class="btn-secondary">Quay lai</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body">
            <dl class="grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Thiet bi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->equipmentItem->equipment->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ma ca the</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->equipmentItem->specific_code }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phong cu</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->fromRoom?->name ?? 'Chua gan phong' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phong moi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->toRoom?->name ?? 'Chua gan phong' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Trang thai hien tai</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($transfer->equipmentItem->status) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nguoi thuc hien</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->transferredBy->name }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ly do</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->reason ?: 'Khong co ly do bo sung.' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ghi chu</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $transfer->notes ?: 'Khong co ghi chu.' }}</dd>
                </div>
            </dl>
        </div>
    </section>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Lich su dieu chuyen item')

@section('content')
<div class="resource-shell max-w-5xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">Quan tri kho</p>
        <h2 class="resource-title">Lich su dieu chuyen item</h2>
        <p class="resource-copy">{{ $equipmentItem->equipment->name }} - {{ $equipmentItem->specific_code }}</p>
        <div class="resource-meta">
            <span class="meta-chip">Phong hien tai: {{ $equipmentItem->room?->name ?? 'Chua gan phong' }}</span>
            <span class="meta-chip">Trang thai: {{ ucfirst($equipmentItem->status) }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('admin.transfers.create', ['item' => $equipmentItem->id]) }}" class="btn-primary">Tao dieu chuyen moi</a>
            <a href="{{ route('admin.transfers.index') }}" class="btn-secondary">Quay lai</a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ngay chuyen</th>
                        <th>Tu phong</th>
                        <th>Den phong</th>
                        <th>Nguoi chuyen</th>
                        <th>Ly do</th>
                        <th>Ghi chu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($transfers as $transfer)
                        <tr>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->transfer_date->format('d/m/Y') }}</td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->fromRoom?->name ?? 'Chua gan phong' }}</td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->toRoom?->name ?? 'Chua gan phong' }}</td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->transferredBy->name }}</td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->reason ?: 'Khong co' }}</td>
                            <td class="text-gray-600 dark:text-gray-300">{{ $transfer->notes ?: 'Khong co' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <p>Item nay chua co lich su dieu chuyen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

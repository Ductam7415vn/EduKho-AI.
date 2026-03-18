@extends('layouts.app')

@section('title', __('messages.approval.title'))

@section('content')
<div class="resource-shell" x-data="bulkApproval()">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.approval.title') }}</p>
        <h2 class="resource-title">{{ __('messages.approval.pending_list') }}</h2>
        <p class="resource-copy">
            {{ __('messages.approval.description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.approval.total_pending') }}: {{ number_format($pending->total()) }}</span>
            <span class="meta-chip">{{ __('messages.approval.page') }}: {{ $pending->currentPage() }}/{{ $pending->lastPage() }}</span>
        </div>

        <div x-show="selectedIds.length > 0" x-cloak class="resource-actions">
            <span class="text-sm text-gray-600 dark:text-gray-300">{{ __('messages.approval.selected') }} <strong x-text="selectedIds.length"></strong> {{ __('messages.approval.records') }}</span>
            <button @click="bulkApprove()" class="btn-success text-sm">{{ __('messages.approval.approve_all') }}</button>
            <button @click="openBulkRejectModal()" class="btn-danger text-sm">{{ __('messages.approval.reject_all') }}</button>
        </div>
    </section>

    @if($pending->count() > 0)
    <section class="filter-panel animate-fade-in-up" style="animation-delay: 80ms;">
        <p class="text-amber-800 dark:text-amber-300">
            {{ __('messages.approval.high_security_pending', ['count' => $pending->total()]) }}
        </p>
    </section>
    @endif

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 120ms;">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-14">
                            <input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500" :checked="selectedIds.length === {{ $pending->count() }} && {{ $pending->count() }} > 0">
                        </th>
                        <th>{{ __('messages.borrow.code') }}</th>
                        <th>{{ __('messages.user.teacher') }}</th>
                        <th>{{ __('messages.equipment.title') }}</th>
                        <th>{{ __('messages.borrow.borrow_date') }}</th>
                        <th>{{ __('messages.approval.waiting_time') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pending as $borrow)
                    <tr :class="{ 'bg-cyan-50/70 dark:bg-cyan-900/20': selectedIds.includes({{ $borrow->id }}) }">
                        <td>
                            <input type="checkbox" value="{{ $borrow->id }}" @change="toggleSelect({{ $borrow->id }})" :checked="selectedIds.includes({{ $borrow->id }})" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="font-semibold text-gray-900 dark:text-gray-100">#{{ $borrow->id }}</td>
                        <td>
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $borrow->user->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $borrow->user->department?->name }}</div>
                        </td>
                        <td>
                            @foreach($borrow->details as $detail)
                            <div>{{ $detail->equipmentItem->equipment->name }}</div>
                            @endforeach
                        </td>
                        <td>
                            <div>{{ $borrow->borrow_date->format('d/m/Y') }}</div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.borrow.period') }} {{ $borrow->period }} - {{ $borrow->class_name }}</span>
                        </td>
                        <td>{{ $borrow->created_at->diffForHumans() }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('borrow.show', $borrow) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">{{ __('messages.view') }}</a>
                            <form action="{{ route('admin.approvals.approve', $borrow) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-300 dark:hover:text-emerald-200">{{ __('messages.approval.approve') }}</button>
                            </form>
                            <button onclick="openRejectModal({{ $borrow->id }})" class="text-sm font-semibold text-rose-700 hover:text-rose-800 dark:text-rose-300 dark:hover:text-rose-200">{{ __('messages.approval.reject') }}</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2">{{ __('messages.approval.no_pending') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4">
        {{ $pending->links() }}
    </div>

    <form id="bulkApproveForm" action="{{ route('admin.approvals.bulk-approve') }}" method="POST" class="hidden">
        @csrf
        <template x-for="id in selectedIds" :key="id">
            <input type="hidden" name="ids[]" :value="id">
        </template>
    </form>

    <div x-show="showBulkRejectModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="card max-w-md w-full mx-4">
            <div class="card-body">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.approval.reject') }} <span x-text="selectedIds.length"></span> {{ __('messages.approval.records') }}</h3>
                <form action="{{ route('admin.approvals.bulk-reject') }}" method="POST">
                    @csrf
                    <template x-for="id in selectedIds" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <div class="mb-4">
                        <label class="form-label">{{ __('messages.approval.reject_reason_required') }} <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" rows="3" required class="form-input" placeholder="{{ __('messages.approval.enter_reason_all') }}"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showBulkRejectModal = false" class="btn-secondary">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn-danger">{{ __('messages.approval.reject_all') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="card max-w-md w-full mx-4">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.approval.reject_borrow') }}</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">{{ __('messages.approval.reject_reason_required') }} <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required class="form-input" placeholder="{{ __('messages.approval.enter_reason') }}"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="btn-secondary">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn-danger">{{ __('messages.approval.reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/approvals/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function bulkApproval() {
    return {
        selectedIds: [],
        showBulkRejectModal: false,
        allIds: @json($pending->pluck('id')),

        toggleSelect(id) {
            if (this.selectedIds.includes(id)) {
                this.selectedIds = this.selectedIds.filter(i => i !== id);
            } else {
                this.selectedIds.push(id);
            }
        },

        toggleAll(event) {
            if (event.target.checked) {
                this.selectedIds = [...this.allIds];
            } else {
                this.selectedIds = [];
            }
        },

        bulkApprove() {
            if (confirm('{{ __('messages.approval.confirm_approve', ['count' => "' + this.selectedIds.length + '"]) }}')) {
                document.getElementById('bulkApproveForm').submit();
            }
        },

        openBulkRejectModal() {
            this.showBulkRejectModal = true;
        }
    }
}
</script>
@endpush
@endsection

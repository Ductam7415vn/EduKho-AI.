@extends('layouts.app')

@section('title', __('messages.borrow.detail_title') . ' #' . $borrowRecord->id)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.borrow.detail_title') }}</p>
        <h2 class="resource-title">{{ __('messages.borrow.record') }} #{{ $borrowRecord->id }}</h2>
        <p class="resource-copy">
            {{ __('messages.borrow.created_at') }} {{ $borrowRecord->created_at->format('d/m/Y H:i') }} - {{ __('messages.borrow.tracking_description') }}
        </p>
        <div class="resource-meta">
            <span class="table-pill
                @if($borrowRecord->status === 'active') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300
                @elseif($borrowRecord->status === 'returned') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                @endif">
                @if($borrowRecord->status === 'active') {{ __('messages.borrow.borrowed') }}
                @elseif($borrowRecord->status === 'returned') {{ __('messages.borrow.returned') }}
                @else {{ __('messages.borrow.overdue') }}
                @endif
            </span>
            <span class="table-pill
                @if($borrowRecord->approval_status === 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300
                @elseif(in_array($borrowRecord->approval_status, ['approved', 'auto_approved'])) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300
                @else bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300
                @endif">
                @if($borrowRecord->approval_status === 'pending') {{ __('messages.borrow.pending') }}
                @elseif($borrowRecord->approval_status === 'auto_approved') {{ __('messages.borrow.auto_approved') }}
                @elseif($borrowRecord->approval_status === 'approved') {{ __('messages.borrow.approved') }}
                @else {{ __('messages.borrow.rejected') }}
                @endif
            </span>
            <span class="meta-chip">{{ __('messages.borrow.borrow_date') }}: {{ $borrowRecord->borrow_date->format('d/m/Y') }}</span>
            <span class="meta-chip">{{ __('messages.borrow.deadline') }}: {{ $borrowRecord->expected_return_date->format('d/m/Y') }}</span>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="lg:col-span-2 space-y-6">
            <article class="filter-panel">
                <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.borrow.borrow_info') }}</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.teacher') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.user.department') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->user->department?->name ?? __('messages.borrow.no_department') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.subject') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->subject }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.class') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->class_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.lesson_name') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->lesson_name ?? __('messages.borrow.no_lesson') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.period') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ __('messages.borrow.period') }} {{ $borrowRecord->period }} {{ $borrowRecord->period <= 5 ? '(' . __('messages.borrow.morning') . ')' : '(' . __('messages.borrow.afternoon') . ')' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.borrow_date') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->borrow_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.deadline') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->expected_return_date->format('d/m/Y') }}</dd>
                    </div>
                    @if($borrowRecord->actual_return_date)
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.actual_return_date') }}</dt>
                        <dd class="font-medium text-gray-900 dark:text-gray-100">{{ $borrowRecord->actual_return_date->format('d/m/Y H:i') }}</dd>
                    </div>
                    @endif
                </dl>

                @if($borrowRecord->notes)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <dt class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.borrow.notes') }}</dt>
                    <dd class="text-gray-700 dark:text-gray-300">{{ $borrowRecord->notes }}</dd>
                </div>
                @endif
            </article>

            <article class="data-table-wrap">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.borrow.borrowed_equipment') }}</h3>
                    <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300">{{ $borrowRecord->details->count() }} {{ __('messages.borrow.items') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.equipment.title') }}</th>
                                <th>{{ __('messages.equipment.item_code') }}</th>
                                <th>{{ __('messages.room.title') }}</th>
                                <th>{{ __('messages.borrow.condition_before') }}</th>
                                <th>{{ __('messages.borrow.condition_after') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($borrowRecord->details as $detail)
                            <tr>
                                <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $detail->equipmentItem->equipment->name }}</td>
                                <td>{{ $detail->equipmentItem->specific_code }}</td>
                                <td>{{ $detail->equipmentItem->room?->name ?? '-' }}</td>
                                <td>
                                    <span class="table-pill bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">{{ $detail->condition_before ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($detail->condition_after)
                                    <span class="table-pill
                                        @if($detail->condition_after === 'good') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                        @elseif($detail->condition_after === 'damaged') bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300
                                        @else bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300
                                        @endif">
                                        @if($detail->condition_after === 'good') {{ __('messages.borrow.condition_good') }}
                                        @elseif($detail->condition_after === 'damaged') {{ __('messages.borrow.condition_damaged') }}
                                        @else {{ __('messages.borrow.condition_lost') }}
                                        @endif
                                    </span>
                                    @if($detail->damage_notes)
                                    <p class="text-xs text-rose-600 dark:text-rose-300 mt-1">{{ $detail->damage_notes }}</p>
                                    @endif
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>

            @if($borrowRecord->isActive() && $borrowRecord->isApproved() && ($borrowRecord->user_id === auth()->id() || auth()->user()->isAdmin()))
            <article class="card">
                <div class="card-header">
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white">{{ __('messages.borrow.return_equipment') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('borrow.return', $borrowRecord) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            @foreach($borrowRecord->details as $detail)
                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800/50 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $detail->equipmentItem->equipment->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $detail->equipmentItem->specific_code }}</p>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                    <select name="conditions[{{ $detail->equipment_item_id }}]" class="form-select text-sm">
                                        <option value="good">{{ __('messages.borrow.condition_good') }}</option>
                                        <option value="damaged">{{ __('messages.borrow.condition_damaged') }}</option>
                                        <option value="lost">{{ __('messages.borrow.condition_lost') }}</option>
                                    </select>
                                    <input type="text" name="damage_notes[{{ $detail->equipment_item_id }}]" placeholder="{{ __('messages.borrow.damage_note_placeholder') }}" class="form-input text-sm sm:w-56">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-5 flex justify-end">
                            <button type="submit" class="btn-primary">{{ __('messages.borrow.confirm_return') }}</button>
                        </div>
                    </form>
                </div>
            </article>
            @endif
        </div>

        <aside class="space-y-6">
            @if($borrowRecord->approver || $borrowRecord->rejection_reason)
            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.borrow.approval_info') }}</h3>
                    @if($borrowRecord->approver)
                    <div class="space-y-2">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="text-gray-500 dark:text-gray-400">{{ __('messages.borrow.approver') }}:</span> {{ $borrowRecord->approver->name }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300"><span class="text-gray-500 dark:text-gray-400">{{ __('messages.borrow.approved_time') }}:</span> {{ $borrowRecord->approved_at?->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($borrowRecord->rejection_reason)
                    <div class="mt-4 rounded-xl bg-rose-100/70 dark:bg-rose-900/25 px-3 py-2">
                        <p class="text-sm text-rose-700 dark:text-rose-300"><strong>{{ __('messages.borrow.rejection_reason') }}:</strong> {{ $borrowRecord->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </article>
            @endif

            @if($borrowRecord->aiChatLog)
            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.borrow.created_by_ai') }}</h3>
                    <div class="rounded-xl bg-cyan-50/80 dark:bg-cyan-900/25 p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.borrow.teacher_request') }}:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-200">{{ $borrowRecord->aiChatLog->user_message }}</p>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">
                        {{ __('messages.borrow.processing_time') }} {{ $borrowRecord->aiChatLog->response_time_ms }}ms
                    </p>
                </div>
            </article>
            @endif

            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.borrow.actions') }}</h3>
                    <div class="space-y-2">
                        <a href="{{ route('borrow.print', $borrowRecord) }}" target="_blank" class="btn-info w-full">{{ __('messages.borrow.print_pdf') }}</a>
                        <a href="{{ route('borrow.index') }}" class="btn-secondary w-full">{{ __('messages.borrow.back_to_list') }}</a>

                        @if(auth()->user()->isAdmin() && $borrowRecord->isPending())
                        <form action="{{ route('admin.approvals.approve', $borrowRecord) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-success w-full">{{ __('messages.approval.approve') }}</button>
                        </form>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="btn-danger w-full">{{ __('messages.approval.reject') }}</button>
                        @endif
                    </div>
                </div>
            </article>
        </aside>
    </section>
</div>

@if(auth()->user()->isAdmin() && $borrowRecord->isPending())
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="card max-w-md w-full mx-4">
        <div class="card-body">
            <h3 class="font-display text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.approval.reject_borrow') }}</h3>
            <form action="{{ route('admin.approvals.reject', $borrowRecord) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">{{ __('messages.approval.reject_reason') }} <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required class="form-input" placeholder="{{ __('messages.approval.enter_reason') }}"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="btn-secondary">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn-danger">{{ __('messages.approval.reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

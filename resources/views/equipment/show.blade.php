@extends('layouts.app')

@section('title', $equipment->name)

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.equipment.detail') }}</p>
        <h2 class="resource-title">{{ $equipment->name }}</h2>
        <p class="resource-copy">
            {{ __('messages.equipment.detail_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.equipment.code') }}: {{ $equipment->base_code }}</span>
            <span class="meta-chip">{{ $equipment->is_digital ? __('messages.equipment.type_digital') : __('messages.equipment.type_physical') }}</span>
            <span class="meta-chip">{{ __('messages.equipment.available') }}: {{ $equipment->availableCount() }}/{{ $equipment->totalCount() }}</span>
            @if($equipment->isHighSecurity())
            <span class="meta-chip">{{ __('messages.equipment.high_security') }}</span>
            @endif
        </div>
        <div class="resource-actions">
            @if(!$equipment->is_digital && $equipment->availableCount() > 0)
            <a href="{{ route('borrow.create', ['equipment' => $equipment->id]) }}" class="btn-primary">{{ __('messages.equipment.register_borrow') }}</a>
            @endif
            <a href="{{ route('equipment.history', $equipment) }}" class="btn-secondary">{{ __('messages.equipment.view_history') }}</a>
            @if(auth()->user()->canManageEquipment())
            <a href="{{ route('equipment.qr.print', $equipment) }}" class="btn-secondary">{{ __('messages.equipment.print_qr') }}</a>
            <a href="{{ route('admin.equipment.edit', $equipment) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="lg:col-span-2 space-y-6">
            @if($equipment->image_url)
            <article class="filter-panel">
                <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" class="w-full rounded-lg shadow-lg">
            </article>
            @endif
            
            <article class="filter-panel">
                <h3 class="font-display text-lg font-semibold text-inherit mb-4">{{ __('messages.equipment.info') }}</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.subject') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->category_subject }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.grade') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->grade_level }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.unit') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->unit }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.unit_price') }}</dt>
                        <dd class="font-medium text-inherit">{{ number_format($equipment->price ?? 0) }} VND</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.origin') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->origin ?? __('messages.equipment.origin_unknown') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.type') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->is_digital ? __('messages.equipment.type_digital') : __('messages.equipment.type_physical') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.security_level') }}</dt>
                        <dd>
                            @if($equipment->isHighSecurity())
                            <span class="table-pill bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">{{ __('messages.equipment.high_security') }}</span>
                            @else
                            <span class="table-pill bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">{{ __('messages.equipment.security_normal') }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-inherit">{{ __('messages.equipment.is_fixed_asset') }}</dt>
                        <dd class="font-medium text-inherit">{{ $equipment->is_fixed_asset ? __('messages.yes') : __('messages.no') }}</dd>
                    </div>
                </dl>

                @if($equipment->description)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <dt class="text-sm text-inherit mb-2">{{ __('messages.description') }}</dt>
                    <dd class="text-inherit">{{ $equipment->description }}</dd>
                </div>
                @endif

                @if($equipment->is_digital && $equipment->file_url)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ $equipment->file_url }}" target="_blank" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        {{ __('messages.equipment.access_material') }}
                    </a>
                </div>
                @endif
            </article>

            @if(!$equipment->is_digital)
            <article class="data-table-wrap">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-display text-lg font-semibold text-inherit">{{ __('messages.equipment.item_list') }}</h3>
                    <span class="table-pill bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">{{ $equipment->items->count() }} {{ __('messages.equipment.records') }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.equipment.item_code') }}</th>
                                <th>{{ __('messages.room.title') }}</th>
                                <th>{{ __('messages.equipment.year_acquired') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($equipment->items as $item)
                            <tr>
                                <td class="font-semibold text-inherit">{{ $item->specific_code }}</td>
                                <td>{{ $item->room?->name ?? __('messages.equipment.no_room') }}</td>
                                <td>{{ $item->year_acquired }}</td>
                                <td>
                                    <span class="table-pill
                                        @if($item->status === 'available') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                        @elseif($item->status === 'borrowed') bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300
                                        @elseif($item->status === 'maintenance') bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300
                                        @else bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300
                                        @endif">
                                        @if($item->status === 'available') {{ __('messages.equipment.ready') }}
                                        @elseif($item->status === 'borrowed') {{ __('messages.equipment.borrowed') }}
                                        @elseif($item->status === 'maintenance') {{ __('messages.equipment.maintenance') }}
                                        @elseif($item->status === 'broken') {{ __('messages.equipment.damaged') }}
                                        @elseif($item->status === 'lost') {{ __('messages.equipment.lost') }}
                                        @else {{ ucfirst($item->status) }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            @endif
        </div>

        <aside class="space-y-6">
            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-inherit mb-4">{{ __('messages.equipment.qr_code') }}</h3>
                    <div class="text-center">
                        <img src="{{ route('equipment.qr', $equipment) }}" alt="QR Code" class="w-32 h-32 mx-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                        <p class="text-xs text-inherit mt-2">{{ __('messages.equipment.scan_qr') }}</p>
                        <a href="{{ route('equipment.qr.print', $equipment) }}" target="_blank" class="btn-secondary mt-3 w-full">{{ __('messages.equipment.print_qr') }}</a>
                    </div>
                </div>
            </article>

            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-inherit mb-4">{{ __('messages.equipment.inventory_status') }}</h3>
                    <div class="text-center">
                        <div class="font-display text-4xl font-bold text-teal-700 dark:text-teal-300">{{ $equipment->availableCount() }}</div>
                        <div class="text-inherit">/ {{ $equipment->totalCount() }} {{ __('messages.equipment.available') }}</div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.ready') }}</span>
                            <span class="font-semibold text-emerald-600 dark:text-emerald-300">{{ $equipment->items->where('status', 'available')->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.borrowed') }}</span>
                            <span class="font-semibold text-cyan-600 dark:text-cyan-300">{{ $equipment->items->where('status', 'borrowed')->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.maintenance') }}</span>
                            <span class="font-semibold text-amber-600 dark:text-amber-300">{{ $equipment->items->where('status', 'maintenance')->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.broken_lost') }}</span>
                            <span class="font-semibold text-rose-600 dark:text-rose-300">{{ $equipment->items->whereIn('status', ['broken', 'lost'])->count() }}</span>
                        </div>
                    </div>
                </div>
            </article>

            @if(auth()->user()->isAdmin() && $equipment->inventoryLogs->count() > 0)
            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-inherit mb-4">{{ __('messages.equipment.inventory_history') }}</h3>
                    <div class="space-y-3">
                        @foreach($equipment->inventoryLogs->take(5) as $log)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <span class="font-semibold {{ $log->type === 'increase' ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">
                                    {{ $log->type === 'increase' ? '+' : '-' }}{{ $log->quantity }}
                                </span>
                                <span class="text-inherit">{{ $log->reason }}</span>
                            </div>
                            <span class="text-inherit text-xs">{{ $log->action_date->format('d/m/Y') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </article>
            @endif

            @if(auth()->user()->isAdmin() && $equipment->hasDepreciationInfo())
            @php $depreciation = $equipment->getDepreciation(); @endphp
            <article class="card">
                <div class="card-body">
                    <h3 class="font-display text-lg font-semibold text-inherit mb-4">{{ __('messages.equipment.depreciation') }}</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.purchase_price') }}</span>
                            <span class="font-medium text-inherit">{{ number_format($depreciation['purchase_price']) }} VND</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.current_value') }}</span>
                            <span class="font-medium text-teal-700 dark:text-teal-300">{{ number_format($depreciation['current_value']) }} VND</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.accumulated_depreciation') }}</span>
                            <span class="font-medium text-rose-600 dark:text-rose-300">{{ number_format($depreciation['accumulated_depreciation']) }} VND</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-inherit">{{ __('messages.equipment.usage_time') }}</span>
                            <span class="font-medium text-inherit">{{ $depreciation['years_used'] }} / {{ $depreciation['useful_life_years'] }} {{ __('messages.equipment.years') }}</span>
                        </div>
                        @if($depreciation['is_fully_depreciated'])
                        <div class="mt-3 rounded-xl px-3 py-2 text-center text-sm font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                            {{ __('messages.equipment.fully_depreciated') }}
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @endif
        </aside>
    </section>
</div>
@endsection

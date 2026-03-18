@extends('layouts.app')

@section('title', __('messages.borrow.template_title'))

@section('content')
<div class="resource-shell">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.borrow.template_kicker') }}</p>
        <h2 class="resource-title">{{ __('messages.borrow.my_templates') }}</h2>
        <p class="resource-copy">
            {{ __('messages.borrow.template_description') }}
        </p>
        <div class="resource-meta">
            <span class="meta-chip">{{ __('messages.borrow.total_templates') }}: {{ number_format($templates->count()) }}</span>
        </div>
        <div class="resource-actions">
            <a href="{{ route('borrow.templates.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('messages.borrow.create_template') }}
            </a>
        </div>
    </section>

    <section class="data-table-wrap animate-fade-in-up" style="animation-delay: 80ms;">
        @if($templates->isEmpty())
        <div class="empty-state">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.borrow.no_templates') }}</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.borrow.no_templates_hint') }}</p>
            <a href="{{ route('borrow.templates.create') }}" class="btn-primary mt-4">{{ __('messages.borrow.create_first_template') }}</a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.borrow.template_name') }}</th>
                        <th>{{ __('messages.equipment.title') }}</th>
                        <th>{{ __('messages.borrow.quantity') }}</th>
                        <th>{{ __('messages.borrow.class_subject') }}</th>
                        <th class="text-right">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($templates as $template)
                    <tr>
                        <td class="font-semibold text-gray-900 dark:text-gray-100">{{ $template->name }}</td>
                        <td>{{ $template->equipment->name }}</td>
                        <td>{{ $template->quantity }}</td>
                        <td>{{ $template->class_name ?? '-' }} / {{ $template->subject ?? '-' }}</td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('borrow.create', ['template' => $template->id]) }}" class="font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200 text-sm">
                                {{ __('messages.borrow.use_template') }}
                            </a>
                            <form method="POST" action="{{ route('borrow.templates.destroy', $template) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-700 dark:text-rose-300 dark:hover:text-rose-200 text-sm font-semibold" onclick="return confirm('{{ __('messages.borrow.delete_template_confirm') }}')">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
</div>
@endsection

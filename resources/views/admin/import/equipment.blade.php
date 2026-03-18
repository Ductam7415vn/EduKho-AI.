@extends('layouts.app')

@section('title', __('messages.import.title'))

@section('content')
<div class="resource-shell max-w-4xl mx-auto">
    <section class="resource-hero animate-fade-in-up">
        <p class="resource-kicker">{{ __('messages.import.data_import') }}</p>
        <h2 class="resource-title">{{ __('messages.import.from_csv') }}</h2>
        <p class="resource-copy">
            {{ __('messages.import.description') }}
        </p>
        <div class="resource-actions">
            <a href="{{ route('admin.import.equipment.template') }}" class="btn-secondary">{{ __('messages.import.download_template') }}</a>
            <a href="{{ route('equipment.index') }}" class="btn-secondary">{{ __('messages.import.back_to_list') }}</a>
        </div>
    </section>

    <section class="card animate-fade-in-up" style="animation-delay: 80ms;">
        <div class="card-body space-y-6">
            <div class="rounded-2xl border border-cyan-200 bg-cyan-50/90 dark:border-cyan-800/60 dark:bg-cyan-900/20 p-4">
                <h3 class="text-sm font-semibold text-cyan-800 dark:text-cyan-200">{{ __('messages.import.guide') }}</h3>
                <ul class="mt-2 list-disc list-inside space-y-1 text-sm text-cyan-700 dark:text-cyan-300">
                    <li>{{ __('messages.import.guide_1') }}</li>
                    <li>{{ __('messages.import.guide_2') }}</li>
                    <li>{{ __('messages.import.guide_3') }}</li>
                    <li>{{ __('messages.import.guide_4') }}</li>
                    <li>{{ __('messages.import.guide_5') }}</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.import.equipment') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label for="csv_file" class="form-label">{{ __('messages.import.csv_file') }}</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-300 justify-center">
                                <label for="csv_file" class="relative cursor-pointer font-medium text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                                    <span>{{ __('messages.import.select_file') }}</span>
                                    <input id="csv_file" name="csv_file" type="file" class="sr-only" accept=".csv,.txt" required>
                                </label>
                                <p class="pl-1">{{ __('messages.import.or_drag_drop') }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.import.max_size') }}</p>
                        </div>
                    </div>
                    <p id="file-name" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
                    @error('csv_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('equipment.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn-primary">Import</button>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.getElementById('csv_file').addEventListener('change', function(e) {
    document.getElementById('file-name').textContent = e.target.files[0]?.name || '';
});
</script>
@endpush
@endsection

@extends('layouts.guest')

@section('title', '500 - ' . __('messages.error.500'))

@section('content')
<div class="w-full max-w-xl mx-auto animate-fade-in-up">
    <div class="resource-hero text-center">
        <div class="mb-4 inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <p class="resource-kicker">{{ __('messages.error.500_title') }}</p>
        <h1 class="resource-title !mt-1">500</h1>
        <p class="resource-copy !mt-2">
            {{ __('messages.error.500_message') }}
        </p>
        <div class="resource-actions justify-center">
            <a href="javascript:location.reload()" class="btn-secondary">{{ __('messages.error.try_again') }}</a>
            <a href="{{ route('dashboard') }}" class="btn-primary">{{ __('messages.error.go_home') }}</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.guest')

@section('title', '403 - ' . __('messages.error.403'))

@section('content')
<div class="w-full max-w-xl mx-auto animate-fade-in-up">
    <div class="resource-hero text-center">
        <div class="mb-4 inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-300">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <p class="resource-kicker">{{ __('messages.error.403_title') }}</p>
        <h1 class="resource-title !mt-1">403</h1>
        <p class="resource-copy !mt-2">
            {{ __('messages.error.403_message') }}
        </p>
        <div class="resource-actions justify-center">
            <a href="{{ url()->previous() }}" class="btn-secondary">{{ __('messages.error.go_back') }}</a>
            <a href="{{ route('dashboard') }}" class="btn-primary">{{ __('messages.error.go_home') }}</a>
        </div>
    </div>
</div>
@endsection

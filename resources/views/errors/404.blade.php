@extends('layouts.guest')

@section('title', '404 - ' . __('messages.error.404'))

@section('content')
<div class="w-full max-w-xl mx-auto animate-fade-in-up">
    <div class="resource-hero text-center">
        <div class="mb-4 inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-300">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="resource-kicker">{{ __('messages.error.404') }}</p>
        <h1 class="resource-title !mt-1">404</h1>
        <p class="resource-copy !mt-2">
            {{ __('messages.error.404_message') }}
        </p>
        <div class="resource-actions justify-center">
            <a href="{{ url()->previous() }}" class="btn-secondary">{{ __('messages.error.go_back') }}</a>
            <a href="{{ route('dashboard') }}" class="btn-primary">{{ __('messages.error.go_home') }}</a>
        </div>
    </div>
</div>
@endsection

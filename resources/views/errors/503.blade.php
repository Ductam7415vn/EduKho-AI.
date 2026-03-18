@extends('layouts.guest')

@section('title', '503 - ' . __('messages.error.503'))

@section('content')
<div class="w-full max-w-xl mx-auto animate-fade-in-up">
    <div class="resource-hero text-center">
        <div class="mb-4 inline-flex w-16 h-16 items-center justify-center rounded-2xl bg-yellow-100 text-yellow-600 dark:bg-yellow-900/40 dark:text-yellow-300">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="resource-kicker">{{ __('messages.error.503_title') }}</p>
        <h1 class="resource-title !mt-1">503</h1>
        <p class="resource-copy !mt-2">
            {{ __('messages.error.503_message') }}
        </p>
        <div class="resource-actions justify-center">
            <a href="javascript:location.reload()" class="btn-primary">{{ __('messages.error.refresh') }}</a>
        </div>
    </div>
</div>
@endsection

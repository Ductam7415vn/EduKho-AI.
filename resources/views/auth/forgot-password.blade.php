@extends('layouts.guest')

@section('title', __('messages.auth.forgot_password') . ' - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md mx-auto space-y-6 animate-fade-in-up">
    <div class="text-center">
        <p class="resource-kicker">{{ __('messages.auth.recover_account') }}</p>
        <h1 class="resource-title !mt-1">{{ __('messages.auth.forgot_password') }}</h1>
        <p class="resource-copy !mt-1">{{ __('messages.auth.forgot_description') }}</p>
    </div>

    @if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800/60 dark:bg-emerald-900/20 dark:text-emerald-300">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="card">
        @csrf
        <div class="card-body space-y-5">
            <div>
                <label for="email" class="form-label">{{ __('messages.auth.email') }}</label>
                <input id="email" name="email" type="email" required autocomplete="email" class="form-input" value="{{ old('email') }}" placeholder="email@truong.edu.vn">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full justify-center">{{ __('messages.auth.send_reset_link') }}</button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                    {{ __('messages.auth.back_to_login') }}
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

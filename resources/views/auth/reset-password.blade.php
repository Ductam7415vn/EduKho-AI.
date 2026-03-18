@extends('layouts.guest')

@section('title', 'Dat lai mat khau - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md mx-auto space-y-6 animate-fade-in-up">
    <div class="text-center">
        <p class="resource-kicker">Khoi phuc tai khoan</p>
        <h1 class="resource-title !mt-1">Dat lai mat khau</h1>
        <p class="resource-copy !mt-1">Nhap mat khau moi cho tai khoan cua ban.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="card">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="card-body space-y-5">
            @error('token')
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-800/60 dark:bg-rose-900/20 dark:text-rose-300">
                {{ $message }}
            </div>
            @enderror

            <div>
                <label for="email_display" class="form-label">Email</label>
                <input id="email_display" type="email" disabled class="form-input bg-gray-100 dark:bg-gray-800" value="{{ $email }}">
            </div>

            <div>
                <label for="password" class="form-label">Mat khau moi</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-input" placeholder="••••••••">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Xac nhan mat khau</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="form-input" placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Dat lai mat khau</button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                    Quay lai dang nhap
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

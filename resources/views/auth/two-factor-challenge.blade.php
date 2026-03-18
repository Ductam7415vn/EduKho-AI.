@extends('layouts.guest')

@section('title', 'Xac thuc 2 yeu to - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md mx-auto space-y-6 animate-fade-in-up">
    <div class="text-center">
        <p class="resource-kicker">Bao mat dang nhap</p>
        <h1 class="resource-title !mt-1">Xac thuc 2 yeu to</h1>
        <p class="resource-copy !mt-1">Nhap ma xac thuc tu ung dung tren dien thoai cua ban.</p>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('error'))
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-800/60 dark:bg-rose-900/20 dark:text-rose-300">
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="code" class="form-label text-center">Ma xac thuc 6 so</label>
                    <input
                        type="text"
                        name="code"
                        id="code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        class="form-input text-center text-2xl tracking-widest"
                        placeholder="000000"
                        required
                        autofocus
                        autocomplete="one-time-code"
                    >
                    @error('code')
                    <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Xac nhan</button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800 dark:text-teal-300 dark:hover:text-teal-200">
                    Quay lai dang nhap
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.guest')

@section('title', 'QR Code - ' . $equipment->name)

@push('styles')
<style>
    .qr-print-card {
        max-width: 340px;
        margin: 0 auto;
    }

    .qr-image {
        width: 220px;
        height: 220px;
        margin: 0 auto;
        padding: 12px;
        border-radius: 16px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
    }

    @media print {
        .guest-backdrop,
        .qr-actions {
            display: none !important;
        }

        .guest-shell {
            min-height: auto;
            padding: 0;
        }

        .guest-main {
            max-width: none;
        }

        .qr-print-card {
            border-color: #d7e0e8;
            box-shadow: none;
        }
    }
</style>
@endpush

@section('content')
<div class="w-full max-w-xl mx-auto animate-fade-in-up">
    <div class="resource-hero text-center mb-6">
        <p class="resource-kicker">In tem QR</p>
        <h1 class="resource-title !mt-1">{{ $equipment->name }}</h1>
        <p class="resource-copy !mt-2">Ma thiet bi: {{ $equipment->base_code }}</p>
    </div>

    <div class="card qr-print-card">
        <div class="card-body text-center space-y-4">
            <img src="{{ route('equipment.qr', $equipment) }}" alt="QR Code" class="qr-image">
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $equipment->category_subject }} | Khoi {{ $equipment->grade_level }}</p>

            <div class="resource-actions qr-actions justify-center">
                <button type="button" onclick="window.print()" class="btn-primary">In QR Code</button>
                <a href="{{ route('equipment.show', $equipment) }}" class="btn-secondary">Quay lai</a>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $user = auth()->user();
    $isMitra = $user && ($user->hasRole('mitra') || ($user->role ?? null) === 'mitra');
    $isPemilik = $user && ($user->hasRole('pemilik') || ($user->role ?? null) === 'pemilik');
@endphp

@if ($isMitra)
    <x-partner-layout title="Verifikasi Identitas Resmi (KYC)">
        <div class="max-w-4xl space-y-6">
            @include('kyc.partials.content')
        </div>
    </x-partner-layout>
@elseif ($isPemilik)
    <x-owner-layout title="Verifikasi Identitas Resmi (KYC)">
        <div class="max-w-4xl space-y-6">
            @include('kyc.partials.content')
        </div>
    </x-owner-layout>
@else
    <x-tenant-layout title="Verifikasi Identitas Resmi (KYC)">
        <div class="p-4 space-y-6">
            @include('kyc.partials.content')
        </div>
    </x-tenant-layout>
@endif

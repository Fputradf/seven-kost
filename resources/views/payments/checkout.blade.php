@php
    $backUrl = auth()->user()?->hasRole('pemilik') ? route('owner.promosi.index') : route('ads.packages.index');
    $backLabel = auth()->user()?->hasRole('pemilik') ? 'Kembali ke Promosi & VIP Pemilik' : 'Kembali ke Paket Iklan';
@endphp

<x-app-layout title="Pembayaran Tagihan - SevenKost">
    <div class="max-w-xl mx-auto px-4 py-8">
        <x-payment-gateway-checkout 
            :transaction="$transaction" 
            :backUrl="$backUrl" 
            :backLabel="$backLabel"
            :uploadRoute="route('payments.upload-proof', $transaction->reference_id)"
            :simulateRoute="route('payments.simulate', $transaction->reference_id)"
            :successRoute="route('payments.success', $transaction->reference_id)"
        />
    </div>
</x-app-layout>

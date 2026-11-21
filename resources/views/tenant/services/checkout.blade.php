<x-tenant-layout title="Pembayaran Layanan Kos" :backUrl="route('services.index', ['tab' => 'orders'])">

    <x-payment-gateway-checkout 
        :transaction="$transaction" 
        :backUrl="route('services.index', ['tab' => 'orders'])" 
        backLabel="Kembali ke Pesanan Layanan"
        :uploadRoute="route('tenant.services.upload-proof', $transaction->reference_id)"
        :simulateRoute="route('tenant.services.simulate-payment', $transaction->reference_id)"
        :successRoute="route('tenant.services.payment-success', $transaction->reference_id)"
    />

</x-tenant-layout>

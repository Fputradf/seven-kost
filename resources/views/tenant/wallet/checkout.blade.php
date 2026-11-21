<x-tenant-layout title="Pembayaran Top Up Dompet" :backUrl="route('tenant.wallet.index')">

    <x-payment-gateway-checkout 
        :transaction="$transaction" 
        :backUrl="route('tenant.wallet.index')" 
        backLabel="Kembali ke Dompet Digital"
        :uploadRoute="route('tenant.wallet.upload-proof', $transaction->reference_id)"
        :simulateRoute="route('tenant.wallet.simulate-payment', $transaction->reference_id)"
        :successRoute="route('tenant.wallet.payment-success', $transaction->reference_id)"
    />

</x-tenant-layout>

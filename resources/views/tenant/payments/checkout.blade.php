<x-tenant-layout title="Pembayaran Sewa Kamar" :backUrl="$booking ? route('tenant.bookings.show', $booking) : route('tenant.bookings.index')">

    <x-payment-gateway-checkout 
        :transaction="$transaction" 
        :booking="$booking"
        :backUrl="$booking ? route('tenant.bookings.show', $booking) : route('tenant.bookings.index')" 
        backLabel="Kembali ke Detail Sewa"
        :uploadRoute="route('tenant.bookings.upload-proof', $transaction->reference_id)"
        :simulateRoute="route('tenant.bookings.simulate-payment', $transaction->reference_id)"
        :successRoute="route('tenant.bookings.payment-success', $transaction->reference_id)"
    />

</x-tenant-layout>

<x-tenant-layout title="Pembayaran Sewa Kamar" :backUrl="$booking ? route('tenant.bookings.show', $booking) : route('tenant.bookings.index')">

    <div 
        x-data="{
            checking: false,
            pollStatus() {
                setInterval(async () => {
                    try {
                        const res = await fetch('{{ route('payments.status', $transaction->reference_id) }}');
                        if (!res.ok) return;
                        const data = await res.json();
                        if (data.is_paid) {
                            window.location.href = '{{ route('tenant.bookings.payment-success', $transaction->reference_id) }}';
                        }
                    } catch (e) {
                        // ignore network hiccup
                    }
                }, 3000);
            },
            async checkPaymentNow() {
                this.checking = true;
                try {
                    const res = await fetch('{{ route('payments.status', $transaction->reference_id) }}');
                    if (res.ok) {
                        const data = await res.json();
                        if (data.is_paid) {
                            window.location.href = '{{ route('tenant.bookings.payment-success', $transaction->reference_id) }}';
                            return;
                        }
                    }
                } catch (e) {}
                this.checking = false;
            }
        }"
        x-init="pollStatus()"
        class="space-y-4"
    >

        {{-- Top Sapphire Header Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-2" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider">
                    Invoice: #{{ $transaction->reference_id }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-200 border border-amber-300/30">
                    Menunggu Bayar
                </span>
            </div>

            <div>
                <span class="text-xs text-sky-100 font-medium block">Total Tagihan Sewa</span>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </h2>
            </div>

            <p class="text-[11px] text-sky-100/90 leading-relaxed pt-1 border-t border-white/15">
                Selesaikan transaksi melalui Payment Gateway Xendit Sandbox. Anda dapat memilih metode pembayaran apapun seperti QRIS, Virtual Account BCA/BNI/BRI/Mandiri, atau E-Wallet.
            </p>
        </div>

        {{-- Detail Sewa Kamar --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Rincian Booking Kamar</span>
            </h3>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Properti Kos:</span>
                    <strong class="text-slate-900 font-bold">{{ $booking?->kost?->name ?? 'Seven Kost' }}</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Tipe Kamar:</span>
                    <strong class="text-slate-900 font-bold">{{ $booking?->room?->name ?? 'Standard' }}</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Durasi Sewa:</span>
                    <span class="text-slate-800 font-semibold">{{ $booking?->duration_months ?? 1 }} Bulan</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Penghuni:</span>
                    <span class="text-slate-800 font-semibold">{{ $booking?->tenant_name ?: auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        {{-- Embedded Xendit Sandbox (In-App Tanpa Redirect Keluar) --}}
        @if(!empty($transaction->xendit_invoice_url) && (str_contains($transaction->xendit_invoice_url, 'checkout.xendit.co') || str_contains($transaction->xendit_invoice_url, 'checkout-staging.xendit.co')))
            <div 
                x-data="{ iframeLoading: true }" 
                class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs p-2 sm:p-3 space-y-2.5"
            >
                <div class="px-2 pt-1 flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Pembayaran Xendit In-App</span>
                    </div>
                    <a 
                        href="{{ $transaction->xendit_invoice_url }}" 
                        target="_blank" 
                        class="text-[11px] text-blue-600 hover:text-blue-700 font-bold inline-flex items-center gap-1"
                    >
                        <span>Buka Tab Baru</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>

                {{-- Iframe Container Tersemat Langsung dalam Aplikasi --}}
                <div class="relative w-full rounded-xl overflow-hidden bg-slate-50 border border-slate-100" style="min-height: 620px; height: 75vh;">
                    <div x-show="iframeLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 gap-2 z-10 transition-opacity">
                        <svg class="animate-spin w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span class="text-xs font-semibold text-slate-500">Memuat Gateway Xendit Sandbox...</span>
                    </div>

                    <iframe 
                        src="{{ $transaction->xendit_invoice_url }}" 
                        @load="iframeLoading = false"
                        class="w-full h-full border-0 rounded-xl"
                        allow="payment"
                    ></iframe>
                </div>
            </div>
        @endif

        {{-- Tombol Cek Status Pembayaran Manual --}}
        <div>
            <button 
                type="button" 
                @click="checkPaymentNow()" 
                :disabled="checking"
                class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-extrabold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4 text-white" :class="{ 'animate-spin': checking }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span x-text="checking ? 'Mengecek status pembayaran ke Xendit...' : 'Sudah Bayar? Cek Status Pembayaran Sekarang'"></span>
            </button>
        </div>

        {{-- Simulator Sandbox Lokal (Cepat & Mandiri) --}}
        <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Xendit Sandbox Testing</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">Mode Uji Coba</span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Ingin langsung menguji alur lunas tanpa membuka halaman luar Xendit? Anda dapat memicu simulasi lunas instan di bawah ini.
            </p>
            <form action="{{ route('tenant.bookings.simulate-payment', $transaction->reference_id) }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simulasikan Pembayaran Lunas (Sandbox Simulator)</span>
                </button>
            </form>
        </div>

    </div>

</x-tenant-layout>

<x-tenant-layout title="Pembayaran Top Up Saldo" :backUrl="route('tenant.wallet.index')">

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
                            window.location.href = '{{ route('tenant.wallet.payment-success', $transaction->reference_id) }}';
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
                            window.location.href = '{{ route('tenant.wallet.payment-success', $transaction->reference_id) }}';
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
                    Menunggu Pembayaran
                </span>
            </div>

            <div>
                <span class="text-xs text-sky-100 font-medium block">Nominal Top Up Saldo</span>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </h2>
            </div>

            <p class="text-[11px] text-sky-100/90 leading-relaxed pt-1 border-t border-white/15">
                Selesaikan transaksi Top Up melalui Payment Gateway Xendit Sandbox. Anda dapat memilih metode pembayaran seperti QRIS, Virtual Account BCA/BNI/BRI/Mandiri, atau E-Wallet.
            </p>
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

                <div class="relative w-full rounded-xl overflow-hidden bg-slate-50 border border-slate-100" style="min-height: 580px; height: 75vh;">
                    <div x-show="iframeLoading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 gap-2 z-10 transition-opacity">
                        <svg class="animate-spin w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span class="text-xs font-semibold text-slate-500">Memuat Gateway Xendit Sandbox...</span>
                    </div>

                    <iframe 
                        src="{{ $transaction->xendit_invoice_url }}" 
                        class="w-full h-full border-0 rounded-xl"
                        allow="payment *"
                        @load="iframeLoading = false"
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

        {{-- Tombol Simulasi Pembayaran Sandbox --}}
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 space-y-2.5 text-xs text-amber-900 shadow-2xs">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                <span>Lingkungan Uji Coba Xendit Sandbox</span>
            </div>
            <p class="text-[11px] leading-relaxed text-amber-800">
                Gunakan simulasi di bawah ini untuk menguji pelunasan saldo dompet secara instan tanpa perlu transfer sungguhan.
            </p>
            <form action="{{ route('tenant.wallet.simulate-payment', $transaction->reference_id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs transition shadow-xs active:scale-95 cursor-pointer">
                    Simulasikan Pelunasan Top Up (Sandbox)
                </button>
            </form>
        </div>

    </div>

</x-tenant-layout>

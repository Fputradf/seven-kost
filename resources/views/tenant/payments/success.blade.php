<x-tenant-layout title="Pembayaran Berhasil" :backUrl="route('tenant.bookings.index')">

    <div class="space-y-4">

        {{-- Top Success Card --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-6 text-center shadow-2xs space-y-4">
            <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-100 shadow-sm animate-bounce">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <div class="space-y-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider inline-block">
                    Pembayaran Berhasil & Lunas
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    Kamar Kos Resmi Aktif!
                </h2>
                <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                    Terima kasih! Pembayaran telah berhasil diverifikasi oleh Xendit Gateway. Kamar kos Anda telah resmi siap ditempati.
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-left space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">ID Referensi:</span>
                    <strong class="text-slate-900 font-mono font-bold">{{ $transaction->reference_id }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Properti Kos:</span>
                    <strong class="text-slate-900 font-bold">{{ $booking?->kost?->name ?? 'Seven Kost' }}</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Tipe Kamar:</span>
                    <span class="text-slate-800 font-semibold">{{ $booking?->room?->name ?? 'Standard' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500">Durasi Sewa:</span>
                    <span class="text-slate-800 font-semibold">{{ $booking?->duration_months ?? 1 }} Bulan</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-slate-200/70">
                    <span class="text-slate-700 font-bold">Total Lunas:</span>
                    <strong class="text-sm font-black text-emerald-600">
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div class="space-y-2 pt-2">
                @if($booking)
                    <a 
                        href="{{ route('tenant.bookings.show', $booking) }}" 
                        class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-blue-500/25 transition active:scale-95"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Lihat Detail Kamar &amp; Sewa Saya</span>
                    </a>
                @endif

                <a 
                    href="{{ route('tenant.dashboard') }}" 
                    class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
                >
                    <span>Kembali ke Dashboard Penghuni</span>
                </a>
            </div>
        </div>

    </div>

</x-tenant-layout>

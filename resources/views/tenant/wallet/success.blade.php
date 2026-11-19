<x-tenant-layout title="Top Up Berhasil" :backUrl="route('tenant.wallet.index')">

    <div class="space-y-4">

        {{-- Top Success Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-5 text-center shadow-lg shadow-emerald-950/20 border border-emerald-400/30 space-y-3" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #10b981 100%);">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/40 flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <div>
                <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider block">Top Up Sukses</span>
                <h2 class="text-xl sm:text-2xl font-black text-white leading-tight mt-0.5">
                    Saldo Berhasil Ditambahkan!
                </h2>
                <p class="text-xs text-emerald-100 mt-1">
                    Invoice: #{{ $transaction->reference_id }}
                </p>
            </div>

            <div class="p-3 rounded-xl bg-black/20 border border-white/20 backdrop-blur-xs flex items-center justify-between text-xs">
                <span class="text-emerald-100 font-medium">Nominal Top Up:</span>
                <strong class="text-base font-black text-white">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        {{-- Info Saldo Dompet Baru --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Saldo Dompet SevenKost Anda</span>
            </h3>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-slate-500 block">Total Saldo Aktif Saat Ini</span>
                    <strong class="text-xl font-black text-slate-900">
                        Rp {{ number_format(auth()->user()->fresh()->wallet_balance, 0, ',', '.') }}
                    </strong>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    Siap Digunakan
                </span>
            </div>

            <p class="text-[11px] text-slate-500 leading-relaxed">
                Saldo dompet Anda sekarang dapat digunakan secara instan untuk membayar sewa kos, melunasi tagihan perpanjangan, atau memesan layanan tambahan kamar tanpa biaya admin gateway berulang.
            </p>
        </div>

        {{-- Navigasi Lanjut --}}
        <div class="space-y-2 pt-2">
            <a href="{{ route('tenant.wallet.index') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm transition shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Kembali ke Dompet Saya</span>
            </a>

            <a href="{{ route('tenant.bookings.index') }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95">
                <span>Lihat Kamar Kos Saya</span>
            </a>
        </div>

    </div>

</x-tenant-layout>

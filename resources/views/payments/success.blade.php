<x-seven-layout title="Pembayaran Berhasil - SevenKost">

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        
        {{-- KARTU SUKSES UTAMA --}}
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-lg text-center space-y-6">
            
            {{-- ANIMATED CELEBRATION BADGE --}}
            <div class="relative inline-flex items-center justify-center">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs shadow-md">
                    ✓
                </div>
            </div>

            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span>Lunas Terverifikasi via Xendit</span>
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Pembayaran Berhasil!
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Terima kasih, transaksi Anda telah diverifikasi oleh sistem Xendit Payment Gateway SevenKost.
                </p>
            </div>

            {{-- KOTAK RINCIAN TRANSAKSI --}}
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-left space-y-2.5 text-xs">
                <div class="flex justify-between items-center pb-2 border-b border-slate-200/60">
                    <span class="text-slate-500">Nomor Referensi:</span>
                    <strong class="font-mono text-slate-900 text-[13px]">{{ $transaction->reference_id }}</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Layanan:</span>
                    <strong class="text-slate-900 text-right">{{ $transaction->title }}</strong>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Saluran Pembayaran:</span>
                    <span class="font-bold text-blue-600">{{ $transaction->channel_label }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Waktu Lunas:</span>
                    <span class="font-medium text-slate-800">{{ $transaction->paid_at?->format('d M Y, H:i:s') ?? now()->format('d M Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-slate-200/60">
                    <span class="font-bold text-slate-700">Total Dibayar:</span>
                    <span class="text-lg font-black text-emerald-600">{{ $transaction->formatted_amount }}</span>
                </div>
            </div>

            {{-- PESAN SPESIFIK TIPE LAYANAN --}}
            @if($transaction->type === 'vip')
                <div class="p-5 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 text-left space-y-3">
                    <div class="flex items-center gap-2.5 text-amber-900 font-extrabold text-sm">
                        <span class="text-xl">👑</span>
                        <span>Akun Anda Kini Berstatus Pemilik VIP!</span>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Masa aktif VIP Anda telah diperpanjang hingga:
                        <strong class="font-bold text-slate-900 block mt-0.5 text-sm">
                            {{ auth()->user()->vip_expires_at?->format('d F Y, H:i') ?? 'Aktif' }} WIB
                        </strong>
                    </p>
                    <div class="pt-2 border-t border-amber-200/60 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] text-amber-900 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span> Kuota properti kos tak terbatas
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span> Prioritas baris teratas hasil pencarian
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span> Pin emas Leaflet interaktif
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-600 font-bold">✓</span> Lencana emas VIP pada seluruh listing
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a 
                        href="{{ route('owner.dashboard') }}" 
                        class="flex-1 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-md shadow-blue-500/25 transition text-center"
                    >
                        Buka Dashboard Pemilik →
                    </a>
                    <a 
                        href="{{ route('owner.kosts.index') }}" 
                        class="flex-1 py-3 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition text-center"
                    >
                        Kelola Properti Kos Saya
                    </a>
                </div>

            @else
                <div class="p-5 rounded-2xl bg-blue-50/80 border border-blue-200 text-left space-y-2">
                    <div class="flex items-center gap-2 text-blue-900 font-extrabold text-sm">
                        <span class="text-lg">📢</span>
                        <span>Materi Banner Telah Diterima</span>
                    </div>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        Pembayaran telah terverifikasi lunas. Admin SevenKost akan segera memeriksa materi grafis banner Anda dan mengaktifkannya dalam kurun waktu 1x24 jam kerja.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    @if(auth()->user()?->hasRole('pemilik'))
                        <a 
                            href="{{ route('owner.promosi.index', ['tab' => 'banner']) }}" 
                            class="flex-1 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-md shadow-blue-500/25 transition text-center"
                        >
                            Buka Pusat Promosi & Banner →
                        </a>
                        <a 
                            href="{{ route('owner.dashboard') }}" 
                            class="flex-1 py-3 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition text-center"
                        >
                            Buka Dashboard Pemilik
                        </a>
                    @else
                        <a 
                            href="{{ route('ads.packages.index', ['tab' => 'banner']) }}" 
                            class="flex-1 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-md shadow-blue-500/25 transition text-center"
                        >
                            Kembali ke Paket Iklan
                        </a>
                        <a 
                            href="{{ route('home') }}" 
                            class="flex-1 py-3 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition text-center"
                        >
                            Kembali ke Halaman Beranda
                        </a>
                    @endif
                </div>
            @endif

        </div>

    </div>

</x-seven-layout>

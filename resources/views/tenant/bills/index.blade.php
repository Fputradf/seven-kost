<x-tenant-layout title="Tagihan & Riwayat Pembayaran" :backUrl="route('tenant.dashboard')">

    <div 
        x-data="{ 
            activeTab: 'pending',
            cancelModalOpen: false,
            cancelActionUrl: '',
            cancelBillTitle: '',
            confirmCancel(url, title) {
                this.cancelActionUrl = url;
                this.cancelBillTitle = title;
                this.cancelModalOpen = true;
            }
        }" 
        class="space-y-4 max-w-xl mx-auto pb-12"
    >

        {{-- 1. HEADER CARD TAGIHAN --}}
        <div class="relative overflow-hidden rounded-3xl text-white p-5 shadow-lg border border-blue-400/25 space-y-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-white shrink-0 shadow-xs">
                        <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">Pusat Pembayaran</span>
                        <h1 class="text-base sm:text-lg font-black text-white tracking-tight">Tagihan &amp; Pembayaran</h1>
                    </div>
                </div>
                <div>
                    @if($pendingBills->count() > 0)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-500 text-white border border-rose-400 inline-flex items-center gap-1 shadow-xs animate-pulse">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            {{ $pendingBills->count() }} Belum Bayar
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-400 text-emerald-950 border border-emerald-300 inline-flex items-center gap-1 shadow-xs">
                            Semua Lunas
                        </span>
                    @endif
                </div>
            </div>

            <p class="text-xs text-sky-100/90 leading-relaxed">
                Kelola semua tagihan sewa kamar, perpanjangan masa sewa, top up dompet, dan pesanan layanan kos SevenKost Anda di satu tempat.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold shadow-2xs flex items-start gap-2">
                <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
                <span class="leading-relaxed">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold shadow-2xs flex items-start gap-2">
                <span class="w-4 h-4 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shrink-0 mt-0.5">!</span>
                <span class="leading-relaxed">{{ session('error') }}</span>
            </div>
        @endif

        {{-- 2. TABS NAVIGASI: MENUNGGU PEMBAYARAN | RIWAYAT LUNAS | DIBATALKAN --}}
        <div class="bg-slate-100/90 p-1 rounded-2xl flex items-center gap-1 border border-slate-200/60 shadow-inner">
            <button 
                type="button" 
                @click="activeTab = 'pending'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                :class="activeTab === 'pending' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <span>Menunggu Bayar</span>
                @if($pendingBills->count() > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black bg-rose-500 text-white">
                        {{ $pendingBills->count() }}
                    </span>
                @endif
            </button>

            <button 
                type="button" 
                @click="activeTab = 'paid'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                :class="activeTab === 'paid' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <span>Riwayat Lunas</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'failed'"
                class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                :class="activeTab === 'failed' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <span class="hidden sm:inline">Dibatalkan</span>
                <span class="sm:hidden">Batal</span>
            </button>
        </div>

        {{-- ============================================================== --}}
        {{-- TAB 1: TAGIHAN MENUNGGU PEMBAYARAN                             --}}
        {{-- ============================================================== --}}
        <div x-show="activeTab === 'pending'" x-transition class="space-y-3">
            @if($pendingBills->count() > 0)
                @foreach($pendingBills as $bill)
                    @php
                        $typeBadge = match($bill->type) {
                            'booking' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Sewa Kamar'],
                            'renewal' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Perpanjang Sewa'],
                            'topup' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Top Up Saldo'],
                            'service' => ['bg' => 'bg-teal-50 text-teal-700 border-teal-200', 'label' => 'Layanan Kos'],
                            default => ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => ucfirst($bill->type)],
                        };
                    @endphp
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3.5 hover:border-blue-300 transition">
                        {{-- Header Tagihan --}}
                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-mono text-xs font-bold text-slate-500">#{{ $bill->reference_id }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border {{ $typeBadge['bg'] }}">
                                    {{ $typeBadge['label'] }}
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                Menunggu Bayar
                            </span>
                        </div>

                        {{-- Judul & Nominal --}}
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-1 min-w-0 flex-1">
                                <h3 class="text-sm font-extrabold text-slate-900 leading-snug">
                                    {{ $bill->title }}
                                </h3>
                                <p class="text-[11px] text-slate-500">
                                    Dibuat pada {{ $bill->created_at->translatedFormat('d M Y, H:i') }}
                                </p>
                                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-600">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Batas Bayar: <strong class="text-slate-800">{{ $bill->formatted_expires_at }}</strong></span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] text-slate-400 block font-semibold uppercase">Total Tagihan</span>
                                <strong class="text-base sm:text-lg font-black text-blue-600">
                                    Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>

                        {{-- Tombol Aksi: Lanjutkan Pembayaran & Batalkan --}}
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            {{-- Tombol Batal Tagihan (Khusus topup/service/unpaid) --}}
                            @if(in_array($bill->type, ['topup', 'service'], true))
                                <button 
                                    type="button" 
                                    @click="confirmCancel('{{ route('tenant.bills.cancel', $bill->reference_id) }}', '{{ addslashes($bill->title) }}')"
                                    class="py-2 px-3 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 font-bold text-xs transition border border-slate-200 cursor-pointer active:scale-95"
                                >
                                    Batalkan
                                </button>
                            @endif

                            {{-- Tombol Utama: Bayar Sekarang --}}
                            <a 
                                href="{{ $bill->getPaymentUrl() }}" 
                                class="py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 flex items-center gap-1.5 cursor-pointer"
                            >
                                <span>Bayar Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- State Kosong: Tidak Ada Tagihan Pending --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Tidak Ada Tagihan Tertunda</h3>
                        <p class="text-xs text-slate-500 max-w-xs mx-auto mt-1 leading-relaxed">
                            Hebat! Semua tagihan Anda saat ini sudah selesai dibayar. Anda bebas dari tunggakan sewa dan pesanan.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('tenant.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:underline">
                            <span>Kembali ke Beranda</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- ============================================================== --}}
        {{-- TAB 2: RIWAYAT TAGIHAN LUNAS                                   --}}
        {{-- ============================================================== --}}
        <div x-show="activeTab === 'paid'" x-transition class="space-y-3" style="display: none;">
            @if($paidBills->count() > 0)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs divide-y divide-slate-100 overflow-hidden">
                    @foreach($paidBills as $pb)
                        @php
                            $pTypeBadge = match($pb->type) {
                                'booking' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Sewa Kamar'],
                                'renewal' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200', 'label' => 'Perpanjang Sewa'],
                                'topup' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Top Up Saldo'],
                                'service' => ['bg' => 'bg-teal-50 text-teal-700 border-teal-200', 'label' => 'Layanan Kos'],
                                default => ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => ucfirst($pb->type)],
                            };
                        @endphp
                        <div class="p-4 flex items-start justify-between gap-3 hover:bg-slate-50/50 transition">
                            <div class="space-y-1 min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono text-[10px] text-slate-400">#{{ $pb->reference_id }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold border {{ $pTypeBadge['bg'] }}">
                                        {{ $pTypeBadge['label'] }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $pb->title }}</h4>
                                <span class="text-[10px] text-slate-500 block">
                                    Lunas pada {{ $pb->paid_at ? $pb->paid_at->translatedFormat('d M Y, H:i') : $pb->updated_at->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <div class="text-right shrink-0">
                                <span class="text-xs sm:text-sm font-black text-slate-900 block">
                                    Rp {{ number_format($pb->amount, 0, ',', '.') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span>Lunas</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($paidBills->hasPages())
                    <div class="pt-2">
                        {{ $paidBills->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 text-center space-y-2 shadow-2xs">
                    <p class="text-xs font-bold text-slate-600">Belum Ada Riwayat Pembayaran Lunas</p>
                    <p class="text-[11px] text-slate-400">Transaksi yang sudah berhasil diverifikasi akan otomatis tercatat di sini.</p>
                </div>
            @endif
        </div>

        {{-- ============================================================== --}}
        {{-- TAB 3: TAGIHAN DIBATALKAN / GAGAL                              --}}
        {{-- ============================================================== --}}
        <div x-show="activeTab === 'failed'" x-transition class="space-y-3" style="display: none;">
            @if($failedBills->count() > 0)
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs divide-y divide-slate-100 overflow-hidden">
                    @foreach($failedBills as $fb)
                        <div class="p-4 flex items-start justify-between gap-3 opacity-75">
                            <div class="space-y-0.5 min-w-0 flex-1">
                                <span class="font-mono text-[10px] text-slate-400 block">#{{ $fb->reference_id }}</span>
                                <h4 class="text-xs font-bold text-slate-700 truncate line-through">{{ $fb->title }}</h4>
                                <span class="text-[10px] text-rose-500 font-semibold block">Dibatalkan</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs font-bold text-slate-500 line-through">
                                    Rp {{ number_format($fb->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 text-center space-y-2 shadow-2xs">
                    <p class="text-xs font-bold text-slate-600">Tidak Ada Tagihan yang Dibatalkan</p>
                </div>
            @endif
        </div>

        {{-- ============================================================== --}}
        {{-- POP UP ALERT MODAL KONFIRMASI BATALKAN TAGIHAN                 --}}
        {{-- ============================================================== --}}
        <div 
            x-show="cancelModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                @click.away="cancelModalOpen = false"
                class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Batalkan Tagihan Ini?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Apakah Anda yakin ingin membatalkan tagihan <strong class="text-slate-900" x-text="cancelBillTitle"></strong>? Tagihan yang dibatalkan tidak dapat dibayarkan kembali.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="cancelModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Tutup
                    </button>
                    <form :action="cancelActionUrl" method="POST" class="flex-1">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full py-2.5 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-sm transition cursor-pointer"
                        >
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>

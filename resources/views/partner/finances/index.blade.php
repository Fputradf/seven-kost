<x-partner-layout title="Dompet & Pendapatan Mitra">
    <div class="space-y-5 max-w-5xl">

        {{-- Header Card Gradient with Background Banner Image --}}
        <div class="relative overflow-hidden rounded-2xl p-5 text-white shadow-md border border-blue-400/25">
            <img src="{{ asset('images/frontend/banner-bandung-wide.jpg') }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(12, 43, 84, 0.92) 0%, rgba(20, 70, 133, 0.85) 60%, rgba(30, 91, 168, 0.78) 100%);"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Dompet &amp; Pendapatan</h1>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($partner)
                        <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full {{ $partner->is_open ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                            <span class="text-xs font-semibold text-white">
                                {{ $partner->is_open ? 'Toko Buka' : 'Toko Tutup' }}
                            </span>
                        </div>
                    @endif
                    <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/10 border border-white/20 text-white backdrop-blur-xs">
                        Saldo: Rp {{ number_format($walletBalance, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        {{-- 1. SALDO & METRIK CARD --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            {{-- Saldo Siap Ditarik --}}
            <div class="rounded-2xl p-5 text-white shadow-md relative overflow-hidden"
                 style="background: linear-gradient(135deg, #0c2b54 0%, #144685 60%, #1e5ba8 100%);">
                <span class="text-xs font-bold text-sky-200 uppercase tracking-wider block mb-1">Saldo Aktif Dompet</span>
                <div class="text-2xl sm:text-3xl font-extrabold">
                    Rp {{ number_format($walletBalance, 0, ',', '.') }}
                </div>
                <div class="mt-3 pt-3 border-t border-white/15 flex items-center justify-between text-xs">
                    <span class="text-white/80">Min. Pencairan:</span>
                    <span class="font-bold text-amber-300">Rp 20.000</span>
                </div>
            </div>

            {{-- Total Pendapatan Bersih --}}
            <div class="rounded-2xl bg-white border border-slate-200/90 p-5 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Pendapatan</span>
                <div class="text-2xl font-bold text-slate-900">
                    Rp {{ number_format($totalEarningsLifetime, 0, ',', '.') }}
                </div>
                <div class="pt-2 text-xs text-slate-500">
                    {{ $completedOrders->count() }} pesanan selesai
                </div>
            </div>

            {{-- Total Telah Ditarik --}}
            <div class="rounded-2xl bg-white border border-slate-200/90 p-5 shadow-xs space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Telah Dicairkan</span>
                <div class="text-2xl font-bold text-slate-900">
                    Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
                </div>
                <div class="pt-2 text-xs text-slate-500">
                    Ditransfer ke rekening bank
                </div>
            </div>

        </div>

        {{-- 2. FORM PENARIKAN SALDO DANA --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-5 shadow-xs space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Pencairan Saldo ke Rekening Bank</h3>
            </div>

            <form method="POST" action="{{ route('partner.finances.withdraw') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nominal Pencairan (Rp) *</label>
                        <input 
                            type="number" 
                            name="amount" 
                            min="20000"
                            max="{{ $walletBalance }}"
                            value="{{ old('amount') }}"
                            placeholder="Min. 20000" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Bank Tujuan *</label>
                        <input 
                            type="text" 
                            name="bank_name" 
                            value="{{ old('bank_name', $partner->bank_name ?? 'BCA') }}"
                            placeholder="Misal: BCA, Mandiri, BRI" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Rekening *</label>
                        <input 
                            type="text" 
                            name="account_number" 
                            value="{{ old('account_number', $partner->bank_account_number ?? '') }}"
                            placeholder="Nomor rekening" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pemilik Rekening *</label>
                        <input 
                            type="text" 
                            name="account_holder" 
                            value="{{ old('account_holder', $partner->bank_account_holder ?? auth()->user()->name) }}"
                            placeholder="Sesuai buku tabungan" 
                            required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end pt-1">
                    <button 
                        type="submit" 
                        class="px-5 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $walletBalance < 20000 ? 'disabled' : '' }}
                    >
                        Tarik Saldo
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. MUTASI TRANSAKSI DOMPET --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Riwayat Mutasi Saldo</h3>
            </div>

            @if ($walletTransactions->isEmpty())
                <div class="p-8 text-center text-xs text-slate-400">
                    Belum ada riwayat mutasi transaksi dompet.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($walletTransactions as $mutation)
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $mutation->type === 'withdrawal' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $mutation->type === 'withdrawal' ? 'Penarikan' : 'Pendapatan' }}
                                </span>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">{{ $mutation->description }}</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">
                                        #{{ $mutation->reference_id }} • {{ $mutation->created_at?->format('d M Y, H:i') }} WIB
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-sm font-bold block {{ $mutation->type === 'withdrawal' ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $mutation->type === 'withdrawal' ? '-' : '+' }} Rp {{ number_format($mutation->amount, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">
                                    Sisa: Rp {{ number_format((float) $mutation->balance_after, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-partner-layout>

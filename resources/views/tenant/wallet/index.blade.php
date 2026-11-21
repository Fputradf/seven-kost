<x-tenant-layout title="Dompet & Saldo">

    <div x-data="{ 
        showTopup: false, 
        showWithdraw: false,
        topupAmount: 100000,
        withdrawAmount: {{ min((float) $user->wallet_balance, 50000) }},
        formatRupiah(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        }
    }" class="space-y-4">

        {{-- 1. TOP SAPPHIRE WALLET CARD --}}
        <div class="relative overflow-hidden rounded-3xl text-white p-5 shadow-lg shadow-blue-950/20 border border-blue-400/25 space-y-4" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            {{-- Header info --}}
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">SevenKost Wallet</span>
                        <strong class="text-xs font-bold text-white">{{ $user->name }}</strong>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-200 border border-emerald-300/30">
                    Aktif
                </span>
            </div>

            {{-- Balance display --}}
            <div class="space-y-0.5">
                <span class="text-xs text-sky-100 font-medium block">Total Saldo Dompet</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Rp {{ number_format($user->wallet_balance, 0, ',', '.') }}
                </h2>
            </div>

            {{-- Action buttons: Top Up & Tarik Saldo --}}
            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-white/15">
                <button type="button" @click="showTopup = true; showWithdraw = false" class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-100 text-blue-900 font-extrabold text-xs transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Top Up Saldo</span>
                </button>

                <button type="button" @click="showWithdraw = true; showTopup = false" class="py-2.5 px-3 rounded-xl bg-white/20 hover:bg-white/30 text-white font-extrabold text-xs transition flex items-center justify-center gap-1.5 border border-white/30 active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Tarik Saldo</span>
                </button>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800 flex items-center gap-2.5 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-3 text-rose-800 flex items-center gap-2.5 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- TOP UP PENDING ALERT / KEMBALI KE MENU PEMBAYARAN --}}
        @if(isset($activePendingTopups) && $activePendingTopups->isNotEmpty())
            <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Top Up Menunggu Pembayaran
                    </span>
                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
                        {{ $activePendingTopups->count() }} Permintaan
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($activePendingTopups->take(3) as $pendingTopup)
                        <div class="bg-slate-50/70 p-3 rounded-xl border border-slate-200 flex items-center justify-between gap-2 shadow-2xs hover:border-blue-200 transition">
                            <div class="space-y-0.5 min-w-0 flex-1">
                                <span class="text-[10px] font-mono text-slate-400 block font-semibold">#{{ $pendingTopup->reference_id }}</span>
                                <strong class="text-sm font-black text-blue-700 block">
                                    Rp {{ number_format($pendingTopup->amount, 0, ',', '.') }}
                                </strong>
                                <span class="text-[10px] text-slate-500 block">
                                    Batas bayar: <span class="font-bold text-slate-700">{{ $pendingTopup->formatted_expires_at }}</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <form action="{{ route('tenant.bills.cancel', $pendingTopup->reference_id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan top up ini?');">
                                    @csrf
                                    <button type="submit" class="py-1.5 px-2.5 rounded-lg text-[11px] font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition cursor-pointer">
                                        Batal
                                    </button>
                                </form>
                                <a href="{{ route('tenant.wallet.payment', $pendingTopup->reference_id) }}" class="py-1.5 px-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition active:scale-95 flex items-center gap-1">
                                    <span>Bayar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($activePendingTopups->count() > 3)
                    <div class="pt-1 text-center">
                        <a href="{{ route('tenant.bills.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            Lihat {{ $activePendingTopups->count() - 3 }} transaksi pending lainnya di Tagihan &rarr;
                        </a>
                    </div>
                @endif
            </div>
        @endif

        {{-- 2. MODAL / CARD FORM TOP UP SALDO --}}
        <div x-show="showTopup" x-transition.opacity class="bg-white rounded-2xl border border-blue-200 p-4 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Top Up Saldo Dompet</h3>
                </div>
                <button type="button" @click="showTopup = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('tenant.wallet.topup') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Nominal Top Up Cepat</label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="nom in [50000, 100000, 250000, 500000, 1000000, 2000000]" :key="nom">
                            <button type="button" 
                                @click="topupAmount = nom"
                                :class="topupAmount === nom ? 'bg-blue-600 text-white font-black shadow-xs' : 'bg-slate-50 text-slate-700 font-semibold hover:bg-slate-100 border border-slate-200'"
                                class="py-2 text-[11px] rounded-xl transition text-center cursor-pointer">
                                <span x-text="formatRupiah(nom)"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div>
                    <label for="topup_custom" class="block text-xs font-bold text-slate-700 mb-1">Atau Masukkan Nominal Sendiri (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                        <input 
                            type="number" 
                            id="topup_custom" 
                            name="amount" 
                            x-model.number="topupAmount"
                            min="10000" 
                            max="50000000" 
                            step="5000" 
                            required
                            class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <span class="text-[10px] text-slate-500 mt-1 block">Minimal top up Rp 10.000 (Mendukung QRIS, VA Bank & E-Wallet).</span>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-blue-500/25 transition active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Lanjutkan Pembayaran Top Up (<span x-text="formatRupiah(topupAmount)"></span>)</span>
                </button>
            </form>
        </div>

        {{-- 3. MODAL / CARD FORM TARIK SALDO (WITHDRAWAL) --}}
        <div x-show="showWithdraw" x-transition.opacity class="bg-white rounded-2xl border border-indigo-200 p-4 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tarik Saldo ke Rekening / E-Wallet</h3>
                </div>
                <button type="button" @click="showWithdraw = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if((float) $user->wallet_balance < 20000)
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1">
                    <strong class="font-bold block">Saldo Belum Mencukupi</strong>
                    <p class="text-[11px] text-amber-800">
                        Batas minimal penarikan saldo adalah Rp 20.000. Saldo aktif Anda saat ini sebesar <strong>Rp {{ number_format($user->wallet_balance, 0, ',', '.') }}</strong>.
                    </p>
                </div>
            @else
                <form action="{{ route('tenant.wallet.withdraw') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="bank_name" class="block text-xs font-bold text-slate-700 mb-1">Bank / E-Wallet Tujuan</label>
                        <select id="bank_name" name="bank_name" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="">-- Pilih Bank / E-Wallet --</option>
                            <optgroup label="Bank Nasional">
                                <option value="BCA">BCA (Bank Central Asia)</option>
                                <option value="Bank Mandiri">Bank Mandiri</option>
                                <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
                                <option value="BNI">BNI (Bank Negara Indonesia)</option>
                                <option value="BSI">BSI (Bank Syariah Indonesia)</option>
                                <option value="CIMB Niaga">CIMB Niaga</option>
                                <option value="Permata Bank">Permata Bank</option>
                            </optgroup>
                            <optgroup label="E-Wallet">
                                <option value="GoPay">GoPay</option>
                                <option value="OVO">OVO</option>
                                <option value="DANA">DANA</option>
                                <option value="ShopeePay">ShopeePay</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="account_number" class="block text-xs font-bold text-slate-700 mb-1">Nomor Rekening / HP</label>
                            <input type="text" id="account_number" name="account_number" placeholder="Contoh: 1234567890" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="account_holder" class="block text-xs font-bold text-slate-700 mb-1">Atas Nama Rekening</label>
                            <input type="text" id="account_holder" name="account_holder" value="{{ $user->name }}" placeholder="Nama pemilik" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="withdraw_amount" class="text-xs font-bold text-slate-700">Nominal Penarikan (Rp)</label>
                            <button type="button" @click="withdrawAmount = {{ (float) $user->wallet_balance }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 cursor-pointer">
                                Tarik Semua (Rp {{ number_format($user->wallet_balance, 0, ',', '.') }})
                            </button>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                            <input 
                                type="number" 
                                id="withdraw_amount" 
                                name="amount" 
                                x-model.number="withdrawAmount"
                                min="20000" 
                                max="{{ (float) $user->wallet_balance }}" 
                                step="1000" 
                                required
                                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                        <span class="text-[10px] text-slate-500 mt-1 block">Minimal penarikan Rp 20.000. Saldo langsung diproses ke rekening tujuan.</span>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs sm:text-sm shadow-md transition active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Konfirmasi Tarik Saldo (<span x-text="formatRupiah(withdrawAmount)"></span>)</span>
                    </button>
                </form>
            @endif
        </div>

        {{-- 4. RIWAYAT MUTASI DOMPET (WALLET TRANSACTIONS) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Riwayat Mutasi Saldo</span>
                </h3>
                <span class="text-[10px] text-slate-500 font-semibold">{{ $transactions->total() }} Transaksi</span>
            </div>

            @if($transactions->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($transactions as $trx)
                        @php
                            $isCredit = in_array($trx->type, ['topup', 'refund'], true);
                            $badge = match($trx->type) {
                                'topup' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Top Up'],
                                'payment' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Bayar Sewa'],
                                'withdrawal' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Tarik Saldo'],
                                default => ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => ucfirst($trx->type)],
                            };
                        @endphp
                        <div class="py-3 flex items-start justify-between gap-3">
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold border {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $trx->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 leading-snug truncate">
                                    {{ $trx->description }}
                                </p>
                                <span class="text-[10px] text-slate-500 block truncate">
                                    Sisa Saldo: Rp {{ number_format($trx->balance_after, 0, ',', '.') }} &bull; Ref: #{{ $trx->reference_id }}
                                </span>
                            </div>

                            <div class="text-right shrink-0">
                                <strong class="text-xs sm:text-sm font-black {{ $isCredit ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $isCredit ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </strong>
                                <span class="block text-[9px] font-bold mt-0.5 text-emerald-600">
                                    Berhasil
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($transactions->hasPages())
                    <div class="pt-2 border-t border-slate-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <div class="py-8 text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <strong class="text-xs font-bold text-slate-700 block">Belum Ada Riwayat Mutasi</strong>
                    <p class="text-[11px] text-slate-500">
                        Lakukan Top Up saldo untuk mulai menikmati kemudahan pembayaran sewa kos dan penarikan dana tanpa ribet.
                    </p>
                </div>
            @endif
        </div>

    </div>

</x-tenant-layout>

<x-tenant-layout title="Portal Penghuni">

    <div class="space-y-4">

        {{-- 1. USER GREETING HEADER CARD (DENGAN SAPAAN PERSONAL & RINGAN, TANPA DUPLIKASI CARI KOS) --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 sm:p-5 shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between gap-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 truncate group">
                    {{-- User Avatar / Photo --}}
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-white/40 shadow-2xs shrink-0 group-hover:scale-105 transition-transform">
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center font-black text-lg border border-white/30 shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="truncate">
                        <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">Selamat Datang</span>
                        <h2 class="text-base sm:text-lg font-black text-white !text-white truncate">
                            Halo, {{ $user->name }}
                        </h2>
                        <span class="inline-flex items-center gap-1 text-xs text-white/90 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span>Penghuni Terdaftar</span>
                        </span>
                    </div>
                </a>

                {{-- Status Ringan Sisi Kanan (Tanggal Hari Ini & Badge) --}}
                <div class="shrink-0 text-right">
                    <span class="text-xs font-bold text-sky-200 block">{{ now()->translatedFormat('d M Y') }}</span>
                    <span class="inline-flex items-center gap-1 text-[11px] bg-white/20 px-2 py-0.5 rounded-full text-white font-semibold backdrop-blur-xs mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Aktif</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- 2. DOMPET DIGITAL SEVENKOST (SINKRONISASI SALDO, TOP UP & TARIK SALDO DENGAN STATE DISABLE ABU-ABU) --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div class="truncate">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Saldo Dompet SevenKost</span>
                        <strong class="text-base sm:text-lg font-black text-slate-900 block truncate">
                            Rp {{ number_format($user->wallet_balance ?? 0, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <a href="{{ route('tenant.wallet.index') }}" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs transition shadow-xs active:scale-95">
                        + Top Up
                    </a>
                    @if(($user->wallet_balance ?? 0) > 0)
                        <a href="{{ route('tenant.wallet.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition border border-slate-200 active:scale-95">
                            Tarik Saldo
                        </a>
                    @else
                        <button type="button" disabled class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs border border-slate-200 cursor-not-allowed" title="Saldo Rp 0">
                            Tarik Saldo
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Flash Notification --}}
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

        {{-- BANNER PERINGATAN TAGIHAN MENUNGGU PEMBAYARAN (SEMUA TIPE: SEWA, PERPANJANG, TOP UP, LAYANAN) --}}
        @if(isset($userPendingBills) && $userPendingBills->isNotEmpty())
            <div class="rounded-2xl bg-white border border-slate-200/90 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider">
                            Tagihan Menunggu Pembayaran ({{ $userPendingBills->count() }})
                        </h3>
                    </div>
                    <a href="{{ route('tenant.bills.index') }}" class="text-[11px] font-bold text-blue-600 hover:underline flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="space-y-2">
                    @foreach($userPendingBills->take(2) as $pBill)
                        @php
                            $bBadge = match($pBill->type) {
                                'booking' => ['bg' => 'bg-blue-100 text-blue-800', 'label' => 'Sewa Kos'],
                                'renewal' => ['bg' => 'bg-purple-100 text-purple-800', 'label' => 'Perpanjang'],
                                'topup' => ['bg' => 'bg-emerald-100 text-emerald-800', 'label' => 'Top Up'],
                                'service' => ['bg' => 'bg-teal-100 text-teal-800', 'label' => 'Layanan'],
                                default => ['bg' => 'bg-slate-100 text-slate-800', 'label' => ucfirst($pBill->type)],
                            };
                        @endphp
                        <div class="bg-slate-50/70 rounded-xl p-3 border border-slate-200/90 shadow-2xs flex items-center justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-mono text-[10px] text-slate-400 font-semibold">#{{ $pBill->reference_id }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black {{ $bBadge['bg'] }}">
                                        {{ $bBadge['label'] }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 truncate mt-0.5">{{ $pBill->title }}</h4>
                                <span class="text-sm font-black text-blue-600 block mt-0.5">
                                    Rp {{ number_format($pBill->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="shrink-0">
                                <a 
                                    href="{{ $pBill->getPaymentUrl() }}" 
                                    class="py-2 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 flex items-center gap-1 cursor-pointer"
                                >
                                    <span>Bayar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 3. CARD TAGIHAN SEWA AWAL (STATUS = APPROVED / MENUNGGU PEMBAYARAN) --}}
        @if($activeBooking && $activeBooking->status === 'approved' && $initialBill)
            <div class="rounded-2xl bg-white border border-slate-200/90 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Disetujui Pemilik &bull; Menunggu Pembayaran
                    </span>
                </div>

                <div class="flex items-center justify-between gap-3 bg-slate-50/70 p-3 rounded-xl border border-slate-200/90">
                    <div class="min-w-0 flex-1 truncate">
                        <h4 class="text-xs font-black text-slate-900 truncate">{{ $activeBooking->kost->name }}</h4>
                        <p class="text-[11px] text-slate-500">Kamar: {{ $activeBooking->room->name ?? '-' }} ({{ $activeBooking->duration_months }} Bulan)</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] text-slate-400 block font-bold">Total Biaya Sewa</span>
                        <strong class="text-sm font-black text-blue-700">Rp {{ number_format($initialBill['total_amount'], 0, ',', '.') }}</strong>
                    </div>
                </div>

                {{-- Action Buttons Pembayaran Sewa Awal --}}
                <div class="space-y-2 pt-1">
                    @if($user->wallet_balance >= $initialBill['total_amount'])
                        <form action="{{ route('tenant.bookings.pay-wallet', $activeBooking) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Bayar via Saldo Dompet (Rp {{ number_format($initialBill['total_amount'], 0, ',', '.') }})</span>
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('tenant.bookings.pay', $activeBooking) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Bayar Sekarang (Transfer / E-Wallet - Xendit)</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- 4. CARD TAGIHAN PERPANJANGAN SEWA OTOMATIS (AKTIF H-7 / LEWAT JATUH TEMPO) --}}
        @if($activeBooking && $activeBooking->status === 'active' && $renewalBill)
            @php
                $isOverdue = $activeBooking->isOverdue();
            @endphp
            <div class="rounded-2xl bg-white border border-slate-200/90 p-4 shadow-2xs space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full {{ $isOverdue ? 'bg-rose-50 text-rose-800 border border-rose-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }} text-[10px] font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full {{ $isOverdue ? 'bg-rose-600 animate-ping' : 'bg-amber-600' }}"></span>
                        {{ $isOverdue ? '⚠️ Tagihan Lewat Jatuh Tempo' : '🔔 Tagihan Perpanjangan Sewa (H-7)' }}
                    </span>

                    <span class="text-xs font-bold {{ $isOverdue ? 'text-rose-700' : 'text-amber-800' }}">
                        {{ $isOverdue ? 'Terlambat ' . $renewalBill['overdue_days'] . ' Hari' : 'Jatuh Tempo: ' . ($activeBooking->end_date?->format('d M Y') ?? '-') }}
                    </span>
                </div>

                {{-- Breakdown Rincian Tagihan --}}
                <div class="bg-white rounded-xl p-3 border border-slate-200/90 text-xs space-y-1.5">
                    <div class="flex justify-between text-slate-600">
                        <span>Sewa Kamar (1 Bulan):</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($renewalBill['room_price'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Administrasi:</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($renewalBill['fee_booking_tenant'], 0, ',', '.') }}</span>
                    </div>
                    @if($renewalBill['late_fee'] > 0)
                        <div class="flex justify-between text-rose-600 font-bold pt-1 border-t border-rose-100">
                            <span>Denda Keterlambatan ({{ $renewalBill['late_fee_days'] }} Hari):</span>
                            <span>+ Rp {{ number_format($renewalBill['late_fee'], 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center text-slate-900 font-black pt-2 border-t border-slate-100 text-sm">
                        <span>Total Tagihan:</span>
                        <span class="text-blue-700 font-black text-base">Rp {{ number_format($renewalBill['grand_total'], 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Tombol Pembayaran Perpanjangan --}}
                <div class="space-y-2">
                    @if($user->wallet_balance >= $renewalBill['grand_total'])
                        <form action="{{ route('tenant.bookings.renew-wallet', $activeBooking) }}" method="POST">
                            @csrf
                            <input type="hidden" name="duration_months" value="1">
                            <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Bayar via Saldo Dompet (Rp {{ number_format($renewalBill['grand_total'], 0, ',', '.') }})</span>
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('tenant.bookings.renew', $activeBooking) }}" method="POST">
                        @csrf
                        <input type="hidden" name="duration_months" value="1">
                        <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Bayar & Perpanjang Sewa via Gateway</span>
                        </button>
                    </form>

                    {{-- Tombol Tidak Ingin Perpanjang / Check-Out --}}
                    <div class="pt-1.5 text-center">
                        <form action="{{ route('tenant.bookings.terminate', $activeBooking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin tidak ingin memperpanjang sewa dan akan check-out? Seluruh tagihan perpanjangan otomatis berikutnya akan dihentikan.')">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 underline transition cursor-pointer">
                                Tidak Memperpanjang? Akhiri Sewa & Check-Out Kamar &rarr;
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- 5. STATUS KAMAR HUNIAN AKTIF --}}
        @if($activeBooking && $activeBooking->status === 'active')
            <div class="rounded-3xl bg-white border border-slate-200/90 p-5 shadow-sm space-y-4 relative overflow-hidden">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Hunian Kos Aktif
                    </span>

                    @php
                        $daysRemaining = max(0, (int) now()->diffInDays($activeBooking->end_date, false));
                    @endphp
                    @if($daysRemaining < 30)
                        <span class="text-xs font-black text-amber-900 bg-amber-100 border border-amber-300 px-2.5 py-1 rounded-xl flex items-center gap-1 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            <span>{{ $daysRemaining }} Hari Lagi</span>
                        </span>
                    @else
                        <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-xl">
                            {{ $daysRemaining }} Hari Lagi
                        </span>
                    @endif
                </div>

                <div class="flex items-start gap-3.5">
                    <a href="{{ route('tenant.bookings.show', $activeBooking) }}" class="w-20 h-20 rounded-2xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200/90 block group shadow-2xs">
                        <img src="{{ $activeBooking->kost->primary_image_url }}" alt="{{ $activeBooking->kost->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </a>
                    <div class="min-w-0 flex-1">
                        {{-- Judul Kos 2 Baris Tanpa Terpotong --}}
                        <h3 class="text-base sm:text-lg font-black text-slate-900 line-clamp-2 leading-snug">
                            <a href="{{ route('tenant.bookings.show', $activeBooking) }}" class="hover:text-blue-600 transition">
                                {{ $activeBooking->kost->name }}
                            </a>
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium truncate mt-1">
                            {{ $activeBooking->room->name ?? 'Standard' }} &bull; {{ $activeBooking->kost->regency?->name ?? 'Kota' }}
                        </p>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Sampai: <strong class="text-slate-900 font-black">{{ $activeBooking->end_date?->format('d M Y') ?? '-' }}</strong>
                        </p>
                    </div>
                </div>

                {{-- Tombol Utama Perpanjang Sewa (Fokus Penting Saat Sisa < 30 Hari) --}}
                <div class="pt-1">
                    <a 
                        href="{{ route('tenant.bookings.show', $activeBooking) }}" 
                        class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs sm:text-sm shadow-md shadow-blue-500/25 transition active:scale-98"
                    >
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Perpanjang Sewa Kamar</span>
                    </a>
                </div>

                {{-- Action Sub-Buttons: Detail, Tanya Pemilik, Lapor --}}
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('tenant.bookings.show', $activeBooking) }}" class="inline-flex items-center justify-center gap-1.5 py-2 px-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95 text-center">
                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </a>
                        @php
                            $rawOwnerPhone = $activeBooking->kost->owner->phone ?? '081234567890';
                            $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerPhone);
                            if (str_starts_with($cleanOwnerPhone, '0')) {
                                $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
                            }
                            $waText = urlencode("Halo bapak/ibu {$activeBooking->kost->owner->name}, saya {$user->name} penghuni kamar {$activeBooking->room->name} kos {$activeBooking->kost->name}.");
                            $waLink = "https://wa.me/{$cleanOwnerPhone}?text={$waText}";
                        @endphp
                        <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 py-2 px-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95 text-center">
                            <svg class="w-3.5 h-3.5 fill-current text-emerald-600 shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Tanya Pemilik</span>
                        </a>
                        <a href="{{ route('tenant.complaints.create') }}" class="inline-flex items-center justify-center gap-1.5 py-2 px-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95 text-center">
                            <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Lapor</span>
                        </a>
                    </div>

                    {{-- Aksi Destruktif: Tautan Teks Kecil Subtle (Aman dari Sentuhan Tidak Sengaja) --}}
                    <div class="pt-1.5 text-center">
                        <form action="{{ route('tenant.bookings.terminate', $activeBooking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri sewa dan check-out dari kamar ini? Seluruh tagihan perpanjangan otomatis berikutnya akan dihentikan.')" class="w-full">
                            @csrf
                            <button type="submit" class="text-xs text-slate-400 hover:text-rose-600 transition underline cursor-pointer">
                                Ingin berhenti sewa? Akhiri Sewa & Check-Out Kamar &rarr;
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif(!$activeBooking || $activeBooking->status !== 'approved')
            {{-- Empty State Active Stay --}}
            <div class="rounded-2xl bg-white border border-slate-200/90 p-4 text-center space-y-2 shadow-2xs">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum Ada Kamar Aktif</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto">
                    Temukan kamar kos impian Anda dan ajukan sewa langsung dengan proses mudah dan aman.
                </p>
                <div class="pt-1">
                    <a href="{{ route('tenant.search') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition active:scale-95">
                        <span>Cari & Sewa Kos</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- CARD STATUS LAYANAN KAMAR AKTIF / TERKINI --}}
        @if(isset($recentServiceOrders) && $recentServiceOrders->isNotEmpty())
            <div class="rounded-2xl bg-white border border-blue-200/90 p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Pesanan Layanan Kamar
                    </span>
                    <a href="{{ route('tenant.services.index', ['tab' => 'orders']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="space-y-2">
                    @foreach($recentServiceOrders as $srvOrder)
                        @php
                            $srvPayload = $srvOrder->payload ?? [];
                            $isPaid = $srvOrder->isPaid();
                        @endphp
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <strong class="text-slate-900 font-bold truncate max-w-[180px]">{{ $srvOrder->title }}</strong>
                                @if($isPaid)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        Diproses Mitra
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                        Menunggu Bayar
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>Jadwal: {{ $srvPayload['order_date'] ?? '-' }} ({{ $srvPayload['order_time'] ?? '-' }} WIB)</span>
                                <strong class="text-blue-600 font-bold">Rp {{ number_format($srvOrder->amount, 0, ',', '.') }}</strong>
                            </div>

                            @if(!$isPaid)
                                <div class="pt-1">
                                    <a href="{{ route('tenant.services.payment', $srvOrder->reference_id) }}" class="w-full py-1.5 px-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center block transition active:scale-95 shadow-2xs">
                                        Selesaikan Pembayaran &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 6. MENU PINTASAN APLIKASI (QUICK APP TILES - NON-DUPLICATE, ALL BLUE FAMILY) --}}
        <div>
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 px-1">Menu Utama</h3>
            <div class="grid grid-cols-4 gap-2 text-center">
                {{-- Tile 1: Pesanan Layanan --}}
                <a href="{{ route('tenant.services.index', ['tab' => 'orders']) }}" class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 shadow-2xs flex flex-col items-center gap-1.5 transition active:scale-95 hover:border-blue-300 relative group">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center font-bold group-hover:bg-blue-100/70 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-700 leading-tight">Pesanan</span>
                    @if(isset($stats['total_service_orders']) && $stats['total_service_orders'] > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-blue-600 text-white rounded-full text-[9px] font-black flex items-center justify-center shadow-xs">
                            {{ $stats['total_service_orders'] }}
                        </span>
                    @endif
                </a>

                {{-- Tile 2: Riwayat Pembayaran --}}
                <a href="{{ route('tenant.bookings.index') }}" class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 shadow-2xs flex flex-col items-center gap-1.5 transition active:scale-95 hover:border-blue-300 group">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center font-bold group-hover:bg-blue-100/70 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-700 leading-tight">Pembayaran</span>
                </a>

                {{-- Tile 3: Kontrak / Dokumen Sewa --}}
                <a href="{{ $activeBooking ? route('tenant.bookings.show', $activeBooking) : route('tenant.bookings.index') }}" class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 shadow-2xs flex flex-col items-center gap-1.5 transition active:scale-95 hover:border-blue-300 group">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center font-bold group-hover:bg-blue-100/70 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-700 leading-tight">Kontrak</span>
                </a>

                {{-- Tile 4: Bantuan & Kendala --}}
                <a href="{{ url('/penghuni/bantuan') }}" class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/80 shadow-2xs flex flex-col items-center gap-1.5 transition active:scale-95 hover:border-blue-300 group">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-blue-50 text-blue-700 border border-blue-100 flex items-center justify-center font-bold group-hover:bg-blue-100/70 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-700 leading-tight">Bantuan</span>
                </a>
            </div>
        </div>

        {{-- 7. KOS FAVORIT SAYA (PREVIEW CEPAT) --}}
        @if($favoriteKosts->isNotEmpty())
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kos Favorit Saya</h3>
                    <a href="{{ route('tenant.wishlists.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @foreach($favoriteKosts as $fav)
                        <a href="{{ route('tenant.kost.detail', $fav->slug) }}" class="p-2.5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex items-center gap-3 transition active:scale-98 hover:border-blue-300">
                            <img src="{{ $fav->primary_image_url }}" alt="{{ $fav->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                            <div class="min-w-0 flex-1 truncate">
                                <h4 class="text-xs font-black text-slate-900 truncate">{{ $fav->name }}</h4>
                                <p class="text-[10px] text-slate-500 truncate">{{ $fav->regency?->name ?? 'Indonesia' }} &bull; Kos {{ ucfirst($fav->type) }}</p>
                                <span class="text-xs font-extrabold text-blue-600 mt-0.5 block">Rp {{ number_format($fav->price_start, 0, ',', '.') }} / bln</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-tenant-layout>

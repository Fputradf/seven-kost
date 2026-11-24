<x-tenant-layout title="Detail Kamar & Sewa" :backUrl="route('tenant.bookings.index')">

    @php
        $statusConfig = match($booking->status) {
            'approved' => [
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                'label' => 'Disetujui - Menunggu Pembayaran',
                'desc' => 'Pengajuan sewa telah disetujui pemilik kos! Silakan lakukan pembayaran agar kamar resmi aktif untuk Anda huni.',
            ],
            'active' => [
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'label' => 'Hunian Aktif',
                'desc' => 'Pembayaran lunas dan kamar telah resmi aktif untuk Anda huni.',
            ],
            'pending' => [
                'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                'label' => 'Menunggu Konfirmasi Pemilik',
                'desc' => 'Menunggu pemilik kos memverifikasi dan menyetujui pengajuan sewa Anda.',
            ],
            'rejected' => [
                'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                'label' => 'Pengajuan Ditolak',
                'desc' => 'Pengajuan sewa belum dapat disetujui oleh pemilik kos saat ini.',
            ],
            'cancelled' => [
                'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                'label' => 'Dibatalkan',
                'desc' => 'Pengajuan sewa telah dibatalkan oleh penyewa.',
            ],
            default => [
                'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                'label' => 'Selesai',
                'desc' => 'Masa sewa kamar kos telah selesai.',
            ],
        };

        $rawOwnerPhone = $booking->kost->owner->phone ?? '081234567890';
        $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerPhone);
        if (str_starts_with($cleanOwnerPhone, '0')) {
            $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
        }
        $ownerName = $booking->kost->owner->name ?? 'Pemilik';
        $occupantName = $booking->tenant_name ?: (auth()->user()?->name ?? 'Penyewa');
        $kostName = $booking->kost->name ?? 'Kos';
        $roomName = $booking->room->name ?? 'Standard';
        $waText = urlencode("Halo bapak/ibu {$ownerName}, saya {$occupantName} penyewa kamar {$roomName} kos {$kostName}.");
        $waLink = "https://wa.me/{$cleanOwnerPhone}?text={$waText}";

        $daysRemaining = null;
        if ($booking->status === 'active' && $booking->end_date) {
            $daysRemaining = max(0, (int) now()->diffInDays($booking->end_date, false));
        }
    @endphp

    <div class="space-y-4">

        {{-- 1. TOP STATUS CARD (SIGNATURE SAPPHIRE GRADIENT) --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-2.5" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider">
                    ID Booking: #BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                @if(!is_null($daysRemaining))
                    @if($booking->isOverdue())
                        <span class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-bold border border-rose-300 backdrop-blur-xs">
                            Terlambat {{ $booking->getOverdueDays() }} Hari
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-white/20 text-white text-[10px] font-bold border border-white/30 backdrop-blur-xs">
                            Sisa {{ $daysRemaining }} Hari
                        </span>
                    @endif
                @endif
            </div>

            <div>
                <h2 class="text-base sm:text-lg font-black text-white !text-white leading-tight" style="color: #ffffff !important;">
                    {{ $booking->kost->name }}
                </h2>
                <p class="text-xs text-sky-100 mt-0.5 font-medium">
                    {{ $roomName }} &bull; {{ $booking->kost->regency?->name ?? 'Kota' }}
                </p>
            </div>

            <div class="pt-1.5 border-t border-white/15 flex items-center justify-between text-xs">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusConfig['badge'] }}">
                    {{ $statusConfig['label'] }}
                </span>
                <span class="text-white/90 text-[11px] font-medium">
                    Diajukan {{ $booking->created_at->format('d M Y') }}
                </span>
            </div>
        </div>

        {{-- Flash message --}}
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

        {{-- TAGIHAN PERPANJANGAN SEWA & DENDA BERKALA (JIKA AKTIF H-7 ATAU TERLAMBAT) --}}
        @if($booking->status === 'active' && $booking->isDueForRenewal())
            @php
                $isOverdue = $booking->isOverdue();
                $overdueDays = $booking->getOverdueDays();
                $lateFeeDays = $booking->getLateFeeDays();
                $lateFee = $booking->getLateFee();
                $roomMonthly = (float) ($booking->room?->price ?? ($booking->duration_months > 0 ? $booking->total_amount / $booking->duration_months : 0));
                $tenantFee = (float) setting('fee_booking_tenant', 5000);
            @endphp

            <div x-data="{
                duration: 1,
                roomPrice: {{ $roomMonthly }},
                lateFee: {{ $lateFee }},
                serviceFee: {{ $tenantFee }},
                get subtotal() {
                    return this.duration * this.roomPrice;
                },
                get total() {
                    return this.subtotal + this.serviceFee + this.lateFee;
                },
                formatRupiah(num) {
                    return 'Rp ' + Number(num).toLocaleString('id-ID');
                }
            }" class="rounded-2xl border {{ $isOverdue ? 'border-rose-300 bg-rose-50/40' : 'border-blue-200 bg-blue-50/40' }} p-4 shadow-sm space-y-3.5">
                
                {{-- Header Tagihan --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="space-y-0.5">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $isOverdue ? 'bg-rose-100 text-rose-800 border border-rose-300' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isOverdue ? 'bg-rose-600 animate-ping' : 'bg-blue-600' }}"></span>
                            {{ $isOverdue ? 'Tagihan Lewat Jatuh Tempo' : 'Tagihan Perpanjangan Aktif' }}
                        </span>
                        <h3 class="text-sm sm:text-base font-black text-slate-900">
                            Tagihan Periode Berikutnya
                        </h3>
                        <p class="text-[11px] text-slate-600">
                            Jatuh tempo sewa: <strong class="text-slate-800 font-bold">{{ $booking->end_date->format('d M Y') }}</strong>
                            @if($isOverdue)
                                <span class="text-rose-600 font-bold">(Terlambat {{ $overdueDays }} hari)</span>
                            @else
                                <span class="text-blue-600 font-semibold">(Sisa {{ max(0, (int) now()->startOfDay()->diffInDays($booking->end_date->startOfDay(), false)) }} hari)</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-semibold text-slate-500 block">Total Tagihan</span>
                        <strong class="text-base font-black {{ $isOverdue ? 'text-rose-600' : 'text-blue-600' }}" x-text="formatRupiah(total)">
                            Rp {{ number_format($roomMonthly + $tenantFee + $lateFee, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                {{-- Alert Denda jika Melewati Toleransi Grace Period --}}
                @if($isOverdue && $lateFeeDays > 0)
                    <div class="p-3 rounded-xl bg-rose-100/80 border border-rose-300 text-rose-900 text-xs space-y-1">
                        <div class="flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>Denda Keterlambatan Sewa Aktif</span>
                        </div>
                        <p class="text-[11px] leading-relaxed text-rose-800">
                            Pembayaran sewa telah melewati batas toleransi (H+{{ (int) setting('late_fee_grace_days', 1) }}). Dikenakan denda harian sebesar <strong>Rp {{ number_format((float) setting('late_fee_daily_amount', 20000), 0, ',', '.') }} / hari</strong> (terhitung {{ $lateFeeDays }} hari keterlambatan = Rp {{ number_format($lateFee, 0, ',', '.') }}). Denda bertambah setiap hari hingga perpanjangan sewa dilunasi.
                        </p>
                    </div>
                @elseif($isOverdue)
                    <div class="p-2.5 rounded-xl bg-amber-100/80 border border-amber-300 text-amber-900 text-[11px] leading-relaxed">
                        Masa sewa kamar Anda telah berakhir hari ini. Anda saat ini masih berada dalam <strong>masa toleransi (grace period)</strong>. Bayar tagihan sekarang untuk menghindari denda keterlambatan harian mulai esok hari.
                    </div>
                @endif

                {{-- Form Perpanjangan Sewa --}}
                <form action="{{ route('tenant.bookings.renew', $booking) }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Pilih Durasi Perpanjangan:
                        </label>
                        <div class="grid grid-cols-4 gap-1.5">
                            <template x-for="m in [1, 3, 6, 12]" :key="m">
                                <button type="button" 
                                    @click="duration = m"
                                    :class="duration === m ? 'bg-blue-600 text-white font-black shadow-xs' : 'bg-white text-slate-700 font-semibold border border-slate-200 hover:bg-slate-50'"
                                    class="py-2 text-xs rounded-xl transition text-center cursor-pointer">
                                    <span x-text="m + ' Bln'"></span>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="duration_months" :value="duration">
                    </div>

                    {{-- Rincian Biaya Perpanjangan --}}
                    <div class="bg-white rounded-xl p-3 border border-slate-200/90 text-xs space-y-1.5">
                        <div class="flex justify-between text-slate-600">
                            <span>Sewa Kamar (<span x-text="duration"></span> Bulan)</span>
                            <span class="font-semibold text-slate-800" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Layanan Transaksi</span>
                            <span class="font-semibold text-slate-800" x-text="formatRupiah(serviceFee)"></span>
                        </div>
                        @if($lateFee > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Denda Keterlambatan ({{ $lateFeeDays }} Hari)</span>
                                <span class="font-bold text-rose-600" x-text="formatRupiah(lateFee)"></span>
                            </div>
                        @endif
                        <div class="pt-1.5 border-t border-slate-100 flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-900">Total Pembayaran</span>
                            <strong class="text-sm font-black text-blue-600" x-text="formatRupiah(total)"></strong>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        <button type="submit" formaction="{{ route('tenant.bookings.renew-wallet', $booking) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Bayar Cepat via Saldo Dompet</span>
                        </button>

                        <button type="submit" formaction="{{ route('tenant.bookings.renew', $booking) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-blue-500/25 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Bayar & Perpanjang Sewa (<span x-text="formatRupiah(total)"></span>)</span>
                        </button>
                    </div>

                    {{-- Opsi Tidak Ingin Perpanjang / Check-Out --}}
                    <div class="pt-2 border-t border-slate-200 text-center">
                        <button 
                            type="submit" 
                            formaction="{{ route('tenant.bookings.terminate', $booking) }}" 
                            formmethod="POST"
                            onclick="return confirm('Apakah Anda yakin tidak ingin memperpanjang sewa kamar ini? Kamar akan resmi di-checkout dan tagihan otomatis dibatalkan.')"
                            class="text-xs font-bold text-rose-600 hover:text-rose-800 underline transition cursor-pointer"
                        >
                            Tidak Memperpanjang? Akhiri Sewa & Check-Out Kamar &rarr;
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- 2. KAMAR & KOS PROFILE CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs space-y-3 p-3.5">
            {{-- Foto Kamar / Kos --}}
            <div class="relative h-44 sm:h-48 w-full rounded-xl overflow-hidden bg-slate-100 border border-slate-100">
                <img 
                    src="{{ $booking->kost->primary_image_url }}" 
                    alt="{{ $booking->kost->name }}" 
                    class="w-full h-full object-cover"
                >
                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded-lg bg-black/60 text-white text-[10px] font-bold capitalize backdrop-blur-xs">
                        Kos {{ $booking->kost->type }}
                    </span>
                    @if($booking->room?->room_size)
                        <span class="px-2 py-0.5 rounded-lg bg-blue-600/90 text-white text-[10px] font-bold backdrop-blur-xs">
                            {{ $booking->room->room_size }}
                        </span>
                    @endif
                </div>

                <div class="absolute bottom-2.5 right-2.5 bg-black/60 backdrop-blur-xs px-2 py-1 rounded-lg text-white text-[11px] font-bold">
                    {{ $booking->duration_months }} Bulan Sewa
                </div>
            </div>

            {{-- Nama Kos & Kamar --}}
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">Tipe Kamar:</span>
                <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug">
                    {{ $roomName }} &bull; {{ $booking->kost->name }}
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ $booking->kost->address }}
                </p>
            </div>

            {{-- Fasilitas Kamar & Kos --}}
            @if($booking->room?->facilities && $booking->room->facilities->count() > 0)
                <div class="pt-2 border-t border-slate-100 space-y-1.5">
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">Fasilitas Kamar Ini:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($booking->room->facilities as $facility)
                            <span class="inline-flex items-center gap-1 text-[11px] text-blue-700 bg-blue-50 border border-blue-200/80 px-2 py-0.5 rounded-lg font-medium">
                                <svg class="w-3 h-3 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>{{ $facility->name }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($booking->kost->facilities && $booking->kost->facilities->count() > 0)
                <div class="pt-2 border-t border-slate-100 space-y-1.5">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Fasilitas Bersama Properti Kos:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($booking->kost->facilities as $facility)
                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-700 bg-slate-50 border border-slate-200/80 px-2 py-0.5 rounded-lg">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>{{ $facility->name }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- 3. DATA DIRI PENGHUNI --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Data Diri Penghuni</span>
            </h4>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Nama Lengkap:</span>
                    <strong class="text-slate-800 font-bold">{{ $booking->tenant_name ?: ($booking->tenant->name ?? '-') }}</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Nomor WhatsApp:</span>
                    <strong class="text-slate-800 font-bold">{{ $booking->tenant_phone ?: ($booking->tenant->phone ?? '-') }}</strong>
                </div>
                @if($booking->tenant_nik)
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">NIK KTP:</span>
                        <strong class="text-slate-800 font-mono font-bold">{{ $booking->tenant_nik }}</strong>
                    </div>
                @endif
                @if($booking->tenant_gender)
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Jenis Kelamin:</span>
                        <strong class="text-slate-800 font-bold capitalize">{{ $booking->tenant_gender }}</strong>
                    </div>
                @endif
                @if($booking->tenant_job)
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Pekerjaan / Status:</span>
                        <strong class="text-slate-800 font-bold">{{ $booking->tenant_job }}</strong>
                    </div>
                @endif
                @if($booking->emergency_phone)
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-slate-500">Kontak Darurat:</span>
                        <strong class="text-slate-800 font-bold">{{ $booking->emergency_phone }}</strong>
                    </div>
                @endif
            </div>
        </div>

        {{-- 4. RINCIAN SEWA & PEMBAYARAN --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Rincian Masa Sewa & Biaya</span>
            </h4>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Mulai Sewa:</span>
                    <strong class="text-slate-800 font-bold">
                        {{ $booking->start_date ? $booking->start_date->format('d M Y') : '-' }}
                    </strong>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Berakhir Pada:</span>
                    <strong class="text-slate-800 font-bold">
                        {{ $booking->end_date ? $booking->end_date->format('d M Y') : '-' }}
                    </strong>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Durasi Sewa:</span>
                    <strong class="text-slate-800 font-bold">{{ $booking->duration_months }} Bulan</strong>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Tarif Kamar per Bulan:</span>
                    <span class="text-slate-800 font-semibold">
                        Rp {{ number_format($booking->room?->price ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-700 font-bold">Total Biaya Sewa:</span>
                    <strong class="text-sm font-black text-blue-600">
                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- 5. INFORMASI DATA PENGHUNI --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Data Penghuni Kamar</span>
            </h4>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Penghuni:</span>
                    <div class="flex items-center gap-1.5 font-bold text-slate-800">
                        @if($booking->is_for_other)
                            <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-bold">Orang Lain</span>
                            <span>{{ $booking->tenant_name }}</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-900 text-[10px] font-bold">Diri Sendiri</span>
                            <span>{{ $booking->tenant_name ?: auth()->user()->name }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Nomor Telepon:</span>
                    <strong class="text-slate-800 font-bold">
                        {{ $booking->tenant_phone ?: (auth()->user()->phone ?? '-') }}
                    </strong>
                </div>

                @if($booking->notes)
                    <div class="pt-2">
                        <span class="text-slate-500 block mb-1">Catatan Sewa:</span>
                        <p class="text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-[11px] leading-relaxed">
                            {{ $booking->notes }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 5. KONTAK PEMILIK KOS & AKSI --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Pemilik Kos</span>
            </h4>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                <div class="truncate">
                    <strong class="text-xs font-bold text-slate-900 block truncate">{{ $ownerName }}</strong>
                    <span class="text-[11px] text-slate-500 truncate block">{{ $booking->kost->owner->phone ?? 'Nomor terverifikasi' }}</span>
                </div>
                <span class="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">Pengelola</span>
            </div>

            {{-- Action Buttons: Chat WA & Tepat di Bawahnya Bayar Sekarang / Akhiri Sewa --}}
            <div class="space-y-2 pt-1">
                {{-- 1. Tombol Chat WhatsApp --}}
                <a href="{{ $waLink }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs active:scale-95">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Tanya Pemilik via WhatsApp</span>
                </a>

                {{-- 2. Tombol Akhiri Sewa (Tepat di Bawah Button Tanya Pemilik) --}}
                @if($booking->status === 'active')
                    <form action="{{ route('tenant.bookings.terminate', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri sewa dan check-out dari kamar ini? Seluruh tagihan perpanjangan otomatis berikutnya akan dihentikan.')">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs border border-rose-200 transition shadow-2xs active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Akhiri Sewa & Selesaikan Hunian (Check-Out)</span>
                        </button>
                    </form>
                @endif

                {{-- 3. Tombol Bayar Sekarang (Untuk Booking Approved yang Menunggu Pembayaran) --}}
                @if($booking->status === 'approved')
                    <div class="space-y-2 pt-1">
                        @if(auth()->user()->hasSufficientBalance((float) $booking->total_amount))
                            <form action="{{ route('tenant.bookings.pay-wallet', $booking) }}" method="POST" onsubmit="return confirm('Gunakan saldo dompet sebesar Rp {{ number_format($booking->total_amount, 0, ',', '.') }} untuk melunasi sewa kamar ini?')">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-sm transition active:scale-95 cursor-pointer">
                                    <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span>Bayar via Saldo Dompet (Rp {{ number_format($booking->total_amount, 0, ',', '.') }})</span>
                                </button>
                            </form>
                        @else
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                                <span class="text-slate-600 font-medium">Saldo Dompet: <strong>Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}</strong></span>
                                <a href="{{ route('tenant.wallet.index') }}" class="text-[11px] text-blue-600 font-bold hover:underline flex items-center gap-1">
                                    <span>+ Top Up Saldo</span>
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('tenant.bookings.pay', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-blue-500/25 transition active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Bayar Sekarang (Transfer / E-Wallet - Rp {{ number_format($booking->total_amount, 0, ',', '.') }})</span>
                            </button>
                        </form>
                    </div>
                @endif

                @if($booking->status === 'active')
                    <a href="{{ route('services.index', ['kamar_id' => $booking->id]) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Pesan Layanan Tambahan</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- 7. PANDUAN / LAPOR KENDALA FASILITAS --}}
        @if(in_array($booking->status, ['approved', 'active']))
            <div class="p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <strong class="text-xs font-bold text-blue-950 block">Ada Kerusakan di Kamar?</strong>
                    <p class="text-[11px] text-blue-700 leading-snug">Laporkan langsung ke pemilik kos untuk perbaikan cepat.</p>
                </div>
                <a href="{{ route('tenant.complaints.create') }}" class="shrink-0 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition active:scale-95 shadow-2xs">
                    Lapor Kendala
                </a>
            </div>
        @endif

        {{-- 8. BATALKAN PENGAJUAN (JIKA MASIH PENDING) --}}
        @if($booking->status === 'pending')
            <form action="{{ route('tenant.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan sewa ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition text-center shadow-2xs active:scale-98">
                    Batalkan Pengajuan Sewa
                </button>
            </form>
        @endif

    </div>

</x-tenant-layout>

<x-tenant-layout title="Lacak Pesanan Layanan" :backUrl="route('services.index', ['tab' => 'orders'])">

    @php
        $isCancelled = ($payload['service_order_status'] ?? null) === 'cancelled';
    @endphp

    <div class="space-y-4 max-w-md mx-auto pb-10">

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

        {{-- 1. LIVE TRACKING BANNER (SHOPEEFOOD / GRABFOOD STYLE) --}}
        <div class="relative overflow-hidden rounded-3xl text-white p-5 shadow-lg border border-blue-400/20 space-y-4" style="background: linear-gradient(135deg, #0a2540 0%, #0f3d7c 50%, #1d4ed8 100%);">
            
            {{-- Background decorative circles --}}
            <div class="absolute -top-10 -right-10 w-36 h-36 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-36 h-36 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between gap-2 border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    @if($isCancelled)
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-rose-300">
                            Pesanan Dibatalkan
                        </span>
                    @elseif($isOverdue ?? false)
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
                        </span>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-amber-300">
                            Terlambat Melewati Jadwal
                        </span>
                    @else
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                        </span>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-300">
                            Diproses Mitra
                        </span>
                    @endif
                </div>
                <span class="text-[11px] font-mono font-bold text-blue-200 bg-white/10 px-2 py-0.5 rounded-md">
                    #{{ $transaction->reference_id }}
                </span>
            </div>

            <div>
                @if($isCancelled)
                    <span class="text-[11px] font-semibold text-rose-200 block">Pesanan Dibatalkan &amp; Dana Dikembalikan</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white leading-tight mt-0.5">
                        Dana Refund 100% Selesai
                    </h2>
                    <p class="text-xs text-blue-100/90 mt-1 leading-relaxed">
                        Pesanan ini telah dibatalkan karena mitra melewati jadwal. Dana sebesar Rp {{ number_format($transaction->amount, 0, ',', '.') }} telah dikembalikan ke Saldo Dompet Anda.
                    </p>
                @elseif($isOverdue ?? false)
                    <span class="text-[11px] font-semibold text-amber-200 block">Pesanan Layanan Dikonfirmasi!</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white leading-tight mt-0.5">
                        Mitra Belum Tiba di Kamar Kos
                    </h2>
                    <p class="text-xs text-blue-100/90 mt-1 leading-relaxed">
                        Waktu kedatangan telah melewati jadwal yang Anda pilih. Anda berhak menjadwalkan ulang atau membatalkan dengan refund 100% ke saldo dompet.
                    </p>
                @else
                    <span class="text-[11px] font-semibold text-blue-200 block">Pesanan Layanan Dikonfirmasi!</span>
                    <h2 class="text-xl sm:text-2xl font-black text-white leading-tight mt-0.5">
                        {{ $driver['status_headline'] ?? 'Mitra Sedang Menuju Kos Anda' }}
                    </h2>
                    <p class="text-xs text-blue-100/90 mt-1 leading-relaxed">
                        {{ $driver['status_desc'] ?? 'Mitra kami sedang dalam perjalanan menuju lokasi kamar kos Anda sesuai jadwal.' }}
                    </p>
                @endif
            </div>

            {{-- Estimasi Waktu Tiba (ETA Badge Card) --}}
            <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center justify-between gap-3 shadow-inner">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ ($isOverdue ?? false) ? 'bg-rose-500/20 border-rose-400/30 text-rose-300' : 'bg-emerald-500/20 border-emerald-400/30 text-emerald-300' }} border flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ ($isOverdue ?? false) ? '' : 'animate-pulse' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-blue-200 font-bold block uppercase tracking-wider">Estimasi Tiba</span>
                        <strong class="text-sm sm:text-base font-black text-white">
                            {{ $payload['order_time'] ?? '10:00' }} WIB
                        </strong>
                        <span class="text-[11px] text-emerald-300 font-semibold block">
                            {{ $payload['order_date'] ?? date('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    @if($isCancelled)
                        <span class="px-2 py-1 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-xs">
                            Dibatalkan
                        </span>
                    @elseif($isOverdue ?? false)
                        <span class="px-2 py-1 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-xs">
                            Melewati Jadwal
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full text-[10px] font-black bg-emerald-500 text-white shadow-xs">
                            Tepat Waktu
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2. KOTAK PERLINDUNGAN & SOLUSI KETERLAMBATAN (JIKA MITRA TERLAMBAT) --}}
        @if(($isOverdue ?? false) && !$isCancelled)
            <div x-data="{ rescheduleModalOpen: false, cancelModalOpen: false }" class="bg-rose-50 border border-rose-200/90 rounded-3xl p-4 sm:p-5 space-y-3.5 shadow-2xs">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-rose-500 text-white flex items-center justify-center shrink-0 font-black text-base shadow-xs">
                        !
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs sm:text-sm font-black text-rose-950">
                                Jadwal Kedatangan Terlewat
                            </h4>
                            <span class="text-[10px] font-bold text-rose-700 bg-rose-200/60 px-2 py-0.5 rounded-full">
                                Lewat {{ ($minutesOverdue ?? 0) > 60 ? floor(($minutesOverdue ?? 0) / 60) . ' jam ' . (($minutesOverdue ?? 0) % 60) . ' mnt' : ($minutesOverdue ?? 0) . ' mnt' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-rose-800 mt-1 leading-relaxed">
                            Mitra belum tiba sesuai jadwal ({{ $payload['order_time'] ?? '09:00' }} WIB). Sebagai jaminan kenyamanan penghuni SevenKost, Anda berhak:
                        </p>
                    </div>
                </div>

                {{-- Opsi Aksi --}}
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="rescheduleModalOpen = true" 
                        class="py-2.5 px-3 rounded-xl bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-xs border border-slate-300 shadow-2xs transition active:scale-95 text-center cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span>Jadwalkan Ulang</span>
                    </button>

                    <button 
                        type="button" 
                        @click="cancelModalOpen = true" 
                        class="py-2.5 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 text-center cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <span>Batalkan &amp; Refund</span>
                    </button>
                </div>

                {{-- MODAL RESCHEDULE / JADWALKAN ULANG --}}
                <div 
                    x-show="rescheduleModalOpen" 
                    x-transition.opacity 
                    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 text-slate-800" 
                    style="display: none;"
                >
                    <div 
                        @click.outside="rescheduleModalOpen = false" 
                        class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200 text-left"
                    >
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm sm:text-base font-black text-slate-900">Jadwalkan Ulang Mitra</h3>
                                <p class="text-[11px] text-slate-500">Pilih tanggal dan jam kedatangan baru</p>
                            </div>
                            <button @click="rescheduleModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer text-sm font-bold">✕</button>
                        </div>

                        <form method="POST" action="{{ route('tenant.services.reschedule', $transaction->reference_id) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">Tanggal Kunjungan Baru</label>
                                <input 
                                    type="date" 
                                    name="order_date" 
                                    min="{{ date('Y-m-d') }}" 
                                    value="{{ date('Y-m-d') }}" 
                                    required 
                                    class="w-full text-xs font-medium rounded-xl border-slate-300 p-2.5"
                                >
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700">Jam Kunjungan Baru</label>
                                <select name="order_time" required class="w-full text-xs font-medium rounded-xl border-slate-300 p-2.5 bg-white">
                                    <option value="11:30">Siang (11:30 WIB)</option>
                                    <option value="14:00" selected>Siang (14:00 WIB)</option>
                                    <option value="16:30">Sore (16:30 WIB)</option>
                                    <option value="19:00">Malam (19:00 WIB)</option>
                                    <option value="09:00">Besok Pagi (09:00 WIB)</option>
                                </select>
                            </div>

                            <div class="pt-2 flex gap-2">
                                <button 
                                    type="button" 
                                    @click="rescheduleModalOpen = false" 
                                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs"
                                >
                                    Simpan Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL KONFIRMASI BATAL & REFUND 100% --}}
                <div 
                    x-show="cancelModalOpen" 
                    x-transition.opacity 
                    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 text-slate-800" 
                    style="display: none;"
                >
                    <div 
                        @click.outside="cancelModalOpen = false" 
                        class="bg-white rounded-3xl max-w-sm w-full p-5 space-y-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200 text-center"
                    >
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto text-xl font-black">
                            ✕
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-black text-slate-900">Batalkan Pesanan &amp; Refund 100%?</h3>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Pesanan #{{ $transaction->reference_id }} akan dibatalkan karena mitra melewati jadwal. Dana sebesar <strong class="text-rose-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong> akan langsung dikembalikan ke Saldo Dompet Anda saat ini juga.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('tenant.services.cancel-refund', $transaction->reference_id) }}" class="pt-1 flex gap-2">
                            @csrf
                            <button 
                                type="button" 
                                @click="cancelModalOpen = false" 
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50"
                            >
                                Jangan Sekarang
                            </button>
                            <button 
                                type="submit" 
                                class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs"
                            >
                                Ya, Batalkan &amp; Refund
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @endif

        {{-- 2. SHOPEEFOOD-STYLE STATUS STEPPER --}}
        @php
            $isSelfService = $isSelfService ?? (($payload['fulfillment_type'] ?? 'delivery') === 'self_service');
            $currentStatus = $payload['service_order_status'] ?? ($transaction->status === 'paid' ? 'confirmed' : 'pending');
        @endphp
        <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Status Tahapan Pesanan</span>
                </h3>
                <span class="text-[11px] font-bold text-blue-600">
                    {{ $isSelfService ? 'Metode Mandiri (Self-Service)' : 'Metode Kurir Antar-Jemput' }}
                </span>
            </div>

            <div class="relative pl-6 space-y-5 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                
                {{-- Step 1 --}}
                <div class="relative">
                    <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shadow-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900">Pembayaran Lunas & Terkonfirmasi</h4>
                            <span class="text-[10px] text-slate-400 font-semibold">{{ $transaction->paid_at ? $transaction->paid_at->format('H:i') : 'Selesai' }} WIB</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Pembayaran Rp {{ number_format($transaction->amount, 0, ',', '.') }} berhasil diverifikasi via {{ strtoupper($transaction->payment_channel ?? 'Xendit') }}.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative">
                    <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full {{ in_array($currentStatus, ['confirmed', 'on_the_way', 'in_progress', 'ready_for_pickup', 'completed']) ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px] shadow-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900">Mitra Menerima Pesanan</h4>
                            <span class="text-[10px] text-slate-400 font-semibold">Terkonfirmasi</span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            @if ($isSelfService)
                                {{ $payload['partner_name'] ?? 'Mitra Resmi SevenKost' }} telah menerima pesanan. Silakan antarkan cucian Anda ke alamat outlet mitra.
                            @else
                                {{ $payload['partner_name'] ?? 'Mitra Resmi SevenKost' }} menerima pesanan dan menugaskan petugas resmi.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                @if($isCancelled)
                    <div class="relative">
                        <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shadow-xs font-bold">
                            ✕
                        </div>
                        <div class="bg-rose-50 border border-rose-200/80 rounded-2xl p-2.5 -ml-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black text-rose-950 flex items-center gap-1.5">
                                    <span>Pesanan Dibatalkan &amp; Refund Selesai</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-rose-600 text-white uppercase">Selesai</span>
                                </h4>
                                <span class="text-[10px] font-bold text-rose-700">Refund 100%</span>
                            </div>
                            <p class="text-[11px] text-rose-900 mt-1">
                                Pesanan dibatalkan. Dana sebesar Rp {{ number_format($transaction->amount, 0, ',', '.') }} telah dikembalikan ke Saldo Dompet Anda.
                            </p>
                        </div>
                    </div>
                @elseif ($isSelfService)
                    {{-- Step 3 Versi Self-Service --}}
                    <div class="relative">
                        <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full {{ in_array($currentStatus, ['in_progress', 'ready_for_pickup', 'completed']) ? 'bg-purple-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px] shadow-xs">
                            {{ in_array($currentStatus, ['ready_for_pickup', 'completed']) ? '✓' : '3' }}
                        </div>
                        <div class="{{ $currentStatus === 'in_progress' ? 'bg-purple-50/70 border border-purple-100 rounded-2xl p-2.5 -ml-1' : '' }}">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                                    <span>🧺 Cucian Dicuci / Dikerjakan di Toko</span>
                                    @if ($currentStatus === 'in_progress')
                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-purple-600 text-white uppercase">Proses</span>
                                    @endif
                                </h4>
                                <span class="text-[10px] font-bold {{ $currentStatus === 'in_progress' ? 'text-purple-700' : 'text-slate-400' }}">
                                    {{ in_array($currentStatus, ['ready_for_pickup', 'completed']) ? 'Selesai Cuci' : ($currentStatus === 'in_progress' ? 'Sedang Dicuci' : 'Menunggu Barang') }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-1">
                                Cucian Anda sedang diproses dan dicuci dengan higienis oleh mitra profesional.
                            </p>
                        </div>
                    </div>

                    {{-- Step 4 Versi Self-Service --}}
                    <div class="relative">
                        <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full {{ $currentStatus === 'completed' ? 'bg-emerald-600 text-white font-bold' : ($currentStatus === 'ready_for_pickup' ? 'bg-amber-500 text-white font-bold ring-4 ring-amber-100 animate-pulse' : 'bg-slate-200 text-slate-500') }} flex items-center justify-center text-[10px]">
                            {{ $currentStatus === 'completed' ? '✓' : '4' }}
                        </div>
                        <div class="{{ $currentStatus === 'ready_for_pickup' ? 'bg-amber-50/80 border border-amber-200 rounded-2xl p-2.5 -ml-1' : '' }}">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black {{ $currentStatus === 'ready_for_pickup' ? 'text-amber-950' : 'text-slate-700' }} flex items-center gap-1.5">
                                    <span>✨ Siap Diambil di Outlet Toko</span>
                                    @if ($currentStatus === 'ready_for_pickup')
                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-amber-600 text-white uppercase animate-bounce">Siap Ambil</span>
                                    @endif
                                </h4>
                                <span class="text-[10px] {{ $currentStatus === 'completed' ? 'text-emerald-600 font-bold' : ($currentStatus === 'ready_for_pickup' ? 'text-amber-700 font-bold' : 'text-slate-400 font-semibold') }}">
                                    {{ $currentStatus === 'completed' ? 'Telah Diambil' : ($currentStatus === 'ready_for_pickup' ? 'Siap di Rak' : 'Tahap Akhir') }}
                                </span>
                            </div>
                            <p class="text-[11px] {{ $currentStatus === 'ready_for_pickup' ? 'text-amber-900 font-semibold' : 'text-slate-500' }} mt-0.5">
                                @if ($currentStatus === 'ready_for_pickup')
                                    Cucian telah rapi & siap diambil! Tunjukkan nomor pesanan (#{{ $transaction->reference_id }}) kepada petugas di outlet.
                                @else
                                    Pengambilan cucian bersih langsung di toko mitra oleh penghuni.
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Step 3 Versi Kurir Antar-Jemput --}}
                    <div class="relative">
                        <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shadow-md ring-4 ring-blue-100">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                            </span>
                        </div>
                        <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-2.5 -ml-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black text-blue-900 flex items-center gap-1.5">
                                    <span>🛵 Mitra Menuju Kamar Kos</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-blue-600 text-white uppercase">Aktif</span>
                                </h4>
                                <span class="text-[10px] font-bold text-blue-700">Dalam Perjalanan</span>
                            </div>
                            <p class="text-[11px] text-blue-800/90 mt-1">
                                {{ $driver['name'] }} ({{ $driver['vehicle'] }}) sedang menuju lokasi Anda untuk pengerjaan layanan.
                            </p>
                        </div>
                    </div>

                    {{-- Step 4 Versi Kurir Antar-Jemput --}}
                    <div class="relative {{ ($payload['service_order_status'] ?? '') === 'completed' ? 'opacity-100' : 'opacity-60' }}">
                        <div class="absolute -left-6 top-0.5 w-5 h-5 rounded-full {{ ($payload['service_order_status'] ?? '') === 'completed' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px]">
                            ✓
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-bold text-slate-700">Layanan Selesai & Diterima</h4>
                                <span class="text-[10px] {{ ($payload['service_order_status'] ?? '') === 'completed' ? 'text-emerald-600 font-bold' : 'text-slate-400 font-semibold' }}">
                                    {{ ($payload['service_order_status'] ?? '') === 'completed' ? 'Telah Selesai' : 'Tahap Akhir' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Konfirmasi serah terima pakaian/layanan di kamar kos Anda.
                            </p>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- 3. MITRA / OUTLET HUB CARD --}}
        @if ($isSelfService)
            {{-- Card Outlet Toko Mitra untuk Pesanan Mandiri --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi Outlet / Toko Mitra</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                        <span>🧺 Antar &amp; Ambil Sendiri</span>
                    </span>
                </div>

                <div class="flex items-start gap-3.5">
                    <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-700 text-white font-black text-2xl flex items-center justify-center shadow-md shrink-0">
                        🏪
                    </div>
                    <div class="min-w-0 flex-1 space-y-1">
                        <h4 class="text-sm font-extrabold text-slate-900 truncate">
                            {{ $partnerHub?->business_name ?? ($payload['partner_name'] ?? 'Seven Laundry Hub') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            📍 {{ $partnerHub?->address ?? ($payload['partner_address'] ?? 'Hub Toko Mitra SevenKost') }}
                        </p>
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-xs">
                            <span class="inline-flex items-center gap-1 text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded-lg text-[11px]">
                                ⏰ {{ $partnerHub?->operational_hours ?? 'Buka: 08:00 - 21:00 WIB' }}
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="text-emerald-700 font-bold text-[11px]">
                                Bebas Biaya Ongkir (Rp 0)
                            </span>
                        </div>
                    </div>
                </div>

                @php
                    $partnerPhone = $partnerHub?->phone ?? ($driver['phone'] ?? '081234567890');
                    $cleanPartnerPhone = preg_replace('/[^0-9]/', '', $partnerPhone);
                    if (str_starts_with($cleanPartnerPhone, '0')) {
                        $cleanPartnerPhone = '62' . substr($cleanPartnerPhone, 1);
                    }
                    $hubWaText = "Halo " . ($partnerHub?->business_name ?? 'Mitra SevenKost') . ", saya " . ($payload['occupant_name'] ?? auth()->user()->name) . " terkait pesanan #" . $transaction->reference_id . " (Antar & Ambil Sendiri). Mau konfirmasi lokasi toko dan pengambilan cucian.";
                    $hubWaUrl = "https://wa.me/" . $cleanPartnerPhone . "?text=" . urlencode($hubWaText);
                @endphp
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a 
                        href="{{ $hubWaUrl }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-95"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Chat WhatsApp Toko</span>
                    </a>
                    <a 
                        href="tel:{{ $partnerPhone }}" 
                        class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs flex items-center justify-center gap-1.5 transition active:scale-95 border border-slate-200/80"
                    >
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Telepon Toko</span>
                    </a>
                </div>
            </div>
        @else
            {{-- 3. MITRA / DRIVER CARD (SHOPEEFOOD COURIER STYLE) --}}
            <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mitra Bertugas</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Terverifikasi SevenKost</span>
                    </span>
                </div>

                <div class="flex items-center gap-3.5">
                    <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr {{ $driver['avatar_color'] ?? 'from-blue-600 to-indigo-600' }} text-white font-black text-lg flex items-center justify-center shadow-md shrink-0">
                        {{ $driver['initials'] ?? 'MS' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <h4 class="text-sm font-extrabold text-slate-900 truncate">{{ $driver['name'] }}</h4>
                        </div>
                        <p class="text-xs text-slate-500 font-medium truncate">{{ $driver['role'] }}</p>
                        <div class="flex items-center gap-2 mt-1 text-xs">
                            <span class="inline-flex items-center gap-1 text-amber-500 font-extrabold">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>{{ $driver['rating'] }}</span>
                            </span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-[11px] text-slate-500 font-semibold">{{ $driver['reviews_count'] }}+ pesanan</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-[11px] font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $driver['vehicle'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Action Buttons: WhatsApp & Call --}}
                @php
                    $waText = "Halo Mas " . $driver['name'] . ", saya " . ($payload['occupant_name'] ?? auth()->user()->name) . " penghuni kamar " . ($payload['room_number'] ?? 'kos') . " di " . ($payload['kost_address'] ?? 'Seven Kost') . ". Mau konfirmasi pesanan SevenKost #" . $transaction->reference_id . " (" . ($payload['service_title'] ?? $transaction->title) . ").";
                    $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $driver['phone']) . "?text=" . urlencode($waText);
                @endphp
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a 
                        href="{{ $waUrl }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs flex items-center justify-center gap-1.5 shadow-xs transition active:scale-95"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Chat WhatsApp</span>
                    </a>
                    <a 
                        href="tel:{{ $driver['phone'] }}" 
                        class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs flex items-center justify-center gap-1.5 transition active:scale-95 border border-slate-200/80"
                    >
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>Panggil Mitra</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- 4. RUTE & ALAMAT PENGANTARAN (SHOPEEFOOD DELIVERY ROUTE) --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5 pb-2 border-b border-slate-100">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Rute & Tujuan Kunjungan</span>
            </h3>

            <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:border-l-2 before:border-dashed before:border-slate-300">
                
                {{-- Hub Asal --}}
                <div class="relative">
                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-blue-600 ring-4 ring-blue-100 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Lokasi Hub Mitra</span>
                        <strong class="text-xs font-bold text-slate-900 block mt-0.5">
                            {{ $payload['partner_name'] ?? 'Mitra Resmi SevenKost' }}
                        </strong>
                        <span class="text-[11px] text-blue-600 font-semibold block">Estimasi radius 1.4 km dari lokasi kos</span>
                    </div>
                </div>

                {{-- Kamar Tujuan Penghuni --}}
                <div class="relative">
                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-emerald-600 ring-4 ring-emerald-100 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kamar Kos Tujuan</span>
                        <strong class="text-xs font-black text-slate-900 block mt-0.5">
                            {{ $payload['room_number'] ?? 'Kamar Penghuni' }}
                        </strong>
                        <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">
                            {{ $payload['kost_address'] ?? 'Alamat Kos Penghuni SevenKost' }}
                        </p>
                        <div class="mt-1.5 flex items-center gap-2 text-[11px] text-slate-500 font-medium">
                            <span>Penerima: <strong class="text-slate-800">{{ $payload['occupant_name'] ?? auth()->user()->name }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ $payload['occupant_phone'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Catatan untuk Mitra (Dapat diubah oleh penghuni) --}}
            <div x-data="{ editingNote: false, noteText: @js($payload['order_notes'] ?? '') }" class="mt-3 p-3 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Catatan untuk Mitra</span>
                    <button 
                        type="button" 
                        @click="editingNote = !editingNote" 
                        class="text-[11px] font-bold text-blue-600 hover:text-blue-800 cursor-pointer"
                    >
                        <span x-text="editingNote ? 'Batal' : (noteText ? 'Ubah Catatan' : '+ Tambah Catatan')"></span>
                    </button>
                </div>

                {{-- Mode Tampilan --}}
                <div x-show="!editingNote">
                    @if(!empty($payload['order_notes']))
                        <p class="text-slate-800 font-medium leading-relaxed">
                            "{{ $payload['order_notes'] }}"
                        </p>
                    @else
                        <p class="text-slate-400 italic">
                            Tidak ada catatan khusus untuk mitra.
                        </p>
                    @endif
                </div>

                {{-- Mode Edit --}}
                <form 
                    x-show="editingNote" 
                    method="POST" 
                    action="{{ route('tenant.services.update-notes', $transaction->reference_id) }}" 
                    class="space-y-2"
                >
                    @csrf
                    @method('PATCH')
                    <textarea 
                        name="order_notes" 
                        x-model="noteText" 
                        rows="2" 
                        placeholder="Contoh: Titipkan di meja resepsionis atau hubungi saya saat sampai." 
                        class="w-full text-xs rounded-xl border-slate-300 p-2.5 bg-white text-slate-800 focus:ring-blue-500 focus:border-blue-500"
                    ></textarea>
                    <div class="flex justify-end gap-2">
                        <button 
                            type="button" 
                            @click="editingNote = false" 
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold text-[11px] hover:bg-slate-100"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] shadow-xs"
                        >
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 5. RINCIAN PESANAN & PEMBAYARAN (SHOPEEFOOD RECEIPT STYLE) --}}
        <div class="bg-white rounded-3xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-3">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5 pb-2 border-b border-slate-100">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                <span>Rincian Pembayaran</span>
            </h3>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-start justify-between gap-2 pt-1">
                    <div>
                        <strong class="text-slate-900 font-bold block">{{ $payload['service_title'] ?? $transaction->title }}</strong>
                        <span class="text-[11px] text-blue-600 font-semibold">{{ $payload['package_name'] ?? '-' }}</span>
                        <span class="text-[11px] text-slate-500 block">Qty: {{ $payload['quantity'] ?? 1 }} {{ $payload['package_unit'] ?? '' }}</span>
                    </div>
                    <span class="font-bold text-slate-800 shrink-0">
                        Rp {{ number_format($payload['subtotal'] ?? $transaction->amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Biaya Antar-Jemput:</span>
                    <span class="font-bold text-slate-800">
                        Rp {{ number_format($payload['delivery_fee'] ?? 0, 0, ',', '.') }} ({{ $payload['distance_km'] ?? 1 }} km)
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Metode Pembayaran:</span>
                    <span class="font-bold text-slate-800">{{ strtoupper($transaction->payment_channel ?? 'Xendit') }}</span>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Status Pembayaran:</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                        LUNAS &bull; {{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : 'Terverifikasi' }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-2.5">
                    <strong class="text-sm font-black text-slate-900">Total Pembayaran:</strong>
                    <strong class="text-base font-black text-blue-600">
                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </div>

        {{-- 6. PUSAT BANTUAN & CS --}}
        <div class="bg-amber-50/70 border border-amber-200/80 rounded-3xl p-4 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-200/80 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <strong class="font-bold text-amber-950 block">Ada Kendala dengan Mitra?</strong>
                    <span class="text-[11px] text-amber-800">Tim CS SevenKost siap mendampingi pesanan Anda.</span>
                </div>
            </div>
            <a 
                href="{{ route('tenant.help') }}" 
                class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-100 text-amber-900 font-extrabold text-xs border border-amber-300 shadow-2xs shrink-0 transition active:scale-95"
            >
                Bantuan
            </a>
        </div>

        {{-- 7. TOMBOL NAVIGASI BAWAH --}}
        <div class="space-y-2 pt-1">
            <a 
                href="{{ route('services.index', ['tab' => 'orders']) }}" 
                class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm transition shadow-sm active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Lihat Semua Pesanan Saya</span>
            </a>

            <a 
                href="{{ route('services.index', ['tab' => 'catalog']) }}" 
                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Pesan Layanan Kos Lainnya</span>
            </a>
        </div>

    </div>

</x-tenant-layout>

<x-tenant-layout title="Pesanan Layanan Berhasil" :backUrl="route('services.index')">

    <div class="space-y-4">

        {{-- Top Success Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-5 text-center shadow-lg shadow-emerald-950/20 border border-emerald-400/30 space-y-3" style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #10b981 100%);">
            <div class="w-16 h-16 rounded-full bg-white/20 border-2 border-white/40 flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <div>
                <span class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider block">Pembayaran Berhasil</span>
                <h2 class="text-xl sm:text-2xl font-black text-white leading-tight mt-0.5">
                    Pesanan Layanan Dikonfirmasi!
                </h2>
                <p class="text-xs text-emerald-100 mt-1">
                    Invoice: #{{ $transaction->reference_id }}
                </p>
            </div>

            <div class="p-3 rounded-xl bg-black/20 border border-white/20 backdrop-blur-xs flex items-center justify-between text-xs">
                <span class="text-emerald-100 font-medium">Total Terbayar:</span>
                <strong class="text-base font-black text-white">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        {{-- Rincian Pemesanan & Jadwal Kunjungan --}}
        @php
            $payload = $transaction->payload ?? [];
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Status & Jadwal Kunjungan Mitra</span>
            </h3>

            <div class="space-y-2 text-xs divide-y divide-slate-100">
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-500">Layanan:</span>
                    <strong class="text-slate-900 font-bold">{{ $payload['service_title'] ?? $transaction->title }}</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Paket:</span>
                    <strong class="text-blue-600 font-bold">{{ $payload['package_name'] ?? '-' }}</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Jumlah:</span>
                    <span class="text-slate-800 font-semibold">{{ $payload['quantity'] ?? 1 }} {{ $payload['package_unit'] ?? '' }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Lokasi Kamar Kos:</span>
                    <span class="text-slate-800 font-semibold text-right max-w-[200px] truncate">{{ $payload['room_number'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Alamat:</span>
                    <span class="text-slate-800 font-semibold text-right max-w-[200px] truncate">{{ $payload['kost_address'] ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Waktu Kunjungan:</span>
                    <strong class="text-emerald-700 font-bold">{{ $payload['order_date'] ?? '-' }} jam {{ $payload['order_time'] ?? '-' }} WIB</strong>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Mitra Pelaksana:</span>
                    <span class="text-slate-800 font-semibold">{{ $payload['partner_name'] ?? 'Mitra Resmi SevenKost' }}</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-500">Kontak Penghuni:</span>
                    <span class="text-slate-800 font-semibold">{{ $payload['occupant_name'] ?? auth()->user()->name }} ({{ $payload['occupant_phone'] ?? '-' }})</span>
                </div>
            </div>

            <div class="p-3 bg-blue-50/70 border border-blue-100 rounded-xl text-[11px] text-blue-900 leading-relaxed">
                Mitra kami telah menerima pesanan Anda dan akan tiba langsung ke lokasi kamar kos sesuai jadwal. Mohon pastikan nomor WhatsApp penghuni aktif.
            </div>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="space-y-2 pt-2">
            <a 
                href="{{ route('services.index') }}" 
                class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm transition shadow-sm active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Pesan Layanan Lainnya</span>
            </a>

            <a 
                href="{{ route('tenant.dashboard') }}" 
                class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition active:scale-95"
            >
                <span>Kembali ke Beranda Penghuni</span>
            </a>
        </div>

    </div>

</x-tenant-layout>

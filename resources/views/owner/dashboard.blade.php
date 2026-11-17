<x-owner-layout title="Dashboard">

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

        {{-- Header Card (Refined Sapphire Blue Gradient, Selaras dengan Superadmin) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-white text-xs font-medium mb-3 backdrop-blur-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-xs"></span>
                        Dashboard
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Dashboard
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-1 max-w-2xl leading-relaxed">
                        Pantau tingkat hunian kamar, pendapatan sewa berjalan, persetujuan booking masuk, dan pengingat jatuh tempo sewa dalam satu layar.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('owner.kosts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-medium transition">
                        Kelola Iklan
                    </a>
                    <a href="{{ route('owner.tenants.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-medium transition">
                        Daftar Penyewa
                    </a>
                    <a href="{{ route('owner.promosi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-medium transition">
                        Promosi Kos
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- 1. Kartu Metrik Utama (Geist, Tabular Figures, Clean Border) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Kartu Okupansi Kamar --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Tingkat Okupansi</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-extrabold text-slate-900 tabular-nums">{{ $occupancyRate }}%</span>
                        <span class="text-xs font-semibold text-slate-500">terisi</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ min(100, $occupancyRate) }}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] text-slate-500 mt-2 font-medium">
                        <span class="tabular-nums font-semibold text-slate-700">{{ $occupiedRooms }}/{{ $totalRooms }} unit terisi</span>
                        <span class="tabular-nums text-emerald-600 font-semibold">{{ $availableRooms }} kosong</span>
                    </div>
                </div>
            </div>

            {{-- Kartu Estimasi Pendapatan Sewa Bulanan --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Estimasi Omzet Bulanan</span>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-2xl sm:text-3xl font-extrabold text-blue-600 tabular-nums">
                            Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-medium">Penyewa Aktif Bulan Ini</span>
                    <span class="font-bold text-slate-900 tabular-nums">{{ $activeTenantsCount }} orang</span>
                </div>
            </div>

            {{-- Kartu Pengajuan Butuh Tindakan Segera --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pengajuan Butuh Tindakan</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-extrabold text-slate-900 tabular-nums {{ $pendingBookingsCount > 0 ? 'text-amber-600' : '' }}">
                            {{ $pendingBookingsCount }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500">permohonan baru</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    @if($pendingBookingsCount > 0)
                        <span class="text-amber-600 font-semibold">Perlu persetujuan Anda</span>
                        <a href="#pengajuan-sewa" class="text-blue-600 hover:underline font-bold">Tinjau Sekarang &darr;</a>
                    @else
                        <span class="text-emerald-600 font-semibold">Semua pengajuan telah diproses</span>
                        <span class="text-slate-400">Rapi</span>
                    @endif
                </div>
            </div>

            {{-- Kartu Total Properti & Penghuni Aktif --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Properti Kos</span>
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-3xl font-extrabold text-slate-900 tabular-nums">{{ $kosts->count() }}</span>
                        <span class="text-xs font-semibold text-slate-500">listing aktif</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-medium">Kapasitas Maksimal</span>
                    <span class="font-bold text-slate-900 tabular-nums">{{ $totalRooms }} unit kamar</span>
                </div>
            </div>
        </div>

        {{-- 2. Pengingat Jatuh Tempo Sewa (H-7) --}}
        @if($expiringBookings->isNotEmpty())
            <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                            <h2 class="text-sm sm:text-base font-bold text-amber-950">
                                Pengingat Jatuh Tempo Sewa (Mendekati H-7)
                            </h2>
                        </div>
                        <p class="text-xs text-amber-800/90 mt-0.5">
                            Terdapat {{ $expiringBookings->count() }} penghuni yang masa sewanya akan segera berakhir. Hubungi penyewa untuk follow-up perpanjangan sewa.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    @foreach($expiringBookings as $booking)
                        @php
                            $daysLeft = now()->startOfDay()->diffInDays($booking->end_date->startOfDay(), false);
                            $isOverdue = $daysLeft < 0;
                            $isToday = $daysLeft === 0;

                            $waMessage = "Halo {$booking->tenant_name}, kami dari pengelola {$booking->kost->name}. Masa sewa Anda untuk kamar {$booking->room->name} akan berakhir pada {$booking->end_date->format('d M Y')}. Apakah Anda berencana untuk memperpanjang periode sewa kos?";
                            $waUrl = format_wa_url($booking->tenant_phone ?: ($booking->tenant->phone ?? ''), $waMessage);
                        @endphp
                        <div class="bg-white rounded-xl border border-amber-200/70 p-4 flex flex-col justify-between shadow-2xs">
                            <div class="space-y-1">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="text-xs font-bold text-slate-900 truncate">
                                        {{ $booking->tenant_name ?: ($booking->tenant->name ?? 'Penyewa') }}
                                    </h3>
                                    @if($isOverdue)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 whitespace-nowrap">
                                            Lewat {{ abs($daysLeft) }} Hari
                                        </span>
                                    @elseif($isToday)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 whitespace-nowrap">
                                            Jatuh Tempo Hari Ini
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 whitespace-nowrap tabular-nums">
                                            Sisa {{ $daysLeft }} Hari
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 truncate">
                                    {{ $booking->kost->name }} &bull; {{ $booking->room->name }}
                                </p>
                                <p class="text-[11px] font-medium text-slate-700 tabular-nums">
                                    Selesai: {{ $booking->end_date->format('d M Y') }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                <span class="text-[11px] font-bold text-blue-600 tabular-nums">
                                    Rp {{ number_format($booking->room?->price ?? 0, 0, ',', '.') }}/bln
                                </span>
                                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold transition">
                                    <span>Chat WA</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 3. Pengajuan Sewa Masuk (Menunggu Tindakan Cepat) --}}
        <div id="pengajuan-sewa" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        Pengajuan Sewa Masuk
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Permohonan booking kamar yang membutuhkan persetujuan Anda segera.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('owner.tenants.index', ['tab' => 'pending']) }}" class="text-xs font-bold text-blue-600 hover:underline">
                        Lihat Semua Riwayat &rarr;
                    </a>
                </div>
            </div>

            @if($pendingBookings->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3.5 px-5">Calon Penghuni</th>
                                <th class="py-3.5 px-5">Properti & Kamar</th>
                                <th class="py-3.5 px-5">Rencana Masuk</th>
                                <th class="py-3.5 px-5">Durasi & Tarif</th>
                                <th class="py-3.5 px-5 text-right">Aksi Cepat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pendingBookings->take(5) as $booking)
                                @php
                                    $waMessage = "Halo {$booking->tenant_name}, kami telah menerima pengajuan sewa kamar {$booking->room->name} di {$booking->kost->name} untuk tanggal {$booking->start_date->format('d M Y')}.";
                                    $waUrl = format_wa_url($booking->tenant_phone ?: ($booking->tenant->phone ?? ''), $waMessage);
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3.5 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                                {{ strtoupper(substr($booking->tenant_name ?: 'P', 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong class="text-slate-900 text-xs block font-bold">
                                                    {{ $booking->tenant_name ?: ($booking->tenant->name ?? 'Penyewa') }}
                                                </strong>
                                                <a href="{{ $waUrl }}" target="_blank" class="text-[11px] text-emerald-600 hover:underline inline-flex items-center gap-1 font-mono">
                                                    {{ $booking->tenant_phone ?: ($booking->tenant->phone ?? 'Kontak WA') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-5">
                                        <strong class="text-slate-900 block font-semibold">{{ $booking->kost->name }}</strong>
                                        <span class="text-[11px] text-slate-500">{{ $booking->room->name }} (Sisa {{ $booking->room->available_rooms }} unit)</span>
                                    </td>

                                    <td class="py-3.5 px-5">
                                        <span class="text-slate-800 font-semibold block tabular-nums">{{ $booking->start_date->format('d M Y') }}</span>
                                        @if($booking->end_date)
                                            <span class="text-[10px] text-slate-400 block tabular-nums">s/d {{ $booking->end_date->format('d M Y') }}</span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 px-5">
                                        <strong class="text-blue-600 block font-bold tabular-nums">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                                        <span class="text-[10px] text-slate-400 tabular-nums">{{ $booking->duration_months }} Bulan</span>
                                    </td>

                                    <td class="py-3.5 px-5 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            {{-- Approve Button --}}
                                            <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold shadow-xs transition" title="Setujui Sewa">
                                                    Terima
                                                </button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST" onsubmit="return confirm('Tolak pengajuan sewa ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 text-[11px] font-bold transition" title="Tolak Pengajuan">
                                                    Tolak
                                                </button>
                                            </form>

                                            {{-- WhatsApp Contact --}}
                                            <a href="{{ $waUrl }}" target="_blank" class="p-1.5 rounded-xl border border-slate-200 text-emerald-600 hover:bg-emerald-50 transition" title="Hubungi Calon Penyewa via WhatsApp">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 px-4 text-center">
                    <p class="text-xs text-slate-500 font-medium">
                        Tidak ada pengajuan sewa baru yang menunggu konfirmasi saat ini.
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1">
                        Ketika calon penyewa mengajukan booking kamar dari halaman iklan kos Anda, permohonan akan muncul di sini.
                    </p>
                </div>
            @endif
        </div>

        {{-- 4. Portofolio Listing Properti Kos Saya --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">
                        Listing Properti Kos Saya ({{ $kosts->count() }})
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Kelola kapasitas unit, tarif sewa, dan status tayang iklan kos Anda.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-seven-button :href="route('owner.kosts.index')" variant="outline-primary" size="sm">
                        Kelola Semua Iklan &rarr;
                    </x-seven-button>
                </div>
            </div>

            @if($kosts->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kosts as $kost)
                        @php
                            $kTotal = $kost->rooms->sum('total_rooms');
                            $kAvail = $kost->rooms->sum('available_rooms');
                            $kOccupied = max(0, $kTotal - $kAvail);
                            $kRate = $kTotal > 0 ? round(($kOccupied / $kTotal) * 100, 1) : 0;

                            $coverUrl = $kost->primaryImage 
                                ? asset('storage/' . $kost->primaryImage->file_path)
                                : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80';
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between hover:border-slate-300 transition">
                            <div>
                                {{-- Image & Badges --}}
                                <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                                    <img src="{{ $coverUrl }}" alt="{{ $kost->name }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

                                    {{-- Type badge --}}
                                    <div class="absolute top-3 left-3 flex gap-1.5">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/95 text-slate-800 shadow-2xs capitalize">
                                            Kos {{ $kost->type }}
                                        </span>
                                    </div>

                                    {{-- Status badge --}}
                                    <div class="absolute top-3 right-3">
                                        @if($kost->verification_status === 'verified')
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-600 text-white shadow-2xs">
                                                Terverifikasi
                                            </span>
                                        @elseif($kost->verification_status === 'pending')
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-500 text-white shadow-2xs">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($kost->verification_status === 'rejected')
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-red-600 text-white shadow-2xs">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-600 text-white shadow-2xs">
                                                Draft
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Name on photo --}}
                                    <div class="absolute bottom-3 left-3 right-3 text-white">
                                        <p class="text-[11px] text-white/80 font-medium truncate">
                                            {{ $kost->regency->name ?? '-' }}, {{ $kost->province->name ?? '-' }}
                                        </p>
                                        <h3 class="text-sm font-extrabold text-white truncate">
                                            {{ $kost->name }}
                                        </h3>
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="p-5 space-y-3.5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] text-slate-400 block font-medium">Mulai dari</span>
                                            <span class="text-sm font-extrabold text-blue-600 tabular-nums">
                                                Rp {{ number_format($kost->price_start, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">/{{ $kost->rent_type }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] text-slate-400 block font-medium">Okupansi</span>
                                            <span class="text-xs font-bold text-slate-800 tabular-nums">{{ $kRate }}%</span>
                                        </div>
                                    </div>

                                    {{-- Occupancy bar --}}
                                    <div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ min(100, $kRate) }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] text-slate-500 mt-1.5 font-medium tabular-nums">
                                            <span>{{ $kOccupied }}/{{ $kTotal }} Kamar Terisi</span>
                                            <span class="text-emerald-600 font-semibold">{{ $kAvail }} Unit Siap Huni</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions Footer --}}
                            <div class="px-5 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-2">
                                <a href="{{ route('owner.rooms.index', $kost) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700">
                                    <span>Kelola Kamar ({{ $kost->rooms->count() }})</span>
                                </a>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('owner.kosts.edit', $kost) }}" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-slate-600 hover:bg-slate-200/70 transition">
                                        Edit
                                    </a>
                                    <a href="{{ route('kost.detail', $kost->slug) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-slate-600 hover:bg-slate-200/70 transition">
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-12 text-center">
                    <h3 class="text-sm font-bold text-slate-900">Belum ada properti kos yang didaftarkan</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Mulai pasang iklan kos Anda secara gratis untuk menjangkau calon penyewa di seluruh Indonesia.
                    </p>
                    <div class="mt-4">
                        <x-seven-button :href="route('owner.kosts.create')" variant="primary" size="sm">
                            Pasang Iklan Kos Sekarang
                        </x-seven-button>
                    </div>
                </div>
            @endif
        </div>

    </div>

</x-owner-layout>

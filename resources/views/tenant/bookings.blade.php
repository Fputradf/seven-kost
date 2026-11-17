<x-tenant-layout title="Booking & Kamar Saya">

    <div class="space-y-6 sm:space-y-8">

        {{-- Header Card (Refined Sapphire Blue Gradient) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Riwayat Pengajuan Sewa & Booking Kos
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-700 text-xs font-bold shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Kos Baru</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center gap-3 text-xs sm:text-sm font-semibold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($bookings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bookings as $booking)
                    @php
                        $statusBadge = match($booking->status) {
                            'approved', 'active' => ['bg' => 'bg-emerald-600 text-white shadow-xs', 'label' => 'Disetujui Pemilik'],
                            'rejected' => ['bg' => 'bg-rose-600 text-white shadow-xs', 'label' => 'Ditolak'],
                            'completed' => ['bg' => 'bg-slate-700 text-white shadow-xs', 'label' => 'Selesai'],
                            'cancelled' => ['bg' => 'bg-slate-600 text-white shadow-xs', 'label' => 'Dibatalkan'],
                            default => ['bg' => 'bg-amber-500 text-white shadow-xs', 'label' => 'Menunggu Konfirmasi'],
                        };

                        $roomImage = $booking->kost->primary_image_url;
                    @endphp

                    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            {{-- Foto Kamar / Kos --}}
                            <div class="relative aspect-[16/10] sm:aspect-[16/9] w-full overflow-hidden bg-slate-100">
                                <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="block h-full w-full">
                                    <img src="{{ $roomImage }}" 
                                         alt="{{ $booking->kost->name }}" 
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         loading="lazy">
                                </a>

                                {{-- Gradient Overlay Halus --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/15 to-transparent pointer-events-none"></div>

                                {{-- Floating Badges: Tipe Kamar & Status Booking --}}
                                <div class="absolute top-3 left-3 right-3 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/95 text-slate-800 shadow-sm backdrop-blur-xs">
                                        {{ $booking->room->name ?? 'Kamar Standar' }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-xs {{ $statusBadge['bg'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </div>

                                {{-- Lokasi Ringkas di Atas Foto --}}
                                <div class="absolute bottom-3 left-3 right-3 text-white pointer-events-none flex items-center gap-1.5 text-xs text-slate-200 font-medium drop-shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">{{ $booking->kost->regency->name ?? '' }}, {{ $booking->kost->province->name ?? '' }}</span>
                                </div>
                            </div>

                            {{-- Konten Body --}}
                            <div class="p-5">
                                <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-1 mb-3">
                                    <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="hover:text-blue-600 transition">
                                        {{ $booking->kost->name }}
                                    </a>
                                </h3>

                                {{-- Details Box --}}
                                <div class="bg-slate-50 rounded-xl p-3.5 space-y-2 text-xs text-slate-600 mb-4 border border-slate-100">
                                    @if(!empty($booking->room->room_size))
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">Ukuran Kamar:</span>
                                            <strong class="text-slate-800">{{ $booking->room->room_size }}</strong>
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Rencana Masuk:</span>
                                        <strong class="text-slate-800">{{ $booking->start_date->format('d M Y') }}</strong>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Durasi Sewa:</span>
                                        <strong class="text-slate-800">{{ $booking->duration_months }} Bulan</strong>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                                        <span class="text-slate-500">Total Biaya Sewa:</span>
                                        <strong class="text-blue-600 font-extrabold text-sm">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                                    </div>
                                </div>

                                {{-- Owner Contact Box --}}
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block font-semibold">Pemilik Kos</span>
                                        <strong class="text-slate-900">{{ $booking->kost->owner->name ?? 'Pemilik' }}</strong>
                                    </div>

                                    @php
                                        $rawOwnerPhone = $booking->kost->owner->phone ?? '081234567890';
                                        $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerPhone);
                                        if (str_starts_with($cleanOwnerPhone, '0')) {
                                            $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
                                        }
                                        $waLink = "https://wa.me/{$cleanOwnerPhone}?text=" . urlencode("Halo, saya {$booking->tenant_name} yang mengajukan booking untuk kos {$booking->kost->name}.");
                                    @endphp

                                    <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-xs transition">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        <span>Chat WA</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Aksi --}}
                        <div class="px-5 py-3.5 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1">
                                <span>Lihat Halaman Kos</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('tenant.bookings.cancel', $booking->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan booking ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 cursor-pointer hover:underline">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-xs space-y-3">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                    <svg class="h-8 w-8 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Pengajuan Booking</h3>
                <div class="pt-2">
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition shadow-xs">
                        Cari Kos Sekarang
                    </a>
                </div>
            </div>
        @endif

    </div>

</x-tenant-layout>

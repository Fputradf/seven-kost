<x-tenant-layout title="Dashboard">

    <div class="space-y-6 sm:space-y-8">

        {{-- 1. Hero Header Card (White text judul, tanpa text helper) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Halo, {{ $user->name }}
                    </h1>
                </div>

                {{-- Action Shortcuts --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Kos Baru</span>
                    </a>
                    <a href="{{ route('tenant.complaints.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Lapor Kendala</span>
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

        {{-- 2. Status Kamar Aktif --}}
        @if($activeBooking)
            <div class="relative overflow-hidden rounded-2xl bg-white border border-blue-200/80 p-6 sm:p-7 shadow-xs">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                            <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Hunian Kos Aktif</span>
                        </div>

                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                                <a href="{{ route('kost.detail', $activeBooking->kost->slug) }}" class="hover:text-blue-600 transition">
                                    {{ $activeBooking->kost->name }}
                                </a>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5 mt-1">
                                <svg class="w-4 h-4 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $activeBooking->kost->address ?? ($activeBooking->kost->regency->name . ', ' . $activeBooking->kost->province->name) }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                <span class="text-[11px] text-slate-400 font-medium block">Tipe / Unit Kamar</span>
                                <strong class="text-xs sm:text-sm text-slate-800 font-bold block mt-0.5">{{ $activeBooking->room->name ?? 'Standar' }}</strong>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                <span class="text-[11px] text-slate-400 font-medium block">Tanggal Masuk</span>
                                <strong class="text-xs sm:text-sm text-slate-800 font-bold block mt-0.5">{{ $activeBooking->start_date->format('d M Y') }}</strong>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                <span class="text-[11px] text-slate-400 font-medium block">Jatuh Tempo Sewa</span>
                                <strong class="text-xs sm:text-sm text-slate-800 font-bold block mt-0.5">{{ $activeBooking->end_date->format('d M Y') }}</strong>
                            </div>
                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3">
                                <span class="text-[11px] text-slate-500 font-medium block">Sisa Masa Sewa</span>
                                @php
                                    $daysRemaining = max(0, (int) now()->diffInDays($activeBooking->end_date, false));
                                @endphp
                                <strong class="text-xs sm:text-sm text-slate-900 font-black block mt-0.5">
                                    {{ $daysRemaining }} Hari Lagi
                                </strong>
                            </div>
                        </div>
                    </div>

                    {{-- Action Card with Owner --}}
                    <div class="flex-shrink-0 flex flex-col sm:flex-row lg:flex-col gap-3 justify-center">
                        @php
                            $rawOwnerPhone = $activeBooking->kost->owner->phone ?? '081234567890';
                            $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerPhone);
                            if (str_starts_with($cleanOwnerPhone, '0')) {
                                $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
                            }
                            $waText = urlencode("Halo bapak/ibu {$activeBooking->kost->owner->name}, saya {$user->name} penghuni kamar {$activeBooking->room->name} kos {$activeBooking->kost->name}.");
                            $waLink = "https://wa.me/{$cleanOwnerPhone}?text={$waText}";
                        @endphp

                        <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span>Chat Pemilik via WhatsApp</span>
                        </a>

                        <a href="{{ route('tenant.complaints.create', ['kost_id' => $activeBooking->kost_id]) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Lapor Kerusakan Kamar</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. Ringkasan Metrik (3 Quick Cards tanpa text helper) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            {{-- Card 1: Booking --}}
            <a href="{{ route('tenant.bookings.index') }}" class="group bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md hover:border-slate-300 transition flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Riwayat Booking</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 block tabular-nums">
                        {{ $stats['total_bookings'] }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </a>

            {{-- Card 2: Wishlist --}}
            <a href="{{ route('tenant.wishlists.index') }}" class="group bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md hover:border-slate-300 transition flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kos Favorit</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 block tabular-nums">
                        {{ $stats['total_wishlists'] }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
            </a>

            {{-- Card 3: Keluhan --}}
            <a href="{{ route('tenant.complaints.index') }}" class="group bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs hover:shadow-md hover:border-slate-300 transition flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Keluhan Fasilitas</span>
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 block tabular-nums">
                        {{ $stats['total_complaints'] }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </a>
        </div>

        {{-- 4. Grid Dua Kolom: Booking Terbaru & Keluhan Terakhir --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            {{-- Kolom Kiri: Riwayat Pengajuan Sewa --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base">Booking Sewa Terkini</h3>
                        </div>
                        <a href="{{ route('tenant.bookings.index') }}" class="text-xs font-bold text-slate-700 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    @if($recentBookings->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($recentBookings as $booking)
                                @php
                                    $statusBadge = match($booking->status) {
                                        'approved', 'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Disetujui'],
                                        'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                        'cancelled' => ['bg' => 'bg-slate-100 text-slate-500 border-slate-200', 'label' => 'Batal'],
                                        default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu'],
                                    };
                                @endphp
                                <div class="py-3.5 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                                            <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="hover:text-blue-600 transition">
                                                {{ $booking->kost->name }}
                                            </a>
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $booking->room->name ?? 'Tipe Kamar' }} &bull; {{ $booking->duration_months }} Bulan &bull; Masuk: {{ $booking->start_date->format('d M Y') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold border {{ $statusBadge['bg'] }}">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-xs text-slate-400">Belum ada pengajuan sewa kos.</p>
                            <a href="{{ route('search') }}" class="mt-2 inline-block text-xs font-bold text-slate-700 hover:underline">
                                Mulai cari kos &rarr;
                            </a>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="{{ route('tenant.bookings.index') }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                        <span>Kelola Semua Booking & Sewa</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Tiket Pengaduan & Keluhan Kamar --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base">Keluhan & Perbaikan Fasilitas</h3>
                        </div>
                        <a href="{{ route('tenant.complaints.index') }}" class="text-xs font-bold text-slate-700 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    @if($recentComplaints->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($recentComplaints as $complaint)
                                @php
                                    $complaintBadge = match($complaint->status) {
                                        'resolved' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Selesai Diperbaiki'],
                                        'reviewed' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Sedang Ditangani'],
                                        'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                        default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Respon'],
                                    };
                                @endphp
                                <div class="py-3.5 flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                                            {{ $complaint->reason }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">
                                            {{ $complaint->kost->name ?? 'Kos' }} &bull; {{ $complaint->created_at->diffForHumans() }}
                                        </p>
                                        @if($complaint->admin_notes)
                                            <p class="mt-1 text-[11px] text-slate-700 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                                                <strong>Pemilik:</strong> {{ $complaint->admin_notes }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $complaintBadge['bg'] }}">
                                            {{ $complaintBadge['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-xs text-slate-400">Tidak ada kendala fasilitas saat ini.</p>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 flex items-center gap-2">
                    <a href="{{ route('tenant.complaints.create') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition">
                        <span>+ Lapor Kendala Baru</span>
                    </a>
                    <a href="{{ route('tenant.complaints.index') }}" class="inline-flex items-center justify-center py-2.5 px-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 font-bold text-xs transition">
                        <span>Semua Tiket</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- 5. Bagian Kos Favorit (Wishlist Preview tanpa helper text) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-7 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                <h3 class="font-extrabold text-slate-900 text-lg">Kos Impian & Favorit Saya</h3>
                <a href="{{ route('tenant.wishlists.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 hover:underline self-start sm:self-center">
                    <span>Lihat Semua Favorit ({{ $stats['total_wishlists'] }})</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($favoriteKosts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($favoriteKosts as $kost)
                        <x-kost-card 
                            :title="$kost->name"
                            :slug="$kost->slug"
                            :type="$kost->type"
                            :city="$kost->regency->name ?? ''"
                            :province="$kost->province->name ?? ''"
                            :price="$kost->price_start"
                            :rentType="$kost->rent_type"
                            :image="$kost->primaryImage?->file_path ? asset('storage/' . $kost->primaryImage->file_path) : null"
                            :isVerified="$kost->verification_status === 'verified'"
                            :isFeatured="(bool) $kost->is_featured"
                            :availableRooms="$kost->rooms->sum('available_rooms')"
                            :facilities="$kost->facilities->pluck('name')->toArray()"
                        />
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Belum Ada Kos Favorit</h4>
                    <a href="{{ route('search') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition shadow-xs">
                        <span>Eksplorasi Kos Sekarang</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- 6. Komunitas & Marketplace Anak Kos Banner --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white relative overflow-hidden shadow-md shadow-navy-950/20" style="background: linear-gradient(135deg, #091e3a 0%, #1e3a8a 60%, #3b82f6 100%);">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                        Punya Barang Kos yang Mau Dijual atau Dioper?
                    </h3>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('marketplace.index') }}" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white font-bold text-xs transition">
                        <span>Lihat Marketplace</span>
                    </a>
                    <a href="{{ route('marketplace.create') }}" class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs shadow-xs transition">
                        <span>+ Jual Barang Kos</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>

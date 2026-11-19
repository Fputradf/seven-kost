<x-tenant-layout title="Booking & Kamar Saya">

    <div class="space-y-4">

        {{-- Top Info Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div>
                <span class="text-xs font-bold text-sky-200 uppercase tracking-wider block">Riwayat Sewa</span>
                <h2 class="text-base sm:text-lg font-black text-white !text-white leading-tight" style="color: #ffffff !important;">
                    Booking & Kamar Kos Saya
                </h2>
                <p class="text-xs text-white/95 mt-0.5">Total {{ $bookings->count() }} pengajuan sewa kos</p>
            </div>
            <a href="{{ route('tenant.search') }}" class="shrink-0 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition border border-white/30 active:scale-95 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Sewa Baru</span>
            </a>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800 flex items-center gap-2.5 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($bookings->count() > 0)
            <div class="space-y-3">
                @foreach($bookings as $booking)
                    @php
                        $statusBadge = match($booking->status) {
                            'approved' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Disetujui - Siap Bayar'],
                            'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Hunian Aktif'],
                            'rejected' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Ditolak'],
                            'completed' => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => 'Selesai'],
                            'cancelled' => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Dibatalkan'],
                            default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Konfirmasi'],
                        };
                        $roomImage = $booking->kost->primary_image_url;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs space-y-3 p-3.5 transition hover:border-blue-300">
                        {{-- Image & Main Info Row --}}
                        <div class="flex gap-3">
                            <a href="{{ route('tenant.bookings.show', $booking) }}" class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 relative group block">
                                <img src="{{ $roomImage }}" alt="{{ $booking->kost->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <span class="absolute bottom-1 left-1 right-1 text-[9px] font-bold text-center text-white bg-black/60 rounded px-1 py-0.5 truncate">
                                    {{ $booking->duration_months }} Bulan
                                </span>
                            </a>

                            <div class="flex-1 truncate">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $statusBadge['bg'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-black text-slate-900 truncate">
                                    <a href="{{ route('tenant.bookings.show', $booking) }}" class="hover:text-blue-600 transition">
                                        {{ $booking->kost->name }}
                                    </a>
                                </h3>

                                <p class="text-xs sm:text-sm text-slate-600 font-medium truncate mt-0.5">
                                    {{ $booking->room->name ?? 'Standard' }} &bull; {{ $booking->kost->regency?->name ?? 'Kota' }}
                                </p>

                                <div class="mt-1.5">
                                    <strong class="text-sm sm:text-base font-black text-blue-600">
                                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        {{-- Details & Occupant Meta --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                            {{-- Info Penghuni Kamar (Orang Lain / Diri Sendiri) --}}
                            <div class="flex items-center justify-between text-xs bg-slate-50 p-2.5 rounded-xl">
                                <span class="text-slate-600 font-medium">Penghuni Kamar:</span>
                                @if($booking->is_for_other)
                                    <div class="flex items-center gap-1.5 font-bold text-slate-900">
                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-bold border border-amber-200">Orang Lain</span>
                                        <span class="truncate">{{ $booking->tenant_name }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 font-bold text-slate-900">
                                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-900 text-[10px] font-bold border border-blue-200">Diri Sendiri</span>
                                        <span class="truncate">{{ $booking->tenant_name ?: auth()->user()->name }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Rental Period --}}
                            <div class="flex items-center justify-between text-xs px-1">
                                <span class="text-slate-600 font-medium">Masa Sewa:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $booking->start_date ? $booking->start_date->format('d M Y') : '-' }} s/d {{ $booking->end_date ? $booking->end_date->format('d M Y') : '-' }}
                                </span>
                            </div>

                            {{-- Notice Tagihan Perpanjangan / Denda --}}
                            @if($booking->status === 'active' && $booking->isDueForRenewal())
                                @if($booking->isOverdue())
                                    <div class="p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[11px] flex items-center justify-between gap-2 mt-1">
                                        <div class="flex items-center gap-1.5 font-bold truncate">
                                            <span class="w-2 h-2 rounded-full bg-rose-600 shrink-0 animate-ping"></span>
                                            <span class="truncate">Sewa Terlambat {{ $booking->getOverdueDays() }} Hari (Denda Aktif)</span>
                                        </div>
                                        <a href="{{ route('tenant.bookings.show', $booking) }}" class="shrink-0 px-2 py-0.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-black text-[10px] transition">
                                            Perpanjang
                                        </a>
                                    </div>
                                @else
                                    <div class="p-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] flex items-center justify-between gap-2 mt-1">
                                        <div class="flex items-center gap-1.5 font-bold truncate">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                            <span class="truncate">Tagihan Perpanjangan Aktif (H-{{ max(0, (int) now()->startOfDay()->diffInDays($booking->end_date->startOfDay(), false)) }})</span>
                                        </div>
                                        <a href="{{ route('tenant.bookings.show', $booking) }}" class="shrink-0 px-2 py-0.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] transition">
                                            Bayar
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                            <a href="{{ route('tenant.bookings.show', $booking) }}" class="flex-1 py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs text-center transition flex items-center justify-center gap-1.5 border border-blue-200 shadow-2xs active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Detail Kamar</span>
                            </a>

                            @if($booking->status === 'approved')
                                <form action="{{ route('tenant.bookings.pay', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs text-center transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Bayar Sekarang</span>
                                    </button>
                                </form>
                            @elseif($booking->status === 'active' && $booking->isDueForRenewal())
                                <a href="{{ route('tenant.bookings.show', $booking) }}" class="flex-1 py-2 px-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs text-center transition flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Perpanjang Sewa</span>
                                </a>
                            @elseif($booking->status === 'active')
                                @php
                                    $rawOwnerPhone = $booking->kost->owner->phone ?? '081234567890';
                                    $cleanOwnerPhone = preg_replace('/[^0-9]/', '', $rawOwnerPhone);
                                    if (str_starts_with($cleanOwnerPhone, '0')) {
                                        $cleanOwnerPhone = '62' . substr($cleanOwnerPhone, 1);
                                    }
                                    $ownerName = $booking->kost->owner->name ?? 'Pemilik';
                                    $occName = $booking->tenant_name ?: (auth()->user()?->name ?? 'Penyewa');
                                    $kostName = $booking->kost->name ?? 'Kos';
                                    $waText = urlencode("Halo bapak/ibu {$ownerName}, saya {$occName} penyewa kos {$kostName}.");
                                    $waLink = "https://wa.me/{$cleanOwnerPhone}?text={$waText}";
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="flex-1 py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs text-center transition flex items-center justify-center gap-1.5 shadow-2xs active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span>Chat Pemilik</span>
                                </a>
                            @endif

                            @if($booking->status === 'pending')
                                <form action="{{ route('tenant.bookings.cancel', $booking) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan sewa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition text-center active:scale-95">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-white border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Belum Ada Pengajuan Booking</h3>
                    <p class="text-xs text-slate-500 mt-1">Cari kos favorit Anda sekarang dan nikmati proses sewa yang cepat & aman.</p>
                </div>
                <a href="{{ route('tenant.search') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                    <span>Mulai Cari Kos</span>
                </a>
            </div>
        @endif

    </div>

</x-tenant-layout>

<x-seven-layout title="Booking Saya - Seven Kost">

    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-2">
                Panel Penghuni
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Riwayat Pengajuan Sewa & Booking Kos
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Pantau proses konfirmasi dari pemilik kos dan kelola informasi kontak hunian Anda.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center gap-2 text-xs font-semibold">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($bookings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-xs text-slate-400 font-medium block">
                                        {{ $booking->kost->regency->name ?? '' }}, {{ $booking->kost->province->name ?? '' }}
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-1 mt-0.5">
                                        <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="hover:text-blue-600 transition">
                                            {{ $booking->kost->name }}
                                        </a>
                                    </h3>
                                </div>

                                @php
                                    $statusBadge = match($booking->status) {
                                        'approved', 'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Disetujui Pemilik'],
                                        'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                        'completed' => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Selesai'],
                                        'cancelled' => ['bg' => 'bg-slate-100 text-slate-400 border-slate-200', 'label' => 'Dibatalkan'],
                                        default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Konfirmasi'],
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusBadge['bg'] }} whitespace-nowrap">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>

                            {{-- Details box --}}
                            <div class="bg-slate-50 rounded-2xl p-3.5 my-3 space-y-2 text-xs text-slate-600">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Tipe Kamar:</span>
                                    <strong class="text-slate-800">{{ $booking->room->name }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Rencana Masuk:</span>
                                    <strong class="text-slate-800">{{ $booking->start_date->format('d M Y') }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Durasi:</span>
                                    <strong class="text-slate-800">{{ $booking->duration_months }} Bulan</strong>
                                </div>
                                <div class="flex justify-between pt-1.5 border-t border-slate-200">
                                    <span class="text-slate-400">Total Biaya Sewa:</span>
                                    <strong class="text-blue-600 font-extrabold text-sm">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            {{-- Owner Contact Box --}}
                            <div class="p-3 rounded-2xl bg-blue-50/50 border border-blue-100 flex items-center justify-between text-xs mb-4">
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

                                <a href="{{ $waLink }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition">
                                    <span>Chat WA</span>
                                </a>
                            </div>
                        </div>

                        {{-- Cancel action if pending --}}
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('kost.detail', $booking->kost->slug) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                Lihat Halaman Kos
                            </a>

                            @if($booking->status === 'pending')
                                <form action="{{ route('tenant.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline">
                                        Batalkan Booking
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-md mx-auto shadow-xs space-y-4">
                <div class="h-20 w-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="h-10 w-10 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Pengajuan Booking</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda belum mengajukan sewa pada properti kos mana pun. Cari kos impian Anda dan ajukan booking dengan mudah.
                </p>
                <div class="pt-2">
                    <x-seven-button href="{{ url('/cari') }}" variant="primary" size="sm">
                        Cari Kos Sekarang
                    </x-seven-button>
                </div>
            </div>
        @endif
    </div>

</x-seven-layout>

<x-seven-layout title="Daftar Penyewa & Pengajuan Sewa - Seven Kost">

    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-2">
                        Panel Pemilik Kos
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Daftar Penyewa & Pengajuan Sewa
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Tinjau permohonan booking calon penghuni, kelola status sewa, dan kontrak hunian.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <x-seven-button :href="route('owner.kosts.index')" variant="outline-white" size="sm">
                        Kelola Iklan Kos
                    </x-seven-button>
                </div>
            </div>
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

        {{-- Table Container --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Pengajuan Sewa Masuk ({{ $bookings->count() }})</h2>
                    <p class="text-xs text-slate-500">Konfirmasi calon penghuni yang mengajukan sewa kamar.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-4 px-6">Calon Penghuni</th>
                            <th class="py-4 px-6">Properti Kos & Kamar</th>
                            <th class="py-4 px-6">Rencana Masuk</th>
                            <th class="py-4 px-6">Durasi & Tarif</th>
                            <th class="py-4 px-6">Status Sewa</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($booking->tenant->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 text-xs block">{{ $booking->tenant_name ?: $booking->tenant->name }}</strong>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->tenant_phone ?: ($booking->tenant->phone ?? '')) }}" target="_blank" class="text-[11px] text-emerald-600 hover:underline flex items-center gap-1 font-mono">
                                                <span>📱 {{ $booking->tenant_phone ?: ($booking->tenant->phone ?? 'Belum ada no') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    <strong class="text-slate-900 block font-semibold">{{ $booking->kost->name }}</strong>
                                    <span class="text-[11px] text-slate-400">{{ $booking->room->name }}</span>
                                </td>

                                <td class="py-4 px-6">
                                    <span class="text-slate-800 font-medium block">{{ $booking->start_date->format('d M Y') }}</span>
                                    @if($booking->end_date)
                                        <span class="text-[10px] text-slate-400 block">s/d {{ $booking->end_date->format('d M Y') }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <strong class="text-blue-600 block font-extrabold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                                    <span class="text-[10px] text-slate-400">{{ $booking->duration_months }} Bulan</span>
                                </td>

                                <td class="py-4 px-6">
                                    @php
                                        $statusConfig = match($booking->status) {
                                            'approved', 'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Disetujui / Aktif'],
                                            'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                            'completed' => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Selesai'],
                                            default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Konfirmasi'],
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusConfig['bg'] }} whitespace-nowrap">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold shadow-sm transition">
                                                    Terima
                                                </button>
                                            </form>

                                            <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 text-[11px] font-bold transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        @elseif($booking->status === 'approved')
                                            <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-600 text-[11px] font-semibold transition">
                                                    Selesai Sewa
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-400 text-[11px]">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                    Belum ada pengajuan sewa dari calon penghuni.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-seven-layout>

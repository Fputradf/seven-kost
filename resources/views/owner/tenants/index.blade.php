<x-owner-layout title="Daftar Penyewa & Booking">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6"
        x-data="{
            rejectModalOpen: false,
            rejectActionUrl: '',
            rejectTenantName: '',
            openRejectModal(url, tenantName) {
                this.rejectActionUrl = url;
                this.rejectTenantName = tenantName;
                this.rejectModalOpen = true;
            }
        }"
    >

        {{-- 1. Header Card (Kompak & Berkelas) --}}
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-7 text-white shadow-lg shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs text-blue-200 font-medium">
                        <a href="{{ route('owner.dashboard') }}" class="hover:underline hover:text-white transition">Dashboard</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Penghuni & Sewa</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Daftar Penyewa & Pengajuan Booking
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 font-medium max-w-xl leading-relaxed">
                        Kelola data penghuni kos, konfirmasi permohonan sewa masuk, dan hubungi penyewa via WhatsApp.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 self-start sm:self-center">
                    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-semibold transition">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('owner.kosts.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-700 text-xs font-bold shadow-xs transition">
                        <span>Kelola Iklan Kos</span>
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

        {{-- 2. Ringkasan Metrik Kompak (4 Quick Stat Cards) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengajuan</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5 block tabular-nums">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Menunggu Konfirmasi</span>
                    @if(($counts['pending'] ?? 0) > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </div>
                <span class="text-xl sm:text-2xl font-black text-amber-600 mt-0.5 block tabular-nums">
                    {{ $counts['pending'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Sedang Menyewa</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-600 mt-0.5 block tabular-nums">
                    {{ $counts['active'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Riwayat Selesai</span>
                <span class="text-xl sm:text-2xl font-black text-slate-700 mt-0.5 block tabular-nums">
                    {{ $counts['history'] ?? 0 }}
                </span>
            </div>
        </div>

        {{-- 3. Segmented Filter Tabs --}}
        <div class="flex items-center justify-start overflow-x-auto pb-1 scrollbar-none">
            <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200/80 shadow-xs gap-1">
                <a 
                    href="{{ route('owner.tenants.index', ['tab' => 'all']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? 'all') === 'all' ? 'bg-white text-blue-600 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Semua</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? 'all') === 'all' ? 'bg-blue-50 text-blue-700' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['all'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.tenants.index', ['tab' => 'pending']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Menunggu Konfirmasi</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'pending' ? 'bg-white text-amber-700' : 'bg-amber-100 text-amber-800' }}">
                        {{ $counts['pending'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.tenants.index', ['tab' => 'active']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Sedang Menyewa</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'active' ? 'bg-white text-emerald-700' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $counts['active'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.tenants.index', ['tab' => 'history']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'history' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Riwayat Selesai</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'history' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['history'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        {{-- 4. Main Table Card (Kompak 3 Kolom - Bebas Melar ke Samping) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">
                        Daftar Pengajuan Sewa & Penghuni
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Ringkasan permohonan booking dan penghuni aktif pada properti kos Anda.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-600 border-collapse">
                    <thead class="bg-slate-50/90 text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200/70">
                        <tr>
                            <th class="py-3.5 px-5 w-5/12">Penyewa &amp; Properti Kos</th>
                            <th class="py-3.5 px-5 w-4/12">Periode &amp; Tarif Sewa</th>
                            <th class="py-3.5 px-5 w-3/12 text-right">Status &amp; Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            @php
                                $statusConfig = match($booking->status) {
                                    'approved', 'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Sedang Menyewa'],
                                    'rejected' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                    'completed' => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400', 'label' => 'Selesai Sewa'],
                                    'cancelled' => ['bg' => 'bg-slate-100 text-slate-500 border-slate-200', 'dot' => 'bg-slate-400', 'label' => 'Dibatalkan'],
                                    default => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500 animate-ping', 'label' => 'Menunggu Konfirmasi'],
                                };

                                $waMessage = match($booking->status) {
                                    'approved', 'active' => "Halo {$booking->tenant_name}, kami dari pengelola {$booking->kost->name}. Kami ingin mengonfirmasi terkait sewa kamar {$booking->room->name}.",
                                    'rejected' => "Halo {$booking->tenant_name}, mohon maaf pengajuan sewa kamar di {$booking->kost->name} belum dapat kami setujui saat ini.",
                                    default => "Halo {$booking->tenant_name}, kami telah menerima permohonan booking sewa Anda untuk kamar {$booking->room->name} di {$booking->kost->name}."
                                };
                                $waUrl = format_wa_url($booking->tenant_phone ?: ($booking->tenant->phone ?? ''), $waMessage);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                {{-- Kolom 1: Penyewa & Properti Kos --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-xs sm:text-sm flex items-center justify-center flex-shrink-0 shadow-xs mt-0.5">
                                            {{ strtoupper(substr($booking->tenant_name ?: 'P', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 space-y-1">
                                            <strong class="text-sm font-extrabold text-slate-900 block leading-tight truncate">
                                                {{ $booking->tenant_name ?: ($booking->tenant->name ?? 'Penyewa') }}
                                            </strong>
                                            <div class="text-xs text-slate-600 flex items-center gap-1.5 flex-wrap">
                                                <span class="font-semibold text-slate-800">{{ $booking->kost->name }}</span>
                                                <span class="text-slate-300">•</span>
                                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[11px]">Kamar {{ $booking->room->name }}</span>
                                            </div>
                                            <a 
                                                href="{{ $waUrl }}" 
                                                target="_blank" 
                                                class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1 pt-0.5"
                                            >
                                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.587 1.961.954 3.031.954 3.183 0 5.768-2.587 5.769-5.766.001-3.187-2.575-5.77-5.76-5.77zm0-2.172c4.398 0 7.969 3.568 7.969 7.942 0 4.375-3.571 7.941-7.969 7.941-1.391 0-2.709-.364-3.856-1.002l-4.529 1.187 1.21-4.417c-.742-1.229-1.174-2.656-1.174-4.184 0-4.375 3.571-7.942 7.969-7.942z"/></svg>
                                                <span>{{ $booking->tenant_phone ?: ($booking->tenant->phone ?? 'Belum ada kontak') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 2: Periode & Tarif Sewa --}}
                                <td class="py-4 px-5">
                                    <div class="space-y-1">
                                        <div class="flex items-baseline gap-1.5 flex-wrap">
                                            <strong class="text-sm sm:text-base font-black text-blue-600 tabular-nums">
                                                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                            </strong>
                                            <span class="text-xs font-semibold text-slate-500">
                                                ({{ $booking->duration_months }} Bulan)
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-500 space-y-0.5 tabular-nums">
                                            <span class="block">Masuk: <strong class="text-slate-800 font-semibold">{{ $booking->start_date->format('d M Y') }}</strong></span>
                                            @if($booking->end_date)
                                                <span class="block text-slate-400">s/d {{ $booking->end_date->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 3: Status & Aksi --}}
                                <td class="py-4 px-5 text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $statusConfig['bg'] }} whitespace-nowrap shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                            <span>{{ $statusConfig['label'] }}</span>
                                        </span>

                                        <div class="inline-flex items-center gap-1.5">
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button 
                                                        type="submit" 
                                                        class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold shadow-sm shadow-emerald-600/25 transition cursor-pointer"
                                                    >
                                                        Terima
                                                    </button>
                                                </form>

                                                <button 
                                                    type="button" 
                                                    @click="openRejectModal('{{ route('owner.bookings.updateStatus', $booking) }}', '{{ $booking->tenant_name ?: 'Penyewa' }}')"
                                                    class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 text-slate-700 text-xs font-bold transition cursor-pointer"
                                                >
                                                    Tolak
                                                </button>
                                            @elseif(in_array($booking->status, ['approved', 'active']))
                                                <form action="{{ route('owner.bookings.updateStatus', $booking) }}" method="POST" onsubmit="return confirm('Tandai sewa kamar ini telah selesai? Unit kamar akan otomatis bertambah ke status tersedia.')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button 
                                                        type="submit" 
                                                        class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-800 text-xs font-bold transition cursor-pointer"
                                                    >
                                                        Selesai Sewa
                                                    </button>
                                                </form>
                                            @endif

                                            <a 
                                                href="{{ $waUrl }}" 
                                                target="_blank" 
                                                class="p-2 rounded-xl border border-slate-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition cursor-pointer shadow-2xs" 
                                                title="Chat WhatsApp Penyewa"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.587 1.961.954 3.031.954 3.183 0 5.768-2.587 5.769-5.766.001-3.187-2.575-5.77-5.76-5.77zm0-2.172c4.398 0 7.969 3.568 7.969 7.942 0 4.375-3.571 7.941-7.969 7.941-1.391 0-2.709-.364-3.856-1.002l-4.529 1.187 1.21-4.417c-.742-1.229-1.174-2.656-1.174-4.184 0-4.375 3.571-7.942 7.969-7.942z"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                                            📋
                                        </div>
                                        <h3 class="text-sm font-extrabold text-slate-800">Tidak ada pengajuan sewa</h3>
                                        <p class="text-xs text-slate-500 leading-relaxed font-normal">
                                            Belum ada data pengajuan sewa atau penghuni pada kategori filter ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Modal Tolak Pengajuan Sewa (Kompak) --}}
        <div x-show="rejectModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
            <div @click.outside="rejectModalOpen = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-slate-800 border border-slate-200">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Konfirmasi Penolakan</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Tolak Pengajuan Booking Sewa</h3>
                    </div>
                    <button @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="rejectActionUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="rejected">

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Anda akan menolak permohonan sewa dari <strong class="text-slate-900 font-bold" x-text="rejectTenantName"></strong>. Berikan catatan alasan penolakan:
                    </p>

                    <div class="space-y-1.5">
                        <label for="reject_notes" class="block text-xs font-bold text-slate-700">Catatan Alasan Penolakan <span class="text-rose-500">*</span></label>
                        <textarea 
                            id="reject_notes"
                            name="notes" 
                            rows="3" 
                            required
                            placeholder="Contoh: Kamar sedang direnovasi / Sudah terisi penyewa offline" 
                            class="w-full text-xs rounded-xl border-slate-200 bg-white p-3 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-rose-500 focus:ring-rose-500"
                        ></textarea>
                    </div>

                    <div class="pt-2 flex items-center gap-2.5">
                        <button type="button" @click="rejectModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-extrabold shadow-md shadow-rose-600/25 transition cursor-pointer">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-owner-layout>

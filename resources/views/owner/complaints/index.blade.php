<x-owner-layout title="Daftar Keluhan & Laporan">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6"
        x-data="{
            actionModalOpen: false,
            actionUrl: '',
            complaintReason: '',
            currentStatus: 'reviewed',
            adminNotes: '',
            openActionModal(url, reason, status, notes) {
                this.actionUrl = url;
                this.complaintReason = reason;
                this.currentStatus = status || 'reviewed';
                this.adminNotes = notes || '';
                this.actionModalOpen = true;
            }
        }"
    >

        {{-- 1. Header Card (Kompak & Berkelas Sapphire Blue Gradient) --}}
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-7 text-white shadow-lg shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs text-blue-200 font-medium">
                        <a href="{{ route('owner.dashboard') }}" class="hover:underline hover:text-white transition">Dashboard</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Penghuni & Sewa</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Daftar Keluhan & Laporan Kos
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 font-medium max-w-xl leading-relaxed">
                        Pantau laporan keluhan dari penghuni, catat penanganan kendala fasilitas, dan hubungi pelapor via WhatsApp.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 self-start sm:self-center">
                    <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-semibold transition">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('owner.tenants.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-700 text-xs font-bold shadow-xs transition">
                        <span>Daftar Penyewa</span>
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
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Keluhan</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5 block tabular-nums">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Menunggu Tindakan</span>
                    @if(($counts['pending'] ?? 0) > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    @endif
                </div>
                <span class="text-xl sm:text-2xl font-black text-amber-600 mt-0.5 block tabular-nums">
                    {{ $counts['pending'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider block">Sedang Ditangani</span>
                <span class="text-xl sm:text-2xl font-black text-blue-600 mt-0.5 block tabular-nums">
                    {{ $counts['reviewed'] ?? 0 }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs">
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Selesai Ditangani</span>
                <span class="text-xl sm:text-2xl font-black text-emerald-600 mt-0.5 block tabular-nums">
                    {{ $counts['resolved'] ?? 0 }}
                </span>
            </div>
        </div>

        {{-- 3. Segmented Filter Tabs --}}
        <div class="flex items-center justify-start overflow-x-auto pb-1 scrollbar-none">
            <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200/80 shadow-xs gap-1">
                <a 
                    href="{{ route('owner.complaints.index', ['tab' => 'all']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? 'all') === 'all' ? 'bg-white text-blue-600 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Semua</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? 'all') === 'all' ? 'bg-blue-50 text-blue-700' : 'bg-slate-200 text-slate-700' }}">
                        {{ $counts['all'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.complaints.index', ['tab' => 'pending']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Menunggu Tindakan</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'pending' ? 'bg-white text-amber-700' : 'bg-amber-100 text-amber-800' }}">
                        {{ $counts['pending'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.complaints.index', ['tab' => 'reviewed']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'reviewed' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Sedang Ditangani</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'reviewed' ? 'bg-white text-blue-700' : 'bg-blue-100 text-blue-800' }}">
                        {{ $counts['reviewed'] ?? 0 }}
                    </span>
                </a>

                <a 
                    href="{{ route('owner.complaints.index', ['tab' => 'resolved']) }}" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition flex items-center gap-1.5 {{ ($tab ?? '') === 'resolved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <span>Selesai</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black {{ ($tab ?? '') === 'resolved' ? 'bg-white text-emerald-700' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $counts['resolved'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        {{-- 4. Main Table Card (Kompak 3 Kolom - Bebas Melar ke Samping) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">
                        Laporan Keluhan Properti
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Daftar keluhan fasilitas, kenyamanan, atau kendala lain dari penghuni dan pengunjung kos.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm text-slate-600 border-collapse">
                    <thead class="bg-slate-50/90 text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200/70">
                        <tr>
                            <th class="py-3.5 px-5 w-5/12">Pelapor &amp; Properti Kos</th>
                            <th class="py-3.5 px-5 w-4/12">Rincian Keluhan</th>
                            <th class="py-3.5 px-5 w-3/12 text-right">Status &amp; Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($complaints as $complaint)
                            @php
                                $statusConfig = match($complaint->status) {
                                    'reviewed' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-600', 'label' => 'Sedang Ditangani'],
                                    'resolved' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-600', 'label' => 'Selesai'],
                                    'rejected' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                    default => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500 animate-ping', 'label' => 'Menunggu Tindakan'],
                                };

                                $reporterName = $complaint->reporter?->name ?? 'Anonim / Penghuni';
                                $reporterPhone = $complaint->reporter?->phone;
                                $waMessage = "Halo {$reporterName}, kami dari pengelola {$complaint->kost->name}. Terkait keluhan mengenai '{$complaint->reason}', kami ingin menindaklanjutinya bersama Anda.";
                                $waUrl = $reporterPhone ? format_wa_url($reporterPhone, $waMessage) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                {{-- Kolom 1: Pelapor & Properti Kos --}}
                                <td class="py-4 px-5">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-xs sm:text-sm flex items-center justify-center flex-shrink-0 shadow-xs mt-0.5">
                                            {{ strtoupper(substr($reporterName, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 space-y-1">
                                            <strong class="text-sm font-extrabold text-slate-900 block leading-tight truncate">
                                                {{ $reporterName }}
                                            </strong>
                                            <div class="text-xs text-slate-600 flex items-center gap-1.5 flex-wrap">
                                                <span class="font-semibold text-slate-800">{{ $complaint->kost->name }}</span>
                                            </div>
                                            @if($waUrl)
                                                <a 
                                                    href="{{ $waUrl }}" 
                                                    target="_blank" 
                                                    class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1 pt-0.5"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.587 1.961.954 3.031.954 3.183 0 5.768-2.587 5.769-5.766.001-3.187-2.575-5.77-5.76-5.77zm0-2.172c4.398 0 7.969 3.568 7.969 7.942 0 4.375-3.571 7.941-7.969 7.941-1.391 0-2.709-.364-3.856-1.002l-4.529 1.187 1.21-4.417c-.742-1.229-1.174-2.656-1.174-4.184 0-4.375 3.571-7.942 7.969-7.942z"/></svg>
                                                    <span>{{ $reporterPhone }}</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 block pt-0.5">Kontak pelapor tidak tersedia</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 2: Rincian Keluhan --}}
                                <td class="py-4 px-5">
                                    <div class="space-y-1">
                                        <strong class="text-xs sm:text-sm font-bold text-slate-900 block">
                                            {{ $complaint->reason }}
                                        </strong>
                                        @if($complaint->details)
                                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                                {{ $complaint->details }}
                                            </p>
                                        @endif
                                        <div class="text-[11px] text-slate-400 pt-0.5">
                                            Dilaporkan: {{ $complaint->created_at->format('d M Y, H:i') }}
                                        </div>
                                        @if($complaint->admin_notes)
                                            <div class="mt-1.5 p-2 rounded-lg bg-blue-50/70 border border-blue-100 text-[11px] text-blue-900">
                                                <span class="font-bold block text-blue-800">Catatan Pemilik:</span>
                                                {{ $complaint->admin_notes }}
                                            </div>
                                        @endif
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
                                            @if($complaint->status === 'pending')
                                                <form action="{{ route('owner.complaints.updateStatus', $complaint) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="reviewed">
                                                    <button 
                                                        type="submit" 
                                                        class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-extrabold shadow-sm shadow-blue-600/25 transition cursor-pointer"
                                                    >
                                                        Tangani
                                                    </button>
                                                </form>
                                            @elseif($complaint->status === 'reviewed')
                                                <form action="{{ route('owner.complaints.updateStatus', $complaint) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="resolved">
                                                    <button 
                                                        type="submit" 
                                                        class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold shadow-sm shadow-emerald-600/25 transition cursor-pointer"
                                                    >
                                                        Selesai
                                                    </button>
                                                </form>
                                            @endif

                                            <button 
                                                type="button" 
                                                @click="openActionModal('{{ route('owner.complaints.updateStatus', $complaint) }}', '{{ addslashes($complaint->reason) }}', '{{ $complaint->status }}', '{{ addslashes($complaint->admin_notes ?? '') }}')"
                                                class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer"
                                            >
                                                Tanggapi
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-800">Tidak ada keluhan ditemukan</p>
                                        <p class="text-xs text-slate-500">
                                            Semua laporan keluhan dari penghuni atau pengunjung kos akan tampil rapi di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Modal Tanggapan / Update Status Keluhan --}}
        <div 
            x-show="actionModalOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            style="display: none;"
            @keydown.escape.window="actionModalOpen = false"
        >
            <div 
                @click.outside="actionModalOpen = false"
                class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200/80 space-y-4"
            >
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">
                        Tanggapi &amp; Perbarui Keluhan
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Subjek: <span class="font-bold text-slate-700" x-text="complaintReason"></span>
                    </p>
                </div>

                <form :action="actionUrl" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status Penanganan</label>
                        <select 
                            name="status" 
                            x-model="currentStatus"
                            class="w-full text-xs sm:text-sm rounded-xl border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-semibold"
                        >
                            <option value="pending">Menunggu Tindakan</option>
                            <option value="reviewed">Sedang Ditangani</option>
                            <option value="resolved">Selesai Ditangani</option>
                            <option value="rejected">Tolak / Tidak Valid</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Tindakan Pemilik</label>
                        <textarea 
                            name="admin_notes" 
                            x-model="adminNotes"
                            rows="3" 
                            placeholder="Contoh: Teknisi listrik dijadwalkan datang besok pukul 10:00 WIB..."
                            class="w-full text-xs sm:text-sm rounded-xl border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button 
                            type="button" 
                            @click="actionModalOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-600/25 transition cursor-pointer"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-owner-layout>

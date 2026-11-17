<x-owner-layout title="Iklan Kos Saya">

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- Header Card (Refined Sapphire Blue Gradient) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs text-blue-200 font-medium mb-2">
                        <a href="{{ route('owner.dashboard') }}" class="hover:underline">Dashboard</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Iklan Kos Saya</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Iklan Kos Saya
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-1">
                        Pantau status verifikasi, perbarui ketersediaan kamar, dan tingkatkan visibilitas listing kos Anda.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('owner.kosts.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-700 text-xs font-semibold shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Pasang Iklan Kos Baru</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($kosts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kosts as $kost)
                    @php
                        $coverUrl = $kost->primaryImage 
                            ? asset('storage/' . $kost->primaryImage->file_path)
                            : 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=600&q=80';

                        $totalKamar = $kost->rooms->sum('total_rooms');
                        $availKamar = $kost->rooms->sum('available_rooms');
                        $occupiedKamar = max(0, $totalKamar - $availKamar);
                        $kRate = $totalKamar > 0 ? round(($occupiedKamar / $totalKamar) * 100, 1) : 0;

                        $statusBadge = match($kost->verification_status) {
                            'verified' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Terverifikasi'],
                            'pending' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Verifikasi'],
                            'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                            default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Draft'],
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between hover:border-slate-300 transition">
                        <div>
                            {{-- Image header --}}
                            <div class="relative h-44 w-full bg-slate-100 overflow-hidden">
                                <img src="{{ $coverUrl }}" alt="{{ $kost->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>

                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/95 text-slate-800 shadow-2xs capitalize">
                                        Kos {{ $kost->type }}
                                    </span>
                                </div>

                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $statusBadge['bg'] }} shadow-2xs">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </div>

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
                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block font-medium">Tarif Mulai</span>
                                        <span class="text-sm font-extrabold text-blue-600 tabular-nums">
                                            Rp {{ number_format($kost->price_start, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">/{{ $kost->rent_type }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-slate-400 block font-medium">Tingkat Okupansi</span>
                                        <span class="text-xs font-bold text-slate-800 tabular-nums">{{ $kRate }}%</span>
                                    </div>
                                </div>

                                <div class="bg-slate-50/80 rounded-xl p-3 space-y-1.5 text-xs text-slate-600 border border-slate-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Kapasitas Unit:</span>
                                        <span class="font-semibold text-slate-800 tabular-nums">{{ $totalKamar }} Kamar</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Unit Siap Huni:</span>
                                        <span class="font-bold text-emerald-600 tabular-nums">{{ $availKamar }} Kosong</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-500">Tipe Kamar:</span>
                                        <span class="font-semibold text-slate-800 tabular-nums">{{ $kost->rooms->count() }} Tipe</span>
                                    </div>
                                </div>

                                @if($kost->verification_status === 'rejected' && $kost->rejection_reason)
                                    <div class="p-3 rounded-xl bg-red-50 border border-red-200/80 text-[11px] text-red-700">
                                        <strong class="font-bold block mb-0.5">Alasan Penolakan:</strong>
                                        {{ $kost->rejection_reason }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="p-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('owner.rooms.index', $kost) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                    Kelola Kamar
                                </a>
                                <a href="{{ route('owner.promosi.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition">
                                    Promosi
                                </a>
                            </div>

                            <div class="flex items-center gap-1">
                                <a href="{{ route('owner.kosts.edit', $kost) }}" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-200/70 transition" title="Edit Iklan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <a href="{{ route('kost.detail', $kost->slug) }}" target="_blank" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-200/70 transition" title="Lihat Halaman Publik">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <form action="{{ route('owner.kosts.destroy', $kost) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan kos ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Iklan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-md mx-auto shadow-xs space-y-4">
                <div class="h-16 w-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="h-8 w-8 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Belum Ada Iklan Kos</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Anda belum memasang iklan properti kos. Mulai sekarang untuk menjangkau calon penghuni di seluruh Indonesia.
                </p>
                <div class="pt-2">
                    <x-seven-button :href="route('owner.kosts.create')" variant="primary" size="sm">
                        Pasang Iklan Kos Pertama Anda
                    </x-seven-button>
                </div>
            </div>
        @endif
    </div>

</x-owner-layout>

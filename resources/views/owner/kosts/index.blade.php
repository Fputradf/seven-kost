<x-seven-layout title="Kelola Iklan Kos Saya - Seven Kost">

    {{-- Header --}}
    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-2">
                        Panel Pemilik Kos
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Kelola Iklan Properti Kos
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        Pantau status verifikasi, ketersediaan kamar, dan kelola listing kos Anda.
                    </p>
                </div>

                <div>
                    <x-seven-button :href="route('owner.kosts.create')" variant="primary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Pasang Iklan Kos Baru
                    </x-seven-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($kosts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kosts as $kost)
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            {{-- Header Card --}}
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <span class="text-xs text-slate-400 font-medium block">
                                        {{ $kost->regency->name ?? '-' }}, {{ $kost->province->name ?? '-' }}
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-1 mt-0.5">
                                        {{ $kost->name }}
                                    </h3>
                                </div>

                                {{-- Status Verification Badge --}}
                                @php
                                    $statusBadge = match($kost->verification_status) {
                                        'verified' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Terverifikasi'],
                                        'pending' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Review'],
                                        'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                                        default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Draft'],
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusBadge['bg'] }} whitespace-nowrap">
                                    {{ $statusBadge['label'] }}
                                </span>
                            </div>

                            {{-- Info Details --}}
                            <div class="bg-slate-50 rounded-2xl p-3 my-3 space-y-1.5 text-xs text-slate-600">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Tipe:</span>
                                    <span class="font-semibold uppercase text-slate-800">{{ $kost->type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Mulai Dari:</span>
                                    <span class="font-extrabold text-blue-600">Rp {{ number_format($kost->price_start, 0, ',', '.') }}/bln</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Total Kamar:</span>
                                    <span class="font-semibold text-slate-800">{{ $kost->rooms->sum('total_rooms') }} Kamar (Sisa {{ $kost->rooms->sum('available_rooms') }})</span>
                                </div>
                            </div>

                            @if($kost->verification_status === 'rejected' && $kost->rejection_reason)
                                <div class="p-2.5 rounded-xl bg-red-50 border border-red-100 text-[11px] text-red-700 mb-3">
                                    <strong>Alasan Penolakan:</strong> {{ $kost->rejection_reason }}
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <a href="{{ route('owner.kosts.edit', $kost) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Iklan
                            </a>

                            <div class="flex items-center gap-1">
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
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-md mx-auto shadow-xs space-y-4">
                <div class="h-20 w-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="h-10 w-10 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Iklan Kos</h3>
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

</x-seven-layout>

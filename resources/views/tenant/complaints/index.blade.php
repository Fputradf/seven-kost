<x-tenant-layout title="Keluhan & Fasilitas">

    <div class="space-y-4">

        {{-- Top Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div>
                <span class="text-xs font-bold text-sky-200 uppercase tracking-wider block">Bantuan Fasilitas</span>
                <h2 class="text-base sm:text-lg font-black text-white !text-white leading-tight" style="color: #ffffff !important;">
                    Tiket Keluhan & Perbaikan Fasilitas
                </h2>
                <p class="text-xs text-white/95 mt-0.5">Total {{ $counts['all'] }} tiket keluhan</p>
            </div>
            <a href="{{ route('tenant.complaints.create') }}" class="shrink-0 px-3 py-1.5 rounded-xl bg-white text-blue-700 hover:bg-blue-50 text-xs font-bold transition shadow-xs active:scale-95 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Buat Tiket</span>
            </a>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800 flex items-center gap-2.5 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filter Tabs Status (Mobile Scrollable) --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs scrollbar-none">
            <a href="{{ route('tenant.complaints.index') }}" 
               class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ !$status ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'pending']) }}" 
               class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'pending' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80' }}">
                Menunggu ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'reviewed']) }}" 
               class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'reviewed' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80' }}">
                Ditinjau ({{ $counts['reviewed'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'resolved']) }}" 
               class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'resolved' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200/80' }}">
                Selesai ({{ $counts['resolved'] }})
            </a>
        </div>

        {{-- Complaints Feed --}}
        @if($complaints->count() > 0)
            <div class="space-y-3">
                @foreach($complaints as $complaint)
                    @php
                        $badge = match($complaint->status) {
                            'resolved' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Selesai'],
                            'reviewed' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Ditangani'],
                            'rejected' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Ditolak'],
                            default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu'],
                        };

                        $categoryName = match($complaint->category) {
                            'kamar_mandi' => 'Kamar Mandi',
                            'listrik_elektronik' => 'Listrik / AC',
                            'kebersihan' => 'Kebersihan',
                            'keamanan' => 'Keamanan',
                            'kamar' => 'Fasilitas Kamar',
                            default => 'Lainnya',
                        };
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-3.5 space-y-2 transition hover:border-blue-300">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $badge['bg'] }}">
                                {{ $badge['label'] }}
                            </span>
                            <span class="text-[10px] text-slate-400">
                                {{ $complaint->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">
                                {{ $categoryName }}
                            </span>
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 line-clamp-1 mt-0.5">
                                {{ $complaint->reason }}
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2 mt-1 leading-relaxed">
                                {{ $complaint->details }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px] text-slate-500">
                            <span class="truncate">{{ $complaint->kost->name ?? 'Kos Terkait' }}</span>
                            @if($complaint->room)
                                <span class="text-slate-400 truncate ml-1">Kamar: {{ $complaint->room->name }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-white border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak Ada Keluhan</h3>
                    <p class="text-xs text-slate-500 mt-1">Belum ada tiket keluhan atau laporan fasilitas yang diajukan.</p>
                </div>
                <a href="{{ route('tenant.complaints.create') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                    <span>+ Buat Keluhan Baru</span>
                </a>
            </div>
        @endif

    </div>

</x-tenant-layout>

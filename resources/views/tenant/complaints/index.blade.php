<x-tenant-layout title="Keluhan & Fasilitas">

    <div class="space-y-6 sm:space-y-8">

        {{-- Header Card (White text judul, tanpa text helper) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Tiket Keluhan & Perbaikan Fasilitas
                    </h1>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('tenant.complaints.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold shadow-xs transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Buat Keluhan Baru</span>
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

        {{-- Filter Tabs Status (Monochrome) --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <a href="{{ route('tenant.complaints.index') }}" 
               class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap {{ !$status ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'pending' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Menunggu ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'reviewed']) }}" 
               class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'reviewed' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Ditinjau ({{ $counts['reviewed'] }})
            </a>
            <a href="{{ route('tenant.complaints.index', ['status' => 'resolved']) }}" 
               class="px-4 py-2 rounded-xl font-bold transition whitespace-nowrap {{ $status === 'resolved' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
                Selesai ({{ $counts['resolved'] }})
            </a>
        </div>

        {{-- Complaints List --}}
        @if($complaints->count() > 0)
            <div class="space-y-4">
                @foreach($complaints as $complaint)
                    @php
                        $badge = match($complaint->status) {
                            'resolved' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Selesai Diperbaiki'],
                            'reviewed' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Sedang Ditangani'],
                            'rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'label' => 'Ditolak'],
                            default => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'Menunggu Respon Pemilik'],
                        };

                        $categoryName = match($complaint->category) {
                            'kamar_mandi' => 'Kamar Mandi / Pipa Air',
                            'listrik_elektronik' => 'Listrik / AC / Lampu',
                            'kebersihan' => 'Kebersihan & Sampah',
                            'keamanan' => 'Keamanan & Kunci',
                            'kamar' => 'Fasilitas Kamar',
                            default => 'Lainnya',
                        };
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $categoryName }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        &bull; Dilaporkan {{ $complaint->created_at->format('d M Y, H:i') }} ({{ $complaint->created_at->diffForHumans() }})
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg">
                                    {{ $complaint->reason }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                    Kos: {{ $complaint->kost->name ?? 'Kos Terkait' }}
                                    @if($complaint->room)
                                        &bull; Kamar: {{ $complaint->room->name }}
                                    @endif
                                </p>
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badge['bg'] }} self-start sm:self-auto">
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        {{-- Details --}}
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4 text-xs text-slate-700 leading-relaxed">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Deskripsi Kendala</span>
                            {{ $complaint->details ?: 'Tidak ada detail tambahan.' }}
                        </div>

                        {{-- Owner Response Notes --}}
                        @if($complaint->admin_notes)
                            <div class="mt-4 rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-800 space-y-1">
                                <div class="flex items-center gap-1.5 font-bold text-slate-900">
                                    <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    <span>Tanggapan / Catatan Pemilik Kos:</span>
                                </div>
                                <p class="leading-relaxed pl-5 font-medium">
                                    {{ $complaint->admin_notes }}
                                </p>
                            </div>
                        @endif

                        {{-- Contact Owner via WA --}}
                        @if($complaint->kost && $complaint->kost->owner)
                            @php
                                $rawPhone = $complaint->kost->owner->phone ?? '081234567890';
                                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                                $waText = urlencode("Halo bapak/ibu {$complaint->kost->owner->name}, saya ingin menanyakan status keluhan fasilitas '{$complaint->reason}' untuk kos {$complaint->kost->name}.");
                                $waUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
                            @endphp
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs text-slate-400">Pemilik: <strong>{{ $complaint->kost->owner->name }}</strong></span>
                                <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                                    <svg class="w-3.5 h-3.5 text-slate-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.044.073.044.419-.1.824z"/></svg>
                                    <span>Tanya Pemilik via WA</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $complaints->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto shadow-xs space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Belum Ada Tiket Keluhan</h3>
                <div class="pt-2">
                    <a href="{{ route('tenant.complaints.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition shadow-xs">
                        + Buat Tiket Keluhan Baru
                    </a>
                </div>
            </div>
        @endif

    </div>

</x-tenant-layout>

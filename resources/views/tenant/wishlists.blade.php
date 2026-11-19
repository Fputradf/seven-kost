<x-tenant-layout title="Kos Favorit Saya">

    <div class="space-y-4">

        {{-- Top Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div>
                <span class="text-xs font-bold text-sky-200 uppercase tracking-wider block">Koleksi Tersimpan</span>
                <h2 class="text-base sm:text-lg font-black text-white !text-white leading-tight" style="color: #ffffff !important;">
                    Kos Favorit Saya
                </h2>
                <p class="text-xs text-white/95 mt-0.5">Total {{ $kosts->count() }} kos disimpan</p>
            </div>
            <a href="{{ route('tenant.search') }}" class="shrink-0 px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold transition border border-white/30 active:scale-95 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Cari Lainnya</span>
            </a>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-emerald-800 flex items-center gap-2.5 text-xs font-semibold shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($kosts->count() > 0)
            <div class="space-y-3" x-data="{
                async removeWishlist(kostId, el) {
                    if (!confirm('Hapus kos ini dari daftar favorit?')) return;
                    try {
                        const res = await fetch(`/penghuni/favorit/${kostId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        if (data.success && !data.is_favorited) {
                            el.closest('.wishlist-card').remove();
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
            }">
                @foreach($kosts as $kost)
                    @php
                        $img = $kost->primaryImage?->file_path ? asset('storage/' . $kost->primaryImage->file_path) : asset('images/placeholder-kost.jpg');
                        $genderBadge = match($kost->type) {
                            'putra' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Putra'],
                            'putri' => ['bg' => 'bg-pink-50 text-pink-700 border-pink-200', 'label' => 'Putri'],
                            default => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Campur'],
                        };
                    @endphp

                    <div class="wishlist-card bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs p-3 flex gap-3 relative transition hover:border-blue-300">
                        {{-- Image Thumbnail --}}
                        <div class="w-24 h-24 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200 relative">
                            <a href="{{ route('tenant.kost.detail', $kost->slug) }}" class="block w-full h-full">
                                <img src="{{ $img }}" alt="{{ $kost->name }}" class="w-full h-full object-cover">
                            </a>
                            <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $genderBadge['bg'] }}">
                                {{ $genderBadge['label'] }}
                            </span>
                        </div>

                        {{-- Info & Action --}}
                        <div class="flex-1 truncate flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 truncate">
                                    <a href="{{ route('tenant.kost.detail', $kost->slug) }}" class="hover:text-blue-600 transition">
                                        {{ $kost->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 truncate mt-0.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $kost->regency?->name ?? 'Kota' }}</span>
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Mulai</span>
                                    <strong class="text-xs sm:text-sm font-extrabold text-blue-600">
                                        Rp {{ number_format($kost->price_start, 0, ',', '.') }}
                                    </strong>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <button 
                                        type="button" 
                                        @click="removeWishlist({{ $kost->id }}, $el)" 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-100"
                                        title="Hapus dari Favorit"
                                    >
                                        <svg class="w-4 h-4 fill-rose-500 text-rose-500" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                    <a href="{{ route('tenant.kost.detail', $kost->slug) }}" class="px-2.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-2xs transition">
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-white border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Belum Ada Kos Favorit</h3>
                    <p class="text-xs text-slate-500 mt-1">Simpan kos yang menarik perhatian Anda dengan klik tombol hati pada kartu kos.</p>
                </div>
                <a href="{{ route('tenant.search') }}" class="inline-flex items-center gap-1.5 py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                    <span>Cari Kos Sekarang</span>
                </a>
            </div>
        @endif

    </div>

</x-tenant-layout>

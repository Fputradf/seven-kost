@props([
    'id' => null,
    'title' => 'Melati Residence Premium',
    'slug' => '#',
    'detailUrl' => null,
    'type' => 'campur', // putra, putri, campur
    'city' => 'Jakarta Selatan',
    'province' => 'DKI Jakarta',
    'price' => 1500000,
    'rentType' => 'bulan',
    'image' => null,
    'isVerified' => false,
    'isFeatured' => false,
    'availableRooms' => 3,
    'facilities' => ['AC', 'WiFi', 'K. Mandi Dalam'],
    'isFavorited' => false,
    'showRemoveFavorite' => false,
])

@php
    $resolvedDetailUrl = $detailUrl ?? ($slug !== '#' ? url('/kos/' . $slug) : '#');
@endphp

<div class="group relative flex flex-col overflow-hidden rounded-2xl sm:rounded-3xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70 {{ $isFeatured ? 'border-blue-200/90 bg-gradient-to-b from-blue-50/20 via-white to-slate-50/80 shadow-xs' : 'border-slate-200/80 bg-gradient-to-b from-white via-slate-50/30 to-slate-100/60 hover:border-slate-300 shadow-2xs' }}">
    {{-- Thumbnail Image, Tag Rekomendasi & Icon Simpan Favorit --}}
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
        <a href="{{ $resolvedDetailUrl }}" class="block h-full w-full">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                    <svg class="h-12 w-12 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
            @endif
        </a>

        {{-- Gradient Overlay Halus Bawah Foto --}}
        <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-slate-950/40 via-slate-950/10 to-transparent pointer-events-none"></div>

        {{-- HANYA Tag Rekomendasi di Dalam Gambar (Warna Biru) --}}
        @if($isFeatured)
            <div class="absolute top-3 left-3 pointer-events-none">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[11px] font-extrabold shadow-md shadow-blue-600/35 backdrop-blur-xs">
                    <svg class="w-3 h-3 fill-current text-white" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Rekomendasi</span>
                </span>
            </div>
        @endif

        {{-- Tombol Simpan / Favorit (Pojok Kanan Atas Gambar) --}}
        @if($id || $slug !== '#')
            <div class="absolute top-3 right-3 z-10" 
                 x-data="{ 
                     isSaved: {{ $isFavorited ? 'true' : 'false' }}, 
                     loading: false,
                     toggleFavorite() {
                         @auth
                             if (this.loading) return;
                             this.loading = true;
                             const target = '{{ $id ?: $slug }}';
                             fetch('{{ url('/penghuni/favorit') }}/' + target + '/toggle', {
                                 method: 'POST',
                                 headers: {
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                     'Accept': 'application/json',
                                     'X-Requested-With': 'XMLHttpRequest'
                                 }
                             })
                             .then(res => res.json())
                             .then(data => {
                                 if (data && data.success !== undefined) {
                                     this.isSaved = data.is_favorited;
                                 }
                             })
                             .catch(err => console.error(err))
                             .finally(() => { this.loading = false; });
                         @else
                             window.location.href = '{{ route('login') }}';
                         @endauth
                     }
                 }">
                <button 
                    type="button" 
                    @click.stop.prevent="toggleFavorite()"
                    :disabled="loading"
                    class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full transition-all duration-200 active:scale-90 shadow-md backdrop-blur-md cursor-pointer focus:outline-none"
                    :class="isSaved 
                        ? 'bg-white text-rose-600 shadow-rose-500/20 ring-2 ring-rose-500/20' 
                        : 'bg-white/90 hover:bg-white text-slate-600 hover:text-rose-600 border border-white/70 hover:shadow-lg'"
                    title="Simpan ke Favorit"
                    aria-label="Simpan ke Favorit"
                >
                    <svg class="h-4 w-4 sm:h-4.5 sm:w-4.5 transition-transform duration-200" 
                         :class="{ 'fill-rose-500 stroke-rose-500 scale-110': isSaved, 'fill-none stroke-current stroke-2': !isSaved, 'animate-pulse': loading }" 
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    {{-- Content Bawah Gambar --}}
    <div class="flex flex-1 flex-col p-4 sm:p-5">
        {{-- Baris Tag: Tidak Berwarna (Netral / Monochrome) --}}
        <div class="flex items-center gap-2 mb-2.5 flex-wrap">
            {{-- Badge Jenis Kos (Netral) --}}
            @php
                $typeLabel = match(strtolower($type)) {
                    'putri' => 'Kos Putri',
                    'putra' => 'Kos Putra',
                    default => 'Kos Campur',
                };
            @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80 tracking-wide">
                {{ $typeLabel }}
            </span>

            {{-- Badge Ketersediaan Kamar (Netral) --}}
            @if($availableRooms > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200/80">
                    Sisa {{ $availableRooms }} Kamar
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200/80">
                    Kamar Penuh
                </span>
            @endif
        </div>

        {{-- Lokasi --}}
        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500 mb-1">
            <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $city }}{{ $province ? ', ' . $province : '' }}</span>
        </div>

        {{-- Judul Kos --}}
        <h3 class="font-extrabold text-slate-900 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition-colors">
            <a href="{{ $resolvedDetailUrl }}">
                {{ $title }}
            </a>
        </h3>

        {{-- Fasilitas Utama --}}
        @if(!empty($facilities))
            <div class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                @foreach(array_slice($facilities, 0, 3) as $facility)
                    <span class="rounded-md bg-white border border-slate-200/80 px-2 py-0.5 text-[11px] font-medium text-slate-600 inline-block shadow-3xs">
                        {{ $facility }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Divider & Harga + Aksi Hapus Favorit Dalam Card --}}
        <div class="mt-4 pt-3.5 border-t border-slate-100/90 flex items-center justify-between gap-2">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block leading-tight">Mulai dari</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-base sm:text-lg font-black bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 bg-clip-text text-transparent tracking-tight">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-slate-500 font-medium">/ {{ $rentType }}</span>
                </div>
            </div>

            @if($showRemoveFavorite && ($id || $slug !== '#'))
                <form method="POST" action="{{ route('tenant.wishlists.toggle', $id ?: $slug) }}" onsubmit="return confirm('Hapus kos ini dari daftar favorit?');" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 border border-rose-200/80 transition active:scale-95 shadow-3xs cursor-pointer" title="Hapus dari Favorit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus</span>
                    </button>
                </form>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>

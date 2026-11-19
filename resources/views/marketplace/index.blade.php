<x-seven-layout title="Marketplace Barang Kos - Jual Beli Kebutuhan Kos (Sistem COD)">

    {{-- HERO SECTION MARKETPLACE (COMPACT & MOBILE FRIENDLY) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-5 pb-2 sm:pb-3">
        <div class="relative overflow-hidden rounded-2xl text-white p-4 sm:p-5 md:p-6 shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            {{-- Decorative circles --}}
            <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-blue-400/15 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -top-6 right-1/4 w-28 h-28 bg-sky-300/15 rounded-full blur-lg pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-center">
                <div class="lg:col-span-8 space-y-1.5 sm:space-y-2">
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight leading-snug text-white">
                        Marketplace <span class="text-sky-300">Barang Kos</span>
                    </h1>

                    <p class="text-xs sm:text-sm text-blue-100 leading-relaxed max-w-2xl font-normal">
                        Jual & beli perlengkapan kamar kos bekas berkualitas (kasur, kipas, meja, rak, rice cooker) langsung antar sesama anak kos di sekitar kampus. Transaksi mudah, cepat, dan 100% bayar di tempat (COD).
                    </p>

                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-4 pt-1 text-xs text-blue-100">
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-2 h-2 bg-emerald-400 inline-block rounded-full"></span>
                            <span>Tanpa Biaya Admin</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-2 h-2 bg-emerald-400 inline-block rounded-full"></span>
                            <span>100% COD (Bayar Saat Temu)</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-2 h-2 bg-emerald-400 inline-block rounded-full"></span>
                            <span>Titik Temu Area Kampus & Kos</span>
                        </div>
                    </div>
                </div>

                {{-- CTA KANAN: JUAL BARANG (RESPONSIVE & CLEAN) --}}
                <div class="lg:col-span-4 flex flex-col justify-center">
                    <div class="w-full bg-white/10 backdrop-blur-md rounded-xl p-3.5 sm:p-4 border border-white/20 flex flex-col sm:flex-row lg:flex-col items-center justify-between gap-3 text-center sm:text-left lg:text-center shadow-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center shrink-0 shadow-xs border border-white/25">
                                <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white leading-tight">Punya Barang Tak Terpakai?</h3>
                                <p class="text-xs text-blue-100 mt-0.5">Posting gratis dalam hitungan menit</p>
                            </div>
                        </div>

                        @auth
                            <a 
                                href="{{ route('marketplace.create') }}" 
                                class="w-full sm:w-auto lg:w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-md transition text-center border border-white/20 active:scale-95"
                            >
                                + Posting Jual Barang
                            </a>
                        @else
                            <a 
                                href="{{ route('login') }}" 
                                class="w-full sm:w-auto lg:w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-md transition text-center border border-white/20 active:scale-95"
                            >
                                Masuk untuk Jual Barang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT PEMBERITAHUAN SISTEM COD (RAMAH, ALAMI & JELAS) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 border border-amber-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3.5 text-slate-800">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5 border border-amber-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-bold text-amber-950">
                        Tips Transaksi Aman (Sistem COD)
                    </h4>
                    <p class="text-xs sm:text-sm text-amber-900 leading-relaxed">
                        Transaksi dilakukan secara tatap muka (Cash on Delivery). Cek kondisi fisik dan kelengkapan barang saat bertemu di lokasi yang aman (seperti lobby kos atau kampus) sebelum melakukan pembayaran langsung ke penjual.
                    </p>
                </div>
            </div>
            <div class="shrink-0 text-xs font-bold text-amber-900 bg-amber-100/90 px-3.5 py-1.5 rounded-xl border border-amber-300">
                100% COD
            </div>
        </div>
    </div>

    {{-- COMMERCIAL AD BANNER SLIDER (BR-11: PENAYANGAN BANNER BRAND & BISNIS DI MARKETPLACE) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        @php
            $mktBanners = [];
            if (isset($banners) && $banners->count() > 0) {
                foreach ($banners as $b) {
                    $mktBanners[] = [
                        'img' => $b->image_url,
                        'link' => $b->target_url ?: route('ads.packages.index', ['tab' => 'banner']),
                        'alt' => $b->title,
                    ];
                }
            }
            if (empty($mktBanners)) {
                $mktBanners = [
                    ['img' => asset('images/frontend/banner-bandung-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Promo Perlengkapan Kos'],
                    ['img' => asset('images/frontend/banner-jakarta-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Promo Laundry Express'],
                ];
            }
        @endphp

        <div 
            class="group/banner relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-200/80 shadow-xs"
            x-data="{
                activeBanner: 0,
                totalBanners: {{ count($mktBanners) }},
                timer: null,
                start() {
                    this.timer = setInterval(() => {
                        this.next();
                    }, 5000);
                },
                stop() { clearInterval(this.timer); },
                next() {
                    this.activeBanner = (this.activeBanner + 1) % this.totalBanners;
                },
                prev() {
                    this.activeBanner = (this.activeBanner - 1 + this.totalBanners) % this.totalBanners;
                }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()"
        >
            <div class="relative w-full h-32 sm:h-44 md:h-52 overflow-hidden">
                @foreach($mktBanners as $idx => $bItem)
                    <div 
                        x-show="activeBanner === {{ $idx }}"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-98"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0 w-full h-full"
                        style="{{ $idx > 0 ? 'display: none;' : '' }}"
                    >
                        <a href="{{ $bItem['link'] }}" class="block w-full h-full group">
                            <img src="{{ $bItem['img'] }}" alt="{{ $bItem['alt'] }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/60 text-white text-xs font-semibold tracking-wider uppercase backdrop-blur-xs">
                                Iklan Sponsor & Promosi
                            </div>
                            <div class="absolute bottom-3 right-3">
                                <div class="text-xs sm:text-sm font-bold text-white bg-blue-600/95 hover:bg-blue-600 px-3.5 py-1.5 rounded-xl backdrop-blur-xs shadow-sm">
                                    Lihat Penawaran →
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Navigasi Kiri & Kanan --}}
            @if(count($mktBanners) > 1)
                <button 
                    type="button" 
                    @click.prevent="prev()" 
                    aria-label="Banner Sebelumnya" 
                    class="absolute left-2.5 sm:left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/75 text-white flex items-center justify-center transition-all opacity-0 group-hover/banner:opacity-100 hover:scale-105 active:scale-95 focus:opacity-100 shadow-md backdrop-blur-xs z-10"
                >
                    <svg class="w-4 h-4 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button 
                    type="button" 
                    @click.prevent="next()" 
                    aria-label="Banner Selanjutnya" 
                    class="absolute right-2.5 sm:right-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/45 hover:bg-black/75 text-white flex items-center justify-center transition-all opacity-0 group-hover/banner:opacity-100 hover:scale-105 active:scale-95 focus:opacity-100 shadow-md backdrop-blur-xs z-10"
                >
                    <svg class="w-4 h-4 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif

            {{-- Dots Indicator --}}
            @if(count($mktBanners) > 1)
                <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-xs">
                    @foreach($mktBanners as $idx => $bItem)
                        <button 
                            type="button" 
                            @click="activeBanner = {{ $idx }}" 
                            class="h-1.5 rounded-full transition-all"
                            :class="activeBanner === {{ $idx }} ? 'w-5 bg-white' : 'w-1.5 bg-white/50'"
                            aria-label="Slide {{ $idx + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- FILTER BAR & SEARCH --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-4">
        {{-- Search input & Filter row --}}
        <form action="{{ route('marketplace.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200 p-3.5 sm:p-4 shadow-xs flex flex-col md:flex-row gap-3 items-center justify-between">
            {{-- Search Bar --}}
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari perlengkapan kos... (kasur, kipas, meja)" 
                    class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400"
                >
            </div>

            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 w-full md:w-auto">
                {{-- Filter Kota --}}
                <select 
                    name="kota" 
                    onchange="this.form.submit()" 
                    class="rounded-xl border border-slate-200 text-sm py-2.5 sm:py-3 px-3 text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 bg-white"
                >
                    <option value="semua">Semua Wilayah</option>
                    <option value="Jakarta" {{ request('kota') === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                    <option value="Depok" {{ request('kota') === 'Depok' ? 'selected' : '' }}>Depok</option>
                    <option value="Bandung" {{ request('kota') === 'Bandung' ? 'selected' : '' }}>Bandung</option>
                    <option value="Yogyakarta" {{ request('kota') === 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                    <option value="Surabaya" {{ request('kota') === 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                </select>

                {{-- Urutkan --}}
                <select 
                    name="urutkan" 
                    onchange="this.form.submit()" 
                    class="rounded-xl border border-slate-200 text-sm py-2.5 sm:py-3 px-3 text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 bg-white"
                >
                    <option value="terbaru" {{ request('urutkan') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="termurah" {{ request('urutkan') === 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="termahal" {{ request('urutkan') === 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="populer" {{ request('urutkan') === 'populer' ? 'selected' : '' }}>Paling Banyak Dilihat</option>
                </select>

                <button type="submit" class="px-5 py-2.5 sm:py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition shadow-sm">
                    Cari
                </button>

                @if(request()->hasAny(['q', 'kategori', 'kota', 'urutkan']))
                    <a href="{{ route('marketplace.index') }}" class="px-3 py-2 text-sm font-semibold text-slate-500 hover:text-slate-800 underline">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Category Pills --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <span class="text-sm font-bold text-slate-700 mr-2 shrink-0">Kategori:</span>
            @foreach($categories as $catKey => $catData)
                @php
                    $isActive = ($catKey === 'semua' && !request('kategori')) || request('kategori') === $catKey;
                @endphp
                <a 
                    href="{{ route('marketplace.index', array_merge(request()->except('kategori'), ['kategori' => $catKey])) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition shrink-0 {{ $isActive ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                >
                    <span>{{ $catData['icon'] }}</span>
                    <span>{{ $catData['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- FLASH MESSAGE JIKA BERHASIL POSTING --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
            </div>
        @endif

        {{-- PRODUCT CATALOG GRID (LEGA & PROPORTIONAL) --}}
        <div class="pt-2">
            @if($items->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($items as $item)
                        <div class="group bg-white rounded-2xl border border-slate-200/90 hover:border-blue-400 shadow-2xs hover:shadow-lg transition-all duration-200 flex flex-col justify-between overflow-hidden">
                            <div>
                                {{-- Card Image with badges --}}
                                <div class="relative w-full h-44 sm:h-52 bg-slate-100 overflow-hidden">
                                    <a href="{{ route('marketplace.show', $item->slug) }}" class="block w-full h-full">
                                        <img 
                                            src="{{ $item->primary_image_url }}" 
                                            alt="{{ $item->title }}" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        >
                                    </a>
                                    
                                    {{-- Badges: COD dan Kondisi Barang --}}
                                    <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white font-bold text-xs shadow-xs tracking-wide">
                                            COD
                                        </span>
                                    </div>

                                    <div class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-white font-medium text-xs">
                                        {{ $item->condition_label }}
                                    </div>
                                </div>

                                {{-- Card Content --}}
                                <div class="p-3.5 sm:p-4 space-y-1.5">
                                    <div class="flex items-center justify-between text-xs text-slate-500 font-medium">
                                        <span class="truncate">{{ $item->category_label }}</span>
                                        <span class="inline-flex items-center gap-1 shrink-0">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ $item->views_count }}
                                        </span>
                                    </div>

                                    <h3 class="font-bold text-sm sm:text-base text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('marketplace.show', $item->slug) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h3>

                                    <div class="text-base sm:text-lg font-black text-blue-600 pt-0.5">
                                        {{ $item->formatted_price }}
                                    </div>

                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 pt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        <span class="truncate">{{ $item->meetup_location }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer: Seller info & Action --}}
                            <div class="p-3 sm:p-3.5 border-t border-slate-100 flex items-center justify-between bg-slate-50/70 gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ substr($item->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-slate-700 text-xs truncate">{{ $item->user->name ?? 'Penjual' }}</span>
                                </div>

                                <a 
                                    href="{{ route('marketplace.show', $item->slug) }}" 
                                    class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition shrink-0"
                                >
                                    Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pt-8">
                    {{ $items->links() }}
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-10 sm:p-14 text-center max-w-md mx-auto space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto border border-blue-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-bold text-base sm:text-lg text-slate-900">Belum Ada Barang di Kategori Ini</h4>
                        <p class="text-xs sm:text-sm text-slate-500">Jadilah yang pertama memposting barang kebutuhan anak kos.</p>
                    </div>
                    <div class="pt-2">
                        @auth
                            <a href="{{ route('marketplace.create') }}" class="inline-block px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                                + Posting Barang Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                                Masuk untuk Posting Barang
                            </a>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-seven-layout>

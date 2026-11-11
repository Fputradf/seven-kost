<x-seven-layout title="SevenKost - Cari Kos Jadi Lebih Mudah | Platform Kos Terpercaya" :transparentNavbar="true">

@push('styles')
<style>
    @keyframes heroFadeInUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .hero-animate-1 { animation: heroFadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both; }
    .hero-animate-2 { animation: heroFadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.25s both; }
    .hero-animate-3 { animation: heroFadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s both; }
    .hero-animate-4 { animation: heroFadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.55s both; }
</style>
@endpush

    {{-- 1. HERO SECTION (FULLSCREEN DARK HERO DENGAN OVERLAY FOTO GEDUNG & SEARCH TERPUSAT) --}}
    <section class="relative min-h-screen w-full flex flex-col justify-center items-center text-center overflow-hidden pt-28 pb-16 lg:pt-32 lg:pb-20">
        
        {{-- Fullscreen Background Image dengan Overlay Navy Gelap --}}
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <img 
                src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                alt="SevenKost Hero Background" 
                class="w-full h-full object-cover object-center scale-105 filter brightness-90"
            >
            {{-- Overlay gradient navy gelap (atas sekitar #031d44 opacity 60%, bawah hampir solid #020b1f) --}}
            <div class="absolute inset-0 bg-gradient-to-b from-[#031d44]/65 via-[#031d44]/85 to-[#020b1f]"></div>
            {{-- Glow biru samar di tengah --}}
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[650px] bg-blue-500/20 rounded-full blur-[140px]"></div>
        </div>

        {{-- Hero Center Content (Horisontal & Vertikal Rata Tengah) --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full flex flex-col items-center">
            
            {{-- Main Headline --}}
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight leading-[1.1] text-white hero-animate-1">
                Cari Kos Jadi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">
                    Lebih Mudah
                </span>
            </h1>

            {{-- Subheading Rata Tengah --}}
            <p class="text-blue-100/90 text-sm sm:text-base md:text-lg max-w-[700px] mx-auto font-medium leading-relaxed mt-4 sm:mt-5 hero-animate-2">
                Temukan kos terbaik di lokasi strategis di 34 Provinsi, dengan harga bersahabat.
            </p>

            {{-- 2 Tombol Aksi Berdampingan (Menumpuk di Mobile) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 sm:gap-4 mt-6 sm:mt-8 w-full sm:w-auto hero-animate-3">
                {{-- Primary Button: Cari Kos Sekarang --}}
                <a href="{{ url('/cari') }}" 
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl font-bold text-sm sm:text-base text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari Kos Sekarang</span>
                </a>

                {{-- Secondary Button: Pasang Iklan Kos --}}
                <a href="{{ url('/paket-iklan') }}" 
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl font-bold text-sm sm:text-base text-white bg-white/10 hover:bg-white/20 border border-white/25 backdrop-blur-md transition transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Pasang Iklan Kos</span>
                </a>
            </div>

            {{-- Floating Search Bar Card (Gaya Glass: bg-white/95 + backdrop-blur, max-w-5xl, posisi di bawah tombol) --}}
            <div 
                x-data="{
                    location: '{{ request('kota', '') }}',
                    type: '{{ request('tipe', '') }}',
                    price: '{{ request('max_price', '') }}'
                }"
                class="w-full max-w-5xl mx-auto mt-8 sm:mt-10 bg-white/95 backdrop-blur-md rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-5 text-slate-800 border border-white/40 text-left hero-animate-4"
            >
                <form action="{{ url('/cari') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center">
                        {{-- 1. Lokasi / Kampus --}}
                        <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold text-slate-900 leading-tight">Lokasi / Kampus</label>
                                <select name="kota" x-model="location" class="w-full p-0 border-0 text-xs sm:text-sm text-slate-500 font-medium focus:ring-0 bg-transparent cursor-pointer">
                                    <option value="">Pilih lokasi</option>
                                    <option value="3171">Jakarta</option>
                                    <option value="3273">Bandung</option>
                                    <option value="3471">Yogyakarta</option>
                                    <option value="3578">Surabaya</option>
                                    <option value="3276">Depok</option>
                                    <option value="3573">Malang</option>
                                    @if(isset($provinces))
                                        <optgroup label="Provinsi">
                                            @foreach($provinces as $prov)
                                                <option value="prov_{{ $prov->id }}">{{ $prov->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- 2. Tipe Kos --}}
                        <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 sm:border-l sm:border-slate-200">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold text-slate-900 leading-tight">Tipe Kos</label>
                                <select name="tipe" x-model="type" class="w-full p-0 border-0 text-xs sm:text-sm text-slate-500 font-medium focus:ring-0 bg-transparent cursor-pointer">
                                    <option value="">Putra / Putri / Campur</option>
                                    <option value="putra">Putra</option>
                                    <option value="putri">Putri</option>
                                    <option value="campur">Campur</option>
                                </select>
                            </div>
                        </div>

                        {{-- 3. Rentang Harga --}}
                        <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-200 lg:border-l lg:border-slate-200">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-xs font-bold text-slate-900 leading-tight">Rentang Harga</label>
                                <select name="max_price" x-model="price" class="w-full p-0 border-0 text-xs sm:text-sm text-slate-500 font-medium focus:ring-0 bg-transparent cursor-pointer">
                                    <option value="">Rp 500rb - 2jt+</option>
                                    <option value="1000000">&le; Rp 1 Juta/bln</option>
                                    <option value="1500000">&le; Rp 1.5 Juta/bln</option>
                                    <option value="2500000">&le; Rp 2.5 Juta/bln</option>
                                    <option value="5000000">&le; Rp 5 Juta/bln</option>
                                </select>
                            </div>
                        </div>

                        {{-- 4. Tombol Cari --}}
                        <div>
                            <button type="submit" class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 active:translate-y-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Cari</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- 2. BANNER IKLAN WIDE (DIPASANG OLEH PEMILIK / ADMIN - SLIDER PROMOSI SPONSOR KOMERSIAL) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-12 relative z-20">
        @php
            $bannerItems = [];
            if (isset($banners) && $banners->count() > 0) {
                foreach ($banners as $b) {
                    $bannerItems[] = [
                        'img' => $b->image_url,
                        'link' => $b->target_url ?: route('ads.packages.index', ['tab' => 'banner']),
                        'alt' => $b->title ?: 'Banner Iklan SevenKost HD',
                    ];
                }
            }
            $fallbacks = [
                ['img' => asset('images/frontend/banner-jakarta-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Banner Iklan Kos Jakarta SevenKost'],
                ['img' => asset('images/frontend/banner-bandung-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Banner Iklan Kos Bandung SevenKost'],
                ['img' => asset('images/frontend/banner-yogyakarta-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Banner Iklan Kos Yogyakarta SevenKost'],
            ];
            while (count($bannerItems) < 3) {
                $bannerItems[] = $fallbacks[count($bannerItems) % count($fallbacks)];
            }
        @endphp

        <div 
            class="group/banner relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-200/80 shadow-xs"
            x-data="{
                activeBanner: 0,
                totalBanners: {{ count($bannerItems) }},
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
                @foreach($bannerItems as $idx => $bItem)
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
                            <div class="absolute top-2.5 left-3 px-2 py-0.5 rounded bg-black/60 text-white text-[9px] font-bold tracking-wider uppercase backdrop-blur-xs">
                                Iklan Sponsor & Promosi
                            </div>
                            <div class="absolute bottom-2.5 right-3">
                                <div class="text-[11px] font-bold text-white bg-blue-600/90 hover:bg-blue-600 px-3 py-1 rounded-lg backdrop-blur-xs shadow-sm">
                                    Lihat Penawaran →
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Navigasi Kiri & Kanan --}}
            @if(count($bannerItems) > 1)
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
            @if(count($bannerItems) > 1)
                <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-xs">
                    @foreach($bannerItems as $idx => $bItem)
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
    </section>

    {{-- 3. KOS UNGGULAN (4 CARDS GRID) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Kos Unggulan</h2>
                </div>
                <p class="text-slate-500 text-sm mt-0.5">Pilihan terbaik untuk hunian nyamanmu</p>
            </div>

            <a href="{{ url('/cari') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        {{-- 4 Cards Grid Matching Mockup --}}
        @php
            $defaultFeatured = [
                [
                    'title' => 'Kost Putri Melati Residence',
                    'slug' => 'kost-putri-melati-residence',
                    'location' => 'Kramat, Jakarta Pusat',
                    'price' => 'Rp 1.200.000',
                    'rating' => '4.8',
                    'reviews' => '120',
                    'image' => asset('images/frontend/kost-1-hd.jpg'),
                ],
                [
                    'title' => 'Kost Putra Mandiri Jaya',
                    'slug' => 'kost-putra-mandiri-jaya',
                    'location' => 'Sukajadi, Bandung',
                    'price' => 'Rp 850.000',
                    'rating' => '4.7',
                    'reviews' => '98',
                    'image' => asset('images/frontend/kost-2-hd.jpg'),
                ],
                [
                    'title' => 'Kost Campur Harmoni',
                    'slug' => 'kost-campur-harmoni',
                    'location' => 'Depok, Jawa Barat',
                    'price' => 'Rp 900.000',
                    'rating' => '4.8',
                    'reviews' => '75',
                    'image' => asset('images/frontend/kost-3-hd.jpg'),
                ],
                [
                    'title' => 'Kost Putri Srikandi',
                    'slug' => 'kost-putri-srikandi',
                    'location' => 'Tegalsari, Surabaya',
                    'price' => 'Rp 750.000',
                    'rating' => '4.7',
                    'reviews' => '64',
                    'image' => asset('images/frontend/kost-4-hd.jpg'),
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredKosts as $index => $kost)
                @php
                    $displayImg = $kost['image'] ?: ($defaultFeatured[$index % 4]['image'] ?? asset('images/frontend/kost-1-hd.jpg'));
                    $displayTitle = $kost['title'];
                    $displayLoc = ($kost['city'] ?? 'Kota') . ($kost['province'] ? ', ' . $kost['province'] : '');
                    $displayPrice = 'Rp ' . number_format($kost['price'], 0, ',', '.');
                    $displayRating = '4.' . (7 + ($index % 3));
                    $displayReviews = (60 + ($index * 19));
                @endphp
                <div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                    {{-- Image Container --}}
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                        <img 
                            src="{{ $displayImg }}" 
                            alt="{{ $displayTitle }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        {{-- Verified Badge --}}
                        <div class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold shadow-md">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Terverifikasi</span>
                        </div>

                        {{-- Tombol Simpan Kost (Bookmark) --}}
                        <button type="button" aria-label="Simpan Kost" title="Simpan Kost" class="group/save absolute top-3 right-3 w-8 h-8 rounded-full bg-white/85 hover:bg-white backdrop-blur-xs text-slate-600 hover:text-blue-600 flex items-center justify-center shadow-md transition-all active:scale-95">
                            <svg class="w-4 h-4 group-hover/save:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1">
                            <h3 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors line-clamp-1">
                                <a href="{{ route('kost.detail', $kost['slug']) }}">
                                    {{ $displayTitle }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="truncate">{{ $displayLoc }}</span>
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <span class="text-sm font-extrabold text-blue-600">{{ $displayPrice }}</span>
                                <span class="text-[11px] text-slate-400 font-normal">/bulan</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-semibold text-slate-700">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>{{ $displayRating }}</span>
                                <span class="text-slate-400 font-normal text-[11px]">({{ $displayReviews }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @foreach($defaultFeatured as $item)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col">
                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-100">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Terverifikasi</span>
                            </div>
                            {{-- Tombol Simpan Kost (Bookmark) --}}
                            <button type="button" aria-label="Simpan Kost" title="Simpan Kost" class="group/save absolute top-3 right-3 w-8 h-8 rounded-full bg-white/85 hover:bg-white backdrop-blur-xs text-slate-600 hover:text-blue-600 flex items-center justify-center shadow-md transition-all active:scale-95">
                                <svg class="w-4 h-4 group-hover/save:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <div class="space-y-1">
                                <h3 class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">
                                    <a href="{{ url('/cari?q=' . urlencode($item['title'])) }}">{{ $item['title'] }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span>{{ $item['location'] }}</span>
                                </p>
                            </div>
                            <div class="pt-2 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-extrabold text-blue-600">{{ $item['price'] }}</span>
                                    <span class="text-[11px] text-slate-400 font-normal">/bulan</span>
                                </div>
                                <div class="flex items-center gap-1 text-xs font-semibold text-slate-700">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>{{ $item['rating'] }}</span>
                                    <span class="text-slate-400 font-normal text-[11px]">({{ $item['reviews'] }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- 4. JELAJAHI PER KOTA & KAMPUS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h2 class="text-2xl font-extrabold text-slate-900">Jelajahi per Kota & Kampus</h2>
            </div>
            <p class="text-slate-500 text-sm mt-0.5">Temukan kos di kota dan kampus favoritmu</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            {{-- Jakarta --}}
            <a href="{{ url('/cari?q=Jakarta&kota=3171') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-jakarta.png') }}" alt="Jakarta" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Jakarta
                </span>
            </a>

            {{-- Bandung --}}
            <a href="{{ url('/cari?q=Bandung&kota=3273') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-bandung.png') }}" alt="Bandung" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Bandung
                </span>
            </a>

            {{-- Yogyakarta --}}
            <a href="{{ url('/cari?q=Yogyakarta&kota=3471') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-yogyakarta.png') }}" alt="Yogyakarta" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Yogyakarta
                </span>
            </a>

            {{-- Surabaya --}}
            <a href="{{ url('/cari?q=Surabaya&kota=3578') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-surabaya.png') }}" alt="Surabaya" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Surabaya
                </span>
            </a>

            {{-- Depok --}}
            <a href="{{ url('/cari?q=Depok&kota=3276') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-depok.png') }}" alt="Depok" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Depok
                </span>
            </a>

            {{-- Lainnya --}}
            <a href="{{ url('/peta') }}" class="group block rounded-2xl overflow-hidden border border-blue-100 shadow-xs hover:shadow-md transition text-center bg-blue-50/60 p-2 flex flex-col justify-center">
                <div class="h-16 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 bg-white group-hover:bg-blue-600 text-blue-600 group-hover:text-white text-xs font-bold transition shadow-xs">
                    Lainnya &rarr;
                </span>
            </a>
        </div>
    </section>

    {{-- 5. TWO INTERACTIVE CARDS (MARKETPLACE BARANG KOS & LAYANAN MITRA) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {{-- Card 1: Marketplace Barang Kos --}}
            <div id="marketplace" class="group/market relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-100/80 via-sky-50/60 to-white p-4 sm:p-5 md:p-6 border border-blue-200/70 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                {{-- Decorative Subtle Background Glow --}}
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-blue-300/20 rounded-full blur-2xl pointer-events-none group-hover/market:scale-110 transition-transform duration-500"></div>

                <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-3.5 sm:gap-6">
                    {{-- Text Info --}}
                    <div class="flex-1 min-w-0 space-y-2 text-center sm:text-left">
                        <div class="flex items-center justify-center sm:justify-start gap-2 text-blue-800 font-black text-base sm:text-lg">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Marketplace Barang Kos</span>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            Lengkapi kamar kosmu dengan kasur, meja belajar, rak, hingga elektronik hemat & berkualitas tanpa ribet.
                        </p>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 text-[11px] sm:text-xs font-semibold text-slate-600">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                100% COD (Bayar di Tempat)
                            </span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Titik Temu Kos & Kampus
                            </span>
                        </div>

                        <div class="pt-1">
                            <a href="{{ route('marketplace.index') }}" class="group/btn inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition-all transform hover:scale-105 active:scale-95">
                                <span>Buka Marketplace</span>
                                <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Image Container (Compact on mobile) --}}
                    <div class="w-full sm:w-40 md:w-44 h-28 sm:h-32 rounded-xl overflow-hidden shadow-xs border border-white ring-1 ring-blue-100 flex-shrink-0 relative group-hover/market:scale-103 transition-transform duration-300">
                        <img src="{{ asset('images/frontend/marketplace-hd.jpg') }}" alt="Marketplace Barang Kos SevenKost" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/30 via-transparent to-transparent"></div>
                        <div class="absolute bottom-1 left-1 right-1 text-center text-[10px] font-bold text-white bg-black/50 backdrop-blur-xs py-0.5 px-1 rounded-md">
                            Pilihan Hemat Anak Kos
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Layanan Mitra (Translucent / Frosted Glass Style) --}}
            <div id="layanan" class="group/service relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-100/70 via-sky-50/40 to-blue-50/20 backdrop-blur-md p-4 sm:p-5 md:p-6 border border-blue-200/70 shadow-sm hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between">
                {{-- Decorative Subtle Background Glow --}}
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-blue-300/20 rounded-full blur-2xl pointer-events-none group-hover/service:scale-110 transition-transform duration-500"></div>

                <div class="space-y-2.5 relative z-10">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 text-blue-800 font-black text-base sm:text-lg">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Layanan Mitra</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-emerald-500/10 text-emerald-800 border border-emerald-500/20 text-[10px] font-bold shadow-2xs backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 bg-emerald-500 animate-ping inline-block"></span>
                            2 Layanan Tersedia
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Layanan harian cuci pakaian & kebersihan kamar langsung ke kosmu agar hidup makin praktis.
                    </p>

                    {{-- 3 Service Sub-Cards (Tetap Transparan & Tanpa Animasi Interaktif Putih) --}}
                    <div class="pt-1 grid grid-cols-3 gap-2">
                        {{-- 1. Laundry --}}
                        <a href="{{ route('services.index') }}#laundry" class="bg-white/30 backdrop-blur-md rounded-xl p-2 border border-white/50 shadow-2xs text-center flex flex-col items-center transition-opacity hover:opacity-90">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg overflow-hidden mb-1 relative ring-1 ring-emerald-400/50 shadow-2xs">
                                <img src="{{ asset('images/frontend/service-laundry.jpg') }}" alt="Layanan Laundry Kos" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[11px] sm:text-xs font-black text-slate-800 leading-tight">Laundry</span>
                        </a>

                        {{-- 2. Kebersihan --}}
                        <a href="{{ route('services.index') }}#cleaning" class="bg-white/30 backdrop-blur-md rounded-xl p-2 border border-white/50 shadow-2xs text-center flex flex-col items-center transition-opacity hover:opacity-90">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg overflow-hidden mb-1 relative ring-1 ring-emerald-400/50 shadow-2xs">
                                <img src="{{ asset('images/frontend/service-cleaning.jpg') }}" alt="Layanan Kebersihan Kos" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[11px] sm:text-xs font-black text-slate-800 leading-tight">Kebersihan</span>
                        </a>

                        {{-- 3. Katering --}}
                        <a href="{{ route('services.index') }}#catering" class="bg-white/20 backdrop-blur-md rounded-xl p-2 border border-white/40 shadow-2xs text-center flex flex-col items-center opacity-85 hover:opacity-100 transition-opacity">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg overflow-hidden mb-1 relative ring-1 ring-amber-300/80 filter grayscale-30">
                                <img src="{{ asset('images/frontend/service-catering.jpg') }}" alt="Layanan Katering Kos" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[11px] sm:text-xs font-bold text-slate-700 leading-tight">Katering "Segera"</span>
                        </a>
                    </div>

                    <div class="pt-1 flex items-center justify-between">
                        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition-all">
                            <span>Pesan Layanan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. INTERACTIVE & ANIMATED CTA BANNER (PASANG IKLAN KOSMU SEKARANG!) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="group relative rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-600 to-sky-500 text-white p-7 sm:p-12 overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-blue-500/25 transition-all duration-500 transform hover:-translate-y-1">
            {{-- Floating Animated Decorative Glow Blobs --}}
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-white/15 rounded-full blur-2xl animate-pulse pointer-events-none"></div>
            <div class="absolute -top-12 left-1/3 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Shimmer Sweep Overlay on Hover --}}
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                {{-- Left: Modern Kos Room Photo with Verified Badge --}}
                <div class="relative hidden sm:block flex-shrink-0 group/kost">
                    <div class="w-32 h-32 md:w-36 md:h-36 rounded-2xl overflow-hidden ring-4 ring-white/30 shadow-2xl relative transform group-hover/kost:scale-105 group-hover/kost:-rotate-1 transition-transform duration-500">
                        <img src="{{ asset('images/frontend/cta-kost-room.jpg') }}" alt="Kamar Kos Modern SevenKost" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/50 via-transparent to-transparent"></div>
                    </div>
                    {{-- Floating Listing Verified Badge --}}
                    <div class="absolute -bottom-3 -right-2 bg-white/95 backdrop-blur-md text-blue-800 text-[11px] font-extrabold px-3 py-1 rounded-lg shadow-lg border border-white/60 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Kos Terverifikasi</span>
                    </div>
                </div>

                {{-- Center Text Content & Perks --}}
                <div class="space-y-3 text-center md:text-left flex-1">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight">
                        Pasang Iklan Kosmu <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 to-sky-300 animate-pulse">Sekarang!</span>
                    </h2>

                    <p class="text-xs sm:text-base text-blue-100 max-w-xl leading-relaxed">
                        Jangkau ribuan calon penyewa mahasiswa & pekerja kantoran tanpa biaya perantara.
                    </p>

                    {{-- Highlight Perks --}}
                    <div class="pt-1 flex flex-wrap items-center justify-center md:justify-start gap-2.5 text-xs font-semibold text-blue-100">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Listing Langsung Tayang
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white/10 backdrop-blur-xs border border-white/15">
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Verifikasi Cepat & Aman
                        </div>
                    </div>
                </div>

                {{-- Right Interactive CTA Button & HD House Keys Photo --}}
                <div class="flex items-center gap-5 flex-shrink-0">
                    <a href="{{ url('/paket-iklan') }}" class="group/btn relative overflow-hidden rounded-2xl bg-white text-blue-700 font-extrabold px-8 py-3.5 text-sm sm:text-base shadow-xl hover:shadow-2xl hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center gap-2.5">
                        {{-- Button Shine Sweep --}}
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-blue-200/40 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></span>
                        
                        <span class="relative z-10">Pasang Iklan</span>
                        <svg class="w-4 h-4 text-blue-700 relative z-10 group-hover/btn:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    <div class="hidden lg:block relative flex-shrink-0 w-32 h-32 md:w-36 md:h-36 rounded-2xl overflow-hidden ring-4 ring-white/30 shadow-2xl transform group-hover:scale-105 group-hover:rotate-2 transition-transform duration-500">
                        <img src="{{ asset('images/frontend/cta-house-keys.jpg') }}" alt="Kunci Rumah Kos SevenKost" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-2 left-2 right-2 text-center text-[10px] font-bold text-white bg-black/50 backdrop-blur-xs py-0.5 px-1.5 rounded-md">
                            Unit Cepat Terisi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-seven-layout>

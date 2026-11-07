<x-seven-layout title="SevenKost - Cari Kos Jadi Lebih Mudah | Platform Kos Terpercaya">

    {{-- 1. HERO SECTION (Vibrant Blue Gradient, Building & Floating Search) --}}
    <section class="relative bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white overflow-hidden pt-8 pb-16 lg:pt-14 lg:pb-24">
        {{-- Background Glow Accents --}}
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-10 w-[500px] h-[500px] bg-sky-400/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                {{-- Hero Left Content --}}
                <div class="lg:col-span-7 space-y-5 text-center lg:text-left">
                    {{-- Verified Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/95 text-slate-800 text-xs font-bold shadow-lg border border-white/60">
                        <span class="flex h-4 w-4 rounded-full bg-emerald-500 items-center justify-center text-white">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        <span>Terpercaya & Aman</span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.15]">
                        Cari Kos Jadi <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">
                            Lebih Mudah
                        </span>
                    </h1>

                    <p class="text-blue-100 text-base sm:text-lg max-w-xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        Temukan kos terbaik di lokasi strategis, dengan harga bersahabat.
                    </p>
                </div>

                {{-- Hero Right: Modern Building Facade --}}
                <div class="lg:col-span-5 relative hidden lg:block">
                    <div class="relative mx-auto rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20 group">
                        <img 
                            src="{{ asset('images/frontend/hero-building.png') }}" 
                            alt="SevenKost Building" 
                            class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs text-white">
                            <span class="bg-blue-600/90 backdrop-blur-md px-3 py-1 rounded-full font-semibold">
                                Listing Pilihan Nasional
                            </span>
                            <span class="bg-black/50 backdrop-blur-md px-2.5 py-1 rounded-full">
                                34 Provinsi
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Floating Search Bar Card --}}
            <div 
                x-data="{
                    location: '{{ request('kota', '') }}',
                    type: '{{ request('tipe', '') }}',
                    price: '{{ request('max_price', '') }}'
                }"
                class="mt-10 lg:mt-12 bg-white rounded-2xl sm:rounded-3xl shadow-2xl p-4 sm:p-5 text-slate-800 border border-slate-100"
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

    {{-- 2. PROMO CAROUSEL / HIGHLIGHT KOTA (Jakarta, Bandung, Yogyakarta) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-8 relative z-20">
        <div class="relative">
            {{-- Navigation Arrows (Decorative & Functional) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Promo 1: Jakarta --}}
                <div class="group relative overflow-hidden rounded-2xl shadow-lg border border-slate-200/50 block bg-slate-900 hover:shadow-xl transition-all duration-300">
                    <img 
                        src="{{ asset('images/frontend/promo-jakarta.png') }}" 
                        alt="Kos Terbaik di Jakarta" 
                        class="w-full h-44 object-cover group-hover:scale-105 transition duration-500"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/95 via-blue-900/70 to-transparent p-5 flex flex-col justify-between text-white">
                        <div class="space-y-1">
                            <h3 class="text-lg font-black leading-tight text-white group-hover:text-sky-300 transition-colors">
                                Kos Terbaik <br> di Jakarta
                            </h3>
                            <p class="text-xs text-blue-100 font-medium">Mulai dari <span class="font-bold text-white">Rp 750rb/bulan</span></p>
                        </div>
                        <div>
                            <a href="{{ url('/cari?q=Jakarta&kota=3171') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-sm">
                                <span>Lihat Sekarang</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Promo 2: Bandung --}}
                <div class="group relative overflow-hidden rounded-2xl shadow-lg border border-slate-200/50 block bg-slate-900 hover:shadow-xl transition-all duration-300">
                    <img 
                        src="{{ asset('images/frontend/promo-bandung.png') }}" 
                        alt="Kos Strategis di Bandung" 
                        class="w-full h-44 object-cover group-hover:scale-105 transition duration-500"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-sky-900/95 via-blue-900/70 to-transparent p-5 flex flex-col justify-between text-white">
                        <div class="space-y-1">
                            <h3 class="text-lg font-black leading-tight text-white group-hover:text-sky-300 transition-colors">
                                Kos Strategis <br> di Bandung
                            </h3>
                            <p class="text-xs text-blue-100 font-medium">Mulai dari <span class="font-bold text-white">Rp 600rb/bulan</span></p>
                        </div>
                        <div>
                            <a href="{{ url('/cari?q=Bandung&kota=3273') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-sm">
                                <span>Lihat Sekarang</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Promo 3: Yogyakarta --}}
                <div class="group relative overflow-hidden rounded-2xl shadow-lg border border-slate-200/50 block bg-slate-900 hover:shadow-xl transition-all duration-300">
                    <img 
                        src="{{ asset('images/frontend/promo-yogyakarta.png') }}" 
                        alt="Kos Nyaman di Yogyakarta" 
                        class="w-full h-44 object-cover group-hover:scale-105 transition duration-500"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-950/95 via-blue-900/70 to-transparent p-5 flex flex-col justify-between text-white">
                        <div class="space-y-1">
                            <h3 class="text-lg font-black leading-tight text-white group-hover:text-sky-300 transition-colors">
                                Kos Nyaman <br> di Yogyakarta
                            </h3>
                            <p class="text-xs text-blue-100 font-medium">Mulai dari <span class="font-bold text-white">Rp 650rb/bulan</span></p>
                        </div>
                        <div>
                            <a href="{{ url('/cari?q=Yogyakarta&kota=3471') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition shadow-sm">
                                <span>Lihat Sekarang</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pagination Dots Underneath --}}
            <div class="flex items-center justify-center gap-2 mt-5">
                <span class="w-6 h-2 rounded-full bg-blue-600 transition-all"></span>
                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
            </div>
        </div>
    </section>

    {{-- 3. KOS UNGGULAN (4 CARDS GRID) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
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
                    'image' => asset('images/frontend/kost-1.png'),
                ],
                [
                    'title' => 'Kost Putra Mandiri Jaya',
                    'slug' => 'kost-putra-mandiri-jaya',
                    'location' => 'Sukajadi, Bandung',
                    'price' => 'Rp 850.000',
                    'rating' => '4.7',
                    'reviews' => '98',
                    'image' => asset('images/frontend/kost-2.png'),
                ],
                [
                    'title' => 'Kost Campur Harmoni',
                    'slug' => 'kost-campur-harmoni',
                    'location' => 'Depok, Jawa Barat',
                    'price' => 'Rp 900.000',
                    'rating' => '4.8',
                    'reviews' => '75',
                    'image' => asset('images/frontend/kost-3.png'),
                ],
                [
                    'title' => 'Kost Putri Srikandi',
                    'slug' => 'kost-putri-srikandi',
                    'location' => 'Tegalsari, Surabaya',
                    'price' => 'Rp 750.000',
                    'rating' => '4.7',
                    'reviews' => '64',
                    'image' => asset('images/frontend/kost-4.png'),
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredKosts as $index => $kost)
                @php
                    $displayImg = $kost['image'] ?: ($defaultFeatured[$index % 4]['image'] ?? asset('images/frontend/kost-1.png'));
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

                        {{-- Wishlist Button --}}
                        <button type="button" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 hover:bg-white text-slate-700 hover:text-red-500 flex items-center justify-center shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
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
                            <button type="button" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 hover:bg-white text-slate-700 flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
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
                <span class="inline-block px-4 py-1 rounded-full bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Jakarta
                </span>
            </a>

            {{-- Bandung --}}
            <a href="{{ url('/cari?q=Bandung&kota=3273') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-bandung.png') }}" alt="Bandung" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 rounded-full bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Bandung
                </span>
            </a>

            {{-- Yogyakarta --}}
            <a href="{{ url('/cari?q=Yogyakarta&kota=3471') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-yogyakarta.png') }}" alt="Yogyakarta" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 rounded-full bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Yogyakarta
                </span>
            </a>

            {{-- Surabaya --}}
            <a href="{{ url('/cari?q=Surabaya&kota=3578') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-surabaya.png') }}" alt="Surabaya" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 rounded-full bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
                    Surabaya
                </span>
            </a>

            {{-- Depok --}}
            <a href="{{ url('/cari?q=Depok&kota=3276') }}" class="group block rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition text-center bg-white p-2">
                <div class="h-16 rounded-xl overflow-hidden bg-slate-100 mb-2">
                    <img src="{{ asset('images/frontend/city-depok.png') }}" alt="Depok" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                </div>
                <span class="inline-block px-4 py-1 rounded-full bg-slate-50 group-hover:bg-blue-600 text-slate-700 group-hover:text-white text-xs font-bold transition">
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
                <span class="inline-block px-3 py-1 rounded-full bg-white group-hover:bg-blue-600 text-blue-600 group-hover:text-white text-xs font-bold transition shadow-xs">
                    Lainnya &rarr;
                </span>
            </a>
        </div>
    </section>

    {{-- 5. TWO BANNERS (MARKETPLACE & LAYANAN MITRA) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Banner 1: Marketplace Barang Kos --}}
            <div id="marketplace" class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-100/90 via-sky-50 to-white p-6 sm:p-8 border border-blue-200/60 shadow-sm flex flex-col justify-between">
                <div class="space-y-2 max-w-xs relative z-10">
                    <div class="flex items-center gap-2 text-blue-700 font-extrabold text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Marketplace Barang Kos</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Lengkapi kebutuhan kosmu dengan berbagai barang berkualitas.
                    </p>
                    <div class="pt-3">
                        <a href="{{ url('/cari?kategori=marketplace') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                            <span>Belanja Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <div class="mt-4 sm:mt-0 sm:absolute sm:right-4 sm:bottom-4 w-48 sm:w-56 pointer-events-none">
                    <img src="{{ asset('images/frontend/marketplace-items.png') }}" alt="Marketplace Barang Kos" class="w-full object-contain">
                </div>
            </div>

            {{-- Banner 2: Layanan Mitra (Laundry, Kebersihan, Katering) --}}
            <div id="layanan" class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-100/90 via-sky-50 to-white p-6 sm:p-8 border border-blue-200/60 shadow-sm flex flex-col justify-between">
                {{-- Coming Soon Badge --}}
                <div class="absolute top-4 right-4 inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-blue-600 to-sky-500 text-white text-[11px] font-bold shadow-sm">
                    Coming Soon
                </div>

                <div class="space-y-2 max-w-xs relative z-10">
                    <div class="flex items-center gap-2 text-blue-700 font-extrabold text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>Layanan Mitra</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Layanan pendukung untuk kos lebih nyaman dan terkelola baik.
                    </p>
                    <div class="pt-3">
                        <a href="#layanan" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                            <span>Lihat Layanan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Service Circles: Laundry, Kebersihan, Katering --}}
                <div class="mt-6 sm:mt-0 sm:absolute sm:right-6 sm:bottom-6 flex items-center gap-3">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-xl mb-1">
                            🧺
                        </div>
                        <span class="text-[10px] font-bold text-slate-700">Laundry</span>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-xl mb-1">
                            🧹
                        </div>
                        <span class="text-[10px] font-bold text-slate-700">Kebersihan</span>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-white shadow-md border border-slate-100 flex items-center justify-center text-xl mb-1">
                            🍱
                        </div>
                        <span class="text-[10px] font-bold text-slate-700">Katering</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. CTA BANNER (PASANG IKLAN KOSMU SEKARANG!) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl bg-gradient-to-r from-blue-800 via-blue-600 to-sky-500 text-white p-6 sm:p-10 relative overflow-hidden shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            {{-- Background Subtle Circles --}}
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            {{-- Left Character Illustration --}}
            <div class="hidden sm:block flex-shrink-0 w-28 md:w-36">
                <img src="{{ asset('images/frontend/cta-person.png') }}" alt="Mitra Pemilik Kos" class="w-full object-contain drop-shadow-md">
            </div>

            {{-- Center Text --}}
            <div class="space-y-2 text-center md:text-left flex-1">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight">
                    Pasang Iklan Kosmu <span class="text-cyan-300">Sekarang!</span>
                </h2>
                <p class="text-xs sm:text-sm text-blue-100 max-w-lg">
                    Jangkau lebih banyak calon penyewa dengan mudah.
                </p>
            </div>

            {{-- Right CTA Button & Mini House --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                <a href="{{ url('/paket-iklan') }}" class="px-7 py-3 rounded-full bg-white hover:bg-blue-50 text-blue-700 text-xs sm:text-sm font-extrabold shadow-lg transition transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                    <span>Pasang Iklan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <div class="hidden lg:block w-20 flex-shrink-0">
                    <img src="{{ asset('images/frontend/cta-house.png') }}" alt="Rumah Kos" class="w-full object-contain drop-shadow">
                </div>
            </div>
        </div>
    </section>

</x-seven-layout>

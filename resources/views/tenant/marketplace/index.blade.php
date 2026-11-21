<x-tenant-layout title="Marketplace Anak Kos (COD)">

    <div class="space-y-4">

        {{-- 1. HEADER HERO MARKETPLACE --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-1" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                        Marketplace Anak Kos
                    </h1>
                    <p class="text-xs text-blue-100 font-medium">
                        Jual beli perlengkapan kos &bull; 100% COD
                    </p>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <a href="{{ route('tenant.marketplace.my-items') }}" class="px-2.5 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs shadow-2xs transition active:scale-95 flex items-center gap-1 border border-white/20">
                        <svg class="w-3.5 h-3.5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Barang Saya</span>
                    </a>
                    <a href="{{ route('tenant.marketplace.create') }}" class="px-3 py-1.5 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-black text-xs shadow-xs transition active:scale-95 flex items-center gap-1 border border-white/20">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Jual</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- FLASH SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-2xs">
                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-medium text-[11px] leading-relaxed">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- 3. BANNER PROMOSI KOMERSIAL SLIDER --}}
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
            class="relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-200/90 shadow-2xs"
            x-data="{
                activeBanner: 0,
                totalBanners: {{ count($mktBanners) }},
                timer: null,
                start() {
                    this.timer = setInterval(() => { this.next(); }, 5000);
                },
                stop() { clearInterval(this.timer); },
                next() { this.activeBanner = (this.activeBanner + 1) % this.totalBanners; },
                prev() { this.activeBanner = (this.activeBanner - 1 + this.totalBanners) % this.totalBanners; }
            }"
            x-init="start()"
            @mouseenter="stop()"
            @mouseleave="start()"
        >
            <div class="relative w-full h-28 sm:h-32 overflow-hidden">
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
                            <img src="{{ $bItem['img'] }}" alt="{{ $bItem['alt'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-black/60 text-white text-[10px] font-bold uppercase tracking-wider backdrop-blur-xs">
                                Iklan Sponsor
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if(count($mktBanners) > 1)
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-black/40 backdrop-blur-xs">
                    @foreach($mktBanners as $idx => $bItem)
                        <button 
                            type="button" 
                            @click="activeBanner = {{ $idx }}" 
                            class="h-1 rounded-full transition-all"
                            :class="activeBanner === {{ $idx }} ? 'w-4 bg-white' : 'w-1 bg-white/50'"
                            aria-label="Slide {{ $idx + 1 }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 4. PENCARIAN & FILTER CEPAT --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-2xs space-y-2.5">
            <form action="{{ route('tenant.marketplace.index') }}" method="GET" class="space-y-2">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif

                {{-- Search Bar --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}" 
                        placeholder="Cari kasur, kipas, meja kos..." 
                        class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400 bg-slate-50 focus:bg-white transition"
                    >
                    @if(request('q'))
                        <a href="{{ route('tenant.marketplace.index', request()->except('q')) }}" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>

                {{-- Row Filter Kota & Urutkan --}}
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <select 
                        name="kota" 
                        onchange="this.form.submit()" 
                        class="rounded-xl border border-slate-200 text-xs py-2 px-2.5 text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white truncate"
                    >
                        <option value="semua">Semua Kota</option>
                        <option value="Jakarta" {{ request('kota') === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                        <option value="Depok" {{ request('kota') === 'Depok' ? 'selected' : '' }}>Depok</option>
                        <option value="Bandung" {{ request('kota') === 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        <option value="Yogyakarta" {{ request('kota') === 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                        <option value="Surabaya" {{ request('kota') === 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                    </select>

                    <select 
                        name="urutkan" 
                        onchange="this.form.submit()" 
                        class="rounded-xl border border-slate-200 text-xs py-2 px-2.5 text-slate-700 font-medium focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white truncate"
                    >
                        <option value="terbaru" {{ request('urutkan') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="termurah" {{ request('urutkan') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                        <option value="termahal" {{ request('urutkan') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                        <option value="populer" {{ request('urutkan') === 'populer' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- 5. KATEGORI PILLS (SCROLL HORIZONTAL) --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs scrollbar-none">
            @foreach($categories as $catKey => $catData)
                @php
                    $isActive = ($catKey === 'semua' && !request('kategori')) || request('kategori') === $catKey;
                @endphp
                <a 
                    href="{{ route('tenant.marketplace.index', array_merge(request()->except('kategori'), ['kategori' => $catKey])) }}"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap {{ $isActive ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                >
                    <span>{{ $catData['icon'] }}</span>
                    <span>{{ $catData['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- FLASH MESSAGE SUKSES --}}
        @if(session('success'))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- 6. KATALOG PRODUK GRID 2 KOLOM --}}
        @if($items->count() > 0)
            <div class="grid grid-cols-2 gap-3">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl border border-slate-200/90 hover:border-blue-300 shadow-2xs transition overflow-hidden flex flex-col justify-between group">
                        <div>
                            {{-- Foto Barang & Badges --}}
                            <div class="relative w-full aspect-square bg-slate-100 overflow-hidden">
                                <a href="{{ route('tenant.marketplace.show', $item->slug) }}" class="block w-full h-full">
                                    <img 
                                        src="{{ $item->primary_image_url }}" 
                                        alt="{{ $item->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                        loading="lazy"
                                    >
                                </a>

                                {{-- Badge COD --}}
                                <div class="absolute top-2 left-2">
                                    <span class="px-1.5 py-0.5 rounded bg-emerald-600 text-white font-black text-[10px] shadow-xs">
                                        COD
                                    </span>
                                </div>

                                {{-- Badge Kondisi --}}
                                <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded bg-black/60 backdrop-blur-xs text-white font-medium text-[10px]">
                                    {{ $item->condition_label }}
                                </div>
                            </div>

                            {{-- Info Barang --}}
                            <div class="p-2.5 space-y-1">
                                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider block truncate">
                                    {{ $item->category_label }}
                                </span>

                                <h3 class="font-bold text-xs text-slate-900 group-hover:text-blue-600 transition line-clamp-2 leading-snug">
                                    <a href="{{ route('tenant.marketplace.show', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <div class="text-sm font-black text-blue-700 pt-0.5">
                                    {{ $item->formatted_price }}
                                </div>

                                <div class="flex items-center gap-1 text-[11px] text-slate-500 pt-0.5 truncate">
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <span class="truncate">{{ $item->meetup_location }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Kartu Barang: Info Penjual & Tombol Aksi --}}
                        <div class="p-2 pt-0">
                            <a 
                                href="{{ route('tenant.marketplace.show', $item->slug) }}" 
                                class="w-full inline-flex items-center justify-center py-1.5 px-2 rounded-xl bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 font-bold text-xs border border-slate-200 hover:border-blue-200 transition active:scale-95 text-center"
                            >
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pt-2">
                {{ $items->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto border border-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-sm text-slate-900">Belum Ada Barang di Kategori Ini</h4>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto">Jadilah yang pertama memposting barang perlengkapan kamar kos Anda.</p>
                </div>
                <div class="pt-1">
                    <a href="{{ route('tenant.marketplace.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Posting Barang Sekarang</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

</x-tenant-layout>

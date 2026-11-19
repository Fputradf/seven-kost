<x-tenant-layout title="Layanan Kebutuhan Kos" :backUrl="route('tenant.dashboard')">

    {{-- Root Container with Alpine.js State --}}
    <div 
        x-data="{
            activeCategory: 'all',
            orderModalOpen: false,
            alertNotTenant: false,
            alertNoActiveStay: false,
            orderSuccess: false,
            hasActiveStay: {{ $hasActiveBooking ? 'true' : 'false' }},
            activeBookingsCount: {{ $activeBookings->count() }},
            selectedBookingId: {{ $activeBooking?->id ?? 'null' }},
            selectedService: 'laundry',
            selectedServiceName: 'Seven Laundry Kos',
            selectedPackageName: 'Laundry Kiloan Reguler (2-3 Hari)',
            selectedPackagePrice: 7000,
            selectedPackageUnit: '/kg',
            selectedPartnerName: '{{ addslashes($services[0]['local_partner']['partner_name'] ?? '') }}',
            kostAddress: '{{ addslashes($tenantAddress ?? '') }}',
            roomNumber: '{{ addslashes($tenantRoomName ?? '') }}',
            occupantName: '{{ addslashes($tenantOccupantName ?? (auth()->user()?->name ?? '')) }}',
            occupantPhone: '{{ addslashes($tenantOccupantPhone ?? (auth()->user()?->phone ?? '')) }}',
            orderDate: '{{ date('Y-m-d') }}',
            orderTime: '10:00',
            orderNotes: '',

            openOrderModal(serviceId, serviceName, defaultPkgName, defaultPrice, defaultUnit, partnerName = '') {
                if (!this.hasActiveStay) {
                    this.alertNoActiveStay = true;
                    return;
                }
                this.selectedService = serviceId;
                this.selectedServiceName = serviceName;
                this.selectedPackageName = defaultPkgName;
                this.selectedPackagePrice = defaultPrice;
                this.selectedPackageUnit = defaultUnit;
                this.selectedPartnerName = partnerName;
                this.orderSuccess = false;
                this.orderModalOpen = true;
            },

            submitOrder() {
                this.orderSuccess = true;
                setTimeout(() => {
                    this.orderModalOpen = false;
                    this.orderSuccess = false;
                }, 2500);
            }
        }"
        @keydown.escape.window="orderModalOpen = false; alertNotTenant = false; alertNoActiveStay = false"
        class="pb-20"
    >

        {{-- COMPACT MOBILE LOCATION / STAY BAR (TRUE MOBILE SIZE) --}}
        <div class="pt-0.5">
            @if($hasActiveBooking)
                <div class="p-3 rounded-2xl bg-white border border-emerald-200/90 shadow-2xs space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 truncate">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span class="text-xs font-black text-slate-900 truncate">
                                {{ $tenantKostName }} &bull; {{ $tenantRoomName }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0">
                            Kamar Aktif Terverifikasi
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                        <div class="truncate">
                            Penghuni: <strong class="text-slate-700">{{ $tenantOccupantName }}</strong> &bull; <span class="text-emerald-600 font-medium">Mitra Siap Melayani Hari Ini</span>
                        </div>
                        @if($activeBookings->count() > 1)
                            <div class="flex items-center gap-1 shrink-0 ml-2">
                                <span class="text-[10px] font-semibold text-slate-500">Pilih Kamar Tujuan Layanan:</span>
                                @foreach($activeBookings as $bOption)
                                    @php $isSelected = $activeBooking && $activeBooking->id === $bOption->id; @endphp
                                    <a href="{{ route('services.index', ['kamar_id' => $bOption->id]) }}" 
                                       class="px-2 py-0.5 rounded text-[10px] font-bold transition {{ $isSelected ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $bOption->kost->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-3 rounded-2xl bg-white border border-blue-200/90 shadow-2xs space-y-1.5 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                            <span>Jangkauan Berbasis Lokasi Kos</span>
                        </span>
                        <a href="{{ route('search') }}" class="text-[11px] font-bold text-blue-600 hover:underline shrink-0 flex items-center gap-1">
                            <span>Cari Kamar Kos</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                        <span class="truncate">Layanan Mitra Khusus Penghuni Kamar Aktif &bull; <strong class="text-blue-600 font-semibold">Mitra Siap Melayani Hari Ini</strong></span>
                        <form method="GET" action="{{ route('services.index') }}" class="flex items-center gap-1 shrink-0">
                            <label for="kota-filter" class="text-[10px] font-semibold text-slate-600">Cek Mitra di:</label>
                            <select id="kota-filter" name="kota" onchange="this.form.submit()" class="text-[10px] py-0.5 px-1.5 rounded-lg border-slate-200 bg-slate-50 text-slate-700">
                                @foreach($regionalPartners as $rKey => $rVal)
                                    <option value="{{ $rVal['badge_city'] }}" {{ $cityKey === $rKey ? 'selected' : '' }}>
                                        {{ $rVal['city_label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. CATEGORY FILTER TABS --}}
        <div class="pt-2">
            <div class="bg-white rounded-2xl p-2 shadow-2xs border border-slate-200/80 flex items-center gap-1.5 overflow-x-auto scrollbar-none">
                <button 
                    @click="activeCategory = 'all'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all"
                    :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    Semua Layanan
                </button>
                <button 
                    @click="activeCategory = 'laundry'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all flex items-center gap-2"
                    :class="activeCategory === 'laundry' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Laundry Kiloan & Satuan</span>
                </button>
                <button 
                    @click="activeCategory = 'cleaning'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all flex items-center gap-2"
                    :class="activeCategory === 'cleaning' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>Kebersihan Kamar & Toilet</span>
                </button>
                <button 
                    @click="activeCategory = 'catering'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all flex items-center gap-2"
                    :class="activeCategory === 'catering' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Katering Harian (Coming Soon)</span>
                </button>
                <button 
                    @click="activeCategory = 'maintenance'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap transition-all flex items-center gap-2"
                    :class="activeCategory === 'maintenance' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                    <span>Teknisi & Servis AC</span>
                </button>
            </div>
        </div>

        {{-- COMMERCIAL AD BANNER SLIDER (BR-11: PENAYANGAN BANNER BRAND & LAYANAN MITRA) --}}
        @php
            $svcBanners = [];
            if (isset($banners) && $banners->count() > 0) {
                foreach ($banners as $b) {
                    $svcBanners[] = [
                        'img' => $b->image_url,
                        'link' => $b->target_url ?: route('ads.packages.index', ['tab' => 'banner']),
                        'alt' => $b->title,
                    ];
                }
            }
            if (empty($svcBanners)) {
                $svcBanners = [
                    ['img' => asset('images/frontend/banner-jakarta-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Promo Layanan Laundry Kos'],
                    ['img' => asset('images/frontend/banner-bandung-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Promo Bersih-Bersih Kos'],
                    ['img' => asset('images/frontend/banner-yogyakarta-wide.jpg'), 'link' => route('ads.packages.index', ['tab' => 'banner']), 'alt' => 'Promo Teknisi AC Kos'],
                ];
            }
        @endphp

        <div class="pt-2">
            <div 
                class="group/banner relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-200/80 shadow-xs"
                x-data="{
                    activeBanner: 0,
                    totalBanners: {{ count($svcBanners) }},
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
                    @foreach($svcBanners as $idx => $bItem)
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
                @if(count($svcBanners) > 1)
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
                @if(count($svcBanners) > 1)
                    <div class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-xs">
                        @foreach($svcBanners as $idx => $bItem)
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

        {{-- 3. SERVICE CATALOG CARDS FEED (COMPACT MOBILE NATIVE) --}}
        <div class="pt-2 space-y-3">
            <div class="space-y-3">
                @foreach($services as $svc)
                    @php
                        $isAvail = $svc['is_available'] ?? true;
                    @endphp
                    <div 
                        id="{{ $svc['id'] }}"
                        x-show="activeCategory === 'all' || activeCategory === '{{ $svc['category'] }}'"
                        x-transition.duration.300ms
                        class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs hover:shadow-md transition-all duration-200 p-3.5 space-y-3 {{ !$isAvail ? 'opacity-95' : '' }}"
                    >
                        {{-- Top Section: Side Thumbnail + Title, Rating & Starting Price --}}
                        <div class="flex gap-3 items-start">
                            {{-- Thumbnail Foto Layanan --}}
                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden shrink-0 bg-slate-100 border border-slate-100">
                                <img 
                                    src="{{ $svc['image'] }}" 
                                    alt="{{ $svc['title'] }}" 
                                    class="w-full h-full object-cover {{ !$isAvail ? 'filter grayscale-25 opacity-90' : '' }}"
                                >
                                <div class="absolute bottom-1 left-1 flex items-center gap-0.5 bg-black/60 backdrop-blur-xs px-1.5 py-0.5 rounded-md text-[10px] font-bold text-amber-300">
                                    <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>{{ $svc['rating'] }}</span>
                                </div>
                            </div>

                            {{-- Info Singkat & Tarif --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 leading-snug">
                                    {{ $svc['title'] }}
                                </h3>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5 leading-relaxed">
                                    {{ $svc['description'] }}
                                </p>
                                <div class="mt-1.5 flex items-baseline gap-1">
                                    <span class="text-[10px] text-slate-400 font-medium">Tarif Mulai</span>
                                    <span class="text-sm sm:text-base font-black text-blue-600">
                                        Rp {{ number_format($svc['price_start'], 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-medium">{{ $svc['price_unit'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kelebihan Layanan (Compact Bullets) --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kelebihan Layanan:</div>
                            <div class="space-y-1 text-xs text-slate-700">
                                @foreach(array_slice($svc['features'], 0, 3) as $feat)
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span class="truncate text-[11px]">{{ $feat }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Pilihan Paket Menu / Layanan (Compact Rows) --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1.5">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pilihan Paket:</div>
                            <div class="space-y-1.5">
                                @foreach(array_slice($svc['packages'], 0, 3) as $pkg)
                                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                        <span class="font-medium text-slate-800 truncate">{{ $pkg['name'] }}</span>
                                        <strong class="text-blue-600 font-bold shrink-0 ml-2">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Mitra Lokal Sekitar Kos --}}
                        @if(isset($svc['local_partner']))
                            <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-100/90 flex items-center justify-between text-[11px] gap-2">
                                <span class="font-bold text-blue-950 flex items-center gap-1.5 truncate">
                                    <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <span class="truncate">{{ $svc['local_partner']['partner_name'] }}</span>
                                </span>
                                <span class="text-blue-700 font-semibold text-[10px] shrink-0 bg-blue-100/80 px-1.5 py-0.5 rounded">
                                    {{ $svc['local_partner']['distance_approx'] }}
                                </span>
                            </div>
                        @endif

                        {{-- Tombol Pesan Sekarang --}}
                        <div class="pt-1">
                            @if($isAvail)
                                @auth
                                    @if(auth()->user()->hasRole('penghuni'))
                                        <button 
                                            @click="openOrderModal(
                                                '{{ $svc['id'] }}', 
                                                '{{ addslashes($svc['title']) }}', 
                                                '{{ addslashes($svc['packages'][0]['name']) }}', 
                                                {{ $svc['packages'][0]['price'] }}, 
                                                '{{ addslashes($svc['packages'][0]['unit']) }}',
                                                '{{ addslashes($svc['local_partner']['partner_name'] ?? '') }}'
                                            )" 
                                            type="button" 
                                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition active:scale-98 flex items-center justify-center gap-1.5"
                                        >
                                            <span>Pesan Sekarang</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    @else
                                        <button 
                                            @click="alertNotTenant = true" 
                                            type="button" 
                                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition active:scale-98 flex items-center justify-center gap-1.5"
                                        >
                                            <span>Pesan Sekarang</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    @endif
                                @else
                                    <a 
                                        href="{{ route('login') }}" 
                                        class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition active:scale-98 flex items-center justify-center gap-1.5"
                                    >
                                        <span>Pesan Sekarang</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endauth
                            @else
                                <button 
                                    type="button" 
                                    disabled 
                                    class="w-full py-2.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80 text-xs sm:text-sm font-bold cursor-not-allowed flex items-center justify-center gap-1 shadow-2xs"
                                >
                                    Segera Hadir
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 4. ALUR & CARA KERJA PEMESANAN (HOW IT WORKS) - INTERAKTIF & ANIMATED --}}
        <div 
            x-data="{
                activeStep: 1,
                autoPlay: true,
                timer: null,
                init() {
                    this.timer = setInterval(() => {
                        if (this.autoPlay) {
                            this.activeStep = this.activeStep >= 4 ? 1 : this.activeStep + 1;
                        }
                    }, 4000);
                },
                setStep(num) {
                    this.activeStep = num;
                    this.autoPlay = false;
                }
            }"
            class="space-y-3 pt-6 border-t border-slate-200/80"
        >
            <div class="text-center space-y-1">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Proses Cepat & Praktis</div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Bagaimana Cara Pesan Layanan?</h2>
                <p class="text-xs text-slate-600">4 langkah mudah untuk menikmati layanan langsung ke kamar kosmu.</p>
            </div>

            <div class="space-y-2 relative z-10">
                {{-- Step 1 --}}
                <div 
                    @click="setStep(1)"
                    class="cursor-pointer rounded-2xl p-3 border transition-all duration-200 flex items-start gap-3"
                    :class="activeStep === 1 
                        ? 'bg-white border-blue-500 shadow-md ring-1 ring-blue-500/20' 
                        : 'bg-white border-slate-200/80 shadow-2xs hover:bg-slate-50'"
                >
                    <div 
                        class="w-9 h-9 rounded-xl font-bold text-sm flex items-center justify-center shrink-0 transition-all duration-200"
                        :class="activeStep === 1 
                            ? 'bg-blue-600 text-white shadow-xs' 
                            : 'bg-blue-50 text-blue-600'"
                    >
                        1
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900">Pilih Layanan & Paket</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider" :class="activeStep === 1 ? 'text-blue-600' : 'text-slate-400'">Langkah 1</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                            Tentukan kebutuhanmu, mulai dari laundry kiloan, deep clean kamar mandi, katering, atau servis AC.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div 
                    @click="setStep(2)"
                    class="cursor-pointer rounded-2xl p-3 border transition-all duration-200 flex items-start gap-3"
                    :class="activeStep === 2 
                        ? 'bg-white border-blue-500 shadow-md ring-1 ring-blue-500/20' 
                        : 'bg-white border-slate-200/80 shadow-2xs hover:bg-slate-50'"
                >
                    <div 
                        class="w-9 h-9 rounded-xl font-bold text-sm flex items-center justify-center shrink-0 transition-all duration-200"
                        :class="activeStep === 2 
                            ? 'bg-blue-600 text-white shadow-xs' 
                            : 'bg-blue-50 text-blue-600'"
                    >
                        2
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900">Atur Jadwal & Kamar</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider" :class="activeStep === 2 ? 'text-blue-600' : 'text-slate-400'">Langkah 2</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                            Isi alamat kos, nomor kamar, dan pilih jam kunjungan yang paling nyaman untuk Anda.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div 
                    @click="setStep(3)"
                    class="cursor-pointer rounded-2xl p-3 border transition-all duration-200 flex items-start gap-3"
                    :class="activeStep === 3 
                        ? 'bg-white border-blue-500 shadow-md ring-1 ring-blue-500/20' 
                        : 'bg-white border-slate-200/80 shadow-2xs hover:bg-slate-50'"
                >
                    <div 
                        class="w-9 h-9 rounded-xl font-bold text-sm flex items-center justify-center shrink-0 transition-all duration-200"
                        :class="activeStep === 3 
                            ? 'bg-blue-600 text-white shadow-xs' 
                            : 'bg-blue-50 text-blue-600'"
                    >
                        3
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900">Mitra Tiba di Kos</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider" :class="activeStep === 3 ? 'text-blue-600' : 'text-slate-400'">Langkah 3</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                            Mitra terverifikasi SevenKost tiba sesuai jadwal membawa peralatan lengkap dan seragam resmi.
                        </p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div 
                    @click="setStep(4)"
                    class="cursor-pointer rounded-2xl p-3 border transition-all duration-200 flex items-start gap-3"
                    :class="activeStep === 4 
                        ? 'bg-white border-emerald-500 shadow-md ring-1 ring-emerald-500/20' 
                        : 'bg-white border-slate-200/80 shadow-2xs hover:bg-slate-50'"
                >
                    <div 
                        class="w-9 h-9 rounded-xl font-bold text-sm flex items-center justify-center shrink-0 transition-all duration-200"
                        :class="activeStep === 4 
                            ? 'bg-emerald-600 text-white shadow-xs' 
                            : 'bg-emerald-50 text-emerald-600'"
                    >
                        4
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900">Selesai & Bayar Aman</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider" :class="activeStep === 4 ? 'text-emerald-600' : 'text-slate-400'">Langkah 4</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5">
                            Periksa hasil pengerjaan. Pembayaran fleksibel dapat dilakukan via transfer QRIS atau tunai langsung.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. MITRA REGISTRATION CALLOUT (SIGNATURE SAPPHIRE GRADIENT, 100% WHITE TEXT) --}}
        <div class="pt-2">
            <div class="relative overflow-hidden rounded-2xl text-white p-4 sm:p-5 shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1 max-w-xl">
                        <h3 class="text-base sm:text-lg font-bold text-white !text-white" style="color: #ffffff !important;">
                            Punya Usaha Laundry, Katering, atau Kebersihan?
                        </h3>
                        <p class="text-xs sm:text-sm text-white leading-relaxed font-normal">
                            Gabung jadi Mitra Resmi SevenKost dan dapatkan pesanan langsung dari ribuan penghuni kos aktif di sekitarmu.
                        </p>
                    </div>

                    <a 
                        href="https://wa.me/6281234567890?text=Halo%20Admin%20SevenKost%2C%20saya%20tertarik%20mendaftar%20menjadi%20Mitra%20Layanan%20di%20SevenKost." 
                        target="_blank" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-950/30 transition border border-white/20 active:scale-95 flex-shrink-0"
                    >
                        <span>Daftar Jadi Mitra</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- 7. ORDER MODAL INTERAKTIF (KHUSUS PENGHUNI KOS TERDAFTAR) --}}
        @auth
            @if(auth()->user()->hasRole('penghuni'))
                <div 
                    x-show="orderModalOpen" 
                    x-transition.opacity 
                    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
                    style="display: none;"
                >
                    <div 
                        @click.outside="orderModalOpen = false" 
                        class="bg-white rounded-3xl max-w-md w-full p-4 sm:p-5 shadow-2xl space-y-4 text-slate-800 animate-in fade-in zoom-in-95 duration-200 relative overflow-y-auto max-h-[90vh]"
                    >
                        {{-- Notifikasi Sukses Pemesanan --}}
                        <div 
                            x-show="orderSuccess" 
                            x-transition 
                            class="absolute inset-0 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center space-y-3 z-20"
                            style="display: none;"
                        >
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h4 class="text-lg sm:text-xl font-bold text-slate-900">Pesanan Berhasil Dikirim!</h4>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-xs leading-relaxed">
                                Jadwal kunjungan telah diteruskan ke mitra SevenKost terdekat. Mitra akan segera menuju ke kamar kos Anda sesuai jadwal.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Form Pemesanan Layanan</span>
                                <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-0.5" x-text="selectedServiceName"></h3>
                            </div>
                            <button @click="orderModalOpen = false" class="text-slate-400 hover:text-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitOrder()" class="space-y-4">
                            {{-- Detail Pilihan Paket --}}
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between gap-3">
                                <div>
                                    <span class="text-xs text-slate-500 font-medium block">Paket Terpilih:</span>
                                    <strong class="text-sm sm:text-base font-bold text-slate-900" x-text="selectedPackageName"></strong>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-base sm:text-lg font-black text-blue-600" x-text="'Rp ' + Number(selectedPackagePrice).toLocaleString('id-ID')"></span>
                                    <span class="text-xs text-slate-500 block" x-text="selectedPackageUnit"></span>
                                </div>
                            </div>

                            {{-- Mitra Pelaksana --}}
                            <template x-if="selectedPartnerName">
                                <div class="px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Mitra Pelaksana Terdekat:</span>
                                    <span class="font-bold text-blue-900 flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        <span x-text="selectedPartnerName" class="truncate"></span>
                                    </span>
                                </div>
                            </template>

                            @if($activeBookings->count() > 1)
                                {{-- Pemilihan Kamar Aktif Jika Multi-Kamar --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kamar Kos Tujuan Layanan <span class="text-rose-500">*</span></label>
                                    <select 
                                        @change="
                                            let selOpt = $event.target.selectedOptions[0];
                                            kostAddress = selOpt.dataset.address;
                                            roomNumber = selOpt.dataset.room;
                                            occupantName = selOpt.dataset.occupant;
                                            occupantPhone = selOpt.dataset.phone;
                                        "
                                        class="w-full text-xs sm:text-sm font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
                                    >
                                        @foreach($activeBookings as $bItem)
                                            @php
                                                $bKostName = $bItem->kost->name;
                                                $bReg = $bItem->kost->regency?->name ?? '';
                                                $bRoomName = $bItem->room?->name ?? 'Kamar Hunian';
                                                $bAddr = $bItem->kost->address ?: ($bKostName.', '.$bReg);
                                                $bOccName = $bItem->tenant_name ?: auth()->user()->name;
                                                $bOccPhone = $bItem->tenant_phone ?: (auth()->user()->phone ?? '');
                                            @endphp
                                            <option 
                                                value="{{ $bItem->id }}" 
                                                data-address="{{ $bAddr }}" 
                                                data-room="{{ $bRoomName }}" 
                                                data-occupant="{{ $bOccName }}" 
                                                data-phone="{{ $bOccPhone }}"
                                                {{ ($activeBooking && $activeBooking->id === $bItem->id) ? 'selected' : '' }}
                                            >
                                                {{ $bKostName }} ({{ $bRoomName }}) - {{ $bReg }} [Penghuni: {{ $bOccName }}]
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Alamat Kos & No Kamar --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Kos & Alamat Lengkap <span class="text-rose-500">*</span></label>
                                    @if($hasActiveBooking)
                                        <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Data Kamar Aktif
                                        </span>
                                    @endif
                                </div>
                                <input 
                                    type="text" 
                                    x-model="kostAddress" 
                                    placeholder="Contoh: Seven Kost Melati Residence, Jl. Fatmawati No. 45" 
                                    required 
                                    class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Kamar <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        x-model="roomNumber" 
                                        placeholder="Contoh: Kamar 204 (Lt. 2)" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Waktu Kunjungan</label>
                                    <select x-model="orderTime" class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white">
                                        <option value="09:00">Pagi (09:00 WIB)</option>
                                        <option value="11:30">Siang (11:30 WIB)</option>
                                        <option value="14:00">Siang (14:00 WIB)</option>
                                        <option value="16:30">Sore (16:30 WIB)</option>
                                        <option value="19:00">Malam (19:00 WIB)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Kontak Penghuni di Lokasi Kamar --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Penghuni di Kamar <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        x-model="occupantName" 
                                        placeholder="Nama yang menyambut di kamar" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">No. WhatsApp Penghuni <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="tel" 
                                        x-model="occupantPhone" 
                                        placeholder="081234567890" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Layanan</label>
                                <input 
                                    type="date" 
                                    x-model="orderDate" 
                                    min="{{ date('Y-m-d') }}" 
                                    class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan Tambahan (Opsional)</label>
                                <textarea 
                                    x-model="orderNotes" 
                                    rows="2" 
                                    placeholder="Contoh: Mohon ketuk kamar dulu, ada pakaian jas butuh dry clean..." 
                                    class="w-full text-sm rounded-xl border-slate-200 p-3 leading-relaxed"
                                ></textarea>
                            </div>

                            {{-- Tombol Konfirmasi Pesanan --}}
                            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                                <button 
                                    type="button" 
                                    @click="orderModalOpen = false" 
                                    class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="w-full sm:flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 text-center transition"
                                >
                                    Konfirmasi & Pesan Layanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        {{-- MODAL PERINGATAN BUKAN PENGHUNI KOS (UNTUK PEMILIK/ADMIN) --}}
        <div 
            x-show="alertNotTenant" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="alertNotTenant = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 text-center space-y-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-xs border border-amber-200">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Khusus Akun Penghuni Kos</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                        Pemesanan layanan harian (laundry, cleaning, teknisi) hanya dapat dilakukan oleh akun yang terdaftar sebagai penghuni kos aktif di SevenKost.
                    </p>
                </div>
                <button 
                    type="button" 
                    @click="alertNotTenant = false" 
                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition"
                >
                    Saya Mengerti
                </button>
            </div>
        </div>

        {{-- MODAL PERINGATAN BELUM ADA KAMAR AKTIF --}}
        <div 
            x-show="alertNoActiveStay" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="alertNoActiveStay = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 text-center space-y-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto shadow-xs border border-blue-200">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Perlu Kamar Kos Aktif</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                        Layanan mitra SevenKost (laundry antar-jemput, kebersihan kamar, dan servis AC) dikerjakan langsung ke kamar kos Anda. Anda harus memiliki sewa kamar aktif terlebih dahulu agar mitra terdekat di sekitar kos dapat ditugaskan.
                    </p>
                </div>
                <div class="space-y-2 pt-2">
                    <a 
                        href="{{ route('search') }}" 
                        class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition block text-center"
                    >
                        Cari & Sewa Kos Sekarang
                    </a>
                    <button 
                        type="button" 
                        @click="alertNoActiveStay = false" 
                        class="w-full py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs sm:text-sm transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>

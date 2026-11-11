<x-seven-layout :title="'Layanan Kebutuhan Kos - Seven Kost'">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/80 backdrop-blur-md border-b border-slate-100 py-3.5 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Layanan Mitra Kos</span>
            </nav>
        </div>
    </div>

    {{-- Root Container with Alpine.js State --}}
    <div 
        x-data="{
            activeCategory: 'all',
            orderModalOpen: false,
            alertNotTenant: false,
            orderSuccess: false,
            selectedService: 'laundry',
            selectedServiceName: 'Seven Laundry Kos',
            selectedPackageName: 'Laundry Kiloan Reguler (2-3 Hari)',
            selectedPackagePrice: 7000,
            selectedPackageUnit: '/kg',
            kostAddress: '',
            roomNumber: '',
            orderDate: '{{ date('Y-m-d') }}',
            orderTime: '10:00',
            orderNotes: '',

            openOrderModal(serviceId, serviceName, defaultPkgName, defaultPrice, defaultUnit) {
                this.selectedService = serviceId;
                this.selectedServiceName = serviceName;
                this.selectedPackageName = defaultPkgName;
                this.selectedPackagePrice = defaultPrice;
                this.selectedPackageUnit = defaultUnit;
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
        @keydown.escape.window="orderModalOpen = false; alertNotTenant = false"
        class="pb-20"
    >

        {{-- Page Header: Modern Card Gradient (Sesuai Dashboard Home Page) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-5 pb-2">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-4 sm:p-5 md:p-6 shadow-md shadow-blue-950/15 border border-white/15">
                {{-- Decorative background glow --}}
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-blue-400/15 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -top-6 right-1/4 w-28 h-28 bg-sky-300/15 rounded-full blur-lg pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5 max-w-2xl">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white leading-tight">
                            Layanan Kebutuhan <span class="text-sky-300">Kos</span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                            Pilihan jasa harian terpercaya mulai dari laundry antar-jemput, bersih-bersih kamar & toilet, katering harian, hingga servis teknisi AC langsung ke kamar kosmu.
                        </p>
                    </div>

                    {{-- Trust Info on Gradient Card --}}
                    <div class="flex flex-row md:flex-col items-start md:items-end gap-1.5 sm:gap-2 flex-wrap flex-shrink-0 text-[11px] sm:text-xs text-slate-200">
                        <div class="flex items-center gap-1.5 font-medium">
                            <span class="w-1.5 h-1.5 bg-emerald-400 inline-block"></span>
                            <span>Mitra Siap Melayani Hari Ini</span>
                        </div>
                        <div class="hidden sm:flex items-center gap-1.5 text-sky-200 font-medium">
                            <span>🛡️ Garansi Kerapian & Keamanan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. CATEGORY FILTER TABS --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 relative z-10">
            <div class="bg-white rounded-2xl sm:rounded-3xl p-2.5 sm:p-3 shadow-md shadow-slate-200/50 border border-slate-200/80 flex items-center justify-start sm:justify-center gap-2 overflow-x-auto scrollbar-none">
                <button 
                    @click="activeCategory = 'all'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    Semua Layanan
                </button>
                <button 
                    @click="activeCategory = 'laundry'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'laundry' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🧺 Laundry Kiloan & Satuan
                </button>
                <button 
                    @click="activeCategory = 'cleaning'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'cleaning' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🧹 Kebersihan Kamar & Toilet
                </button>
                <button 
                    @click="activeCategory = 'catering'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'catering' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🍱 Katering Harian (Coming Soon)
                </button>
                <button 
                    @click="activeCategory = 'maintenance'" 
                    type="button" 
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'maintenance' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🔧 Teknisi & Service AC
                </button>
            </div>
        </section>

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

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-2">
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
        </section>

        {{-- 3. SERVICE CATALOG CARDS GRID (3 SEJAJAR / 3 COLS) --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $svc)
                    @php
                        $isAvail = $svc['is_available'] ?? true;
                    @endphp
                    <div 
                        id="{{ $svc['id'] }}"
                        x-show="activeCategory === 'all' || activeCategory === '{{ $svc['category'] }}'"
                        x-transition.duration.300ms
                        class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group {{ !$isAvail ? 'opacity-95' : '' }}"
                    >
                        {{-- Card Header & Visual Banner (Compact Height) --}}
                        <div>
                            <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-slate-100">
                                <img 
                                    src="{{ $svc['image'] }}" 
                                    alt="{{ $svc['title'] }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 {{ !$isAvail ? 'filter grayscale-25 opacity-90' : '' }}"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent flex items-end p-4 sm:p-5">
                                    <div class="w-full flex items-center justify-between text-white">
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-black leading-snug">{{ $svc['title'] }}</h3>
                                        </div>

                                        <div class="flex items-center gap-1 bg-black/50 backdrop-blur-md px-2 py-1 rounded-xl text-xs font-bold text-amber-400">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            {{ $svc['rating'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Description & Feature Bullets --}}
                            <div class="p-5 sm:p-6 space-y-3.5">
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                                    {{ $svc['description'] }}
                                </p>

                                {{-- Features Checklist --}}
                                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                    <div class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Kelebihan:</div>
                                    <div class="space-y-1 text-xs text-slate-700">
                                        @foreach(array_slice($svc['features'], 0, 3) as $feat)
                                            <div class="flex items-start gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <div class="truncate">{{ $feat }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Pilihan Paket Menu / Layanan --}}
                                <div class="pt-2.5 border-t border-slate-100 space-y-1.5">
                                    <div class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Pilihan Paket:</div>
                                    <div class="space-y-1.5">
                                        @foreach(array_slice($svc['packages'], 0, 3) as $pkg)
                                            <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100 text-[11px]">
                                                <div class="font-medium text-slate-800 truncate max-w-[170px]">{{ $pkg['name'] }}</div>
                                                <strong class="text-blue-600 font-bold flex-shrink-0">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Action Bar --}}
                        <div class="p-5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <div class="text-[10px] text-slate-400 font-medium">Tarif Mulai</div>
                                <div class="flex items-baseline gap-1">
                                    <div class="text-lg sm:text-xl font-black text-slate-900">
                                        Rp {{ number_format($svc['price_start'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-semibold">{{ $svc['price_unit'] }}</div>
                                </div>
                            </div>

                            <div>
                                @if($isAvail)
                                    @auth
                                        @if(auth()->user()->hasRole('penghuni'))
                                            <button 
                                                @click="openOrderModal(
                                                    '{{ $svc['id'] }}', 
                                                    '{{ addslashes($svc['title']) }}', 
                                                    '{{ addslashes($svc['packages'][0]['name']) }}', 
                                                    {{ $svc['packages'][0]['price'] }}, 
                                                    '{{ addslashes($svc['packages'][0]['unit']) }}'
                                                )" 
                                                type="button" 
                                                class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-95"
                                            >
                                                Pesan Sekarang
                                            </button>
                                        @else
                                            <button 
                                                @click="alertNotTenant = true" 
                                                type="button" 
                                                class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-95"
                                            >
                                                Pesan Sekarang
                                            </button>
                                        @endif
                                    @else
                                        <a 
                                            href="{{ route('login') }}" 
                                            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-95 inline-block text-center"
                                        >
                                            Pesan Sekarang
                                        </a>
                                    @endauth
                                @else
                                    <button 
                                        type="button" 
                                        disabled 
                                        class="px-3.5 py-2 rounded-xl bg-amber-50 text-amber-800 border border-amber-200/80 text-xs font-bold cursor-not-allowed flex items-center gap-1 shadow-2xs"
                                    >
                                        Segera Hadir (Coming Soon)
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- 4. ALUR & CARA KERJA PEMESANAN (HOW IT WORKS) - INTERAKTIF & ANIMATED --}}
        <section 
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
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 pt-16 border-t border-slate-200/80"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-28 sm:mt-36 lg:mt-40 pt-16 sm:pt-20 border-t border-slate-200/80"
        >
            <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Proses Cepat & Praktis</div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Bagaimana Cara Pesan Layanan?</h2>
                <p class="text-xs sm:text-sm text-slate-500">Hanya 4 langkah mudah untuk menikmati kemudahan layanan kos langsung di kamarmu.</p>
            </div>

            {{-- Interactive Stepper Grid with Connecting Animated Line --}}
            <div class="relative">
                {{-- Desktop Connecting Line Tracker --}}
                <div class="hidden lg:block absolute top-12 left-16 right-16 h-1 bg-slate-200/80 rounded z-0">
                    <div 
                        class="h-full bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 rounded transition-all duration-700 ease-out"
                        :style="'width: ' + (activeStep === 1 ? '12%' : (activeStep === 2 ? '38%' : (activeStep === 3 ? '68%' : '100%')))"
                    ></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                    {{-- Step 1 --}}
                    <div 
                        @click="setStep(1)"
                        @mouseenter="setStep(1)"
                        class="cursor-pointer rounded-3xl p-6 border transition-all duration-300 relative text-center space-y-3.5 group"
                        :class="activeStep === 1 
                            ? 'bg-white border-blue-500 shadow-xl shadow-blue-500/15 -translate-y-2 ring-2 ring-blue-500/20' 
                            : 'bg-white/80 hover:bg-white border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-1'"
                    >
                        <div 
                            class="w-14 h-14 rounded-2xl font-black text-xl flex items-center justify-center mx-auto transition-all duration-300"
                            :class="activeStep === 1 
                                ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 scale-110' 
                                : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100'"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div class="text-[11px] font-black uppercase tracking-wider transition-colors" :class="activeStep === 1 ? 'text-blue-600' : 'text-slate-400'">Langkah 1</div>
                        <h3 class="font-extrabold text-sm text-slate-900">Pilih Layanan & Paket</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Tentukan kebutuhanmu, mulai dari laundry kiloan, deep clean kamar mandi, katering, atau servis AC.
                        </p>
                    </div>

                    {{-- Step 2 --}}
                    <div 
                        @click="setStep(2)"
                        @mouseenter="setStep(2)"
                        class="cursor-pointer rounded-3xl p-6 border transition-all duration-300 relative text-center space-y-3.5 group"
                        :class="activeStep === 2 
                            ? 'bg-white border-blue-500 shadow-xl shadow-blue-500/15 -translate-y-2 ring-2 ring-blue-500/20' 
                            : 'bg-white/80 hover:bg-white border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-1'"
                    >
                        <div 
                            class="w-14 h-14 rounded-2xl font-black text-xl flex items-center justify-center mx-auto transition-all duration-300"
                            :class="activeStep === 2 
                                ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 scale-110' 
                                : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100'"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-[11px] font-black uppercase tracking-wider transition-colors" :class="activeStep === 2 ? 'text-blue-600' : 'text-slate-400'">Langkah 2</div>
                        <h3 class="font-extrabold text-sm text-slate-900">Atur Jadwal & Kamar</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Isi alamat kos, nomor kamar, dan pilih jam kunjungan yang paling nyaman untuk Anda.
                        </p>
                    </div>

                    {{-- Step 3 --}}
                    <div 
                        @click="setStep(3)"
                        @mouseenter="setStep(3)"
                        class="cursor-pointer rounded-3xl p-6 border transition-all duration-300 relative text-center space-y-3.5 group"
                        :class="activeStep === 3 
                            ? 'bg-white border-blue-500 shadow-xl shadow-blue-500/15 -translate-y-2 ring-2 ring-blue-500/20' 
                            : 'bg-white/80 hover:bg-white border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-1'"
                    >
                        <div 
                            class="w-14 h-14 rounded-2xl font-black text-xl flex items-center justify-center mx-auto transition-all duration-300"
                            :class="activeStep === 3 
                                ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 scale-110' 
                                : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100'"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="text-[11px] font-black uppercase tracking-wider transition-colors" :class="activeStep === 3 ? 'text-blue-600' : 'text-slate-400'">Langkah 3</div>
                        <h3 class="font-extrabold text-sm text-slate-900">Mitra Tiba di Kos</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Mitra terverifikasi SevenKost tiba sesuai jadwal membawa peralatan lengkap dan seragam resmi.
                        </p>
                    </div>

                    {{-- Step 4 --}}
                    <div 
                        @click="setStep(4)"
                        @mouseenter="setStep(4)"
                        class="cursor-pointer rounded-3xl p-6 border transition-all duration-300 relative text-center space-y-3.5 group"
                        :class="activeStep === 4 
                            ? 'bg-white border-emerald-500 shadow-xl shadow-emerald-500/15 -translate-y-2 ring-2 ring-emerald-500/20' 
                            : 'bg-white/80 hover:bg-white border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-1'"
                    >
                        <div 
                            class="w-14 h-14 rounded-2xl font-black text-xl flex items-center justify-center mx-auto transition-all duration-300"
                            :class="activeStep === 4 
                                ? 'bg-gradient-to-tr from-emerald-600 to-teal-600 text-white shadow-lg shadow-emerald-600/30 scale-110' 
                                : 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100'"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-[11px] font-black uppercase tracking-wider transition-colors" :class="activeStep === 4 ? 'text-emerald-600' : 'text-slate-400'">Langkah 4</div>
                        <h3 class="font-extrabold text-sm text-slate-900">Selesai & Bayar Aman</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Periksa hasil pengerjaan. Pembayaran fleksibel dapat dilakukan via transfer QRIS atau tunai langsung.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. MITRA REGISTRATION CALLOUT (COMPACT & SESUAI PALET HOME PAGE) --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 sm:mt-10">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-4 sm:p-5 shadow-md border border-white/10">
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="space-y-1 max-w-xl">
                        <h3 class="text-sm sm:text-base font-bold text-white">
                            Punya Usaha Laundry, Katering, atau Kebersihan?
                        </h3>
                        <p class="text-xs text-slate-200 leading-relaxed">
                            Gabung jadi Mitra Resmi SevenKost dan dapatkan pesanan langsung dari ribuan penghuni kos aktif di sekitarmu.
                        </p>
                    </div>

                    <a 
                        href="https://wa.me/6281234567890?text=Halo%20Admin%20SevenKost%2C%20saya%20tertarik%20mendaftar%20menjadi%20Mitra%20Layanan%20di%20SevenKost." 
                        target="_blank" 
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white text-blue-900 hover:bg-slate-100 text-xs font-bold shadow-sm transition active:scale-95 flex-shrink-0"
                    >
                        <span>Daftar Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </section>

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
                        class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-800 animate-in fade-in zoom-in-95 duration-200 relative overflow-hidden"
                    >
                        {{-- Notifikasi Sukses Pemesanan --}}
                        <div 
                            x-show="orderSuccess" 
                            x-transition 
                            class="absolute inset-0 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center space-y-3 z-20"
                            style="display: none;"
                        >
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl">
                                ✓
                            </div>
                            <h4 class="text-lg font-black text-slate-900">Pesanan Berhasil Dikirim!</h4>
                            <p class="text-xs text-slate-600 max-w-xs leading-relaxed">
                                Jadwal kunjungan telah diteruskan ke mitra SevenKost terdekat. Mitra akan segera menuju ke kamar kos Anda sesuai jadwal.
                            </p>
                        </div>

                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Form Pemesanan Layanan</span>
                                <h3 class="text-lg font-black text-slate-900 mt-0.5" x-text="selectedServiceName"></h3>
                            </div>
                            <button @click="orderModalOpen = false" class="text-slate-400 hover:text-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form @submit.prevent="submitOrder()" class="space-y-4">
                            {{-- Detail Pilihan Paket --}}
                            <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] text-slate-500 font-medium block">Paket Terpilih:</span>
                                    <strong class="text-xs sm:text-sm font-bold text-slate-900" x-text="selectedPackageName"></strong>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-extrabold text-blue-600" x-text="'Rp ' + Number(selectedPackagePrice).toLocaleString('id-ID')"></span>
                                    <span class="text-[10px] text-slate-400 block" x-text="selectedPackageUnit"></span>
                                </div>
                            </div>

                            {{-- Alamat Kos & No Kamar --}}
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Nama Kos & Alamat Lengkap <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    x-model="kostAddress" 
                                    placeholder="Contoh: Seven Kost Melati Residence, Jl. Fatmawati No. 45" 
                                    required 
                                    class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                                >
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Nomor Kamar <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        x-model="roomNumber" 
                                        placeholder="Contoh: Kamar 204 (Lt. 2)" 
                                        required 
                                        class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                                    >
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase">Waktu Kunjungan</label>
                                    <select x-model="orderTime" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                                        <option value="09:00">Pagi (09:00 WIB)</option>
                                        <option value="11:30">Siang (11:30 WIB)</option>
                                        <option value="14:00">Siang (14:00 WIB)</option>
                                        <option value="16:30">Sore (16:30 WIB)</option>
                                        <option value="19:00">Malam (19:00 WIB)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Tanggal Layanan</label>
                                <input 
                                    type="date" 
                                    x-model="orderDate" 
                                    min="{{ date('Y-m-d') }}" 
                                    class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                >
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Catatan Tambahan (Opsional)</label>
                                <textarea 
                                    x-model="orderNotes" 
                                    rows="2" 
                                    placeholder="Contoh: Mohon ketuk kamar dulu, ada pakaian jas butuh dry clean..." 
                                    class="w-full text-xs rounded-xl border-slate-200 p-2.5"
                                ></textarea>
                            </div>

                            {{-- Tombol Konfirmasi Pesanan --}}
                            <div class="pt-2 flex gap-2">
                                <button 
                                    type="button" 
                                    @click="orderModalOpen = false" 
                                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 text-center transition"
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
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto text-2xl shadow-xs">
                    🔒
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Khusus Akun Penghuni Kos</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Pemesanan layanan harian (laundry, cleaning, teknisi) hanya dapat dilakukan oleh akun yang terdaftar sebagai penghuni kos aktif di SevenKost.
                    </p>
                </div>
                <button 
                    type="button" 
                    @click="alertNotTenant = false" 
                    class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition"
                >
                    Saya Mengerti
                </button>
            </div>
        </div>

    </div>

</x-seven-layout>

<x-seven-layout title="Layanan Kebutuhan Kos - Mitra Resmi SevenKost">

    {{-- Root Container with Alpine.js State --}}
    <div 
        x-data="{
            mainTab: '{{ request('tab') === 'orders' ? 'orders' : 'catalog' }}',
            activeCategory: 'all',
            orderModalOpen: false,
            alertNotTenant: false,
            alertNoActiveStay: false,
            hasActiveStay: {{ $hasActiveBooking ? 'true' : 'false' }},
            activeBookingsCount: {{ $activeBookings->count() }},
            selectedBookingId: {{ $activeBooking?->id ?? 'null' }},
            selectedService: 'laundry',
            selectedServiceName: 'Seven Laundry Kos',
            selectedPackageName: 'Laundry Kiloan Reguler (2-3 Hari)',
            selectedPackagePrice: 7000,
            selectedPackageUnit: '/kg',
            selectedPartnerName: @js($services[0]['local_partner']['partner_name'] ?? ''),
            quantity: 1,
            paymentMethod: 'xendit',
            userWalletBalance: {{ auth()->check() ? (float) auth()->user()->wallet_balance : 0 }},
            kostAddress: @js($tenantAddress ?? ''),
            roomNumber: @js($tenantRoomName ?? ''),
            occupantName: @js($tenantOccupantName ?? (auth()->user()?->name ?? '')),
            occupantPhone: @js($tenantOccupantPhone ?? (auth()->user()?->phone ?? '')),
            orderDate: @js(date('Y-m-d')),
            orderTime: '10:00',
            orderNotes: '',

            selectedDistanceKm: 1.4,
            fulfillmentType: 'delivery',

            openOrderModal(serviceId, serviceName, defaultPkgName, defaultPrice, defaultUnit, partnerName = '', distanceKm = 1.4) {
                if (!this.hasActiveStay) {
                    this.alertNoActiveStay = true;
                    return;
                }
                this.selectedService = serviceId;
                this.selectedServiceName = serviceName;
                this.selectedPackageName = defaultPkgName;
                this.selectedPackagePrice = Number(defaultPrice);
                this.selectedPackageUnit = defaultUnit;
                this.selectedPartnerName = partnerName;
                this.selectedDistanceKm = Number(distanceKm) || 1.4;
                this.fulfillmentType = 'delivery';
                this.quantity = 1;
                this.paymentMethod = 'xendit';
                this.orderModalOpen = true;
            },

            onPackageChange(event) {
                const opt = event.target.selectedOptions[0];
                if (opt) {
                    this.selectedPackageName = opt.value;
                    this.selectedPackagePrice = Number(opt.dataset.price);
                    this.selectedPackageUnit = opt.dataset.unit;
                }
            },

            deliveryConfig: {{ \Illuminate\Support\Js::from($deliveryConfig ?? [
                'base_fee' => 5000,
                'base_km' => 2.0,
                'per_km' => 2000,
                'max_km' => 5.0,
            ]) }},

            get deliveryFee() {
                if (this.fulfillmentType === 'self_service') {
                    return 0;
                }
                const dist = Number(this.selectedDistanceKm) || 0;
                const baseFee = Number(this.deliveryConfig.base_fee) || 5000;
                const baseKm = Number(this.deliveryConfig.base_km) || 2.0;
                const perKm = Number(this.deliveryConfig.per_km) || 2000;

                if (dist <= baseKm) {
                    return baseFee;
                }
                return baseFee + Math.ceil(dist - baseKm) * perKm;
            },

            get subtotalOrderPrice() {
                return Number(this.selectedPackagePrice) * Math.max(1, Number(this.quantity));
            },

            get totalOrderPrice() {
                return this.subtotalOrderPrice + this.deliveryFee;
            },

            alertOutOfRange: false,
            selectedOutOfRangeDistance: 0,
            selectedOutOfRangeService: ''
        }"
        @keydown.escape.window="orderModalOpen = false; alertNotTenant = false; alertNoActiveStay = false; alertOutOfRange = false"
        class="min-h-screen bg-slate-50 py-6 sm:py-8"
    >

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- 1. HERO SECTION WEB (DESKTOP FULL WIDTH BANNER) --}}
            <div class="relative overflow-hidden rounded-3xl text-white p-6 sm:p-8 md:p-10 shadow-xl border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
                {{-- Decorative circles --}}
                <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none blur-xl"></div>
                <div class="absolute right-1/4 -top-12 w-48 h-48 rounded-full bg-blue-400/10 pointer-events-none blur-lg"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="max-w-2xl space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold text-blue-100 border border-white/15">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Layanan Mitra Resmi SevenKost &bull; Mitra Siap Melayani Hari Ini</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white leading-tight">
                            Kebutuhan Kamar Kos Beres Tanpa Ribet
                        </h1>
                        <p class="text-sm sm:text-base text-blue-100/90 leading-relaxed font-normal">
                            Nikmati kemudahan laundry antar-jemput ke kamar, jasa bersih-bersih kamar & toilet, servis AC terpercaya, hingga katering harian langsung dari mitra lokal terverifikasi di sekitar kos Anda.
                        </p>
                        <div class="flex flex-wrap items-center gap-4 pt-2 text-xs font-semibold text-blue-200">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Mitra Terverifikasi Resmi
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Gratis Ongkir (Ambil Sendiri di Toko)
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Bisa Bayar QRIS & Dompet
                            </span>
                        </div>
                    </div>

                    {{-- Right Box: Filter Wilayah & Portal Khusus Penghuni --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 sm:p-5 border border-white/20 space-y-3 shrink-0 lg:w-80">
                        <div class="text-xs font-bold text-blue-200 uppercase tracking-wider">Wilayah Layanan Mitra</div>
                        <form method="GET" action="{{ route('services.index') }}" class="space-y-2">
                            <label for="kota-filter-web" class="text-xs text-white/90 block">Pilih Kota / Wilayah Mitra:</label>
                            <select id="kota-filter-web" name="kota" onchange="this.form.submit()" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border-white/20 bg-white text-slate-800 shadow-sm focus:ring-2 focus:ring-blue-400">
                                @foreach($regionalPartners as $rKey => $rVal)
                                    <option value="{{ $rVal['badge_city'] }}" {{ $cityKey === $rKey ? 'selected' : '' }}>
                                        {{ $rVal['city_label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        @auth
                            @if(auth()->user()->hasRole('penghuni'))
                                <div class="pt-2 border-t border-white/15">
                                    <a href="{{ route('tenant.services.index') }}" class="inline-flex items-center justify-center gap-2 w-full py-2 px-3 rounded-xl bg-white text-blue-900 hover:bg-blue-50 text-xs font-black transition shadow-sm">
                                        <span>Buka Portal Layanan Penghuni</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            {{-- 2. STATUS KAMAR AKTIF PENGHUNI (JIKA LOGIN & SEWA KAMAR) ATAU PANDUAN JANGKAUAN --}}
            @if($hasActiveBooking)
                <div class="p-4 sm:p-5 rounded-2xl bg-white border border-emerald-200/90 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-sm sm:text-base font-extrabold text-slate-900">
                                    {{ $tenantKostName }} &bull; {{ $tenantRoomName }}
                                </h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Kamar Aktif</span>
                                @if(isset($hasOutOfRangeService) && $hasOutOfRangeService)
                                    <span class="text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Di Luar Jangkauan (> 5 km)
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Mitra Siap Melayani Hari Ini
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Pemesan: <strong class="text-slate-700">{{ $tenantOccupantName }}</strong> &bull; Alamat: {{ $tenantAddress }}
                            </p>
                        </div>
                    </div>

                    {{-- Pemilihan Multi-Kamar Jika Ada --}}
                    @if($activeBookings->count() > 1)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 shrink-0">
                            <span class="text-xs font-bold text-slate-600">Pilih Kamar Tujuan Layanan:</span>
                            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 shrink-0">
                                @foreach($activeBookings as $bOption)
                                    @php $isSelected = $activeBooking && $activeBooking->id === $bOption->id; @endphp
                                    <a href="{{ route('services.index', ['kamar_id' => $bOption->id]) }}" 
                                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition shrink-0 whitespace-nowrap {{ $isSelected ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                                        {{ $bOption->kost->name }} ({{ $bOption->room?->name ?? 'Kamar' }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-4 sm:p-5 rounded-2xl bg-white border border-blue-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Jangkauan Berbasis Lokasi Kos</h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">Info Layanan</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Layanan Mitra Khusus Penghuni Kamar Aktif &bull; <strong class="text-blue-600">Mitra Siap Melayani Hari Ini</strong>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <form method="GET" action="{{ route('services.index') }}" class="flex items-center gap-2">
                            <label for="kota-filter-bar" class="text-xs font-semibold text-slate-600">Cek Mitra di:</label>
                            <select id="kota-filter-bar" name="kota" onchange="this.form.submit()" class="text-xs py-1.5 px-3 rounded-xl border-slate-200 bg-slate-50 text-slate-700">
                                @foreach($regionalPartners as $rKey => $rVal)
                                    <option value="{{ $rVal['badge_city'] }}" {{ $cityKey === $rKey ? 'selected' : '' }}>
                                        {{ $rVal['city_label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <a href="{{ route('search') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs">
                            <span>Cari Kamar Kos</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endif

            {{-- 3. COMMERCIAL BANNER SLIDER PROMO (WIDE WEB DISPLAY) --}}
            @php
                $svcBanners = [];
                if (isset($banners) && $banners->count() > 0) {
                    foreach ($banners as $b) {
                        $svcBanners[] = [
                            'img' => $b->image_url,
                            'link' => $b->target_url ?: route('services.index'),
                            'alt' => $b->title,
                            'advertiser' => $b->advertiser,
                        ];
                    }
                }
                if (empty($svcBanners)) {
                    $svcBanners = [
                        ['img' => asset('images/frontend/banner-jakarta-wide.jpg'), 'link' => route('services.index'), 'alt' => 'Promo Layanan Laundry Kos', 'advertiser' => 'Seven Laundry'],
                        ['img' => asset('images/frontend/banner-bandung-wide.jpg'), 'link' => route('services.index'), 'alt' => 'Promo Bersih-Bersih Kos', 'advertiser' => 'Seven Clean'],
                        ['img' => asset('images/frontend/banner-yogyakarta-wide.jpg'), 'link' => route('services.index'), 'alt' => 'Promo Teknisi AC Kos', 'advertiser' => 'Seven Care'],
                    ];
                }
            @endphp

            @if(count($svcBanners) > 0)
                <div 
                    class="group/banner relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-200/80 shadow-sm"
                    x-data="{
                        activeBanner: 0,
                        totalBanners: {{ count($svcBanners) }},
                        timer: null,
                        start() {
                            this.timer = setInterval(() => { this.next(); }, 6000);
                        },
                        stop() { clearInterval(this.timer); },
                        next() { this.activeBanner = (this.activeBanner + 1) % this.totalBanners; },
                        prev() { this.activeBanner = (this.activeBanner - 1 + this.totalBanners) % this.totalBanners; }
                    }"
                    x-init="start()"
                    @mouseenter="stop()"
                    @mouseleave="start()"
                >
                    <div class="relative w-full h-40 sm:h-56 md:h-64 lg:h-72 overflow-hidden">
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
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                                    <div class="absolute top-4 left-4 px-3 py-1 rounded-xl bg-black/60 text-white text-xs font-semibold tracking-wider uppercase backdrop-blur-xs flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>Iklan Sponsor &bull; Promo Eksklusif</span>
                                    </div>
                                    <div class="absolute bottom-4 left-4 right-32 sm:right-48 max-w-xl space-y-1">
                                        @if(!empty($bItem['advertiser']))
                                            <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-600/90 text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider backdrop-blur-xs">
                                                {{ $bItem['advertiser'] }}
                                            </span>
                                        @endif
                                        <h3 class="text-white text-sm sm:text-base md:text-lg font-black drop-shadow-md line-clamp-1 leading-snug">
                                            {{ $bItem['alt'] }}
                                        </h3>
                                    </div>
                                    <div class="absolute bottom-4 right-4">
                                        <div class="text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-xl backdrop-blur-xs shadow-md transition">
                                            Lihat Penawaran &rarr;
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Navigasi Slider --}}
                    @if(count($svcBanners) > 1)
                        <button 
                            type="button" 
                            @click.prevent="prev()" 
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 hover:bg-black/75 text-white flex items-center justify-center transition-all opacity-0 group-hover/banner:opacity-100 hover:scale-105 active:scale-95 shadow-md backdrop-blur-xs z-10 cursor-pointer"
                        >
                            <svg class="w-5 h-5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button 
                            type="button" 
                            @click.prevent="next()" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/45 hover:bg-black/75 text-white flex items-center justify-center transition-all opacity-0 group-hover/banner:opacity-100 hover:scale-105 active:scale-95 shadow-md backdrop-blur-xs z-10 cursor-pointer"
                        >
                            <svg class="w-5 h-5 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </div>
            @endif

            {{-- 4. MAIN TAB SWITCHER (KATALOG LAYANAN vs PESANAN SAYA) --}}
            @php $isOrdersTab = request('tab') === 'orders'; @endphp
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="inline-flex p-1 bg-slate-200/80 rounded-2xl gap-1 text-xs sm:text-sm font-bold">
                    <a 
                        href="{{ route('services.index', ['tab' => 'catalog']) }}"
                        class="px-5 py-2.5 rounded-xl transition flex items-center gap-2 cursor-pointer text-center {{ !$isOrdersTab ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Katalog Layanan</span>
                    </a>

                    @auth
                        <a 
                            href="{{ route('services.index', ['tab' => 'orders']) }}"
                            class="px-5 py-2.5 rounded-xl transition flex items-center gap-2 cursor-pointer text-center {{ $isOrdersTab ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Pesanan Saya</span>
                            @if(isset($serviceOrders) && $serviceOrders->count() > 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-black bg-blue-600 text-white">
                                    {{ $serviceOrders->count() }}
                                </span>
                            @endif
                        </a>
                    @endauth
                </div>

                {{-- Category Filters --}}
                @if(!$isOrdersTab)
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
                        <button 
                            @click="activeCategory = 'all'" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer"
                            :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        >
                            Semua Layanan
                        </button>
                        <button 
                            @click="activeCategory = 'laundry'" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
                            :class="activeCategory === 'laundry' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        >
                            <span>🧺 Laundry Kiloan</span>
                        </button>
                        <button 
                            @click="activeCategory = 'cleaning'" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
                            :class="activeCategory === 'cleaning' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        >
                            <span>✨ Kebersihan Kamar</span>
                        </button>
                        <button 
                            @click="activeCategory = 'catering'" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
                            :class="activeCategory === 'catering' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        >
                            <span>🍱 Katering Harian (Coming Soon)</span>
                        </button>
                        <button 
                            @click="activeCategory = 'maintenance'" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer flex items-center gap-1.5"
                            :class="activeCategory === 'maintenance' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                        >
                            <span>🛠️ Teknisi (Coming Soon)</span>
                        </button>
                    </div>
                @endif
            </div>

            {{-- 5. CONTENT AREA: TAB PESANAN SAYA --}}
            @if($isOrdersTab)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Riwayat & Status Pesanan</h2>
                            <p class="text-xs text-slate-500">Pantau proses pengerjaan layanan mitra langsung ke kamar kos Anda.</p>
                        </div>
                        <span class="text-xs font-bold text-slate-500 bg-white px-3 py-1 rounded-xl border border-slate-200">
                            {{ isset($serviceOrders) ? $serviceOrders->count() : 0 }} Pesanan Terdaftar
                        </span>
                    </div>

                    @if(isset($serviceOrders) && $serviceOrders->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($serviceOrders as $ord)
                                @php
                                    $ordPayload = $ord->payload ?? [];
                                    $isPaid = $ord->isPaid();
                                    $statusOrder = $ordPayload['service_order_status'] ?? ($isPaid ? 'confirmed' : 'pending');
                                @endphp
                                <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-100">
                                            <div>
                                                <span class="text-xs font-mono text-blue-900 font-extrabold bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-lg">
                                                    #{{ $ord->reference_id }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 block mt-1">
                                                    {{ $ord->created_at?->format('d M Y, H:i') }} WIB
                                                </span>
                                            </div>
                                            <div>
                                                @if($statusOrder === 'completed')
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        ✅ Selesai
                                                    </span>
                                                @elseif($statusOrder === 'ready_for_pickup')
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                                        ✨ Siap Diambil
                                                    </span>
                                                @elseif($isPaid)
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-800 border border-blue-200">
                                                        ⚙️ Diproses Mitra
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-200">
                                                        ⏳ Menunggu Bayar
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-sm font-black text-slate-900 leading-snug">{{ $ordPayload['service_title'] ?? $ord->title }}</h3>
                                            <p class="text-xs text-blue-600 font-bold mt-1">
                                                {{ $ordPayload['package_name'] ?? '-' }} &bull; {{ $ordPayload['quantity'] ?? 1 }} {{ $ordPayload['package_unit'] ?? '' }}
                                            </p>
                                        </div>

                                        <div class="bg-slate-50 rounded-2xl p-3 text-xs text-slate-600 space-y-1.5 border border-slate-100">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Kamar Tujuan:</span>
                                                <strong class="text-slate-800">{{ $ordPayload['room_number'] ?? '-' }}</strong>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Jadwal:</span>
                                                <span class="font-bold text-slate-800">{{ $ordPayload['order_date'] ?? '-' }} ({{ $ordPayload['order_time'] ?? '-' }} WIB)</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Metode:</span>
                                                <span class="font-bold {{ ($ordPayload['fulfillment_type'] ?? '') === 'self_service' ? 'text-emerald-700' : 'text-blue-700' }}">
                                                    {{ ($ordPayload['fulfillment_type'] ?? '') === 'self_service' ? '🧺 Bawa Sendiri ke Toko' : '🛵 Kurir Antar-Jemput' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-3">
                                        <div>
                                            <span class="text-[10px] text-slate-400 font-semibold block uppercase">Total Tagihan</span>
                                            <strong class="text-sm sm:text-base font-black text-slate-900">
                                                Rp {{ number_format($ord->amount, 0, ',', '.') }}
                                            </strong>
                                        </div>

                                        @if(!$isPaid)
                                            <a 
                                                href="{{ route('tenant.services.payment', $ord->reference_id) }}" 
                                                class="py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-xs transition"
                                            >
                                                Bayar Sekarang
                                            </a>
                                        @else
                                            <a 
                                                href="{{ route('tenant.services.tracking', $ord->reference_id) }}" 
                                                class="py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-xs transition"
                                            >
                                                Lacak Pesanan &rarr;
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-4 shadow-xs">
                            <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-base font-bold text-slate-800">Belum Ada Pesanan Layanan</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                    Anda belum memiliki riwayat pesanan layanan kebutuhan kamar. Silakan jelajahi katalog layanan untuk memesan.
                                </p>
                            </div>
                            <a 
                                href="{{ route('services.index', ['tab' => 'catalog']) }}" 
                                class="inline-flex items-center gap-2 py-2.5 px-5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-xs transition"
                            >
                                <span>Buka Katalog Layanan</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

            {{-- 6. CONTENT AREA: TAB KATALOG LAYANAN (DESKTOP RESPONSIVE GRID 4 KOLOM) --}}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($services as $svc)
                        @php
                            $isAvail = $svc['is_available'] ?? true;
                        @endphp
                        <div 
                            id="{{ $svc['id'] }}"
                            x-show="activeCategory === 'all' || activeCategory === '{{ $svc['category'] }}'"
                            x-transition.duration.300ms
                            class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden {{ !$isAvail ? 'opacity-95' : '' }}"
                        >
                            {{-- Top: Cover Image with Overlay Badge --}}
                            <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                                <img 
                                    src="{{ $svc['image'] }}" 
                                    alt="{{ $svc['title'] }}" 
                                    class="w-full h-full object-cover hover:scale-105 transition duration-500 {{ !$isAvail ? 'filter grayscale-25 opacity-90' : '' }}"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-black/60 text-white backdrop-blur-xs">
                                        {{ ucfirst($svc['category']) }}
                                    </span>
                                </div>
                                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white">
                                    <div class="flex items-center gap-1 text-xs font-extrabold text-amber-300 bg-black/60 backdrop-blur-xs px-2 py-0.5 rounded-lg">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>{{ $svc['rating'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] text-blue-200 block font-semibold">Mulai</span>
                                        <strong class="text-sm font-black text-white">
                                            Rp {{ number_format($svc['price_start'], 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Body: Title, Description & Features --}}
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3 class="text-base font-extrabold text-slate-900 leading-snug">{{ $svc['title'] }}</h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $svc['description'] }}
                                    </p>

                                    {{-- Kelebihan Layanan --}}
                                    <div class="pt-2 border-t border-slate-100 space-y-1.5">
                                        @foreach(array_slice($svc['features'], 0, 3) as $feat)
                                            <div class="flex items-start gap-2 text-xs text-slate-700">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <span class="text-[11px] font-medium leading-tight">{{ $feat }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Pilihan Paket List & CTA Button --}}
                                <div class="pt-3 border-t border-slate-100 space-y-3">
                                    <div class="space-y-1.5">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pilihan Paket:</span>
                                        @foreach(array_slice($svc['packages'], 0, 2) as $pkg)
                                            <div class="flex items-center justify-between text-xs py-1 px-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                <span class="font-bold text-slate-800 truncate">{{ $pkg['name'] }}</span>
                                                <span class="font-extrabold text-blue-600 shrink-0 ml-2">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    @php
                                        $isInRange = $svc['is_in_range'] ?? true;
                                        $defaultPkg = $svc['packages'][0] ?? null;
                                        $partnerName = $svc['local_partner']['partner_name'] ?? 'Mitra Resmi SevenKost';
                                        $dist = $svc['local_partner']['distance_km'] ?? 1.4;
                                    @endphp

                                    @if($isAvail && $isInRange)
                                        @auth
                                            @if(auth()->user()->hasRole('penghuni'))
                                                <button 
                                                    type="button" 
                                                    @click="openOrderModal('{{ $svc['id'] }}', '{{ addslashes($svc['title']) }}', '{{ addslashes($defaultPkg['name'] ?? '') }}', {{ $defaultPkg['price'] ?? 0 }}, '{{ $defaultPkg['unit'] ?? '' }}', '{{ addslashes($partnerName) }}', {{ $dist }})"
                                                    class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs text-center shadow-md shadow-blue-600/20 transition active:scale-98 cursor-pointer flex items-center justify-center gap-1.5"
                                                >
                                                    <span>Pesan Layanan</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </button>
                                            @else
                                                <button 
                                                    type="button" 
                                                    @click="alertNotTenant = true" 
                                                    class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition cursor-pointer"
                                                >
                                                    Khusus Penghuni Kos
                                                </button>
                                            @endif
                                        @else
                                            <a 
                                                href="{{ route('login') }}" 
                                                class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs text-center shadow-xs transition block"
                                            >
                                                Pesan Sekarang
                                            </a>
                                        @endauth
                                    @elseif(!$isInRange)
                                        <button 
                                            type="button" 
                                            @click="alertOutOfRange = true; selectedOutOfRangeDistance = '{{ $svc['local_partner']['distance_km'] ?? 0 }} km'; selectedOutOfRangeService = '{{ addslashes($svc['title']) }}'" 
                                            class="w-full py-2.5 px-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs text-center cursor-pointer"
                                        >
                                            Di Luar Jangkauan (> 5 km)
                                        </button>
                                    @else
                                        <button 
                                            type="button" 
                                            disabled 
                                            class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs text-center cursor-not-allowed"
                                        >
                                            Segera Hadir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- ========================================================= --}}
        {{-- MODAL PESAN LAYANAN KHUSUS PENGHUNI (RESPONSIVE WEB DIALOG) --}}
        {{-- ========================================================= --}}
        @auth
            @if(auth()->user()->hasRole('penghuni'))
                <div 
                    x-show="orderModalOpen" 
                    x-transition.opacity 
                    class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6 overflow-y-auto" 
                    style="display: none;"
                >
                    <div 
                        @click.outside="orderModalOpen = false" 
                        class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 space-y-4 shadow-2xl relative my-8"
                    >
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base sm:text-lg font-black text-slate-900" x-text="'Pesan ' + selectedServiceName"></h3>
                                <p class="text-xs text-slate-500 mt-0.5">Konfirmasi paket pengerjaan dan lokasi kamar kos Anda.</p>
                            </div>
                            <button @click="orderModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('services.order') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="service_id" :value="selectedService">
                            <input type="hidden" name="package_price" :value="selectedPackagePrice">
                            <input type="hidden" name="package_unit" :value="selectedPackageUnit">
                            <input type="hidden" name="partner_name" :value="selectedPartnerName">
                            <input type="hidden" name="booking_id" :value="selectedBookingId">
                            <input type="hidden" name="distance_km" :value="selectedDistanceKm">

                            {{-- Pilihan Paket Layanan (Dropdown Dinamis) --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Paket Layanan <span class="text-rose-500">*</span>
                                </label>
                                @foreach($services as $sItem)
                                    <div x-show="selectedService === '{{ $sItem['id'] }}'">
                                        <select 
                                            name="package_name"
                                            :disabled="selectedService !== '{{ $sItem['id'] }}'"
                                            @change="onPackageChange($event)"
                                            class="w-full text-xs sm:text-sm font-bold text-slate-900 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
                                        >
                                            @foreach($sItem['packages'] as $pItem)
                                                <option 
                                                    value="{{ $pItem['name'] }}" 
                                                    data-price="{{ $pItem['price'] }}" 
                                                    data-unit="{{ $pItem['unit'] }}"
                                                    :selected="selectedPackageName === '{{ addslashes($pItem['name']) }}'"
                                                >
                                                    {{ $pItem['name'] }} — Rp {{ number_format($pItem['price'], 0, ',', '.') }} {{ $pItem['unit'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pilihan Metode Pemenuhan Pesanan (Antar-Jemput vs Antar Sendiri) --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Metode Penyerahan / Pengambilan <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label 
                                        @click="fulfillmentType = 'delivery'" 
                                        :class="fulfillmentType === 'delivery' ? 'border-blue-600 bg-blue-50/80 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:border-slate-300'"
                                        class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition select-none"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-base">🛵</span>
                                                <span class="text-xs font-black text-slate-900">Antar-Jemput Kos</span>
                                            </div>
                                            <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillmentType" class="text-blue-600 focus:ring-blue-500">
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-1">Kurir mitra jemput cucian di kos &amp; antar kembali setelah selesai.</p>
                                        <span class="text-[11px] font-bold text-blue-700 mt-2" x-text="'+ Rp ' + deliveryFee.toLocaleString('id-ID') + ' ongkir'"></span>
                                    </label>

                                    <label 
                                        @click="fulfillmentType = 'self_service'" 
                                        :class="fulfillmentType === 'self_service' ? 'border-emerald-600 bg-emerald-50/80 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:border-slate-300'"
                                        class="relative flex flex-col p-3 rounded-2xl border cursor-pointer transition select-none"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-base">🧺</span>
                                                <span class="text-xs font-black text-slate-900">Bawa Sendiri ke Toko</span>
                                            </div>
                                            <input type="radio" name="fulfillment_type" value="self_service" x-model="fulfillmentType" class="text-emerald-600 focus:ring-emerald-500">
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-1">Anda bawa sendiri ke toko mitra dan ambil saat selesai.</p>
                                        <span class="text-[11px] font-black text-emerald-600 mt-2">GRATIS Ongkir (Rp 0)</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Jumlah Unit & Rincian Biaya --}}
                            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 space-y-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-[11px] text-slate-500 font-bold uppercase block">Jumlah:</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button 
                                                type="button" 
                                                @click="quantity = Math.max(1, quantity - 1)" 
                                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center text-sm shadow-xs active:scale-95 cursor-pointer"
                                            >-</button>
                                            <input 
                                                type="number" 
                                                name="quantity"
                                                x-model.number="quantity" 
                                                min="1" 
                                                max="50"
                                                class="w-16 text-center font-black text-slate-900 text-sm py-1.5 border border-slate-200 rounded-lg bg-white"
                                            >
                                            <button 
                                                type="button" 
                                                @click="quantity = quantity + 1" 
                                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center text-sm shadow-xs active:scale-95 cursor-pointer"
                                            >+</button>
                                            <span class="text-xs text-slate-600 font-bold" x-text="selectedPackageUnit"></span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs text-slate-500 font-semibold block">Subtotal:</span>
                                        <strong class="text-sm sm:text-base font-bold text-slate-800 block" x-text="'Rp ' + subtotalOrderPrice.toLocaleString('id-ID')"></strong>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-blue-200/60 flex items-center justify-between text-xs">
                                    <span class="text-slate-600 font-medium">Biaya Antar-Jemput:</span>
                                    <template x-if="fulfillmentType === 'self_service'">
                                        <span class="font-black text-emerald-600">Rp 0 (Antar &amp; Ambil Sendiri)</span>
                                    </template>
                                    <template x-if="fulfillmentType !== 'self_service'">
                                        <span 
                                            class="font-bold text-slate-800" 
                                            x-text="'Rp ' + deliveryFee.toLocaleString('id-ID') + ' (' + selectedDistanceKm + ' km)'"
                                        ></span>
                                    </template>
                                </div>

                                <div class="pt-2 border-t border-blue-200/60 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800">Total Tagihan:</span>
                                    <strong class="text-base sm:text-lg font-black text-blue-600" x-text="'Rp ' + totalOrderPrice.toLocaleString('id-ID')"></strong>
                                </div>
                            </div>

                            {{-- Mitra Pelaksana --}}
                            <template x-if="selectedPartnerName">
                                <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Mitra Pelaksana:</span>
                                    <span class="font-bold text-slate-800 truncate" x-text="selectedPartnerName"></span>
                                </div>
                            </template>

                            {{-- Kamar & Alamat Kos --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Kamar <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="room_number"
                                        x-model="roomNumber" 
                                        placeholder="Misal: Kamar 12" 
                                        required 
                                        class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Waktu Kunjungan</label>
                                    <select name="order_time" x-model="orderTime" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5 bg-white">
                                        <option value="09:00">Pagi (09:00 WIB)</option>
                                        <option value="11:30">Siang (11:30 WIB)</option>
                                        <option value="14:00">Siang (14:00 WIB)</option>
                                        <option value="16:30">Sore (16:30 WIB)</option>
                                        <option value="19:00">Malam (19:00 WIB)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Kos <span class="text-rose-500">*</span></label>
                                <input 
                                    type="text" 
                                    name="kost_address"
                                    x-model="kostAddress" 
                                    placeholder="Nama kos dan alamat lengkap" 
                                    required 
                                    class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5"
                                >
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Penghuni <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="occupant_name"
                                        x-model="occupantName" 
                                        placeholder="Nama lengkap" 
                                        required 
                                        class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="tel" 
                                        name="occupant_phone"
                                        x-model="occupantPhone" 
                                        placeholder="081234567890" 
                                        required 
                                        class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5"
                                    >
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Layanan</label>
                                <input 
                                    type="date" 
                                    name="order_date"
                                    x-model="orderDate" 
                                    min="{{ date('Y-m-d') }}" 
                                    class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 py-2.5"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea 
                                    name="order_notes"
                                    x-model="orderNotes" 
                                    rows="2" 
                                    placeholder="Misal: Taruh di rak sepatu jika saya belum pulang." 
                                    class="w-full text-xs rounded-xl border-slate-200 p-2.5 leading-relaxed"
                                ></textarea>
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700">Metode Pembayaran <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label 
                                        class="flex items-center gap-2.5 p-3 rounded-2xl border transition cursor-pointer"
                                        :class="paymentMethod === 'xendit' ? 'bg-blue-50/70 border-blue-500 ring-1 ring-blue-500/20' : 'bg-white border-slate-200 hover:bg-slate-50'"
                                    >
                                        <input type="radio" name="payment_method" value="xendit" x-model="paymentMethod" class="text-blue-600 focus:ring-blue-500">
                                        <div class="text-xs">
                                            <strong class="font-bold text-slate-900 block">{!! ($isXenditActive ?? false) ? 'Transfer Bank & QRIS (Otomatis Xendit)' : 'Transfer Bank & QRIS' !!}</strong>
                                            <span class="text-[10px] text-slate-500">Otomatis / Manual</span>
                                        </div>
                                    </label>

                                    <label 
                                        class="flex items-center gap-2.5 p-3 rounded-2xl border transition cursor-pointer"
                                        :class="paymentMethod === 'wallet' ? 'bg-blue-50/70 border-blue-500 ring-1 ring-blue-500/20' : 'bg-white border-slate-200 hover:bg-slate-50'"
                                    >
                                        <input type="radio" name="payment_method" value="wallet" x-model="paymentMethod" class="text-blue-600 focus:ring-blue-500">
                                        <div class="text-xs">
                                            <strong class="font-bold text-slate-900 block">Saldo Dompet</strong>
                                            <span 
                                                class="text-[10px] font-bold"
                                                :class="userWalletBalance >= totalOrderPrice ? 'text-emerald-600' : 'text-rose-600'"
                                                x-text="'Rp ' + userWalletBalance.toLocaleString('id-ID')"
                                            ></span>
                                        </div>
                                    </label>
                                </div>
                                <template x-if="userWalletBalance < totalOrderPrice && paymentMethod === 'wallet'">
                                    <p class="text-[11px] font-bold text-rose-600 px-1">
                                        Saldo dompet tidak mencukupi (kurang Rp <span x-text="(totalOrderPrice - userWalletBalance).toLocaleString('id-ID')"></span>).
                                    </p>
                                </template>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                                <button 
                                    type="button" 
                                    @click="orderModalOpen = false" 
                                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    :disabled="paymentMethod === 'wallet' && userWalletBalance < totalOrderPrice"
                                    :class="paymentMethod === 'wallet' && userWalletBalance < totalOrderPrice ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-500 active:scale-98 cursor-pointer'"
                                    class="px-6 py-2.5 rounded-xl text-white text-xs font-extrabold shadow-md shadow-blue-600/20 text-center transition"
                                >
                                    <span x-text="paymentMethod === 'wallet' ? 'Bayar via Dompet' : 'Lanjut ke Pembayaran'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        {{-- MODAL PERINGATAN BUKAN PENGHUNI --}}
        <div 
            x-show="alertNotTenant" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="alertNotTenant = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl"
            >
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-xs border border-amber-200">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Khusus Akun Penghuni Kos</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Pemesanan layanan harian (laundry, cleaning, teknisi) hanya dapat dilakukan oleh akun yang terdaftar sebagai penghuni kos aktif di SevenKost.
                    </p>
                </div>
                <button 
                    type="button" 
                    @click="alertNotTenant = false" 
                    class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition cursor-pointer"
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
                class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl"
            >
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto shadow-xs border border-blue-200">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Perlu Sewa Kamar Kos Aktif</h3>
                    <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                        Layanan mitra SevenKost dikerjakan langsung ke kamar kos Anda. Anda harus memiliki sewa kamar aktif terlebih dahulu agar mitra terdekat dapat ditugaskan ke kos Anda.
                    </p>
                </div>
                <div class="space-y-2 pt-2">
                    <a 
                        href="{{ route('search') }}" 
                        class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition block text-center"
                    >
                        Cari &amp; Sewa Kos Sekarang
                    </a>
                    <button 
                        type="button" 
                        @click="alertNoActiveStay = false" 
                        class="w-full py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL PERINGATAN DI LUAR JANGKAUAN (> 5 KM) --}}
        <div 
            x-show="alertOutOfRange" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="alertOutOfRange = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 text-center space-y-4 shadow-2xl"
            >
                <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-xs border border-rose-200">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <span class="inline-block text-[10px] font-bold text-rose-600 uppercase tracking-wider bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full mb-1">
                        Radius Maksimal 5.0 KM
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Lokasi Kos di Luar Jangkauan</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Mitra terdekat untuk layanan <strong class="text-slate-900" x-text="selectedOutOfRangeService"></strong> berjarak <strong class="text-rose-600" x-text="selectedOutOfRangeDistance"></strong> dari kos Anda. Saat ini layanan hanya dapat dipesan dalam radius operasional maksimal <strong>5.0 km</strong> demi menjaga mutu dan kecepatan respon.
                    </p>
                </div>
                <button 
                    type="button" 
                    @click="alertOutOfRange = false" 
                    class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white font-bold text-xs shadow-md transition cursor-pointer"
                >
                    Saya Mengerti
                </button>
            </div>
        </div>

    </div>

</x-seven-layout>

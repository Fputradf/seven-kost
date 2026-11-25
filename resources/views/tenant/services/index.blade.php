<x-tenant-layout title="Layanan Kebutuhan Kos" :backUrl="route('tenant.dashboard')">

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
        class="pb-20"
    >

        @if(session('error'))
            <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- COMPACT MOBILE LOCATION / STAY BAR (TRUE MOBILE SIZE) --}}
        <div class="pt-0.5">
            @if($hasActiveBooking)
                <div class="p-3 rounded-2xl bg-white border border-emerald-200/90 shadow-2xs space-y-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 truncate">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span class="text-xs font-black text-slate-900 truncate">
                                {{ $tenantKostName }} &bull; {{ $tenantRoomName }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0">
                            Kamar Aktif
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100 gap-2">
                        <div class="truncate">
                            Penghuni: <strong class="text-slate-700">{{ $tenantOccupantName }}</strong>
                        </div>
                        @if(isset($hasOutOfRangeService) && $hasOutOfRangeService)
                            <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full shrink-0 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Di Luar Jangkauan (> 5 km)
                            </span>
                        @else
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full shrink-0 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Mitra Siap Melayani Hari Ini
                            </span>
                        @endif
                    </div>

                    {{-- PILIHAN KAMAR TUJUAN LAYANAN (RESPONSIF MOBILE - SCROLLABLE & TIDAK MELEBIHI PAGE) --}}
                    @if($activeBookings->count() > 1)
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block">
                                Pilih Kamar Tujuan Layanan:
                            </span>
                            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none w-full min-w-0">
                                @foreach($activeBookings as $bOption)
                                    @php $isSelected = $activeBooking && $activeBooking->id === $bOption->id; @endphp
                                    <a href="{{ route('tenant.services.index', ['kamar_id' => $bOption->id]) }}" 
                                       class="px-2.5 py-1 rounded-xl text-[11px] font-bold transition shrink-0 whitespace-nowrap max-w-[180px] sm:max-w-xs truncate {{ $isSelected ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
                                        {{ $bOption->kost->name }} ({{ $bOption->room?->name ?? 'Kamar' }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
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

        {{-- MAIN TAB SWITCHER (KATALOG LAYANAN vs PESANAN SAYA) --}}
        @php $isOrdersTab = request('tab') === 'orders'; @endphp
        <div class="grid grid-cols-2 p-1 bg-slate-200/80 rounded-2xl gap-1 text-xs font-bold">
            <a 
                href="{{ route('tenant.services.index', ['tab' => 'catalog']) }}"
                class="py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer text-center {{ !$isOrdersTab ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Katalog Layanan</span>
            </a>

            <a 
                href="{{ route('tenant.services.index', ['tab' => 'orders']) }}"
                class="py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 cursor-pointer text-center {{ $isOrdersTab ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Pesanan Saya</span>
                @if(isset($serviceOrders) && $serviceOrders->count() > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black bg-blue-600 text-white">
                        {{ $serviceOrders->count() }}
                    </span>
                @endif
            </a>
        </div>

        @if($isOrdersTab)
            {{-- TAB VIEW 2: PESANAN SAYA (RIWAYAT & STATUS LAYANAN) --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Riwayat & Status Pesanan</h3>
                        <p class="text-[11px] text-slate-500">Pantau proses pengerjaan layanan mitra langsung ke kamar kos Anda.</p>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500">
                        {{ isset($serviceOrders) ? $serviceOrders->count() : 0 }} Pesanan
                    </span>
                </div>

                @if(isset($serviceOrders) && $serviceOrders->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($serviceOrders as $ord)
                            @php
                                $ordPayload = $ord->payload ?? [];
                                $isPaid = $ord->isPaid();
                            @endphp
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
                                <div class="flex items-center justify-between gap-2 pb-2 border-b border-slate-100">
                                    <div>
                                        <span class="text-[10px] font-mono text-slate-400 block font-semibold">
                                            #{{ $ord->reference_id }}
                                        </span>
                                        <span class="text-[11px] text-slate-500">
                                            {{ $ord->created_at?->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($isPaid)
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                                Diproses Mitra
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                Menunggu Bayar
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-black text-slate-900">{{ $ordPayload['service_title'] ?? $ord->title }}</h4>
                                        <p class="text-xs text-blue-600 font-bold mt-0.5">
                                            {{ $ordPayload['package_name'] ?? '-' }} &bull; {{ $ordPayload['quantity'] ?? 1 }} {{ $ordPayload['package_unit'] ?? '' }}
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-[10px] text-slate-400 font-bold block">Total Biaya</span>
                                        <strong class="text-sm sm:text-base font-black text-slate-900">
                                            Rp {{ number_format($ord->amount, 0, ',', '.') }}
                                        </strong>
                                    </div>
                                </div>

                                <div class="bg-slate-50 rounded-xl p-2.5 text-xs text-slate-600 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Kamar Tujuan:</span>
                                        <strong class="text-slate-800">{{ $ordPayload['room_number'] ?? '-' }}</strong>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Jadwal Kedatangan:</span>
                                        <span class="font-bold text-slate-800">{{ $ordPayload['order_date'] ?? '-' }} ({{ $ordPayload['order_time'] ?? '-' }} WIB)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-500">Mitra:</span>
                                        <span class="font-medium text-slate-800">{{ $ordPayload['partner_name'] ?? 'Mitra Resmi SevenKost' }}</span>
                                    </div>
                                    @if(!empty($ordPayload['order_notes']))
                                        <div class="pt-1 text-[11px] text-slate-500 italic border-t border-slate-200/60">
                                            Catatan: "{{ $ordPayload['order_notes'] }}"
                                        </div>
                                    @endif
                                </div>

                                @if(!$isPaid)
                                    <div class="pt-1 flex gap-2">
                                        <a 
                                            href="{{ route('tenant.services.payment', $ord->reference_id) }}" 
                                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center shadow-xs transition active:scale-95 block"
                                        >
                                            Bayar Sekarang
                                        </a>
                                    </div>
                                @else
                                    <div class="pt-1">
                                        <a 
                                            href="{{ route('tenant.services.tracking', $ord->reference_id) }}" 
                                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs text-center shadow-xs transition active:scale-95 block"
                                        >
                                            Lacak Pesanan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-slate-800">Belum Ada Pesanan Layanan</h4>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto">
                                Anda belum pernah memesan layanan kebutuhan kos. Pilih layanan di tab katalog untuk memesan langsung ke kamar Anda.
                            </p>
                        </div>
                        <a 
                            href="{{ route('tenant.services.index', ['tab' => 'catalog']) }}" 
                            class="inline-flex items-center gap-1.5 py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition active:scale-95 cursor-pointer"
                        >
                            <span>Buka Katalog Layanan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        @else
            {{-- TAB VIEW 1: KATALOG LAYANAN --}}
            <div class="space-y-4">
            {{-- 2. CATEGORY FILTER TABS --}}
            <div class="pt-1">
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
            </div>
        </div>

        {{-- COMMERCIAL AD BANNER SLIDER (BR-11: PENAYANGAN BANNER BRAND & LAYANAN MITRA) --}}
        @php
            $svcBanners = [];
            if (isset($banners) && $banners->count() > 0) {
                foreach ($banners as $b) {
                    $svcBanners[] = [
                        'img' => $b->image_url,
                        'link' => $b->target_url ?: route('tenant.services.index'),
                        'alt' => $b->title,
                        'advertiser' => $b->advertiser,
                    ];
                }
            }
            if (empty($svcBanners)) {
                $svcBanners = [
                    ['img' => asset('images/frontend/banner-jakarta-wide.jpg'), 'link' => route('tenant.services.index'), 'alt' => 'Promo Layanan Laundry Kos', 'advertiser' => 'Seven Laundry'],
                    ['img' => asset('images/frontend/banner-bandung-wide.jpg'), 'link' => route('tenant.services.index'), 'alt' => 'Promo Bersih-Bersih Kos', 'advertiser' => 'Seven Clean'],
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
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/60 text-white text-[11px] font-semibold tracking-wider uppercase backdrop-blur-xs flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Iklan Sponsor & Promosi</span>
                                </div>
                                <div class="absolute bottom-3 left-3 right-28 max-w-sm space-y-0.5">
                                    @if(!empty($bItem['advertiser']))
                                        <span class="inline-block px-2 py-0.5 rounded-md bg-blue-600/90 text-white text-[9px] font-black uppercase tracking-wider backdrop-blur-xs">
                                            {{ $bItem['advertiser'] }}
                                        </span>
                                    @endif
                                    <h3 class="text-white text-xs sm:text-sm font-black drop-shadow-md line-clamp-1 leading-snug">
                                        {{ $bItem['alt'] }}
                                    </h3>
                                </div>
                                <div class="absolute bottom-3 right-3">
                                    <div class="text-[11px] sm:text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 px-3 py-1.5 rounded-lg backdrop-blur-xs shadow-md transition">
                                        Lihat &rarr;
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

                        {{-- Pilihan Paket Menu / Layanan (Interactive Selectable Rows) --}}
                        <div class="pt-2 border-t border-slate-100 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pilihan Paket:</span>
                                @if($isAvail && ($svc['is_in_range'] ?? true))
                                    <span class="text-[10px] text-blue-600 font-bold">Pilih Paket</span>
                                @endif
                            </div>
                            <div class="space-y-1.5">
                                @foreach($svc['packages'] as $pkg)
                                    @php
                                        $isInRange = $svc['is_in_range'] ?? true;
                                    @endphp
                                    @if($isAvail && $isInRange)
                                        @auth
                                            @if(auth()->user()->hasRole('penghuni'))
                                                <button 
                                                    type="button"
                                                    @click="openOrderModal(
                                                        '{{ $svc['id'] }}', 
                                                        '{{ addslashes($svc['title']) }}', 
                                                        '{{ addslashes($pkg['name']) }}', 
                                                        {{ $pkg['price'] }}, 
                                                        '{{ addslashes($pkg['unit']) }}',
                                                        '{{ addslashes($svc['local_partner']['partner_name'] ?? '') }}',
                                                        {{ $svc['distance_km'] ?? 1.4 }}
                                                    )"
                                                    class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-blue-50/80 hover:border-blue-300 border border-slate-100 text-xs transition active:scale-98 text-left group cursor-pointer"
                                                >
                                                    <span class="font-medium text-slate-800 group-hover:text-blue-900 truncate">{{ $pkg['name'] }}</span>
                                                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                                        <strong class="text-blue-600 font-bold">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                                        <span class="text-[10px] text-slate-400 group-hover:text-blue-600 font-bold">&rarr;</span>
                                                    </div>
                                                </button>
                                            @else
                                                <button 
                                                    type="button"
                                                    @click="alertNotTenant = true"
                                                    class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 text-xs text-left"
                                                >
                                                    <span class="font-medium text-slate-800 truncate">{{ $pkg['name'] }}</span>
                                                    <strong class="text-blue-600 font-bold shrink-0 ml-2">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                                </button>
                                            @endif
                                        @else
                                            <a 
                                                href="{{ route('login') }}"
                                                class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-blue-50/80 hover:border-blue-300 border border-slate-100 text-xs text-left"
                                            >
                                                <span class="font-medium text-slate-800 truncate">{{ $pkg['name'] }}</span>
                                                <strong class="text-blue-600 font-bold shrink-0 ml-2">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                            </a>
                                        @endauth
                                    @else
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50/70 border border-slate-100 text-xs opacity-75">
                                            <span class="font-medium text-slate-600 truncate">{{ $pkg['name'] }}</span>
                                            <strong class="text-slate-500 font-bold shrink-0 ml-2">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</strong>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Mitra Lokal Sekitar Kos --}}
                        @if(isset($svc['local_partner']))
                            @if(!($svc['is_in_range'] ?? true))
                                <div class="p-2.5 rounded-xl bg-rose-50/90 border border-rose-200/90 flex items-center justify-between text-[11px] gap-2">
                                    <span class="font-bold text-rose-950 flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span class="truncate">{{ $svc['local_partner']['partner_name'] }}</span>
                                    </span>
                                    <span class="text-rose-700 font-bold text-[10px] shrink-0 bg-rose-100/90 px-2 py-0.5 rounded-md">
                                        Di Luar Jangkauan ({{ $svc['local_partner']['distance_approx'] }})
                                    </span>
                                </div>
                            @else
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
                        @endif

                        {{-- Tombol Pesan Sekarang --}}
                        <div class="pt-1">
                            @if($isAvail)
                                @if(!($svc['is_in_range'] ?? true))
                                    <button 
                                        @click="alertOutOfRange = true; selectedOutOfRangeDistance = '{{ $svc['local_partner']['distance_approx'] ?? ($svc['distance_km'] . ' km') }}'; selectedOutOfRangeService = '{{ addslashes($svc['title']) }}'" 
                                        type="button" 
                                        class="w-full py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/90 text-xs sm:text-sm font-bold shadow-2xs transition active:scale-98 flex items-center justify-center gap-1.5"
                                    >
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        <span>Di Luar Jangkauan (> 5 km)</span>
                                    </button>
                                @else
                                    @auth
                                        @if(auth()->user()->hasRole('penghuni'))
                                            <button 
                                                @click="openOrderModal(
                                                    '{{ $svc['id'] }}', 
                                                    '{{ addslashes($svc['title']) }}', 
                                                    '{{ addslashes($svc['packages'][0]['name']) }}', 
                                                    {{ $svc['packages'][0]['price'] }}, 
                                                    '{{ addslashes($svc['packages'][0]['unit']) }}',
                                                    '{{ addslashes($svc['local_partner']['partner_name'] ?? '') }}',
                                                    {{ $svc['distance_km'] ?? 1.4 }}
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
                                @endif
                            @else
                                <button 
                                    type="button" 
                                    disabled 
                                    class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-xs sm:text-sm font-bold cursor-not-allowed flex items-center justify-center gap-1 shadow-2xs"
                                >
                                    @if(($svc['has_real_partner'] ?? false) && !($svc['is_open'] ?? true))
                                        Toko Sedang Tutup
                                    @elseif(($svc['local_partner']['delivery_time'] ?? '') === 'Tidak Tersedia')
                                        Tidak Tersedia di {{ $currentRegional['badge_city'] }}
                                    @else
                                        Segera Hadir
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
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
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900" x-text="selectedServiceName"></h3>
                            </div>
                            <button @click="orderModalOpen = false" class="text-slate-400 hover:text-slate-700 cursor-pointer">
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
                                            class="w-full max-w-full text-xs sm:text-sm font-bold text-slate-900 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
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

                            {{-- Pilihan Metode Pemenuhan Pesanan (Antar-Jemput vs Antar & Ambil Sendiri) --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">
                                    Metode Penyerahan / Pengambilan <span class="text-rose-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    {{-- Opsi 1: Kurir Jemput & Antar ke Kamar Kos --}}
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
                                        <p class="text-[11px] text-slate-500 mt-1">Kurir mitra jemput cucian di kos & antar kembali setelah selesai.</p>
                                        <span class="text-[11px] font-bold text-blue-700 mt-2" x-text="'+ Rp ' + deliveryFee.toLocaleString('id-ID') + ' ongkir'"></span>
                                    </label>

                                    {{-- Opsi 2: Antar & Ambil Sendiri ke Toko Mitra --}}
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

                            {{-- Jumlah Unit / Kuantitas & Rincian Biaya --}}
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-blue-50/70 border border-blue-100 space-y-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <span class="text-[11px] text-slate-500 font-bold uppercase block">Jumlah:</span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button 
                                                type="button" 
                                                @click="quantity = Math.max(1, quantity - 1)" 
                                                class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center text-sm shadow-xs active:scale-95 cursor-pointer"
                                            >-</button>
                                            <input 
                                                type="number" 
                                                name="quantity"
                                                x-model.number="quantity" 
                                                min="1" 
                                                max="50"
                                                class="w-14 text-center font-black text-slate-900 text-sm py-1 border border-slate-200 rounded-lg bg-white"
                                            >
                                            <button 
                                                type="button" 
                                                @click="quantity = quantity + 1" 
                                                class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center text-sm shadow-xs active:scale-95 cursor-pointer"
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

                                <div class="pt-1.5 border-t border-blue-200/60 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800">Total Tagihan:</span>
                                    <strong class="text-base sm:text-lg font-black text-blue-600" x-text="'Rp ' + totalOrderPrice.toLocaleString('id-ID')"></strong>
                                </div>
                            </div>

                            {{-- Mitra Pelaksana --}}
                            <template x-if="selectedPartnerName">
                                <div class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Mitra:</span>
                                    <span class="font-bold text-slate-800 truncate" x-text="selectedPartnerName"></span>
                                </div>
                            </template>

                            @if($activeBookings->count() > 1)
                                {{-- Pemilihan Kamar Aktif Jika Multi-Kamar --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700">Kamar Tujuan <span class="text-rose-500">*</span></label>
                                    <select 
                                        name="selected_kamar_id"
                                        @change="
                                            let selOpt = $event.target.selectedOptions[0];
                                            selectedBookingId = selOpt.value;
                                            kostAddress = selOpt.dataset.address;
                                            roomNumber = selOpt.dataset.room;
                                            occupantName = selOpt.dataset.occupant;
                                            occupantPhone = selOpt.dataset.phone;
                                        "
                                        class="w-full max-w-full truncate text-xs sm:text-sm font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
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
                                                {{ $bKostName }} ({{ $bRoomName }}) - {{ $bReg }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Alamat Kos & No Kamar --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">Alamat Kos <span class="text-rose-500">*</span></label>
                                <input 
                                    type="text" 
                                    name="kost_address"
                                    x-model="kostAddress" 
                                    placeholder="Alamat lengkap kos" 
                                    required 
                                    class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700">Nomor Kamar <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="room_number"
                                        x-model="roomNumber" 
                                        placeholder="Nomor kamar" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700">Waktu Kunjungan</label>
                                    <select name="order_time" x-model="orderTime" class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white">
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
                                    <label class="block text-xs font-bold text-slate-700">Nama Penghuni <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="occupant_name"
                                        x-model="occupantName" 
                                        placeholder="Nama lengkap" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                                    <input 
                                        type="tel" 
                                        name="occupant_phone"
                                        x-model="occupantPhone" 
                                        placeholder="Nomor WhatsApp" 
                                        required 
                                        class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                    >
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">Tanggal Layanan</label>
                                <input 
                                    type="date" 
                                    name="order_date"
                                    x-model="orderDate" 
                                    min="{{ date('Y-m-d') }}" 
                                    class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">Catatan (Opsional)</label>
                                <textarea 
                                    name="order_notes"
                                    x-model="orderNotes" 
                                    rows="2" 
                                    placeholder="Catatan untuk mitra" 
                                    class="w-full text-sm rounded-xl border-slate-200 p-2.5 leading-relaxed"
                                ></textarea>
                            </div>

                            {{-- PILIHAN METODE PEMBAYARAN --}}
                            <div class="space-y-2 pt-2 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700">
                                    Metode Pembayaran <span class="text-rose-500">*</span>
                                </label>

                                <div class="space-y-2">
                                    {{-- Option 1: Xendit --}}
                                    <label 
                                        class="flex items-center gap-3 p-3 rounded-2xl border transition cursor-pointer"
                                        :class="paymentMethod === 'xendit' 
                                            ? 'bg-blue-50/70 border-blue-500 ring-1 ring-blue-500/20' 
                                            : 'bg-white border-slate-200 hover:bg-slate-50'"
                                    >
                                        <input 
                                            type="radio" 
                                            name="payment_method" 
                                            value="xendit" 
                                            x-model="paymentMethod" 
                                            class="text-blue-600 focus:ring-blue-500"
                                        >
                                        <div class="flex-1 min-w-0">
                                            <strong class="text-xs sm:text-sm font-bold text-slate-900 block">
                                                {!! ($isXenditActive ?? false) ? 'Transfer Bank & QRIS (Otomatis Xendit)' : 'Transfer Bank & QRIS (Manual)' !!}
                                            </strong>
                                        </div>
                                    </label>

                                    {{-- Option 2: Saldo Dompet --}}
                                    <label 
                                        class="flex items-center gap-3 p-3 rounded-2xl border transition cursor-pointer"
                                        :class="paymentMethod === 'wallet' 
                                            ? 'bg-blue-50/70 border-blue-500 ring-1 ring-blue-500/20' 
                                            : 'bg-white border-slate-200 hover:bg-slate-50'"
                                    >
                                        <input 
                                            type="radio" 
                                            name="payment_method" 
                                            value="wallet" 
                                            x-model="paymentMethod" 
                                            class="text-blue-600 focus:ring-blue-500"
                                        >
                                        <div class="flex-1 min-w-0 flex items-center justify-between">
                                            <strong class="text-xs sm:text-sm font-bold text-slate-900">
                                                Saldo Dompet
                                            </strong>
                                            <span 
                                                class="text-[11px] font-bold"
                                                :class="userWalletBalance >= totalOrderPrice ? 'text-slate-600' : 'text-rose-600'"
                                                x-text="'Rp ' + userWalletBalance.toLocaleString('id-ID')"
                                            ></span>
                                        </div>
                                    </label>
                                    <template x-if="userWalletBalance < totalOrderPrice && paymentMethod === 'wallet'">
                                        <p class="text-[11px] font-bold text-rose-600 px-1">
                                            Saldo tidak mencukupi (kurang Rp <span x-text="(totalOrderPrice - userWalletBalance).toLocaleString('id-ID')"></span>).
                                        </p>
                                    </template>
                                </div>
                            </div>

                            {{-- Tombol Konfirmasi Pesanan & Pembayaran --}}
                            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                                <button 
                                    type="button" 
                                    @click="orderModalOpen = false" 
                                    class="w-full sm:w-auto px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                                >
                                    Batal
                                </button>
                                <button 
                                    type="submit" 
                                    :disabled="paymentMethod === 'wallet' && userWalletBalance < totalOrderPrice"
                                    :class="paymentMethod === 'wallet' && userWalletBalance < totalOrderPrice ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 active:scale-98 cursor-pointer'"
                                    class="w-full sm:flex-1 py-3 rounded-xl text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 text-center transition"
                                >
                                    <span x-text="paymentMethod === 'wallet' ? 'Bayar Sekarang' : 'Lanjut ke Pembayaran'"></span>
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
                        Pemesanan layanan harian (laundry, cleaning, katering) hanya dapat dilakukan oleh akun yang terdaftar sebagai penghuni kos aktif di SevenKost.
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

        {{-- MODAL PERINGATAN DI LUAR JANGKAUAN (> 5 KM) --}}
        <div 
            x-show="alertOutOfRange" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="alertOutOfRange = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 text-center space-y-4 shadow-2xl animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-xs border border-rose-200">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <span class="inline-block text-[11px] font-bold text-rose-600 uppercase tracking-wider bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full mb-1">
                        Radius Maksimal 5.0 KM
                    </span>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Lokasi Kos di Luar Jangkauan</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                        Mitra terdekat untuk layanan <strong class="text-slate-900" x-text="selectedOutOfRangeService"></strong> berjarak <strong class="text-rose-600" x-text="selectedOutOfRangeDistance"></strong> dari kos Anda. Saat ini layanan hanya dapat dipesan dalam radius operasional maksimal <strong>5.0 km</strong> demi menjaga kualitas dan kecepatan respon.
                    </p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-[11px] text-slate-500 text-left space-y-1">
                    <div class="font-bold text-slate-700">Informasi:</div>
                    <div>• Jarak kos melebihi batas 5.0 km dari titik operasional mitra regional.</div>
                    <div>• Anda dapat berkoordinasi langsung dengan pengelola kos untuk rekomendasi vendor sekitar kos.</div>
                </div>
                <button 
                    type="button" 
                    @click="alertOutOfRange = false" 
                    class="w-full py-3 rounded-xl bg-slate-900 hover:bg-black text-white font-bold text-sm shadow-md transition"
                >
                    Saya Mengerti
                </button>
            </div>
        </div>

    </div>

</x-tenant-layout>

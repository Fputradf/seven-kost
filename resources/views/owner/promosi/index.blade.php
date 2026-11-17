<x-owner-layout title="Pusat Promosi & Kemitraan">

    <div 
        class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8"
        x-data="{
            activeTab: '{{ request()->query('tab', 'vip') }}',
            vipModalOpen: false,
            selectedVipId: '3_months',
            selectedVipName: '{{ $vipPackages['3_months']['name'] ?? 'VIP 3 Bulan' }}',
            selectedVipPrice: '{{ $vipPackages['3_months']['price_label'] ?? 'Rp 129.000' }}',
            bannerDuration: 90,
            bannerRates: {
                @foreach($bannerPackages as $dKey => $bVal)
                    {{ $dKey }}: { price: {{ (int) $bVal['price'] }}, name: '{{ $bVal['name'] }}' },
                @endforeach
            },
            getBannerTotal() {
                return this.bannerRates[this.bannerDuration]?.price || 0;
            },
            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            },
            openVipModal(id = null, name = null, price = null) {
                if (id) this.selectedVipId = id;
                if (name) this.selectedVipName = name;
                if (price) this.selectedVipPrice = price;
                this.vipModalOpen = true;
            },
            scrollToBannerForm() {
                this.activeTab = 'banner';
                this.$nextTick(() => {
                    const el = document.getElementById('owner-banner-order-section');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        }"
    >

        {{-- Header Card with Hero Background Image & Sapphire Blue Gradient --}}
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-7 text-white shadow-lg shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, rgba(15, 61, 124, 0.94) 0%, rgba(27, 92, 184, 0.90) 50%, rgba(37, 99, 235, 0.88) 100%);">
            <img 
                src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                alt="Building Backdrop" 
                class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-25 pointer-events-none"
            >
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs text-blue-200 font-medium">
                        <a href="{{ route('owner.dashboard') }}" class="hover:underline">Dashboard</a>
                        <span>/</span>
                        <span class="text-white font-semibold">Pusat Promosi & VIP</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Pusat Promosi & Kemitraan
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 font-medium max-w-2xl leading-relaxed">
                        Tingkatkan keterisian hunian kos Anda dengan <strong>Akun VIP Pemilik 👑</strong> serta kelola penayangan <strong>Iklan Banner Komersial</strong> di beranda SevenKost.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <button 
                        type="button" 
                        @click="openVipModal('3_months', '{{ $vipPackages['3_months']['name'] ?? 'VIP 3 Bulan' }}', '{{ $vipPackages['3_months']['price_label'] ?? 'Rp 129.000' }}')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-blue-50 text-blue-700 text-xs sm:text-sm font-bold shadow-md transition cursor-pointer"
                    >
                        <span>👑 Upgrade Akun VIP</span>
                    </button>
                    <button 
                        type="button"
                        @click="scrollToBannerForm()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/30 hover:bg-white/15 text-white text-xs sm:text-sm font-semibold transition cursor-pointer"
                    >
                        <span>+ Pasang Banner</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center gap-3 text-sm font-semibold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- 1. Metric Summary Cards (Compact 4-Card Grid) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Status Akun Pemilik --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Status Akun Pemilik</span>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-xl sm:text-2xl font-black {{ $isVip ? 'text-amber-500' : 'text-slate-900' }}">
                            {{ $isVip ? 'Akun VIP 👑' : 'Akun Biasa' }}
                        </span>
                        @if($isVip)
                            <span class="px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-black uppercase">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold uppercase">
                                Standar
                            </span>
                        @endif
                    </div>
                </div>
                <span class="text-xs text-slate-500 mt-2 block font-medium">
                    {{ $isVip ? 'Prioritas baris teratas & kuota unlimited' : 'Urutan standar (Maks. 1 properti)' }}
                </span>
            </div>

            {{-- Kuota Portofolio Kos --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Portofolio Kos</span>
                    <span class="text-xl sm:text-2xl font-black text-blue-600 mt-1.5 block tabular-nums">
                        {{ $kostsCount }} <span class="text-sm font-semibold text-slate-500">Kos</span>
                    </span>
                </div>
                <span class="text-xs text-slate-500 mt-2 block font-medium">
                    {{ $isVip ? 'Pendaftaran kos: Unlimited' : 'Batas kuota gratis: 1 Kos' }}
                </span>
            </div>

            {{-- Banner Komersial Aktif --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Banner Sponsorship</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-600 mt-1.5 block tabular-nums">
                        {{ $activeBannersCount }} <span class="text-sm font-semibold text-slate-500">Slot Tayang</span>
                    </span>
                </div>
                <span class="text-xs text-slate-500 mt-2 block font-medium">
                    Slot iklan visual di halaman beranda
                </span>
            </div>

            {{-- Total Klik Banner --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Klik Banner</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 mt-1.5 block tabular-nums">
                        {{ number_format($totalBannerClicks) }}
                    </span>
                </div>
                <span class="text-xs text-slate-500 mt-2 block font-medium">
                    Kunjungan langsung ke tautan sponsor Anda
                </span>
            </div>
        </div>

        {{-- Segmented Dual-Tab Switcher (Sama Seperti di ads-packages.blade.php) --}}
        <div class="flex justify-center">
            <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200/80 shadow-xs max-w-md w-full">
                {{-- TAB 1: Akun VIP Pemilik --}}
                <button 
                    type="button"
                    @click="activeTab = 'vip'"
                    :class="activeTab === 'vip' ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                    class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>👑 Akun VIP Pemilik</span>
                </button>

                {{-- TAB 2: Iklan Banner --}}
                <button 
                    type="button"
                    @click="activeTab = 'banner'"
                    :class="activeTab === 'banner' ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                    class="flex-1 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>🎨 Banner Komersial Beranda</span>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB A: AKUN VIP PEMILIK 👑                                                --}}
        {{-- ========================================================================= --}}
        <div x-show="activeTab === 'vip'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6 sm:space-y-8">
            
            {{-- 1. Pratinjau Listing Kos Anda --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Pratinjau Status Listing Kos Anda</span>
                            @if($isVip)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-200">
                                    👑 Tercover VIP
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                    Akun Standar
                                </span>
                            @endif
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Lihat bagaimana properti Anda ditampilkan di katalog pencarian SevenKost saat ini.
                        </p>
                    </div>
                    <div>
                        @if(! $isVip)
                            <button 
                                type="button" 
                                @click="openVipModal('3_months', '{{ $vipPackages['3_months']['name'] ?? 'VIP 3 Bulan' }}', '{{ $vipPackages['3_months']['price_label'] ?? 'Rp 129.000' }}')"
                                class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-xs font-bold shadow-xs transition inline-flex items-center gap-1.5"
                            >
                                <span>👑 Buka Akses VIP untuk Kos Ini</span>
                            </button>
                        @endif
                    </div>
                </div>

                @if($myKosts->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($myKosts as $kost)
                            @php
                                $coverImg = $kost->primaryImage 
                                    ? (str_starts_with($kost->primaryImage->file_path, 'http') ? $kost->primaryImage->file_path : asset('storage/' . $kost->primaryImage->file_path))
                                    : asset('images/frontend/kost-1-hd.jpg');
                            @endphp
                            <div class="rounded-xl border {{ $isVip ? 'border-amber-400 ring-2 ring-amber-400/20 bg-amber-50/10' : 'border-slate-200/90 bg-white' }} overflow-hidden flex flex-col justify-between hover:shadow-sm transition">
                                <div>
                                    {{-- Thumbnail Foto Kos --}}
                                    <div class="relative h-40 w-full bg-slate-100 overflow-hidden">
                                        <img 
                                            src="{{ $coverImg }}" 
                                            alt="{{ $kost->name }}" 
                                            class="w-full h-full object-cover"
                                            onerror="this.onerror=null; this.src='{{ asset('images/frontend/kost-1-hd.jpg') }}'"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                                        {{-- Badge Tipe Kos --}}
                                        <div class="absolute top-2.5 left-2.5">
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-white/95 text-slate-800 shadow-xs capitalize">
                                                Kos {{ $kost->type }}
                                            </span>
                                        </div>

                                        {{-- Badge VIP / Standar --}}
                                        <div class="absolute top-2.5 right-2.5">
                                            @if($isVip)
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-black bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 shadow-xs flex items-center gap-1">
                                                    <span>👑 VIP Unggulan</span>
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                                    Listing Standar
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Harga di cover --}}
                                        <div class="absolute bottom-2.5 left-2.5 right-2.5 flex items-center justify-between text-white">
                                            <span class="text-xs sm:text-sm font-extrabold drop-shadow-xs">
                                                Rp {{ number_format($kost->price_start, 0, ',', '.') }} <span class="text-[10px] font-normal">/ {{ $kost->rent_type }}</span>
                                            </span>
                                            <span class="text-[11px] text-slate-200">
                                                {{ $kost->rooms->sum('available_rooms') }} kamar sisa
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Detail Informasi Kos --}}
                                    <div class="p-4 space-y-1.5">
                                        <h3 class="font-bold text-sm text-slate-900 leading-snug line-clamp-1">
                                            {{ $kost->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 line-clamp-1">
                                            {{ $kost->address ?: 'Alamat kos belum diisi' }}
                                        </p>

                                        @if($isVip)
                                            <div class="p-2 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-center gap-1.5 mt-2">
                                                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <span class="font-bold text-[11px]">Prioritas Baris Teratas & Pin Emas Aktif</span>
                                            </div>
                                        @else
                                            <div class="p-2 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-center justify-between mt-2">
                                                <span class="text-[11px]">Peringkat: <strong>Standar</strong></span>
                                                <button type="button" class="text-blue-600 font-bold hover:underline text-[11px]" @click="openVipModal('3_months', '{{ $vipPackages['3_months']['name'] ?? 'VIP 3 Bulan' }}', '{{ $vipPackages['3_months']['price_label'] ?? 'Rp 129.000' }}')">
                                                    Upgrade VIP →
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-4 pt-0">
                                    <a 
                                        href="{{ route('owner.kosts.edit', $kost) }}" 
                                        class="block w-full py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold text-center transition"
                                    >
                                        Kelola Properti Kos
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Ilustrasi Visual Komparasi Kos Standar vs Kos VIP jika belum ada kos --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-2">
                            <div class="relative h-36 rounded-lg overflow-hidden bg-slate-200">
                                <img src="{{ asset('images/frontend/kost-2-hd.jpg') }}" alt="Ilustrasi Kos Standar" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-800/80 text-white">Listing Biasa</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Akun Biasa (Gratis)</span>
                                <h4 class="font-bold text-sm text-slate-900">Kos Standar di Aplikasi</h4>
                                <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                    Tampil di urutan organik, batas 1 properti, pin biru peta umum, dan kapasitas foto 5 galeri.
                                </p>
                            </div>
                        </div>

                        <div class="rounded-xl border-2 border-amber-400 bg-amber-50/30 p-4 space-y-2 shadow-xs">
                            <div class="relative h-36 rounded-lg overflow-hidden bg-slate-200 ring-2 ring-amber-400">
                                <img src="{{ asset('images/frontend/kost-1-hd.jpg') }}" alt="Ilustrasi Kos VIP" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-black bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 shadow-xs flex items-center gap-1">
                                        👑 Lencana Emas VIP
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-[11px] font-extrabold text-amber-600 uppercase tracking-wider block">Akun VIP Pemilik 👑</span>
                                <h4 class="font-extrabold text-sm text-slate-900">Kos Prioritas Baris Teratas</h4>
                                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                                    Muncul di baris teratas pencarian, border highlight emas, kuota kos unlimited, pin peta emas, dan foto 15 HD.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 2. Pilihan 3 Paket Berlangganan VIP --}}
            <div class="space-y-4">
                <div class="text-center max-w-xl mx-auto space-y-1">
                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900">
                        Pilihan Paket Berlangganan VIP Anda
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Klik paket pilihan Anda untuk mengaktifkan status VIP dan menikmati seluruh keuntungan.
                    </p>
                </div>

                {{-- 3 Kartu Paket VIP --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch pt-1">
                    @foreach($vipPackages as $vId => $vPkg)
                        @php
                            $isPop = $vPkg['is_popular'] ?? false;
                        @endphp
                        <div 
                            @click="selectedVipId = '{{ $vId }}'; selectedVipName = '{{ $vPkg['name'] }}'; selectedVipPrice = '{{ $vPkg['price_label'] }}'"
                            :class="selectedVipId === '{{ $vId }}' ? 'border-2 border-blue-500 ring-4 ring-blue-500/20 shadow-md shadow-blue-500/10 bg-white' : 'border border-slate-200/90 hover:border-blue-300 hover:shadow-xs bg-white'"
                            class="relative rounded-2xl p-5 sm:p-6 flex flex-col justify-between transition-all duration-200 cursor-pointer select-none"
                        >
                            @if($isPop)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-md bg-gradient-to-r from-blue-600 to-sky-500 text-white font-extrabold text-[10px] uppercase tracking-wider shadow-xs">
                                    Paling Populer
                                </div>
                            @endif

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-extrabold text-base text-slate-900">{{ $vPkg['name'] }}</h4>
                                        <span class="text-xs font-semibold text-slate-500">{{ $vPkg['duration_label'] }}</span>
                                    </div>
                                    <div 
                                        class="w-6 h-6 rounded-full flex items-center justify-center transition-all duration-200 shrink-0"
                                        :class="selectedVipId === '{{ $vId }}' ? 'bg-blue-600 text-white ring-2 ring-blue-400/50' : 'border-2 border-slate-300 text-transparent'"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="flex items-baseline gap-1 my-2">
                                    <span class="text-2xl sm:text-3xl font-black text-blue-600">
                                        {{ $vPkg['price_label'] }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $vPkg['period'] }}</span>
                                </div>

                                <p class="text-xs text-slate-500 leading-relaxed">
                                    {{ $vPkg['description'] }}
                                </p>

                                <ul class="space-y-2 text-xs text-slate-600 pt-3 border-t border-slate-100">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Unlimited kuota pendaftaran kos</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Prioritas baris teratas hasil pencarian</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span class="font-bold text-amber-800">Lencana emas eksklusif "VIP" 👑</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Pin emas menonjol di peta Leaflet</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-5 mt-1">
                                <button 
                                    type="button"
                                    @click.stop="openVipModal('{{ $vId }}', '{{ $vPkg['name'] }}', '{{ $vPkg['price_label'] }}')"
                                    :class="selectedVipId === '{{ $vId }}' ? 'bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-bold shadow-xs' : 'bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold'"
                                    class="w-full py-2.5 rounded-xl text-xs transition text-center"
                                >
                                    <span x-text="selectedVipId === '{{ $vId }}' ? 'Pilih Paket Ini ✓' : 'Pilih Paket'"></span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Tabel Komparasi Fitur Akun Biasa vs Akun VIP (Proporsional & Bebas Overflow) --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-base font-extrabold text-slate-900">
                        Perbandingan Lengkap: Akun Biasa vs Akun VIP Pemilik
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Transparan tanpa biaya tersembunyi. Didesain untuk memaksimalkan eksposur dan kemudahan kelola kos.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/90">
                                <th class="py-3.5 px-4 sm:px-6 font-bold text-slate-700 w-1/2">
                                    Fitur & Keunggulan
                                </th>
                                <th class="py-3.5 px-4 sm:px-6 font-bold text-slate-600 w-1/4 text-center">
                                    Akun Biasa (Gratis)
                                </th>
                                <th class="py-3.5 px-4 sm:px-6 font-black text-blue-600 w-1/4 text-center bg-blue-50/60 border-l border-blue-200">
                                    Akun VIP Pemilik 👑
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            {{-- Row 1: Biaya Berlangganan --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Biaya Berlangganan</div>
                                    <div class="text-[11px] text-slate-500">Tarif komitmen keanggotaan platform</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-700 font-bold">
                                    Rp 0 (Gratis Selamanya)
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-black text-blue-600 bg-blue-50/30 border-l border-blue-100">
                                    Mulai Rp 49.000 / bulan
                                </td>
                            </tr>

                            {{-- Row 2: Batas Kuota Kos --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Batas Kuota Properti Kos</div>
                                    <div class="text-[11px] text-slate-500">Jumlah listing kos yang dapat didaftarkan</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-700 font-medium">
                                    Maksimal 1 Properti Kos
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-emerald-600 bg-blue-50/30 border-l border-blue-100">
                                    Unlimited (Bebas Tambah Kos)
                                </td>
                            </tr>

                            {{-- Row 3: Peringkat Hasil Pencarian --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Peringkat Hasil Pencarian</div>
                                    <div class="text-[11px] text-slate-500">Posisi kos saat pencari melakukan pencarian di aplikasi</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-600 font-medium">
                                    Urutan Standar (Organik)
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-blue-600 bg-blue-50/30 border-l border-blue-100">
                                    Prioritas Baris Teratas (Top Ranking)
                                </td>
                            </tr>

                            {{-- Row 4: Lencana Eksklusif --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Lencana Eksklusif di Kartu Kos</div>
                                    <div class="text-[11px] text-slate-500">Identitas tepercaya di mata calon penyewa</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-400 font-medium">
                                    Standar (Tanpa Lencana)
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-amber-600 bg-blue-50/30 border-l border-blue-100">
                                    Lencana Emas "VIP" 👑
                                </td>
                            </tr>

                            {{-- Row 5: Penanda Peta Leaflet --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Penanda di Peta Leaflet</div>
                                    <div class="text-[11px] text-slate-500">Tampilan pin kos pada eksplorasi peta</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-600 font-medium">
                                    Pin Biru Standar
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-blue-600 bg-blue-50/30 border-l border-blue-100">
                                    Pin Emas Menonjol (Highlight Pin)
                                </td>
                            </tr>

                            {{-- Row 6: Batas Foto Galeri --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Batas Unggah Foto Galeri</div>
                                    <div class="text-[11px] text-slate-500">Jumlah foto untuk kamar & fasilitas</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-600 font-medium">
                                    Maksimal 5 Foto
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-blue-600 bg-blue-50/30 border-l border-blue-100">
                                    Hingga 15 Foto HD
                                </td>
                            </tr>

                            {{-- Row 7: Kecepatan Verifikasi --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Kecepatan Verifikasi Iklan</div>
                                    <div class="text-[11px] text-slate-500">Listing baru atau update kamar langsung tayang</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-600 font-medium">
                                    Antrean Standar
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-emerald-600 bg-blue-50/30 border-l border-blue-100">
                                    Prioritas Fast-Track Admin
                                </td>
                            </tr>

                            {{-- Row 8: Layanan Bantuan --}}
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="font-bold text-slate-900">Layanan Bantuan Sistem (In-App)</div>
                                    <div class="text-[11px] text-slate-500">Penanganan kendala akun & listing di aplikasi</div>
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center text-slate-600 font-medium">
                                    Tiket Bantuan Standar
                                </td>
                                <td class="py-3.5 px-4 sm:px-6 text-center font-bold text-blue-600 bg-blue-50/30 border-l border-blue-100">
                                    Prioritas Tiket Bantuan In-App
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- TAB B: BANNER KOMERSIAL BERANDA (SPONSORSHIP)                             --}}
        {{-- ========================================================================= --}}
        <div x-show="activeTab === 'banner'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-4xl mx-auto space-y-6" style="display: none;">
            
            {{-- 1. DAFTAR BANNER AKTIF PEMILIK --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                            Banner Komersial Beranda (Sponsorship)
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Slot iklan visual eksklusif di beranda SevenKost yang dilihat puluhan ribu pencari kos mahasiswa & pekerja.
                        </p>
                    </div>
                    <div>
                        <button 
                            type="button"
                            @click="scrollToBannerForm()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-bold shadow-xs transition shrink-0 cursor-pointer"
                        >
                            <span>+ Pasang Banner Baru</span>
                        </button>
                    </div>
                </div>

                @if($banners->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                            <thead class="bg-slate-50/90 text-xs font-bold text-slate-600 uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="py-3 px-4 sm:px-5 w-7/12">Banner & Informasi Brand</th>
                                    <th class="py-3 px-4 sm:px-5 w-3/12">Periode & Status</th>
                                    <th class="py-3 px-4 sm:px-5 w-2/12 text-right">Performa Klik</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($banners as $banner)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        {{-- Kolom 1: Banner Thumbnail + Judul + Brand + Link URL --}}
                                        <td class="py-3.5 px-4 sm:px-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-24 h-12 sm:w-28 sm:h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 border border-slate-200 shadow-2xs">
                                                    <img 
                                                        src="{{ $banner->image_url }}" 
                                                        alt="{{ $banner->title }}" 
                                                        class="w-full h-full object-cover"
                                                        onerror="this.onerror=null; this.src='{{ asset('images/frontend/banner-jakarta-wide.jpg') }}'"
                                                    >
                                                </div>
                                                <div class="min-w-0 space-y-0.5">
                                                    <strong class="text-slate-900 block font-bold text-xs sm:text-sm leading-snug line-clamp-1">{{ $banner->title }}</strong>
                                                    <div class="flex flex-wrap items-center gap-x-2 text-[11px] text-slate-500">
                                                        <span>{{ $banner->advertiser_name }}</span>
                                                        @if($banner->target_url)
                                                            <span>•</span>
                                                            <a href="{{ $banner->target_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline font-mono truncate max-w-[140px] sm:max-w-[200px]">
                                                                {{ $banner->target_url }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom 2: Masa Tayang & Status Badge --}}
                                        <td class="py-3.5 px-4 sm:px-5">
                                            <div class="space-y-1">
                                                @if($banner->starts_at && $banner->ends_at)
                                                    <span class="text-slate-800 font-bold block tabular-nums text-xs">{{ $banner->starts_at->format('d M Y') }}</span>
                                                    <span class="text-[11px] text-slate-400 block tabular-nums">s/d {{ $banner->ends_at->format('d M Y') }}</span>
                                                @else
                                                    <span class="text-slate-400 text-xs">-</span>
                                                @endif

                                                @if($banner->is_active)
                                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200 whitespace-nowrap">
                                                        Aktif Tayang
                                                    </span>
                                                @else
                                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border bg-amber-50 text-amber-700 border-amber-200 whitespace-nowrap">
                                                        Menunggu Moderasi
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Kolom 3: Total Klik --}}
                                        <td class="py-3.5 px-4 sm:px-5 text-right">
                                            <span class="text-emerald-600 font-extrabold tabular-nums text-sm sm:text-base block">{{ number_format($banner->click_count) }}</span>
                                            <span class="text-[10px] text-slate-400 block">klik interaksi</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 px-5 text-center space-y-3">
                        <div class="max-w-sm mx-auto rounded-xl overflow-hidden border border-slate-200 shadow-2xs relative">
                            <img 
                                src="{{ asset('images/frontend/banner-jakarta-wide.jpg') }}" 
                                alt="Contoh Banner Beranda" 
                                class="w-full h-28 object-cover"
                            >
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center p-3">
                                <span class="px-3 py-1 rounded-lg bg-white/95 text-slate-900 text-xs font-bold shadow-xs">
                                    Contoh Posisi Slot Banner Beranda
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-slate-800 font-bold">
                                Anda belum memiliki pemasangan banner komersial aktif.
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5 max-w-md mx-auto leading-relaxed">
                                Jangkau puluhan ribu pencari kos setiap hari dengan promosi visual di halaman beranda SevenKost.
                            </p>
                        </div>

                        <div>
                            <button 
                                type="button"
                                @click="scrollToBannerForm()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-xs transition cursor-pointer"
                            >
                                <span>+ Pasang Banner Beranda Sekarang</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 2. FORMULIR PEMASANGAN BANNER KOMERSIAL LANGSUNG VIA XENDIT --}}
            <div id="owner-banner-order-section" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden scroll-mt-6">
                <div class="p-5 sm:p-6 bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-[11px] font-bold text-sky-300 uppercase tracking-wider block">Pemasangan Banner Komersial</span>
                        <h3 class="text-base sm:text-lg font-black tracking-tight text-white mt-0.5">
                            Formulir Penayangan Banner Beranda
                        </h3>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-blue-800/80 text-blue-200 text-xs font-semibold border border-blue-400/30 self-start sm:self-center">
                        ⚡ Gateway Otomatis Xendit
                    </span>
                </div>

                <form action="{{ route('ads.banner.order') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        {{-- Pilihan Durasi Banner --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Pilih Paket Durasi Penayangan <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($bannerPackages as $dKey => $bItem)
                                    <label class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition" :class="bannerDuration === {{ $dKey }} ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/20' : 'border-slate-200 hover:bg-slate-50'">
                                        <div class="flex items-center gap-2.5">
                                            <input type="radio" name="duration_days" value="{{ $dKey }}" x-model.number="bannerDuration" class="text-blue-600 focus:ring-blue-500">
                                            <div>
                                                <span class="font-bold text-xs text-slate-900 block">{{ $bItem['name'] }}</span>
                                                <span class="text-[11px] text-slate-500 block">{{ $bItem['price_label'] }}</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Nama Brand --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nama Brand / Bisnis <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="brand_name" 
                                required 
                                value="{{ old('brand_name') }}"
                                placeholder="Contoh: Laundry Express / Catering Enak" 
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                        </div>

                        {{-- URL Tujuan --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Link URL Tujuan Promosi (Opsional)
                            </label>
                            <input 
                                type="url" 
                                name="target_url" 
                                value="{{ old('target_url') }}"
                                placeholder="https://instagram.com/brand-anda atau web" 
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                        </div>

                        {{-- Email PIC --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Email Kontak PIC <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="contact_email" 
                                required 
                                value="{{ old('contact_email', auth()->user()->email ?? '') }}"
                                placeholder="email@bisnisanda.com" 
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                        </div>

                        {{-- WhatsApp PIC --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nomor WhatsApp PIC <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="contact_phone" 
                                required 
                                value="{{ old('contact_phone', auth()->user()->phone ?? '') }}"
                                placeholder="081234567890" 
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                        </div>

                        {{-- Unggah Gambar Banner --}}
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Unggah File Desain Banner (JPG, PNG, WebP - Maks. 3MB) <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="banner_image" 
                                accept="image/png,image/jpeg,image/webp" 
                                required 
                                class="w-full text-xs file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            >
                        </div>

                        {{-- Saluran Pembayaran Xendit --}}
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Saluran Pembayaran Xendit Gateway
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="qris" checked class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">📱 QRIS Instan</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="bca_va" class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">🏦 BCA VA</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="mandiri_va" class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">🏦 Mandiri VA</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="bri_va" class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">🏦 BRI VA</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="bni_va" class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">🏦 BNI VA</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                    <input type="radio" name="payment_channel" value="ewallet" class="text-blue-600 focus:ring-blue-500">
                                    <span class="font-bold text-slate-800 text-[11px]">💳 E-Wallet</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- RINGKASAN TAGIHAN & SUBMIT --}}
                    <div class="p-5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-500 block">Total Biaya Penayangan:</span>
                            <span class="text-xl sm:text-2xl font-black text-blue-600" x-text="formatRupiah(getBannerTotal())"></span>
                            <span class="text-[11px] text-slate-400 block mt-0.5" x-text="bannerRates[bannerDuration]?.name"></span>
                        </div>

                        <button 
                            type="submit" 
                            class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-black shadow-md shadow-blue-500/25 transition-all text-center cursor-pointer"
                        >
                            Bayar via Xendit & Ajukan Review Admin →
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL BERLANGGANAN / UPGRADE AKUN VIP PEMILIK (XENDIT PAYMENT GATEWAY)    --}}
        {{-- ========================================================================= --}}
        <div 
            x-show="vipModalOpen" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="vipModalOpen = false" 
                class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 text-slate-800"
            >
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Upgrade Status Keanggotaan</span>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 mt-0.5" x-text="selectedVipName"></h3>
                    </div>
                    <button @click="vipModalOpen = false" class="text-slate-400 hover:text-slate-700 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('ads.vip.order') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="vip_package_id" :value="selectedVipId">

                    <div class="p-3.5 rounded-xl bg-blue-50/80 border border-blue-200/80 space-y-1.5 text-xs">
                        <div class="font-bold text-slate-900 flex items-center justify-between">
                            <span>Biaya Berlangganan VIP:</span>
                            <strong class="text-base font-black text-blue-600" x-text="selectedVipPrice"></strong>
                        </div>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Pembayaran otomatis via Xendit. Setelah pembayaran terkonfirmasi lunas, status VIP Anda langsung aktif seketika dengan fasilitas kuota kos tak terbatas dan prioritas ranking.
                        </p>
                    </div>

                    {{-- Saluran Pembayaran Xendit --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pilih Saluran Pembayaran
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="qris" checked class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">📱 QRIS Instan</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="bca_va" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">🏦 BCA VA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="mandiri_va" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">🏦 Mandiri VA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="bri_va" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">🏦 BRI VA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="bni_va" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">🏦 BNI VA</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                <input type="radio" name="payment_channel" value="ewallet" class="text-blue-600 focus:ring-blue-500">
                                <span class="font-bold text-slate-800 text-[11px]">💳 E-Wallet</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-2 flex gap-2.5">
                        <button 
                            type="button" 
                            @click="vipModalOpen = false" 
                            class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-black shadow-md shadow-blue-500/25 text-center transition cursor-pointer"
                        >
                            Bayar via Xendit →
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-owner-layout>

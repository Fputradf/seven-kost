<x-seven-layout title="Pusat Pasang Iklan & Kemitraan - SevenKost">

    {{-- HEADER HERO SECTION --}}
    <div class="relative bg-gradient-to-br from-[#031d44] via-[#05377a] to-[#0a66c2] text-white py-14 sm:py-18 overflow-hidden border-b border-white/10">
        {{-- Subtle background glow --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_50%)] pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                Pusat Pasang Iklan & Kemitraan
            </h1>
            <p class="text-xs sm:text-base text-slate-200 max-w-2xl mx-auto leading-relaxed font-normal">
                Dapatkan solusi periklanan tepat sasaran: pasang listing kos gratis atau upgrade ke status VIP untuk jangkauan maksimal, serta pasang banner promosi bisnis Anda di SevenKost.
            </p>
        </div>
    </div>

    {{-- MAIN CONTAINER WITH ALPINE.JS TAB CONTROL --}}
    <div 
        class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14"
        x-data="{
            activeTab: '{{ $activeTab ?? 'kost' }}',
            vipModalOpen: false,
            selectedVipId: '3_months',
            selectedVipName: 'VIP 3 Bulan',
            selectedVipPrice: 'Rp 129.000',
            bannerDuration: 90,
            bannerRates: {
                30: { price: 250000, name: 'Banner 1 Bulan (30 Hari)' },
                90: { price: 650000, name: 'Banner 3 Bulan (90 Hari)' },
                365: { price: 1990000, name: 'Banner 1 Tahun (365 Hari)' }
            },
            getBannerTotal() {
                return this.bannerRates[this.bannerDuration]?.price || 0;
            },
            formatRupiah(num) {
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            },
            selectBannerDuration(duration) {
                this.bannerDuration = duration;
                const formEl = document.getElementById('banner-order-section');
                if (formEl) {
                    formEl.scrollIntoView({ behavior: 'smooth' });
                }
            },
            openVipModal(pkgId = null, pkgName = null, pkgPrice = null) {
                if (pkgId) this.selectedVipId = pkgId;
                if (pkgName) this.selectedVipName = pkgName;
                if (pkgPrice) this.selectedVipPrice = pkgPrice;
                this.vipModalOpen = true;
            }
        }"
    >
        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-8 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-start sm:items-center gap-3 text-xs font-semibold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="leading-relaxed">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs shadow-xs">
                <div class="font-bold flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    Mohon periksa kesalahan input berikut:
                </div>
                <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SEGMENTED DUAL-TAB SWITCHER --}}
        <div class="flex justify-center mb-10">
            <div class="inline-flex p-1.5 rounded-2xl bg-slate-100 border border-slate-200/80 shadow-xs max-w-xl w-full">
                {{-- TAB 1: Iklan Properti Kos --}}
                <button 
                    type="button"
                    @click="activeTab = 'kost'"
                    :class="activeTab === 'kost' ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                    class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Iklan Properti Kos</span>
                </button>

                {{-- TAB 2: Iklan Banner Komersial --}}
                <button 
                    type="button"
                    @click="activeTab = 'banner'"
                    :class="activeTab === 'banner' ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60 font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                    class="flex-1 py-3 px-4 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Iklan Banner Komersial</span>
                </button>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB A: IKLAN PROPERTI KOS (AKUN BIASA VS AKUN VIP PEMILIK)                --}}
        {{-- ========================================================================= --}}
        <div x-show="activeTab === 'kost'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-14">
            
            {{-- CARD: LISTING KOS GRATIS --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 to-indigo-900 text-white p-8 sm:p-10 shadow-xl border border-blue-800">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-4">
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                            Pasang & Kelola Iklan Kos Anda Secara Gratis
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-2xl">
                            Daftarkan properti kos Anda sekarang juga tanpa biaya pendaftaran. Nikmati fitur lengkap pengelolaan kamar, galeri foto, fasilitas, ketersediaan kamar, dan terhubung langsung dengan ribuan pencari kos di dalam aplikasi.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Rp 0 Biaya Listing</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Tampil di Katalog & Peta</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Kelola Kamar di Aplikasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-3 justify-center">
                        @auth
                            <x-seven-button :href="route('owner.kosts.create')" variant="primary" size="lg" class="w-full text-center justify-center bg-blue-500 hover:bg-blue-400 text-white font-black shadow-lg shadow-blue-500/30 border-0">
                                + Pasang Iklan Kos Gratis
                            </x-seven-button>
                            <a href="{{ route('owner.kosts.index') }}" class="text-center text-xs text-slate-300 hover:text-white underline underline-offset-4">
                                Kelola Kos Terdaftar →
                            </a>
                        @else
                            <x-seven-button :href="route('register')" variant="primary" size="lg" class="w-full text-center justify-center bg-blue-500 hover:bg-blue-400 text-white font-black shadow-lg shadow-blue-500/30 border-0">
                                Daftar Sebagai Pemilik Kos
                            </x-seven-button>
                            <a href="{{ route('login') }}" class="text-center text-xs text-slate-300 hover:text-white underline underline-offset-4">
                                Sudah punya akun? Masuk di sini →
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- SECTION: PERBANDINGAN AKUN BIASA VS AKUN VIP PEMILIK --}}
            <div class="space-y-8">
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Perbandingan Akun Biasa vs Akun VIP Pemilik
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Tingkatkan jangkauan dan performa properti Anda dengan berlangganan Akun VIP. Seluruh transaksi dan interaksi tetap berjalan 100% aman di dalam aplikasi SevenKost.
                    </p>
                </div>

                {{-- TABEL KOMPARASI FITUR --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-slate-200 bg-slate-50/90">
                                    <th class="py-5 px-6 sm:px-8 font-extrabold text-sm sm:text-base text-slate-800 w-2/5">
                                        Fitur & Fasilitas Akun
                                    </th>
                                    <th class="py-5 px-6 sm:px-8 font-bold text-sm sm:text-base text-slate-700 w-3/10 text-center">
                                        Akun Biasa (Gratis)
                                    </th>
                                    <th class="py-5 px-6 sm:px-8 font-black text-sm sm:text-base text-blue-600 w-3/10 text-center bg-blue-50/80 border-l-2 border-blue-200">
                                        Akun VIP Pemilik 👑
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                {{-- Row 1: Biaya --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Biaya Berlangganan</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Tarif komitmen keanggotaan platform</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-bold">
                                        Rp 0 (Gratis Selamanya)
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-black text-blue-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Mulai Rp 49.000 / bulan
                                    </td>
                                </tr>

                                {{-- Row 2: Batas Kuota Properti Kos --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Batas Kuota Properti Kos</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Jumlah listing kos yang dapat didaftarkan</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Maksimal 1 Properti Kos
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-emerald-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Unlimited (Bebas Pasang Banyak Kos)
                                    </td>
                                </tr>

                                {{-- Row 3: Peringkat Hasil Pencarian --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Peringkat Hasil Pencarian</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Posisi kos saat pencari melakukan pencarian di aplikasi</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Urutan Standar (Organik)
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-blue-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Prioritas Baris Teratas (Top Ranking)
                                    </td>
                                </tr>

                                {{-- Row 4: Lencana Eksklusif Properti --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Lencana Eksklusif di Kartu Kos</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Identitas tepercaya di mata calon penyewa</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-500 font-medium">
                                        Standar (Tanpa Lencana)
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-amber-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Lencana Emas "VIP" 👑
                                    </td>
                                </tr>

                                {{-- Row 5: Penanda Peta Interaktif --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Penanda di Peta Leaflet</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Tampilan pin kos pada eksplorasi peta nasional</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Pin Biru Standar
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-blue-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Pin Emas Menonjol (Highlight Pin)
                                    </td>
                                </tr>

                                {{-- Row 6: Kapasitas Foto Galeri --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Batas Unggah Foto Galeri</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Jumlah foto untuk memperlihatkan kamar & fasilitas</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Maksimal 5 Foto
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-blue-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Hingga 15 Foto HD
                                    </td>
                                </tr>

                                {{-- Row 7: Kecepatan Verifikasi Iklan --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Kecepatan Verifikasi Iklan</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Listing baru atau update kamar langsung tayang cepat</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Antrean Standar
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-emerald-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Prioritas Fast-Track Admin
                                    </td>
                                </tr>

                                {{-- Row 8: Layanan Bantuan Sistem --}}
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-5 px-6 sm:px-8">
                                        <div class="font-bold text-sm sm:text-base text-slate-900">Layanan Bantuan Sistem (In-App)</div>
                                        <div class="text-xs sm:text-sm text-slate-500">Penanganan kendala akun & periklanan di dalam aplikasi</div>
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] text-slate-700 font-medium">
                                        Tiket Bantuan Standar
                                    </td>
                                    <td class="py-5 px-6 sm:px-8 text-center text-sm sm:text-[15px] font-bold text-blue-600 bg-blue-50/40 border-l-2 border-blue-100">
                                        Prioritas Tiket Bantuan In-App
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KARTU PILIHAN PAKET BERLANGGANAN VIP --}}
                <div class="space-y-6 pt-6">
                    <div class="text-center max-w-xl mx-auto space-y-1">
                        <h4 class="text-xl sm:text-2xl font-black text-slate-900">Pilih Paket Berlangganan VIP Anda</h4>
                        <p class="text-xs sm:text-sm text-slate-500">Klik paket pilihan Anda untuk mengaktifkan status VIP dan menikmati seluruh keuntungan.</p>
                    </div>

                    {{-- 3 KARTU INTERAKTIF VIP --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch pt-2">
                        @foreach($vipPackages as $vId => $vPkg)
                            @php
                                $isPop = $vPkg['is_popular'] ?? false;
                            @endphp
                            <div 
                                @click="selectedVipId = '{{ $vId }}'; selectedVipName = '{{ $vPkg['name'] }}'; selectedVipPrice = '{{ $vPkg['price_label'] }}'"
                                :class="selectedVipId === '{{ $vId }}' ? 'border-2 border-blue-500 ring-4 ring-blue-500/20 shadow-xl shadow-blue-500/10 scale-102 bg-white' : 'border border-slate-200/90 hover:border-blue-300 hover:shadow-md bg-white'"
                                class="relative rounded-3xl p-7 flex flex-col justify-between transition-all duration-200 cursor-pointer select-none"
                            >
                                @if($isPop)
                                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 text-white font-black text-[10px] uppercase tracking-wider shadow-sm">
                                        Paling Populer
                                    </div>
                                @endif

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-extrabold text-base text-slate-900">{{ $vPkg['name'] }}</h4>
                                            <span class="text-xs font-bold text-slate-500">{{ $vPkg['duration_label'] }}</span>
                                        </div>
                                        <div 
                                            class="w-6 h-6 rounded-full flex items-center justify-center transition-all duration-200 shrink-0"
                                            :class="selectedVipId === '{{ $vId }}' ? 'bg-blue-600 text-white ring-2 ring-blue-400/50' : 'border-2 border-slate-300 text-transparent'"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </div>

                                    <div class="flex items-baseline gap-1 my-3">
                                        <span class="text-2xl sm:text-3xl font-black text-blue-600">
                                            {{ $vPkg['price_label'] }}
                                        </span>
                                        <span class="text-xs text-slate-500 font-medium">{{ $vPkg['period'] }}</span>
                                    </div>

                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        {{ $vPkg['description'] }}
                                    </p>

                                    <ul class="space-y-2.5 text-xs text-slate-600 pt-3 border-t border-slate-100">
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span>Unlimited kuota properti kos</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span>Prioritas teratas hasil pencarian</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            <span>Lencana emas "VIP" 👑</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="pt-6">
                                    @auth
                                        <button 
                                            type="button"
                                            @click.stop="openVipModal('{{ $vId }}', '{{ $vPkg['name'] }}', '{{ $vPkg['price_label'] }}')"
                                            :class="selectedVipId === '{{ $vId }}' ? 'bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-black shadow-md shadow-blue-500/25' : 'bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold'"
                                            class="w-full py-2.5 rounded-xl text-xs transition text-center"
                                        >
                                            <span x-text="selectedVipId === '{{ $vId }}' ? 'Pilih Paket Ini ✓' : 'Pilih Paket'"></span>
                                        </button>
                                    @else
                                        <a 
                                            href="{{ route('register') }}"
                                            :class="selectedVipId === '{{ $vId }}' ? 'bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-black shadow-md shadow-blue-500/25' : 'bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold'"
                                            class="block w-full py-2.5 rounded-xl text-xs transition text-center"
                                        >
                                            Daftar & Berlangganan
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- TAB B: IKLAN BANNER KOMERSIAL (BRAND, BISNIS & SPONSOR)                   --}}
        {{-- ========================================================================= --}}
        <div x-show="activeTab === 'banner'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12" style="display: none;">
            
            {{-- INTRO BANNER KOMERSIAL --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 to-indigo-900 text-white p-8 sm:p-10 shadow-xl border border-blue-800">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-4">
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tight leading-snug">
                            Pasang Banner Promosi Brand & Layanan Anda
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-2xl font-normal">
                            Jangkau puluhan ribu anak kos, mahasiswa, dan pekerja rantau aktif di seluruh Indonesia. Sangat tepat untuk promosi produk perlengkapan kamar, laundry, kuliner, provider internet, dan layanan pendukung lainnya.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Tampil di Halaman Utama</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Link Langsung ke Bisnis</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-100 font-medium">
                                <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-emerald-400">✓</div>
                                <span>Pembayaran Otomatis Xendit</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-3 justify-center">
                        @auth
                            <x-seven-button href="#banner-order-section" variant="primary" size="lg" class="w-full text-center justify-center bg-blue-500 hover:bg-blue-400 text-white font-black shadow-lg shadow-blue-500/30 border-0">
                                + Pasang Banner Sekarang
                            </x-seven-button>
                        @else
                            <x-seven-button :href="route('login')" variant="primary" size="lg" class="w-full text-center justify-center bg-blue-500 hover:bg-blue-400 text-white font-black shadow-lg shadow-blue-500/30 border-0">
                                Masuk untuk Pasang Banner →
                            </x-seven-button>
                            <a href="{{ route('register') }}" class="text-center text-xs text-slate-300 hover:text-white underline underline-offset-4">
                                Belum punya akun? Daftar pemilik →
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- PAKET DURASI & BIAYA IKLAN BANNER (INTERAKTIF & DISAMAKAN DENGAN VIP) --}}
            <div class="space-y-6">
                <div class="text-center max-w-2xl mx-auto space-y-1">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Pilihan Paket Durasi & Biaya Banner
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Pilihan durasi penayangan banner komersial yang fleksibel di platform SevenKost.
                    </p>
                </div>

                {{-- 3 KARTU INTERAKTIF BANNER --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch pt-2">
                    @foreach($bannerPackages as $durationKey => $bp)
                        @php
                            $isBest = $bp['is_popular'] ?? false;
                        @endphp
                        <div 
                            @click="bannerDuration = {{ $durationKey }}"
                            :class="bannerDuration === {{ $durationKey }} ? 'border-2 border-blue-500 ring-4 ring-blue-500/20 shadow-xl shadow-blue-500/10 scale-102 bg-white' : 'border border-slate-200/90 hover:border-blue-300 hover:shadow-md bg-white'"
                            class="relative rounded-3xl p-7 flex flex-col justify-between transition-all duration-200 cursor-pointer select-none"
                        >
                            @if($isBest)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-lg bg-gradient-to-r from-blue-600 to-sky-500 text-white font-black text-[10px] uppercase tracking-wider shadow-sm">
                                    Paling Populer
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-extrabold text-base text-slate-900">{{ $bp['name'] }}</h4>
                                        <span class="text-xs font-bold text-slate-500">{{ $bp['duration_label'] }}</span>
                                    </div>
                                    <div 
                                        class="w-6 h-6 rounded-full flex items-center justify-center transition-all duration-200 shrink-0"
                                        :class="bannerDuration === {{ $durationKey }} ? 'bg-blue-600 text-white ring-2 ring-blue-400/50' : 'border-2 border-slate-300 text-transparent'"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>

                                <div class="flex items-baseline gap-1 my-3">
                                    <span class="text-2xl sm:text-3xl font-black text-blue-600">
                                        {{ $bp['price_label'] }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-medium">{{ $bp['period'] }}</span>
                                </div>

                                <p class="text-xs text-slate-500 leading-relaxed">
                                    {{ $bp['description'] }}
                                </p>

                                <ul class="space-y-2 text-xs text-slate-600 pt-3 border-t border-slate-100">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Penayangan banner visual di platform SevenKost</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Link URL langsung ke website / kontak bisnis</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>{{ $bp['dimension'] }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-6">
                                @auth
                                    <button 
                                        type="button"
                                        @click.stop="selectBannerDuration({{ $durationKey }})"
                                        :class="bannerDuration === {{ $durationKey }} ? 'bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white font-black shadow-md shadow-blue-500/25' : 'bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold'"
                                        class="w-full py-2.5 rounded-xl text-xs transition text-center"
                                    >
                                        <span x-text="bannerDuration === {{ $durationKey }} ? 'Pilih Paket Ini ✓' : 'Pilih Paket'"></span>
                                    </button>
                                @else
                                    <a 
                                        href="{{ route('login') }}"
                                        class="block w-full py-2.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 font-bold text-xs transition text-center"
                                    >
                                        Masuk untuk Memesan
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3-STEP ALUR PEMESANAN XENDIT & APPROVAL --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xs space-y-6">
                <div class="text-center max-w-2xl mx-auto space-y-1">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Alur Pemesanan & Penayangan Banner
                    </h3>
                    <p class="text-xs text-slate-500">
                        Proses praktis dari pengunggahan materi hingga persetujuan penayangan oleh admin.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                    {{-- STEP 1 --}}
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center">
                            1
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Pilih Durasi & Unggah Desain</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Tentukan durasi penayangan, tautan tujuan promosi, dan unggah file gambar banner Anda (JPG, PNG, atau WebP).
                        </p>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center">
                            2
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Pembayaran Otomatis via Xendit</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Lakukan pembayaran instan menggunakan QRIS, Virtual Account bank nasional (BCA, Mandiri, BRI, BNI), atau E-Wallet.
                        </p>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-black text-xs flex items-center justify-center">
                            3
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Review & Approval Admin</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Tim SevenKost meninjau materi banner agar mematuhi ketentuan penayangan. Setelah disetujui, banner langsung aktif tayang.
                        </p>
                    </div>
                </div>
            </div>

            @auth
                {{-- PEMESANAN BANNER: FORMULIR HANYA UNTUK USER LOGIN --}}
                <div id="banner-order-section">
                    {{-- FORMULIR PEMESANAN BANNER UNTUK PEMILIK TERDAFTAR --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xs space-y-6">
                        <div class="pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Formulir Pemasangan Banner Promosi</h3>
                                <p class="text-xs text-slate-500">Lengkapi data promosi Anda dan selesaikan transaksi melalui Xendit.</p>
                            </div>
                            <div class="text-xs font-semibold text-slate-500">
                                Peninjauan materi oleh tim SevenKost
                            </div>
                        </div>

                        <form action="{{ route('ads.banner.order') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Pilihan Durasi --}}
                                <div class="space-y-1.5 md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Pilihan Durasi Penayangan Banner <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        @foreach($bannerPackages as $dKey => $bItem)
                                            <label class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition" :class="bannerDuration === {{ $dKey }} ? 'border-blue-600 bg-blue-50/50 ring-2 ring-blue-500/20' : 'border-slate-200 hover:bg-slate-50'">
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
                                        placeholder="Contoh: Laundry Bersih Kilat / Toko Meubel Jaya" 
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
                                        placeholder="https://instagram.com/brand-anda atau link website" 
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

                                {{-- Saluran Pembayaran Xendit (Simulasi) --}}
                                <div class="md:col-span-2 space-y-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Saluran Pembayaran Otomatis (Xendit Gateway)
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                            <input type="radio" name="payment_channel" value="qris" checked class="text-blue-600 focus:ring-blue-500">
                                            <span class="font-bold text-slate-800">QRIS Instan</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                            <input type="radio" name="payment_channel" value="bca_va" class="text-blue-600 focus:ring-blue-500">
                                            <span class="font-bold text-slate-800">BCA Virtual Acc.</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                            <input type="radio" name="payment_channel" value="mandiri_va" class="text-blue-600 focus:ring-blue-500">
                                            <span class="font-bold text-slate-800">Mandiri VA</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer has-checked:border-blue-600 has-checked:bg-blue-50/50">
                                            <input type="radio" name="payment_channel" value="ewallet" class="text-blue-600 focus:ring-blue-500">
                                            <span class="font-bold text-slate-800">GoPay / OVO</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- RINGKASAN TAGIHAN & SUBMIT --}}
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <span class="text-xs text-slate-500 block">Total Investasi Penayangan:</span>
                                    <span class="text-xl sm:text-2xl font-black text-blue-600" x-text="formatRupiah(getBannerTotal())"></span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5" x-text="bannerRates[bannerDuration]?.name"></span>
                                </div>

                                <button 
                                    type="submit" 
                                    class="px-8 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-black shadow-lg shadow-blue-500/25 transition-all text-center"
                                >
                                    Bayar via Xendit & Ajukan Review Admin →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL BERLANGGANAN AKUN VIP PEMILIK                                       --}}
        {{-- ========================================================================= --}}
        @auth
            <div x-show="vipModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                <div @click.outside="vipModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-800">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Upgrade Status Akun</span>
                            <h3 class="text-base sm:text-lg font-black text-slate-900" x-text="selectedVipName"></h3>
                        </div>
                        <button @click="vipModalOpen = false" class="text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/80 border border-blue-200/80 space-y-2 text-xs">
                        <div class="font-bold text-slate-900 flex items-center justify-between">
                            <span>Biaya Paket Berlangganan:</span>
                            <strong class="text-base font-black text-blue-600" x-text="selectedVipPrice"></strong>
                        </div>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Dengan berlangganan VIP, akun Anda mendapatkan kuota kos unlimited, prioritas pencarian, dan lencana VIP 👑 di aplikasi SevenKost.
                        </p>
                    </div>

                    {{-- Petunjuk Pembayaran Otomatis & Manual --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-2">
                        <span class="font-bold text-slate-900 block">Rekening Resmi SevenKost:</span>
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            <div>
                                <span class="text-slate-500 block">Bank BCA:</span>
                                <strong class="text-slate-900 font-mono">123-456-7890</strong>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Bank Mandiri:</span>
                                <strong class="text-slate-900 font-mono">137-00-9876543-2</strong>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-500 block">a.n. Seven Kost Nusantara PT</span>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <button type="button" @click="vipModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <a 
                            href="{{ route('owner.kosts.index') }}" 
                            class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-500/25 text-center"
                        >
                            Konfirmasi Upgrade VIP
                        </a>
                    </div>
                </div>
            </div>
        @endauth

    </div>

</x-seven-layout>

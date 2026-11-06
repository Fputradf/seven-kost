<x-seven-layout title="Seven Kost - Cari Kos Terverifikasi Seluruh Indonesia">

    {{-- HERO SECTION (Navy Gradient & Search Box) --}}
    <section class="relative bg-navy-gradient text-white overflow-hidden pt-12 pb-24 lg:pt-16 lg:pb-32">
        {{-- Background Decorative Glows --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-blue-500/10 blur-3xl pointer-events-none rounded-full"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/15 blur-3xl pointer-events-none rounded-full"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                {{-- Tag Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-blue-200">
                    <span class="flex h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                    Platform Kos Nasional Terverifikasi
                </div>

                {{-- Hero Heading --}}
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                    Cari Kos Idaman di <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-indigo-200">
                        Seluruh Indonesia
                    </span>
                </h1>

                <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                    Lebih dari 34 provinsi dan 514 kabupaten/kota terjangkau. Temukan kos putra, putri, atau campur dengan peta interaktif dan lencana resmi.
                </p>
            </div>

            {{-- SEARCH BOX (Dinamis dengan Alpine.js) --}}
            <div 
                x-data="{
                    selectedProvince: '',
                    selectedRegency: '',
                    regencies: [],
                    isLoadingRegencies: false,
                    type: '',
                    maxPrice: '',
                    async onProvinceChange() {
                        this.selectedRegency = '';
                        this.regencies = [];
                        if (!this.selectedProvince) return;
                        this.isLoadingRegencies = true;
                        try {
                            const res = await fetch(`/api/wilayah/provinces/${this.selectedProvince}/regencies`);
                            this.regencies = await res.json();
                        } catch (e) {
                            console.error('Gagal mengambil data kabupaten/kota', e);
                        } finally {
                            this.isLoadingRegencies = false;
                        }
                    }
                }"
                class="mt-10 max-w-4xl mx-auto bg-white/95 backdrop-blur-xl p-4 sm:p-6 rounded-3xl shadow-2xl shadow-navy-950/40 text-slate-800 border border-white/40"
            >
                <form action="{{ url('/cari') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        {{-- 1. Pilih Provinsi --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                📍 Provinsi
                            </label>
                            <select 
                                name="provinsi" 
                                x-model="selectedProvince" 
                                @change="onProvinceChange()"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2.5 transition"
                            >
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Pilih Kabupaten / Kota (Dinamis) --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                🏙️ Kota / Kab
                            </label>
                            <div class="relative">
                                <select 
                                    name="kota" 
                                    x-model="selectedRegency" 
                                    :disabled="!selectedProvince || isLoadingRegencies"
                                    class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2.5 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <option value="" x-text="isLoadingRegencies ? 'Memuat wilayah...' : (selectedProvince ? 'Semua Kota / Kab' : 'Pilih Provinsi dulu')"></option>
                                    <template x-for="reg in regencies" :key="reg.id">
                                        <option :value="reg.id" x-text="reg.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- 3. Tipe Kos --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                👥 Tipe Kos
                            </label>
                            <select 
                                name="tipe" 
                                x-model="type"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2.5 transition"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="putra">Putra</option>
                                <option value="putri">Putri</option>
                                <option value="campur">Campur</option>
                            </select>
                        </div>

                        {{-- 4. Budget Maksimal --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">
                                💰 Maksimal Budget
                            </label>
                            <select 
                                name="max_price" 
                                x-model="maxPrice"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-2.5 transition"
                            >
                                <option value="">Bebas Budget</option>
                                <option value="1000000">&le; Rp 1 Juta/bln</option>
                                <option value="1500000">&le; Rp 1.5 Juta/bln</option>
                                <option value="2500000">&le; Rp 2.5 Juta/bln</option>
                                <option value="5000000">&le; Rp 5 Juta/bln</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tombol Aksi Cari & Peta --}}
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Langsung terhubung ke pemilik tanpa perantara</span>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <x-seven-button href="{{ url('/peta') }}" variant="outline" size="sm" class="w-full sm:w-auto">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Buka Peta
                            </x-seven-button>

                            <x-seven-button type="submit" variant="primary" size="sm" class="w-full sm:w-auto px-6">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari Kos Sekarang
                            </x-seven-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- SLOT BANNER / PROMO SECTION (BR-11) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Banner 1: Promo Pemilik --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-indigo-700 to-navy-900 p-6 text-white shadow-lg flex items-center justify-between">
                <div class="space-y-2 max-w-sm">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-bold tracking-wider uppercase">
                        Khusus Pemilik Kos
                    </span>
                    <h3 class="text-lg font-bold">Pasang Iklan & Dapatkan Lencana Terverifikasi</h3>
                    <p class="text-xs text-blue-100">Kamar cepat terisi dengan iklan tampil di halaman utama.</p>
                    <div class="pt-2">
                        <x-seven-button href="{{ url('/paket-iklan') }}" variant="secondary" size="sm" class="text-xs font-bold text-blue-900">
                            Lihat Paket Iklan
                        </x-seven-button>
                    </div>
                </div>
                <div class="hidden sm:block text-blue-300/40">
                    <svg class="w-24 h-24 stroke-[1]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>

            {{-- Banner 2: Fitur Peta Nasional --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-700 via-teal-800 to-navy-900 p-6 text-white shadow-lg flex items-center justify-between">
                <div class="space-y-2 max-w-sm">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-bold tracking-wider uppercase">
                        Fitur Baru
                    </span>
                    <h3 class="text-lg font-bold">Eksplorasi Kos Melalui Peta Interaktif</h3>
                    <p class="text-xs text-emerald-100">Cek radius kos dekat kampus, stasiun krl, atau pusat perkantoran.</p>
                    <div class="pt-2">
                        <x-seven-button href="{{ url('/peta') }}" variant="secondary" size="sm" class="text-xs font-bold text-teal-900">
                            Buka Peta Leaflet
                        </x-seven-button>
                    </div>
                </div>
                <div class="hidden sm:block text-emerald-300/40">
                    <svg class="w-24 h-24 stroke-[1]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- KOS UNGGULAN & REKOMENDASI TERVERIFIKASI --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div>
                <div class="flex items-center gap-2 text-blue-600 font-bold text-xs uppercase tracking-wider mb-1">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Paling Banyak Dicari
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    Kos Unggulan & Terverifikasi
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Kos dengan fasilitas lengkap, lencana verifikasi tim Seven Kost, dan ulasan terpercaya.
                </p>
            </div>

            <a href="{{ url('/cari') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                <span>Lihat Semua Listing</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Grid Listing --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($featuredKosts as $kost)
                <x-kost-card 
                    :title="$kost['title']"
                    :slug="$kost['slug']"
                    :type="$kost['type']"
                    :city="$kost['city']"
                    :province="$kost['province']"
                    :price="$kost['price']"
                    :rentType="$kost['rentType']"
                    :isVerified="$kost['isVerified']"
                    :isFeatured="$kost['isFeatured']"
                    :availableRooms="$kost['availableRooms']"
                    :facilities="$kost['facilities']"
                    :image="$kost['image']"
                />
            @endforeach
        </div>
    </section>

    {{-- JELAJAH KOTA POPULER --}}
    <section class="bg-white py-16 border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    Jelajah Kos di Kota Populer
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Pilih kota tujuan Anda untuk melihat ketersediaan kamar di dekat kampus dan area kerja.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($popularCities as $city)
                    <a href="{{ url('/cari?kota=' . $city->id) }}" class="group p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-blue-500/40 hover:shadow-lg transition-all duration-200 text-center flex flex-col items-center">
                        <div class="h-12 w-12 rounded-2xl bg-blue-100/70 text-blue-600 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <svg class="h-6 w-6 stroke-[1.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M3 21h18M3 7v14M21 7v14M6 11h2M6 15h2M16 11h2M16 15h2M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4M9 3h6l3 4H6l3-4z"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm text-slate-800 group-hover:text-blue-600 transition-colors">
                            {{ $city->name }}
                        </h4>
                        <span class="text-[11px] text-slate-400 mt-0.5">
                            {{ $city->province->name ?? 'Indonesia' }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KEUNGGULAN SEVEN KOST (WHY US) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex flex-col items-start">
                <div class="h-12 w-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-5 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">Verifikasi Listing Ketat</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Setiap kos dengan lencana centang telah diperiksa identitas pemilik dan legalitas lokasinya oleh tim admin kami untuk mencegah penipuan.
                </p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex flex-col items-start">
                <div class="h-12 w-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-5 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">Peta Presisi Seluruh Indonesia</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Menghitung jarak akurat dan memeriksa apakah kos berada tepat di dalam wilayah administratif menggunakan teknologi peta Leaflet dan Turf.js.
                </p>
            </div>

            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex flex-col items-start">
                <div class="h-12 w-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-5 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">Langsung ke Pemilik</h3>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Ajukan sewa langsung dari platform atau hubungi nomor WhatsApp pemilik terdaftar secara transparan tanpa biaya tersembunyi.
                </p>
            </div>
        </div>
    </section>

    {{-- CALL TO ACTION UNTUK PEMILIK KOS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="rounded-3xl bg-navy-gradient text-white p-8 sm:p-12 relative overflow-hidden shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-4 max-w-xl text-center md:text-left">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    Gabung Jadi Mitra Kos
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight">
                    Punya Rumah Kos Kosong? Iklankan di Seven Kost Sekarang!
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Kelola kamar, pantau penyewa, dan jangkau puluhan ribu pencari kos mahasiswa & pekerja kantoran di seluruh Indonesia.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <x-seven-button :href="route('register')" variant="primary" size="lg" class="w-full sm:w-auto">
                    Daftar Sebagai Pemilik
                </x-seven-button>
                <x-seven-button href="{{ url('/paket-iklan') }}" variant="outline-white" size="lg" class="w-full sm:w-auto">
                    Pelajari Paket Iklan
                </x-seven-button>
            </div>
        </div>
    </section>

</x-seven-layout>

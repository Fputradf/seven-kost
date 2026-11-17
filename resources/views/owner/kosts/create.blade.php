<x-owner-layout title="Pasang Iklan Kos Baru">

    @push('styles')
        {{-- Leaflet Map CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .leaflet-container {
                z-index: 10 !important;
                border-radius: 1rem;
            }
        </style>
    @endpush

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- Header Card (Refined Sapphire Blue Gradient) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="flex items-center gap-2 text-xs text-blue-200 font-medium mb-2">
                <a href="{{ route('owner.dashboard') }}" class="hover:underline">Dasbor Pemilik</a>
                <span>/</span>
                <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan</a>
                <span>/</span>
                <span class="text-white font-semibold">Pasang Iklan Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                Formulir Pasang Iklan Kos Nasional
            </h1>
            <p class="text-xs sm:text-sm text-blue-100/90 mt-1">
                Lengkapi rincian kos Anda, pin lokasi di peta Leaflet, dan unggah foto properti.
            </p>
        </div>
        {{-- Flash / Error Messages --}}
        @if (isset($errors) && $errors->any())
            <div class="mb-8 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-800">
                <div class="flex items-center gap-2 font-bold text-sm mb-1">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Mohon periksa kembali formulir Anda:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form 
            action="{{ route('owner.kosts.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="space-y-8"
            x-data="{
                provId: '{{ old('province_id', '') }}',
                regId: '{{ old('regency_id', '') }}',
                regencies: [],
                isLoading: false,
                lat: '{{ old('latitude', -6.2088) }}',
                lng: '{{ old('longitude', 106.8456) }}',
                coverPreview: null,
                galleryPreviews: [],
                async onProvChange() {
                    this.regId = '';
                    this.regencies = [];
                    if (!this.provId) return;
                    this.isLoading = true;
                    try {
                        const res = await fetch(`/api/wilayah/provinces/${this.provId}/regencies`);
                        this.regencies = await res.json();
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                handleCoverPreview(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.coverPreview = URL.createObjectURL(file);
                    }
                },
                handleGalleryPreview(e) {
                    this.galleryPreviews = [];
                    const files = Array.from(e.target.files);
                    files.slice(0, 8).forEach(file => {
                        this.galleryPreviews.push({
                            name: file.name,
                            url: URL.createObjectURL(file),
                            size: (file.size / 1024 / 1024).toFixed(2)
                        });
                    });
                },
                init() {
                    if (this.provId) {
                        this.onProvChange().then(() => {
                            this.regId = '{{ old('regency_id', '') }}';
                        });
                    }
                }
            }"
        >
            @csrf

            {{-- 1. INFORMASI DASAR KOS --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">1</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Informasi Dasar Kos</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Nama resmi dan klasifikasi jenis sewa properti kos Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Nama Kos <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            name="name" 
                            value="{{ old('name') }}" 
                            placeholder="Contoh: Kos Melati Residence Eksklusif" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label for="type" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Tipe Kos <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="type"
                            name="type" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15 cursor-pointer"
                        >
                            <option value="campur" {{ old('type') == 'campur' ? 'selected' : '' }}>Kos Campur</option>
                            <option value="putri" {{ old('type') == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="putra" {{ old('type') == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                        </select>
                    </div>

                    <div>
                        <label for="rent_type" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Skema Periode Sewa <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="rent_type"
                            name="rent_type" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15 cursor-pointer"
                        >
                            <option value="bulanan" {{ old('rent_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('rent_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="harian" {{ old('rent_type') == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Deskripsi Kos
                        </label>
                        <textarea 
                            id="description"
                            name="description" 
                            rows="4" 
                            placeholder="Ceritakan keunggulan kos, suasana lingkungan, dan kenyamanan hunian..." 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="rules" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Peraturan & Tata Tertib Kos
                        </label>
                        <textarea 
                            id="rules"
                            name="rules" 
                            rows="3" 
                            placeholder="Contoh: Jam malam tamu pukul 23:00 WIB, dilarang merokok di dalam kamar..." 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('rules') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. LOKASI KOS & PETA LEAFLET --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">2</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Lokasi Administratif & Titik Peta</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Tentukan wilayah provinsi, kabupaten/kota, dan pin posisi presisi pada peta digital.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Provinsi --}}
                    <div>
                        <label for="province_id" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Provinsi <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="province_id"
                            name="province_id" 
                            x-model="provId" 
                            @change="onProvChange()" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15 cursor-pointer"
                        >
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kabupaten / Kota --}}
                    <div>
                        <label for="regency_id" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Kabupaten / Kota <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            id="regency_id"
                            name="regency_id" 
                            x-model="regId" 
                            :disabled="!provId || isLoading" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15 disabled:bg-slate-50 disabled:text-slate-400 cursor-pointer"
                        >
                            <option value="" x-text="isLoading ? 'Memuat data wilayah...' : '-- Pilih Kab/Kota --'"></option>
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Alamat Lengkap & Patokan Akses <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            id="address"
                            name="address" 
                            rows="3" 
                            placeholder="Tuliskan nama jalan, nomor bangunan, RT/RW, kelurahan/desa, serta patokan terdekat..." 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('address') }}</textarea>
                    </div>

                    {{-- PETA INTERAKTIF LEAFLET --}}
                    <div class="md:col-span-2 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-[13px] font-medium text-slate-700 mb-0">
                                Penanda Titik Lokasi Peta
                            </label>
                            <button 
                                type="button" 
                                id="btnDetectGps" 
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 transition cursor-pointer shadow-2xs"
                            >
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12c0 4.418 3.582 8 8 8h1v2a1 1 0 001.707.707L16.414 19H17c4.418 0 8-3.582 8-8 0-5.523-4.477-10-10-10z"/></svg>
                                <span>Gunakan Lokasi Saat Ini</span>
                            </button>
                        </div>

                        {{-- Leaflet Map Container --}}
                        <div id="mapPicker" class="h-72 w-full rounded-lg border border-slate-300 shadow-inner overflow-hidden"></div>
                        <p class="text-[12px] text-slate-500">
                            Klik pada peta atau geser pin penanda biru secara presisi ke letak bangunan kos Anda.
                        </p>

                        {{-- Koordinat Input Tersembunyi/Readonly --}}
                        <div class="grid grid-cols-2 gap-4 pt-1">
                            <div>
                                <label for="latInput" class="block text-[11px] font-medium text-slate-500 mb-1">Koordinat Latitude</label>
                                <input type="text" name="latitude" id="latInput" x-model="lat" readonly class="w-full text-xs bg-slate-50 text-slate-600 rounded-md border border-slate-200 py-1.5 px-3 font-mono">
                            </div>
                            <div>
                                <label for="lngInput" class="block text-[11px] font-medium text-slate-500 mb-1">Koordinat Longitude</label>
                                <input type="text" name="longitude" id="lngInput" x-model="lng" readonly class="w-full text-xs bg-slate-50 text-slate-600 rounded-md border border-slate-200 py-1.5 px-3 font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. UNGGAH FOTO COVER & GALERI (Multi-Photo Upload + Live Preview) --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">3</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Foto Sampul & Dokumentasi Galeri</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Iklan dengan foto resolusi tajam dan terang mendapatkan perhatian pencari kos lebih cepat.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Foto Sampul Utama --}}
                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1">
                            Foto Sampul Utama (Cover) <span class="text-rose-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-blue-500 transition relative bg-slate-50/50 min-h-[160px] flex flex-col justify-center items-center">
                            <template x-if="coverPreview">
                                <div class="relative w-full aspect-video rounded-md overflow-hidden shadow-2xs">
                                    <img :src="coverPreview" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!coverPreview">
                                <div class="py-3 flex flex-col items-center">
                                    <svg class="w-9 h-9 text-slate-400 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium text-slate-700">Pilih Foto Utama Kos</span>
                                    <span class="text-[11px] text-slate-400 mt-0.5">Format PNG atau JPG (maksimal 3MB)</span>
                                </div>
                            </template>
                            <input type="file" name="cover_image" accept="image/*" @change="handleCoverPreview" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        </div>
                    </div>

                    {{-- Multi Galeri Foto --}}
                    <div>
                        <label class="block text-[13px] font-medium text-slate-700 mb-1">
                            Galeri Foto Kamar & Fasilitas
                        </label>
                        <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:border-blue-500 transition relative bg-slate-50/50 min-h-[160px] flex flex-col justify-center items-center">
                            <div class="py-3 flex flex-col items-center">
                                <svg class="w-9 h-9 text-blue-500 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                <span class="text-xs font-medium text-slate-700">Unggah Beberapa Foto Sekaligus</span>
                                <span class="text-[11px] text-slate-400 mt-0.5">Interior kamar, kamar mandi, dapur, parkir (maks 8 foto)</span>
                            </div>
                            <input type="file" name="photos[]" multiple accept="image/*" @change="handleGalleryPreview" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        </div>
                    </div>

                    {{-- Preview List Galeri --}}
                    <template x-if="galleryPreviews.length > 0">
                        <div class="md:col-span-2 pt-1">
                            <span class="text-[13px] font-medium text-slate-700 block mb-2">Pratinjau Foto Galeri:</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(item, idx) in galleryPreviews" :key="idx">
                                    <div class="relative aspect-square rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-2xs">
                                        <img :src="item.url" class="w-full h-full object-cover">
                                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[10px] font-mono tabular-nums" x-text="item.size + ' MB'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. TARIF & KAMAR AWAL --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">4</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Tarif Sewa & Unit Kamar Pertama</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Tentukan harga dasar bulanan dan jumlah kamar yang siap dipasarkan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label for="price_start" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Tarif / Bulan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="price_start"
                            name="price_start" 
                            value="{{ old('price_start', 1000000) }}" 
                            min="100000" 
                            step="50000" 
                            required 
                            class="w-full text-sm font-medium rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label for="room_name" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Nama Tipe Kamar <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="room_name"
                            name="room_name" 
                            value="{{ old('room_name', 'Tipe Standar') }}" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label for="total_rooms" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Total Kapasitas Unit <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="total_rooms"
                            name="total_rooms" 
                            value="{{ old('total_rooms', 5) }}" 
                            min="1" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>

                    <div>
                        <label for="available_rooms" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Unit Siap Huni <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="available_rooms"
                            name="available_rooms" 
                            value="{{ old('available_rooms', 2) }}" 
                            min="0" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>
                </div>
            </div>

            {{-- 5. FASILITAS KOS --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">5</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Fasilitas Hunian & Kamar</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Centang seluruh fasilitas yang tersedia dan dapat dinikmati oleh penyewa.</p>
                </div>

                <div class="space-y-5">
                    @foreach($facilitiesByCategory as $category => $items)
                        <div>
                            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                Fasilitas {{ ucfirst($category) }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
                                @foreach($items as $facility)
                                    <label class="flex items-center gap-2.5 p-2.5 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-blue-400 cursor-pointer transition select-none">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500/20">
                                        <span class="text-[13px] font-medium text-slate-800">{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 6. TOMBOL SIMPAN & SUBMIT --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    Iklan berstatus <strong>Pending</strong> akan otomatis masuk antrean verifikasi kurator Seven Kost.
                </p>

                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <button type="submit" name="action_type" value="draft" class="w-full sm:w-auto px-4 py-2 rounded-lg border border-slate-300 text-[13px] font-medium text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs">
                        Simpan sebagai Draf
                    </button>
                    <button type="submit" name="action_type" value="pending" class="w-full sm:w-auto px-5 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-[13px] font-medium shadow-xs transition cursor-pointer">
                        Ajukan Iklan & Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- Leaflet JS --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const initialLat = parseFloat("{{ old('latitude', -6.2088) }}") || -6.2088;
                const initialLng = parseFloat("{{ old('longitude', 106.8456) }}") || 106.8456;

                // Inisialisasi peta Leaflet
                const map = L.map('mapPicker').setView([initialLat, initialLng], 13);

                // Tambahkan tile OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Buat Marker yang dapat digeser (Draggable)
                const marker = L.marker([initialLat, initialLng], {
                    draggable: true
                }).addTo(map);

                function updateCoordinates(lat, lng) {
                    document.getElementById('latInput').value = lat.toFixed(7);
                    document.getElementById('lngInput').value = lng.toFixed(7);
                    // Dispatch change event to Alpine.js
                    document.getElementById('latInput').dispatchEvent(new Event('input'));
                    document.getElementById('lngInput').dispatchEvent(new Event('input'));
                }

                // Saat marker digeser
                marker.on('dragend', function (e) {
                    const position = marker.getLatLng();
                    updateCoordinates(position.lat, position.lng);
                });

                // Saat peta diklik
                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    updateCoordinates(e.latlng.lat, e.latlng.lng);
                });

                // Tombol Deteksi Lokasi GPS
                const btnGps = document.getElementById('btnDetectGps');
                if (btnGps && navigator.geolocation) {
                    btnGps.addEventListener('click', function () {
                        btnGps.innerHTML = '<span>Mendeteksi GPS...</span>';
                        navigator.geolocation.getCurrentPosition(function (pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            map.setView([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            updateCoordinates(lat, lng);
                            btnGps.innerHTML = '<span class="text-emerald-600">✓ Lokasi Terdeteksi</span>';
                        }, function (err) {
                            alert('Gagal mengambil lokasi GPS: ' + err.message);
                            btnGps.innerHTML = 'Gunakan GPS Saya';
                        });
                    });
                }
            });
        </script>
    @endpush

    </div>

</x-owner-layout>

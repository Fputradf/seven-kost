<x-seven-layout title="Pasang Iklan Kos Baru - Seven Kost">

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

    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-xs text-blue-400 font-semibold mb-2">
                <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan Saya</a>
                <span>/</span>
                <span class="text-slate-300">Pasang Iklan Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Formulir Pasang Iklan Kos Nasional
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Lengkapi rincian kos Anda, pin lokasi di peta Leaflet, dan unggah foto properti.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
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
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 1</span>
                    <h2 class="text-lg font-bold text-slate-900">Informasi Dasar Kos</h2>
                    <p class="text-xs text-slate-500">Nama dan klasifikasi jenis kos Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Kos <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kos Melati Residence Eksklusif" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Tipe Kos <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="campur" {{ old('type') == 'campur' ? 'selected' : '' }}>Kos Campur</option>
                            <option value="putri" {{ old('type') == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="putra" {{ old('type') == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Skema Periode Sewa <span class="text-red-500">*</span></label>
                        <select name="rent_type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="bulanan" {{ old('rent_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('rent_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="harian" {{ old('rent_type') == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Deskripsi Kos</label>
                        <textarea name="description" rows="3" placeholder="Ceritakan keunggulan kos, suasana lingkungan, dan kenyamanan hunian..." class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Peraturan & Ketentuan Kos</label>
                        <textarea name="rules" rows="2" placeholder="Contoh: Jam malam tamu pukul 23:00 WIB, dilarang merokok di dalam kamar..." class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('rules') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. LOKASI KOS & PETA LEAFLET --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 2</span>
                    <h2 class="text-lg font-bold text-slate-900">Lokasi & Titik Peta Leaflet</h2>
                    <p class="text-xs text-slate-500">Pilih wilayah administratif dan geser marker pin tepat di lokasi kos Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Provinsi --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Provinsi <span class="text-red-500">*</span></label>
                        <select name="province_id" x-model="provId" @change="onProvChange()" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kabupaten / Kota --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Kabupaten / Kota <span class="text-red-500">*</span></label>
                        <select name="regency_id" x-model="regId" :disabled="!provId || isLoading" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 disabled:opacity-50">
                            <option value="" x-text="isLoading ? 'Memuat wilayah...' : '-- Pilih Kab/Kota --'"></option>
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Alamat Lengkap & Patokan <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, dan patokan dekat lokasi..." required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('address') }}</textarea>
                    </div>

                    {{-- PETA INTERAKTIF LEAFLET --}}
                    <div class="md:col-span-2 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-700 uppercase">
                                📍 Pin Lokasi Kos di Peta
                            </label>
                            <button type="button" id="btnDetectGps" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3 3 1.343 3 3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12c0 4.418 3.582 8 8 8h1v2a1 1 0 001.707.707L16.414 19H17c4.418 0 8-3.582 8-8 0-5.523-4.477-10-10-10z"/></svg>
                                Gunakan GPS Saya
                            </button>
                        </div>

                        {{-- Leaflet Map Container --}}
                        <div id="mapPicker" class="h-80 w-full rounded-2xl border border-slate-200 shadow-inner overflow-hidden"></div>
                        <p class="text-[11px] text-slate-400">
                            💡 Klik pada peta atau geser (drag) penanda biru untuk menentukan letak koordinat kos yang tepat.
                        </p>

                        {{-- Koordinat Input Tersembunyi/Readonly --}}
                        <div class="grid grid-cols-2 gap-4 pt-1">
                            <div>
                                <label class="block text-[11px] text-slate-400 font-semibold">Latitude</label>
                                <input type="text" name="latitude" id="latInput" x-model="lat" readonly class="w-full text-xs bg-slate-50 rounded-xl border-slate-200 py-2 font-mono">
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-400 font-semibold">Longitude</label>
                                <input type="text" name="longitude" id="lngInput" x-model="lng" readonly class="w-full text-xs bg-slate-50 rounded-xl border-slate-200 py-2 font-mono">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. UNGGAH FOTO COVER & GALERI (Multi-Photo Upload + Live Preview) --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 3</span>
                    <h2 class="text-lg font-bold text-slate-900">Foto & Galeri Properti</h2>
                    <p class="text-xs text-slate-500">Iklan dengan foto berkualitas 3x lebih cepat diminati calon penghuni.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Foto Sampul Utama --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase">
                            Foto Sampul Utama (Cover)
                        </label>
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-blue-500 transition relative bg-slate-50/50">
                            <template x-if="coverPreview">
                                <div class="relative aspect-video rounded-xl overflow-hidden mb-3">
                                    <img :src="coverPreview" class="w-full h-full object-cover">
                                </div>
                            </template>
                            <template x-if="!coverPreview">
                                <div class="py-6 flex flex-col items-center">
                                    <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium text-slate-600">Pilih foto utama kos</span>
                                    <span class="text-[10px] text-slate-400 mt-1">PNG, JPG maksimal 3MB</span>
                                </div>
                            </template>
                            <input type="file" name="cover_image" accept="image/*" @change="handleCoverPreview" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        </div>
                    </div>

                    {{-- Multi Galeri Foto --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase">
                            Galeri Foto Kamar & Lingkungan (Maksimal 8 Foto)
                        </label>
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-blue-500 transition relative bg-slate-50/50">
                            <div class="py-6 flex flex-col items-center">
                                <svg class="w-10 h-10 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                <span class="text-xs font-medium text-slate-700">Pilih beberapa foto sekaligus</span>
                                <span class="text-[10px] text-slate-400 mt-1">Kamar, kamar mandi, dapur, parkiran</span>
                            </div>
                            <input type="file" name="photos[]" multiple accept="image/*" @change="handleGalleryPreview" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        </div>
                    </div>

                    {{-- Preview List Galeri --}}
                    <template x-if="galleryPreviews.length > 0">
                        <div class="md:col-span-2 pt-2">
                            <span class="text-xs font-bold text-slate-700 block mb-2">Pratinjau Foto Galeri:</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(item, idx) in galleryPreviews" :key="idx">
                                    <div class="relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-xs">
                                        <img :src="item.url" class="w-full h-full object-cover">
                                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[9px] font-mono" x-text="item.size + ' MB'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. TARIF & KAMAR AWAL --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 4</span>
                    <h2 class="text-lg font-bold text-slate-900">Tarif & Kamar Awal</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Harga Sewa / Bulan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price_start" value="{{ old('price_start', 1000000) }}" min="100000" step="50000" required class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Tipe Kamar <span class="text-red-500">*</span></label>
                        <input type="text" name="room_name" value="{{ old('room_name', 'Tipe Standar') }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Total Kamar <span class="text-red-500">*</span></label>
                        <input type="number" name="total_rooms" value="{{ old('total_rooms', 5) }}" min="1" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Kamar Kosong / Siap Huni <span class="text-red-500">*</span></label>
                        <input type="number" name="available_rooms" value="{{ old('available_rooms', 2) }}" min="0" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>
                </div>
            </div>

            {{-- 5. FASILITAS KOS --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 5</span>
                    <h2 class="text-lg font-bold text-slate-900">Fasilitas Properti</h2>
                </div>

                <div class="space-y-6">
                    @foreach($facilitiesByCategory as $category => $items)
                        <div class="space-y-3">
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                Fasilitas {{ ucfirst($category) }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($items as $facility)
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-blue-400 cursor-pointer transition select-none">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-medium text-slate-800">{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 6. TOMBOL SIMPAN & SUBMIT --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500">
                    * Status <strong>Pending</strong> akan otomatis masuk antrean verifikasi admin untuk mendapatkan Lencana Resmi.
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="submit" name="action_type" value="draft" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Simpan sebagai Draft
                    </button>
                    <button type="submit" name="action_type" value="pending" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/25 transition">
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

</x-seven-layout>

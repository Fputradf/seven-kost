<x-seven-layout title="Pasang Iklan Kos Baru - Seven Kost">

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
                Lengkapi rincian kos Anda untuk mulai menerima calon penghuni dari seluruh Indonesia.
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
                    {{-- Nama Kos --}}
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Kos <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kos Melati Residence Eksklusif" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Tipe Kos --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Tipe Kos <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="campur" {{ old('type') == 'campur' ? 'selected' : '' }}>Kos Campur</option>
                            <option value="putri" {{ old('type') == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="putra" {{ old('type') == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                        </select>
                    </div>

                    {{-- Skema Sewa --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Skema Periode Sewa <span class="text-red-500">*</span></label>
                        <select name="rent_type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="bulanan" {{ old('rent_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('rent_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="harian" {{ old('rent_type') == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    {{-- Deskripsi Kos --}}
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Deskripsi Kos</label>
                        <textarea name="description" rows="4" placeholder="Ceritakan keunggulan kos, suasana lingkungan, dan kenyamanan hunian..." class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('description') }}</textarea>
                    </div>

                    {{-- Aturan Kos --}}
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Peraturan & Ketentuan Kos</label>
                        <textarea name="rules" rows="3" placeholder="Contoh: Jam malam pukul 23:00 WIB, dilarang merokok di dalam kamar, tamu lawan jenis dilarang masuk kamar..." class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('rules') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. LOKASI KOS (Wilayah Bertingkat) --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 2</span>
                    <h2 class="text-lg font-bold text-slate-900">Lokasi & Alamat Kos</h2>
                    <p class="text-xs text-slate-500">Pilih provinsi dan kabupaten/kota agar kos muncul di pencarian wilayah yang tepat.</p>
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
                        <textarea name="address" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, dan patokan dekat lokasi..." required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('address') }}</textarea>
                    </div>

                    {{-- Titik Koordinat Opsional --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Latitude (Opsional)</label>
                        <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="Contoh: -6.2088" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Longitude (Opsional)</label>
                        <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="Contoh: 106.8456" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>
                </div>
            </div>

            {{-- 3. KAMAR & HARGA AWAL --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 3</span>
                    <h2 class="text-lg font-bold text-slate-900">Tarif & Kamar Awal</h2>
                    <p class="text-xs text-slate-500">Tentukan harga dasar dan jumlah kamar yang tersedia.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Harga Sewa --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Harga Sewa / Bulan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price_start" value="{{ old('price_start', 1000000) }}" min="100000" step="50000" required class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Nama Tipe Kamar --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Tipe Kamar <span class="text-red-500">*</span></label>
                        <input type="text" name="room_name" value="{{ old('room_name', 'Tipe Standar') }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Total Kamar --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Total Kamar <span class="text-red-500">*</span></label>
                        <input type="number" name="total_rooms" value="{{ old('total_rooms', 5) }}" min="1" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Kamar Tersedia --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Kamar Kosong / Siap Huni <span class="text-red-500">*</span></label>
                        <input type="number" name="available_rooms" value="{{ old('available_rooms', 2) }}" min="0" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Ukuran Kamar --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Ukuran Kamar (Opsional)</label>
                        <input type="text" name="room_size" value="{{ old('room_size', '3x4 meter') }}" placeholder="Contoh: 3x4 meter" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    {{-- Foto Cover --}}
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Foto Cover Utama (JPG, PNG)</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
            </div>

            {{-- 4. FASILITAS KOS --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 4</span>
                    <h2 class="text-lg font-bold text-slate-900">Fasilitas Kos</h2>
                    <p class="text-xs text-slate-500">Centang fasilitas yang tersedia di properti kos Anda.</p>
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

            {{-- 5. TOMBOL SIMPAN & SUBMIT --}}
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

</x-seven-layout>

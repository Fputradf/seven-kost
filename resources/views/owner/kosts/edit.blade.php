<x-seven-layout title="Edit Iklan Kos - Seven Kost">

    <div class="bg-navy-950 text-white py-10 border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-xs text-blue-400 font-semibold mb-2">
                <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan Saya</a>
                <span>/</span>
                <span class="text-slate-300">Edit Iklan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Edit Iklan: {{ $kost->name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Perbarui rincian properti kos, harga, fasilitas, dan informasi ketersediaan.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <form 
            action="{{ route('owner.kosts.update', $kost) }}" 
            method="POST" 
            class="space-y-8"
            x-data="{
                provId: '{{ old('province_id', $kost->province_id) }}',
                regId: '{{ old('regency_id', $kost->regency_id) }}',
                regencies: @json($regencies),
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
                }
            }"
        >
            @csrf
            @method('PUT')

            {{-- 1. INFORMASI DASAR KOS --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 1</span>
                    <h2 class="text-lg font-bold text-slate-900">Informasi Dasar Kos</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Nama Kos</label>
                        <input type="text" name="name" value="{{ old('name', $kost->name) }}" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Tipe Kos</label>
                        <select name="type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="campur" {{ old('type', $kost->type) == 'campur' ? 'selected' : '' }}>Kos Campur</option>
                            <option value="putri" {{ old('type', $kost->type) == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="putra" {{ old('type', $kost->type) == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Skema Periode Sewa</label>
                        <select name="rent_type" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            <option value="bulanan" {{ old('rent_type', $kost->rent_type) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('rent_type', $kost->rent_type) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="harian" {{ old('rent_type', $kost->rent_type) == 'harian' ? 'selected' : '' }}>Harian</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Deskripsi Kos</label>
                        <textarea name="description" rows="4" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('description', $kost->description) }}</textarea>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Peraturan & Ketentuan</label>
                        <textarea name="rules" rows="3" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('rules', $kost->rules) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. LOKASI KOS --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 2</span>
                    <h2 class="text-lg font-bold text-slate-900">Lokasi & Alamat Kos</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Provinsi</label>
                        <select name="province_id" x-model="provId" @change="onProvChange()" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Kabupaten / Kota</label>
                        <select name="regency_id" x-model="regId" :disabled="!provId || isLoading" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 disabled:opacity-50">
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name" :selected="reg.id == regId"></option>
                            </template>
                        </select>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Alamat Lengkap</label>
                        <textarea name="address" rows="3" required class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">{{ old('address', $kost->address) }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Harga Sewa / Bulan (Rp)</label>
                        <input type="number" name="price_start" value="{{ old('price_start', (int)$kost->price_start) }}" required class="w-full text-sm font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    </div>
                </div>
            </div>

            {{-- 3. FASILITAS KOS --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Bagian 3</span>
                    <h2 class="text-lg font-bold text-slate-900">Fasilitas Kos</h2>
                </div>

                <div class="space-y-6">
                    @foreach($facilitiesByCategory as $category => $items)
                        <div class="space-y-3">
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                                Fasilitas {{ ucfirst($category) }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($items as $facility)
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-blue-400 cursor-pointer transition select-none">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', $selectedFacilityIds)) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-medium text-slate-800">{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex items-center justify-between">
                <a href="{{ route('owner.kosts.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                    Batal
                </a>

                <div class="flex items-center gap-3">
                    <button type="submit" name="action_type" value="draft" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Simpan Draft
                    </button>
                    <button type="submit" name="action_type" value="pending" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/25 transition">
                        Simpan & Ajukan Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>

</x-seven-layout>

<x-owner-layout :title="'Edit Iklan - ' . $kost->name">

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        {{-- Header Card (Refined Sapphire Blue Gradient) --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #0f3d7c 0%, #1b5cb8 50%, #2563eb 100%);">
            <div class="flex items-center gap-2 text-xs text-blue-200 font-medium mb-2">
                <a href="{{ route('owner.dashboard') }}" class="hover:underline">Dasbor Pemilik</a>
                <span>/</span>
                <a href="{{ route('owner.kosts.index') }}" class="hover:underline">Kelola Iklan Saya</a>
                <span>/</span>
                <span class="text-white font-semibold">Edit Iklan</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                Edit Iklan: {{ $kost->name }}
            </h1>
            <p class="text-xs sm:text-sm text-blue-100/90 mt-1">
                Perbarui rincian properti kos, harga, fasilitas, dan informasi ketersediaan.
            </p>
        </div>
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
                            value="{{ old('name', $kost->name) }}" 
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
                            <option value="campur" {{ old('type', $kost->type) == 'campur' ? 'selected' : '' }}>Kos Campur</option>
                            <option value="putri" {{ old('type', $kost->type) == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                            <option value="putra" {{ old('type', $kost->type) == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
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
                            <option value="bulanan" {{ old('rent_type', $kost->rent_type) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('rent_type', $kost->rent_type) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="harian" {{ old('rent_type', $kost->rent_type) == 'harian' ? 'selected' : '' }}>Harian</option>
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
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('description', $kost->description) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="rules" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Peraturan & Tata Tertib Kos
                        </label>
                        <textarea 
                            id="rules"
                            name="rules" 
                            rows="3" 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('rules', $kost->rules) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 2. LOKASI KOS --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">2</span>
                        <h2 class="text-base font-semibold text-slate-900 tracking-tight">Lokasi Administratif & Tarif Dasar</h2>
                    </div>
                    <p class="text-xs text-slate-500 pl-7">Wilayah operasional dan patokan tarif sewa bulanan properti.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

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
                            <template x-for="reg in regencies" :key="reg.id">
                                <option :value="reg.id" x-text="reg.name" :selected="reg.id == regId"></option>
                            </template>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Alamat Lengkap & Patokan Akses <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            id="address"
                            name="address" 
                            rows="3" 
                            required 
                            class="w-full text-sm rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 placeholder:text-slate-400 shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >{{ old('address', $kost->address) }}</textarea>
                    </div>

                    <div>
                        <label for="price_start" class="block text-[13px] font-medium text-slate-700 mb-1">
                            Tarif Sewa Dasar / Bulan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="price_start"
                            name="price_start" 
                            value="{{ old('price_start', (int)$kost->price_start) }}" 
                            required 
                            class="w-full text-sm font-medium rounded-lg border-slate-300 bg-white px-3.5 py-2 text-slate-900 tabular-nums shadow-2xs transition focus:border-blue-600 focus:outline-hidden focus:ring-2 focus:ring-blue-500/15"
                        >
                    </div>
                </div>
            </div>

            {{-- 3. FASILITAS KOS --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-6 sm:p-7 shadow-xs">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-blue-50 text-blue-700 text-xs font-semibold ring-1 ring-blue-600/20">3</span>
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
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', $selectedFacilityIds)) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500/20">
                                        <span class="text-[13px] font-medium text-slate-800">{{ $facility->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
                <a href="{{ route('owner.kosts.index') }}" class="text-[13px] font-medium text-slate-600 hover:text-slate-900 transition">
                    Batal
                </a>

                <div class="flex items-center gap-2.5">
                    <button type="submit" name="action_type" value="draft" class="px-4 py-2 rounded-lg border border-slate-300 text-[13px] font-medium text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs">
                        Simpan Draf
                    </button>
                    <button type="submit" name="action_type" value="pending" class="px-5 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-[13px] font-medium shadow-xs transition cursor-pointer">
                        Simpan & Ajukan Verifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>

</x-owner-layout>


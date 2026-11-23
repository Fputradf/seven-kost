<x-partner-layout title="Profil Usaha Mitra">
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        <style>
            .leaflet-container {
                z-index: 10 !important;
                font-family: inherit !important;
            }
        </style>
    @endpush

    <div class="space-y-5 max-w-5xl">

        {{-- Header Card Gradient with Store Banner Image --}}
        <div class="relative overflow-hidden rounded-2xl p-5 sm:p-6 text-white shadow-md border border-blue-400/25 min-h-[140px] flex items-center">
            {{-- Background Store Banner Image with Gradient Overlay --}}
            <img src="{{ $partner->banner_url }}" alt="Banner Profil Mitra" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(12, 43, 84, 0.92) 0%, rgba(20, 70, 133, 0.85) 60%, rgba(30, 91, 168, 0.78) 100%);"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                <div class="flex items-center gap-4">
                    {{-- Avatar / Brand Icon --}}
                    <div class="h-16 w-16 rounded-2xl bg-white/20 ring-2 ring-white/40 text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0 backdrop-blur-xs overflow-hidden">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ $partner->business_name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($partner->business_name, 0, 1)) }}
                        @endif
                    </div>

                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/15 border border-white/20 text-white backdrop-blur-xs mb-1">
                            <span>Mitra Resmi</span>
                            <span>•</span>
                            <span>{{ $partner->category_label }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                            {{ $partner->business_name }}
                        </h1>
                        @if($partner->address)
                            <p class="text-xs text-sky-100/90 mt-0.5 truncate max-w-md">
                                {{ $partner->address }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full {{ $banner && $banner->is_active ? 'bg-emerald-400 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span class="text-xs font-semibold text-white">
                            {{ $banner && $banner->is_active ? 'Banner /layanan Aktif' : 'Banner /layanan Nonaktif' }}
                        </span>
                    </div>

                    <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full {{ $partner->is_open ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                        <span class="text-xs font-semibold text-white">
                            {{ $partner->is_open ? 'Toko Buka' : 'Toko Tutup' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('partner.profile.toggle-status') }}">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="px-3.5 py-1.5 rounded-xl font-semibold text-xs transition-all cursor-pointer {{ $partner->is_open ? 'bg-white/15 hover:bg-white/25 text-white border border-white/25' : 'bg-emerald-500 hover:bg-emerald-400 text-white shadow-xs' }}"
                            title="Ubah status operasional toko"
                        >
                            {{ $partner->is_open ? 'Tutup Toko' : 'Buka Toko' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Form Profil --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-5 sm:p-6 shadow-xs">
            <form method="POST" action="{{ route('partner.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Upload Media: Foto Profil / Logo Usaha --}}
                <div class="pb-5 border-b border-slate-100" x-data="{ 
                    avatarPreview: '{{ auth()->user()->avatar_url ?? '' }}'
                }">
                    <div class="flex items-center gap-4 p-4 rounded-2xl border border-slate-150 bg-slate-50/60 max-w-xl">
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-blue-500/20 shadow-xs shrink-0">
                        </template>
                        <template x-if="!avatarPreview">
                            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 ring-2 ring-blue-500/20 font-black text-xl flex items-center justify-center shadow-xs shrink-0">
                                {{ strtoupper(substr($partner->business_name, 0, 1)) }}
                            </div>
                        </template>
                        <div class="min-w-0 flex-1 space-y-1">
                            <label class="block text-xs font-bold text-slate-800">Logo / Foto Profil Usaha</label>
                            <input 
                                type="file" 
                                name="avatar" 
                                accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file) }"
                                class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                            >
                            <p class="text-[10px] text-slate-400">Format JPG, PNG atau WebP (maks. 2MB). Ditampilkan pada kartu layanan.</p>
                        </div>
                    </div>
                </div>

                {{-- Bagian: Banner Iklan Promosi Layanan (/layanan) --}}
                @php
                    $partnerBannerImg = $banner?->image_url ?? $partner->banner_url;
                    $partnerBannerTitle = old('banner_title', $banner?->title ?? ('Promo Spesial ' . $partner->business_name));
                    $partnerBannerTargetUrl = old('banner_target_url', $banner?->target_url ?? route('services.index'));
                    $partnerBannerIsActive = old('banner_is_active', $banner ? (bool)$banner->is_active : true);
                @endphp
                <div 
                    class="space-y-4 pt-4 pb-6 border-b border-slate-100" 
                    x-data="{
                        bannerPreview: @js($partnerBannerImg),
                        bannerTitle: @js($partnerBannerTitle),
                        bannerTargetUrl: @js($partnerBannerTargetUrl),
                        bannerIsActive: {{ $partnerBannerIsActive ? 'true' : 'false' }},
                        businessName: @js($partner->business_name)
                    }"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Banner Iklan Halaman /layanan</span>
                                </h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black" :class="bannerIsActive ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'">
                                    <span x-text="bannerIsActive ? 'Tayang di /layanan' : 'Nonaktif (Tersembunyi)'"></span>
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Banner promosi usaha Anda yang tampil langsung pada carousel slider bagian atas halaman <a href="{{ route('services.index') }}" target="_blank" class="text-blue-600 underline font-semibold">/layanan</a>.</p>
                        </div>
                        <a 
                            href="{{ route('services.index') }}" 
                            target="_blank" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition shrink-0"
                        >
                            <span>Lihat Halaman /layanan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>

                    {{-- Live Mockup Carousel Slider Preview --}}
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-200 shadow-md h-40 sm:h-52 w-full group">
                        <img :src="bannerPreview" alt="Pratinjau Banner Iklan" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>

                        {{-- Top Left Badge --}}
                        <div class="absolute top-3 left-3 px-2.5 py-1 rounded-xl bg-black/60 text-white text-[11px] font-semibold tracking-wider uppercase backdrop-blur-xs flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Iklan Sponsor &bull; Promo Eksklusif</span>
                        </div>

                        {{-- Bottom Left Content --}}
                        <div class="absolute bottom-3 left-3 right-28 sm:right-40 max-w-lg space-y-0.5">
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-600/90 text-white text-[10px] font-black uppercase tracking-wider backdrop-blur-xs">
                                <span x-text="businessName"></span>
                            </div>
                            <h4 class="text-white text-xs sm:text-base font-black drop-shadow-md line-clamp-1 leading-snug" x-text="bannerTitle || 'Judul Promo Banner Anda'"></h4>
                        </div>

                        {{-- Bottom Right CTA Button Mockup --}}
                        <div class="absolute bottom-3 right-3">
                            <div class="text-[11px] sm:text-xs font-bold text-white bg-blue-600 px-3 py-1.5 rounded-xl backdrop-blur-xs shadow-md flex items-center gap-1">
                                <span>Lihat Penawaran</span>
                                <span>&rarr;</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Fields for Banner --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Unggah / Ganti Gambar Banner</label>
                            <input 
                                type="file" 
                                name="banner_image" 
                                accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { bannerPreview = URL.createObjectURL(file) }"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            >
                            <span class="text-[10px] text-slate-500 block mt-1">Format JPG, PNG, WebP (maks. 4MB). Rekomendasi rasio landscape lebar (16:9 / ~1200x400 px).</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Promo / Headline Banner Iklan</label>
                            <input 
                                type="text" 
                                name="banner_title" 
                                x-model="bannerTitle"
                                maxlength="150"
                                placeholder="Contoh: Diskon Cuci Kilat 20% Khusus Penghuni Kost"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                            <span class="text-[10px] text-slate-500 block mt-1">Kalimat headline penawaran yang tampil di atas banner iklan.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Target (URL Aksi Ketika Banner Diklik)</label>
                            <input 
                                type="url" 
                                name="banner_target_url" 
                                x-model="bannerTargetUrl"
                                placeholder="https://..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition font-mono"
                            >
                            <span class="text-[10px] text-slate-500 block mt-1">Biarkan kosong untuk menggunakan tautan default katalog /layanan.</span>
                        </div>

                        <div class="flex items-center pt-2 sm:pt-4">
                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-slate-200 bg-slate-50/70 cursor-pointer hover:bg-slate-100/70 transition w-full">
                                <input 
                                    type="checkbox" 
                                    name="banner_is_active" 
                                    value="1" 
                                    x-model="bannerIsActive"
                                    class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 transition"
                                >
                                <div class="text-xs">
                                    <strong class="font-bold text-slate-800 block">Tayangkan di Slider Halaman /layanan</strong>
                                    <span class="text-[10px] text-slate-500">Banner aktif dan tampil dalam rotasi slider atas /layanan.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Bagian 1: Identitas Bisnis --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Usaha &amp; Kontak</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Brand / Usaha *</label>
                            <input 
                                type="text" 
                                name="business_name" 
                                value="{{ old('business_name', $partner->business_name) }}"
                                required
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Layanan *</label>
                            <select 
                                name="category" 
                                required
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white"
                            >
                                <option value="laundry" {{ $partner->category === 'laundry' ? 'selected' : '' }}>Laundry Kiloan</option>
                                <option value="cleaning" {{ $partner->category === 'cleaning' ? 'selected' : '' }}>Kebersihan Kamar &amp; Toilet</option>
                                <option value="catering" {{ $partner->category === 'catering' ? 'selected' : '' }}>Katering Harian</option>
                                <option value="maintenance" {{ $partner->category === 'maintenance' ? 'selected' : '' }}>Teknisi AC &amp; Listrik</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp Operasional *</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $partner->phone) }}"
                                required
                                placeholder="08xxxxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Jam Operasional</label>
                            <input 
                                type="text" 
                                name="operational_hours" 
                                value="{{ old('operational_hours', $partner->operational_hours) }}"
                                placeholder="08:00 - 20:00 WIB"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: Wilayah & Lokasi Peta Interaktif --}}
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Wilayah &amp; Lokasi Hub</h3>
                        </div>
                        <button 
                            type="button" 
                            id="btnDetectGps"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition self-start sm:self-auto cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Gunakan Lokasi Saat Ini (GPS)</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Provinsi</label>
                            <select 
                                name="province_id" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white"
                            >
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach ($provinces as $prov)
                                    <option value="{{ $prov->id }}" {{ $partner->province_id == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kota / Kabupaten</label>
                            <select 
                                name="regency_id" 
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white"
                            >
                                <option value="">-- Pilih Kota/Kabupaten --</option>
                                @foreach ($regencies as $reg)
                                    <option value="{{ $reg->id }}" {{ $partner->regency_id == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Hub / Toko</label>
                            <textarea 
                                name="address" 
                                rows="2"
                                placeholder="Jalan, nomor bangunan, patokan..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >{{ old('address', $partner->address) }}</textarea>
                        </div>
                    </div>

                    {{-- Leaflet Interactive Map Picker --}}
                    <div class="space-y-2 pt-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700">Tentukan Titik Lokasi pada Peta (Klik atau Geser Pin)</span>
                            <span id="coordDisplay" class="font-mono text-slate-400 text-[11px]">
                                {{ $partner->latitude ?? -6.2088 }}, {{ $partner->longitude ?? 106.8456 }}
                            </span>
                        </div>

                        {{-- Leaflet Map Canvas --}}
                        <div id="partnerMapPicker" class="h-64 sm:h-72 w-full rounded-2xl border border-slate-200 overflow-hidden shadow-inner"></div>

                        {{-- Hidden Coordinates --}}
                        <input type="hidden" name="latitude" id="latInput" value="{{ old('latitude', $partner->latitude ?? -6.2088) }}">
                        <input type="hidden" name="longitude" id="lngInput" value="{{ old('longitude', $partner->longitude ?? 106.8456) }}">

                        <div class="pt-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Radius Jangkauan Layanan Antar-Jemput (KM)</label>
                            <div class="flex items-center gap-3">
                                <input 
                                    type="number" 
                                    step="0.5" 
                                    min="0.5"
                                    max="25"
                                    name="service_radius_km" 
                                    id="radiusInput"
                                    value="{{ old('service_radius_km', $partner->service_radius_km ?? 5) }}"
                                    class="w-32 px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                >
                                <span class="text-xs text-slate-500">Kilometer dari titik hub</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian 3: Rekening Bank Pencairan --}}
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rekening Bank Pencairan Pendapatan</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Bank</label>
                            <input 
                                type="text" 
                                name="bank_name" 
                                value="{{ old('bank_name', $partner->bank_name) }}"
                                placeholder="Misal: BCA, Mandiri"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Rekening</label>
                            <input 
                                type="text" 
                                name="bank_account_number" 
                                value="{{ old('bank_account_number', $partner->bank_account_number) }}"
                                placeholder="Nomor rekening"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Atas Nama Rekening</label>
                            <input 
                                type="text" 
                                name="bank_account_holder" 
                                value="{{ old('bank_account_holder', $partner->bank_account_holder) }}"
                                placeholder="Nama pemilik rekening"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                    >
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const initialLat = parseFloat("{{ old('latitude', $partner->latitude ?? -6.2088) }}") || -6.2088;
                const initialLng = parseFloat("{{ old('longitude', $partner->longitude ?? 106.8456) }}") || 106.8456;
                const initialRadius = parseFloat("{{ old('service_radius_km', $partner->service_radius_km ?? 5) }}") || 5;

                const map = L.map('partnerMapPicker').setView([initialLat, initialLng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Draggable Marker
                const marker = L.marker([initialLat, initialLng], {
                    draggable: true
                }).addTo(map);

                // Radius Circle
                let circle = L.circle([initialLat, initialLng], {
                    color: '#2563eb',
                    fillColor: '#3b82f6',
                    fillOpacity: 0.15,
                    radius: initialRadius * 1000
                }).addTo(map);

                function updateCoords(lat, lng) {
                    document.getElementById('latInput').value = lat.toFixed(7);
                    document.getElementById('lngInput').value = lng.toFixed(7);
                    document.getElementById('coordDisplay').textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    circle.setLatLng([lat, lng]);
                }

                marker.on('dragend', function () {
                    const pos = marker.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    updateCoords(e.latlng.lat, e.latlng.lng);
                });

                // Radius change updates circle
                const radiusInput = document.getElementById('radiusInput');
                if (radiusInput) {
                    radiusInput.addEventListener('input', function () {
                        const val = parseFloat(this.value) || 1;
                        circle.setRadius(val * 1000);
                    });
                }

                // GPS Auto-detect Button
                const btnGps = document.getElementById('btnDetectGps');
                if (btnGps && navigator.geolocation) {
                    btnGps.addEventListener('click', function () {
                        btnGps.innerHTML = '<span>Mendeteksi GPS...</span>';
                        navigator.geolocation.getCurrentPosition(function (pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            map.setView([lat, lng], 15);
                            marker.setLatLng([lat, lng]);
                            updateCoords(lat, lng);
                            btnGps.innerHTML = '<span class="text-emerald-700">✓ Lokasi Terdeteksi</span>';
                        }, function (err) {
                            alert('Gagal mendeteksi lokasi GPS: ' + err.message);
                            btnGps.innerHTML = '<span>Gunakan Lokasi Saat Ini (GPS)</span>';
                        }, {
                            enableHighAccuracy: true,
                            timeout: 8000
                        });
                    });
                }
            });
        </script>
    @endpush
</x-partner-layout>

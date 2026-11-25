<x-partner-layout title="Pengaturan Usaha Mitra">
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
                    {{-- Avatar / Brand Icon (Harmonisasi dengan Navbar) --}}
                    @if(Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-white/40 shadow-md shrink-0">
                    @else
                        <div class="h-16 w-16 rounded-2xl bg-white/20 ring-2 ring-white/40 text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0 backdrop-blur-xs overflow-hidden">
                            {{ strtoupper(substr($partner->business_name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/15 border border-white/20 text-white backdrop-blur-xs mb-1">
                            <span>Mitra Resmi</span>
                            <span>•</span>
                            <span>{{ $partner->category_label }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                            {{ $partner->business_name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-2.5 text-xs text-sky-100/90 mt-1">
                            <span class="inline-flex items-center gap-1 font-semibold text-white">
                                <svg class="w-3.5 h-3.5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Pengelola: {{ Auth::user()->name }}</span>
                            </span>
                            @if($partner->address)
                                <span class="text-white/40">•</span>
                                <span class="truncate max-w-md">{{ $partner->address }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full {{ $partner->banner_image ? 'bg-emerald-400 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span class="text-xs font-semibold text-white">
                            {{ $partner->banner_image ? 'Foto Kartu Kustom Aktif' : 'Foto Kartu Default' }}
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

        {{-- Flash Status Messages --}}
        @if (session('status') === 'profile-updated')
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Profil akun pengelola berhasil diperbarui!</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Kata sandi akun Anda berhasil diperbarui!</span>
            </div>
        @endif

        @if (session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2 shadow-2xs">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Form Pengaturan Usaha dengan Tab Navigasi Terpisah --}}
        <div 
            class="rounded-2xl bg-white border border-slate-200/90 shadow-xs overflow-hidden"
            x-data="{
                activeTab: ((new URLSearchParams(window.location.search).get('tab') === 'branches' ? 'location' : new URLSearchParams(window.location.search).get('tab')) || 'profile'),
                setTab(tab) {
                    this.activeTab = tab;
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                    if (tab === 'location') {
                        setTimeout(() => {
                            if (window.partnerMap) {
                                window.partnerMap.invalidateSize();
                            }
                        }, 120);
                    }
                }
            }"
        >
            {{-- Tab Header Bar --}}
            <div class="border-b border-slate-200 bg-slate-50/80 p-2 sm:px-4 flex flex-wrap items-center gap-1 sm:gap-2">
                {{-- Tab 1: Profil Usaha & Kartu --}}
                <button 
                    type="button"
                    @click="setTab('profile')"
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    :class="activeTab === 'profile' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>1. Profil &amp; Kartu Layanan</span>
                </button>

                {{-- Tab 2: Wilayah & Lokasi Hub (+ Cabang) --}}
                <button 
                    type="button"
                    @click="setTab('location')"
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    :class="activeTab === 'location' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>2. Wilayah &amp; Lokasi Hub</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black" :class="activeTab === 'location' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-700'">
                        {{ $partner->branches->count() }} Cabang
                    </span>
                </button>

                {{-- Tab 3: Rekening Bank --}}
                <button 
                    type="button"
                    @click="setTab('bank')"
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    :class="activeTab === 'bank' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>3. Rekening Bank Pencairan</span>
                </button>

                {{-- Tab 4: Profil Akun Pengelola --}}
                <button 
                    type="button"
                    @click="setTab('account')"
                    class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all cursor-pointer select-none"
                    :class="activeTab === 'account' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>4. Profil Pengelola</span>
                </button>
            </div>

            {{-- Form Tab 1, 2, 3: Pengaturan Usaha Mitra --}}
            <form method="POST" action="{{ route('partner.profile.update') }}" enctype="multipart/form-data" class="p-5 sm:p-6" x-show="activeTab !== 'account'">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" :value="activeTab">

                {{-- =========================================================
                     TAB 1: PROFIL USAHA & KARTU LAYANAN
                     ========================================================= --}}
                <div x-show="activeTab === 'profile'" class="space-y-6">
                    @php
                        $partnerCardImg = $partner->banner_url;
                    @endphp

                    {{-- Section Header --}}
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Identitas Usaha &amp; Foto Kartu Layanan</h2>
                            <p class="text-[11px] text-slate-500">Kelola identitas merek, nomor WhatsApp operasional, dan foto sampul yang muncul pada katalog layanan.</p>
                        </div>
                    </div>

                    {{-- Foto Sampul Kartu Layanan & Live Mockup Preview --}}
                    <div 
                        class="space-y-4 pt-1 pb-6 border-b border-slate-100" 
                        x-data="partnerCardPreviewManager()"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Foto Utama Kartu Layanan (Card Cover di /layanan)</span>
                                    </h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">
                                        Tampil di Katalog /layanan
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">Foto sampul yang tampil langsung pada kartu penawaran jasa Anda di halaman <a href="{{ route('services.index') }}" target="_blank" class="text-blue-600 underline font-semibold">/layanan</a> dan aplikasi penghuni.</p>
                            </div>
                            <a 
                                href="{{ route('services.index') }}" 
                                target="_blank" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition shrink-0"
                            >
                                <span>Lihat Tampilan di /layanan</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        {{-- Live Mockup Kartu Layanan (Card Preview) & Form Upload --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start pt-2">
                            {{-- Mockup Card --}}
                            <div class="md:col-span-5 sm:max-w-xs w-full bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                                {{-- Image Cover with Gradient & Badges --}}
                                <div class="relative h-44 w-full overflow-hidden bg-slate-100">
                                    <img :src="cardPreview" alt="Pratinjau Kartu Layanan" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                    <div class="absolute top-2.5 left-2.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-black/60 text-white backdrop-blur-xs">
                                            {{ $partner->category_label }}
                                        </span>
                                    </div>
                                    <div class="absolute bottom-2.5 left-2.5 right-2.5 flex items-center justify-between text-white">
                                        <div class="flex items-center gap-1 text-[11px] font-extrabold text-amber-300 bg-black/60 backdrop-blur-xs px-1.5 py-0.5 rounded-md">
                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span>4.9</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[9px] text-blue-200 block font-semibold leading-none">Mulai</span>
                                            <strong class="text-xs font-black text-white">Rp 7.000</strong>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="p-3.5 space-y-2.5">
                                    <div>
                                        <h4 class="text-xs font-extrabold text-slate-900 leading-snug line-clamp-1" x-text="businessName"></h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Mitra Resmi SevenKost &bull; Radius {{ $partner->service_radius_km ?? 5 }} km</p>
                                    </div>
                                    <div class="py-1.5 px-3 rounded-xl bg-blue-600 text-white font-bold text-[11px] text-center shadow-xs flex items-center justify-center gap-1">
                                        <span>Pratinjau Tombol Pesan</span>
                                        <span>&rarr;</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Form Field & Guidance --}}
                            <div class="md:col-span-7 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah / Ganti Foto Kartu Layanan</label>
                                    <input 
                                        type="file" 
                                        name="banner_image" 
                                        accept="image/*"
                                        @change="const file = $event.target.files[0]; if (file) { cardPreview = URL.createObjectURL(file) }"
                                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer border border-slate-200 rounded-xl p-1 bg-slate-50/50"
                                    >
                                    <p class="text-[11px] text-slate-500 mt-2 leading-relaxed">
                                        Format: <strong>JPG, PNG, atau WebP</strong> (maksimal 4MB). Disarankan foto landscape atau rasio 4:3 yang menampilkan tempat usaha, proses pengerjaan, atau perlengkapan Anda secara jelas.
                                    </p>
                                </div>

                                <div class="p-3 rounded-xl bg-blue-50/80 border border-blue-200/70 text-xs text-blue-900 space-y-1">
                                    <strong class="font-bold flex items-center gap-1.5 text-blue-950">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Tips Foto Menarik Penghuni Kos</span>
                                    </strong>
                                    <p class="text-[11px] text-blue-800/90 leading-normal">
                                        Gunakan foto asli tempat usaha yang bersih dan terpercaya untuk menarik hingga 3x lipat pesanan masuk dari penghuni kos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Identitas Usaha --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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
                                <span class="text-[10px] text-slate-400 mt-1 block">Tampil pada tombol Chat WhatsApp pelacakan pesanan.</span>
                            </div>
                        </div>

                        {{-- Keunggulan & Keterangan Layanan (Tampil di Kartu Katalog) --}}
                        @php
                            $defaultFeaturesByCategory = match ($partner->category) {
                                'cleaning' => [
                                    'Pembersihan kerak kloset & dinding kamar mandi',
                                    'Sapu, pel wangi, dan vakum debu menyeluruh',
                                    'Pembersihan kaca jendela & perabot meja/lemari',
                                ],
                                'catering' => [
                                    'Menu berganti setiap hari (bebas bosan)',
                                    'Bebas MSG berlebih, higienis & halal',
                                    'Pengantaran tepat waktu makan siang & malam',
                                ],
                                default => [
                                    'Layanan antar-jemput langsung ke pintu kamar kos',
                                    'Deterjen anti-bakteri & pewangi grade hotel',
                                    'Pemisahan pakaian putih dan berwarna',
                                ],
                            };
                            $initialFeatures = old('service_features', $partner->service_features ?: $defaultFeaturesByCategory);
                            if (empty($initialFeatures)) {
                                $initialFeatures = $defaultFeaturesByCategory;
                            }
                        @endphp
                        <div
                            class="pt-4 border-t border-slate-100 space-y-3"
                            x-data="{
                                features: @js(array_values($initialFeatures)),
                                addFeature() {
                                    if (this.features.length < 6) {
                                        this.features.push('');
                                    }
                                },
                                removeFeature(idx) {
                                    if (this.features.length > 1) {
                                        this.features.splice(idx, 1);
                                    } else {
                                        this.features[0] = '';
                                    }
                                }
                            }"
                        >
                            <input type="hidden" name="service_features_submitted" value="1">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800">Keunggulan &amp; Keterangan Layanan (Kartu Katalog)</label>
                                    <p class="text-[11px] text-slate-500">Poin-poin ini tampil di bawah deskripsi kartu layanan Anda pada halaman katalog penghuni.</p>
                                </div>
                                <button
                                    type="button"
                                    @click="addFeature()"
                                    x-show="features.length < 6"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold transition cursor-pointer w-fit"
                                >
                                    <span>+ Tambah Poin</span>
                                </button>
                            </div>

                            <div class="space-y-2">
                                <template x-for="(feat, idx) in features" :key="idx">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 text-[10px] font-black">✓</span>
                                        <input
                                            type="text"
                                            name="service_features[]"
                                            x-model="features[idx]"
                                            maxlength="150"
                                            placeholder="Contoh: Layanan antar-jemput langsung ke pintu kamar kos"
                                            class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                        >
                                        <button
                                            type="button"
                                            @click="removeFeature(idx)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                            title="Hapus poin"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button Tab 1 --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pastikan informasi merek dan WhatsApp sudah benar.</span>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                        >
                            Simpan Profil Usaha
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     TAB 2: WILAYAH & LOKASI OPERASIONAL
                     ========================================================= --}}
                <div 
                    x-show="activeTab === 'location'" 
                    class="space-y-5"
                    style="display: none;"
                    x-data="partnerLocationManager()"
                >
                    {{-- Section Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Wilayah, Alamat &amp; Jangkauan Layanan</h2>
                            <p class="text-[11px] text-slate-500">Tentukan pusat hub/toko, radius jangkauan kurir, dan pin GPS agar kos sekitar dapat mendeteksi layanan Anda.</p>
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

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        {{-- 1. Provinsi --}}
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Provinsi</label>
                            <select 
                                name="province_id" 
                                x-model="provId"
                                @change="onProvChange($event)"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white"
                            >
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach ($provinces as $prov)
                                    <option 
                                        value="{{ $prov->id }}" 
                                        data-lat="{{ $prov->latitude }}" 
                                        data-lng="{{ $prov->longitude }}"
                                        {{ $partner->province_id == $prov->id ? 'selected' : '' }}
                                    >
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Kota / Kabupaten (Dinamis sesuai Provinsi) --}}
                        <div class="md:col-span-6">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-slate-700">Kota / Kabupaten</label>
                                <span x-show="isLoadingRegencies" class="text-[10px] text-blue-600 font-semibold flex items-center gap-1" style="display: none;">
                                    <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memuat kab/kota...
                                </span>
                            </div>
                            <select 
                                name="regency_id" 
                                x-model="regId"
                                @change="onRegencyChange()"
                                :disabled="!provId || isLoadingRegencies"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white disabled:bg-slate-50 disabled:text-slate-400"
                            >
                                <option value="" x-text="isLoadingRegencies ? 'Sedang memuat data wilayah...' : (provId ? '-- Pilih Kota/Kabupaten --' : '-- Pilih Provinsi Terlebih Dahulu --')"></option>
                                <template x-for="reg in regencies" :key="reg.id">
                                    <option :value="reg.id" x-text="reg.name" :selected="String(reg.id) === String(regId)"></option>
                                </template>
                            </select>
                        </div>

                        {{-- 3. Alamat Lengkap Hub / Toko (Sebelah Kiri Radius) --}}
                        <div class="md:col-span-8">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <label class="block text-xs font-bold text-slate-700">Alamat Lengkap Hub / Toko</label>
                                <button
                                    type="button"
                                    @click="geocodeHubAddress()"
                                    :disabled="isGeocoding"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold transition cursor-pointer disabled:opacity-50"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span x-text="isGeocoding ? 'Mencari titik...' : 'Cari Titik di Peta'"></span>
                                </button>
                            </div>
                            <textarea 
                                name="address" 
                                id="hubAddressInput"
                                x-model="hubAddress"
                                rows="2"
                                placeholder="Jalan, nomor bangunan, patokan..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >{{ old('address', $partner->address) }}</textarea>
                            <p x-show="geocodeMessage" x-text="geocodeMessage" class="text-[10px] text-amber-600 font-medium mt-1" style="display: none;"></p>
                        </div>

                        {{-- 4. Text Field & Button Radius (Sebelah Kanan Alamat Lengkap) --}}
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                <span>Radius Layanan (KM)</span>
                                <span class="text-[11px] font-black text-blue-600" x-text="radiusKm + ' KM'"></span>
                            </label>
                            <div class="space-y-1">
                                <div class="flex items-center rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500">
                                    <button 
                                        type="button" 
                                        @click="adjustRadius(-0.5)"
                                        class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 active:bg-slate-200 text-slate-700 font-black text-sm border-r border-slate-200 transition cursor-pointer select-none"
                                        title="Kurangi radius (-0.5 km)"
                                    >
                                        &minus;
                                    </button>
                                    <input 
                                        type="number" 
                                        step="0.5" 
                                        min="0.5" 
                                        max="25" 
                                        name="service_radius_km" 
                                        id="radiusInput"
                                        x-model="radiusKm"
                                        @input="onRadiusInput()"
                                        class="w-full px-2 py-2 text-center text-xs font-black text-slate-800 border-0 focus:ring-0 focus:outline-none"
                                    >
                                    <span class="text-xs font-bold text-slate-400 pr-2.5 shrink-0 select-none">KM</span>
                                    <button 
                                        type="button" 
                                        @click="adjustRadius(0.5)"
                                        class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 active:bg-slate-200 text-slate-700 font-black text-sm border-l border-slate-200 transition cursor-pointer select-none"
                                        title="Tambah radius (+0.5 km)"
                                    >
                                        +
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400">Jarak jangkauan kurir dari titik hub.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Leaflet Interactive Map Picker --}}
                    <div class="space-y-2 pt-1">
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
                    </div>

                    {{-- Submit Button Tab 2 --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Titik GPS dan radius menentukan kos mana saja yang dapat memesan.</span>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                        >
                            Simpan Pengaturan Lokasi
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     TAB 3: REKENING BANK PENCAIRAN
                     ========================================================= --}}
                <div x-show="activeTab === 'bank'" class="space-y-5" style="display: none;">
                    {{-- Section Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Rekening Bank Pencairan Pendapatan</h2>
                            <p class="text-[11px] text-slate-500">Nomor rekening tujuan transfer saat Anda melakukan penarikan saldo dompet mitra.</p>
                        </div>
                        <a 
                            href="{{ route('partner.finances.index') }}" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition self-start sm:self-auto cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Buka Dompet Pendapatan &rarr;</span>
                        </a>
                    </div>

                    {{-- Info Box Integrasi Dompet --}}
                    <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/70 text-xs text-blue-900 space-y-1.5">
                        <div class="flex items-center gap-2 font-bold text-blue-950">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Informasi Pencairan Saldo Dompet</span>
                        </div>
                        <p class="text-[11px] text-blue-800/90 leading-relaxed">
                            Data rekening ini disimpan sebagai rekening utama Anda. Setiap kali mengajukan pencairan pendapatan di menu <strong>Dompet &amp; Keuangan</strong>, data rekening ini akan otomatis terisi untuk mempercepat verifikasi pencairan.
                        </p>
                    </div>

                    {{-- Input Rekening --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Bank</label>
                            <input 
                                type="text" 
                                name="bank_name" 
                                value="{{ old('bank_name', $partner->bank_name) }}"
                                placeholder="Misal: BCA, Mandiri, BRI, BNI"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Rekening</label>
                            <input 
                                type="text" 
                                name="bank_account_number" 
                                value="{{ old('bank_account_number', $partner->bank_account_number) }}"
                                placeholder="Nomor rekening tanpa spasi"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Atas Nama Pemilik Rekening</label>
                            <input 
                                type="text" 
                                name="bank_account_holder" 
                                value="{{ old('bank_account_holder', $partner->bank_account_holder) }}"
                                placeholder="Sesuai nama di buku tabungan"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                    </div>

                    {{-- Submit Button Tab 3 --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pastikan nama pemilik sesuai persis dengan rekening bank Anda.</span>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                        >
                            Simpan Rekening Bank
                        </button>
                    </div>
                </div>

            </form>

            {{-- =========================================================
                 MANAJEMEN PAKET LAYANAN (BAGIAN DARI TAB 1: PROFIL & KARTU LAYANAN)
                 ========================================================= --}}
            <div x-show="activeTab === 'profile'" x-data="partnerPackageManager()" class="p-5 sm:p-6 space-y-6 border-t border-slate-200 bg-slate-50/40">
                <div class="pb-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Daftar Paket &amp; Harga Layanan</h2>
                        <p class="text-[11px] text-slate-500">Tambahkan, ubah, atau hapus paket layanan yang ditawarkan {{ $partner->business_name }} di katalog penghuni.</p>
                    </div>
                    <button
                        type="button"
                        @click="openCreateModal()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer shrink-0 w-fit"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Paket Layanan</span>
                    </button>
                </div>

                @if($partner->packages->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white/80 p-7 text-center space-y-3">
                        <div class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-800">Belum Ada Paket Kustom</h3>
                            <p class="text-[11px] text-slate-500 max-w-md mx-auto mt-1">
                                Saat ini katalog menampilkan paket standar kategori <strong>{{ $partner->category_label }}</strong>. Tambahkan paket Anda sendiri untuk mengatur nama paket, harga, dan satuannya secara langsung.
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="openCreateModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer"
                        >
                            <span>+ Buat Paket Pertama</span>
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($partner->packages as $pkg)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs hover:shadow-sm transition flex flex-col justify-between space-y-3">
                                <div class="space-y-1.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <h3 class="text-sm font-extrabold text-slate-900">{{ $pkg->name }}</h3>
                                            @if($pkg->description)
                                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $pkg->description }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-sm font-black text-blue-600">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            <span class="text-[10px] text-slate-400 font-semibold block">{{ $pkg->unit }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-1.5">
                                    <button
                                        type="button"
                                        @click='openEditModal(@json($pkg))'
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition cursor-pointer"
                                    >
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('partner.packages.destroy', $pkg) }}" onsubmit="return confirm('Yakin ingin menghapus paket {{ addslashes($pkg->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition cursor-pointer"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Modal Tambah / Edit Paket Layanan --}}
                <div
                    x-show="modalOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
                    style="display: none;"
                >
                    <div
                        @click.outside="modalOpen = false"
                        class="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4"
                    >
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm sm:text-base font-extrabold text-slate-900" x-text="isEditing ? 'Edit Paket Layanan' : 'Tambah Paket Layanan'"></h3>
                                <p class="text-[11px] text-slate-500">Atur nama, harga, dan satuan paket yang tampil pada halaman layanan kos.</p>
                            </div>
                            <button type="button" @click="modalOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form method="POST" :action="formAction" class="space-y-4">
                            @csrf
                            <template x-if="isEditing">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <div class="space-y-3.5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Paket Layanan *</label>
                                    <input
                                        type="text"
                                        name="name"
                                        x-model="form.name"
                                        required
                                        placeholder="Contoh: Cuci Kering Setrika Kilat (24 Jam)"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Harga (Rp) *</label>
                                        <input
                                            type="number"
                                            name="price"
                                            x-model="form.price"
                                            min="1000"
                                            step="500"
                                            required
                                            placeholder="10000"
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan *</label>
                                        <input
                                            type="text"
                                            name="unit"
                                            x-model="form.unit"
                                            required
                                            placeholder="/kg, /sesi, /hari, /set"
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Singkat (Opsional)</label>
                                    <input
                                        type="text"
                                        name="description"
                                        x-model="form.description"
                                        placeholder="Contoh: Termasuk pewangi premium dan lipat rapi"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    <span x-text="isEditing ? 'Simpan Perubahan Paket' : 'Simpan Paket'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- =========================================================
                 TAB 4: AKUN & KEAMANAN PENGELOLA
                 ========================================================= --}}
            <div x-show="activeTab === 'account'" class="p-5 sm:p-6 space-y-8" style="display: none;">
                {{-- Sub-bagian 1: Informasi Akun Pengguna & Foto Avatar --}}
                <div class="space-y-5">
                    <div class="pb-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800">Profil Akun Pengelola</h2>
                            <p class="text-[11px] text-slate-500">Perbarui nama lengkap, alamat email login, dan foto avatar profil akun Anda.</p>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 w-fit">
                            Akun Login Mitra
                        </span>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ avatarPreview: @js(Auth::user()->avatar_url) }">
                        @csrf
                        @method('patch')

                        {{-- Avatar Upload & Pratinjau --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                            <div class="relative shrink-0">
                                <template x-if="avatarPreview">
                                    <img :src="avatarPreview" alt="{{ Auth::user()->name }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-blue-500/30 shadow-sm">
                                </template>
                                <template x-if="!avatarPreview">
                                    <div class="h-16 w-16 rounded-full bg-blue-700 text-white font-bold text-xl flex items-center justify-center shadow-sm">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-1.5 flex-1 min-w-0">
                                <label class="block text-xs font-bold text-slate-700">Foto Profil / Avatar Akun</label>
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    accept="image/*"
                                    @change="const f = $event.target.files[0]; if(f) avatarPreview = URL.createObjectURL(f)"
                                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                                >
                                <p class="text-[10px] text-slate-400">Format: JPG, PNG, atau WebP (maksimal 2MB). Foto ini akan tampil pada avatar navbar dan header profil Anda.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Pengelola *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', Auth::user()->name) }}" 
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                >
                                @error('name')
                                    <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Login *</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', Auth::user()->email) }}" 
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                >
                                @error('email')
                                    <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                            >
                                Simpan Profil Pengelola
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- =========================================================
                 MANAJEMEN CABANG USAHA (BAGIAN DARI TAB 2: WILAYAH & LOKASI HUB)
                 ========================================================= --}}
            <div x-show="activeTab === 'location'" x-data="partnerBranchManager()" class="p-5 sm:p-6 space-y-6 border-t border-slate-200 bg-slate-50/40" style="display: none;">
                <div class="pb-3 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Daftar Cabang Operasional Mitra</h2>
                        <p class="text-[11px] text-slate-500">Perluas jangkauan layanan {{ $partner->business_name }} ke berbagai kota/wilayah kos dengan menambahkan cabang baru.</p>
                    </div>
                    <button
                        type="button"
                        @click="openCreateModal()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer shrink-0 w-fit"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Cabang Baru</span>
                    </button>
                </div>

                {{-- Daftar Cabang --}}
                @if($partner->branches->isEmpty())
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-8 text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-800">Belum Ada Cabang Terdaftar</h3>
                            <p class="text-[11px] text-slate-500 max-w-md mx-auto mt-1">
                                Saat ini hanya hub utama Anda yang aktif. Tambahkan cabang baru jika Anda melayani area atau kota lain agar penghuni di wilayah tersebut dapat memesan layanan Anda.
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="openCreateModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer"
                        >
                            <span>+ Tambah Cabang Pertama</span>
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($partner->branches as $branch)
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-2xs hover:shadow-sm transition flex flex-col justify-between space-y-3">
                                <div class="space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="text-sm font-extrabold text-slate-900">{{ $branch->branch_name }}</h3>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $branch->is_open ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                    {{ $branch->is_open ? 'Buka' : 'Tutup' }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] font-semibold text-blue-600 mt-0.5">
                                                {{ $branch->regency?->name ?? 'Kota/Kabupaten' }}{{ $branch->province ? ', '.$branch->province->name : '' }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold shrink-0">
                                            Radius {{ number_format((float) $branch->service_radius_km, 1) }} km
                                        </span>
                                    </div>

                                    <div class="text-[11px] text-slate-600 space-y-1 bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                        <p class="line-clamp-2"><strong class="text-slate-700">Alamat:</strong> {{ $branch->address }}</p>
                                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-slate-500">
                                            <span>Jam: <strong class="text-slate-700">{{ $branch->operational_hours ?: ($partner->operational_hours ?: '08:00 - 20:00 WIB') }}</strong></span>
                                            <span>Telp: <strong class="text-slate-700">{{ $branch->phone ?: $partner->phone }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                    <form method="POST" action="{{ route('partner.branches.toggle-status', $branch) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition cursor-pointer {{ $branch->is_open ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}"
                                        >
                                            {{ $branch->is_open ? 'Tutup Cabang' : 'Buka Cabang' }}
                                        </button>
                                    </form>

                                    <div class="flex items-center gap-1.5">
                                        <button
                                            type="button"
                                            @click='openEditModal(@json($branch))'
                                            class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition cursor-pointer"
                                        >
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('partner.branches.destroy', $branch) }}" onsubmit="return confirm('Yakin ingin menghapus cabang {{ addslashes($branch->branch_name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold transition cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Modal Tambah / Edit Cabang --}}
                <div
                    x-show="modalOpen"
                    x-transition.opacity
                    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4"
                    style="display: none;"
                >
                    <div
                        @click.outside="modalOpen = false"
                        class="bg-white rounded-2xl max-w-2xl w-full p-5 sm:p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto"
                    >
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm sm:text-base font-extrabold text-slate-900" x-text="isEditing ? 'Edit Cabang Mitra' : 'Tambah Cabang Baru'"></h3>
                                <p class="text-[11px] text-slate-500">Isi lokasi dan detail operasional cabang agar tampil di katalog wilayah terkait.</p>
                            </div>
                            <button type="button" @click="modalOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form method="POST" :action="formAction" class="space-y-4">
                            @csrf
                            <template x-if="isEditing">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Cabang *</label>
                                    <input
                                        type="text"
                                        name="branch_name"
                                        x-model="form.branch_name"
                                        required
                                        placeholder="Contoh: Cabang Dago Bandung"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Provinsi *</label>
                                    <select
                                        name="province_id"
                                        x-model="form.province_id"
                                        @change="onProvChange()"
                                        required
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="block text-xs font-bold text-slate-700">Kota / Kabupaten *</label>
                                        <span x-show="isLoadingRegencies" class="text-[10px] text-blue-600 font-semibold" style="display: none;">Memuat...</span>
                                    </div>
                                    <select
                                        name="regency_id"
                                        x-model="form.regency_id"
                                        @change="onRegencyChange()"
                                        :disabled="!form.province_id || isLoadingRegencies"
                                        required
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white disabled:bg-slate-50 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                        <option value="">-- Pilih Kota/Kabupaten --</option>
                                        <template x-for="reg in regencies" :key="reg.id">
                                            <option :value="reg.id" x-text="reg.name" :selected="String(reg.id) === String(form.regency_id)"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <label class="block text-xs font-bold text-slate-700">Alamat Lengkap Cabang *</label>
                                        <button
                                            type="button"
                                            @click="geocodeBranchAddress()"
                                            :disabled="isGeocodingBranch"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold transition cursor-pointer disabled:opacity-50"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            <span x-text="isGeocodingBranch ? 'Mencari titik...' : 'Cari Titik di Peta'"></span>
                                        </button>
                                    </div>
                                    <input
                                        type="text"
                                        name="address"
                                        x-model="form.address"
                                        required
                                        placeholder="Jl. Ir. H. Juanda No. 88, Coblong, Bandung"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                    <p x-show="geocodeBranchMessage" x-text="geocodeBranchMessage" class="text-[10px] text-amber-600 font-medium mt-1" style="display: none;"></p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">No. Telepon / WhatsApp Cabang</label>
                                    <input
                                        type="text"
                                        name="phone"
                                        x-model="form.phone"
                                        placeholder="{{ $partner->phone ?? '081234567890' }}"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Operasional</label>
                                    <input
                                        type="text"
                                        name="operational_hours"
                                        x-model="form.operational_hours"
                                        placeholder="08:00 - 20:00 WIB"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Radius Layanan (KM) *</label>
                                    <input
                                        type="number"
                                        step="0.5"
                                        min="0.5"
                                        max="25"
                                        name="service_radius_km"
                                        x-model="form.service_radius_km"
                                        @input="onBranchRadiusInput()"
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                    >
                                    <p class="text-[10px] text-slate-400 mt-1">Jarak radius jangkauan layanan kurir dari titik lokasi cabang ini.</p>
                                </div>

                                {{-- Hidden Coordinates untuk Cabang --}}
                                <input type="hidden" name="latitude" x-model="form.latitude">
                                <input type="hidden" name="longitude" x-model="form.longitude">

                                {{-- Leaflet Interactive Map Picker untuk Cabang --}}
                                <div class="sm:col-span-2 space-y-2 pt-1">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-700">Area Jangkauan & Radius Layanan Cabang</span>
                                        <span class="text-[11px] text-blue-600 font-bold" x-text="(form.service_radius_km || 5) + ' KM Radius'"></span>
                                    </div>

                                    {{-- Leaflet Map Canvas --}}
                                    <div id="branchMapPicker" class="h-60 sm:h-64 w-full rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-0"></div>

                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span>Klik peta, geser pin manual, cari dari alamat, atau gunakan GPS untuk menentukan titik pusat radius.</span>
                                        <button 
                                            type="button" 
                                            @click="detectBranchGps()"
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-semibold cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span x-text="isDetectingGps ? 'Mendeteksi...' : 'Gunakan Lokasi GPS'"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                        <input type="checkbox" name="is_open" value="1" x-model="form.is_open" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span>Cabang langsung dibuka dan siap menerima pesanan</span>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                                <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition cursor-pointer">
                                    <span x-text="isEditing ? 'Simpan Perubahan Cabang' : 'Simpan Cabang Baru'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function partnerCardPreviewManager() {
                return {
                    cardPreview: @js($partnerCardImg),
                    businessName: @js($partner->business_name)
                };
            }

            function partnerPackageManager() {
                return {
                    modalOpen: false,
                    isEditing: false,
                    formAction: @js(route('partner.packages.store')),
                    form: {
                        name: '',
                        price: 10000,
                        unit: '/kg',
                        description: '',
                        is_active: true,
                    },

                    openCreateModal() {
                        this.isEditing = false;
                        this.formAction = @js(route('partner.packages.store'));
                        this.form = {
                            name: '',
                            price: 10000,
                            unit: @js($partner->category === 'cleaning' ? '/sesi' : ($partner->category === 'catering' ? '/hari' : '/kg')),
                            description: '',
                            is_active: true,
                        };
                        this.modalOpen = true;
                    },

                    openEditModal(pkg) {
                        this.isEditing = true;
                        this.formAction = `/mitra/paket/${pkg.id}`;
                        this.form = {
                            name: pkg.name || '',
                            price: pkg.price || 10000,
                            unit: pkg.unit || '/paket',
                            description: pkg.description || '',
                            is_active: Boolean(pkg.is_active),
                        };
                        this.modalOpen = true;
                    }
                };
            }

            function partnerBranchManager() {
                return {
                    modalOpen: false,
                    isEditing: false,
                    formAction: @js(route('partner.branches.store')),
                    regencies: [],
                    isLoadingRegencies: false,
                    isDetectingGps: false,
                    isGeocodingBranch: false,
                    geocodeBranchMessage: '',
                    branchMap: null,
                    branchMarker: null,
                    branchCircle: null,
                    form: {
                        branch_name: '',
                        province_id: '',
                        regency_id: '',
                        address: '',
                        phone: '',
                        operational_hours: '08:00 - 20:00 WIB',
                        service_radius_km: 5,
                        latitude: '',
                        longitude: '',
                        is_open: true,
                    },

                    openCreateModal() {
                        this.isEditing = false;
                        this.formAction = @js(route('partner.branches.store'));
                        this.regencies = [];

                        const defaultLat = parseFloat(@js($partner->latitude ?? -6.2088)) || -6.2088;
                        const defaultLng = parseFloat(@js($partner->longitude ?? 106.8456)) || 106.8456;
                        const defaultRadius = parseFloat(@js($partner->service_radius_km ?? 5)) || 5;

                        this.form = {
                            branch_name: '',
                            province_id: '',
                            regency_id: '',
                            address: '',
                            phone: '',
                            operational_hours: @js($partner->operational_hours ?? '08:00 - 20:00 WIB'),
                            service_radius_km: defaultRadius,
                            latitude: defaultLat.toFixed(7),
                            longitude: defaultLng.toFixed(7),
                            is_open: true,
                        };
                        this.modalOpen = true;

                        setTimeout(() => {
                            this.initBranchMap(defaultLat, defaultLng, defaultRadius);
                        }, 200);
                    },

                    async openEditModal(branch) {
                        this.isEditing = true;
                        this.formAction = `/mitra/cabang/${branch.id}`;

                        const defaultLat = parseFloat(branch.latitude || @js($partner->latitude ?? -6.2088)) || -6.2088;
                        const defaultLng = parseFloat(branch.longitude || @js($partner->longitude ?? 106.8456)) || 106.8456;
                        const defaultRadius = parseFloat(branch.service_radius_km || @js($partner->service_radius_km ?? 5)) || 5;

                        this.form = {
                            branch_name: branch.branch_name || '',
                            province_id: String(branch.province_id || ''),
                            regency_id: String(branch.regency_id || ''),
                            address: branch.address || '',
                            phone: branch.phone || '',
                            operational_hours: branch.operational_hours || '08:00 - 20:00 WIB',
                            service_radius_km: defaultRadius,
                            latitude: defaultLat.toFixed(7),
                            longitude: defaultLng.toFixed(7),
                            is_open: Boolean(branch.is_open),
                        };
                        this.modalOpen = true;

                        setTimeout(() => {
                            this.initBranchMap(defaultLat, defaultLng, defaultRadius);
                        }, 200);

                        if (this.form.province_id) {
                            await this.loadRegencies(this.form.province_id);
                        }
                    },

                    initBranchMap(lat, lng, radiusKm) {
                        const container = document.getElementById('branchMapPicker');
                        if (!container) return;

                        if (this.branchMap) {
                            this.branchMap.invalidateSize();
                            this.setBranchMapLocation(lat, lng, 14);
                            this.updateBranchCircle(lat, lng, radiusKm);
                            return;
                        }

                        this.branchMap = L.map('branchMapPicker').setView([lat, lng], 14);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(this.branchMap);

                        // Pin marker dapat digeser dan peta dapat diklik untuk penyesuaian titik lokasi cabang secara akurat
                        this.branchMarker = L.marker([lat, lng], { draggable: true }).addTo(this.branchMap);

                        this.branchCircle = L.circle([lat, lng], {
                            color: '#2563eb',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.15,
                            radius: (parseFloat(radiusKm) || 5) * 1000
                        }).addTo(this.branchMap);

                        this.branchMarker.on('dragend', () => {
                            const pos = this.branchMarker.getLatLng();
                            this.syncCoords(pos.lat, pos.lng);
                        });

                        this.branchMap.on('click', (e) => {
                            this.branchMarker.setLatLng(e.latlng);
                            this.syncCoords(e.latlng.lat, e.latlng.lng);
                        });
                    },

                    setBranchMapLocation(lat, lng, zoomLevel) {
                        if (!this.branchMap) return;
                        if (zoomLevel) {
                            this.branchMap.setView([lat, lng], zoomLevel);
                        } else {
                            this.branchMap.panTo([lat, lng]);
                        }
                        if (this.branchMarker) {
                            this.branchMarker.setLatLng([lat, lng]);
                        }
                        if (this.branchCircle) {
                            this.branchCircle.setLatLng([lat, lng]);
                        }
                    },

                    updateBranchCircle(lat, lng, radiusKm) {
                        if (!this.branchCircle) return;
                        const r = (parseFloat(radiusKm) || 5) * 1000;
                        this.branchCircle.setLatLng([lat, lng]);
                        this.branchCircle.setRadius(r);
                    },

                    syncCoords(lat, lng) {
                        this.form.latitude = Number(lat).toFixed(7);
                        this.form.longitude = Number(lng).toFixed(7);
                        if (this.branchCircle) {
                            this.branchCircle.setLatLng([lat, lng]);
                        }
                    },

                    onBranchRadiusInput() {
                        const r = parseFloat(this.form.service_radius_km) || 1;
                        if (this.branchCircle) {
                            this.branchCircle.setRadius(r * 1000);
                        }
                    },

                    onBranchCoordsInput() {
                        const lat = parseFloat(this.form.latitude);
                        const lng = parseFloat(this.form.longitude);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            this.setBranchMapLocation(lat, lng);
                        }
                    },

                    async onProvChange() {
                        this.form.regency_id = '';
                        this.regencies = [];
                        if (!this.form.province_id) return;
                        await this.loadRegencies(this.form.province_id);
                    },

                    async loadRegencies(provId) {
                        this.isLoadingRegencies = true;
                        try {
                            const res = await fetch(`/api/wilayah/provinces/${provId}/regencies`);
                            this.regencies = await res.json();
                        } catch (err) {
                            console.error('Gagal mengambil data kab/kota cabang:', err);
                        } finally {
                            this.isLoadingRegencies = false;
                        }
                    },

                    onRegencyChange() {
                        const selected = this.regencies.find(r => String(r.id) === String(this.form.regency_id));
                        if (selected && selected.latitude && selected.longitude) {
                            const lat = parseFloat(selected.latitude);
                            const lng = parseFloat(selected.longitude);
                            this.form.latitude = lat.toFixed(7);
                            this.form.longitude = lng.toFixed(7);
                            this.setBranchMapLocation(lat, lng, 13);
                        }
                    },

                    detectBranchGps() {
                        if (!navigator.geolocation) {
                            alert('Geolocation tidak didukung oleh browser Anda.');
                            return;
                        }
                        this.isDetectingGps = true;
                        navigator.geolocation.getCurrentPosition((pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            this.syncCoords(lat, lng);
                            this.setBranchMapLocation(lat, lng, 15);
                            this.isDetectingGps = false;
                        }, (err) => {
                            alert('Gagal mendeteksi lokasi GPS: ' + err.message);
                            this.isDetectingGps = false;
                        }, {
                            enableHighAccuracy: true,
                            timeout: 8000
                        });
                    },

                    async geocodeBranchAddress() {
                        const addr = (this.form.address || '').trim();
                        if (!addr) {
                            this.geocodeBranchMessage = 'Isi alamat lengkap cabang terlebih dahulu sebelum mencari titik di peta.';
                            return;
                        }

                        const selectedReg = this.regencies.find(r => String(r.id) === String(this.form.regency_id));
                        const query = [addr, selectedReg ? selectedReg.name : '', 'Indonesia'].filter(Boolean).join(', ');

                        this.isGeocodingBranch = true;
                        this.geocodeBranchMessage = '';
                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=${encodeURIComponent(query)}`);
                            const results = await res.json();
                            if (Array.isArray(results) && results.length > 0) {
                                const lat = parseFloat(results[0].lat);
                                const lng = parseFloat(results[0].lon);
                                this.syncCoords(lat, lng);
                                this.setBranchMapLocation(lat, lng, 16);
                            } else {
                                this.geocodeBranchMessage = 'Alamat tidak ditemukan otomatis. Silakan klik peta atau geser pin secara manual.';
                            }
                        } catch (err) {
                            this.geocodeBranchMessage = 'Gagal mencari alamat otomatis. Silakan tentukan titik langsung pada peta.';
                        } finally {
                            this.isGeocodingBranch = false;
                        }
                    }
                };
            }

            function partnerLocationManager() {
                return {
                    provId: @js((string) old('province_id', $partner->province_id ?? '')),
                    regId: @js((string) old('regency_id', $partner->regency_id ?? '')),
                    regencies: @js($regencies),
                    isLoadingRegencies: false,
                    hubAddress: @js(old('address', $partner->address ?? '')),
                    isGeocoding: false,
                    geocodeMessage: '',
                    radiusKm: Number(@js(old('service_radius_km', $partner->service_radius_km ?? 5))),

                    async onProvChange(e) {
                        this.regId = '';
                        this.regencies = [];
                        if (!this.provId) return;

                        if (e && e.target) {
                            const opt = e.target.selectedOptions[0];
                            if (opt && opt.dataset.lat && opt.dataset.lng && window.setPartnerMapLocation) {
                                window.setPartnerMapLocation(parseFloat(opt.dataset.lat), parseFloat(opt.dataset.lng), 10);
                            }
                        }

                        this.isLoadingRegencies = true;
                        try {
                            const res = await fetch(`/api/wilayah/provinces/${this.provId}/regencies`);
                            this.regencies = await res.json();
                        } catch (err) {
                            console.error('Gagal mengambil data kab/kota:', err);
                        } finally {
                            this.isLoadingRegencies = false;
                        }
                    },

                    onRegencyChange() {
                        const selected = this.regencies.find(r => String(r.id) === String(this.regId));
                        if (selected && selected.latitude && selected.longitude && window.setPartnerMapLocation) {
                            window.setPartnerMapLocation(parseFloat(selected.latitude), parseFloat(selected.longitude), 13);
                        }
                    },

                    adjustRadius(delta) {
                        let current = parseFloat(this.radiusKm) || 5;
                        let updated = Math.min(25, Math.max(0.5, Math.round((current + delta) * 2) / 2));
                        this.radiusKm = updated;
                        if (window.updatePartnerMapRadius) {
                            window.updatePartnerMapRadius(updated);
                        }
                    },

                    onRadiusInput() {
                        let val = parseFloat(this.radiusKm) || 1;
                        if (window.updatePartnerMapRadius) {
                            window.updatePartnerMapRadius(val);
                        }
                    },

                    async geocodeHubAddress() {
                        const addr = (this.hubAddress || '').trim();
                        if (!addr) {
                            this.geocodeMessage = 'Isi alamat lengkap hub terlebih dahulu sebelum mencari titik di peta.';
                            return;
                        }

                        const selectedReg = this.regencies.find(r => String(r.id) === String(this.regId));
                        const query = [addr, selectedReg ? selectedReg.name : '', 'Indonesia'].filter(Boolean).join(', ');

                        this.isGeocoding = true;
                        this.geocodeMessage = '';
                        try {
                            const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=${encodeURIComponent(query)}`);
                            const results = await res.json();
                            if (Array.isArray(results) && results.length > 0 && window.setPartnerMapLocation) {
                                window.setPartnerMapLocation(parseFloat(results[0].lat), parseFloat(results[0].lon), 16);
                            } else {
                                this.geocodeMessage = 'Alamat tidak ditemukan otomatis. Silakan klik peta atau geser pin secara manual.';
                            }
                        } catch (err) {
                            this.geocodeMessage = 'Gagal mencari alamat otomatis. Silakan tentukan titik langsung pada peta.';
                        } finally {
                            this.isGeocoding = false;
                        }
                    }
                };
            }
        </script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const initialLat = parseFloat("{{ old('latitude', $partner->latitude ?? -6.2088) }}") || -6.2088;
                const initialLng = parseFloat("{{ old('longitude', $partner->longitude ?? 106.8456) }}") || 106.8456;
                const initialRadius = parseFloat("{{ old('service_radius_km', $partner->service_radius_km ?? 5) }}") || 5;

                const mapContainer = document.getElementById('partnerMapPicker');
                if (!mapContainer) return;

                const map = L.map('partnerMapPicker').setView([initialLat, initialLng], 14);
                window.partnerMap = map;

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
                    const latEl = document.getElementById('latInput');
                    const lngEl = document.getElementById('lngInput');
                    const coordDisplay = document.getElementById('coordDisplay');

                    if (latEl) latEl.value = lat.toFixed(7);
                    if (lngEl) lngEl.value = lng.toFixed(7);
                    if (coordDisplay) coordDisplay.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
                    circle.setLatLng([lat, lng]);
                }

                // Global helper for Alpine to update radius & location
                window.updatePartnerMapRadius = function (km) {
                    const r = parseFloat(km) || 1;
                    circle.setRadius(r * 1000);
                };

                window.setPartnerMapLocation = function (lat, lng, zoomLevel = 14) {
                    map.setView([lat, lng], zoomLevel);
                    marker.setLatLng([lat, lng]);
                    updateCoords(lat, lng);
                };

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
                            window.setPartnerMapLocation(lat, lng, 15);
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
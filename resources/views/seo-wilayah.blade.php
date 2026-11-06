<x-seven-layout 
    :title="'Sewa Kos di ' . $regency->name . ', ' . $province->name . ' Murah & Nyaman - Seven Kost'"
    :description="'Cari info sewa kamar kos di ' . $regency->name . ', ' . $province->name . ' mulai Rp ' . number_format($minPrice, 0, ',', '.') . ' per bulan. Foto asli, fasilitas lengkap, peta lokasi akurat tanpa perantara.'"
    :robots="$robots"
    :canonical="url('/kos/' . $provinsiSlug . '/' . $kotaSlug)"
>

    @push('head')
        <script type="application/ld+json">
            {!! json_encode($breadcrumbsSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush

    {{-- HERO REGIONAL HEADER --}}
    <div class="bg-navy-950 text-white py-12 border-b border-slate-800 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center text-xs text-slate-400 gap-2 mb-4">
                <a href="{{ url('/') }}" class="hover:text-white transition">Beranda</a>
                <span>/</span>
                <a href="{{ url('/cari') }}" class="hover:text-white transition">Cari Kos</a>
                <span>/</span>
                <span class="text-slate-400">{{ $province->name }}</span>
                <span>/</span>
                <span class="text-amber-400 font-semibold">{{ $regency->name }}</span>
            </nav>

            <div class="max-w-3xl space-y-3">
                <span class="inline-block px-3 py-0.5 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    📍 Wilayah: {{ $regency->name }}, {{ $province->name }}
                </span>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-white leading-tight">
                    Sewa Kos di {{ $regency->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Ditemukan <strong class="text-white">{{ $totalKos }}</strong> pilihan hunian kos dengan tarif mulai dari <strong class="text-emerald-400">Rp {{ number_format($minPrice, 0, ',', '.') }}</strong>/bulan. Pesan langsung atau kontak pemilik tanpa biaya perantara.
                </p>
            </div>

            {{-- Action Shortcuts --}}
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ url('/cari?kota=' . $regency->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Buka Filter Lengkap
                </a>
                <a href="{{ url('/peta?kota=' . $regency->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition border border-slate-700">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Lihat Peta {{ $regency->name }}
                </a>
            </div>
        </div>
    </div>

    {{-- LISTING KOS SECTION --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($totalKos > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($kosts as $kost)
                    <x-kost-card 
                        :id="$kost->id"
                        :title="$kost->name"
                        :slug="$kost->slug"
                        :city="$kost->regency->name ?? $regency->name"
                        :province="$kost->province->name ?? $province->name"
                        :price="$kost->price_start"
                        :rentType="$kost->rent_type"
                        :isVerified="$kost->verification_status === 'verified'"
                        :isFeatured="(bool) $kost->is_featured"
                        :availableRooms="$kost->rooms->sum('available_rooms')"
                        :facilities="$kost->facilities->pluck('name')->toArray()"
                        :image="$kost->primaryImage ? asset('storage/' . $kost->primaryImage->file_path) : null"
                    />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $kosts->links() }}
            </div>
        @else
            {{-- EMPTY STATE REGIONAL --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-2xl mx-auto shadow-xs space-y-4">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Belum Ada Kos Terdaftar di {{ $regency->name }}</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Saat ini listing kos di wilayah {{ $regency->name }} sedang dalam proses verifikasi tim kami atau belum didaftarkan oleh pemilik setempat.
                </p>
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ url('/iklan/buat') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
                        Pasang Iklan Kos di Wilayah Ini
                    </a>
                    <a href="{{ url('/cari') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Jelajah Kota Lain
                    </a>
                </div>
            </div>
        @endif

        {{-- FAQ & REGIONAL GUIDE ACCORDION (SEO TEXT) --}}
        <div class="mt-16 bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-xs">
            <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan & Pertanyaan Umum Seputar Kos di {{ $regency->name }}
            </h2>

            <div class="space-y-4 text-xs" x-data="{ openFaq: null }">
                <div class="border border-slate-200/80 rounded-2xl overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 1 ? null : 1" 
                        type="button" 
                        class="w-full text-left p-4 bg-slate-50 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-100 transition"
                    >
                        <span>Berapa kisaran harga sewa kos di {{ $regency->name }}?</span>
                        <svg class="w-4 h-4 transition-transform text-slate-400" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="p-4 bg-white text-slate-600 leading-relaxed border-t border-slate-100">
                        Tarif kos di {{ $regency->name }} berkisar mulai dari <strong>Rp {{ number_format($minPrice, 0, ',', '.') }}</strong> hingga <strong>Rp {{ number_format($maxPrice, 0, ',', '.') }}</strong> per bulan. Harga bergantung pada kelengkapan kamar (kasur, lemari, meja kerja), fasilitas AC, kamar mandi dalam, akses parkir kendaraan, dan akses ke jalan protokol.
                    </div>
                </div>

                <div class="border border-slate-200/80 rounded-2xl overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 2 ? null : 2" 
                        type="button" 
                        class="w-full text-left p-4 bg-slate-50 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-100 transition"
                    >
                        <span>Bagaimana cara menghubungi pemilik kos di {{ $regency->name }}?</span>
                        <svg class="w-4 h-4 transition-transform text-slate-400" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="p-4 bg-white text-slate-600 leading-relaxed border-t border-slate-100">
                        Di Seven Kost, Anda dapat langsung mengklik tombol 'Chat WhatsApp Pemilik' pada halaman rincian kos untuk langsung terhubung dengan pemilik tanpa potongan biaya dan tanpa pihak perantara.
                    </div>
                </div>

                <div class="border border-slate-200/80 rounded-2xl overflow-hidden">
                    <button 
                        @click="openFaq = openFaq === 3 ? null : 3" 
                        type="button" 
                        class="w-full text-left p-4 bg-slate-50 font-bold text-slate-800 flex items-center justify-between hover:bg-slate-100 transition"
                    >
                        <span>Apakah bisa booking dan survei sebelum bayar sewa?</span>
                        <svg class="w-4 h-4 transition-transform text-slate-400" :class="openFaq === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="p-4 bg-white text-slate-600 leading-relaxed border-t border-slate-100">
                        Sangat bisa! Anda dapat menggunakan fitur 'Ajukan Sewa / Booking' untuk mencatat jadwal rencana sewa Anda, lalu membuat janji survei langsung dengan pemilik kos di {{ $regency->name }}.
                    </div>
                </div>
            </div>
        </div>

        {{-- SIBLING REGION INTERNAL LINKING (SEO SILO) --}}
        @if($siblingRegencies->count() > 0)
            <div class="mt-12">
                <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">
                    Jelajah Wilayah Lain di Provinsi {{ $province->name }}
                </h3>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($siblingRegencies as $sReg)
                        <a 
                            href="{{ url('/kos/' . $provinsiSlug . '/' . \Illuminate\Support\Str::slug($sReg->name)) }}" 
                            class="px-3.5 py-1.5 rounded-full bg-white border border-slate-200/80 text-xs font-semibold text-slate-700 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition"
                        >
                            Kos di {{ $sReg->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</x-seven-layout>

<x-seven-layout :title="$kost->name . ' - Seven Kost'" :hide-bottom-nav="true">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .leaflet-container {
                z-index: 10 !important;
                border-radius: 1.25rem;
                font-family: inherit;
            }
            .custom-kost-marker {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: #ffffff;
                border-radius: 9999px;
                padding: 6px 12px;
                font-weight: 700;
                font-size: 11px;
                box-shadow: 0 4px 14px rgba(37,99,235,0.4);
                display: flex;
                align-items: center;
                gap: 5px;
                white-space: nowrap;
                border: 2px solid #ffffff;
            }
        </style>
    @endpush

    @php
        // Koleksi Foto Galeri
        $uploadedImages = $kost->images->map(fn($img) => asset('storage/' . $img->file_path))->values()->toArray();
        if ($kost->primaryImage && !in_array(asset('storage/' . $kost->primaryImage->file_path), $uploadedImages)) {
            array_unshift($uploadedImages, asset('storage/' . $kost->primaryImage->file_path));
        }

        // Foto Cadangan HD jika foto kos masih sedikit / baru
        $fallbackPhotos = [
            asset('images/frontend/kost-1-hd.jpg'),
            asset('images/frontend/kost-2-hd.jpg'),
            asset('images/frontend/kost-3-hd.jpg'),
            asset('images/frontend/kost-4-hd.jpg'),
            asset('images/frontend/cta-kost-room.jpg'),
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
        ];

        $galleryPhotos = !empty($uploadedImages) ? $uploadedImages : array_slice($fallbackPhotos, 0, 5);
        while (count($galleryPhotos) < 5) {
            $galleryPhotos[] = $fallbackPhotos[count($galleryPhotos) % count($fallbackPhotos)];
        }

        // Kamar Default Terpilih
        $firstRoom = $kost->rooms->first();
        $defaultRoomId = $firstRoom ? $firstRoom->id : 0;
        $defaultRoomName = $firstRoom ? $firstRoom->name : 'Tipe Kamar Standar';
        $defaultRoomPrice = $firstRoom ? (float)$firstRoom->price : (float)$kost->price_start;
        $defaultRoomSize = $firstRoom ? ($firstRoom->room_size ?: '3x4 m') : '3x4 m';
        $defaultRoomAvailable = $firstRoom ? $firstRoom->available_rooms : 1;

        // Nomor WhatsApp Pemilik Kos
        $ownerPhone = $kost->owner->phone ?? '6281234567890';
        $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
    @endphp

    {{-- Root Container dengan Alpine State --}}
    <div 
        x-data="{ 
            activeImageModal: null,
            activeImageIndex: 0,
            allPhotos: {{ json_encode($galleryPhotos) }},
            isBookmarked: false,
            shareCopied: false,
            bookingModalOpen: false, 
            reportModalOpen: false,
            selectedRoomId: {{ $defaultRoomId }},
            selectedRoomName: '{{ addslashes($defaultRoomName) }}',
            selectedRoomPrice: {{ $defaultRoomPrice }},
            selectedRoomSize: '{{ addslashes($defaultRoomSize) }}',
            selectedRoomAvailable: {{ $defaultRoomAvailable }},
            durationMonths: 1,

            openLightbox(index) {
                this.activeImageIndex = index;
                this.activeImageModal = this.allPhotos[index];
            },
            nextPhoto() {
                this.activeImageIndex = (this.activeImageIndex + 1) % this.allPhotos.length;
                this.activeImageModal = this.allPhotos[this.activeImageIndex];
            },
            prevPhoto() {
                this.activeImageIndex = (this.activeImageIndex - 1 + this.allPhotos.length) % this.allPhotos.length;
                this.activeImageModal = this.allPhotos[this.activeImageIndex];
            },
            selectRoom(id, name, price, size, available) {
                this.selectedRoomId = id;
                this.selectedRoomName = name;
                this.selectedRoomPrice = price;
                this.selectedRoomSize = size;
                this.selectedRoomAvailable = available;
            },
            copyShareLink() {
                navigator.clipboard.writeText(window.location.href);
                this.shareCopied = true;
                setTimeout(() => this.shareCopied = false, 2500);
            },
            getDynamicWaUrl() {
                const text = encodeURIComponent('Halo kak, saya tertarik dengan kos ' + '{{ addslashes($kost->name) }}' + ' untuk tipe kamar ' + this.selectedRoomName + ' (Rp ' + Number(this.selectedRoomPrice).toLocaleString('id-ID') + '/bulan) durasi ' + this.durationMonths + ' bulan. Apakah kamar masih tersedia?');
                return 'https://wa.me/{{ $cleanPhone }}?text=' + text;
            }
        }"
        @keydown.escape.window="activeImageModal = null; bookingModalOpen = false; reportModalOpen = false"
        @keydown.arrow-right.window="if(activeImageModal) nextPhoto()"
        @keydown.arrow-left.window="if(activeImageModal) prevPhoto()"
        class="pb-24 lg:pb-12"
    >

        {{-- Breadcrumb & Quick Actions Bar --}}
        <div class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-30 transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
                {{-- Breadcrumbs --}}
                <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap scrollbar-none">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Beranda</span>
                    </a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ url('/cari?provinsi=' . $kost->province_id) }}" class="hover:text-blue-600 transition font-medium">
                        {{ $kost->province->name ?? 'Provinsi' }}
                    </a>
                    <span class="text-slate-300">/</span>
                    <a href="{{ url('/cari?kota=' . $kost->regency_id) }}" class="hover:text-blue-600 transition font-medium">
                        {{ $kost->regency->name ?? 'Kota' }}
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-slate-900 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $kost->name }}</span>
                </nav>

                {{-- Action Tools: Share & Save --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Share Button --}}
                    <button 
                        @click="copyShareLink()" 
                        type="button" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition active:scale-95 shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span x-text="shareCopied ? 'Tersalin!' : 'Bagikan'">Bagikan</span>
                    </button>

                    {{-- Bookmark Button --}}
                    <button 
                        @click="isBookmarked = !isBookmarked" 
                        type="button" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold transition active:scale-95 shadow-xs"
                        :class="isBookmarked ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300'"
                    >
                        <svg class="w-3.5 h-3.5 transition-transform" :class="isBookmarked ? 'fill-blue-600 stroke-blue-600 scale-110' : 'fill-none stroke-current'" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        <span x-text="isBookmarked ? 'Tersimpan' : 'Simpan'">Simpan</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            {{-- Flash Notification Alerts --}}
            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-emerald-800 flex items-center justify-between gap-3 text-xs sm:text-sm font-semibold shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-rose-800 flex items-center justify-between gap-3 text-xs sm:text-sm font-semibold shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- 1. PHOTO GALLERY (LUXURY 5-SLOT GRID ALA AIRBNB) --}}
            <section class="mb-8">
                <div class="relative grid grid-cols-1 md:grid-cols-4 gap-3 rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 bg-slate-900 aspect-[16/11] sm:aspect-[16/8] md:aspect-[21/9]">
                    
                    {{-- Main Big Photo (Left 2 Col, 2 Row) --}}
                    <div 
                        @click="openLightbox(0)" 
                        class="md:col-span-2 relative h-full overflow-hidden group cursor-pointer"
                    >
                        <img 
                            src="{{ $galleryPhotos[0] }}" 
                            alt="{{ $kost->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex flex-col justify-between p-5">
                            {{-- Top Badges Overlay --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                <x-seven-badge :type="$kost->type" class="uppercase text-xs px-3 py-1 bg-white/95 backdrop-blur-md shadow-md font-bold text-slate-800">
                                    Kos {{ $kost->type }}
                                </x-seven-badge>

                                @if($kost->verification_status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-600 text-white shadow-md">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Terverifikasi
                                    </span>
                                @endif

                                @if($kost->is_featured)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500 text-slate-900 shadow-md">
                                        ⭐ Unggulan
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-white/90 text-xs font-medium">
                                <span class="bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-lg">
                                    📷 Foto Utama
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 2 Middle Photos (Col 3) --}}
                    <div class="hidden md:grid grid-rows-2 gap-3 h-full">
                        <div @click="openLightbox(1)" class="relative h-full overflow-hidden group cursor-pointer rounded-xl">
                            <img src="{{ $galleryPhotos[1] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div @click="openLightbox(2)" class="relative h-full overflow-hidden group cursor-pointer rounded-xl">
                            <img src="{{ $galleryPhotos[2] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                    </div>

                    {{-- 2 Right Photos (Col 4) --}}
                    <div class="hidden md:grid grid-rows-2 gap-3 h-full">
                        <div @click="openLightbox(3)" class="relative h-full overflow-hidden group cursor-pointer rounded-xl">
                            <img src="{{ $galleryPhotos[3] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div @click="openLightbox(4)" class="relative h-full overflow-hidden group cursor-pointer rounded-xl">
                            <img src="{{ $galleryPhotos[4] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-colors flex items-center justify-center p-2 text-center">
                                <button type="button" class="px-3 py-1.5 rounded-xl bg-white/90 hover:bg-white text-slate-900 text-xs font-bold shadow-lg backdrop-blur-xs flex items-center gap-1.5 transition">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Lihat Semua Foto</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile View All Photos Pill --}}
                    <button 
                        @click="openLightbox(0)" 
                        type="button" 
                        class="md:hidden absolute bottom-4 right-4 px-3.5 py-1.5 rounded-xl bg-slate-900/80 hover:bg-slate-900 text-white text-xs font-bold backdrop-blur-md border border-white/20 shadow-lg flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Semua Foto ({{ count($galleryPhotos) }})</span>
                    </button>
                </div>
            </section>

            {{-- 2. MAIN TWO-COLUMN LAYOUT --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- ==================== LEFT COLUMN ==================== --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Header Kos & Alamat --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 flex-wrap text-xs font-semibold">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 uppercase">
                                    Kos {{ $kost->type }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700">
                                    {{ $kost->province->name ?? 'Indonesia' }}
                                </span>
                                @if($kost->rooms->sum('available_rooms') > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Siap Huni
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold">
                                        Kamar Penuh
                                    </span>
                                @endif
                            </div>

                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-snug">
                                {{ $kost->name }}
                            </h1>

                            <div class="flex items-start sm:items-center gap-2 text-xs sm:text-sm text-slate-600 font-medium pt-1">
                                <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $kost->address }}, {{ $kost->regency->name ?? '' }}, {{ $kost->province->name ?? '' }}</span>
                            </div>
                        </div>

                        {{-- Quick Info Badges Bar --}}
                        <div class="pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[11px] font-medium text-slate-400 block">Sisa Kamar</span>
                                <span class="text-sm sm:text-base font-extrabold text-emerald-600">
                                    {{ $kost->rooms->sum('available_rooms') }} Kamar Kosong
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[11px] font-medium text-slate-400 block">Tipe Pembayaran</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-800 capitalize">
                                    Per {{ $kost->rent_type }}
                                </span>
                            </div>
                            <div class="col-span-2 sm:col-span-1 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[11px] font-medium text-slate-400 block">Update Terakhir</span>
                                <span class="text-sm sm:text-base font-semibold text-slate-700">
                                    {{ $kost->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- PILIHAN TIPE KAMAR (INTERACTIVE SELECTION) --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Pilihan Tipe Kamar</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih tipe kamar yang sesuai kebutuhan Anda untuk menghitung tarif sewa.</p>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                {{ $kost->rooms->count() }} Tipe Tersedia
                            </span>
                        </div>

                        <div class="space-y-3.5">
                            @foreach($kost->rooms as $room)
                                @php
                                    $isAvail = $room->available_rooms > 0;
                                    $roomPrice = (float)$room->price;
                                    $roomSize = $room->room_size ?: '3x4 m';
                                @endphp
                                <div 
                                    @click="selectRoom({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $roomPrice }}, '{{ addslashes($roomSize) }}', {{ $room->available_rooms }})"
                                    class="p-4 sm:p-5 rounded-2xl border transition-all cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                                    :class="selectedRoomId === {{ $room->id }} 
                                        ? 'bg-blue-50/50 border-blue-500 ring-2 ring-blue-500/20 shadow-sm' 
                                        : 'bg-white border-slate-200/90 hover:border-slate-300 hover:bg-slate-50/50'"
                                >
                                    {{-- Left: Details & Selection Indicator --}}
                                    <div class="flex items-start gap-3.5">
                                        {{-- Radio/Selection Circle --}}
                                        <div class="mt-1 flex-shrink-0">
                                            <div 
                                                class="w-5 h-5 rounded-full border flex items-center justify-center transition-all"
                                                :class="selectedRoomId === {{ $room->id }} ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 bg-white'"
                                            >
                                                <svg x-show="selectedRoomId === {{ $room->id }}" class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>

                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h3 class="font-extrabold text-sm sm:text-base text-slate-900">{{ $room->name }}</h3>
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                                    {{ $roomSize }}
                                                </span>
                                                <span x-show="selectedRoomId === {{ $room->id }}" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-600 text-white">
                                                    Dipilih
                                                </span>
                                            </div>

                                            <p class="text-xs text-slate-500 flex items-center gap-3 flex-wrap">
                                                <span>Kapasitas 1-2 Orang</span>
                                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                                <span>Total {{ $room->total_rooms }} Unit</span>
                                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                                @if($isAvail)
                                                    <span class="text-emerald-700 font-bold">Sisa {{ $room->available_rooms }} kamar</span>
                                                @else
                                                    <span class="text-rose-600 font-bold">Kamar Penuh</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Right: Price & Quick Button --}}
                                    <div class="flex items-center sm:flex-col sm:items-end justify-between border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                        <div class="text-left sm:text-right">
                                            <span class="text-base sm:text-lg font-black text-blue-600 block">
                                                Rp {{ number_format($room->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">per bulan</span>
                                        </div>
                                        <button 
                                            type="button" 
                                            class="sm:hidden text-xs font-bold px-3 py-1 rounded-lg"
                                            :class="selectedRoomId === {{ $room->id }} ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700'"
                                        >
                                            <span x-text="selectedRoomId === {{ $room->id }} ? 'Terpilih' : 'Pilih'"></span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- FASILITAS PROPERTI (CATEGORIZED WITH THEMATIC ICONS) --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Fasilitas Lengkap Kos</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Kelengkapan fasilitas yang disediakan untuk kenyamanan penghuni.</p>
                        </div>

                        @php
                            $groupedFacs = $kost->facilities->groupBy('category');
                        @endphp

                        @if($groupedFacs->count() > 0)
                            <div class="space-y-6">
                                @foreach($groupedFacs as $cat => $items)
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                @if($cat === 'kamar') Fasilitas Kamar Tidur
                                                @elseif($cat === 'umum') Fasilitas Bersama / Publik
                                                @elseif($cat === 'sekitar') Akses & Lokasi Sekitar
                                                @else Fasilitas {{ ucfirst($cat) }}
                                                @endif
                                                ({{ $items->count() }})
                                            </h3>
                                        </div>

                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                            @foreach($items as $fac)
                                                <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-slate-50/80 hover:bg-white border border-slate-100 hover:border-slate-200 hover:shadow-xs transition text-xs font-medium text-slate-800">
                                                    {{-- Facility Icon --}}
                                                    <div class="h-7 w-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                                        @if(str_contains(strtolower($fac->name), 'wifi'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'ac'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'mandi') || str_contains(strtolower($fac->name), 'bath') || str_contains(strtolower($fac->name), 'shower'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'kasur') || str_contains(strtolower($fac->name), 'bed'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'lemari'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'parkir') || str_contains(strtolower($fac->name), 'motor') || str_contains(strtolower($fac->name), 'mobil'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'cctv') || str_contains(strtolower($fac->name), 'security') || str_contains(strtolower($fac->name), 'keamanan'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'dapur') || str_contains(strtolower($fac->name), 'kulkas') || str_contains(strtolower($fac->name), 'dispenser'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), 'cuci') || str_contains(strtolower($fac->name), 'laundry'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><circle cx="12" cy="13" r="4"/><path d="M12 9v1"/></svg>
                                                        @elseif(str_contains(strtolower($fac->name), '24') || str_contains(strtolower($fac->name), 'jam'))
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </div>
                                                    <span class="truncate">{{ $fac->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-500">
                                Fasilitas standar hunian kos nyaman tersedia. Hubungi pemilik untuk pertanyaan fasilitas tambahan.
                            </div>
                        @endif
                    </div>

                    {{-- DESKRIPSI & TATA TERTIB KOS --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Deskripsi & Catatan Kos</h2>
                            <div class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-3 whitespace-pre-line">
                                {{ $kost->description ?: 'Kos nyaman, bersih, dan strategis di lokasi yang aman dan tenang. Dilengkapi dengan fasilitas lengkap untuk menunjang aktivitas harian Anda.' }}
                            </div>
                        </div>

                        {{-- Peraturan Kos Callout --}}
                        @if($kost->rules)
                            <div class="pt-6 border-t border-slate-100 space-y-2.5">
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    </div>
                                    <span>Peraturan & Tata Tertib Kos</span>
                                </h3>
                                <div class="text-xs text-slate-700 whitespace-pre-line bg-amber-50/60 border border-amber-200/70 p-4 sm:p-5 rounded-2xl leading-relaxed">
                                    {{ $kost->rules }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- PETA LOKASI LEAFLET & AKSES SEKITAR --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Lokasi pada Peta</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Titik koordinat resmi lokasi Seven Kost.</p>
                            </div>
                            <a 
                                href="https://www.google.com/maps/search/?api=1&query={{ $kost->latitude ?? -6.2088 }},{{ $kost->longitude ?? 106.8456 }}" 
                                target="_blank" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition"
                            >
                                <span>Buka Google Maps</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>

                        {{-- Map Element --}}
                        <div id="kostDetailMap" class="h-80 w-full rounded-2xl border border-slate-200 overflow-hidden shadow-inner"></div>

                        {{-- Surrounding Highlight Badges --}}
                        <div class="pt-2 flex items-center gap-2 flex-wrap text-xs text-slate-600">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200/80 font-medium flex items-center gap-1">
                                🚗 Akses Jalan Nyaman
                            </span>
                            <span class="px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200/80 font-medium flex items-center gap-1">
                                🛡️ Lingkungan Aman
                            </span>
                            <span class="px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200/80 font-medium flex items-center gap-1">
                                🌊 Bebas Banjir
                            </span>
                        </div>
                    </div>

                </div>

                {{-- ==================== RIGHT COLUMN (STICKY ASIDE) ==================== --}}
                <aside class="sticky top-20 space-y-6">

                    {{-- Sticky Booking & Action Card --}}
                    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xl shadow-slate-200/50 space-y-5">
                        
                        {{-- Selected Room Info Header --}}
                        <div class="pb-4 border-b border-slate-100 space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-blue-600 uppercase tracking-wide">Tarif Sewa</span>
                                <span class="font-semibold text-slate-500" x-text="selectedRoomSize"></span>
                            </div>
                            <div class="flex items-baseline gap-1.5 pt-1">
                                <span class="text-3xl font-black text-slate-900 tracking-tight" x-text="'Rp ' + Number(selectedRoomPrice).toLocaleString('id-ID')">
                                    Rp {{ number_format($kost->price_start, 0, ',', '.') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-400">/ bulan</span>
                            </div>
                            <p class="text-[11px] text-slate-500 pt-0.5">
                                Tipe Kamar: <strong class="text-slate-800" x-text="selectedRoomName"></strong>
                            </p>
                        </div>

                        {{-- Durasi Sewa Selector --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Durasi Sewa</label>
                            <div class="grid grid-cols-4 gap-1.5">
                                <button 
                                    type="button" 
                                    @click="durationMonths = 1"
                                    class="py-2 px-1 text-center rounded-xl text-xs font-bold border transition-all"
                                    :class="durationMonths === 1 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    1 Bln
                                </button>
                                <button 
                                    type="button" 
                                    @click="durationMonths = 3"
                                    class="py-2 px-1 text-center rounded-xl text-xs font-bold border transition-all"
                                    :class="durationMonths === 3 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    3 Bln
                                </button>
                                <button 
                                    type="button" 
                                    @click="durationMonths = 6"
                                    class="py-2 px-1 text-center rounded-xl text-xs font-bold border transition-all"
                                    :class="durationMonths === 6 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    6 Bln
                                </button>
                                <button 
                                    type="button" 
                                    @click="durationMonths = 12"
                                    class="py-2 px-1 text-center rounded-xl text-xs font-bold border transition-all"
                                    :class="durationMonths === 12 ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'"
                                >
                                    1 Thn
                                </button>
                            </div>
                        </div>

                        {{-- Live Total Cost Calculation Box --}}
                        <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-medium text-slate-500 block">Estimasi Total Biaya</span>
                                <span class="text-base font-black text-blue-700" x-text="'Rp ' + (selectedRoomPrice * durationMonths).toLocaleString('id-ID')"></span>
                            </div>
                            <span class="text-[10px] font-semibold text-blue-600 bg-white px-2 py-0.5 rounded-md border border-blue-200" x-text="durationMonths + ' Bulan'"></span>
                        </div>

                        {{-- Action Buttons (WA & Booking) --}}
                        <div class="space-y-2.5 pt-1">
                            {{-- Dynamic WhatsApp CTA --}}
                            <a 
                                :href="getDynamicWaUrl()" 
                                target="_blank" 
                                class="w-full inline-flex items-center justify-center gap-2 font-bold rounded-xl transition-all duration-200 active:scale-[0.98] text-xs sm:text-sm px-4.5 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white shadow-md shadow-emerald-500/25"
                            >
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.35.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                                <span>Tanya Pemilik via WhatsApp</span>
                            </a>

                            {{-- Booking Modal Trigger --}}
                            @auth
                                <button 
                                    @click="bookingModalOpen = true" 
                                    type="button" 
                                    class="w-full inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] text-xs sm:text-sm px-4.5 py-3 bg-navy-950 hover:bg-slate-900 text-white shadow-md shadow-navy-950/20 border border-slate-800"
                                >
                                    <span>Ajukan Booking Sewa</span>
                                </button>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="w-full inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] text-xs sm:text-sm px-4.5 py-3 bg-navy-950 hover:bg-slate-900 text-white shadow-md shadow-navy-950/20 border border-slate-800"
                                >
                                    <span>Masuk untuk Booking</span>
                                </a>
                            @endauth
                        </div>

                        {{-- Card Jaminan & Keamanan --}}
                        <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>100% Tanpa Biaya Perantara / Calo</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Kontak Resmi & Terverifikasi Seven Kost</span>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex justify-center">
                                <button @click="reportModalOpen = true" type="button" class="text-[11px] text-red-500 hover:text-red-700 hover:underline flex items-center gap-1 font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Laporkan Pelanggaran Iklan Ini
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mitra Pemilik Kos Profile Snippet --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-3.5">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-extrabold flex items-center justify-center text-base shadow-md flex-shrink-0">
                            {{ strtoupper(substr($kost->owner->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider block">Dikelola oleh Pemilik</span>
                            <h4 class="font-extrabold text-sm text-slate-900">{{ $kost->owner->name ?? 'Mitra Pemilik Kos' }}</h4>
                            <span class="text-[11px] text-emerald-600 font-medium flex items-center gap-1">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Mitra Pemilik Terverifikasi Seven Kost
                            </span>
                        </div>
                    </div>
                </aside>

            </div>

            {{-- 3. REKOMENDASI KOS SERUPA --}}
            @if($similarKosts->count() > 0)
                <div class="mt-16 pt-12 border-t border-slate-200/80">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                                Kos Lain di Sekitar {{ $kost->regency->name ?? $kost->province->name }}
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">Pilihan alternatif kos di wilayah yang berdekatan.</p>
                        </div>
                        <a href="{{ url('/cari?provinsi=' . $kost->province_id) }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($similarKosts as $sKost)
                            <x-kost-card 
                                :title="$sKost->name"
                                :slug="$sKost->slug"
                                :type="$sKost->type"
                                :city="$sKost->regency->name ?? 'Indonesia'"
                                :province="$sKost->province->name ?? ''"
                                :price="$sKost->price_start"
                                :rentType="$sKost->rent_type"
                                :isVerified="$sKost->verification_status === 'verified'"
                                :isFeatured="(bool)$sKost->is_featured"
                                :availableRooms="$sKost->rooms->sum('available_rooms')"
                                :facilities="$sKost->facilities->pluck('name')->toArray()"
                                :image="$sKost->primaryImage ? asset('storage/' . $sKost->primaryImage->file_path) : null"
                            />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- 4. MOBILE STICKY BOTTOM BAR --}}
        <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] text-slate-400 font-medium block" x-text="selectedRoomName"></span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-black text-blue-600" x-text="'Rp ' + Number(selectedRoomPrice).toLocaleString('id-ID')"></span>
                        <span class="text-[10px] text-slate-500">/ bln</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- WA Quick Button --}}
                    <a 
                        :href="getDynamicWaUrl()" 
                        target="_blank" 
                        class="px-3.5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.35.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                        <span>WA</span>
                    </a>

                    {{-- Booking Quick Button --}}
                    @auth
                        <button 
                            @click="bookingModalOpen = true" 
                            type="button" 
                            class="px-4 py-2.5 rounded-xl bg-navy-950 text-white font-bold text-xs shadow-md shadow-navy-950/20"
                        >
                            Booking
                        </button>
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="px-4 py-2.5 rounded-xl bg-navy-950 text-white font-bold text-xs shadow-md shadow-navy-950/20"
                        >
                            Booking
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- 5. LIGHTBOX MODAL (FULL-SCREEN PHOTO PREVIEW) --}}
        <div 
            x-show="activeImageModal !== null" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6" 
            style="display: none;"
        >
            {{-- Top Modal Bar --}}
            <div class="flex items-center justify-between text-white max-w-6xl mx-auto w-full">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-white/10" x-text="'Foto ' + (activeImageIndex + 1) + ' dari ' + allPhotos.length"></span>
                    <span class="text-xs text-white/70 hidden sm:inline">{{ $kost->name }}</span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-[11px] text-white/50 hidden sm:inline">Gunakan tombol panah &larr; &rarr; untuk navigasi</span>
                    <button 
                        @click="activeImageModal = null" 
                        class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-white transition active:scale-95"
                        title="Tutup (Esc)"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Main Center Image with Arrows --}}
            <div class="relative flex-1 flex items-center justify-center max-w-6xl mx-auto w-full py-4">
                {{-- Prev Button --}}
                <button 
                    @click="prevPhoto()" 
                    class="absolute left-2 sm:left-4 z-10 p-3 rounded-full bg-white/15 hover:bg-white/30 text-white backdrop-blur-md transition active:scale-90"
                    title="Foto Sebelumnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>

                <img 
                    :src="activeImageModal" 
                    alt="Foto Kos" 
                    class="max-h-[70vh] max-w-full rounded-2xl object-contain shadow-2xl transition-all duration-300"
                >

                {{-- Next Button --}}
                <button 
                    @click="nextPhoto()" 
                    class="absolute right-2 sm:right-4 z-10 p-3 rounded-full bg-white/15 hover:bg-white/30 text-white backdrop-blur-md transition active:scale-90"
                    title="Foto Selanjutnya"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Bottom Thumbnails Carousel --}}
            <div class="max-w-4xl mx-auto w-full overflow-x-auto py-2 flex items-center justify-center gap-2">
                <template x-for="(photo, index) in allPhotos" :key="index">
                    <button 
                        @click="openLightbox(index)" 
                        type="button" 
                        class="w-14 h-10 sm:w-16 sm:h-12 rounded-lg overflow-hidden border-2 transition-all flex-shrink-0"
                        :class="activeImageIndex === index ? 'border-blue-500 scale-105 opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                    >
                        <img :src="photo" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        {{-- 6. BOOKING MODAL --}}
        @auth
            <div 
                x-show="bookingModalOpen" 
                x-transition.opacity 
                class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
                style="display: none;"
            >
                <div 
                    @click.outside="bookingModalOpen = false" 
                    class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 text-slate-800 animate-in fade-in zoom-in-95 duration-200"
                >
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wide">Pengajuan Sewa</span>
                            <h3 class="text-lg font-black text-slate-900 mt-0.5">Booking {{ $kost->name }}</h3>
                        </div>
                        <button @click="bookingModalOpen = false" class="text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('tenant.bookings.store', $kost) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        {{-- Pilih Kamar --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Tipe Kamar Terpilih</label>
                            <select 
                                name="room_id" 
                                required 
                                x-model.number="selectedRoomId"
                                @change="
                                    let opt = $event.target.selectedOptions[0];
                                    selectedRoomPrice = Number(opt.dataset.price);
                                    selectedRoomName = opt.dataset.name;
                                    selectedRoomSize = opt.dataset.size;
                                " 
                                class="w-full text-xs font-semibold rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                                @foreach($kost->rooms as $rm)
                                    <option 
                                        value="{{ $rm->id }}" 
                                        data-price="{{ $rm->price }}" 
                                        data-name="{{ $rm->name }}"
                                        data-size="{{ $rm->room_size ?: '3x4 m' }}"
                                        {{ $rm->available_rooms <= 0 ? 'disabled' : '' }}
                                    >
                                        {{ $rm->name }} (Rp {{ number_format($rm->price, 0, ',', '.') }}/bln) - {{ $rm->available_rooms > 0 ? "Sisa {$rm->available_rooms} kamar" : 'Penuh' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Masuk & Durasi --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Tanggal Masuk</label>
                                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-200 py-2 font-medium">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Durasi Sewa</label>
                                <select name="duration_months" x-model.number="durationMonths" required class="w-full text-xs font-medium rounded-xl border-slate-200 py-2">
                                    <option value="1">1 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">1 Tahun (12 Bln)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Catatan Tambahan --}}
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Catatan ke Pemilik (Opsional)</label>
                            <textarea name="notes" rows="2" placeholder="Contoh: Bawa kendaraan motor, rencana mulai masuk jam 10 pagi..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
                        </div>

                        {{-- Ringkasan Biaya --}}
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block">Estimasi Total Biaya:</span>
                                <strong class="text-base font-black text-blue-700" x-text="'Rp ' + (selectedRoomPrice * durationMonths).toLocaleString('id-ID')"></strong>
                            </div>
                            <span class="text-[10px] text-slate-500 font-semibold bg-white px-2 py-1 rounded-md border border-slate-200">Bayar ke pemilik</span>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="bookingModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                                Kirim Pengajuan Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

        {{-- 7. REPORT MODAL --}}
        <div 
            x-show="reportModalOpen" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
            style="display: none;"
        >
            <div 
                @click.outside="reportModalOpen = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 text-slate-800 animate-in fade-in zoom-in-95 duration-200"
            >
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900">Laporkan Iklan Ini</h3>
                    </div>
                    <button @click="reportModalOpen = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('kost.report', $kost) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Alasan Pelaporan <span class="text-red-500">*</span></label>
                        <select name="reason" required class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 py-2.5">
                            <option value="Penipuan / Iklan Palsu">Penipuan / Iklan Palsu</option>
                            <option value="Foto Tidak Sesuai Realita">Foto Tidak Sesuai Realita</option>
                            <option value="Harga Berbeda di Lapangan">Harga Berbeda di Lapangan</option>
                            <option value="Kos Sudah Penuh / Tidak Beroperasi">Kos Sudah Penuh / Tidak Beroperasi</option>
                            <option value="Kontak Pemilik Fiktif">Kontak Pemilik Fiktif</option>
                            <option value="Lainnya">Alasan Lainnya</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Rincian Tambahan</label>
                        <textarea name="details" rows="3" placeholder="Ceritakan rincian kecurigaan atau keluhan Anda secara objektif..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <button type="button" @click="reportModalOpen = false" class="flex-1 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-md shadow-red-500/20 transition">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const lat = {{ $kost->latitude ?? -6.2088 }};
                const lng = {{ $kost->longitude ?? 106.8456 }};

                const map = L.map('kostDetailMap', {
                    scrollWheelZoom: false
                }).setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Custom HTML Marker Pin
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-kost-marker">
                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ addslashes($kost->name) }}</span>
                           </div>`,
                    iconSize: [120, 36],
                    iconAnchor: [60, 18]
                });

                const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
                marker.bindPopup("<strong class='text-sm'>{{ addslashes($kost->name) }}</strong><br><span class='text-xs text-slate-600'>{{ addslashes($kost->address) }}</span>");
            });
        </script>
    @endpush

</x-seven-layout>

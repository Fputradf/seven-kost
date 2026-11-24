<x-tenant-layout :title="$kost->name">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .leaflet-container {
                z-index: 10 !important;
                border-radius: 1rem;
                font-family: inherit;
            }
        </style>
    @endpush

    @php
        // Koleksi Foto Galeri
        $uploadedImages = $kost->images->map(fn($img) => asset('storage/' . $img->file_path))->values()->toArray();
        if ($kost->primaryImage && !in_array(asset('storage/' . $kost->primaryImage->file_path), $uploadedImages)) {
            array_unshift($uploadedImages, asset('storage/' . $kost->primaryImage->file_path));
        }

        $fallbackPhotos = [
            asset('images/frontend/kost-1-hd.jpg'),
            asset('images/frontend/kost-2-hd.jpg'),
            asset('images/frontend/kost-3-hd.jpg'),
            asset('images/frontend/kost-4-hd.jpg'),
            asset('images/frontend/cta-kost-room.jpg'),
        ];

        $galleryPhotos = !empty($uploadedImages) ? $uploadedImages : array_slice($fallbackPhotos, 0, 5);
        while (count($galleryPhotos) < 3) {
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

        $genderBadge = match($kost->type) {
            'putra' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Kos Putra'],
            'putri' => ['bg' => 'bg-pink-50 text-pink-700 border-pink-200', 'label' => 'Kos Putri'],
            default => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Kos Campur'],
        };

        $roomsMap = $kost->rooms->mapWithKeys(function ($room) {
            return [$room->id => [
                'id' => $room->id,
                'name' => $room->name,
                'price' => (float)$room->price,
                'room_size' => $room->room_size ?: '3x4 m',
                'available_rooms' => $room->available_rooms,
                'facilities' => $room->facilities->map(fn($f) => [
                    'id' => $f->id,
                    'name' => $f->name,
                    'icon' => $f->icon,
                    'category' => $f->category,
                ])->values()->all(),
            ]];
        });

        $sharedFacilities = $kost->facilities->where('category', '!=', 'kamar');
    @endphp

    {{-- Main Container Alpine State --}}
    <div 
        x-data="{
            activeImageIndex: 0,
            allPhotos: {{ json_encode($galleryPhotos) }},
            activeImageModal: null,
            isBookmarked: {{ (auth()->check() && auth()->user()->wishlists()->where('kost_id', $kost->id)->exists()) ? 'true' : 'false' }},
            bookingModalOpen: {{ $errors->any() ? 'true' : 'false' }},
            isForOther: {{ old('is_for_other') ? 'true' : 'false' }},
            selectedRoomId: {{ $defaultRoomId }},
            selectedRoomName: '{{ addslashes($defaultRoomName) }}',
            selectedRoomPrice: {{ $defaultRoomPrice }},
            selectedRoomSize: '{{ addslashes($defaultRoomSize) }}',
            selectedRoomAvailable: {{ $defaultRoomAvailable }},
            durationMonths: 1,
            roomsMap: {{ Js::from($roomsMap) }},

            get selectedRoomFacilities() {
                return (this.roomsMap && this.roomsMap[this.selectedRoomId]) ? this.roomsMap[this.selectedRoomId].facilities : [];
            },

            selectRoom(id, name, price, size, available) {
                this.selectedRoomId = id;
                this.selectedRoomName = name;
                this.selectedRoomPrice = price;
                this.selectedRoomSize = size;
                this.selectedRoomAvailable = available;
            },
            nextPhoto() {
                this.activeImageIndex = (this.activeImageIndex + 1) % this.allPhotos.length;
            },
            prevPhoto() {
                this.activeImageIndex = (this.activeImageIndex - 1 + this.allPhotos.length) % this.allPhotos.length;
            },
            getDynamicWaUrl() {
                const text = encodeURIComponent('Halo kak, saya tertarik dengan kos ' + '{{ addslashes($kost->name) }}' + ' untuk tipe kamar ' + this.selectedRoomName + ' (Rp ' + Number(this.selectedRoomPrice).toLocaleString('id-ID') + '/bulan). Apakah kamar masih tersedia?');
                return 'https://wa.me/{{ $cleanPhone }}?text=' + text;
            }
        }"
        @keydown.escape.window="bookingModalOpen = false; activeImageModal = null"
        class="space-y-4 pb-12"
    >

        {{-- 1. PHOTO GALLERY SLIDER --}}
        <div class="relative -mx-4 -mt-3 aspect-[16/10] bg-slate-900 overflow-hidden shadow-sm">
            <template x-for="(photo, idx) in allPhotos" :key="idx">
                <img 
                    :src="photo" 
                    alt="{{ $kost->name }}" 
                    x-show="activeImageIndex === idx" 
                    x-transition.opacity.duration.300ms
                    class="w-full h-full object-cover"
                >
            </template>

            {{-- Slider Controls --}}
            @if(count($galleryPhotos) > 1)
                <button @click="prevPhoto()" class="absolute left-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center backdrop-blur-xs hover:bg-black/70 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="nextPhoto()" class="absolute right-2.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/50 text-white flex items-center justify-center backdrop-blur-xs hover:bg-black/70 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endif

            {{-- Photo Counter & Bookmark Button --}}
            <div class="absolute bottom-2.5 left-3 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-xs text-white text-[11px] font-semibold">
                <span x-text="activeImageIndex + 1"></span> / <span x-text="allPhotos.length"></span> Foto
            </div>

            <button 
                @click="
                    fetch('{{ route('tenant.wishlists.toggle', $kost->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(d => isBookmarked = d.is_favorited);
                " 
                class="absolute bottom-2.5 right-3 w-8 h-8 rounded-full bg-white/90 text-slate-700 shadow-md flex items-center justify-center transition active:scale-90"
                title="Simpan Favorit"
            >
                <svg class="w-4 h-4 transition" :class="isBookmarked ? 'text-rose-500 fill-rose-500' : 'text-slate-600'" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>

        {{-- 2. KOS MAIN INFORMATION --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs space-y-3">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold border {{ $genderBadge['bg'] }}">
                    {{ $genderBadge['label'] }}
                </span>
                @if($kost->verification_status === 'verified')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Terverifikasi
                    </span>
                @endif
                @if($kost->is_featured)
                    <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">
                        Rekomendasi
                    </span>
                @endif
            </div>

            <div>
                <h1 class="text-lg font-black text-slate-900 leading-snug">{{ $kost->name }}</h1>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $kost->address }}, {{ $kost->regency->name ?? '' }}, {{ $kost->province->name ?? '' }}</span>
                </p>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Mulai Dari</span>
                    <span class="text-base font-black text-blue-600">Rp {{ number_format($kost->price_start, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-400 font-medium">/ {{ $kost->rent_type ?: 'bulan' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Ketersediaan</span>
                    <span class="text-xs font-bold text-slate-700">{{ $kost->rooms->sum('available_rooms') }} Kamar Kosong</span>
                </div>
            </div>
        </div>

        {{-- 3. PILIHAN TIPE KAMAR --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-wide">Pilihan Tipe Kamar</h2>
                <span class="text-[10px] text-slate-400 font-semibold">{{ $kost->rooms->count() }} Tipe Tersedia</span>
            </div>

            <div class="space-y-2.5">
                @foreach($kost->rooms as $room)
                    <div 
                        @click="selectRoom({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }}, '{{ addslashes($room->room_size ?: '3x4 m') }}', {{ $room->available_rooms }})"
                        class="p-3.5 rounded-xl border transition cursor-pointer select-none relative"
                        :class="selectedRoomId === {{ $room->id }} ? 'bg-blue-50/70 border-blue-500 ring-2 ring-blue-500/20 shadow-2xs' : 'bg-slate-50 border-slate-200/80 hover:bg-slate-100/60'"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-xs font-bold text-slate-900">{{ $room->name }}</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Ukuran: {{ $room->room_size ?: '3x4 m' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-blue-700 block">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold {{ $room->available_rooms > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ $room->available_rooms > 0 ? "Sisa {$room->available_rooms} kamar" : 'Penuh' }}
                                </span>
                            </div>
                        </div>

                        {{-- Room Specific Facilities Badges --}}
                        @if($room->facilities->isNotEmpty())
                            <div class="mt-2.5 flex items-center gap-1.5 flex-wrap">
                                @foreach($room->facilities as $fac)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/90 text-slate-700 border border-slate-200/90 shadow-2xs">
                                        <svg class="w-2.5 h-2.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ $fac->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-slate-200/60 text-[10px] text-slate-500">
                            <span>Kapasitas 1-2 Orang</span>
                            <span x-show="selectedRoomId === {{ $room->id }}" class="text-blue-600 font-bold flex items-center gap-1">
                                <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Terpilih
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 4. FASILITAS BERSAMA KOS --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs space-y-3">
            <div>
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-wide">Fasilitas Bersama Kos</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Fasilitas properti yang dapat digunakan oleh seluruh penghuni.</p>
            </div>
            
            @if($sharedFacilities->isNotEmpty())
                <div class="grid grid-cols-2 gap-2">
                    @foreach($sharedFacilities as $fac)
                        <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-200/60 text-xs text-slate-700 font-medium">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="truncate">{{ $fac->name }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400">Fasilitas umum disediakan sesuai standar hunian kos.</p>
            @endif
        </div>

        {{-- 5. DESKRIPSI & PERATURAN KOS --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs space-y-3" x-data="{ expanded: false }">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-wide">Deskripsi Kos</h2>
            <div class="text-xs text-slate-600 leading-relaxed space-y-2">
                <p :class="expanded ? '' : 'line-clamp-4'">
                    {{ $kost->description ?: 'Kos nyaman, aman, dan strategis dekat dengan pusat aktivitas kampus dan perkantoran.' }}
                </p>
                @if(strlen($kost->description) > 200)
                    <button @click="expanded = !expanded" class="text-xs font-bold text-blue-600 hover:text-blue-700 pt-1 block">
                        <span x-text="expanded ? 'Tutup sebagian' : 'Baca selengkapnya...'"></span>
                    </button>
                @endif
            </div>
        </div>

        {{-- 6. KONTAK PEMILIK KOS & CHECKOUT SEWA --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs space-y-3.5">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-wide">Pemilik & Pemesanan Kamar</h2>
            
            <div class="flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($kost->owner->name ?? 'P', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold text-slate-900 truncate">{{ $kost->owner->name ?? 'Pemilik Kos' }}</h3>
                    <p class="text-[11px] text-slate-400">Pengelola Resmi</p>
                </div>
            </div>

            {{-- Ringkasan Biaya Kamar Terpilih --}}
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-500 font-semibold block uppercase" x-text="selectedRoomName"></span>
                    <strong class="text-sm sm:text-base font-black text-blue-700" x-text="'Rp ' + Number(selectedRoomPrice).toLocaleString('id-ID')"></strong>
                    <span class="text-[10px] text-slate-500">/ bulan</span>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200" x-text="selectedRoomAvailable > 0 ? 'Tersedia' : 'Penuh'"></span>
            </div>

            {{-- Action Buttons: Chat WA & Tepat di Bawahnya Checkout Sewa --}}
            <div class="space-y-2 pt-1">
                {{-- 1. Tombol Chat WhatsApp --}}
                <a :href="getDynamicWaUrl()" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs active:scale-95">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Tanya Pemilik via WhatsApp</span>
                </a>

                {{-- 2. Tombol Checkout Sewa (Di Bawah Chat WA) --}}
                <button @click="bookingModalOpen = true" type="button" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-blue-500/25 transition active:scale-95">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Checkout Sewa Kamar</span>
                </button>
            </div>
        </div>

        {{-- 8. BOOKING FORM MODAL (MOBILE BOTTOM SHEET) --}}
        <div x-show="bookingModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" style="display: none;">
            <div @click.outside="bookingModalOpen = false" class="w-full max-w-md bg-white rounded-t-3xl sm:rounded-3xl p-5 shadow-2xl max-h-[90vh] overflow-y-auto space-y-4">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Form Pengajuan Sewa Kos</h3>
                        <p class="text-[11px] text-slate-500">{{ $kost->name }}</p>
                    </div>
                    <button @click="bookingModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Notifikasi Error Validasi Form --}}
                @if ($errors->any())
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-rose-900">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>Periksa Kembali Data:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.bookings.store', $kost) }}" method="POST" class="space-y-3.5">
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

                    {{-- Data Diri Calon Penghuni --}}
                    <div class="space-y-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Data Diri Calon Penghuni</span>
                            </label>
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100/80 px-2 py-0.5 rounded-md">Wajib Diisi</span>
                        </div>

                        {{-- Pemesanan Untuk Siapa --}}
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-semibold text-slate-600">Pemesanan Untuk:</span>
                            <div class="grid grid-cols-2 gap-2">
                                <label 
                                    class="flex items-center gap-2 p-2 rounded-xl border cursor-pointer text-xs font-bold transition select-none"
                                    :class="!isForOther ? 'bg-blue-50 border-blue-400 text-blue-700 shadow-2xs' : 'bg-white border-slate-200 text-slate-600'"
                                >
                                    <input type="radio" name="booking_for" value="myself" class="text-blue-600 focus:ring-blue-500" @change="isForOther = false" checked>
                                    <span>Diri Sendiri</span>
                                </label>
                                <label 
                                    class="flex items-center gap-2 p-2 rounded-xl border cursor-pointer text-xs font-bold transition select-none"
                                    :class="isForOther ? 'bg-blue-50 border-blue-400 text-blue-700 shadow-2xs' : 'bg-white border-slate-200 text-slate-600'"
                                >
                                    <input type="radio" name="booking_for" value="other" class="text-blue-600 focus:ring-blue-500" @change="isForOther = true">
                                    <span>Orang Lain</span>
                                </label>
                            </div>
                            <input type="hidden" name="is_for_other" :value="isForOther ? '1' : '0'">
                        </div>

                        {{-- Nama & Nomor Telepon --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input 
                                    type="text" 
                                    name="tenant_name" 
                                    maxlength="100"
                                    :value="isForOther ? '{{ old('tenant_name') }}' : '{{ old('tenant_name', Auth::user()?->name) }}'"
                                    placeholder="Nama sesuai KTP" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">No. WhatsApp <span class="text-rose-500">*</span></label>
                                <input 
                                    type="tel" 
                                    name="tenant_phone" 
                                    maxlength="16"
                                    minlength="9"
                                    inputmode="numeric"
                                    :value="isForOther ? '{{ old('tenant_phone') }}' : '{{ old('tenant_phone', Auth::user()?->phone) }}'"
                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                                    placeholder="081234567890" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                        </div>

                        {{-- NIK KTP & Jenis Kelamin --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">NIK KTP (16 Digit) <span class="text-rose-500">*</span></label>
                                <input 
                                    type="text" 
                                    name="tenant_nik" 
                                    maxlength="16"
                                    minlength="16"
                                    inputmode="numeric"
                                    value="{{ old('tenant_nik') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="16 digit angka NIK" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 tracking-wider focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <select 
                                    name="tenant_gender" 
                                    required 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="" disabled {{ old('tenant_gender') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                    <option value="pria" {{ old('tenant_gender') === 'pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="wanita" {{ old('tenant_gender') === 'wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                            </div>
                        </div>

                        {{-- Pekerjaan & Kontak Darurat --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Pekerjaan / Status</label>
                                <input 
                                    type="text" 
                                    name="tenant_job" 
                                    maxlength="100"
                                    value="{{ old('tenant_job') }}"
                                    placeholder="Cth: Mahasiswa / Karyawan" 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold text-slate-700">Kontak Darurat (Kerabat/Ortu)</label>
                                <input 
                                    type="tel" 
                                    name="emergency_phone" 
                                    maxlength="16"
                                    minlength="9"
                                    inputmode="numeric"
                                    value="{{ old('emergency_phone') }}"
                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                                    placeholder="08xxxxxxxxxx" 
                                    class="w-full text-xs font-semibold rounded-xl border-slate-200 py-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal Masuk & Durasi (Maksimal 7 hari ke depan) --}}
                    @php
                        $minEntryDate = now()->toDateString();
                        $maxEntryDate = now()->addDays(7)->toDateString();
                    @endphp
                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Tgl Masuk (Maks 7 Hari)</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                value="{{ old('start_date', $minEntryDate) }}" 
                                min="{{ $minEntryDate }}" 
                                max="{{ $maxEntryDate }}" 
                                required 
                                class="w-full text-xs rounded-xl border-slate-200 py-2 font-medium"
                            >
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase">Durasi Sewa</label>
                            <select name="duration_months" x-model.number="durationMonths" required class="w-full text-xs font-medium rounded-xl border-slate-200 py-2">
                                <option value="1">1 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">1 Tahun</option>
                            </select>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Catatan ke Pemilik (Opsional)</label>
                        <textarea name="notes" rows="2" maxlength="500" placeholder="Rencana waktu kedatangan, kendaraan, dll..." class="w-full text-xs rounded-xl border-slate-200 p-2">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Total Biaya --}}
                    <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase block font-semibold">Total Estimasi:</span>
                            <strong class="text-sm font-black text-blue-700" x-text="'Rp ' + (selectedRoomPrice * durationMonths).toLocaleString('id-ID')"></strong>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold bg-white px-2 py-1 rounded border border-slate-200">Bayar ke pemilik</span>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <button type="button" @click="bookingModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition active:scale-95">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</x-tenant-layout>

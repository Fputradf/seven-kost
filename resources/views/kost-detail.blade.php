<x-seven-layout :title="$kost->name . ' - Seven Kost'">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .leaflet-container {
                z-index: 10 !important;
                border-radius: 1.5rem;
            }
        </style>
    @endpush

    {{-- Breadcrumb Navigation --}}
    <div class="bg-white border-b border-slate-100 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span>/</span>
                <a href="{{ url('/cari?provinsi=' . $kost->province_id) }}" class="hover:text-blue-600 transition">{{ $kost->province->name ?? 'Provinsi' }}</a>
                <span>/</span>
                <a href="{{ url('/cari?kota=' . $kost->regency_id) }}" class="hover:text-blue-600 transition">{{ $kost->regency->name ?? 'Kota' }}</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold truncate">{{ $kost->name }}</span>
            </nav>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeImageModal: null, bookingModalOpen: false, selectedRoomPrice: {{ $kost->rooms->first()->price ?? $kost->price_start }}, durationMonths: 1 }">

        {{-- PHOTO GALLERY SECTION --}}
        <div class="mb-8">
            @php
                $allImages = $kost->images;
                $coverUrl = $kost->primaryImage 
                    ? asset('storage/' . $kost->primaryImage->file_path)
                    : ($allImages->first() ? asset('storage/' . $allImages->first()->file_path) : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 bg-slate-100">
                {{-- Main Big Photo (Colspan 2 atau 3) --}}
                <div class="md:col-span-2 lg:col-span-3 relative aspect-[16/10] sm:aspect-[16/9] overflow-hidden group cursor-pointer" @click="activeImageModal = '{{ $coverUrl }}'">
                    <img src="{{ $coverUrl }}" alt="{{ $kost->name }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-6">
                        <div class="flex items-center gap-2 flex-wrap">
                            <x-seven-badge :type="$kost->type" class="uppercase text-xs px-3 py-1 bg-white/90 shadow-sm font-bold">
                                Kos {{ $kost->type }}
                            </x-seven-badge>

                            @if($kost->verification_status === 'verified')
                                <x-seven-badge type="verified" class="bg-white/90 shadow-sm font-bold">
                                    Lencana Terverifikasi
                                </x-seven-badge>
                            @endif

                            @if($kost->is_featured)
                                <x-seven-badge type="featured">
                                    Unggulan Nasional
                                </x-seven-badge>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Side Thumbnails Grid --}}
                <div class="hidden md:grid grid-rows-2 gap-3 p-3 bg-white">
                    @forelse($allImages->where('is_primary', false)->take(2) as $img)
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] group cursor-pointer" @click="activeImageModal = '{{ asset('storage/' . $img->file_path) }}'">
                            <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                    @empty
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] group cursor-pointer" @click="activeImageModal = 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80'">
                            <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                        <div class="relative rounded-2xl overflow-hidden aspect-[4/3] group cursor-pointer" @click="activeImageModal = 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80'">
                            <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- CONTENT TWO-COLUMN LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- LEFT COLUMN: DETAIL CONTENT --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Header Title & Location --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-3">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-snug">
                        {{ $kost->name }}
                    </h1>
                    <div class="flex items-center gap-1.5 text-xs sm:text-sm text-slate-600 font-medium">
                        <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $kost->address }}, {{ $kost->regency->name ?? '' }}, {{ $kost->province->name ?? '' }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-4 text-xs text-slate-500">
                        <div class="flex items-center gap-4">
                            <span>Sisa Kamar: <strong class="text-emerald-600 font-bold">{{ $kost->rooms->sum('available_rooms') }} Kamar Siap Huni</strong></span>
                            <span class="inline-block h-3 w-px bg-slate-200"></span>
                            <span>Periode: <strong class="text-slate-800 capitalize">{{ $kost->rent_type }}</strong></span>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            Diperbarui {{ $kost->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                {{-- Tipe Kamar & Ketersediaan --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-5">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Tipe Kamar Tersedia</h2>
                        <p class="text-xs text-slate-500">Pilih tipe kamar yang sesuai dengan kebutuhan Anda.</p>
                    </div>

                    <div class="space-y-3">
                        @foreach($kost->rooms as $room)
                            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-blue-400 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-sm text-slate-900">{{ $room->name }}</h3>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700">
                                            {{ $room->room_size ?? '3x4 m' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        Kapasitas 1-2 orang &bull; Total {{ $room->total_rooms }} kamar &bull; <strong class="text-emerald-700">Sisa {{ $room->available_rooms }} unit</strong>
                                    </p>
                                </div>

                                <div class="text-right sm:self-center">
                                    <div class="text-base font-extrabold text-blue-600">
                                        Rp {{ number_format($room->price, 0, ',', '.') }}
                                    </div>
                                    <span class="text-[10px] text-slate-400">per bulan</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Fasilitas Properti --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Fasilitas Properti</h2>
                        <p class="text-xs text-slate-500">Kelengkapan fasilitas yang disediakan di kos ini.</p>
                    </div>

                    @php
                        $groupedFacs = $kost->facilities->groupBy('category');
                    @endphp

                    @if($groupedFacs->count() > 0)
                        <div class="space-y-5">
                            @foreach($groupedFacs as $cat => $items)
                                <div class="space-y-2.5">
                                    <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                        Fasilitas {{ ucfirst($cat) }}
                                    </h3>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                        @foreach($items as $fac)
                                            <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs font-medium text-slate-700">
                                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <span>{{ $fac->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Fasilitas standar tersedia.</p>
                    @endif
                </div>

                {{-- Deskripsi & Aturan Kos --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Deskripsi & Lingkungan</h2>
                        <div class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2 whitespace-pre-line">
                            {{ $kost->description ?: 'Kos nyaman dan strategis di lokasi yang aman dan tenang.' }}
                        </div>
                    </div>

                    @if($kost->rules)
                        <div class="pt-6 border-t border-slate-100 space-y-2">
                            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Peraturan & Tata Tertib Kos
                            </h3>
                            <p class="text-xs text-slate-600 whitespace-pre-line bg-amber-50/50 border border-amber-100 p-4 rounded-2xl">
                                {{ $kost->rules }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- PETA LOKASI LEAFLET --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Lokasi pada Peta</h2>
                            <p class="text-xs text-slate-500">Peta koordinat resmi lokasi kos Seven Kost.</p>
                        </div>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $kost->latitude ?? -6.2088 }},{{ $kost->longitude ?? 106.8456 }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                            Buka di Google Maps &rarr;
                        </a>
                    </div>

                    <div id="kostDetailMap" class="h-80 w-full rounded-2xl border border-slate-200 overflow-hidden shadow-inner"></div>
                </div>

            </div>

            {{-- RIGHT COLUMN: STICKY BOOKING CARD & OWNER CONTACT --}}
            <aside class="sticky top-24 space-y-6">
                {{-- Booking Action Card --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-xl shadow-slate-200/50 space-y-5">
                    <div>
                        <span class="text-xs text-slate-400 font-medium block">Harga Mulai Dari</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-black text-blue-600">
                                Rp {{ number_format($kost->price_start, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-slate-500">/ {{ $kost->rent_type }}</span>
                        </div>
                    </div>

                    {{-- WhatsApp Link --}}
                    @php
                        $ownerPhone = $kost->owner->phone ?? '6281234567890';
                        $cleanPhone = preg_replace('/[^0-9]/', '', $ownerPhone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $waText = urlencode("Halo, saya tertarik dengan informasi sewa kos {$kost->name} di Seven Kost. Apakah masih ada kamar kosong?");
                        $waUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
                    @endphp

                    <div class="space-y-2.5 pt-2">
                        <x-seven-button :href="$waUrl" target="_blank" variant="primary" size="md" class="w-full justify-center bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-emerald-500/25">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.35.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                            Hubungi Pemilik via WA
                        </x-seven-button>

                        @auth
                            <button @click="bookingModalOpen = true" type="button" class="w-full inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] text-sm px-4.5 py-2.5 bg-navy-950 hover:bg-slate-900 text-white shadow-md shadow-navy-950/20 border border-slate-800">
                                Ajukan Booking Sewa
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] text-sm px-4.5 py-2.5 bg-navy-950 hover:bg-slate-900 text-white shadow-md shadow-navy-950/20 border border-slate-800">
                                Masuk untuk Booking
                            </a>
                        @endauth
                    </div>

                    {{-- Card Keamanan --}}
                    <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Tanpa biaya perantara / calo</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Data kontak terverifikasi tim Seven Kost</span>
                        </div>
                    </div>
                </div>

                {{-- Owner Profile Snippet --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-xs flex items-center gap-3.5">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-md">
                        {{ strtoupper(substr($kost->owner->name ?? 'Pemilik', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-[11px] text-slate-400 font-semibold block">Dikelola oleh</span>
                        <h4 class="font-bold text-sm text-slate-900">{{ $kost->owner->name ?? 'Pemilik Kos' }}</h4>
                        <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-1 mt-0.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Mitra Pemilik Aktif
                        </span>
                    </div>
                </div>
            </aside>
        </div>

        {{-- SIMILAR KOSTS RECOMMENDATIONS --}}
        @if($similarKosts->count() > 0)
            <div class="mt-16 pt-12 border-t border-slate-200/80">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                            Kos Lain di Sekitar {{ $kost->regency->name ?? $kost->province->name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">Pilihan alternatif kos di wilayah yang berdekatan.</p>
                    </div>
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

        {{-- BOOKING MODAL --}}
        @auth
            <div x-show="bookingModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                <div @click.outside="bookingModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6 text-slate-800">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase">Pengajuan Sewa</span>
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
                            <label class="block text-xs font-bold text-slate-700 uppercase">Pilih Tipe Kamar</label>
                            <select name="room_id" required @change="selectedRoomPrice = $event.target.selectedOptions[0].dataset.price" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5">
                                @foreach($kost->rooms as $rm)
                                    <option value="{{ $rm->id }}" data-price="{{ $rm->price }}" {{ $rm->available_rooms <= 0 ? 'disabled' : '' }}>
                                        {{ $rm->name }} (Rp {{ number_format($rm->price, 0, ',', '.') }}/bln) - {{ $rm->available_rooms > 0 ? "Sisa {$rm->available_rooms} kamar" : 'Penuh' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Masuk & Durasi --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Tanggal Masuk</label>
                                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-200 py-2">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Durasi Sewa</label>
                                <select name="duration_months" x-model.number="durationMonths" required class="w-full text-xs rounded-xl border-slate-200 py-2">
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
                            <textarea name="notes" rows="2" placeholder="Contoh: Bawa kendaraan motor, rencana pindah tanggal..." class="w-full text-xs rounded-xl border-slate-200 p-2.5"></textarea>
                        </div>

                        {{-- Ringkasan Biaya --}}
                        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block">Estimasi Total Biaya:</span>
                                <strong class="text-base font-black text-blue-700" x-text="'Rp ' + (selectedRoomPrice * durationMonths).toLocaleString('id-ID')"></strong>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">Bayar langsung ke pemilik</span>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="bookingModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                                Kirim Pengajuan Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

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

                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<strong>{{ addslashes($kost->name) }}</strong><br>{{ addslashes($kost->address) }}").openPopup();
            });
        </script>
    @endpush

</x-seven-layout>

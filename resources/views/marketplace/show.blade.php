<x-seven-layout :title="$item->title . ' - Marketplace SevenKost'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8" x-data="{ activeImage: '{{ $item->primary_image_url }}', contactModalOpen: false }">
        
        {{-- BREADCRUMBS --}}
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-blue-600">Marketplace</a>
            <span>/</span>
            <a href="{{ route('marketplace.index', ['kategori' => $item->category]) }}" class="hover:text-blue-600">{{ $item->category_label }}</a>
            <span>/</span>
            <span class="text-slate-800 font-bold truncate max-w-xs">{{ $item->title }}</span>
        </div>

        {{-- MAIN DETAIL GRID 2 KOLOM --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- ========================================================================= --}}
            {{-- KOLOM KIRI: GALERI FOTO & DETAIL PRODUK (LG: COL-8)                       --}}
            {{-- ========================================================================= --}}
            <div class="lg:col-span-8 space-y-7">
                
                {{-- CARD UTAMA: GALERI & SPESIFIKASI --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                    
                    {{-- Galeri Foto --}}
                    <div class="space-y-4">
                        {{-- Foto Utama Besar --}}
                        <div class="relative w-full h-80 sm:h-96 rounded-2xl bg-slate-100 overflow-hidden shadow-xs border border-slate-200">
                            <img :src="activeImage" alt="{{ $item->title }}" class="w-full h-full object-cover">

                            {{-- Badges: Hanya COD dan Status Barang --}}
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                <div class="px-3 py-1 rounded-md bg-emerald-600 text-white font-bold text-xs shadow-xs">
                                    COD
                                </div>
                                <div class="px-3 py-1 rounded-md bg-black/60 backdrop-blur-xs text-white font-semibold text-xs">
                                    {{ $item->condition_label }}
                                </div>
                            </div>

                            <div class="absolute bottom-4 right-4 px-3 py-1 rounded-xl bg-black/60 backdrop-blur-xs text-white text-xs font-bold">
                                📷 {{ $item->images->count() ?: 1 }} Foto
                            </div>
                        </div>

                        {{-- Thumbnails jika lebih dari 1 foto --}}
                        @if($item->images->count() > 1)
                            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                                @foreach($item->images as $img)
                                    @php
                                        $url = (str_starts_with($img->image_path, 'http') || str_starts_with($img->image_path, 'images/')) 
                                            ? asset($img->image_path) 
                                            : asset('storage/' . $img->image_path);
                                    @endphp
                                    <button 
                                        type="button" 
                                        @click="activeImage = '{{ $url }}'"
                                        class="w-20 h-20 rounded-xl overflow-hidden border-2 transition shrink-0"
                                        :class="activeImage === '{{ $url }}' ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-slate-200 opacity-70 hover:opacity-100'"
                                    >
                                        <img src="{{ $url }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Informasi Judul & Harga --}}
                    <div class="space-y-4 pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                                {{ $item->title }}
                            </h1>
                            <span class="text-xs text-slate-400 shrink-0">
                                👁️ Dilihat {{ $item->views_count }} kali
                            </span>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-blue-600">
                                {{ $item->formatted_price }}
                            </span>
                        </div>

                        {{-- Tags / Badges --}}
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <div class="px-3 py-1 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200">
                                Kondisi: {{ $item->condition_label }}
                            </div>
                            <div class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
                                Kategori: {{ $item->category_label }}
                            </div>
                            @if($item->city)
                                <div class="px-3 py-1 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
                                    Kota: {{ $item->city }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Deskripsi Barang --}}
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <h3 class="font-extrabold text-base text-slate-900">
                            Deskripsi Barang
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $item->description }}
                        </p>
                    </div>

                    {{-- Lokasi Titik Temu COD --}}
                    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-blue-900 uppercase tracking-wider">
                            <span>📍 Titik Temu COD yang Direkomendasikan Penjual</span>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold text-slate-800">
                            {{ $item->meetup_location }}
                        </p>
                        <span class="text-[11px] text-slate-500 block">
                            Lokasi publik aman (lobby kos / minimarket sekitar kampus) untuk kenyamanan bertransaksi langsung.
                        </span>
                    </div>
                </div>

                {{-- TIPS KEAMANAN COD (SESUAI DESAIN MOCKUP) --}}
                <div class="p-6 rounded-3xl bg-blue-50/50 border border-blue-200/80 space-y-3 text-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                            ✓
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900">Tips Keamanan Transaksi COD</h4>
                            <p class="text-xs text-slate-500">Pastikan barang sesuai sebelum membayar ke penjual.</p>
                        </div>
                    </div>

                    <ul class="space-y-2 text-xs text-slate-600 pt-1">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Lakukan COD di tempat ramai (kampus, minimarket, atau area publik kos).</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>Periksa kondisi fisik dan fungsi barang dengan teliti sebelum serah terima uang.</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-rose-500 font-bold">✕</span>
                            <span><strong>PENTING:</strong> Jangan transfer uang ke rekening manapun sebelum barang diterima langsung.</span>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- ========================================================================= --}}
            {{-- KOLOM KANAN: INFO PENJUAL & TOMBOL BELI / HUBUNGI PENJUAL (LG: COL-4)      --}}
            {{-- ========================================================================= --}}
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                
                {{-- CARD INFORMASI PENJUAL --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs space-y-5">
                    <h3 class="font-extrabold text-sm text-slate-900 pb-2 border-b border-slate-100">
                        Informasi Penjual
                    </h3>

                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 font-black text-xl flex items-center justify-center shadow-xs">
                            {{ substr($item->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-bold text-base text-slate-900 flex items-center gap-1.5">
                                <span>{{ $item->user->name ?? 'Penjual' }}</span>
                                <span class="text-blue-500 text-sm" title="Pengguna Terdaftar">✓</span>
                            </h4>
                            <div class="inline-block px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px]">
                                Pengguna Terverifikasi
                            </div>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 space-y-1.5 pt-1">
                        <div class="flex items-center justify-between">
                            <span>Status Akun:</span>
                            <span class="font-bold text-slate-700">Mitra SevenKost</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Terdaftar Sejak:</span>
                            <span class="font-bold text-slate-700">{{ $item->created_at->format('M Y') }}</span>
                        </div>
                    </div>

                    {{-- TOMBOL UTAMA BELI / HUBUNGI PENJUAL --}}
                    <div class="pt-2">
                        @auth
                            <button 
                                type="button" 
                                @click="contactModalOpen = true"
                                class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                            >
                                <span>💬</span>
                                <span>Hubungi Penjual (COD)</span>
                            </button>
                        @else
                            {{-- PROMPT GUEST HARUS LOGIN DULU --}}
                            <a 
                                href="{{ route('login') }}" 
                                class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                            >
                                <span>🔒</span>
                                <span>Masuk untuk Hubungi Penjual</span>
                            </a>
                            <span class="text-[11px] text-slate-400 block text-center mt-2">
                                Masuk ke akun pembeli atau pemilik untuk transaksi COD
                            </span>
                        @endauth
                    </div>
                </div>

                {{-- CARD SARAN TITIK TEMU AMAN --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-900">
                        <span class="text-blue-600">🛡️</span>
                        <span>Saran Titik Temu Aman</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pilih tempat terang dan ramai seperti lobby kos, pos satpam, atau cafetaria kampus terdekat demi kenyamanan kedua pihak.
                    </p>
                </div>

            </div>

        </div>

        {{-- MODAL HUBUNGI PENJUAL (UNTUK USER LOGIN) --}}
        @auth
            <div x-show="contactModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                <div @click.outside="contactModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-800">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Kontak Penjual Barang</span>
                            <h3 class="text-base font-black text-slate-900">{{ $item->title }}</h3>
                        </div>
                        <button @click="contactModalOpen = false" class="text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/80 border border-blue-200 space-y-2 text-xs">
                        <div class="font-bold text-slate-900">Petunjuk Janji Temu (COD):</div>
                        <p class="text-slate-600 leading-relaxed">
                            Hubungi penjual untuk menyepakati waktu dan lokasi titik temu. Ingat: tidak ada pembayaran di muka melalui aplikasi.
                        </p>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                            <span class="text-slate-500">Nama Penjual:</span>
                            <strong class="text-slate-900">{{ $item->user->name }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                            <span class="text-slate-500">Titik Temu:</span>
                            <strong class="text-slate-900 text-right">{{ $item->meetup_location }}</strong>
                        </div>
                        <div class="p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                            <span class="text-slate-500">Nomor Kontak (WhatsApp):</span>
                            <strong class="text-blue-600 font-mono">{{ $item->user->phone ?? '0812-xxxx-xxxx' }}</strong>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        @click="contactModalOpen = false" 
                        class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        @endauth

    </div>

</x-seven-layout>

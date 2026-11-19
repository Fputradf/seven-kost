<x-seven-layout :title="$item->title . ' - Marketplace SevenKost'">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 space-y-8" x-data="{ activeImage: '{{ $item->primary_image_url }}', contactModalOpen: false }">
        
        {{-- BREADCRUMBS --}}
        <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-blue-600">Marketplace</a>
            <span>/</span>
            <a href="{{ route('marketplace.index', ['kategori' => $item->category]) }}" class="hover:text-blue-600">{{ $item->category_label }}</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold truncate max-w-xs">{{ $item->title }}</span>
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

                            {{-- Badges: COD dan Status Barang --}}
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                <div class="px-3 py-1 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-xs tracking-wide">
                                    COD
                                </div>
                                <div class="px-3 py-1 rounded-lg bg-black/60 backdrop-blur-xs text-white font-medium text-xs">
                                    {{ $item->condition_label }}
                                </div>
                            </div>

                            <div class="absolute bottom-4 right-4 px-3 py-1 rounded-xl bg-black/60 backdrop-blur-xs text-white text-xs font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $item->images->count() ?: 1 }} Foto</span>
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
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                                {{ $item->title }}
                            </h1>
                            <span class="text-xs sm:text-sm text-slate-500 inline-flex items-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Dilihat {{ $item->views_count }} kali</span>
                            </span>
                        </div>

                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-black text-blue-600">
                                {{ $item->formatted_price }}
                            </span>
                        </div>

                        {{-- Tags / Badges --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-700 font-semibold text-xs sm:text-sm border border-blue-200">
                                Kondisi: {{ $item->condition_label }}
                            </div>
                            <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm border border-slate-200">
                                Kategori: {{ $item->category_label }}
                            </div>
                            @if($item->city)
                                <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm border border-slate-200">
                                    Kota: {{ $item->city }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Deskripsi Barang --}}
                    <div class="space-y-3 pt-5 border-t border-slate-100">
                        <h3 class="font-bold text-base sm:text-lg text-slate-900">
                            Deskripsi Barang
                        </h3>
                        <p class="text-sm sm:text-base text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $item->description }}
                        </p>
                    </div>

                    {{-- Lokasi Titik Temu COD --}}
                    <div class="p-4 sm:p-5 rounded-2xl bg-blue-50/70 border border-blue-200/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-blue-900 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            <span>Titik Temu COD Rekomendasi Penjual</span>
                        </div>
                        <p class="text-sm sm:text-base font-bold text-slate-800">
                            {{ $item->meetup_location }}
                        </p>
                        <span class="text-xs text-slate-500 block">
                            Lokasi publik aman (lobby kos / minimarket sekitar kampus) untuk kenyamanan bertransaksi langsung.
                        </span>
                    </div>
                </div>

                {{-- TIPS KEAMANAN COD --}}
                <div class="p-6 rounded-3xl bg-blue-50/50 border border-blue-200/80 space-y-3.5 text-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Tips Keamanan Transaksi COD</h4>
                            <p class="text-xs sm:text-sm text-slate-500">Pastikan barang sesuai sebelum membayar ke penjual.</p>
                        </div>
                    </div>

                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 pt-1">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Lakukan COD di tempat ramai (kampus, minimarket, atau area publik kos).</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Periksa kondisi fisik dan fungsi barang dengan teliti sebelum serah terima uang.</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-rose-600 font-bold">✕</span>
                            <span><strong>PENTING:</strong> Jangan mentransfer uang sebelum barang diperiksa dan diterima langsung.</span>
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
                    <h3 class="font-bold text-base text-slate-900 pb-2 border-b border-slate-100">
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
                            <div class="inline-block px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold text-xs">
                                Pengguna Terverifikasi
                            </div>
                        </div>
                    </div>

                    <div class="text-xs sm:text-sm text-slate-600 space-y-2 pt-1">
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
                                class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Hubungi Penjual (COD)</span>
                            </button>
                        @else
                            {{-- PROMPT GUEST HARUS LOGIN DULU --}}
                            <a 
                                href="{{ route('login') }}" 
                                class="w-full py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Masuk untuk Hubungi Penjual</span>
                            </a>
                            <span class="text-xs text-slate-500 block text-center mt-2.5">
                                Masuk ke akun Anda untuk transaksi langsung via COD
                            </span>
                        @endauth
                    </div>
                </div>

                {{-- CARD SARAN TITIK TEMU AMAN --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-900">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Saran Titik Temu Aman</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
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
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">{{ $item->title }}</h3>
                        </div>
                        <button @click="contactModalOpen = false" class="text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/80 border border-blue-200 space-y-1.5 text-xs sm:text-sm">
                        <div class="font-bold text-slate-900">Petunjuk Janji Temu (COD):</div>
                        <p class="text-slate-600 leading-relaxed">
                            Hubungi penjual untuk menyepakati waktu dan lokasi titik temu. Ingat: tidak ada pembayaran di muka melalui aplikasi.
                        </p>
                    </div>

                    <div class="space-y-3 text-xs sm:text-sm">
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
                        class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        @endauth

    </div>

</x-seven-layout>

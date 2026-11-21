<x-tenant-layout :title="$item->title . ' - Marketplace SevenKost'">

    @php
        $sellerPhone = $item->user->phone ?? '081234567890';
        $cleanPhone = preg_replace('/[^0-9]/', '', $sellerPhone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }
        $buyerName = auth()->user()->name ?? 'Penghuni Kos';
        $waMessage = urlencode("Halo kak {$item->user->name}, saya {$buyerName} melihat barang \"{$item->title}\" ({$item->formatted_price}) di Marketplace SevenKost. Apakah masih ada? Mau tanya jadwal dan lokasi COD di {$item->meetup_location}.");
        $waLink = "https://wa.me/{$cleanPhone}?text={$waMessage}";
    @endphp

    <div class="space-y-4" x-data="{ 
        activeImage: '{{ $item->primary_image_url }}', 
        contactModalOpen: false,
        deleteModalOpen: false,
        editModalOpen: false
    }">

        {{-- 1. TOMBOL KEMBALI & NAVIGASI CEPAT --}}
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('tenant.marketplace.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-blue-600 text-xs font-bold transition shadow-2xs active:scale-95">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>

            <div class="flex items-center gap-1.5">
                @if(auth()->check() && $item->user_id === auth()->id())
                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-black uppercase">
                        Barang Saya
                    </span>
                @endif
                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase">
                    100% COD
                </span>
                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold">
                    {{ $item->category_label }}
                </span>
            </div>
        </div>

        {{-- STATUS BANNER JIKA BARANG MILIK SENDIRI --}}
        @if(auth()->check() && $item->user_id === auth()->id())
            @if($item->status === 'pending')
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center justify-between gap-2 shadow-2xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                        <div>
                            <strong class="block">Menunggu Persetujuan Admin</strong>
                            <span class="text-[11px] text-amber-800">Barang ini belum tayang di katalog umum sampai disetujui Superadmin/Admin.</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('tenant.marketplace.edit', $item->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[11px] font-bold hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <button type="button" @click="deleteModalOpen = true" class="px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[11px] font-bold hover:bg-rose-700 transition cursor-pointer">
                            Hapus
                        </button>
                    </div>
                </div>
            @elseif($item->status === 'rejected')
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-center justify-between gap-2 shadow-2xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <strong class="block">Pengajuan Ditolak</strong>
                            <span class="text-[11px] text-rose-800">Barang tidak memenuhi ketentuan marketplace kos.</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('tenant.marketplace.edit', $item->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[11px] font-bold hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <button type="button" @click="deleteModalOpen = true" class="px-2.5 py-1 rounded-lg bg-rose-600 text-white text-[11px] font-bold hover:bg-rose-700 transition cursor-pointer">
                            Hapus
                        </button>
                    </div>
                </div>
            @endif
        @endif

        {{-- 2. GALERI FOTO BARANG --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-3 shadow-2xs space-y-2.5">
            {{-- Foto Utama --}}
            <div class="relative w-full aspect-square rounded-xl bg-slate-100 overflow-hidden border border-slate-200/80">
                <img :src="activeImage" alt="{{ $item->title }}" class="w-full h-full object-cover">

                <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded bg-emerald-600 text-white font-black text-[11px] shadow-xs">
                        COD
                    </span>
                    <span class="px-2 py-0.5 rounded bg-black/60 backdrop-blur-xs text-white font-semibold text-[11px]">
                        {{ $item->condition_label }}
                    </span>
                </div>

                <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-xs text-white text-[10px] font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $item->images->count() ?: 1 }} Foto</span>
                </div>
            </div>

            {{-- Thumbnails jika lebih dari 1 foto --}}
            @if($item->images->count() > 1)
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                    @foreach($item->images as $img)
                        @php
                            $url = (str_starts_with($img->image_path, 'http') || str_starts_with($img->image_path, 'images/')) 
                                ? asset($img->image_path) 
                                : asset('storage/' . $img->image_path);
                        @endphp
                        <button 
                            type="button" 
                            @click="activeImage = '{{ $url }}'"
                            class="w-14 h-14 rounded-lg overflow-hidden border-2 transition shrink-0"
                            :class="activeImage === '{{ $url }}' ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-slate-200 opacity-70 hover:opacity-100'"
                        >
                            <img src="{{ $url }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 3. INFORMASI UTAMA & HARGA --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-2.5">
            <div class="flex items-start justify-between gap-2">
                <h1 class="text-base sm:text-lg font-black text-slate-900 leading-snug">
                    {{ $item->title }}
                </h1>
                <span class="text-[11px] text-slate-400 font-medium shrink-0 flex items-center gap-1 pt-0.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>{{ $item->views_count }}</span>
                </span>
            </div>

            <div class="text-xl font-black text-blue-700">
                {{ $item->formatted_price }}
            </div>

            <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold border border-blue-200/80">
                    Kondisi: {{ $item->condition_label }}
                </span>
                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold border border-slate-200/80">
                    {{ $item->category_label }}
                </span>
                @if($item->city)
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold border border-slate-200/80">
                        {{ $item->city }}
                    </span>
                @endif
            </div>
        </div>

        {{-- 4. TITIK TEMU COD & DETAIL PENJUAL --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-3">
            {{-- Lokasi COD --}}
            <div class="p-3 rounded-xl bg-blue-50/80 border border-blue-200 space-y-1">
                <div class="flex items-center gap-1.5 text-xs font-bold text-blue-900 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    <span>Titik Temu COD Penjual</span>
                </div>
                <p class="text-xs sm:text-sm font-bold text-slate-800">
                    {{ $item->meetup_location }}
                </p>
                <p class="text-[11px] text-slate-500">
                    Disarankan bertemu di area publik aman sekitar kos / kampus.
                </p>
            </div>

            {{-- Profil Penjual --}}
            <div class="flex items-center justify-between gap-3 pt-1">
                <div class="flex items-center gap-2.5 truncate">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 font-black text-sm flex items-center justify-center shrink-0 border border-blue-200">
                        {{ substr($item->user->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <strong class="text-xs font-bold text-slate-900 block truncate">{{ $item->user->name ?? 'Penjual' }}</strong>
                        <span class="text-[10px] text-emerald-600 font-semibold block flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Penjual Terverifikasi
                        </span>
                    </div>
                </div>

                <a href="{{ $waLink }}" target="_blank" class="shrink-0 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition active:scale-95 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Chat WA</span>
                </a>
            </div>
        </div>

        {{-- 5. DESKRIPSI BARANG --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-2">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Deskripsi Barang</span>
            </h3>
            <p class="text-xs sm:text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                {{ $item->description }}
            </p>
        </div>

        {{-- 6. TIPS TRANSAKSI AMAN (COD) --}}
        <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200/80 space-y-2 text-xs">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Panduan Aman COD:</span>
            </div>
            <ul class="space-y-1 text-[11px] text-amber-900/90 pl-1">
                <li>&bull; Bertemu di area terang dan ramai seperti lobby kos atau minimarket.</li>
                <li>&bull; Cek fungsi dan fisik barang sebelum serah terima uang.</li>
                <li>&bull; Jangan transfer uang muka (DP) sebelum barang diterima langsung.</li>
            </ul>
        </div>

        {{-- 7. REKOMENDASI BARANG SERUPA --}}
        @if(isset($relatedItems) && $relatedItems->count() > 0)
            <div class="space-y-2 pt-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Barang Kos Serupa
                    </h3>
                    <a href="{{ route('tenant.marketplace.index', ['kategori' => $item->category]) }}" class="text-[11px] text-blue-600 font-bold hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    @foreach($relatedItems as $rel)
                        <a href="{{ route('tenant.marketplace.show', $rel->slug) }}" class="bg-white rounded-xl border border-slate-200/90 p-2 space-y-1 block hover:border-blue-300 transition shadow-2xs group">
                            <div class="w-full aspect-video rounded-lg bg-slate-100 overflow-hidden">
                                <img src="{{ $rel->primary_image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <h4 class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-600">{{ $rel->title }}</h4>
                            <div class="text-xs font-black text-blue-700">{{ $rel->formatted_price }}</div>
                            <span class="text-[10px] text-slate-500 block truncate">{{ $rel->meetup_location }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 8. STICKY BOTTOM ACTION BAR --}}
        <div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 p-3 shadow-lg flex items-center justify-center">
            <div class="w-full max-w-md flex items-center justify-between gap-3 px-2">
                <div>
                    <span class="text-[10px] text-slate-400 block font-bold">Harga Barang</span>
                    <strong class="text-base font-black text-blue-700 leading-none">
                        {{ $item->formatted_price }}
                    </strong>
                </div>

                @if(auth()->check() && $item->user_id === auth()->id())
                    <div class="flex items-center gap-1.5">
                        @if($item->status !== 'sold')
                            @if($item->status === 'active')
                                <button 
                                    type="button" 
                                    @click="editModalOpen = true"
                                    class="py-2.5 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-extrabold text-xs transition active:scale-95 flex items-center gap-1 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit</span>
                                </button>
                            @else
                                <a 
                                    href="{{ route('tenant.marketplace.edit', $item->id) }}"
                                    class="py-2.5 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-extrabold text-xs transition active:scale-95 flex items-center gap-1 cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit</span>
                                </a>
                            @endif
                        @endif

                        <button 
                            type="button" 
                            @click="deleteModalOpen = true"
                            class="py-2.5 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-extrabold text-xs transition active:scale-95 flex items-center gap-1 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus</span>
                        </button>

                        <a 
                            href="{{ $waLink }}" 
                            target="_blank"
                            class="py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm transition active:scale-95 flex items-center gap-1.5"
                            title="Hubungi Penjual via WhatsApp"
                        >
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Hubungi Penjual (COD)</span>
                        </a>
                    </div>
                @else
                    <a 
                        href="{{ $waLink }}" 
                        target="_blank"
                        class="py-2.5 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm transition active:scale-95 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Hubungi Penjual (COD)</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Spacer for Sticky Bottom Bar --}}
        <div class="h-16"></div>

        {{-- ============================================================== --}}
        {{-- POP UP ALERT MODAL KONFIRMASI HAPUS BARANG                      --}}
        {{-- ============================================================== --}}
        <div 
            x-show="deleteModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                @click.away="deleteModalOpen = false"
                class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Hapus Barang Ini?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus barang <strong>"{{ $item->title }}"</strong>? Tindakan ini tidak dapat dibatalkan dan foto barang akan dihapus permanen.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="deleteModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <form action="{{ route('tenant.marketplace.destroy', $item->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full py-2.5 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-sm transition cursor-pointer"
                        >
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- POP UP ALERT MODAL KONFIRMASI EDIT BARANG AKTIF                 --}}
        {{-- ============================================================== --}}
        <div 
            x-show="editModalOpen" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                @click.away="editModalOpen = false"
                class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Lanjutkan Edit Barang?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Barang ini saat ini berstatus <strong class="text-emerald-600">Aktif</strong>. Mengubah data barang akan mengembalikan status menjadi <strong class="text-amber-600">Pending</strong> untuk ditinjau ulang oleh Admin sebelum tayang kembali.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="editModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <a 
                        href="{{ route('tenant.marketplace.edit', $item->id) }}"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm transition text-center"
                    >
                        Lanjutkan Edit
                    </a>
                </div>
            </div>
        </div>
            </div>
        </div>

        {{-- Spacer for Sticky Bottom Bar --}}
        <div class="h-16"></div>

    </div>

</x-tenant-layout>

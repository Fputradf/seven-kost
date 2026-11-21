<x-tenant-layout title="Barang Saya - Marketplace SevenKost">

    <div 
        class="space-y-4"
        x-data="{
            deleteModalOpen: false,
            deleteActionUrl: '',
            deleteItemTitle: '',

            editModalOpen: false,
            editTargetUrl: '',
            editItemTitle: '',
            editItemStatus: '',

            soldModalOpen: false,
            soldActionUrl: '',
            soldItemTitle: '',

            confirmDelete(url, title) {
                this.deleteActionUrl = url;
                this.deleteItemTitle = title;
                this.deleteModalOpen = true;
            },

            confirmEdit(url, title, status) {
                this.editTargetUrl = url;
                this.editItemTitle = title;
                this.editItemStatus = status;
                if (status === 'active') {
                    this.editModalOpen = true;
                } else {
                    window.location.href = url;
                }
            },

            confirmSold(url, title) {
                this.soldActionUrl = url;
                this.soldItemTitle = title;
                this.soldModalOpen = true;
            }
        }"
    >

        {{-- 1. TOMBOL KEMBALI & AKSI CEPAT --}}
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('tenant.marketplace.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-blue-600 text-xs font-bold transition shadow-2xs active:scale-95">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Katalog Marketplace</span>
            </a>

            <a href="{{ route('tenant.marketplace.create') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Jual Barang</span>
            </a>
        </div>

        {{-- 2. HEADER HERO BARANG SAYA --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-1" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>
            <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                Barang Dagangan Saya
            </h1>
            <p class="text-xs text-blue-100 font-medium">
                Daftar barang yang Anda ajukan di Marketplace SevenKost.
            </p>
        </div>

        {{-- 3. FLASH SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-2xs">
                <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-medium text-[11px] leading-relaxed">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- 4. COUNTER RINGKASAN STATUS --}}
        <div class="grid grid-cols-4 gap-2 text-center">
            <div class="bg-white rounded-xl border border-slate-200/90 p-2 shadow-2xs">
                <span class="text-[10px] text-slate-500 font-bold block">Total</span>
                <strong class="text-sm font-black text-slate-900">{{ $items->total() }}</strong>
            </div>
            <div class="bg-amber-50/70 rounded-xl border border-amber-200/80 p-2 shadow-2xs">
                <span class="text-[10px] text-amber-700 font-bold block">Pending</span>
                <strong class="text-sm font-black text-amber-800">{{ $pendingCount }}</strong>
            </div>
            <div class="bg-emerald-50/70 rounded-xl border border-emerald-200/80 p-2 shadow-2xs">
                <span class="text-[10px] text-emerald-700 font-bold block">Aktif</span>
                <strong class="text-sm font-black text-emerald-800">{{ $activeCount }}</strong>
            </div>
            <div class="bg-slate-50 rounded-xl border border-slate-200/90 p-2 shadow-2xs">
                <span class="text-[10px] text-slate-600 font-bold block">Terjual</span>
                <strong class="text-sm font-black text-slate-700">{{ $soldCount }}</strong>
            </div>
        </div>

        {{-- 5. DAFTAR BARANG SAYA --}}
        @if($items->count() > 0)
            <div class="space-y-3">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-3.5 shadow-2xs space-y-3">
                        <div class="flex items-start gap-3">
                            {{-- Foto Thumbnail --}}
                            <div class="w-20 h-20 rounded-xl bg-slate-100 overflow-hidden border border-slate-200/80 shrink-0 relative">
                                <img src="{{ $item->primary_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @if($item->images->count() > 1)
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[9px] font-bold">
                                        {{ $item->images->count() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Informasi Barang --}}
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider truncate">
                                        {{ $item->category_label }} &bull; {{ $item->condition_label }}
                                    </span>

                                    {{-- Badge Status --}}
                                    @if($item->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black shrink-0 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Pending</span>
                                        </span>
                                    @elseif($item->status === 'active')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black shrink-0 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @elseif($item->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-black shrink-0">
                                            Ditolak
                                        </span>
                                    @elseif($item->status === 'sold')
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-black shrink-0">
                                            Terjual
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 line-clamp-1 leading-snug">
                                    {{ $item->title }}
                                </h3>

                                <div class="text-xs font-black text-blue-700">
                                    {{ $item->formatted_price }}
                                </div>

                                <p class="text-[10px] text-slate-500 line-clamp-1">
                                    📍 {{ $item->meetup_location }}
                                </p>
                            </div>
                        </div>

                        {{-- Tombol Tindakan / Aksi --}}
                        <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100">
                            <span class="text-[10px] text-slate-400">
                                {{ $item->created_at->translatedFormat('d M Y') }}
                            </span>

                            <div class="flex items-center gap-1.5">
                                {{-- Tombol Pratinjau Detail --}}
                                <a 
                                    href="{{ route('tenant.marketplace.show', $item->slug) }}" 
                                    class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition"
                                >
                                    Lihat
                                </a>

                                {{-- Tombol Edit Barang (Pop-up alert jika aktif, atau langsung ke form edit) --}}
                                @if($item->status !== 'sold')
                                    <button 
                                        type="button" 
                                        @click="confirmEdit('{{ route('tenant.marketplace.edit', $item->id) }}', '{{ addslashes($item->title) }}', '{{ $item->status }}')"
                                        class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold transition cursor-pointer flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                @endif

                                {{-- Tombol Tandai Terjual (Jika status aktif) --}}
                                @if($item->status === 'active')
                                    <button 
                                        type="button" 
                                        @click="confirmSold('{{ route('tenant.marketplace.mark-sold', $item->id) }}', '{{ addslashes($item->title) }}')"
                                        class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold transition cursor-pointer"
                                    >
                                        Terjual
                                    </button>
                                @endif

                                {{-- Tombol Hapus Barang (Membuka Pop Up Alert Konfirmasi) --}}
                                <button 
                                    type="button" 
                                    @click="confirmDelete('{{ route('tenant.marketplace.destroy', $item->id) }}', '{{ addslashes($item->title) }}')"
                                    class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition cursor-pointer flex items-center gap-1"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div class="pt-2">
                    {{ $items->links() }}
                </div>
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="bg-white rounded-2xl border border-slate-200/90 p-8 text-center space-y-3 shadow-2xs">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto border border-blue-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-sm font-bold text-slate-800">Belum Ada Barang yang Diajukan</h2>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto">
                        Punya kasur, kipas, atau perabot kos yang tidak terpakai? Jual ke sesama anak kos sekarang secara gratis.
                    </p>
                </div>
                <div class="pt-1">
                    <a href="{{ route('tenant.marketplace.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Jual Barang Sekarang</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- ============================================================== --}}
        {{-- 1. POP UP ALERT MODAL KONFIRMASI HAPUS BARANG                   --}}
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
                {{-- Icon Peringatan Hapus --}}
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>

                {{-- Deskripsi Alert --}}
                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Hapus Barang Dagangan?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus <strong class="text-slate-900" x-text="deleteItemTitle"></strong>? Seluruh data barang dan file foto akan dihapus secara permanen.
                    </p>
                </div>

                {{-- Action Form Buttons --}}
                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="deleteModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST" class="flex-1">
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
        {{-- 2. POP UP ALERT MODAL KONFIRMASI EDIT BARANG AKTIF             --}}
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
                {{-- Icon Edit Alert --}}
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>

                {{-- Deskripsi Alert --}}
                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Lanjutkan Edit Barang?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Barang <strong class="text-slate-900" x-text="editItemTitle"></strong> saat ini berstatus <strong class="text-emerald-600">Aktif</strong>. Jika Anda mengubah datanya, status barang akan kembali menjadi <strong class="text-amber-600">Pending</strong> untuk diverifikasi ulang oleh Admin sebelum tayang kembali.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="editModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <a 
                        :href="editTargetUrl"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm transition text-center"
                    >
                        Lanjutkan Edit
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 3. POP UP ALERT MODAL KONFIRMASI TANDAI TERJUAL                 --}}
        {{-- ============================================================== --}}
        <div 
            x-show="soldModalOpen" 
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
                @click.away="soldModalOpen = false"
                class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Tandai Barang Sudah Terjual?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Barang <strong class="text-slate-900" x-text="soldItemTitle"></strong> akan ditandai Terjual dan calon pembeli tidak dapat menawar barang ini lagi.
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="soldModalOpen = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <form :action="soldActionUrl" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold shadow-sm transition cursor-pointer"
                        >
                            Ya, Terjual
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>

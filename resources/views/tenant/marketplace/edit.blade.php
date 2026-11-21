<x-tenant-layout title="Edit Barang - Marketplace SevenKost">

    <div 
        class="space-y-4"
        x-data="{
            title: @js(old('title', $marketplaceItem->title)),
            category: @js(old('category', $marketplaceItem->category)),
            price: @js(old('price', (string) (int) $marketplaceItem->price)),
            condition: @js(old('condition', $marketplaceItem->condition)),
            meetupLocation: @js(old('meetup_location', $marketplaceItem->meetup_location)),
            city: @js(old('city', $marketplaceItem->city ?? 'Jakarta Selatan')),
            description: @js(old('description', $marketplaceItem->description)),
            imagePreviews: [],
            selectedFiles: [],
            clientError: '',
            showConfirmModal: false,

            handleImageUpload(event) {
                this.clientError = '';
                const incomingFiles = Array.from(event.target.files || []);
                if (!incomingFiles.length) return;

                if (incomingFiles.length > 5) {
                    this.clientError = 'Maksimal 5 foto barang yang dapat diunggah.';
                    return;
                }

                this.selectedFiles = [];
                this.imagePreviews = [];

                for (let i = 0; i < incomingFiles.length; i++) {
                    const file = incomingFiles[i];
                    if (!file.type.startsWith('image/')) {
                        this.clientError = 'Semua file harus berformat gambar (JPG, PNG, atau WebP).';
                        continue;
                    }
                    if (file.size > 5 * 1024 * 1024) {
                        this.clientError = 'Ukuran setiap foto maksimal 5MB: ' + file.name;
                        continue;
                    }
                    this.selectedFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreviews.push(e.target.result);
                    };
                    reader.readAsDataURL(file);
                }

                this.syncFileInput();
            },

            removeImage(index) {
                this.clientError = '';
                this.imagePreviews.splice(index, 1);
                this.selectedFiles.splice(index, 1);
                this.syncFileInput();
            },

            syncFileInput() {
                const input = document.getElementById('images-upload');
                if (!input) return;
                try {
                    const dt = new DataTransfer();
                    this.selectedFiles.forEach(file => dt.items.add(file));
                    input.files = dt.files;
                } catch (e) {
                    console.warn('DataTransfer sync fallback', e);
                }
            },

            formatRupiah(num) {
                if (!num) return 'Rp 0';
                return 'Rp ' + parseInt(num).toLocaleString('id-ID');
            },

            triggerSubmit() {
                this.showConfirmModal = false;
                document.getElementById('edit-item-form').submit();
            }
        }"
    >

        {{-- 1. TOMBOL KEMBALI & NAVIGASI --}}
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('tenant.marketplace.my-items') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-blue-600 text-xs font-bold transition shadow-2xs active:scale-95">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Batal & Kembali</span>
            </a>
            <span class="text-xs font-bold text-slate-500">Edit Postingan</span>
        </div>

        {{-- 2. HEADER GRADIENT FORM --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-1" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>
            <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                Edit Barang Dagangan
            </h1>
            <p class="text-xs text-blue-100 font-medium">
                Perbarui detail barang Anda. Postingan akan ditinjau kembali oleh Admin setelah disimpan.
            </p>
        </div>

        {{-- 3. CLIENT ERROR NOTIFICATION --}}
        <div x-show="clientError" x-cloak class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between gap-2 shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="font-bold" x-text="clientError"></span>
            </div>
            <button type="button" @click="clientError = ''" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
        </div>

        {{-- 4. SERVER VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-2xs">
                <strong class="block font-bold">Mohon perbaiki isian berikut:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 5. FORMULIR EDIT BARANG --}}
        <form 
            id="edit-item-form"
            action="{{ route('tenant.marketplace.update', $marketplaceItem->id) }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-4"
        >
            @csrf
            @method('PUT')

            {{-- 1. FOTO BARANG --}}
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Foto Barang
                </label>

                {{-- Foto Saat Ini (Existing) --}}
                @if($marketplaceItem->images->count() > 0)
                    <div class="space-y-1">
                        <span class="text-[11px] font-semibold text-slate-500">Foto Saat Ini:</span>
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                            @foreach($marketplaceItem->images as $img)
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0">
                                    <img src="{{ $img->image_url }}" alt="Foto" class="w-full h-full object-cover">
                                    @if($loop->first)
                                        <span class="absolute bottom-0 inset-x-0 bg-slate-900/60 text-[9px] text-white text-center font-bold">Utama</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Upload Box Ganti Foto --}}
                <div class="space-y-2 pt-1">
                    <label class="flex flex-col items-center justify-center w-full h-24 rounded-xl border-2 border-dashed border-blue-300 bg-blue-50/50 hover:bg-blue-50 cursor-pointer transition p-3 text-center group">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <span class="text-xs font-bold text-blue-700 mt-1">Unggah Foto Baru (Opsional)</span>
                        <span class="text-[10px] text-slate-500">Jika diunggah, foto lama akan digantikan (Maks. 5 foto)</span>
                        <input 
                            id="images-upload" 
                            type="file" 
                            name="images[]" 
                            multiple 
                            accept="image/png,image/jpeg,image/webp" 
                            @change="handleImageUpload($event)"
                            class="hidden"
                        >
                    </label>

                    {{-- Previews Foto Baru --}}
                    <div x-show="imagePreviews.length > 0" class="space-y-1">
                        <span class="text-[11px] font-semibold text-emerald-600">Foto Baru yang Dipilih:</span>
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                            <template x-for="(preview, idx) in imagePreviews" :key="idx">
                                <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-emerald-300 shrink-0 group">
                                    <img :src="preview" alt="Preview Baru" class="w-full h-full object-cover">
                                    <button 
                                        type="button" 
                                        @click="removeImage(idx)"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] font-bold hover:bg-rose-700 shadow-xs cursor-pointer"
                                        title="Hapus foto ini"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. JUDUL BARANG --}}
            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-slate-800">
                    Nama Barang <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    x-model="title"
                    required
                    maxlength="150"
                    placeholder="Contoh: Kipas Angin Meja Miyako 9 Inch" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400"
                >
            </div>

            {{-- 3. KATEGORI & KONDISI --}}
            <div class="grid grid-cols-2 gap-2.5">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-slate-800">
                        Kategori <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="category" 
                        name="category" 
                        x-model="category"
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium bg-white"
                    >
                        @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}">{{ $catLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="condition" class="block text-xs font-bold text-slate-800">
                        Kondisi <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="condition" 
                        name="condition" 
                        x-model="condition"
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium bg-white"
                    >
                        @foreach($conditions as $condKey => $condLabel)
                            <option value="{{ $condKey }}">{{ $condLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 4. HARGA JUAL --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="price" class="block text-xs font-bold text-slate-800">
                        Harga Jual (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-xs font-extrabold text-blue-700" x-text="formatRupiah(price)"></span>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                        Rp
                    </span>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        x-model="price"
                        required
                        min="0" 
                        step="1000"
                        placeholder="Contoh: 120000" 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-900"
                    >
                </div>
            </div>

            {{-- 5. LOKASI TITIK TEMU COD & KOTA --}}
            <div class="space-y-2.5 pt-1">
                <div class="space-y-1.5">
                    <label for="meetup_location" class="block text-xs font-bold text-slate-800">
                        Titik Temu COD <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="meetup_location" 
                        name="meetup_location" 
                        x-model="meetupLocation"
                        required
                        maxlength="255"
                        placeholder="Contoh: Gerbang Kos Melati / Depan Kampus UI Depok" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400"
                    >
                </div>

                <div class="space-y-1.5">
                    <label for="city" class="block text-xs font-bold text-slate-800">
                        Kota / Wilayah
                    </label>
                    <input 
                        type="text" 
                        id="city" 
                        name="city" 
                        x-model="city"
                        maxlength="100"
                        placeholder="Contoh: Jakarta Selatan, Depok, Bandung" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400"
                    >
                </div>
            </div>

            {{-- 6. DESKRIPSI BARANG --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="description" class="block text-xs font-bold text-slate-800">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[10px] text-slate-400">
                        <span x-text="description.length"></span>/500
                    </span>
                </div>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4" 
                    x-model="description"
                    required
                    maxlength="500"
                    placeholder="Jelaskan spesifikasi barang, minus pemakaian (jika ada), kelengkapan, dan alasan jual..." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500 font-medium placeholder:text-slate-400 leading-relaxed"
                ></textarea>
            </div>

            {{-- 7. TOMBOL SIMPAN DENGAN POP UP TRIGGER --}}
            <div class="pt-2">
                <button 
                    type="button" 
                    @click="showConfirmModal = true"
                    class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm shadow-md transition active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Perubahan Barang</span>
                </button>
            </div>

        </form>

        {{-- ============================================================== --}}
        {{-- POP UP ALERT / MODAL KONFIRMASI SIMPAN PERUBAHAN              --}}
        {{-- ============================================================== --}}
        <div 
            x-show="showConfirmModal" 
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
                @click.away="showConfirmModal = false"
                class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            >
                {{-- Icon Modal --}}
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center mx-auto shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>

                {{-- Konten Pop Up --}}
                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900 leading-snug">
                        Simpan Perubahan Barang?
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Data barang <strong class="text-slate-900" x-text="title"></strong> akan diperbarui. Jika barang sudah aktif sebelumnya, status akan kembali <strong class="text-amber-600">Pending</strong> untuk verifikasi ulang oleh Admin demi keamanan katalog kos.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 pt-1">
                    <button 
                        type="button" 
                        @click="showConfirmModal = false"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="triggerSubmit()"
                        class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm transition cursor-pointer"
                    >
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-tenant-layout>

<x-tenant-layout title="Jual Barang - Marketplace SevenKost">

    <div 
        class="space-y-4"
        x-data="{
            title: @js(old('title', '')),
            category: @js(old('category', 'perabot')),
            price: @js(old('price', '')),
            condition: @js(old('condition', 'bekas_baik')),
            meetupLocation: @js(old('meetup_location', '')),
            description: @js(old('description', '')),
            imagePreviews: [],
            selectedFiles: [],
            clientError: '',

            handleImageUpload(event) {
                this.clientError = '';
                const incomingFiles = Array.from(event.target.files || []);
                if (!incomingFiles.length) return;

                if (this.selectedFiles.length + incomingFiles.length > 5) {
                    this.clientError = 'Maksimal 5 foto barang yang dapat diunggah.';
                    this.syncFileInput();
                    return;
                }

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

            validateAndSubmit(e) {
                this.clientError = '';
                if (this.selectedFiles.length === 0) {
                    e.preventDefault();
                    this.clientError = 'Harap unggah minimal 1 foto barang sebelum memposting.';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return false;
                }
                return true;
            }
        }"
    >

        {{-- 1. TOMBOL KEMBALI --}}
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('tenant.marketplace.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/90 text-slate-700 hover:text-blue-600 text-xs font-bold transition shadow-2xs active:scale-95">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Batal & Kembali</span>
            </a>
            <span class="text-xs font-bold text-slate-500">Posting Barang Bekas</span>
        </div>

        {{-- 2. HEADER GRADIENT FORM --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-1" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>
            <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                Jual Perlengkapan Kos Anda
            </h1>
            <p class="text-xs text-blue-100 font-medium">
                Pasang iklan perlengkapan bekas untuk sesama anak kos (100% COD).
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

        {{-- 5. FORMULIR JUAL BARANG --}}
        <form 
            action="{{ route('tenant.marketplace.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            @submit="validateAndSubmit($event)"
            class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs space-y-4"
        >
            @csrf

            {{-- 1. FOTO BARANG --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Foto Barang <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] font-semibold text-slate-500">
                        <span x-text="selectedFiles.length" class="text-blue-600 font-bold"></span>/5 foto
                    </span>
                </div>

                <div class="space-y-2">
                    {{-- Upload Box --}}
                    <label class="flex flex-col items-center justify-center w-full h-28 rounded-xl border-2 border-dashed border-blue-300 bg-blue-50/50 hover:bg-blue-50 cursor-pointer transition p-3 text-center group">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-blue-700 mt-1.5">Pilih / Tambah Foto Barang</span>
                        <span class="text-[10px] text-slate-500">Maksimal 5 foto (JPG, PNG, WEBP &bull; Maks. 5MB)</span>
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

                    {{-- Previews --}}
                    <div x-show="imagePreviews.length > 0" class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                        <template x-for="(preview, idx) in imagePreviews" :key="idx">
                            <div class="relative w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0 group">
                                <img :src="preview" alt="Preview" class="w-full h-full object-cover">
                                <button 
                                    type="button" 
                                    @click="removeImage(idx)"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] font-bold hover:bg-rose-700 shadow-xs cursor-pointer"
                                    title="Hapus foto ini"
                                >
                                    &times;
                                </button>
                                <span class="absolute bottom-0 inset-x-0 bg-slate-900/60 text-[9px] text-white text-center font-bold" x-show="idx === 0">Utama</span>
                            </div>
                        </template>
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
                        value="{{ old('city', 'Jakarta Selatan') }}"
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

            {{-- 7. SUBMIT BUTTON --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs sm:text-sm shadow-md transition active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Posting Barang Sekarang (Gratis)</span>
                </button>
            </div>

        </form>

    </div>

</x-tenant-layout>
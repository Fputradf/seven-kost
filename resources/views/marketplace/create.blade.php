<x-seven-layout title="Jual Barang di Marketplace - SevenKost">

    {{-- MAIN CONTAINER FORM MARKETPLACE (SESUAI MOCKUP 'Form Marketplace KosNusantara') --}}
    <div 
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
        x-data="{
            title: '{{ old('title', '') }}',
            category: '{{ old('category', 'perabot') }}',
            price: '{{ old('price', '') }}',
            condition: '{{ old('condition', 'bekas_baik') }}',
            meetupLocation: '{{ old('meetup_location', '') }}',
            description: '{{ old('description', '') }}',
            isFeatured: {{ old('is_featured') ? 'true' : 'false' }},
            imagePreviews: [],
            formatRupiah(num) {
                if (!num) return 'Rp 0';
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            },
            handleFileSelect(event) {
                const files = event.target.files;
                if (!files.length) return;
                
                // Allow up to 5 previews
                for (let i = 0; i < files.length && this.imagePreviews.length < 5; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreviews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            },
            removeImage(index) {
                this.imagePreviews.splice(index, 1);
            },
            resetForm() {
                this.title = '';
                this.category = 'perabot';
                this.price = '';
                this.condition = 'bekas_baik';
                this.meetupLocation = '';
                this.description = '';
                this.isFeatured = false;
                this.imagePreviews = [];
                const fileInput = document.getElementById('images-upload');
                if (fileInput) fileInput.value = '';
            }
        }"
    >
        {{-- BREADCRUMBS --}}
        <div class="flex items-center gap-2 text-xs text-slate-500 pb-5">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('marketplace.index') }}" class="hover:text-blue-600">Marketplace</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Jual Barang</span>
        </div>

        {{-- GRID LAYOUT 2 KOLOM (KIRI: FORMULIR, KANAN: LIVE PREVIEW) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- ========================================================================= --}}
            {{-- KOLOM KIRI: FORMULIR JUAL BARANG (LG: COL-8)                              --}}
            {{-- ========================================================================= --}}
            <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-xs space-y-7">
                
                {{-- Form Header Card Gradient (White Text) --}}
                <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25 mb-6" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center shrink-0 shadow-xs border border-white/20">
                            <svg class="w-6 h-6 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                Jual Barang di SevenKost
                            </h1>
                            <p class="text-xs sm:text-sm text-blue-100 mt-0.5">
                                Isi formulir berikut untuk memposting barang kebutuhan anak kos Anda.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Alert Error Validasi jika ada --}}
                @if($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                        <span class="font-bold block">Mohon periksa kesalahan input berikut:</span>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- 1. UNGGAH FOTO BARANG (MULTI-UPLOAD & THUMBNAILS PREVIEW) --}}
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Unggah Foto Barang <span class="text-rose-500">*</span>
                        </label>

                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Drag & Drop Upload Trigger Zone --}}
                            <label class="relative flex flex-col items-center justify-center w-full sm:w-60 h-32 rounded-2xl border-2 border-dashed border-blue-300 bg-blue-50/40 hover:bg-blue-50 cursor-pointer transition group p-4 text-center">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <span class="font-bold text-xs text-blue-700 mt-2">Unggah Foto Barang</span>
                                <span class="text-xs text-slate-500">Klik / seret (maks. 5MB)</span>
                                <input 
                                    id="images-upload" 
                                    type="file" 
                                    name="images[]" 
                                    multiple 
                                    accept="image/png,image/jpeg,image/webp" 
                                    required 
                                    class="hidden" 
                                    @change="handleFileSelect($event)"
                                >
                            </label>

                            {{-- Live Thumbnail Previews via Alpine.js --}}
                            <template x-for="(imgSrc, idx) in imagePreviews" :key="idx">
                                <div class="relative w-28 h-32 rounded-2xl overflow-hidden border border-slate-200 group shadow-xs">
                                    <img :src="imgSrc" alt="Preview Foto" class="w-full h-full object-cover">
                                    <button 
                                        type="button" 
                                        @click="removeImage(idx)" 
                                        class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-slate-900/80 hover:bg-rose-600 text-white flex items-center justify-center text-xs transition"
                                        title="Hapus foto"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>
                        <span class="text-xs text-slate-500 block">Minimal 1 foto, maksimal 5 foto (Format: JPG, PNG, WebP).</span>
                    </div>

                    {{-- 2. FORM FIELDS GRID --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                        
                        {{-- Judul / Nama Barang --}}
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Nama Barang <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                required 
                                x-model="title" 
                                placeholder="Contoh: Kasur Busa Inoac 120x200 cm / Kipas Angin Cosmos" 
                                class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                            >
                        </div>

                        {{-- Kategori Barang --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                name="category" 
                                required 
                                x-model="category" 
                                class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
                            >
                                @foreach($categories as $catKey => $catLabel)
                                    <option value="{{ $catKey }}">{{ $catLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga Barang (Rp) --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Harga Barang (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-sm font-bold text-slate-400 pointer-events-none">
                                    Rp
                                </span>
                                <input 
                                    type="number" 
                                    name="price" 
                                    required 
                                    min="0" 
                                    x-model.number="price" 
                                    placeholder="250000" 
                                    class="w-full text-sm font-medium pl-11 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>
                        </div>

                        {{-- Kondisi Barang --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kondisi Barang <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                name="condition" 
                                required 
                                x-model="condition" 
                                class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3 bg-white"
                            >
                                @foreach($conditions as $condKey => $condLabel)
                                    <option value="{{ $condKey }}">{{ $condLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lokasi Titik Temu (COD) --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Lokasi Titik Temu (COD) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                </span>
                                <input 
                                    type="text" 
                                    name="meetup_location" 
                                    required 
                                    x-model="meetupLocation" 
                                    placeholder="Contoh: Lobby Kos / Area Parkir Kampus UI" 
                                    class="w-full text-sm font-medium pl-10 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                                >
                            </div>
                        </div>

                        {{-- Kota / Wilayah Kampus --}}
                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kota / Wilayah Kampus
                            </label>
                            <input 
                                type="text" 
                                name="city" 
                                value="{{ old('city') }}" 
                                placeholder="Contoh: Depok / Jakarta Selatan / Yogyakarta" 
                                class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 sm:py-3"
                            >
                        </div>

                        {{-- Deskripsi Barang --}}
                        <div class="sm:col-span-2 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Deskripsi Barang <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-xs text-slate-400 font-medium" x-text="(description ? description.length : 0) + ' / 500'"></span>
                            </div>
                            <textarea 
                                name="description" 
                                rows="4" 
                                maxlength="500" 
                                required 
                                x-model="description" 
                                placeholder="Jelaskan kondisi detail barang, lama pemakaian, kelengkapan aksesoris, dan alasan jual..." 
                                class="w-full text-sm font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3.5 leading-relaxed"
                            ></textarea>
                        </div>
                    </div>

                    {{-- 3. TOGGLE IKLAN UNGGULAN (OPSIONAL) --}}
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">
                                    Iklan Unggulan (Opsional)
                                </h4>
                                <p class="text-xs text-slate-500">
                                    Tampilkan barang Anda di posisi teratas selama 7 hari.
                                </p>
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <span class="text-sm font-extrabold text-blue-600">+ Rp 15.000</span>
                            <input type="checkbox" name="is_featured" value="1" x-model="isFeatured" class="w-5 h-5 text-blue-600 rounded-md border-slate-300 focus:ring-blue-500">
                        </label>
                    </div>

                    {{-- 4. PERINGATAN BARANG YANG DILARANG --}}
                    <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200/80 flex items-start gap-3 text-xs text-rose-800">
                        <div class="w-5 h-5 text-rose-600 shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="space-y-0.5">
                            <strong class="font-bold text-xs sm:text-sm block text-rose-900">Barang yang Dilarang</strong>
                            <p class="text-xs text-rose-700 leading-relaxed">
                                Dilarang menjual barang ilegal, berbahaya, senjata tajam, obat terlarang, produk palsu, atau melanggar ketentuan hukum.
                            </p>
                        </div>
                    </div>

                    {{-- 5. ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                        <button 
                            type="button" 
                            @click="resetForm()" 
                            class="w-full sm:w-auto px-6 py-3 rounded-2xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Reset Form</span>
                        </button>

                        <button 
                            type="submit" 
                            class="w-full sm:flex-1 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Posting Barang Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ========================================================================= --}}
            {{-- KOLOM KANAN: PRATINJAU REAL-TIME & STATUS AKUN (LG: COL-4)                --}}
            {{-- ========================================================================= --}}
            <div class="lg:col-span-4 space-y-6 sticky top-24">
                
                {{-- CARD LIVE PREVIEW --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <h3 class="font-bold text-sm sm:text-base text-slate-900 tracking-tight">
                            Pratinjau Iklan Anda
                        </h3>
                    </div>

                    {{-- Live Mockup Card --}}
                    <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                        {{-- Image Preview --}}
                        <div class="relative w-full h-48 bg-slate-100 flex items-center justify-center overflow-hidden">
                            <template x-if="imagePreviews.length > 0">
                                <img :src="imagePreviews[0]" alt="Preview Utama" class="w-full h-full object-cover">
                            </template>
                            <template x-if="imagePreviews.length === 0">
                                <div class="text-center p-4 text-slate-400 space-y-1.5">
                                    <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium block">Foto barang akan tampil di sini</span>
                                </div>
                            </template>

                            {{-- Badge: Hanya COD dan Status Barang --}}
                            <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
                                <div class="px-2 py-0.5 rounded-md bg-emerald-600 text-white font-bold text-xs shadow-xs tracking-wide">
                                    COD
                                </div>
                            </div>

                            <div class="absolute top-2.5 right-2.5">
                                <div class="px-2 py-0.5 rounded-md bg-black/60 text-white font-medium text-xs backdrop-blur-xs" x-text="condition === 'baru' ? 'Baru' : (condition === 'bekas_seperti_baru' ? 'Bekas (Mulus)' : (condition === 'bekas_cukup' ? 'Bekas (Cukup)' : 'Bekas (Baik)'))"></div>
                            </div>
                        </div>

                        {{-- Body Preview --}}
                        <div class="p-4 space-y-2">
                            <h4 class="font-bold text-sm sm:text-base text-slate-900 line-clamp-1" x-text="title || 'Kasur Busa 120x200 cm'"></h4>
                            
                            <div class="text-base sm:text-lg font-black text-blue-600" x-text="formatRupiah(price) || 'Rp 250.000'"></div>

                            <div class="flex items-center gap-1.5 text-xs text-slate-500 truncate">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                <span x-text="meetupLocation || 'Lobby Kos / Area Parkir Kampus'"></span>
                            </div>

                            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-xs">{{ auth()->user()->name ?? 'Penjual' }}</span>
                                    <span class="text-blue-500 text-xs font-bold">✓</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD STATUS AKUN / VERIFIKASI IDENTITAS --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 font-bold border border-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-sm font-bold text-slate-900">
                                Transaksi COD Aman
                            </h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                SevenKost tidak meminta komisi atau memotong pembayaran Anda. Bertemulah di lokasi ramai dan periksa kondisi barang sebelum menerima pembayaran tunai.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-seven-layout>

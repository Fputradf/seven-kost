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
                
                {{-- Form Header --}}
                <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0 shadow-xs">
                        🏪
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            Jual Barang di SevenKost
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            Isi formulir berikut untuk memposting barang kebutuhan anak kos Anda.
                        </p>
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
                                <span class="text-[10px] text-slate-400">Klik / seret (maks. 5MB)</span>
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
                        <span class="text-[11px] text-slate-400 block">Minimal 1 foto, maksimal 5 foto (Format: JPG, PNG, WebP).</span>
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
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
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
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 bg-white"
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
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400 pointer-events-none">
                                    Rp
                                </span>
                                <input 
                                    type="number" 
                                    name="price" 
                                    required 
                                    min="0" 
                                    x-model.number="price" 
                                    placeholder="250000" 
                                    class="w-full text-xs font-medium pl-10 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
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
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 bg-white"
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
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                                    📍
                                </span>
                                <input 
                                    type="text" 
                                    name="meetup_location" 
                                    required 
                                    x-model="meetupLocation" 
                                    placeholder="Contoh: Lobby Kos / Area Parkir Kampus UI" 
                                    class="w-full text-xs font-medium pl-9 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
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
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5"
                            >
                        </div>

                        {{-- Deskripsi Barang --}}
                        <div class="sm:col-span-2 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Deskripsi Barang <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-slate-400 font-medium" x-text="(description ? description.length : 0) + ' / 500'"></span>
                            </div>
                            <textarea 
                                name="description" 
                                rows="4" 
                                maxlength="500" 
                                required 
                                x-model="description" 
                                placeholder="Jelaskan kondisi detail barang, lama pemakaian, kelengkapan aksesoris, dan alasan jual..." 
                                class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 leading-relaxed"
                            ></textarea>
                        </div>
                    </div>

                    {{-- 3. TOGGLE IKLAN UNGGULAN (OPSIONAL) --}}
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">⭐</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">
                                    Iklan Unggulan (Opsional)
                                </h4>
                                <p class="text-[11px] text-slate-500">
                                    Tampilkan barang Anda di posisi teratas selama 7 hari.
                                </p>
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <span class="text-xs font-black text-blue-600">+ Rp 15.000</span>
                            <input type="checkbox" name="is_featured" value="1" x-model="isFeatured" class="w-5 h-5 text-blue-600 rounded-md border-slate-300 focus:ring-blue-500">
                        </label>
                    </div>

                    {{-- 4. PERINGATAN BARANG YANG DILARANG --}}
                    <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200/80 flex items-start gap-3 text-xs text-rose-800">
                        <span class="text-lg shrink-0">⚠️</span>
                        <div class="space-y-0.5">
                            <strong class="font-bold block">Barang yang Dilarang</strong>
                            <p class="text-[11px] text-rose-700 leading-relaxed">
                                Dilarang menjual barang ilegal, berbahaya, senjata tajam, obat terlarang, produk palsu, atau melanggar hukum RI.
                            </p>
                        </div>
                    </div>

                    {{-- 5. ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-3">
                        <button 
                            type="button" 
                            @click="resetForm()" 
                            class="w-full sm:w-auto px-6 py-3 rounded-2xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2"
                        >
                            <span>🗑️</span>
                            <span>Reset Form</span>
                        </button>

                        <button 
                            type="submit" 
                            class="w-full sm:flex-1 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-black shadow-lg shadow-blue-500/25 transition text-center flex items-center justify-center gap-2"
                        >
                            <span>✈️</span>
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
                        <span class="text-base text-blue-600">👁️</span>
                        <h3 class="font-black text-sm text-slate-900 tracking-tight">
                            Pratinjau Iklan Anda
                        </h3>
                    </div>

                    {{-- Live Mockup Card --}}
                    <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                        {{-- Image Preview --}}
                        <div class="relative w-full h-44 bg-slate-100 flex items-center justify-center overflow-hidden">
                            <template x-if="imagePreviews.length > 0">
                                <img :src="imagePreviews[0]" alt="Preview Utama" class="w-full h-full object-cover">
                            </template>
                            <template x-if="imagePreviews.length === 0">
                                <div class="text-center p-4 text-slate-400 space-y-1">
                                    <div class="text-3xl">🛏️</div>
                                    <span class="text-[11px] font-medium block">Foto barang akan tampil di sini</span>
                                </div>
                            </template>

                            {{-- Badge: Hanya COD dan Status Barang --}}
                            <div class="absolute top-2.5 left-2.5 flex flex-wrap gap-1">
                                <div class="px-2 py-0.5 rounded-md bg-emerald-600 text-white font-bold text-[9px] shadow-xs">
                                    COD
                                </div>
                            </div>

                            <div class="absolute top-2.5 right-2.5">
                                <div class="px-2 py-0.5 rounded-md bg-black/60 text-white font-semibold text-[9px]" x-text="condition === 'baru' ? 'Baru' : (condition === 'bekas_seperti_baru' ? 'Bekas (Mulus)' : (condition === 'bekas_cukup' ? 'Bekas (Cukup)' : 'Bekas (Baik)'))"></div>
                            </div>
                        </div>

                        {{-- Body Preview --}}
                        <div class="p-4 space-y-2">
                            <h4 class="font-extrabold text-sm text-slate-900 line-clamp-1" x-text="title || 'Kasur Busa 120x200 cm'"></h4>
                            
                            <div class="text-base font-black text-blue-600" x-text="formatRupiah(price) || 'Rp 250.000'"></div>

                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500 truncate">
                                <span>📍</span>
                                <span x-text="meetupLocation || 'Lobby Kos / Area Parkir Kampus'"></span>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-[10px] flex items-center justify-center">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-800 text-[11px]">{{ auth()->user()->name ?? 'Penjual' }}</span>
                                    <span class="text-blue-500 text-xs">✓</span>
                                </div>
                                <span class="text-rose-500">🤍</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD STATUS AKUN / VERIFIKASI IDENTITAS --}}
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 font-bold">
                            🛡️
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs sm:text-sm font-extrabold text-slate-900">
                                Transaksi COD Aman
                            </h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                SevenKost tidak meminta komisi atau memotong pembayaran Anda. Bertemulah di lokasi ramai dan periksa kondisi barang sebelum menerima pembayaran tunai.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-seven-layout>

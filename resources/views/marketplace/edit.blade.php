<x-seven-layout title="Edit Barang Marketplace - SevenKost">

    <div 
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
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
            showConfirmModal: false,
            formatRupiah(num) {
                if (!num) return 'Rp 0';
                return 'Rp ' + Number(num).toLocaleString('id-ID');
            },
            handleFileSelect(event) {
                const files = event.target.files;
                if (!files.length) return;
                
                this.imagePreviews = [];
                for (let i = 0; i < files.length && i < 5; i++) {
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
            triggerSubmit() {
                this.showConfirmModal = false;
                document.getElementById('edit-item-form').submit();
            }
        }"
    >
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('marketplace.my-items') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Barang Saya</span>
            </a>
            <span class="text-xs font-semibold text-slate-500">Edit Postingan</span>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">Edit Barang Dagangan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui informasi barang yang Anda jual. Postingan akan ditinjau kembali oleh Admin setelah disimpan.</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1">
                    <strong class="block font-bold">Harap perbaiki isian berikut:</strong>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form 
                id="edit-item-form"
                action="{{ route('marketplace.update', $marketplaceItem->id) }}" 
                method="POST" 
                enctype="multipart/form-data" 
                class="space-y-5"
            >
                @csrf
                @method('PUT')

                {{-- Foto Barang --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Foto Barang</label>
                    @if($marketplaceItem->images->count() > 0)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            @foreach($marketplaceItem->images as $img)
                                <div class="w-20 h-20 rounded-xl overflow-hidden border border-slate-200 shrink-0">
                                    <img src="{{ $img->image_url }}" alt="Foto" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <input 
                        type="file" 
                        name="images[]" 
                        id="images-upload" 
                        multiple 
                        accept="image/jpeg,image/png,image/webp" 
                        @change="handleFileSelect($event)"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                    >
                    <span class="text-[11px] text-slate-400 block">Kosongkan jika tidak ingin mengganti foto saat ini (Maks. 5 foto baru).</span>
                </div>

                {{-- Judul --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800">Judul Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" x-model="title" required maxlength="150" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Kategori & Kondisi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-800">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" x-model="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                            @foreach($categories as $catKey => $catLabel)
                                <option value="{{ $catKey }}">{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-800">Kondisi <span class="text-rose-500">*</span></label>
                        <select name="condition" x-model="condition" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                            @foreach($conditions as $condKey => $condLabel)
                                <option value="{{ $condKey }}">{{ $condLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Harga --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-800">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                        <span class="text-xs font-bold text-blue-700" x-text="formatRupiah(price)"></span>
                    </div>
                    <input type="number" name="price" x-model="price" required min="0" step="1000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-bold">
                </div>

                {{-- Lokasi & Kota --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-800">Titik Temu COD <span class="text-rose-500">*</span></label>
                        <input type="text" name="meetup_location" x-model="meetupLocation" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-800">Kota</label>
                        <input type="text" name="city" x-model="city" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800">Deskripsi Barang <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="4" x-model="description" required maxlength="500" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm leading-relaxed"></textarea>
                </div>

                <div class="pt-4">
                    <button 
                        type="button" 
                        @click="showConfirmModal = true"
                        class="w-full py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-md transition cursor-pointer"
                    >
                        Simpan Perubahan Barang
                    </button>
                </div>
            </form>
        </div>

        {{-- POP UP ALERT KONFIRMASI SIMPAN --}}
        <div 
            x-show="showConfirmModal" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        >
            <div 
                @click.away="showConfirmModal = false"
                class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4"
            >
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="text-center space-y-2">
                    <h3 class="text-base font-black text-slate-900">Simpan Perubahan Barang?</h3>
                    <p class="text-xs text-slate-600">Perubahan pada barang Anda akan disimpan. Jika sudah aktif, status akan kembali Pending untuk diverifikasi admin.</p>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="showConfirmModal = false" class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Batal</button>
                    <button type="button" @click="triggerSubmit()" class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>
</x-seven-layout>

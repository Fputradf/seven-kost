<x-tenant-layout title="Lapor Kendala Baru">

    <div class="space-y-6 sm:space-y-8 max-w-4xl mx-auto">

        {{-- Header Card --}}
        <div class="rounded-2xl p-6 sm:p-7 text-white shadow-md shadow-blue-900/15 border border-blue-400/25" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white font-sans">
                        Lapor Kendala / Kerusakan Fasilitas
                    </h1>
                </div>

                <a href="{{ route('tenant.complaints.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-white/25 hover:bg-white/15 text-white text-xs font-semibold transition self-start sm:self-auto">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>

        {{-- Form Box --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('tenant.complaints.store') }}" class="space-y-6">
                @csrf

                {{-- 1. Pilihan Kos --}}
                <div>
                    <label for="kost_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Pilih Kos Terkait <span class="text-rose-500">*</span>
                    </label>

                    @if($myBookings->count() > 0)
                        <select name="kost_id" id="kost_id" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-slate-400 focus:ring-slate-400">
                            <option value="">-- Pilih Kos --</option>
                            @php
                                $uniqueKosts = $myBookings->pluck('kost')->unique('id');
                            @endphp
                            @foreach($uniqueKosts as $kost)
                                <option value="{{ $kost->id }}" {{ (old('kost_id', $selectedKostId) == $kost->id) ? 'selected' : '' }}>
                                    {{ $kost->name }} ({{ $kost->regency->name ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-xs text-slate-700 mb-2">
                            Catatan: Anda belum memiliki riwayat booking kos. Masukkan ID kos yang ingin dilaporkan.
                        </div>
                        <input type="number" name="kost_id" id="kost_id" value="{{ old('kost_id', $selectedKostId) }}" placeholder="ID Kos" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-slate-400 focus:ring-slate-400">
                    @endif

                    @error('kost_id')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Kategori Kendala --}}
                <div>
                    <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kategori Masalah <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="category" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-slate-400 focus:ring-slate-400">
                        <option value="listrik_elektronik" {{ old('category') === 'listrik_elektronik' ? 'selected' : '' }}>Listrik & Elektronik (AC bocor / tidak dingin, Lampu mati, Stopkontak)</option>
                        <option value="kamar_mandi" {{ old('category') === 'kamar_mandi' ? 'selected' : '' }}>Kamar Mandi & Pipa (Air mati, Kran bocor, Kloset mampet, Shower)</option>
                        <option value="kamar" {{ old('category') === 'kamar' ? 'selected' : '' }}>Fasilitas Kamar (Pintu/Kunci rusak, Jendela, Kasur, Lemari)</option>
                        <option value="kebersihan" {{ old('category') === 'kebersihan' ? 'selected' : '' }}>Kebersihan & Lingkungan (Tempat sampah penuh, Koridor kotor)</option>
                        <option value="keamanan" {{ old('category') === 'keamanan' ? 'selected' : '' }}>Keamanan & Parkir (Pagar gerbang, Kunci akses, Parkiran motor)</option>
                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya (Wifi gangguan, Gangguan suara bising, dll)</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Judul / Ringkasan Kendala --}}
                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kendala Utama / Subjek <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="reason" id="reason" required value="{{ old('reason') }}" 
                           placeholder="Contoh: AC Kamar Bocor Menetes & Mengeluarkan Suara Bising"
                           class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-slate-400 focus:ring-slate-400">
                    @error('reason')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Rincian & Deskripsi Lengkap --}}
                <div>
                    <label for="details" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi Rinci Kendala <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="details" id="details" rows="4" required
                              placeholder="Jelaskan kondisi kendala secara detail (sejak kapan terjadi, nomor kamar, dampak yang dialami)..."
                              class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-slate-400 focus:ring-slate-400">{{ old('details') }}</textarea>
                    @error('details')
                        <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('tenant.complaints.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition cursor-pointer">
                        Kirim Laporan Keluhan
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-tenant-layout>

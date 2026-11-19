<x-tenant-layout title="Lapor Kendala Baru" :backUrl="route('tenant.complaints.index')">

    <div class="space-y-4">

        {{-- Top Card --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 flex items-center justify-between gap-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div>
                <span class="text-xs font-bold text-sky-200 uppercase tracking-wider block">Form Pengaduan</span>
                <h2 class="text-base sm:text-lg font-black text-white !text-white leading-tight" style="color: #ffffff !important;">
                    Lapor Kendala / Kerusakan Fasilitas
                </h2>
                <p class="text-xs text-white/95 mt-0.5">Laporan diteruskan langsung ke pemilik kos</p>
            </div>
            <a href="{{ route('tenant.complaints.index') }}" class="shrink-0 px-2.5 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-semibold transition border border-white/30 active:scale-95">
                Batal
            </a>
        </div>

        {{-- Form Box --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs">
            <form method="POST" action="{{ route('tenant.complaints.store') }}" class="space-y-3.5" enctype="multipart/form-data">
                @csrf

                {{-- 1. Pilihan Kos --}}
                <div class="space-y-1">
                    <label for="kost_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pilih Kos Terkait <span class="text-rose-500">*</span>
                    </label>

                    @if($myBookings->count() > 0)
                        <select name="kost_id" id="kost_id" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
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
                        <input type="number" name="kost_id" id="kost_id" value="{{ old('kost_id', $selectedKostId) }}" placeholder="ID Kos" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    @endif

                    @error('kost_id')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Kategori Kendala --}}
                <div class="space-y-1">
                    <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Kategori Masalah <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="category" required class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="listrik_elektronik" {{ old('category') === 'listrik_elektronik' ? 'selected' : '' }}>Listrik / AC / Lampu</option>
                        <option value="kamar_mandi" {{ old('category') === 'kamar_mandi' ? 'selected' : '' }}>Kamar Mandi / Pipa Air</option>
                        <option value="kebersihan" {{ old('category') === 'kebersihan' ? 'selected' : '' }}>Kebersihan Lingkungan</option>
                        <option value="keamanan" {{ old('category') === 'keamanan' ? 'selected' : '' }}>Keamanan / Kunci Kamar</option>
                        <option value="kamar" {{ old('category') === 'kamar' ? 'selected' : '' }}>Kasur / Lemari / Fasilitas Kamar</option>
                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Judul / Ringkasan Masalah --}}
                <div class="space-y-1">
                    <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Ringkasan Kendala <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason') }}" required placeholder="Contoh: AC Kamar Tidak Dingin dan Berisik" class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500 py-2.5">
                    @error('reason')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Detail Kendala --}}
                <div class="space-y-1">
                    <label for="details" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Deskripsi Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="details" id="details" rows="3" required placeholder="Jelaskan detail kendala yang dialami secara jelas agar pemilik dapat segera menindaklanjuti..." class="w-full rounded-xl border-slate-200 text-xs sm:text-sm focus:border-blue-500 focus:ring-blue-500 p-2.5 leading-relaxed">{{ old('details') }}</textarea>
                    @error('details')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Tombol Submit --}}
                <div class="pt-2 flex gap-2">
                    <a href="{{ route('tenant.complaints.index') }}" class="w-1/3 py-2.5 px-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="w-2/3 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs text-center transition active:scale-95">
                        Kirim Laporan Keluhan
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-tenant-layout>

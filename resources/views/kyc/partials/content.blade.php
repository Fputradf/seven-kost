<div class="space-y-6">

    {{-- 1. HERO CARD GRADIENT DENGAN BANNER OVERLAY ELEGAN --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-7 text-white shadow-lg border border-blue-400/25">
        <img src="{{ asset('images/frontend/banner-jakarta-wide.jpg') }}" alt="Banner KYC" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(10, 37, 72, 0.95) 0%, rgba(18, 64, 122, 0.90) 55%, rgba(29, 78, 216, 0.82) 100%);"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1.5 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-white/15 border border-white/20 text-white backdrop-blur-xs">
                    <span class="h-2 w-2 rounded-full bg-blue-300 animate-pulse"></span>
                    <span>Verifikasi Identitas Resmi (KYC)</span>
                    <span>•</span>
                    <span>Keamanan &amp; Integritas Akun</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                    Verifikasi Identitas Pengguna
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                    Lengkapi data KTP, foto selfie, dan berkas pendukung Anda untuk memastikan keabsahan akun dan keamanan transaksi di SevenKost.
                </p>
            </div>

            <div class="h-16 w-16 rounded-2xl bg-white/15 border border-white/20 backdrop-blur-md text-white flex items-center justify-center shrink-0 shadow-inner">
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if (session('warning'))
        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-semibold flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- JIKA STATUS KYC = VERIFIED --}}
    @if ($kyc && $kyc->isVerified())
        <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-xs text-center space-y-5">
            <div class="h-20 w-20 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center ring-8 ring-emerald-50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>

            <div class="space-y-1">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">
                    TERVERIFIKASI RESMI
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Identitas Anda Telah Terverifikasi</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Selamat! Akun Anda telah lolos pemeriksaan identitas KYC SevenKost. Anda sekarang memiliki akses penuh ke seluruh fitur dan layanan.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 max-w-md mx-auto text-left text-xs space-y-2 font-mono">
                <div class="flex justify-between">
                    <span class="text-slate-400">NIK:</span>
                    <span class="font-bold text-slate-800">{{ substr($kyc->nik, 0, 6) }}******{{ substr($kyc->nik, -4) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Nama KTP:</span>
                    <span class="font-bold text-slate-800">{{ $kyc->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Terverifikasi Pada:</span>
                    <span class="font-bold text-slate-800">{{ $kyc->reviewed_at?->translatedFormat('d M Y, H:i') ?? 'Baru saja' }}</span>
                </div>
            </div>

            <div class="pt-2">
                @php
                    $dashRoute = route('dashboard');
                    if (auth()->user()?->hasRole('mitra')) {
                        $dashRoute = route('partner.dashboard');
                    } elseif (auth()->user()?->hasRole('pemilik')) {
                        $dashRoute = route('owner.dashboard');
                    }
                @endphp
                <a 
                    href="{{ $dashRoute }}" 
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition cursor-pointer"
                >
                    <span>Lanjut ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    {{-- JIKA STATUS KYC = PENDING (SEDANG DITINJAU) --}}
    @elseif ($kyc && $kyc->isPending())
        <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-xs text-center space-y-5">
            <div class="h-20 w-20 rounded-full bg-blue-100 text-blue-600 mx-auto flex items-center justify-center ring-8 ring-blue-50">
                <svg class="w-10 h-10 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <div class="space-y-1">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800">
                    SEDANG DITINJAU ADMIN
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Dokumen Verifikasi Sedang Diproses</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                    Terima kasih telah mengunggah data KYC. Tim verifikasi SevenKost sedang memeriksa keabsahan KTP, foto selfie, dan berkas pendukung Anda.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200/80 max-w-md mx-auto text-left text-xs space-y-2">
                <div class="flex items-center gap-2 text-blue-900 font-bold">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Estimasi Waktu Peninjauan:</span>
                </div>
                <p class="text-[11px] text-blue-800/90 leading-relaxed">
                    Proses review berkas membutuhkan waktu sekitar <strong>1x24 jam kerja</strong>. Seluruh fitur operasional akan terbuka otomatis setelah verifikasi disetujui.
                </p>
                <div class="pt-2 border-t border-blue-200/60 text-[11px] text-blue-700 font-mono">
                    NIK Pengajuan: <strong>{{ substr($kyc->nik, 0, 6) }}******{{ substr($kyc->nik, -4) }}</strong>
                </div>
            </div>

            <div class="pt-2 flex justify-center gap-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </div>

    {{-- FORM PENGAJUAN KYC (BARU ATAU JIKA DITOLAK) --}}
    @else
        @if ($kyc && $kyc->isRejected())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
                <strong class="font-bold flex items-center gap-1.5 text-rose-950">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Pengajuan Sebelumnya Ditolak</span>
                </strong>
                <p class="text-[11px] text-rose-900 leading-relaxed">
                    Alasan Penolakan: <strong>{{ $kyc->rejection_reason ?: 'Dokumen tidak terbaca jelas atau data tidak sesuai.' }}</strong>
                </p>
                <p class="text-[10px] text-rose-700">Silakan periksa kembali foto dokumen Anda dan ajukan ulang di bawah ini.</p>
            </div>
        @endif

        {{-- Form Box --}}
        <div class="rounded-3xl bg-white border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 uppercase tracking-wider">
                        Wajib untuk Akun Baru
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Formulir Dokumen Identitas (KYC)</h2>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Harap mengisi data sesuai dokumen kependudukan asli Anda demi validitas dan keamanan akun.
                    </p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            {{-- Form KYC --}}
            <form 
                method="POST" 
                action="{{ route('kyc.store') }}" 
                enctype="multipart/form-data" 
                class="space-y-6"
                x-data="{
                    ktpPreview: @js($kyc?->ktp_url),
                    selfiePreview: @js($kyc?->selfie_url)
                }"
            >
                @csrf

                {{-- Bagian 1: Identitas Kependudukan --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">1. Data Kependudukan Sesuai KTP</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- NIK (16 Digit & UNIQUE) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                <span>NIK (Nomor Induk Kependudukan) *</span>
                                <span class="text-[10px] text-slate-400 font-mono">16 Digit Angka</span>
                            </label>
                            <input 
                                type="text" 
                                name="nik" 
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                value="{{ old('nik', $kyc?->nik) }}"
                                placeholder="Contoh: 3171xxxxxxxxxxxx"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition tracking-wider"
                            >
                            @error('nik')
                                <p class="text-rose-600 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                            <p class="text-[10px] text-slate-400 mt-1">Satu NIK hanya dapat didaftarkan pada 1 akun SevenKost (Unique).</p>
                        </div>

                        {{-- Nama Lengkap Sesuai KTP --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap (Sesuai KTP) *</label>
                            <input 
                                type="text" 
                                name="full_name" 
                                value="{{ old('full_name', $kyc?->full_name ?? $user->name) }}"
                                placeholder="Nama lengkap tanpa gelar"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                            @error('full_name')
                                <p class="text-rose-600 text-[11px] font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bagian 2: Dokumen Foto KTP & Selfie --}}
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">2. Unggah Foto Dokumen KTP &amp; Selfie Wajah</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Upload Foto KTP --}}
                        <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-3">
                            <label class="block text-xs font-bold text-slate-800">Foto KTP Asli *</label>

                            {{-- Pratinjau KTP --}}
                            <div class="relative h-40 w-full rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center">
                                <template x-if="ktpPreview">
                                    <img :src="ktpPreview" alt="Pratinjau KTP" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!ktpPreview">
                                    <div class="text-center p-3 text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[11px] font-semibold block">Belum ada foto KTP</span>
                                        <span class="text-[10px] text-slate-400">Pastikan tulisan terbaca jelas</span>
                                    </div>
                                </template>
                            </div>

                            <input 
                                type="file" 
                                name="ktp_image" 
                                accept="image/*"
                                {{ $kyc?->ktp_path ? '' : 'required' }}
                                @change="const f = $event.target.files[0]; if(f) ktpPreview = URL.createObjectURL(f)"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                            >
                            @error('ktp_image')
                                <p class="text-rose-600 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload Foto Selfie Pegang KTP --}}
                        <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-3">
                            <label class="block text-xs font-bold text-slate-800">Foto Selfie Wajah / Bersama KTP *</label>

                            {{-- Pratinjau Selfie --}}
                            <div class="relative h-40 w-full rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center">
                                <template x-if="selfiePreview">
                                    <img :src="selfiePreview" alt="Pratinjau Selfie" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!selfiePreview">
                                    <div class="text-center p-3 text-slate-400">
                                        <svg class="w-8 h-8 mx-auto mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[11px] font-semibold block">Belum ada foto selfie</span>
                                        <span class="text-[10px] text-slate-400">Wajah terlihat jelas & terang</span>
                                    </div>
                                </template>
                            </div>

                            <input 
                                type="file" 
                                name="selfie_image" 
                                accept="image/*"
                                {{ $kyc?->selfie_path ? '' : 'required' }}
                                @change="const f = $event.target.files[0]; if(f) selfiePreview = URL.createObjectURL(f)"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                            >
                            @error('selfie_image')
                                <p class="text-rose-600 text-[10px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bagian 3: Berkas Pendukung Usaha / Keabsahan --}}
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">3. Berkas Pendukung (Khusus Mitra Usaha / Pemilik)</h3>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $user->hasRole('mitra') ? 'Sangat Dianjurkan Mitra' : ($user->hasRole('pemilik') ? 'Dianjurkan Pemilik' : 'Opsional Penghuni') }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Unggah Berkas Pendukung Usaha / Identitas Tambahan</label>
                            <p class="text-[11px] text-slate-500 mb-2 leading-relaxed">
                                Untuk <strong>Mitra Layanan</strong>: NIB, Surat Izin Usaha, Foto Outlet/Proses Kerja, atau Sertifikat Keahlian.<br>
                                Untuk <strong>Pemilik Kos</strong>: Bukti Kepemilikan / Surat Kuasa Pengelolaan / PBB.<br>
                                Untuk <strong>Penghuni</strong>: Kartu Tanda Mahasiswa (KTM) atau Surat Keterangan Kerja.
                            </p>
                            <input 
                                type="file" 
                                name="business_document" 
                                accept=".pdf,image/*"
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            >
                            <span class="text-[10px] text-slate-400 block mt-1">Format: PDF, JPG, PNG (maksimal 5MB).</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Nama Berkas</label>
                            <input 
                                type="text" 
                                name="business_document_name" 
                                value="{{ old('business_document_name', $kyc?->business_document_name) }}"
                                placeholder="Contoh: NIB Toko Laundry / Sertifikat Teknisi AC / Bukti Kepemilikan"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>

                        @if ($kyc?->business_document_path)
                            <div class="text-[11px] text-blue-700 font-semibold pt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Berkas sebelumnya telah terunggah: {{ $kyc->business_document_name ?: 'Berkas Pendukung' }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-[11px] text-slate-400 text-center sm:text-left">
                        Dengan mengirimkan berkas ini, Anda menjamin bahwa seluruh data dan dokumen adalah asli dan sah.
                    </p>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition cursor-pointer shrink-0"
                    >
                        Kirim Pengajuan Verifikasi (KYC)
                    </button>
                </div>

            </form>
        </div>
    @endif

</div>

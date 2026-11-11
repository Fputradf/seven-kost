<x-seven-layout title="Kebijakan Privasi (UU PDP No. 27/2022) - SevenKost" description="Kebijakan pelindungan dan pemrosesan data pribadi pengguna platform SevenKost sesuai regulasi Undang-Undang No. 27 Tahun 2022.">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 py-3 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Kebijakan Privasi Data</span>
            </nav>
            <div class="text-[11px] text-emerald-600 font-medium hidden sm:block">
                Kepatuhan UU No. 27 Tahun 2022 (UU PDP)
            </div>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        {{-- Hero Header Card (SevenKost Blue Style) --}}
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-6 sm:p-8 md:p-10 shadow-lg shadow-blue-950/15 border border-white/15">
            {{-- Decorative Accents --}}
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute top-0 right-1/3 w-36 h-36 bg-sky-300/15 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-emerald-200">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Standar Pelindungan Data Pribadi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                    Kebijakan Privasi Pengguna
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed max-w-xl">
                    SevenKost menjamin keamanan dan kerahasiaan data pribadi Anda sesuai dengan prinsip tata kelola data yang transparan dan akuntabel di Indonesia.
                </p>
                <div class="pt-1 flex flex-wrap items-center gap-4 text-[11px] text-blue-200">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Diperbarui: {{ date('d F Y') }}
                    </span>
                    <span class="flex items-center gap-1.5 text-emerald-200">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Enkripsi Standar Industri Aktif
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation Tab Switcher --}}
        <div class="bg-white p-1.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-1">
            <a href="{{ route('terms.conditions') }}" class="flex-1 py-2.5 px-4 rounded-xl text-center text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Syarat & Ketentuan Layanan</span>
            </a>
            <a href="{{ route('privacy.policy') }}" class="flex-1 py-2.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold bg-blue-600 text-white shadow-xs transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Kebijakan Privasi (UU PDP)</span>
            </a>
        </div>

        {{-- Content Card Form --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-xs space-y-8 text-slate-700">
            
            {{-- Quick Summary Highlight --}}
            <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-slate-700 space-y-2">
                <div class="flex items-center gap-2 text-emerald-800 font-bold text-xs sm:text-sm">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Komitmen Privasi Tanpa Kompromi</span>
                </div>
                <p class="text-xs leading-relaxed text-slate-600">
                    SevenKost tidak pernah menjual data pribadi Anda kepada pihak ketiga manapun. Data yang dikumpulkan semata-mata digunakan untuk memudahkan komunikasi hunian dan menjamin keaslian properti kos yang disewa.
                </p>
            </div>

            {{-- Bagian 1 --}}
            <section class="space-y-3 pt-2">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        1
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Data Pribadi yang Dikumpulkan
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>Kami memproses kategori data pribadi berikut secara proporsional:</p>
                    <ul class="space-y-2 pt-1">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 flex-shrink-0"></span>
                            <span><strong>Data Identitas & Akun:</strong> Nama lengkap, alamat email aktif, nomor WhatsApp/telepon seluler, dan kata sandi terenkripsi (BCrypt).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 flex-shrink-0"></span>
                            <span><strong>Data Properti Pemilik:</strong> Alamat detail properti, titik koordinat peta (latitude/longitude), foto kamar, fasilitas, dan kontak pengelola.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 flex-shrink-0"></span>
                            <span><strong>Data Transaksi Reservasi:</strong> Durasi sewa yang dipilih, tanggal check-in, dan histori status booking antar pengguna.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 2 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        2
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Tujuan Pemrosesan Data Pribadi
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>Pemrosesan data pribadi Anda dilakukan atas dasar hukum yang sah untuk:</p>
                    <ul class="space-y-2 pt-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Menghubungkan calon penghuni langsung dengan pemilik kos via WhatsApp resmi tanpa perantara tersembunyi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Memverifikasi identitas mitra kos untuk mencegah penipuan properti fiktif.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Mematuhi kewajiban regulasi hukum yang berlaku di wilayah Republik Indonesia.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 3 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        3
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Hak Subjek Data Pribadi (UU PDP No. 27/2022)
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2.5 leading-relaxed">
                    <p>Sesuai dengan ketentuan undang-undang perlindungan data pribadi, Anda memiliki hak penuh untuk:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-1">
                            <div class="font-bold text-slate-800 text-xs">Hak Akses & Perbaikan</div>
                            <div class="text-[11px] text-slate-500">Memperbarui informasi akun Anda kapan pun melalui halaman Pengaturan Profil.</div>
                        </div>
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 space-y-1">
                            <div class="font-bold text-slate-800 text-xs">Hak Penghapusan Data</div>
                            <div class="text-[11px] text-slate-500">Mengajukan permohonan penutupan akun dan penghapusan data secara permanen.</div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 4 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        4
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Hubungi Petugas Pelindungan Data (DPO)
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>
                        Jika Anda memiliki pertanyaan mengenai pemrosesan data Anda, Anda dapat menghubungi tim kami melalui formulir di <a href="{{ url('/bantuan') }}" class="text-blue-600 font-semibold hover:underline">Pusat Bantuan & FAQ</a> atau mengirimkan pesan ke pengelola resmi SevenKost.
                    </p>
                </div>
            </section>

            {{-- Bottom Confirmation Form Box --}}
            <div class="mt-8 pt-6 border-t border-slate-100 bg-slate-50/80 -mx-6 -mb-6 sm:-mx-10 sm:-mb-10 p-6 sm:p-10 rounded-b-2xl sm:rounded-b-3xl space-y-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Data Anda terlindungi oleh enkripsi SSL dan standar keamanan modern.</span>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ url('/') }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs sm:text-sm font-bold text-slate-700 transition">
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs sm:text-sm font-bold text-white shadow-sm shadow-blue-500/20 transition">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-seven-layout>

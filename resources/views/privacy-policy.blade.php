<x-seven-layout title="Kebijakan Privasi (UU PDP No. 27/2022) - SevenKost" description="Kebijakan pelindungan dan pemrosesan data pribadi pengguna platform SevenKost sesuai regulasi Undang-Undang No. 27 Tahun 2022.">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 py-2.5 relative z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Kebijakan Privasi Data</span>
            </nav>
            <div class="text-[11px] text-emerald-600 font-medium hidden sm:block">
                Kepatuhan UU PDP No. 27/2022
            </div>
        </div>
    </div>

    {{-- Main Container (Compact Width max-w-3xl) --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-5 sm:py-6 space-y-4">
        
        {{-- Hero Header Card (Compact Style) --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-4 sm:p-5 shadow-sm border border-white/15">
            <div class="relative z-10 space-y-1.5">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-medium text-emerald-200">
                    <svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Pelindungan Data Pribadi</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Kebijakan Privasi Pengguna
                </h1>
                <p class="text-xs text-blue-100/90 leading-relaxed">
                    SevenKost menjamin keamanan dan kerahasiaan data pribadi Anda sesuai regulasi UU PDP di Indonesia.
                </p>
                <div class="pt-1 flex items-center gap-3 text-[11px] text-blue-200">
                    <span>Diperbarui: {{ date('d F Y') }}</span>
                    <span>•</span>
                    <span class="text-emerald-300">Enkripsi Standar Industri Aktif</span>
                </div>
            </div>
        </div>

        {{-- Navigation Tab Switcher --}}
        <div class="bg-white p-1 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-1">
            <a href="{{ route('terms.conditions') }}" class="flex-1 py-2 px-3 rounded-lg text-center text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Syarat & Ketentuan</span>
            </a>
            <a href="{{ route('privacy.policy') }}" class="flex-1 py-2 px-3 rounded-lg text-center text-xs font-bold bg-blue-600 text-white shadow-xs transition-all flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Kebijakan Privasi (UU PDP)</span>
            </a>
        </div>

        {{-- Content Card Form (Compact Dashboard Form Standard) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5 text-slate-700">
            
            {{-- Bagian 1 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        1
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Data Pribadi yang Dikumpulkan
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <p>Kami memproses kategori data pribadi berikut secara terbatas dan proporsional:</p>
                    <ul class="space-y-1.5 pt-0.5">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></span>
                            <span><strong>Data Identitas & Akun:</strong> Nama lengkap, alamat email aktif, nomor WhatsApp, dan kata sandi terenkripsi (BCrypt).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></span>
                            <span><strong>Data Properti Pemilik:</strong> Alamat properti, koordinat peta (latitude/longitude), foto kamar, fasilitas, dan kontak pengelola.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></span>
                            <span><strong>Data Transaksi Reservasi:</strong> Durasi sewa, tanggal check-in, dan histori status pemesanan.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 2 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        2
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Tujuan Pemrosesan Data Pribadi
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <p>Pemrosesan data pribadi Anda dilakukan secara sah untuk keperluan:</p>
                    <ul class="space-y-1.5 pt-0.5">
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Menghubungkan calon penghuni langsung dengan pemilik kos via formulir dan kontak resmi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Memverifikasi keabsahan data iklan kos untuk melindungi pengguna dari risiko penipuan.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Memenuhi kewajiban kepatuhan hukum dan regulasi di wilayah Republik Indonesia.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 3 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        3
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Hak Subjek Data Pribadi (UU PDP No. 27/2022)
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-2 leading-relaxed">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-0.5">
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 space-y-0.5">
                            <div class="font-bold text-slate-800 text-xs">Hak Akses & Perbaikan</div>
                            <div class="text-[11px] text-slate-500">Memperbarui data pribadi Anda kapan saja di menu Pengaturan Profil.</div>
                        </div>
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 space-y-0.5">
                            <div class="font-bold text-slate-800 text-xs">Hak Penghapusan Data</div>
                            <div class="text-[11px] text-slate-500">Mengajukan penutupan akun dan penghapusan data secara permanen.</div>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Bagian 4 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        4
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Pertanyaan & Kontak DPO
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1 leading-relaxed">
                    <p>
                        Jika Anda memiliki pertanyaan seputar privasi data, Anda dapat menghubungi tim kami melalui <a href="{{ url('/bantuan') }}" class="text-blue-600 font-semibold hover:underline">Pusat Bantuan & FAQ</a>.
                    </p>
                </div>
            </section>

            {{-- Bottom Action Box --}}
            <div class="mt-6 pt-4 border-t border-slate-100 bg-slate-50/70 -mx-5 -mb-5 sm:-mx-6 sm:-mb-6 p-4 sm:p-5 rounded-b-2xl">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-[11px] text-slate-500 text-center sm:text-left">
                        Data Anda terlindungi oleh enkripsi SSL dan standar keamanan modern.
                    </div>
                    <div class="flex items-center gap-2.5 w-full sm:w-auto">
                        <a href="{{ url('/') }}" class="w-full sm:w-auto text-center px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition">
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto text-center px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-semibold text-white shadow-2xs transition">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-seven-layout>

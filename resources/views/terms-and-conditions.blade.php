<x-seven-layout title="Syarat & Ketentuan Layanan - SevenKost" description="Syarat dan ketentuan umum penggunaan platform pencarian, sewa kos, marketplace, dan kemitraan layanan di SevenKost.">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 py-3 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Syarat & Ketentuan Layanan</span>
            </nav>
            <div class="text-[11px] text-slate-400 hidden sm:block">
                Kepatuhan Regulasi Digital Indonesia
            </div>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        
        {{-- Hero Header Card (SevenKost Blue Style) --}}
        <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-6 sm:p-8 md:p-10 shadow-lg shadow-blue-950/15 border border-white/15">
            {{-- Decorative Accents --}}
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute top-0 right-1/3 w-36 h-36 bg-sky-300/15 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-blue-100">
                    <svg class="w-3.5 h-3.5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Regulasi & Standar Komunitas Platform</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight">
                    Syarat & Ketentuan Layanan
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed max-w-xl">
                    Panduan resmi dan ketentuan hukum yang mengikat seluruh pengguna, pemilik kos, dan mitra layanan saat beraktivitas dalam ekosistem SevenKost.
                </p>
                <div class="pt-1 flex flex-wrap items-center gap-4 text-[11px] text-blue-200">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Diperbarui: {{ date('d F Y') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Versi Resmi 2.1 (Aktif)
                    </span>
                </div>
            </div>
        </div>

        {{-- Navigation Tab Switcher (Syarat Ketentuan vs Kebijakan Privasi) --}}
        <div class="bg-white p-1.5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-1">
            <a href="{{ route('terms.conditions') }}" class="flex-1 py-2.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold bg-blue-600 text-white shadow-xs transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Syarat & Ketentuan Layanan</span>
            </a>
            <a href="{{ route('privacy.policy') }}" class="flex-1 py-2.5 px-4 rounded-xl text-center text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Kebijakan Privasi (UU PDP)</span>
            </a>
        </div>

        {{-- Content Card Form (Style Dashboard Form Standard) --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-xs space-y-8 text-slate-700">
            
            {{-- Quick Summary Highlight --}}
            <div class="p-4 sm:p-5 rounded-2xl bg-blue-50/60 border border-blue-100 text-slate-700 space-y-2">
                <div class="flex items-center gap-2 text-blue-800 font-bold text-xs sm:text-sm">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Ringkasan Singkat bagi Pengguna</span>
                </div>
                <p class="text-xs leading-relaxed text-slate-600">
                    Dengan mendaftar, mengakses, atau menggunakan layanan SevenKost, Anda menyetujui perjanjian hukum ini. SevenKost bertindak sebagai jembatan teknologi terpercaya antara pencari kos, pemilik kos, dan penyedia jasa penunjang hunian secara aman dan transparan di seluruh Indonesia.
                </p>
            </div>

            {{-- Pasal 1 --}}
            <section class="space-y-3 pt-2">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        1
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Ketentuan Umum & Peran Platform
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2.5 leading-relaxed">
                    <p>
                        SevenKost adalah platform digital penyedia sarana informasi, pemetaan interaktif, reservasi, dan direktori kos independen di Indonesia. SevenKost bukan pemilik properti kos, melainkan penyedia media perantara teknologi.
                    </p>
                    <p>
                        Pengguna wajib berusia minimal 17 tahun atau memiliki kartu identitas resmi (KTP/SIM/Paspor) untuk membuat akun dan melakukan transaksi sewa kamar kos secara sah menurut hukum Republik Indonesia.
                    </p>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 2 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        2
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Kewajiban Pemilik & Pengelola Kos
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>Setiap mitra pemilik atau pengelola kos berkewajiban untuk:</p>
                    <ul class="space-y-2 pt-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Menyajikan data informasi properti kos secara akurat, mutakhir, dan jujur (termasuk foto aktual, alamat lengkap, dan tarif sewa).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Dilarang mengunggah konten yang menyesatkan, gambar palsu, milik pihak ketiga tanpa hak, atau properti fiktif.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Memproses pengajuan reservasi booking calon penyewa dengan adil dan tepat waktu melalui panel kelola iklan kos.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 3 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        3
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Alur Booking & Pembayaran
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>Tata cara pengajuan sewa dan transaksi finansial diatur sebagai berikut:</p>
                    <ul class="space-y-2 pt-1">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Reservasi Awal:</strong> Pengajuan booking melalui tombol 'Booking Kamar' mencatat minat sewa dan memerlukan konfirmasi penerimaan oleh pemilik kos.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Pembayaran Bebas Potongan:</strong> Pembayaran sewa kamar berlangsung secara langsung antara penghuni dan pemilik kos tanpa potongan perantara dari platform.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Paket Iklan Promosi:</strong> Pembayaran paket promosi iklan premium diverifikasi secara transparan oleh tim keuangan SevenKost.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 4 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        4
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Mitra Layanan Kos & Marketplace Barang (Sistem COD)
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>
                        Penyedia layanan kebutuhan kos (laundry, pembersih kamar, servis AC, katering) wajib menjaga profesionalisme layanan. Pada fitur Marketplace Barang Kos bekas, transaksi berlangsung dengan metode <em>Cash on Delivery (COD)</em> tatap muka demi keamanan bersama di lingkungan kos.
                    </p>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 5 --}}
            <section class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 font-extrabold text-sm flex items-center justify-center border border-blue-100">
                        5
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                        Laporan Pelanggaran, Moderasi & Penindakan Akun
                    </h2>
                </div>
                <div class="pl-11 text-xs sm:text-sm text-slate-600 space-y-2 leading-relaxed">
                    <p>
                        Pengguna berhak melaporkan properti atau konten yang mencurigakan melalui tombol <strong>'Laporkan Kos Ini'</strong>. Tim moderator SevenKost memproses verifikasi laporan maksimal dalam 3 hari kerja dan berhak menurunkan listing atau menangguhkan akun pelanggar demi menjaga keamanan komunitas.
                    </p>
                </div>
            </section>

            {{-- Bottom Confirmation Form Box --}}
            <div class="mt-8 pt-6 border-t border-slate-100 bg-slate-50/80 -mx-6 -mb-6 sm:-mx-10 sm:-mb-10 p-6 sm:p-10 rounded-b-2xl sm:rounded-b-3xl space-y-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Berlaku efektif untuk seluruh pengguna aktif platform SevenKost.</span>
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

<x-seven-layout title="Syarat & Ketentuan Layanan - SevenKost" description="Syarat dan ketentuan umum penggunaan platform pencarian, sewa kos, marketplace, dan kemitraan layanan di SevenKost.">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/90 backdrop-blur-md border-b border-slate-100 py-2.5 sticky top-0 z-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Syarat & Ketentuan Layanan</span>
            </nav>
            <div class="text-[11px] text-slate-400 hidden sm:block">
                Regulasi Digital Indonesia
            </div>
        </div>
    </div>

    {{-- Main Container (Compact Width max-w-3xl) --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-5 sm:py-6 space-y-4">
        
        {{-- Hero Header Card (Compact SevenKost Blue Style) --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-4 sm:p-5 shadow-sm border border-white/15">
            <div class="relative z-10 space-y-1.5">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-medium text-blue-100">
                    <svg class="w-3 h-3 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Regulasi Komunitas</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                    Syarat & Ketentuan Layanan
                </h1>
                <p class="text-xs text-blue-100/90 leading-relaxed">
                    Panduan resmi dan ketentuan hukum yang mengikat seluruh pengguna, pemilik kos, dan mitra layanan di SevenKost.
                </p>
                <div class="pt-1 flex items-center gap-3 text-[11px] text-blue-200">
                    <span>Diperbarui: {{ date('d F Y') }}</span>
                    <span>•</span>
                    <span class="text-emerald-300">Versi 2.1 Aktif</span>
                </div>
            </div>
        </div>

        {{-- Navigation Tab Switcher (Syarat Ketentuan vs Kebijakan Privasi) --}}
        <div class="bg-white p-1 rounded-xl border border-slate-200/80 shadow-xs flex items-center gap-1">
            <a href="{{ route('terms.conditions') }}" class="flex-1 py-2 px-3 rounded-lg text-center text-xs font-bold bg-blue-600 text-white shadow-xs transition-all flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Syarat & Ketentuan</span>
            </a>
            <a href="{{ route('privacy.policy') }}" class="flex-1 py-2 px-3 rounded-lg text-center text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Kebijakan Privasi (UU PDP)</span>
            </a>
        </div>

        {{-- Content Card Form (Compact Dashboard Form Standard) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-5 text-slate-700">
            
            {{-- Pasal 1 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        1
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Ketentuan Umum & Peran Platform
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <p>
                        SevenKost adalah platform digital penyedia sarana informasi, pemetaan interaktif, reservasi, dan direktori kos di Indonesia. SevenKost bukan pemilik properti kos, melainkan penyedia teknologi perantara.
                    </p>
                    <p>
                        Pengguna wajib berusia minimal 17 tahun atau memiliki kartu identitas resmi untuk membuat akun dan bertransaksi sewa kos secara sah menurut hukum Republik Indonesia.
                    </p>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 2 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        2
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Kewajiban Pemilik & Pengelola Kos
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <p>Setiap mitra pemilik atau pengelola kos berkewajiban untuk:</p>
                    <ul class="space-y-1.5 pt-0.5">
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Menyajikan data informasi properti kos secara akurat, mutakhir, dan jujur (termasuk foto aktual, alamat, dan tarif sewa).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Dilarang mengunggah konten yang menyesatkan, gambar palsu, atau properti fiktif.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Memproses pengajuan reservasi booking calon penyewa secara adil dan tepat waktu melalui panel kelola iklan kos.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 3 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        3
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Alur Booking & Pembayaran
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <ul class="space-y-1.5">
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Reservasi Awal:</strong> Pengajuan booking melalui tombol 'Booking Kamar' mencatat minat sewa dan memerlukan konfirmasi penerimaan oleh pemilik kos.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Pembayaran Langsung:</strong> Pembayaran sewa kamar berlangsung langsung antara penghuni dan pemilik kos tanpa potongan perantara platform.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span><strong>Paket Iklan Promosi:</strong> Pembayaran paket promosi iklan diverifikasi secara transparan oleh tim administrasi SevenKost.</span>
                        </li>
                    </ul>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 4 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        4
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Mitra Layanan Kos & Marketplace Barang (COD)
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1 leading-relaxed">
                    <p>
                        Penyedia layanan (laundry, servis AC, katering) wajib menjaga mutu layanan. Transaksi pada fitur Marketplace Barang bekas dilakukan secara langsung via tatap muka <em>(Cash on Delivery)</em> demi keamanan bersama di lingkungan kos.
                    </p>
                </div>
            </section>

            <hr class="border-slate-100">

            {{-- Pasal 5 --}}
            <section class="space-y-2">
                <div class="flex items-center gap-2.5">
                    <span class="h-6 w-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center border border-blue-100 flex-shrink-0">
                        5
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                        Laporan Pelanggaran & Moderasi Akun
                    </h2>
                </div>
                <div class="pl-8 text-xs text-slate-600 space-y-1 leading-relaxed">
                    <p>
                        Pengguna berhak melaporkan properti atau konten mencurigakan melalui tombol <strong>'Laporkan Kos Ini'</strong>. Tim moderator memproses verifikasi laporan maksimal dalam 3 hari kerja dan berhak menurunkan listing serta menangguhkan akun pelanggar.
                    </p>
                </div>
            </section>

            {{-- Bottom Action Box --}}
            <div class="mt-6 pt-4 border-t border-slate-100 bg-slate-50/70 -mx-5 -mb-5 sm:-mx-6 sm:-mb-6 p-4 sm:p-5 rounded-b-2xl">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-[11px] text-slate-500 text-center sm:text-left">
                        Berlaku efektif untuk seluruh pengguna aktif platform SevenKost.
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

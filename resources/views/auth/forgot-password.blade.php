<x-guest-layout title="Lupa Kata Sandi - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden bg-white">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (NAVY GELAP IDENTIK DENGAN LOGIN)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-gradient-to-br from-[#031d44] via-[#05377a] to-[#020b1f] text-white flex flex-col justify-between shrink-0 overflow-hidden relative">
            
            {{-- Glow samar biru --}}
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-400/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- MOBILE BRAND HEADER (LOGO & BRAND DI TENGAH SESUAI LOGIN) --}}
            <div class="flex flex-col items-center justify-center pt-8 pb-3 lg:hidden text-center z-10">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-14 w-14 object-contain bg-white rounded-2xl p-1.5 shadow-md mb-2">
                    <span class="text-xl font-bold tracking-tight text-white block">
                        Seven<span class="text-sky-300">Kost</span>
                    </span>
                    <span class="text-[10px] text-blue-200 font-medium block mt-0.5">Platform Cari & Kelola Kos</span>
                </a>
            </div>

            {{-- DESKTOP HERO CONTENT (HANYA DESKTOP) --}}
            <div class="hidden lg:block p-8 xl:p-10 relative z-10 w-full max-w-lg xl:max-w-xl mx-auto my-auto">
                {{-- Top Navigation: Tombol Kembali ke Halaman Masuk --}}
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:text-white transition group py-1.5 px-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 w-fit backdrop-blur-md shadow-xs">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Halaman Masuk</span>
                    </a>
                </div>

                {{-- Brand Header Desktop --}}
                <div class="flex items-center gap-2.5 mb-3.5">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo-seven-kost.png') }}" 
                             alt="Logo SevenKost" 
                             class="h-8 w-8 object-contain bg-white rounded-xl p-1 shadow-xs">
                        <div>
                            <span class="text-lg xl:text-xl font-bold tracking-tight leading-none text-white block">
                                Seven<span class="text-sky-300">Kost</span>
                            </span>
                            <span class="text-[11px] text-blue-200 font-medium block">Platform Cari & Kelola Kos</span>
                        </div>
                    </a>
                </div>

                {{-- Headline Ringkas --}}
                <div class="mb-3">
                    <h1 class="text-lg lg:text-xl font-bold leading-snug text-white">
                        Pemulihan Akses Akun <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">Aman & Cepat</span>
                    </h1>
                    <p class="text-xs text-blue-100/90 mt-0.5 leading-relaxed">
                        SevenKost menjamin keamanan akun Anda melalui verifikasi tautan email resmi berstandar modern.
                    </p>
                </div>

                {{-- Gambar Kost (Desktop Saja) --}}
                <div class="relative rounded-2xl overflow-hidden shadow-md border border-white/15 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020b1f]/85 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold shadow-xs">
                            Verifikasi SMTP Resmi
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            Enkripsi Aman
                        </span>
                    </div>
                </div>

                {{-- Panduan 3 Langkah Pemulihan (Desktop Saja) --}}
                <div class="space-y-1.5">
                    <div class="text-[11px] font-semibold text-blue-200">
                        Tahapan Pemulihan Akun:
                    </div>

                    {{-- Langkah 1 --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                            <h2 class="font-semibold text-xs text-white">Masukkan Email Akun</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Ketikkan alamat email yang telah Anda daftarkan di akun SevenKost.
                        </p>
                    </div>

                    {{-- Langkah 2 --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                            <h2 class="font-semibold text-xs text-white">Buka Email dari SevenKost</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Sistem SMTP akan mengirimkan tautan verifikasi aman ke kotak masuk Anda.
                        </p>
                    </div>

                    {{-- Langkah 3 --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                            <h2 class="font-semibold text-xs text-white">Buat Kata Sandi Baru</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Tentukan kata sandi baru yang kuat dan login kembali dengan aman.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MULTI-LAYER WAVE PATTERN TRANSITION (PUTIH SESUAI LOGIN)   --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-28 sm:h-36" viewBox="0 0 1440 320" preserveAspectRatio="none">
                    {{-- Layer Aksen Wave Putih Bersih (Samakan dengan Login Blade) --}}
                    <path d="M0,60 C460,260 840,80 1160,180 C1280,220 1380,210 1440,200 L1440,320 L0,320 Z" fill="#ffffff"></path>
                    {{-- Layer Depan Solid White (Menyatu Mulus ke Form) --}}
                    <path d="M0,120 C460,290 840,120 1160,210 C1280,250 1380,240 1440,230 L1440,320 L0,320 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / BAWAH MOBILE (FORM PUTIH ELEGAN SESUAI LOGIN)  --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col items-center px-6 py-6 sm:p-8 lg:p-10 xl:p-12 flex-1 relative z-10">
            <div class="max-w-sm xl:max-w-md w-full my-auto">

                {{-- Header Form di Tengah Sesuai Gambar Inspirasi --}}
                <div class="mb-5 sm:mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-50 text-[#083873] mb-3 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">
                        Lupa Kata Sandi ?
                    </h1>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Masukkan email Anda untuk menerima tautan atur ulang kata sandi melalui pengiriman email.
                    </p>
                </div>

                {{-- Session Status Feedback (Link Berhasil Dikirim) --}}
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-2xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium leading-snug">{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Form Kirim Link Reset Password --}}
                <form method="POST" action="{{ route('password.email') }}" class="space-y-3.5">
                    @csrf

                    {{-- Input Email --}}
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-medium text-slate-700">
                            Email Terdaftar <span class="text-red-500">*</span>
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="email" 
                               inputmode="email"
                               placeholder="nama@email.com"
                               class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3 px-4 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit Pill Rounded Warna #083873 Sesuai Login Blade --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3 px-6 rounded-full bg-[#083873] hover:bg-[#062c5a] text-white font-bold text-sm shadow-md shadow-[#083873]/25 active:scale-[0.99] transition cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Kirim Link Reset Password</span>
                        </button>
                    </div>

                    {{-- Link Kembali ke Login Tepat di Bawah Tombol --}}
                    <div class="text-center text-xs text-slate-500 pt-1">
                        Ingat kembali kata sandi Anda? 
                        <a href="{{ route('login') }}" class="font-bold text-[#083873] hover:underline ml-0.5">
                            Masuk Sekarang
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-guest-layout>

<x-guest-layout title="Masuk ke Akun - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden bg-white" x-data="{ showPassword: false }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (NAVY GELAP SEPERTI WELCOME HERO)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-gradient-to-br from-[#031d44] via-[#05377a] to-[#020b1f] text-white flex flex-col justify-center shrink-0 lg:overflow-hidden relative">
            
            {{-- Glow samar biru seperti pada welcome hero --}}
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-400/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- Konten Utama Header --}}
            <div class="p-4 sm:p-6 lg:p-8 xl:p-10 relative z-10 w-full max-w-lg xl:max-w-xl mx-auto my-auto">
                {{-- Top Navigation: Tombol Kembali ke Beranda (Hanya Desktop) --}}
                <div class="hidden lg:flex items-center gap-3 mb-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:text-white transition group py-1.5 px-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 w-fit backdrop-blur-md shadow-xs">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {{-- Brand Header (Logo di Pojok Kiri di Mobile & Desktop) --}}
                <div class="flex items-center gap-2.5 mb-3 lg:mb-4">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo-seven-kost.png') }}" 
                             alt="Logo SevenKost" 
                             class="h-7 w-7 lg:h-8 lg:w-8 object-contain bg-white rounded-xl p-1 shadow-xs">
                        <div>
                            <span class="text-base lg:text-lg xl:text-xl font-bold tracking-tight leading-none text-white block">
                                Seven<span class="text-sky-300">Kost</span>
                            </span>
                            <span class="text-[10px] lg:text-[11px] text-blue-200 font-medium block">Platform Cari & Kelola Kos</span>
                        </div>
                    </a>
                </div>

                {{-- Headline Ringkas --}}
                <div class="mb-2 lg:mb-3">
                    <h1 class="text-base sm:text-lg lg:text-xl font-bold leading-snug text-white">
                        Cari & Kelola Kos Jadi <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">Lebih Mudah</span>
                    </h1>
                    <p class="text-xs text-blue-100/90 mt-0.5 leading-relaxed hidden sm:block">
                        Akses kamar kos terverifikasi di seluruh Indonesia atau kelola bisnis sewa properti Anda.
                    </p>
                    {{-- Badges Peran Singkat di Mobile --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-2 lg:hidden">
                        <span class="bg-white/10 border border-white/20 text-blue-100 backdrop-blur-md text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Penghuni
                        </span>
                        <span class="bg-white/10 border border-white/20 text-blue-100 backdrop-blur-md text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Pemilik Kos
                        </span>
                        <span class="bg-white/10 border border-white/20 text-blue-100 backdrop-blur-md text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Mitra Layanan
                        </span>
                    </div>
                </div>

                {{-- Gambar Kost (Desktop Saja) --}}
                <div class="hidden lg:block relative rounded-2xl overflow-hidden shadow-md border border-white/15 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-28 sm:h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020b1f]/85 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold shadow-xs">
                            Puluhan Kos Aktif
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            34 Provinsi
                        </span>
                    </div>
                </div>

                {{-- Penjelasan 3 Role (Desktop Saja) --}}
                <div class="hidden lg:block space-y-1.5">
                    <div class="text-[11px] font-semibold text-blue-200">
                        Akses Layanan Sesuai Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                            <h2 class="font-semibold text-xs text-white">Pencari / Penghuni Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Cari kos terverifikasi, booking kamar online, dan akses layanan kebutuhan harian.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                            <h2 class="font-semibold text-xs text-white">Pemilik / Pengelola Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Pasang iklan properti kos, atur kamar, terima pemesanan, dan pantau penyewa.
                        </p>
                    </div>

                    {{-- Role 3: Mitra Layanan --}}
                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                            <h2 class="font-semibold text-xs text-white">Mitra Layanan Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Tawarkan jasa laundry, kebersihan, katering, dan teknisi langsung ke ekosistem kos.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MULTI-LAYER WAVE PATTERN TRANSITION (EKSTRA BESAR KE BAWAH) --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-28 sm:h-36" viewBox="0 0 1440 320" preserveAspectRatio="none">
                    {{-- Layer Gelombang Belakang (Semi-transparan untuk kedalaman) --}}
                    <path d="M0,80 C360,260 720,20 1080,200 C1240,260 1360,180 1440,120 L1440,320 L0,320 Z" class="fill-white/20"></path>
                    {{-- Layer Gelombang Tengah (Semi-transparan untuk kedalaman berlapis) --}}
                    <path d="M0,140 C320,40 680,260 1040,100 C1220,30 1360,180 1440,100 L1440,320 L0,320 Z" class="fill-white/40"></path>
                    {{-- Layer Gelombang Depan (Solid White menyatu ke form) --}}
                    <path d="M0,120 C360,280 720,50 1080,220 C1240,280 1360,200 1440,130 L1440,320 L0,320 Z" class="fill-white"></path>
                </svg>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / BAWAH MOBILE (FORM PUTIH BERSIH & RESPONSIVE)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white rounded-t-[2.5rem] sm:rounded-t-[3rem] lg:rounded-none -mt-6 sm:-mt-8 lg:mt-0 relative z-10 shadow-2xl lg:shadow-none flex flex-col items-center px-5 py-6 sm:p-8 lg:p-10 xl:p-12 flex-1 overflow-y-auto">
            <div class="max-w-sm xl:max-w-md w-full my-auto">

                {{-- Header Form --}}
                <div class="mb-4 sm:mb-5">
                    <span class="text-xs font-semibold text-blue-600 block mb-0.5">
                        Selamat Datang Kembali!
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Pilih metode masuk untuk mengakses akun Anda.
                    </p>
                </div>

                {{-- Session Status Feedback --}}
                @if (session('status'))
                    <div class="mb-3.5 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-3 sm:space-y-3.5">
                    @csrf

                    {{-- Input Email --}}
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-medium text-slate-700">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               inputmode="email"
                               placeholder="nama@email.com"
                               class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 px-3.5 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi --}}
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-medium text-slate-700">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPassword ? 'text' : 'password'"
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="Masukkan kata sandi"
                                   class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2.5 pl-3.5 pr-11 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    aria-label="Tampilkan kata sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me Checkbox --}}
                    <div class="flex items-center pt-0.5">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember"
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="ms-2 text-xs text-slate-600 font-normal">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-1">
                        <button type="submit" 
                                class="w-full py-2.5 sm:py-2.5 px-4 rounded-xl text-sm sm:text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 active:scale-[0.99] transition shadow-md shadow-blue-500/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
                            <span>Masuk ke Akun</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Divider "atau masuk dengan" --}}
                <div class="relative flex items-center my-4">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-3 text-[11px] text-slate-400 font-normal">atau masuk dengan</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                {{-- Tombol Login via Google (Standar Resmi Google Branding - Di Bawah Form) --}}
                <div>
                    <a href="{{ Route::has('auth.google') ? route('auth.google') : '#' }}" 
                       class="w-full py-2.5 px-4 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm flex items-center justify-center gap-2.5 shadow-2xs transition active:scale-[0.99] cursor-pointer">
                        {{-- Logo Resmi Google 4 Warna --}}
                        <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.03 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </a>
                </div>

                {{-- Link ke Register --}}
                <div class="mt-4 sm:mt-5 pt-3.5 sm:pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
                    Belum memiliki akun SevenKost?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Daftar Akun Baru
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

<x-guest-layout title="Masuk ke Akun - SevenKost">
    <div class="min-h-screen w-full flex flex-col lg:flex-row" x-data="{ showPassword: false }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI (BIRU CERAH 50% LEBAR): LOGO, GAMBAR, & ROLE       --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-blue-600 text-white p-6 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between">
            <div>
                {{-- Tombol Navigasi Kembali ke Beranda di Bagian Atas Kiri --}}
                <div class="mb-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-white/95 hover:text-white transition group py-1.5 px-3 rounded-xl bg-blue-700/60 hover:bg-blue-700 border border-blue-400/40 w-fit">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {{-- Brand Header: Logo & Nama Aplikasi --}}
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-11 w-11 object-contain bg-white rounded-xl p-1 shadow-sm">
                    <div>
                        <span class="text-2xl font-black tracking-tight leading-none text-white block">
                            Seven<span class="text-sky-200">Kost</span>
                        </span>
                        <span class="text-xs text-blue-100 font-medium">Platform Cari & Kelola Kos</span>
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mb-5">
                    <h1 class="text-2xl xl:text-3xl font-extrabold leading-snug">
                        Cari & Kelola Kos Jadi Lebih Mudah
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 mt-1.5 leading-relaxed">
                        Akses ribuan kamar kos terverifikasi di seluruh Indonesia atau kelola properti sewa Anda secara langsung.
                    </p>
                </div>

                {{-- Gambar Kost Sesuai Dashboard/Website --}}
                <div class="relative rounded-2xl overflow-hidden shadow-lg border-2 border-white/20 mb-6 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-40 sm:h-48 xl:h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-xs text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-1 rounded-lg font-bold text-[11px] shadow-xs">
                            10.000+ Kos Aktif
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-1 rounded-lg font-medium text-[11px]">
                            34 Provinsi
                        </span>
                    </div>
                </div>

                {{-- Penjelasan Role Singkat --}}
                <div class="space-y-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-blue-200">
                        Akses Layanan Sesuai Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div class="bg-blue-700/70 rounded-xl p-3.5 border border-blue-400/40">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-md bg-white text-blue-600 flex items-center justify-center text-xs font-black shrink-0">1</span>
                            <h2 class="font-bold text-sm text-white">Pencari / Penghuni Kos</h2>
                        </div>
                        <p class="text-xs text-blue-100 pl-7 leading-relaxed">
                            Cari kos terverifikasi, lihat peta interaktif, booking kamar online, dan akses layanan kebutuhan kos.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div class="bg-blue-700/70 rounded-xl p-3.5 border border-blue-400/40">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-5 h-5 rounded-md bg-white text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            <h2 class="font-bold text-sm text-white">Pemilik / Pengelola Kos</h2>
                        </div>
                        <p class="text-xs text-blue-100 pl-7 leading-relaxed">
                            Pasang iklan properti kos, kelola ketersediaan kamar, terima pemesanan, dan pantau penyewa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Layer Kiri: Info & Hak Cipta --}}
            <div class="pt-6 mt-6 border-t border-blue-500/50 flex items-center justify-between text-xs text-blue-100">
                <span class="text-blue-200">SevenKost Indonesia</span>
                <span class="text-blue-200 text-[11px]">© {{ date('Y') }} SevenKost</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN (PUTIH BERSIH 50% LEBAR): FORM LOGIN              --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center p-6 sm:p-12 lg:p-14 xl:p-16">
            <div class="max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-7">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">
                        Autentikasi Akun
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                        Masukkan email dan kata sandi Anda untuk mengakses sistem.
                    </p>
                </div>

                {{-- Session Status Feedback --}}
                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Input Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi --}}
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline">
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
                                   placeholder="Masukkan kata sandi akun"
                                   class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 pl-4 pr-11 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
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
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me Checkbox --}}
                    <div class="flex items-center pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember"
                                   class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="ms-2.5 text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-xl text-sm sm:text-base font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-md shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Register --}}
                <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs sm:text-sm text-slate-600">
                    Belum memiliki akun SevenKost?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Daftar Akun Baru
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

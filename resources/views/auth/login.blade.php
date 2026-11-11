<x-guest-layout title="Masuk ke Akun - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden bg-white" x-data="{ showPassword: false }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (BIRU CERAH DENGAN WAVE PATTERN)     --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-blue-600 text-white flex flex-col justify-between shrink-0 lg:overflow-hidden relative">
            
            {{-- Konten Utama Header --}}
            <div class="p-4 sm:p-6 lg:p-8 xl:p-10 pb-1 sm:pb-2 lg:pb-8">
                {{-- Top Navigation: Tombol Kembali ke Beranda & Logo Brand (Mobile Friendly) --}}
                <div class="flex items-center justify-between gap-3 mb-3 lg:mb-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/95 hover:text-white transition group py-1.5 px-3 rounded-xl bg-blue-700/70 hover:bg-blue-700 border border-blue-400/40 w-fit shadow-xs">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>

                    {{-- Logo Brand di Pojok Kanan (Hanya Mobile) --}}
                    <div class="flex items-center gap-2 lg:hidden">
                        <img src="{{ asset('images/logo-seven-kost.png') }}" 
                             alt="Logo SevenKost" 
                             class="h-7 w-7 object-contain bg-white rounded-lg p-0.5 shadow-xs">
                        <span class="text-base font-bold tracking-tight text-white">
                            Seven<span class="text-sky-200">Kost</span>
                        </span>
                    </div>
                </div>

                {{-- Brand Header Desktop (Logo & Nama Aplikasi) --}}
                <div class="hidden lg:flex items-center gap-2.5 mb-3">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-8 w-8 object-contain bg-white rounded-xl p-1 shadow-xs">
                    <div>
                        <span class="text-lg xl:text-xl font-bold tracking-tight leading-none text-white block">
                            Seven<span class="text-sky-200">Kost</span>
                        </span>
                        <span class="text-[11px] text-blue-100 font-medium">Platform Cari & Kelola Kos</span>
                    </div>
                </div>

                {{-- Headline Ringkas --}}
                <div class="mb-2 lg:mb-3">
                    <h1 class="text-base sm:text-lg lg:text-xl font-bold leading-snug">
                        Cari & Kelola Kos Jadi Lebih Mudah
                    </h1>
                    <p class="text-xs text-blue-100 mt-0.5 leading-relaxed hidden sm:block">
                        Akses kamar kos terverifikasi di seluruh Indonesia atau kelola bisnis sewa properti Anda.
                    </p>
                    {{-- Badges Peran Singkat di Mobile --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-2 lg:hidden">
                        <span class="bg-blue-700/80 border border-blue-400/40 text-blue-100 text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Penghuni
                        </span>
                        <span class="bg-blue-700/80 border border-blue-400/40 text-blue-100 text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Pemilik Kos
                        </span>
                        <span class="bg-blue-700/80 border border-blue-400/40 text-blue-100 text-[10px] px-2 py-0.5 rounded-md font-medium">
                            Mitra Layanan
                        </span>
                    </div>
                </div>

                {{-- Gambar Kost (Desktop & Tablet Lebar Saja Agar Mobile Langsung Fokus Form) --}}
                <div class="hidden lg:block relative rounded-2xl overflow-hidden shadow-md border border-white/20 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-28 sm:h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold shadow-xs">
                            Puluhan Kos Aktif
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            34 Provinsi
                        </span>
                    </div>
                </div>

                {{-- Penjelasan 3 Role (Tampil Penuh di Desktop) --}}
                <div class="hidden lg:block space-y-1.5">
                    <div class="text-[11px] font-semibold text-blue-100">
                        Akses Layanan Sesuai Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div class="bg-blue-700/70 rounded-xl p-2.5 border border-blue-400/40">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                            <h2 class="font-semibold text-xs text-white">Pencari / Penghuni Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Cari kos terverifikasi, booking kamar online, dan akses layanan kebutuhan harian.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div class="bg-blue-700/70 rounded-xl p-2.5 border border-blue-400/40">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                            <h2 class="font-semibold text-xs text-white">Pemilik / Pengelola Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Pasang iklan properti kos, atur kamar, terima pemesanan, dan pantau penyewa.
                        </p>
                    </div>

                    {{-- Role 3: Mitra Layanan --}}
                    <div class="bg-blue-700/70 rounded-xl p-2.5 border border-blue-400/40">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                            <h2 class="font-semibold text-xs text-white">Mitra Layanan Kos</h2>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Tawarkan jasa laundry, kebersihan, katering, dan teknisi langsung ke ekosistem kos.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MOBILE WAVE PATTERN TRANSITION (GELOMBANG ORGANIK HALUS) --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-8 sm:h-11 text-white" viewBox="0 0 1440 96" fill="none" preserveAspectRatio="none">
                    <path d="M0,32 C360,90 540,10 820,50 C1100,90 1260,20 1440,45 L1440,96 L0,96 Z" fill="currentColor"></path>
                </svg>
            </div>

            {{-- Footer Layer Kiri: Hak Cipta (Desktop Saja) --}}
            <div class="hidden lg:flex p-5 lg:p-8 xl:p-10 pt-3 border-t border-blue-500/50 items-center justify-between text-[11px] text-blue-100">
                <span class="text-blue-200">SevenKost Indonesia</span>
                <span class="text-blue-200">© {{ date('Y') }}</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / BAWAH MOBILE (FORM PUTIH BERSIH DENGAN WAVE)    --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center px-5 pt-3 pb-6 sm:p-8 lg:p-10 xl:p-12 flex-1 overflow-y-auto">
            <div class="max-w-sm xl:max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-4 sm:mb-5">
                    <span class="text-xs font-semibold text-blue-600 block mb-0.5">
                        Selamat Datang Kembali!
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Masukkan email dan kata sandi Anda untuk mengakses akun.
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
                                class="w-full py-2.5 sm:py-2.5 px-4 rounded-xl text-sm sm:text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-sm shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
                            <span>Masuk ke Akun</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Register --}}
                <div class="mt-4 sm:mt-5 pt-3.5 sm:pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
                    Belum memiliki akun SevenKost?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Daftar Akun Baru
                    </a>
                </div>

                {{-- Mobile Copyright Footer --}}
                <div class="mt-4 text-center lg:hidden text-[10px] text-slate-400">
                    SevenKost Indonesia © {{ date('Y') }}
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

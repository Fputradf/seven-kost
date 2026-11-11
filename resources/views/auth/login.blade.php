<x-guest-layout title="Masuk ke Akun - SevenKost">
    <div class="min-h-screen w-full flex flex-col lg:flex-row" x-data="{ showPassword: false }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI (BIRU SEVENKOST): LOGO, NAMA, & PENJELASAN ROLE    --}}
        {{-- Warna: Navy-950 / #0F1E36 (Sama dengan Header Form Dashboard)  --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-5/12 xl:w-4/12 bg-navy-950 text-white p-8 sm:p-12 xl:p-16 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-slate-800">
            <div>
                {{-- Brand Header: Logo & Nama Aplikasi --}}
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3.5 group mb-8">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-11 w-11 object-contain bg-white/10 rounded-xl p-1.5 border border-white/20 group-hover:scale-105 transition">
                    <div>
                        <span class="text-2xl font-black tracking-tight leading-none text-white block">
                            Seven<span class="text-blue-400">Kost</span>
                        </span>
                        <span class="text-xs text-slate-400 font-medium">Platform Cari & Kelola Kos</span>
                    </div>
                </a>

                {{-- Badge Panel Sesuai Dashboard --}}
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold border border-blue-500/30">
                        Portal Autentikasi Pengguna
                    </span>
                </div>

                {{-- Headline --}}
                <div class="mb-8">
                    <h1 class="text-2xl xl:text-3xl font-extrabold tracking-tight leading-tight">
                        Satu Akun untuk Semua Kebutuhan Kos
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                        Akses mudah bagi penghuni kos dan pemilik kos di seluruh Indonesia dalam satu platform terpercaya.
                    </p>
                </div>

                {{-- Penjelasan Role Singkat --}}
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        Akses Berdasarkan Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            <h2 class="font-bold text-sm text-white">Pencari / Penghuni Kos</h2>
                        </div>
                        <ul class="text-xs text-slate-300 space-y-1.5 pl-8 list-disc">
                            <li>Cari kos terverifikasi & booking kamar online</li>
                            <li>Pantau status sewa & riwayat booking bulanan</li>
                            <li>Akses layanan mitra laundry & kebutuhan kos</li>
                        </ul>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            <h2 class="font-bold text-sm text-white">Pemilik / Pengelola Kos</h2>
                        </div>
                        <ul class="text-xs text-slate-300 space-y-1.5 pl-8 list-disc">
                            <li>Pasang iklan kos baru & atur tipe kamar</li>
                            <li>Terima pemesanan dan konfirmasi booking sewa</li>
                            <li>Kelola data penyewa & okupansi properti</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Footer Layer Kiri: Navigasi ke Beranda --}}
            <div class="pt-8 mt-8 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-slate-300 hover:text-white font-medium transition">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
                <span class="text-slate-400 text-[11px]">© {{ date('Y') }} SevenKost</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN (PUTIH): KHUSUS FORM FIELD FULL SCREEN            --}}
        {{-- Warna & Gaya Input: Sama Persis dengan Form Blade Dashboard   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-7/12 xl:w-8/12 bg-white flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 xl:p-20">
            <div class="max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-8">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">
                        Autentikasi Akun
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                        Silakan masukkan alamat email dan kata sandi yang terdaftar.
                    </p>
                </div>

                {{-- Session Status Feedback --}}
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

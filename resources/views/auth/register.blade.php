<x-guest-layout title="Daftar Akun Baru - SevenKost">
    <div class="min-h-screen w-full flex flex-col lg:flex-row"
         x-data="{
             selectedRole: '{{ old('role', 'penghuni') }}',
             showPassword: false,
             showPasswordConfirmation: false,
             setRole(role) {
                 this.selectedRole = role;
             }
         }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI (BIRU CERAH 50% LEBAR): LOGO, GAMBAR, & ROLE       --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-blue-600 text-white p-6 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between">
            <div>
                {{-- Brand Header: Logo & Nama Aplikasi --}}
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group mb-6">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-11 w-11 object-contain bg-white rounded-xl p-1 shadow-sm group-hover:scale-105 transition">
                    <div>
                        <span class="text-2xl font-black tracking-tight leading-none text-white block">
                            Seven<span class="text-sky-200">Kost</span>
                        </span>
                        <span class="text-xs text-blue-100 font-medium">Platform Cari & Kelola Kos</span>
                    </div>
                </a>

                {{-- Headline --}}
                <div class="mb-5">
                    <h1 class="text-2xl xl:text-3xl font-extrabold leading-snug">
                        Daftar Akun SevenKost
                    </h1>
                    <p class="text-xs sm:text-sm text-blue-100 mt-1.5 leading-relaxed">
                        Pilih peran Anda dan nikmati kemudahan mencari hunian nyaman atau mengelola bisnis kos dalam satu aplikasi.
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
                            Kamar & Properti Pilihan
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-1 rounded-lg font-medium text-[11px]">
                            Terverifikasi Amanah
                        </span>
                    </div>
                </div>

                {{-- Penjelasan Role Singkat --}}
                <div class="space-y-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-blue-200">
                        Pilih Peran Akun:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div @click="setRole('penghuni')"
                         :class="selectedRole === 'penghuni' ? 'ring-2 ring-white bg-blue-700 shadow-md' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-xl p-3.5 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-white text-blue-600 flex items-center justify-center text-xs font-black shrink-0">1</span>
                                <h2 class="font-bold text-sm text-white">Pencari / Penghuni Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'penghuni'" class="text-[10px] bg-white text-blue-700 font-extrabold px-2 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-xs text-blue-100 pl-7 leading-relaxed">
                            Cari kos berdasarkan kota & peta interaktif, booking kamar online, dan pantau status sewa.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div @click="setRole('pemilik')"
                         :class="selectedRole === 'pemilik' ? 'ring-2 ring-white bg-blue-700 shadow-md' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-xl p-3.5 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-md bg-white text-blue-600 flex items-center justify-center text-xs font-black shrink-0">2</span>
                                <h2 class="font-bold text-sm text-white">Pemilik / Pengelola Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'pemilik'" class="text-[10px] bg-white text-blue-700 font-extrabold px-2 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-xs text-blue-100 pl-7 leading-relaxed">
                            Pasang iklan kos nasional, atur kamar & tarif sewa, konfirmasi pemesanan, dan kelola penyewa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Layer Kiri: Navigasi ke Beranda --}}
            <div class="pt-6 mt-6 border-t border-blue-500/50 flex items-center justify-between text-xs text-blue-100">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 hover:text-white font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
                <span class="text-blue-200 text-[11px]">© {{ date('Y') }} SevenKost</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN (PUTIH BERSIH 50% LEBAR): FORM REGISTER           --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center p-6 sm:p-12 lg:p-14 xl:p-16">
            <div class="max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-6">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">
                        Pendaftaran Akun
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Buat Akun SevenKost
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5">
                        Lengkapi formulir di bawah ini untuk membuat akun baru Anda.
                    </p>
                </div>

                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Hidden Role Input --}}
                    <input type="hidden" name="role" :value="selectedRole">

                    {{-- Switcher Role --}}
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">
                            Daftar Sebagai <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-xl">
                            <button type="button" 
                                    @click="setRole('penghuni')"
                                    :class="selectedRole === 'penghuni' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-2.5 px-3 rounded-lg text-xs sm:text-sm text-center transition cursor-pointer">
                                Pencari / Penghuni
                            </button>
                            <button type="button" 
                                    @click="setRole('pemilik')"
                                    :class="selectedRole === 'pemilik' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-2.5 px-3 rounded-lg text-xs sm:text-sm text-center transition cursor-pointer">
                                Pemilik Kos
                            </button>
                        </div>
                    </div>

                    {{-- Input Nama Lengkap --}}
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Contoh: Budi Prasetyo"
                               class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-slate-900 placeholder-slate-400 @error('name') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('name')
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi & Konfirmasi (Grid 2 Kolom) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Kata Sandi --}}
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Min. 8 karakter"
                                       class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 pl-4 pr-10 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        aria-label="Tampilkan kata sandi"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
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

                        {{-- Konfirmasi Kata Sandi --}}
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase">
                                Ulangi Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       :type="showPasswordConfirmation ? 'text' : 'password'"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Ulangi sandi"
                                       class="w-full text-sm rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-3 pl-4 pr-10 text-slate-900 placeholder-slate-400 @error('password_confirmation') border-red-300 bg-red-50/20 @enderror transition" />
                                <button type="button" 
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        aria-label="Tampilkan sandi konfirmasi"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
                                    <svg x-show="!showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Persetujuan UU PDP & Ketentuan Layanan --}}
                    <div class="flex items-start gap-2.5 pt-1">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required 
                               class="w-4 h-4 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 shrink-0 cursor-pointer">
                        <label for="terms" class="text-xs text-slate-600 leading-normal select-none cursor-pointer">
                            Saya menyetujui 
                            <a href="{{ route('terms.conditions') }}" target="_blank" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">Ketentuan Layanan</a> 
                            serta pemrosesan data sesuai 
                            <a href="{{ route('privacy.policy') }}" target="_blank" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">Kebijakan Privasi (UU PDP No. 27/2022)</a>.
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-xl text-sm sm:text-base font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-md shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
                            <span>Daftar Akun Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Login --}}
                <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs sm:text-sm text-slate-600">
                    Sudah memiliki akun SevenKost?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Masuk ke Akun
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

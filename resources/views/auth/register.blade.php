<x-guest-layout title="Daftar Akun Baru - SevenKost">
    <div class="w-full max-w-5xl mx-auto my-auto"
         x-data="{
             selectedRole: '{{ old('role', 'penghuni') }}',
             showPassword: false,
             showPasswordConfirmation: false,
             setRole(role) {
                 this.selectedRole = role;
             }
         }">

        {{-- Navigasi Cepat Kembali ke Beranda --}}
        <div class="mb-4 flex items-center justify-between px-2">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-blue-200 hover:text-white transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Beranda SevenKost</span>
            </a>
            <span class="text-xs text-blue-200/70 hidden sm:inline-block">Daftar Gratis & Langsung Terhubung</span>
        </div>

        {{-- Main Container Card: Full Screen Glassmorphic Vibrant Navy Blue --}}
        <div class="relative rounded-3xl bg-[#0f2d59]/80 backdrop-blur-2xl border border-white/20 shadow-2xl shadow-[#08182d]/80 overflow-hidden lg:grid lg:grid-cols-12">
            {{-- Glowing Top Accent Line --}}
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-400"></div>

            {{-- ================================================================= --}}
            {{-- SISI KIRI: BRANDING HERO SCENE (Desktop LG+) --}}
            {{-- ================================================================= --}}
            <div class="hidden lg:flex lg:col-span-5 p-8 xl:p-10 flex-col justify-between border-r border-white/10 bg-gradient-to-b from-[#13386e]/70 to-[#0e274d]/90 relative select-none">
                {{-- Ambient Inside Glow --}}
                <div class="absolute top-10 left-10 w-48 h-48 bg-cyan-400/20 blur-3xl rounded-full pointer-events-none"></div>

                {{-- Top Logo & Status Badge --}}
                <div class="relative z-10 space-y-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center group transition transform hover:scale-[1.02] active:scale-95 duration-150">
                        <x-seven-logo :light="true" size="lg" />
                    </a>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-400/15 border border-cyan-400/30 text-xs font-bold text-cyan-200">
                        <span class="w-2 h-2 rounded-full bg-cyan-300 animate-pulse"></span>
                        <span>Pendaftaran Multi-Peran Terintegrasi</span>
                    </div>
                </div>

                {{-- Middle Headline & Role Cards --}}
                <div class="relative z-10 space-y-4 my-6">
                    <h1 class="text-2xl xl:text-3xl font-black tracking-tight leading-snug text-white">
                        Satu Akun, Semua <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-sky-200 to-white">
                            Kebutuhan Kos Anda.
                        </span>
                    </h1>
                    <p class="text-blue-100/90 text-xs xl:text-sm leading-relaxed font-normal">
                        Pilih jenis akun untuk menikmati kemudahan fitur terintegrasi yang disesuaikan dengan kebutuhan Anda.
                    </p>

                    <div class="space-y-3 pt-2">
                        {{-- 1. Kartu Pencari Kos --}}
                        <div @click="setRole('penghuni')"
                             :class="selectedRole === 'penghuni' 
                                ? 'bg-cyan-500/25 border-cyan-300/60 shadow-lg shadow-cyan-500/20 translate-x-1' 
                                : 'bg-white/10 border-white/15 opacity-75 hover:opacity-100 hover:bg-white/15'"
                             class="p-3.5 rounded-2xl border backdrop-blur-md transition-all duration-200 flex items-start gap-3.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-cyan-400/25 border border-cyan-300/40 flex items-center justify-center text-cyan-200 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white block text-sm">Pencari / Penghuni Kos</span>
                                    <span x-show="selectedRole === 'penghuni'" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-cyan-400/30 text-cyan-100">Dipilih</span>
                                </div>
                                <span class="text-blue-200/80 text-[11px] mt-0.5 block leading-normal">
                                    Cari kos terverifikasi, peta interaktif, booking kamar, dan nikmati layanan mitra.
                                </span>
                            </div>
                        </div>

                        {{-- 2. Kartu Pemilik Kos --}}
                        <div @click="setRole('pemilik')"
                             :class="selectedRole === 'pemilik' 
                                ? 'bg-indigo-500/30 border-indigo-300/60 shadow-lg shadow-indigo-500/20 translate-x-1' 
                                : 'bg-white/10 border-white/15 opacity-75 hover:opacity-100 hover:bg-white/15'"
                             class="p-3.5 rounded-2xl border backdrop-blur-md transition-all duration-200 flex items-start gap-3.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-indigo-400/25 border border-indigo-300/40 flex items-center justify-center text-indigo-200 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white block text-sm">Pemilik / Pengelola Kos</span>
                                    <span x-show="selectedRole === 'pemilik'" class="px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-indigo-400/40 text-indigo-100">Dipilih</span>
                                </div>
                                <span class="text-blue-200/80 text-[11px] mt-0.5 block leading-normal">
                                    Pasang iklan gratis/VIP, kelola ketersediaan kamar, pantau penyewa, dan terima booking.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom Badges --}}
                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-blue-200/70">
                    <span>© {{ date('Y') }} SevenKost</span>
                    <span class="font-semibold text-cyan-300 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Privasi Terlindungi UU PDP
                    </span>
                </div>
            </div>

            {{-- ================================================================= --}}
            {{-- SISI KANAN: FORM REGISTER LENGKAP --}}
            {{-- ================================================================= --}}
            <div class="lg:col-span-7 p-6 sm:p-10 xl:p-12 flex flex-col justify-center relative">
                {{-- Header Form --}}
                <div class="mb-5">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden mb-4 flex justify-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center">
                            <x-seven-logo :light="true" size="md" />
                        </a>
                    </div>

                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-cyan-400/20 text-cyan-300 text-xs font-bold uppercase tracking-wider">
                            Pendaftaran Akun
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Bergabung ke SevenKost 🚀
                    </h2>
                    <p class="text-blue-200/80 text-xs sm:text-sm mt-1">
                        Lengkapi data di bawah untuk membuka akses penuh platform kos.
                    </p>
                </div>

                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Hidden Role Input --}}
                    <input type="hidden" name="role" :value="selectedRole">

                    {{-- Role Selector Tabs (Mobile & Desktop Quick Switcher) --}}
                    <div>
                        <label class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-2">
                            Pilih Tipe Pengguna:
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-1.5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md">
                            <button type="button" 
                                    @click="setRole('penghuni')"
                                    :class="selectedRole === 'penghuni' 
                                        ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold shadow-md shadow-cyan-500/30' 
                                        : 'text-blue-200 hover:text-white font-medium hover:bg-white/5'"
                                    class="py-2.5 px-3 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-2 transition-all duration-200 cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Pencari Kos</span>
                            </button>
                            <button type="button" 
                                    @click="setRole('pemilik')"
                                    :class="selectedRole === 'pemilik' 
                                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-md shadow-indigo-500/30' 
                                        : 'text-blue-200 hover:text-white font-medium hover:bg-white/5'"
                                    class="py-2.5 px-3 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-2 transition-all duration-200 cursor-pointer">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Pemilik Kos</span>
                            </button>
                        </div>
                    </div>

                    {{-- Input Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name" 
                                   placeholder="Contoh: Budi Prasetyo"
                                   class="w-full h-11 pl-10 pr-4 bg-white/10 border @error('name') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username" 
                                   placeholder="nama@email.com"
                                   class="w-full h-11 pl-10 pr-4 bg-white/10 border @error('email') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Grid 2 Kolom untuk Password & Konfirmasi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Minimal 8 karakter"
                                       class="w-full h-11 pl-10 pr-10 bg-white/10 border @error('password') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        aria-label="Tampilkan sandi"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white active:scale-75 transition-all duration-150 focus:outline-none cursor-pointer">
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
                                <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                                Ulangi Sandi
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <input id="password_confirmation" 
                                       :type="showPasswordConfirmation ? 'text' : 'password'"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Ulangi sandi"
                                       class="w-full h-11 pl-10 pr-10 bg-white/10 border @error('password_confirmation') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                                <button type="button" 
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        aria-label="Tampilkan sandi konfirmasi"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white active:scale-75 transition-all duration-150 focus:outline-none cursor-pointer">
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
                                <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Persetujuan UU PDP & Ketentuan Layanan --}}
                    <div class="flex items-start gap-2.5 pt-1">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required 
                               class="w-4 h-4 mt-0.5 rounded-md border-white/30 bg-blue-950/60 text-cyan-400 focus:ring-cyan-400 focus:ring-offset-blue-900 shrink-0 cursor-pointer">
                        <label for="terms" class="text-xs text-blue-200 leading-normal select-none cursor-pointer">
                            Saya menyetujui 
                            <a href="{{ route('terms.conditions') }}" target="_blank" class="font-bold text-cyan-300 hover:text-white underline underline-offset-2 transition">Ketentuan Layanan</a> 
                            serta pemrosesan data sesuai 
                            <a href="{{ route('privacy.policy') }}" target="_blank" class="font-bold text-cyan-300 hover:text-white underline underline-offset-2 transition">Kebijakan Privasi (UU PDP No. 27/2022)</a>.
                        </label>
                    </div>

                    {{-- Tombol Submit dengan Click Bounce --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full h-12 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm sm:text-base font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                            <span>Daftar Akun Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Login --}}
                <div class="mt-5 pt-4 border-t border-white/10 text-center text-xs sm:text-sm text-blue-200">
                    Sudah memiliki akun SevenKost?
                    <a href="{{ route('login') }}" class="font-extrabold text-cyan-300 hover:text-white underline underline-offset-4 ml-1 transition">
                        Masuk ke Akun
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

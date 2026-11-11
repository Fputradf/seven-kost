<x-guest-layout title="Masuk ke Akun - SevenKost">
    <div class="w-full max-w-5xl mx-auto my-auto" x-data="{ showPassword: false }">

        {{-- Navigasi Cepat Kembali ke Beranda --}}
        <div class="mb-4 flex items-center justify-between px-2">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-blue-200 hover:text-white transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Beranda SevenKost</span>
            </a>
            <span class="text-xs text-blue-200/70 hidden sm:inline-block">Hunian Nyaman, Booking Amanah</span>
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
                        <span>Platform Kos Terverifikasi Nasional</span>
                    </div>
                </div>

                {{-- Middle Headline & Features --}}
                <div class="relative z-10 space-y-4 my-6">
                    <h1 class="text-2xl xl:text-3xl font-black tracking-tight leading-snug text-white">
                        Sewa & Kelola Kos di <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-sky-200 to-white">
                            Seluruh Indonesia.
                        </span>
                    </h1>
                    <p class="text-blue-100/90 text-xs xl:text-sm leading-relaxed font-normal">
                        Jelajahi ribuan kos terverifikasi di 34 provinsi dengan jaminan kepatuhan UU PDP No. 27/2022 dan booking langsung tanpa perantara.
                    </p>

                    <div class="space-y-3 pt-2">
                        <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center gap-3.5 transition hover:bg-white/15">
                            <div class="w-9 h-9 rounded-xl bg-cyan-400/20 border border-cyan-300/30 flex items-center justify-center text-cyan-200 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-white block">10.000+ Kamar Kos Aktif</span>
                                <span class="text-blue-200/80 text-[11px]">Putra, putri, & pasutri terverifikasi</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center gap-3.5 transition hover:bg-white/15">
                            <div class="w-9 h-9 rounded-xl bg-emerald-400/20 border border-emerald-300/30 flex items-center justify-center text-emerald-200 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-white block">Privasi & Legalitas Terjamin</span>
                                <span class="text-blue-200/80 text-[11px]">Kepatuhan penuh UU PDP No. 27/2022</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center gap-3.5 transition hover:bg-white/15">
                            <div class="w-9 h-9 rounded-xl bg-blue-400/20 border border-blue-300/30 flex items-center justify-center text-blue-200 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-white block">Booking Cepat & Mudah</span>
                                <span class="text-blue-200/80 text-[11px]">Chat langsung dengan pemilik kos</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom Badges --}}
                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-blue-200/70">
                    <span>© {{ date('Y') }} SevenKost</span>
                    <span class="font-semibold text-cyan-300">Amanah & Terpercaya</span>
                </div>
            </div>

            {{-- ================================================================= --}}
            {{-- SISI KANAN: FORM LOGIN INTERAKTIF --}}
            {{-- ================================================================= --}}
            <div class="lg:col-span-7 p-6 sm:p-10 xl:p-12 flex flex-col justify-center relative">
                {{-- Header Form --}}
                <div class="mb-6">
                    {{-- Mobile Logo --}}
                    <div class="lg:hidden mb-5 flex justify-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center">
                            <x-seven-logo :light="true" size="md" />
                        </a>
                    </div>

                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-cyan-400/20 text-cyan-300 text-xs font-bold uppercase tracking-wider">
                            Autentikasi Akun
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Selamat Datang Kembali 👋
                    </h2>
                    <p class="text-blue-200/80 text-xs sm:text-sm mt-1">
                        Masukkan email dan password Anda untuk masuk ke sistem SevenKost.
                    </p>
                </div>

                {{-- Session Status Feedback --}}
                @if (session('status'))
                    <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs sm:text-sm flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

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
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="nama@email.com"
                                   class="w-full h-12 pl-10 pr-4 bg-white/10 border @error('email') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-blue-100 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-cyan-300 hover:text-cyan-200 font-bold hover:underline transition" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
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
                                   autocomplete="current-password" 
                                   placeholder="Masukkan password akun Anda"
                                   class="w-full h-12 pl-10 pr-11 bg-white/10 border @error('password') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    aria-label="Tampilkan sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-blue-300 hover:text-white active:scale-75 transition-all duration-150 focus:outline-none cursor-pointer">
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
                            <p class="mt-1.5 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="w-4 h-4 rounded-md border-white/30 bg-blue-950/60 text-cyan-400 focus:ring-cyan-400 focus:ring-offset-blue-900 cursor-pointer" 
                                   name="remember">
                            <span class="ms-2.5 text-xs text-blue-200 font-medium">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    {{-- Tombol Submit dengan Click Bounce --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full h-12 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm sm:text-base font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                            <span>Masuk ke Akun</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Register --}}
                <div class="mt-6 pt-5 border-t border-white/10 text-center text-xs sm:text-sm text-blue-200">
                    Belum memiliki akun SevenKost?
                    <a href="{{ route('register') }}" class="font-extrabold text-cyan-300 hover:text-white underline underline-offset-4 ml-1 transition">
                        Daftar Akun Baru
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

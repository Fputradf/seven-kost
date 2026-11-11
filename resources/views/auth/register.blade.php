<x-guest-layout title="Daftar Akun Baru - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden relative bg-white"
         x-data="{
             selectedRole: '{{ old('role', 'penghuni') }}',
             showPassword: false,
             showPasswordConfirmation: false,
             openTermsModal: false,
             activeTermsTab: 'terms',
             termsAgreed: false,
             setRole(role) {
                 this.selectedRole = role;
             },
             agreeAndClose() {
                 this.termsAgreed = true;
                 this.openTermsModal = false;
             }
         }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (BIRU CERAH DENGAN MULTI-LAYER WAVE) --}}
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
                        Daftar Akun SevenKost
                    </h1>
                    <p class="text-xs text-blue-100 mt-0.5 leading-relaxed hidden sm:block">
                        Pilih peran Anda dan nikmati kemudahan mencari hunian nyaman atau mengelola bisnis kos dalam satu aplikasi.
                    </p>
                </div>

                {{-- Gambar Kost (Desktop Saja) --}}
                <div class="hidden lg:block relative rounded-2xl overflow-hidden shadow-md border border-white/20 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-28 sm:h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold shadow-xs">
                            Kamar & Properti Pilihan
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            Terverifikasi Amanah
                        </span>
                    </div>
                </div>

                {{-- Penjelasan 3 Role (Desktop Saja) --}}
                <div class="hidden lg:block space-y-1.5">
                    <div class="text-[11px] font-semibold text-blue-100">
                        Pilih Peran Akun:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div @click="setRole('penghuni')"
                         :class="selectedRole === 'penghuni' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-xl p-2.5 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                                <h2 class="font-semibold text-xs text-white">Pencari / Penghuni Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'penghuni'" class="text-[9px] bg-white text-blue-700 font-bold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Cari kos berdasarkan kota & peta interaktif, booking kamar online, dan pantau status sewa.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div @click="setRole('pemilik')"
                         :class="selectedRole === 'pemilik' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-xl p-2.5 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                                <h2 class="font-semibold text-xs text-white">Pemilik / Pengelola Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'pemilik'" class="text-[9px] bg-white text-blue-700 font-bold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Pasang iklan kos nasional, atur kamar & tarif sewa, konfirmasi pemesanan, dan kelola penyewa.
                        </p>
                    </div>

                    {{-- Role 3: Mitra Layanan --}}
                    <div @click="setRole('mitra')"
                         :class="selectedRole === 'mitra' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-xl p-2.5 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-white text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                                <h2 class="font-semibold text-xs text-white">Mitra Layanan Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'mitra'" class="text-[9px] bg-white text-blue-700 font-bold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[11px] text-blue-100 pl-5.5 leading-normal">
                            Tawarkan jasa laundry, katering, kebersihan, atau servis teknisi langsung ke ekosistem kos.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MULTI-LAYER WAVE PATTERN TRANSITION (BERKUALITAS & NYATA) --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-12 sm:h-16" viewBox="0 0 1440 120" preserveAspectRatio="none">
                    {{-- Layer Gelombang Belakang (Semi-transparan untuk kedalaman) --}}
                    <path d="M0,45 C240,95 480,15 720,65 C960,115 1200,35 1440,75 L1440,120 L0,120 Z" class="fill-white/30"></path>
                    {{-- Layer Gelombang Depan (Solid White menyatu ke form) --}}
                    <path d="M0,60 C320,120 540,25 840,70 C1140,115 1320,40 1440,65 L1440,120 L0,120 Z" class="fill-white"></path>
                </svg>
            </div>

            {{-- Footer Layer Kiri: Hak Cipta (Desktop Saja) --}}
            <div class="hidden lg:flex p-5 lg:p-8 xl:p-10 pt-3 border-t border-blue-500/50 items-center justify-between text-[11px] text-blue-100">
                <span class="text-blue-200">SevenKost Indonesia</span>
                <span class="text-blue-200">© {{ date('Y') }}</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / BAWAH MOBILE (FORM PUTIH BERSIH & RESPONSIVE)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-start lg:justify-center items-center px-5 pt-3 pb-12 sm:p-8 lg:p-10 xl:p-12 flex-1 overflow-y-auto">
            <div class="max-w-sm xl:max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-3.5">
                    <span class="text-xs font-semibold text-blue-600 block mb-0.5">
                        Pendaftaran Akun
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                        Buat Akun SevenKost
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Lengkapi formulir di bawah ini untuk membuat akun baru Anda.
                    </p>
                </div>

                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-2.5">
                    @csrf

                    {{-- Hidden Role Input --}}
                    <input type="hidden" name="role" :value="selectedRole">

                    {{-- Switcher Role --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700">
                            Daftar Sebagai <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-1 p-1 bg-slate-100 rounded-xl">
                            <button type="button" 
                                    @click="setRole('penghuni')"
                                    :class="selectedRole === 'penghuni' ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-normal'"
                                    class="py-1.5 px-2 rounded-lg text-xs text-center transition cursor-pointer">
                                Penghuni
                            </button>
                            <button type="button" 
                                    @click="setRole('pemilik')"
                                    :class="selectedRole === 'pemilik' ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-normal'"
                                    class="py-1.5 px-2 rounded-lg text-xs text-center transition cursor-pointer">
                                Pemilik Kos
                            </button>
                            <button type="button" 
                                    @click="setRole('mitra')"
                                    :class="selectedRole === 'mitra' ? 'bg-blue-600 text-white font-semibold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-normal'"
                                    class="py-1.5 px-2 rounded-lg text-xs text-center transition cursor-pointer">
                                Mitra Layanan
                            </button>
                        </div>
                    </div>

                    {{-- Input Nama Lengkap --}}
                    <div class="space-y-1">
                        <label for="name" class="block text-xs font-medium text-slate-700">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Nama lengkap sesuai KTP"
                               class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-slate-900 placeholder-slate-400 @error('name') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('name')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

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
                               autocomplete="username" 
                               inputmode="email"
                               placeholder="nama@email.com"
                               class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Nomor WhatsApp / Telepon --}}
                    <div class="space-y-1">
                        <label for="phone" class="block text-xs font-medium text-slate-700">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input id="phone" 
                               type="tel" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               required 
                               autocomplete="tel" 
                               placeholder="081234567890"
                               class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-slate-900 placeholder-slate-400 @error('phone') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('phone')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Grid Input Password & Konfirmasi Password --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        {{-- Password --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-medium text-slate-700">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Minimal 8 karakter"
                                       class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 pl-3 pr-9 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        aria-label="Tampilkan kata sandi"
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
                                    <svg x-show="!showPassword" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPassword" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-medium text-slate-700">
                                Ulangi Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       :type="showPasswordConfirmation ? 'text' : 'password'"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Ulangi kata sandi"
                                       class="w-full text-sm sm:text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 pl-3 pr-9 text-slate-900 placeholder-slate-400 @error('password_confirmation') border-red-300 bg-red-50/20 @enderror transition" />
                                <button type="button" 
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        aria-label="Tampilkan konfirmasi sandi"
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
                                    <svg x-show="!showPasswordConfirmation" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror

                    {{-- Checkbox Syarat & Ketentuan dengan Pop Up Modal Trigger --}}
                    <div class="pt-1">
                        <label class="flex items-start gap-2 cursor-pointer select-none">
                            <input type="checkbox" 
                                   name="terms" 
                                   id="terms"
                                   x-model="termsAgreed"
                                   required 
                                   class="mt-0.5 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            <span class="text-[11px] sm:text-xs text-slate-600 leading-snug">
                                Saya menyetujui 
                                <button type="button" 
                                        @click.prevent="openTermsModal = true; activeTermsTab = 'terms'" 
                                        class="font-semibold text-blue-600 hover:text-blue-700 underline cursor-pointer">
                                    Syarat & Ketentuan
                                </button> 
                                dan 
                                <button type="button" 
                                        @click.prevent="openTermsModal = true; activeTermsTab = 'privacy'" 
                                        class="font-semibold text-blue-600 hover:text-blue-700 underline cursor-pointer">
                                    Kebijakan Privasi (UU PDP)
                                </button>.
                            </span>
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-1">
                        <button type="submit" 
                                class="w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-sm shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
                            <span>Daftar Akun Sekarang</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Link ke Login --}}
                <div class="mt-4 pt-3 border-t border-slate-100 text-center text-xs text-slate-600">
                    Sudah memiliki akun SevenKost?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Masuk ke Akun
                    </a>
                </div>

                {{-- Mobile Copyright Footer --}}
                <div class="mt-4 text-center lg:hidden text-[10px] text-slate-400">
                    SevenKost Indonesia © {{ date('Y') }}
                </div>

            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- MODAL POPUP: SYARAT & KETENTUAN DAN KEBIJAKAN PRIVASI         --}}
        {{-- ============================================================= --}}
        <div x-show="openTermsModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             @keydown.escape.window="openTermsModal = false">
            
            {{-- Klik Luar untuk Tutup --}}
            <div class="fixed inset-0" @click="openTermsModal = false"></div>

            {{-- Kotak Modal --}}
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] flex flex-col overflow-hidden border border-slate-200/80 z-10"
                 @click.stop>
                
                {{-- Header Modal --}}
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost" class="h-6 w-6 object-contain">
                        <h3 class="font-bold text-sm text-slate-900">Ketentuan Resmi SevenKost</h3>
                    </div>
                    <button type="button" 
                            @click="openTermsModal = false" 
                            class="text-slate-400 hover:text-slate-600 rounded-lg p-1 hover:bg-slate-200/60 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Tabs Navigasi di Modal --}}
                <div class="flex border-b border-slate-100 px-5 pt-2 gap-4 text-xs font-semibold">
                    <button type="button" 
                            @click="activeTermsTab = 'terms'"
                            :class="activeTermsTab === 'terms' ? 'text-blue-600 border-b-2 border-blue-600 pb-2.5' : 'text-slate-500 hover:text-slate-800 pb-2.5'"
                            class="transition cursor-pointer">
                        1. Syarat & Ketentuan Layanan
                    </button>
                    <button type="button" 
                            @click="activeTermsTab = 'privacy'"
                            :class="activeTermsTab === 'privacy' ? 'text-blue-600 border-b-2 border-blue-600 pb-2.5' : 'text-slate-500 hover:text-slate-800 pb-2.5'"
                            class="transition cursor-pointer">
                        2. Kebijakan Privasi (UU PDP)
                    </button>
                </div>

                {{-- Body Modal (Scrollable) --}}
                <div class="p-5 overflow-y-auto space-y-4 text-xs text-slate-600 leading-relaxed max-h-[50vh]">
                    
                    {{-- Tab 1: Syarat & Ketentuan Layanan --}}
                    <div x-show="activeTermsTab === 'terms'" class="space-y-4">
                        <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-100 text-blue-900">
                            <span class="font-semibold block mb-0.5">Ringkasan Ketentuan Layanan:</span>
                            Dengan mendaftar, Anda menyepakati interaksi yang aman, jujur, dan bertanggung jawab antara pencari kos, mitra pemilik kos, dan mitra layanan di SevenKost.
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">1</span>
                                Peran & Batasan Tanggung Jawab Platform
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                SevenKost memfasilitasi publikasi data kos terverifikasi, penentuan koordinat peta presisi, serta penyampaian minat sewa. SevenKost bukan pemilik fisik properti, melainkan penyedia sistem penghubung resmi.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">2</span>
                                Kewajiban Pengguna & Pemilik Kos
                            </h4>
                            <ul class="list-disc pl-9 space-y-1 text-slate-600">
                                <li>Wajib memberikan informasi yang benar, foto aktual kamar, tarif sewa yang transparan, dan ketersediaan nyata.</li>
                                <li>Dilarang mengunggah konten fiktif, properti palsu, atau gambar milik pihak lain tanpa hak cipta.</li>
                                <li>Iklan kos yang melanggar standar kenyamanan akan ditindak dan dinonaktifkan oleh tim verifikator SevenKost.</li>
                            </ul>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">3</span>
                                Alur Booking & Pembayaran Sewa
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Pengajuan sewa awal dicatat melalui sistem reservasi. Pembayaran sewa dilakukan langsung sesuai kesepakatan antara calon penyewa dan pengelola tanpa potongan perantara platform.
                            </p>
                        </div>
                    </div>

                    {{-- Tab 2: Kebijakan Privasi --}}
                    <div x-show="activeTermsTab === 'privacy'" class="space-y-4" style="display: none;">
                        <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 text-emerald-900">
                            <span class="font-semibold block mb-0.5">Komitmen Privasi UU No. 27 Tahun 2022:</span>
                            Data pribadi Anda dilindungi dengan standar keamanan modern. SevenKost tidak pernah menjual informasi pribadi Anda kepada pihak mana pun.
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold">1</span>
                                Jenis Data yang Kami Proses
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Kami memproses nama lengkap, alamat email aktif, nomor WhatsApp, serta data properti bagi pemilik kos untuk keperluan verifikasi akun dan komunikasi sewa.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold">2</span>
                                Tujuan Penggunaan Data
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Data digunakan terbatas untuk menghubungkan pencari kos dengan pemilik, mengirimkan status pemesanan, dan mencegah akun penipuan.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-semibold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold">3</span>
                                Kerahasiaan Dokumen & Hak Subjek Data
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Dokumen identitas verifikasi disimpan di area penyimpanan tertutup (private storage). Anda memiliki hak penuh untuk memperbarui, meminta koreksi data, atau menarik persetujuan akun Anda.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Footer Modal --}}
                <div class="px-5 py-3.5 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                    <span class="text-[11px] text-slate-500">SevenKost • Amanah & Terpercaya</span>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="openTermsModal = false" 
                                class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="button" 
                                @click="agreeAndClose()"
                                class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white transition shadow-xs cursor-pointer flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Saya Setuju</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

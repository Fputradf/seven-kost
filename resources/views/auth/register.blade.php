<x-guest-layout title="Daftar Akun Baru - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden relative"
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
        {{-- LAYER KIRI (BIRU CERAH 50% LEBAR - COMPACT NO SCROLL)          --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-blue-600 text-white p-5 sm:p-7 lg:p-8 xl:p-10 flex flex-col justify-between overflow-y-auto lg:overflow-hidden">
            <div>
                {{-- Tombol Navigasi Kembali ke Beranda di Bagian Atas Kiri --}}
                <div class="mb-3">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/95 hover:text-white transition group py-1 px-2.5 rounded-lg bg-blue-700/60 hover:bg-blue-700 border border-blue-400/40 w-fit">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {{-- Brand Header: Logo & Nama Aplikasi --}}
                <div class="flex items-center gap-2.5 mb-3">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-8 w-8 object-contain bg-white rounded-lg p-1 shadow-xs">
                    <div>
                        <span class="text-lg xl:text-xl font-black tracking-tight leading-none text-white block">
                            Seven<span class="text-sky-200">Kost</span>
                        </span>
                        <span class="text-[10px] text-blue-100 font-medium">Platform Cari & Kelola Kos</span>
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mb-3">
                    <h1 class="text-lg xl:text-xl font-extrabold leading-snug">
                        Daftar Akun SevenKost
                    </h1>
                    <p class="text-xs text-blue-100 mt-0.5 leading-relaxed">
                        Pilih peran Anda dan nikmati kemudahan mencari hunian nyaman atau mengelola bisnis kos dalam satu aplikasi.
                    </p>
                </div>

                {{-- Gambar Kost Sesuai Dashboard/Website (Compact) --}}
                <div class="relative rounded-xl overflow-hidden shadow-md border border-white/20 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-28 sm:h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2 py-0.5 rounded text-[10px] font-bold shadow-xs">
                            Kamar & Properti Pilihan
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2 py-0.5 rounded text-[10px] font-medium">
                            Terverifikasi Amanah
                        </span>
                    </div>
                </div>

                {{-- Penjelasan Role Singkat (3 Peran: Penghuni, Pemilik, Mitra) --}}
                <div class="space-y-1.5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-blue-200">
                        Pilih Peran Akun:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div @click="setRole('penghuni')"
                         :class="selectedRole === 'penghuni' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-lg p-2 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-white text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0">1</span>
                                <h2 class="font-bold text-xs text-white">Pencari / Penghuni Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'penghuni'" class="text-[9px] bg-white text-blue-700 font-extrabold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[10px] text-blue-100 pl-5.5 leading-tight">
                            Cari kos berdasarkan kota & peta interaktif, booking kamar online, dan pantau status sewa.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div @click="setRole('pemilik')"
                         :class="selectedRole === 'pemilik' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-lg p-2 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-white text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0">2</span>
                                <h2 class="font-bold text-xs text-white">Pemilik / Pengelola Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'pemilik'" class="text-[9px] bg-white text-blue-700 font-extrabold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[10px] text-blue-100 pl-5.5 leading-tight">
                            Pasang iklan kos nasional, atur kamar & tarif sewa, konfirmasi pemesanan, dan kelola penyewa.
                        </p>
                    </div>

                    {{-- Role 3: Mitra Layanan --}}
                    <div @click="setRole('mitra')"
                         :class="selectedRole === 'mitra' ? 'ring-2 ring-white bg-blue-700 shadow-sm' : 'bg-blue-700/60 opacity-80 hover:opacity-100'"
                         class="rounded-lg p-2 border border-blue-400/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-0.5">
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-white text-blue-600 flex items-center justify-center text-[10px] font-black shrink-0">3</span>
                                <h2 class="font-bold text-xs text-white">Mitra Layanan Kos</h2>
                            </div>
                            <span x-show="selectedRole === 'mitra'" class="text-[9px] bg-white text-blue-700 font-extrabold px-1.5 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-[10px] text-blue-100 pl-5.5 leading-tight">
                            Tawarkan jasa laundry, katering, kebersihan, atau servis teknisi langsung ke ekosistem kos.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Layer Kiri: Hak Cipta --}}
            <div class="pt-3 mt-3 border-t border-blue-500/50 flex items-center justify-between text-[11px] text-blue-100">
                <span class="text-blue-200">SevenKost Indonesia</span>
                <span class="text-blue-200">© {{ date('Y') }}</span>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN (PUTIH BERSIH 50% LEBAR - COMPACT NO SCROLL)       --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center p-5 sm:p-8 lg:p-10 xl:p-12 overflow-y-auto lg:overflow-hidden">
            <div class="max-w-sm xl:max-w-md w-full">

                {{-- Header Form --}}
                <div class="mb-3.5">
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block mb-0.5">
                        Pendaftaran Akun
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
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

                    {{-- Switcher Role (3 Pilihan: Penghuni, Pemilik, Mitra) --}}
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Daftar Sebagai <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-1 p-1 bg-slate-100 rounded-lg">
                            <button type="button" 
                                    @click="setRole('penghuni')"
                                    :class="selectedRole === 'penghuni' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-1.5 px-2 rounded-md text-[11px] sm:text-xs text-center transition cursor-pointer">
                                Penghuni
                            </button>
                            <button type="button" 
                                    @click="setRole('pemilik')"
                                    :class="selectedRole === 'pemilik' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-1.5 px-2 rounded-md text-[11px] sm:text-xs text-center transition cursor-pointer">
                                Pemilik Kos
                            </button>
                            <button type="button" 
                                    @click="setRole('mitra')"
                                    :class="selectedRole === 'mitra' ? 'bg-blue-600 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-1.5 px-2 rounded-md text-[11px] sm:text-xs text-center transition cursor-pointer">
                                Mitra Layanan
                            </button>
                        </div>
                    </div>

                    {{-- Input Nama Lengkap --}}
                    <div class="space-y-1">
                        <label for="name" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
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
                               class="w-full text-xs sm:text-sm rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-slate-900 placeholder-slate-400 @error('name') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('name')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Email --}}
                    <div class="space-y-1">
                        <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full text-xs sm:text-sm rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi & Konfirmasi (Grid 2 Kolom) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        {{-- Kata Sandi --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                                Kata Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Min. 8 karakter"
                                       class="w-full text-xs sm:text-sm rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 pl-3 pr-9 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
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
                            @error('password')
                                <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Kata Sandi --}}
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                                Ulangi Sandi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       :type="showPasswordConfirmation ? 'text' : 'password'"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Ulangi sandi"
                                       class="w-full text-xs sm:text-sm rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 pl-3 pr-9 text-slate-900 placeholder-slate-400 @error('password_confirmation') border-red-300 bg-red-50/20 @enderror transition" />
                                <button type="button" 
                                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        aria-label="Tampilkan sandi konfirmasi"
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
                            @error('password_confirmation')
                                <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Persetujuan UU PDP & Ketentuan Layanan (Modal Popup Interaktif) --}}
                    <div class="flex items-start gap-2 pt-0.5">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required 
                               x-model="termsAgreed"
                               class="w-3.5 h-3.5 mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 shrink-0 cursor-pointer">
                        <label for="terms" class="text-[11px] text-slate-600 leading-tight select-none cursor-pointer">
                            Saya menyetujui 
                            <button type="button" 
                                    @click.prevent="openTermsModal = true; activeTermsTab = 'terms'" 
                                    class="font-bold text-blue-600 hover:text-blue-700 hover:underline inline cursor-pointer">
                                Ketentuan Layanan
                            </button> 
                            serta pemrosesan data sesuai 
                            <button type="button" 
                                    @click.prevent="openTermsModal = true; activeTermsTab = 'privacy'" 
                                    class="font-bold text-blue-600 hover:text-blue-700 hover:underline inline cursor-pointer">
                                Kebijakan Privasi (UU PDP No. 27/2022)
                            </button>.
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-1">
                        <button type="submit" 
                                class="w-full py-2.5 px-4 rounded-lg text-xs sm:text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-sm shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer flex items-center justify-center gap-2">
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
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Masuk ke Akun
                    </a>
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
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-10 flex flex-col max-h-[85vh]">
                
                {{-- Header Modal --}}
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">
                            Regulasi & Kebijakan Platform
                        </h3>
                        <p class="text-xs text-slate-500">SevenKost Indonesia • Kepatuhan UU PDP No. 27/2022</p>
                    </div>
                    <button type="button" 
                            @click="openTermsModal = false"
                            aria-label="Tutup popup"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Tabs Switcher: Syarat Ketentuan vs Kebijakan Privasi --}}
                <div class="px-5 pt-3 border-b border-slate-100 flex gap-4 text-xs font-bold bg-white">
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
                            <span class="font-bold block mb-0.5">Ringkasan Ketentuan Layanan:</span>
                            Dengan mendaftar, Anda menyepakati interaksi yang aman, jujur, dan bertanggung jawab antara pencari kos, mitra pemilik kos, dan mitra layanan di SevenKost.
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">1</span>
                                Peran & Batasan Tanggung Jawab Platform
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                SevenKost memfasilitasi publikasi data kos terverifikasi, penentuan koordinat peta presisi, serta penyampaian minat sewa. SevenKost bukan pemilik fisik properti, melainkan penyedia sistem penghubung resmi.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">2</span>
                                Kewajiban Pengguna & Pemilik Kos
                            </h4>
                            <ul class="list-disc pl-9 space-y-1 text-slate-600">
                                <li>Wajib memberikan informasi yang benar, foto aktual kamar, tarif sewa yang transparan, dan ketersediaan nyata.</li>
                                <li>Dilarang mengunggah konten fiktif, properti palsu, atau gambar milik pihak lain tanpa hak cipta.</li>
                                <li>Iklan kos yang melanggar standar kenyamanan akan ditindak dan dinonaktifkan oleh tim verifikator SevenKost.</li>
                            </ul>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">3</span>
                                Alur Booking & Pembayaran Sewa
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Pengajuan sewa melalui fitur booking merupakan reservasi awal yang memerlukan konfirmasi pemilik kos. Pembayaran sewa dilakukan langsung sesuai kesepakatan kedua pihak tanpa potongan sepihak.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-black">4</span>
                                Laporan Pelanggaran & Sanksi
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Setiap pengguna berhak melaporkan listing atau pengguna yang mencurigakan melalui tombol pelaporan. Tim moderator SevenKost berhak menangguhkan akun pelanggar demi menjaga kenyamanan ekosistem.
                            </p>
                        </div>
                    </div>

                    {{-- Tab 2: Kebijakan Privasi (UU PDP No. 27/2022) --}}
                    <div x-show="activeTermsTab === 'privacy'" style="display: none;" class="space-y-4">
                        <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 text-emerald-900">
                            <span class="font-bold block mb-0.5">Kepatuhan UU No. 27 Tahun 2022 (UU PDP):</span>
                            SevenKost menjamin pelindungan data pribadi Anda secara ketat, tidak memperjualbelikan data pribadi, dan menggunakan enkripsi standar industri.
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-black">1</span>
                                Jenis Data Pribadi yang Dikumpulkan
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Data yang dikumpulkan terbatas pada nama lengkap, alamat surel (email), nomor telepon aktif, dan kata sandi terenkripsi guna keperluan pembuatan akun dan autentikasi.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-black">2</span>
                                Tujuan Pemrosesan Data
                            </h4>
                            <p class="pl-5.5 text-slate-600">
                                Data diproses secara sah untuk menghubungkan penyewa dengan pemilik kos terverifikasi, mengelola administrasi reservasi kamar, dan memastikan keamanan transaksi.
                            </p>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-black">3</span>
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
                                class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                            Tutup
                        </button>
                        <button type="button" 
                                @click="agreeAndClose()"
                                class="px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white transition shadow-xs cursor-pointer flex items-center gap-1.5">
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

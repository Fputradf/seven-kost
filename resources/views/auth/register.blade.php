<x-guest-layout title="Daftar Akun - SevenKost">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]"
         x-data="{
             selectedRole: '{{ old('role', 'penghuni') }}',
             showPassword: false,
             showPasswordConfirmation: false,
             setRole(role) {
                 this.selectedRole = role;
             }
         }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI (BIRU): LOGO, NAMA APLIKASI, & PENJELASAN ROLE     --}}
        {{-- ============================================================= --}}
        <div class="lg:col-span-5 bg-blue-600 text-white p-8 sm:p-10 flex flex-col justify-between relative">
            <div>
                {{-- Brand Header: Logo & Nama Aplikasi --}}
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" 
                         alt="Logo SevenKost" 
                         class="h-11 w-11 object-contain bg-white/10 rounded-xl p-1.5 border border-white/20">
                    <div>
                        <h1 class="text-2xl font-black tracking-tight leading-none text-white">
                            Seven<span class="text-sky-300">Kost</span>
                        </h1>
                        <p class="text-xs text-blue-100 font-medium mt-0.5">Platform Cari & Kelola Kos</p>
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mb-6">
                    <h2 class="text-xl sm:text-2xl font-extrabold leading-snug">
                        Daftar Akun SevenKost
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100 mt-2 leading-relaxed">
                        Pilih peran yang sesuai dengan kebutuhan Anda untuk mendapatkan pengalaman terbaik di platform kami.
                    </p>
                </div>

                {{-- Penjelasan Role Singkat --}}
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-wider text-blue-200">
                        Panduan Memilih Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div @click="setRole('penghuni')"
                         :class="selectedRole === 'penghuni' ? 'ring-2 ring-white bg-blue-700' : 'bg-blue-700/60 opacity-85 hover:opacity-100'"
                         class="rounded-xl p-4 border border-blue-500/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">1</span>
                                <h3 class="font-bold text-sm text-white">Pencari / Penghuni Kos</h3>
                            </div>
                            <span x-show="selectedRole === 'penghuni'" class="text-[10px] bg-white text-blue-700 font-bold px-2 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-xs text-blue-100 leading-relaxed pl-8">
                            Untuk mahasiswa atau pekerja yang mencari hunian kos, booking kamar, dan mengelola sewa bulanan.
                        </p>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div @click="setRole('pemilik')"
                         :class="selectedRole === 'pemilik' ? 'ring-2 ring-white bg-blue-700' : 'bg-blue-700/60 opacity-85 hover:opacity-100'"
                         class="rounded-xl p-4 border border-blue-500/40 cursor-pointer transition">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">2</span>
                                <h3 class="font-bold text-sm text-white">Pemilik / Pengelola Kos</h3>
                            </div>
                            <span x-show="selectedRole === 'pemilik'" class="text-[10px] bg-white text-blue-700 font-bold px-2 py-0.5 rounded-full">Dipilih</span>
                        </div>
                        <p class="text-xs text-blue-100 leading-relaxed pl-8">
                            Untuk juragan atau agen kos yang ingin memasang iklan kos, kelola ketersediaan kamar, dan pantau penyewa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Layer Kiri: Link Kembali ke Beranda --}}
            <div class="pt-6 mt-6 border-t border-blue-500/40 flex items-center justify-between text-xs text-blue-100">
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
        {{-- LAYER KANAN (PUTIH): KHUSUS FORM FIELD                        --}}
        {{-- ============================================================= --}}
        <div class="lg:col-span-7 bg-white p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
            <div class="max-w-md w-full mx-auto">

                {{-- Header Form --}}
                <div class="mb-5">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Pendaftaran Akun
                    </h2>
                    <p class="text-sm text-slate-600 mt-1">
                        Lengkapi formulir di bawah untuk membuat akun baru.
                    </p>
                </div>

                {{-- Form Register --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Hidden Role Input --}}
                    <input type="hidden" name="role" :value="selectedRole">

                    {{-- Pilihan Role (Switcher Cepat di Form) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Daftar Sebagai:
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-xl">
                            <button type="button" 
                                    @click="setRole('penghuni')"
                                    :class="selectedRole === 'penghuni' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-2.5 px-3 rounded-lg text-xs sm:text-sm text-center transition cursor-pointer">
                                Pencari / Penghuni
                            </button>
                            <button type="button" 
                                    @click="setRole('pemilik')"
                                    :class="selectedRole === 'pemilik' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-slate-700 hover:text-slate-900 font-medium'"
                                    class="py-2.5 px-3 rounded-lg text-xs sm:text-sm text-center transition cursor-pointer">
                                Pemilik Kos
                            </button>
                        </div>
                    </div>

                    {{-- Input Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Contoh: Budi Prasetyo"
                               class="w-full px-4 py-3 rounded-xl border @error('name') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi & Konfirmasi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        {{-- Kata Sandi --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input id="password" 
                                       :type="showPassword ? 'text' : 'password'"
                                       name="password" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Min. 8 karakter"
                                       class="w-full pl-4 pr-10 py-3 rounded-xl border @error('password') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        aria-label="Tampilkan sandi"
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
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Kata Sandi --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Ulangi Sandi
                            </label>
                            <div class="relative">
                                <input id="password_confirmation" 
                                       :type="showPasswordConfirmation ? 'text' : 'password'"
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password" 
                                       placeholder="Ulangi sandi"
                                       class="w-full pl-4 pr-10 py-3 rounded-xl border @error('password_confirmation') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
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
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
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
                <div class="mt-5 pt-4 border-t border-slate-200 text-center text-xs sm:text-sm text-slate-600">
                    Sudah memiliki akun SevenKost?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Masuk ke Akun
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

<x-guest-layout title="Masuk - SevenKost">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]"
         x-data="{ showPassword: false }">

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
                        Satu Pintu untuk Seluruh Kebutuhan Kos
                    </h2>
                    <p class="text-xs sm:text-sm text-blue-100 mt-2 leading-relaxed">
                        Masuk untuk mengakses layanan pencarian kos terverifikasi atau kelola properti kos Anda dengan mudah.
                    </p>
                </div>

                {{-- Penjelasan Role Singkat --}}
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-wider text-blue-200">
                        Akses Berdasarkan Peran:
                    </div>

                    {{-- Role 1: Pencari / Penghuni --}}
                    <div class="bg-blue-700/60 rounded-xl p-4 border border-blue-500/40">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">1</span>
                            <h3 class="font-bold text-sm text-white">Pencari / Penghuni Kos</h3>
                        </div>
                        <ul class="text-xs text-blue-100 space-y-1 pl-8 list-disc">
                            <li>Cari kos terverifikasi & booking online</li>
                            <li>Pantau status sewa & riwayat pembayaran</li>
                            <li>Akses layanan kebutuhan harian kos</li>
                        </ul>
                    </div>

                    {{-- Role 2: Pemilik Kos --}}
                    <div class="bg-blue-700/60 rounded-xl p-4 border border-blue-500/40">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-xs font-bold">2</span>
                            <h3 class="font-bold text-sm text-white">Pemilik / Pengelola Kos</h3>
                        </div>
                        <ul class="text-xs text-blue-100 space-y-1 pl-8 list-disc">
                            <li>Pasang iklan kos & kelola kamar</li>
                            <li>Terima dan konfirmasi booking sewa</li>
                            <li>Pantau data penyewa secara rapi</li>
                        </ul>
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
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Masuk ke Akun
                    </h2>
                    <p class="text-sm text-slate-600 mt-1">
                        Silakan masukkan alamat email dan kata sandi Anda.
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
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Kata Sandi
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
                                   placeholder="Masukkan kata sandi"
                                   class="w-full pl-4 pr-11 py-3 rounded-xl border @error('password') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    aria-label="Tampilkan sandi"
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
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
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
                <div class="mt-6 pt-5 border-t border-slate-200 text-center text-xs sm:text-sm text-slate-600">
                    Belum memiliki akun SevenKost?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                        Daftar Akun Baru
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>

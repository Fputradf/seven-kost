<x-guest-layout title="Atur Ulang Kata Sandi - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden bg-white" x-data="{ showPassword: false, showPasswordConfirmation: false }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (NAVY GELAP IDENTIK DENGAN LOGIN)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-gradient-to-br from-[#031d44] via-[#05377a] to-[#020b1f] text-white flex flex-col justify-between shrink-0 overflow-hidden relative">
            
            {{-- Glow samar biru --}}
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-400/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- MOBILE BRAND HEADER (LOGO & BRAND DI TENGAH SESUAI LOGIN) --}}
            <div class="flex flex-col items-center justify-center pt-8 pb-3 lg:hidden text-center z-10">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center">
                    <img src="{{ app_logo_url('dark') }}" 
                         alt="Logo {{ app_name() }}" 
                         class="h-14 w-14 object-contain mb-2">
                    <span class="text-xl font-bold tracking-tight text-white block">
                        {{ app_name() }}
                    </span>
                    <span class="text-[10px] text-blue-200 font-medium block mt-0.5">{{ setting('app_slogan', 'Platform Cari & Kelola Kos') }}</span>
                </a>
            </div>

            {{-- DESKTOP HERO CONTENT (HANYA DESKTOP) --}}
            <div class="hidden lg:block p-8 xl:p-10 relative z-10 w-full max-w-lg xl:max-w-xl mx-auto my-auto">
                {{-- Top Navigation: Tombol Kembali ke Halaman Masuk --}}
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white hover:text-white transition group py-1.5 px-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 w-fit backdrop-blur-md shadow-xs">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Halaman Masuk</span>
                    </a>
                </div>

                {{-- Brand Header Desktop --}}
                <div class="flex items-center gap-2.5 mb-3.5">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ app_logo_url('dark') }}" 
                             alt="Logo {{ app_name() }}" 
                             class="h-8 w-8 object-contain">
                        <div>
                            <span class="text-lg xl:text-xl font-bold tracking-tight leading-none text-white block">
                                {{ app_name() }}
                            </span>
                            <span class="text-[11px] text-blue-200 font-medium block">{{ setting('app_slogan', 'Platform Cari & Kelola Kos') }}</span>
                        </div>
                    </a>
                </div>

                {{-- Headline Ringkas --}}
                <div class="mb-3">
                    <h1 class="text-lg lg:text-xl font-bold leading-snug text-white">
                        Perbarui Kata Sandi <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">Dengan Aman</span>
                    </h1>
                    <p class="text-xs text-blue-100/90 mt-0.5 leading-relaxed">
                        Gunakan kombinasi karakter yang kuat agar akun SevenKost Anda selalu terlindungi.
                    </p>
                </div>

                {{-- Gambar Kost (Desktop Saja) --}}
                <div class="relative rounded-2xl overflow-hidden shadow-md border border-white/15 mb-3 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-32 xl:h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020b1f]/85 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5 right-2.5 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold shadow-xs">
                            Enkripsi Argon2 / Bcrypt
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            Akses Terlindungi
                        </span>
                    </div>
                </div>

                {{-- Tips Kata Sandi Aman --}}
                <div class="space-y-1.5">
                    <div class="text-[11px] font-semibold text-blue-200">
                        Tips Kata Sandi Aman:
                    </div>

                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                            <h2 class="font-semibold text-xs text-white">Minimal 8 Karakter</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Gunakan panjang kata sandi yang memadai untuk mencegah percobaan tebakan brute-force.
                        </p>
                    </div>

                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                            <h2 class="font-semibold text-xs text-white">Kombinasi Huruf & Simbol</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Padukan huruf besar, huruf kecil, angka, dan karakter khusus seperti (!, @, #).
                        </p>
                    </div>

                    <div class="bg-white/10 rounded-xl p-2.5 border border-white/15 backdrop-blur-md">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                            <h2 class="font-semibold text-xs text-white">Jangan Gunakan Password Lama</h2>
                        </div>
                        <p class="text-[11px] text-blue-100/90 pl-5.5 leading-normal">
                            Hindari menggunakan kata sandi yang sama dengan akun platform lain.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MULTI-LAYER WAVE PATTERN TRANSITION (PUTIH SESUAI LOGIN)   --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-28 sm:h-36" viewBox="0 0 1440 320" preserveAspectRatio="none">
                    {{-- Layer Aksen Wave Putih Bersih (Samakan dengan Login Blade) --}}
                    <path d="M0,60 C460,260 840,80 1160,180 C1280,220 1380,210 1440,200 L1440,320 L0,320 Z" fill="#ffffff"></path>
                    {{-- Layer Depan Solid White (Menyatu Mulus ke Form) --}}
                    <path d="M0,120 C460,290 840,120 1160,210 C1280,250 1380,240 1440,230 L1440,320 L0,320 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / BAWAH MOBILE (FORM PUTIH ELEGAN SESUAI LOGIN)  --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-1/2 bg-white flex flex-col items-center px-6 py-6 sm:p-8 lg:p-10 xl:p-12 flex-1 relative z-10">
            <div class="max-w-sm xl:max-w-md w-full my-auto">

                {{-- Header Form di Tengah Sesuai Gambar Inspirasi --}}
                <div class="mb-5 sm:mb-6 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-50 text-[#083873] mb-3 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">
                        Atur Ulang Kata Sandi
                    </h1>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                        Masukkan kata sandi baru untuk memulihkan akses ke akun SevenKost Anda.
                    </p>
                </div>

                {{-- Form Simpan Password Baru --}}
                <form method="POST" action="{{ route('password.store') }}" class="space-y-3.5">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Input Email --}}
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-medium text-slate-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $request->email) }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3 px-4 text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50/20 @enderror transition" />
                        @error('email')
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Kata Sandi Baru --}}
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-medium text-slate-700">
                            Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   :type="showPassword ? 'text' : 'password'"
                                   name="password" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Minimal 8 karakter"
                                   class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3 pl-4 pr-11 text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50/20 @enderror transition" />
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

                    {{-- Input Konfirmasi Kata Sandi Baru --}}
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-xs font-medium text-slate-700">
                            Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   :type="showPasswordConfirmation ? 'text' : 'password'"
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full text-sm rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3 pl-4 pr-11 text-slate-900 placeholder-slate-400 @error('password_confirmation') border-red-300 bg-red-50/20 @enderror transition" />
                            <button type="button" 
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    aria-label="Tampilkan konfirmasi kata sandi"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
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
                            <p class="text-xs text-red-600 font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit Pill Rounded Warna #083873 Sesuai Login Blade --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3 px-6 rounded-full bg-[#083873] hover:bg-[#062c5a] text-white font-bold text-sm shadow-md shadow-[#083873]/25 active:scale-[0.99] transition cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Kata Sandi Baru</span>
                        </button>
                    </div>

                    {{-- Link Kembali ke Login Tepat di Bawah Tombol --}}
                    <div class="text-center text-xs text-slate-500 pt-1">
                        Kembali ke halaman masuk? 
                        <a href="{{ route('login') }}" class="font-bold text-[#083873] hover:underline ml-0.5">
                            Masuk Sekarang
                        </a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-guest-layout>

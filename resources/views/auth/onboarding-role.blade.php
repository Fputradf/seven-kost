<x-guest-layout title="Pilih Peran Akun - SevenKost">
    <div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row lg:overflow-hidden bg-white" x-data="{ selectedRole: 'penghuni' }">

        {{-- ============================================================= --}}
        {{-- LAYER KIRI / ATAS MOBILE (NAVY GELAP SEPERTI LOGIN & HERO)   --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-5/12 bg-gradient-to-br from-[#031d44] via-[#05377a] to-[#020b1f] text-white flex flex-col justify-between shrink-0 overflow-hidden relative">
            
            {{-- Glow samar biru --}}
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-400/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- MOBILE BRAND HEADER --}}
            <div class="flex flex-col items-center justify-center pt-8 pb-3 lg:hidden text-center z-10">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center">
                    <img src="{{ app_logo_url() }}" 
                         alt="Logo {{ app_name() }}" 
                         class="h-14 w-14 object-contain mb-2">
                    <span class="text-xl font-bold tracking-tight text-white block">
                        {{ app_name() }}
                    </span>
                    <span class="text-[10px] text-blue-200 font-medium block mt-0.5">{{ setting('app_slogan', 'Platform Cari & Kelola Kos') }}</span>
                </a>
            </div>

            {{-- DESKTOP HERO CONTENT --}}
            <div class="hidden lg:block p-8 xl:p-10 relative z-10 w-full max-w-md mx-auto my-auto">
                {{-- Brand Header Desktop --}}
                <div class="flex items-center gap-2.5 mb-5">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ app_logo_url() }}" 
                             alt="Logo {{ app_name() }}" 
                             class="h-9 w-9 object-contain">
                        <div>
                            <span class="text-xl font-bold tracking-tight leading-none text-white block">
                                {{ app_name() }}
                            </span>
                            <span class="text-[11px] text-blue-200 font-medium block mt-0.5">{{ setting('app_slogan', 'Platform Cari & Kelola Kos') }}</span>
                        </div>
                    </a>
                </div>

                {{-- Headline Ringkas --}}
                <div class="mb-5">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-400/20 text-sky-200 border border-sky-400/30 text-xs font-semibold mb-3">
                        <svg class="w-3.5 h-3.5 text-sky-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Terhubung dengan Akun Google</span>
                    </div>
                    <h1 class="text-xl xl:text-2xl font-bold leading-snug text-white">
                        Satu Akun untuk Semua <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-blue-200">Kebutuhan Kos</span>
                    </h1>
                    <p class="text-xs text-blue-100/90 mt-1.5 leading-relaxed">
                        Pilih peran utama Anda untuk menyesuaikan fitur dashboard, navigasi, dan kemudahan transaksi Anda di SevenKost.
                    </p>
                </div>

                {{-- Gambar Kos --}}
                <div class="relative rounded-2xl overflow-hidden shadow-md border border-white/15 mb-4 group">
                    <img src="{{ asset('images/frontend/hero-building-hd.jpg') }}" 
                         alt="Hunian Kos SevenKost" 
                         class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020b1f]/85 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-white">
                        <span class="bg-blue-600/90 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-semibold">
                            Personalisasi Fitur
                        </span>
                        <span class="bg-black/50 backdrop-blur-xs px-2.5 py-0.5 rounded-lg text-[10px] font-medium">
                            Aman & Resmi
                        </span>
                    </div>
                </div>

                {{-- Catatan Privasi Singkat --}}
                <div class="text-[11px] text-blue-200/80 leading-normal flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Peran dapat Anda perbarui atau sesuaikan kembali melalui menu pengaturan profil.</span>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MULTI-LAYER WAVE PATTERN TRANSITION (PUTIH SESUAI LOGIN)   --}}
            {{-- ========================================================= --}}
            <div class="w-full overflow-hidden leading-none lg:hidden pointer-events-none -mb-[1px]">
                <svg class="relative block w-full h-28 sm:h-36" viewBox="0 0 1440 320" preserveAspectRatio="none">
                    <path d="M0,60 C460,260 840,80 1160,180 C1280,220 1380,210 1440,200 L1440,320 L0,320 Z" fill="#ffffff"></path>
                    <path d="M0,120 C460,290 840,120 1160,210 C1280,250 1380,240 1440,230 L1440,320 L0,320 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        {{-- ============================================================= --}}
        {{-- LAYER KANAN / FORM PEMILIHAN PERAN (PUTIH BERSIH)           --}}
        {{-- ============================================================= --}}
        <div class="w-full lg:w-7/12 bg-white flex flex-col items-center px-6 py-6 sm:p-8 lg:p-10 xl:p-12 flex-1 relative z-10 overflow-y-auto">
            <div class="max-w-lg w-full my-auto">

                {{-- Header Form Sambutan --}}
                <div class="mb-5 sm:mb-6 text-center">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" 
                             alt="{{ $user->name }}" 
                             class="w-14 h-14 rounded-full mx-auto mb-2.5 border-2 border-[#083873]/20 shadow-xs object-cover">
                    @else
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-50 text-[#083873] mb-2.5 shadow-2xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif

                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">
                        Selamat Datang di SevenKost !
                    </h1>
                    <p class="text-xs text-slate-600 mt-1">
                        Halo <span class="font-semibold text-[#083873]">{{ $user->name }}</span>, silakan pilih peran akun Anda untuk melanjutkan:
                    </p>
                </div>

                {{-- Form Pemilihan Role --}}
                <form method="POST" action="{{ route('onboarding.role.store') }}" class="space-y-3.5">
                    @csrf
                    <input type="hidden" name="role" :value="selectedRole">

                    {{-- Pilihan 1: Pencari / Penghuni Kos --}}
                    <div @click="selectedRole = 'penghuni'"
                         :class="selectedRole === 'penghuni' 
                            ? 'ring-2 ring-[#083873] bg-blue-50/50 border-[#083873] shadow-sm' 
                            : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50'"
                         class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-start gap-3.5 select-none relative group">
                        
                        <div :class="selectedRole === 'penghuni' ? 'bg-[#083873] text-white' : 'bg-slate-100 text-slate-600'"
                             class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>

                        <div class="flex-1 pr-6">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-800">Pencari / Penghuni Kos</h3>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Paling Umum</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Cari kos impian di seluruh Indonesia, jelajahi peta interaktif, dan ajukan booking kamar secara mudah.
                            </p>
                        </div>

                        <div class="absolute top-4 right-4">
                            <span :class="selectedRole === 'penghuni' ? 'border-[#083873] bg-[#083873]' : 'border-slate-300 bg-white'"
                                  class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition">
                                <svg x-show="selectedRole === 'penghuni'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Pilihan 2: Pemilik / Pengelola Kos --}}
                    <div @click="selectedRole = 'pemilik'"
                         :class="selectedRole === 'pemilik' 
                            ? 'ring-2 ring-[#083873] bg-blue-50/50 border-[#083873] shadow-sm' 
                            : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50'"
                         class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-start gap-3.5 select-none relative group">
                        
                        <div :class="selectedRole === 'pemilik' ? 'bg-[#083873] text-white' : 'bg-slate-100 text-slate-600'"
                             class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>

                        <div class="flex-1 pr-6">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-800">Pemilik / Pengelola Kos</h3>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Mitra Properti</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Pasang iklan kamar kos Anda, tentukan harga & fasilitas, serta pantau pemesanan dan penyewa langsung.
                            </p>
                        </div>

                        <div class="absolute top-4 right-4">
                            <span :class="selectedRole === 'pemilik' ? 'border-[#083873] bg-[#083873]' : 'border-slate-300 bg-white'"
                                  class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition">
                                <svg x-show="selectedRole === 'pemilik'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Pilihan 3: Mitra Layanan Kos --}}
                    <div @click="selectedRole = 'mitra'"
                         :class="selectedRole === 'mitra' 
                            ? 'ring-2 ring-[#083873] bg-blue-50/50 border-[#083873] shadow-sm' 
                            : 'border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50/50'"
                         class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-start gap-3.5 select-none relative group">
                        
                        <div :class="selectedRole === 'mitra' ? 'bg-[#083873] text-white' : 'bg-slate-100 text-slate-600'"
                             class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <div class="flex-1 pr-6">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-800">Mitra Layanan Kos</h3>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Penyedia Jasa</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                Tawarkan jasa laundry, kebersihan, katering makanan, servis AC, atau perbaikan teknisi ke kos-kosan.
                            </p>
                        </div>

                        <div class="absolute top-4 right-4">
                            <span :class="selectedRole === 'mitra' ? 'border-[#083873] bg-[#083873]' : 'border-slate-300 bg-white'"
                                  class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition">
                                <svg x-show="selectedRole === 'mitra'" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Tombol Submit Lanjutkan ke Dashboard --}}
                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full py-3.5 px-6 rounded-full bg-[#083873] hover:bg-[#062c5a] text-white font-bold text-sm shadow-md shadow-[#083873]/25 active:scale-[0.99] transition cursor-pointer flex items-center justify-center gap-2">
                            <span>Lanjutkan ke Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-guest-layout>

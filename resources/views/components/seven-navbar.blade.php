@props(['transparent' => false])

@php
    $isHome = request()->is('/');
    $enableTransparent = $transparent || $isHome;
@endphp

<nav 
    x-data="{ 
        scrolled: {{ $enableTransparent ? 'false' : 'true' }}, 
        mobileMenuOpen: false, 
        userDropdownOpen: false,
        isTransparent: {{ $enableTransparent ? 'true' : 'false' }}
    }" 
    x-init="if (isTransparent) { scrolled = window.scrollY > 50 }"
    @scroll.window="if (isTransparent) { scrolled = window.scrollY > 50 }"
    class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300"
    :class="scrolled 
        ? 'bg-white/95 backdrop-blur-md shadow-xs border-b border-slate-200/80 text-slate-800' 
        : 'bg-transparent border-b border-white/10 text-white shadow-none'"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 sm:h-20 gap-4">
            
            {{-- Left: Logo Brand (Dinamis dari Pengaturan Aplikasi & Mode Gelap/Terang) --}}
            <div class="flex items-center flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group focus:outline-none">
                    <img src="{{ app_logo_url('light') }}" 
                         x-show="scrolled"
                         alt="{{ app_name() }} Logo" 
                         class="h-9 w-9 sm:h-10 sm:w-10 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
                    <img src="{{ app_logo_url('dark') }}" 
                         x-show="!scrolled"
                         alt="{{ app_name() }} Logo" 
                         class="h-9 w-9 sm:h-10 sm:w-10 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
                    <span class="text-xl sm:text-2xl font-black tracking-tight transition-colors"
                          :class="scrolled ? 'text-slate-900 group-hover:text-blue-600' : 'text-white group-hover:text-sky-200'">
                        {{ app_name() }}
                    </span>
                </a>
            </div>

            {{-- Center: Desktop Nav Links (Clean Text Tanpa Icon) --}}
            <div class="hidden md:flex items-center gap-4 lg:gap-6 text-xs sm:text-[13px] lg:text-[14px] font-bold">
                @if(Auth::check() && Auth::user()->hasRole('penghuni'))
                    {{-- Navigasi Khusus Penghuni Kos (Eksplorasi & Fitur Hunian) --}}
                    <a href="{{ route('tenant.dashboard') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('tenant.dashboard') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('tenant.dashboard') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Dashboard
                    </a>

                    <a href="{{ route('tenant.bookings.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('tenant.bookings*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('tenant.bookings*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Kamar Saya
                    </a>

                    <a href="{{ route('tenant.wishlists.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('tenant.wishlists*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('tenant.wishlists*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Favorit
                    </a>

                    <a href="{{ route('tenant.complaints.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('tenant.complaints*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('tenant.complaints*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Keluhan
                    </a>

                    <a href="{{ route('search') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('search*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('search*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Cari Kos
                    </a>

                    <a href="{{ route('map') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('map*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('map*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Peta Kos
                    </a>

                    <a href="{{ route('marketplace.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('marketplace*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('marketplace*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Marketplace
                    </a>

                    <a href="{{ route('services.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('layanan*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('layanan*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Layanan
                    </a>
                @else
                    {{-- 1. Beranda --}}
                    <a href="{{ url('/') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('/') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('/') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Beranda
                    </a>

                    {{-- 2. Pasang Iklan --}}
                    <a href="{{ url('/paket-iklan') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('paket-iklan*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('paket-iklan*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Pasang Iklan
                    </a>

                    {{-- 3. Layanan --}}
                    <a href="{{ route('services.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('layanan*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('layanan*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Layanan
                    </a>

                    {{-- 4. Marketplace --}}
                    <a href="{{ route('marketplace.index') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('marketplace*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('marketplace*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Marketplace
                    </a>

                    {{-- 5. FAQ --}}
                    <a href="{{ url('/bantuan') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->is('bantuan*') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->is('bantuan*') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        FAQ
                    </a>

                    {{-- 6. Syarat dan Ketentuan --}}
                    <a href="{{ route('terms.conditions') }}" 
                       class="transition-colors pb-1"
                       :class="scrolled 
                           ? '{{ request()->routeIs('terms.conditions') ? 'text-blue-600 border-b-2 border-blue-600 font-extrabold' : 'text-slate-700 hover:text-blue-600' }}' 
                           : '{{ request()->routeIs('terms.conditions') ? 'text-sky-300 border-b-2 border-sky-300 font-extrabold' : 'text-white/90 hover:text-sky-200' }}'">
                        Syarat & Ketentuan
                    </a>
                @endif
            </div>

            {{-- Right: Auth Buttons (Masuk & Daftar) or User Dropdown --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    {{-- User Profile Menu (Transparent, No Rounded Border/Pill) --}}
                    <div class="relative" @click.outside="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" 
                                type="button" 
                                class="group flex items-center gap-2 py-1 px-1.5 text-xs transition bg-transparent focus:outline-none"
                                :class="scrolled ? 'text-slate-800 hover:text-blue-600' : 'text-white hover:text-sky-200'">
                            <div class="h-7 w-7 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-2xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-semibold max-w-[110px] truncate hidden md:inline leading-none">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="h-3.5 w-3.5 flex-shrink-0 transition-colors" 
                                 :class="scrolled ? 'text-slate-400 group-hover:text-blue-600' : 'text-white/70 group-hover:text-sky-200'" 
                                 viewBox="0 0 20 20" 
                                 fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="userDropdownOpen" 
                             x-transition 
                             class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-2xl ring-1 ring-black/5 text-slate-800" 
                             style="display: none;">
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                <div class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                    {{ Auth::user()->hasRole('penghuni') ? 'Portal Penghuni' : (Auth::user()->getRoleNames()->first() ?? 'Member') }}
                                </div>
                            </div>

                            @if(Auth::user()->hasAnyRole(['superadmin', 'admin']))
                                <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Panel Admin
                                </a>
                            @endif

                            @if(Auth::user()->hasRole('pemilik'))
                                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Dasbor Pemilik
                                </a>

                                <a href="{{ route('owner.kosts.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Kelola Iklan Kos
                                </a>

                                <a href="{{ route('owner.tenants.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Daftar Penyewa
                                </a>

                                <a href="{{ route('owner.promosi.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Promosi Kos
                                </a>
                            @else
                                {{-- Menu Khusus Penghuni / Penyewa (Monochrome Icons) --}}
                                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    Dashboard Penghuni
                                </a>

                                <a href="{{ route('tenant.bookings.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Kamar & Booking Saya
                                </a>

                                <a href="{{ route('tenant.wishlists.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    Kos Favorit
                                </a>

                                <a href="{{ route('tenant.complaints.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Keluhan Fasilitas
                                </a>

                                <a href="{{ route('tenant.complaints.create') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Lapor Kendala Baru
                                </a>
                            @endif

                            <a href="{{ route('marketplace.create') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Jual Barang Kos
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>

                            <a href="{{ route('terms.conditions') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Syarat & Ketentuan
                            </a>

                            <div class="my-1 border-t border-slate-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Tombol Masuk --}}
                    <a href="{{ route('login') }}" 
                       class="px-5 py-2 text-xs sm:text-sm font-bold rounded-xl transition"
                       :class="scrolled 
                           ? 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50' 
                           : 'border border-white/40 text-white hover:bg-white/10'">
                        Masuk
                    </a>

                    {{-- Tombol Daftar CTA --}}
                    <a href="{{ route('register') }}" 
                       class="px-5 py-2 text-xs sm:text-sm font-bold rounded-xl transition shadow-md"
                       :class="scrolled 
                           ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-500/20' 
                           : 'bg-blue-600 hover:bg-blue-500 text-white shadow-blue-600/30'">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex md:hidden items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        type="button" 
                        class="p-2 rounded-lg focus:outline-none transition"
                        :class="scrolled ? 'text-slate-800 hover:bg-slate-100' : 'text-white hover:bg-white/10'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown (Drawer Adaptif: Putih saat scrolled, Dark Navy saat di atas hero transparan) --}}
    <div x-show="mobileMenuOpen" 
         x-transition 
         class="md:hidden border-t px-4 pt-3 pb-6 space-y-3 shadow-xl transition-colors"
         :class="scrolled 
             ? 'bg-white border-slate-100 text-slate-800' 
             : 'bg-[#0A192F]/98 backdrop-blur-xl border-white/10 text-white'"
         style="display: none;">
        <div class="space-y-1 text-sm font-bold pt-1">
            @if(Auth::check() && Auth::user()->hasRole('penghuni'))
                {{-- Menu Khusus Penghuni --}}
                <a href="{{ route('tenant.dashboard') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('tenant.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('tenant.dashboard') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Dashboard Penghuni
                </a>
                <a href="{{ route('tenant.bookings.index') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('tenant.bookings*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('tenant.bookings*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Kamar & Booking Saya
                </a>
                <a href="{{ route('tenant.wishlists.index') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('tenant.wishlists*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('tenant.wishlists*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Kos Favorit
                </a>
                <a href="{{ route('tenant.complaints.index') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('tenant.complaints*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('tenant.complaints*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Keluhan Fasilitas
                </a>
                <a href="{{ route('search') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('search*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('search*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Cari Kos
                </a>
                <a href="{{ route('map') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('map*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('map*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Peta Interaktif Kos
                </a>
                <a href="{{ route('marketplace.index') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('marketplace*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('marketplace*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Marketplace Barang Kos
                </a>
                <a href="{{ route('services.index') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('layanan*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('layanan*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Layanan Kebutuhan Kos
                </a>
                <a href="{{ url('/bantuan') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('bantuan*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('bantuan*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Bantuan / FAQ
                </a>
            @else
                <a href="{{ url('/') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('/') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('/') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Beranda
                </a>
                <a href="{{ url('/paket-iklan') }}" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('paket-iklan*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('paket-iklan*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Pasang Iklan
                </a>
                <a href="{{ route('services.index') }}" 
                   @click="mobileMenuOpen = false" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('layanan*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('layanan*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Layanan
                </a>
                <a href="{{ route('marketplace.index') }}" 
                   @click="mobileMenuOpen = false" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('marketplace*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('marketplace*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Marketplace
                </a>
                <a href="{{ url('/bantuan') }}" 
                   @click="mobileMenuOpen = false" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->is('bantuan*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->is('bantuan*') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    FAQ
                </a>
                <a href="{{ route('terms.conditions') }}" 
                   @click="mobileMenuOpen = false" 
                   class="block px-3 py-2 rounded-lg transition"
                   :class="scrolled 
                       ? '{{ request()->routeIs('terms.conditions') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}' 
                       : '{{ request()->routeIs('terms.conditions') ? 'text-sky-300 bg-white/10' : 'text-white/90 hover:bg-white/5' }}'">
                    Syarat & Ketentuan
                </a>
            @endif
        </div>

        <div class="pt-3 border-t flex flex-col gap-2"
             :class="scrolled ? 'border-slate-100' : 'border-white/10'">
            @auth
                @if(Auth::user()->hasAnyRole(['pemilik', 'admin', 'superadmin']))
                    <a href="{{ route('owner.dashboard') }}" class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl">Dasbor Pemilik</a>
                @else
                    <a href="{{ route('tenant.dashboard') }}" class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl">Dasbor Penghuni</a>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="w-full text-center py-2.5 text-sm font-bold rounded-xl transition"
                   :class="scrolled ? 'border-2 border-blue-600 text-blue-600' : 'border border-white/30 text-white hover:bg-white/10'">
                    Masuk
                </a>
                <a href="{{ route('register') }}" 
                   class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-md">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>

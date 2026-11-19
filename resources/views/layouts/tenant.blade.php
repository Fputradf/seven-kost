@props([
    'title' => null,
    'backUrl' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900/5 sm:bg-slate-900/10">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . app_name('SevenKost') . ' Penghuni' : (app_name('SevenKost') . ' - Portal Penghuni') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-slate-800 min-h-screen flex justify-center selection:bg-blue-600 selection:text-white">

    {{-- STANDALONE MOBILE APP SHELL (MAX-W-MD) --}}
    <div x-data="{ drawerOpen: false }" class="w-full max-w-md min-h-screen bg-slate-50 flex flex-col shadow-2xl relative border-x border-slate-200/80">

        {{-- 1. STICKY TOP MOBILE APP BAR (STANDAR APP BAR 56PX DENGAN GRADASI SAPPHIRE) --}}
        <header class="sticky top-0 z-40 h-14 min-h-[56px] px-4 flex items-center justify-between shadow-md text-white border-b border-white/10" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex items-center gap-2.5 min-w-0 truncate">
                {{-- Official Logo Brand & Nama Aplikasi Terhubung ke Konfigurasi Superadmin --}}
                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 min-w-0 group focus:outline-none" aria-label="{{ app_name() }} Beranda">
                    <img 
                        src="{{ app_logo_url('dark') }}" 
                        alt="{{ app_name() }} Logo" 
                        class="h-9 w-9 object-contain shrink-0 drop-shadow-xs group-hover:scale-105 transition-transform"
                    >
                    <span class="text-base sm:text-lg font-black tracking-tight text-white !text-white leading-none font-sans truncate">
                        {{ app_name() }}
                    </span>
                </a>
            </div>

            {{-- Right Shortcuts: Tombol Menu Garis 3 (Semua Fitur) --}}
            <div class="flex items-center gap-1.5 shrink-0">
                {{-- Tombol Icon Garis 3 (Buka Semua Fitur) --}}
                <button 
                    type="button" 
                    @click="drawerOpen = true" 
                    class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition border border-white/30 active:scale-95 shadow-xs cursor-pointer" 
                    aria-label="Menu Semua Fitur"
                >
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </header>

        {{-- 2. MAIN SCROLLABLE MOBILE CONTENT --}}
        <main class="flex-1 px-4 py-4 space-y-4 pb-24 overflow-y-auto">
            {{ $slot }}
        </main>

        {{-- 3. FIXED BOTTOM MOBILE NAVIGATION BAR --}}
        @if(!($hideBottomNav ?? false))
        <nav class="fixed bottom-0 left-0 right-0 max-w-md mx-auto z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-2 py-1.5 flex items-center justify-around shadow-lg">
            @auth
                @if(auth()->user()->hasRole('penghuni'))
                    {{-- Tab 1: Beranda --}}
                    @php $isTabDashboard = request()->routeIs('tenant.dashboard'); @endphp
                    <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isTabDashboard ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="relative">
                            <svg class="w-5 h-5 {{ $isTabDashboard ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Beranda</span>
                    </a>

                    {{-- Tab 2: Cari Kos --}}
                    @php $isTabSearch = request()->routeIs('tenant.search') || request()->routeIs('search'); @endphp
                    <a href="{{ route('tenant.search') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isTabSearch ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="relative">
                            <svg class="w-5 h-5 {{ $isTabSearch ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Cari Kos</span>
                    </a>

                    {{-- Tab 3: Kos Saya --}}
                    @php $isTabBookings = request()->routeIs('tenant.bookings.*'); @endphp
                    <a href="{{ route('tenant.bookings.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isTabBookings ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="relative">
                            <svg class="w-5 h-5 {{ $isTabBookings ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Kos Saya</span>
                    </a>

                    {{-- Tab 4: Dompet --}}
                    @php $isTabWallet = request()->routeIs('tenant.wallet.*'); @endphp
                    <a href="{{ route('tenant.wallet.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isTabWallet ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="relative">
                            <svg class="w-5 h-5 {{ $isTabWallet ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Dompet</span>
                    </a>

                    {{-- Tab 5: Akun / Profil --}}
                    @php $isTabProfile = request()->routeIs('profile*'); @endphp
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isTabProfile ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="relative">
                            <div class="w-5 h-5 rounded-full {{ $isTabProfile ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }} text-[9px] font-bold flex items-center justify-center shadow-2xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Akun</span>
                    </a>
                @elseif(auth()->user()->hasRole('pemilik'))
                    {{-- Owner Tabs --}}
                    @php $isOwnerHome = request()->routeIs('owner.dashboard'); @endphp
                    <a href="{{ route('owner.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isOwnerHome ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Dasbor</span>
                    </a>
                    <a href="{{ route('owner.kosts.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->routeIs('owner.kosts*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Kos Saya</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('layanan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Layanan</span>
                    </a>
                    <a href="{{ route('owner.tenants.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->routeIs('owner.tenants*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Penyewa</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->routeIs('profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-2xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Akun</span>
                    </a>
                @else
                    {{-- Default general auth --}}
                    <a href="{{ url('/') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('/') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Beranda</span>
                    </a>
                    <a href="{{ url('/cari') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('cari*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Cari Kos</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('layanan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Layanan</span>
                    </a>
                    <a href="{{ url('/bantuan') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('bantuan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Bantuan</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('dashboard*') || request()->is('profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                        <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-2xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Akun</span>
                    </a>
                @endif
            @else
                {{-- Guest Mode: 5 Clean Public Tabs (Tidak Menampilkan Tab Penghuni Aneh) --}}
                {{-- 1. Beranda --}}
                <a href="{{ url('/') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('/') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                    <div class="relative">
                        <svg class="w-5 h-5 {{ request()->is('/') ? 'text-blue-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        @if(request()->is('/'))
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Beranda</span>
                </a>

                {{-- 2. Cari Kos --}}
                <a href="{{ url('/cari') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('cari*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                    <div class="relative">
                        <svg class="w-5 h-5 {{ request()->is('cari*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        @if(request()->is('cari*'))
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Cari Kos</span>
                </a>

                {{-- 3. Layanan --}}
                @php $isGuestServices = request()->is('layanan*') || request()->routeIs('services.*'); @endphp
                <a href="{{ route('services.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ $isGuestServices ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isGuestServices ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        @if($isGuestServices)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Layanan</span>
                </a>

                {{-- 4. Bantuan --}}
                <a href="{{ url('/bantuan') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('bantuan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                    <div class="relative">
                        <svg class="w-5 h-5 {{ request()->is('bantuan*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @if(request()->is('bantuan*'))
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Bantuan</span>
                </a>

                {{-- 5. Masuk --}}
                <a href="{{ route('login') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-900 font-medium' }}">
                    <div class="relative">
                        <svg class="w-5 h-5 {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        @if(request()->is('login*') || request()->is('register*'))
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Masuk</span>
                </a>
            @endauth
        </nav>
        @endif

        {{-- 4. OFF-CANVAS / SLIDE-OVER DRAWER MENU LENGKAP SEMUA FITUR (TOMBOL GARIS 3) --}}
        <div x-show="drawerOpen" class="relative z-50" style="display: none;" role="dialog" aria-modal="true">
            {{-- Backdrop blur overlay --}}
            <div 
                x-show="drawerOpen" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                @click="drawerOpen = false" 
                class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-y-0 right-0 flex max-w-full pl-10 pointer-events-auto">
                    <div 
                        x-show="drawerOpen" 
                        x-transition:enter="transform transition ease-in-out duration-300" 
                        x-transition:enter-start="translate-x-full" 
                        x-transition:enter-end="translate-x-0" 
                        x-transition:leave="transform transition ease-in-out duration-200" 
                        x-transition:leave-start="translate-x-0" 
                        x-transition:leave-end="translate-x-full" 
                        class="w-80 max-w-[85vw] bg-white shadow-2xl flex flex-col justify-between"
                    >
                        {{-- Top Drawer Header --}}
                        <div class="p-4 bg-gradient-to-r from-[#091e3a] to-[#1d4ed8] text-white flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img src="{{ app_logo_url('dark') }}" alt="{{ app_name() }}" class="h-7 w-7 object-contain">
                                <span class="font-extrabold text-sm text-white">{{ app_name() }} Menu</span>
                            </div>
                            <button type="button" @click="drawerOpen = false" class="w-8 h-8 rounded-lg bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition active:scale-95 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Drawer Body: User Info & All Menu Items --}}
                        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-4">
                            @auth
                                {{-- User Info Card --}}
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-xs shrink-0">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1 truncate">
                                            <strong class="text-xs font-bold text-slate-900 block truncate">{{ auth()->user()->name }}</strong>
                                            <span class="text-[11px] text-slate-500 truncate block">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>

                                    {{-- Quick Wallet Card inside drawer --}}
                                    <div class="p-2.5 rounded-xl bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex items-center justify-between shadow-2xs">
                                        <div>
                                            <span class="text-[9px] font-bold text-sky-200 uppercase tracking-wider block">Saldo Dompet</span>
                                            <strong class="text-xs font-black text-white">Rp {{ number_format(auth()->user()->wallet_balance ?? 0, 0, ',', '.') }}</strong>
                                        </div>
                                        <a href="{{ route('tenant.wallet.index') }}" class="px-2 py-1 rounded-lg bg-white/20 hover:bg-white/30 text-white text-[10px] font-extrabold border border-white/30 transition">
                                            Buka Dompet &rarr;
                                        </a>
                                    </div>
                                </div>
                            @endauth

                            {{-- Section: Menu Utama --}}
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 block mb-1">Semua Fitur Utama</span>

                                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>Beranda Penghuni</span>
                                </a>

                                <a href="{{ route('tenant.search') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Cari & Filter Kamar Kos</span>
                                </a>

                                <a href="{{ route('tenant.map') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span>Peta Interaktif Kos</span>
                                </a>

                                <a href="{{ route('tenant.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    <span>Kamar & Booking Saya</span>
                                </a>

                                <a href="{{ route('tenant.wallet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span>Dompet & Saldo (Top Up / Tarik)</span>
                                </a>
                            </div>

                            {{-- Section: Layanan & Fasilitas --}}
                            <div class="space-y-1 pt-2 border-t border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 block mb-1">Layanan & Komunitas</span>

                                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>Pesan Layanan Kamar</span>
                                </a>

                                <a href="{{ route('tenant.wishlists.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                    <span>Kos Favorit (Wishlist)</span>
                                </a>

                                <a href="{{ route('tenant.complaints.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    <span>Lapor Keluhan Fasilitas</span>
                                </a>

                                <a href="{{ route('marketplace.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Marketplace Anak Kos (COD)</span>
                                </a>

                                <a href="{{ route('help') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Pusat Bantuan & CS</span>
                                </a>

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Pengaturan Akun</span>
                                </a>
                            </div>
                        </div>

                        {{-- Drawer Footer: Logout --}}
                        <div class="p-4 border-t border-slate-100 bg-slate-50">
                            @auth
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-extrabold text-xs transition flex items-center justify-center gap-2 border border-rose-200 shadow-2xs active:scale-95 cursor-pointer">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span>Keluar dari Akun</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs transition flex items-center justify-center gap-2 shadow-xs">
                                    <span>Masuk ke Akun</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @stack('scripts')
</body>
</html>

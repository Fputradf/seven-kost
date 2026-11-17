@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . app_name('SevenKost') . ' Mitra Pemilik' : (app_name('SevenKost') . ' - Portal Pemilik Kos') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">

    <!-- Fonts: Geist, Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body 
    class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex selection:bg-blue-600 selection:text-white"
    x-data="{ sidebarOpen: false, userDropdownOpen: false }"
>
    {{-- =========================================================================
       1. SIDEBAR KIRI (IDENTIK DENGAN SUPERADMIN: DEEP MIDNIGHT NAVY GRADIENT)
       ========================================================================= --}}

    {{-- Mobile Overlay Backdrop --}}
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden"
        @click="sidebarOpen = false"
        style="display: none;"
    ></div>

    {{-- Sidebar Container --}}
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 border-r border-white/10 shadow-2xl lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        style="background: linear-gradient(180deg, #0c2b54 0%, #144685 50%, #0a2243 100%);"
    >
        {{-- 1a. Header Sidebar (Brand Logo & Name) --}}
        <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 flex-shrink-0">
            <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ app_logo_url('dark') }}" alt="{{ app_name() }} Logo" class="h-9 w-9 object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform">
                <div class="flex flex-col text-left leading-tight">
                    <span class="text-base font-extrabold text-white tracking-tight">{{ app_name() }}</span>
                    <span class="text-[10px] font-bold text-sky-300 tracking-wider uppercase mt-0.5">Portal Pemilik</span>
                </div>
            </a>

            {{-- Close Button on Mobile --}}
            <button 
                type="button" 
                @click="sidebarOpen = false" 
                class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- 1b. Menu Navigasi Sidebar --}}
        <div class="flex-1 overflow-y-auto py-5 px-3.5 space-y-6 scrollbar-thin scrollbar-thumb-white/10">
            
            {{-- Nav Group 1: IKHTISAR --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Ikhtisar Operasional
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('owner.dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.dashboard') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 2: PROPERTI & UNIT KAMAR --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Listing Properti & Kamar
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('owner.kosts.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.kosts.index', 'owner.rooms.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.kosts.index', 'owner.rooms.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>Iklan Kos Saya</span>
                    </a>

                    <a 
                        href="{{ route('owner.kosts.create') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.kosts.create') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.kosts.create') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Pasang Kos Baru</span>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 3: HUNIAN & TRANSAKSI --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Penghuni & Sewa
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('owner.tenants.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.tenants.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.tenants.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Daftar Penyewa & Booking</span>
                    </a>

                    <a 
                        href="{{ route('owner.complaints.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.complaints.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.complaints.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Daftar Keluhan</span>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 4: PEMASARAN & PROMOSI --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Pemasaran & Promosi
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('owner.promosi.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('owner.promosi.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('owner.promosi.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <span>Promosi Iklan & VIP</span>
                    </a>
                </nav>
            </div>

        </div>

        {{-- Tombol Merah Keluar di Pojok Bawah Sidebar --}}
        <div class="p-3.5 flex-shrink-0 mt-auto border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white font-bold text-xs sm:text-[13px] shadow-md shadow-rose-950/30 transition-all cursor-pointer"
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- =========================================================================
       2. AREA KONTEN UTAMA (DI KANAN SIDEBAR)
       ========================================================================= --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        {{-- Topbar Header (Putih Bersih dengan Border Halus, Mirip Superadmin) --}}
        <header class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                {{-- Hamburger Mobile Button --}}
                <button 
                    type="button" 
                    @click="sidebarOpen = true" 
                    class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Page Title / Subtitle --}}
                <div class="hidden sm:block">
                    <span class="text-[11px] font-medium text-slate-400 block tracking-tight">Portal Pemilik Properti</span>
                    <span class="text-sm font-semibold text-slate-900 tracking-tight">{{ $title ?? 'Dashboard' }}</span>
                </div>
            </div>

            {{-- Topbar Right Actions --}}
            <div class="flex items-center gap-3">
                {{-- User Dropdown --}}
                <div class="relative" @click.outside="userDropdownOpen = false">
                    <button 
                        @click="userDropdownOpen = !userDropdownOpen" 
                        type="button" 
                        class="flex items-center gap-2.5 p-1 rounded-full text-sm hover:bg-slate-100 transition focus:outline-none"
                    >
                        <div class="h-8 w-8 rounded-full bg-blue-700 text-white font-semibold text-xs ring-2 ring-blue-500/20 flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                        </div>
                        <span class="text-[13px] font-medium text-slate-800 hidden md:inline">{{ Auth::user()->name }}</span>
                        <svg class="h-3.5 w-3.5 text-slate-400 hidden sm:inline" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div 
                        x-show="userDropdownOpen" 
                        x-transition 
                        class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black/5 text-slate-800 z-50" 
                        style="display: none;"
                    >
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="text-[11px] text-slate-400 font-medium">Masuk sebagai</p>
                            <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">
                                Mitra Pemilik Kos
                            </span>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>

                        <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Website SevenKost
                        </a>

                        <div class="my-1 border-t border-slate-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content Body --}}
        <main class="flex-1 w-full pb-16">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>

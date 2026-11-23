@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . app_name('SevenKost') . ' Mitra Layanan' : (app_name('SevenKost') . ' - Portal Mitra Layanan') }}</title>

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
    @php
        $partner = auth()->user()?->servicePartner;
    @endphp

    {{-- =========================================================================
       1. SIDEBAR KIRI (DEEP MIDNIGHT NAVY GRADIENT)
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
            <a href="{{ route('partner.dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ app_logo_url('dark') }}" alt="{{ app_name() }} Logo" class="h-9 w-9 object-contain flex-shrink-0 drop-shadow-sm group-hover:scale-105 transition-transform">
                <div class="flex flex-col text-left leading-tight">
                    <span class="text-base font-extrabold text-white tracking-tight">{{ app_name() }}</span>
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
                        href="{{ route('partner.dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('partner.dashboard') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('partner.dashboard') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 2: MANAJEMEN PESANAN --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Pesanan Layanan
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('partner.orders.index') }}" 
                        class="flex items-center justify-between px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('partner.orders.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('partner.orders.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Daftar Pesanan</span>
                        </div>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 3: KEUANGAN & PENDAPATAN --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Dompet & Keuangan
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('partner.finances.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('partner.finances.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('partner.finances.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>Dompet Pendapatan</span>
                    </a>
                </nav>
            </div>

            {{-- Nav Group 4: PENGATURAN USAHA --}}
            <div>
                <span class="px-3 text-[11px] font-bold tracking-wider text-blue-200/70 uppercase block mb-2">
                    Profil & Pengaturan
                </span>
                <nav class="space-y-1">
                    <a 
                        href="{{ route('partner.profile.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] transition-all {{ request()->routeIs('partner.profile.*') ? 'bg-blue-600 text-white font-semibold shadow-md shadow-blue-500/30' : 'text-blue-100/80 font-medium hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('partner.profile.*') ? 'text-white' : 'text-blue-200/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Profil Usaha</span>
                    </a>

                    <a 
                        href="{{ route('services.index') }}" 
                        target="_blank"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] text-blue-100/80 font-medium hover:text-white hover:bg-white/10 transition-all"
                    >
                        <svg class="w-4 h-4 flex-shrink-0 text-blue-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Katalog Layanan Publik</span>
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
                    class="w-full flex items-center justify-start gap-3 px-3.5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white font-bold text-xs sm:text-[13px] shadow-md shadow-rose-950/30 transition-all cursor-pointer text-left"
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
       2. AREA KONTEN UTAMA
       ========================================================================= --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        {{-- Topbar Header --}}
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

                {{-- Page Title --}}
                <div class="hidden sm:block">
                    <h1 class="text-sm font-bold text-slate-900 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
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
                            {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
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
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                {{ $partner?->business_name ?? 'Mitra Layanan' }}
                            </span>
                        </div>

                        <a href="{{ route('partner.profile.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Usaha
                        </a>

                        <a href="{{ route('partner.finances.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Dompet Saldo
                        </a>

                        <div class="my-1 border-t border-slate-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer text-left">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="px-4 sm:px-6 lg:px-8 pt-4">
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-800 text-sm font-medium shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 sm:px-6 lg:px-8 pt-4">
                <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 flex items-center gap-3 text-rose-800 text-sm font-medium shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Page Content Slot --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>

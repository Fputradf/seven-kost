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
    <div x-data="{ drawerOpen: false, notifOpen: false }" class="w-full max-w-md min-h-screen bg-slate-50 flex flex-col shadow-2xl relative border-x border-slate-200/80">

        {{-- 1. STICKY TOP MOBILE APP BAR (HEADER BIRU GRADASI SAPPHIRE ELEGAN - TINGGI 64PX SERASI DENGAN BOTTOM NAVBAR) --}}
        <header class="sticky top-0 z-40 h-16 min-h-[64px] px-4 flex items-center justify-between shadow-md text-white border-b border-white/10" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="flex items-center gap-2.5 min-w-0 truncate">
                {{-- Official Logo Brand & Nama Aplikasi Terhubung ke Konfigurasi Superadmin --}}
                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 min-w-0 group focus:outline-none" aria-label="{{ app_name() }} Beranda">
                    <img 
                        src="{{ app_logo_url('dark') ?: app_logo_url('light') }}" 
                        alt="{{ app_name() }} Logo" 
                        class="h-9 w-9 object-contain shrink-0 drop-shadow-xs group-hover:scale-105 transition-transform"
                    >
                    <span class="text-base sm:text-lg font-black tracking-tight text-white leading-none font-sans truncate">
                        {{ app_name() }}
                    </span>
                </a>
            </div>

            {{-- Right Shortcuts: Notifikasi & Menu --}}
            <div class="flex items-center gap-1.5 shrink-0 relative">
                @auth
                    @php
                        $headerPendingCount = \App\Models\Transaction::where('user_id', auth()->id())->where('status', 'pending')->count();
                        $activeSrvNotif = \App\Models\Transaction::where('user_id', auth()->id())->where('type', 'service')->count();
                        $totalNotifCount = $headerPendingCount + $activeSrvNotif;
                    @endphp

                    {{-- Pusat Notifikasi (Menampung Tagihan & Riwayat Pembayaran serta Pesanan Layanan) --}}
                    <div class="relative">
                        <button 
                            type="button" 
                            @click="notifOpen = !notifOpen" 
                            class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition border border-white/30 active:scale-95 shadow-xs relative cursor-pointer" 
                            aria-label="Notifikasi & Tagihan"
                            title="Notifikasi"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if($totalNotifCount > 0)
                                <span class="absolute -top-1 -right-1 min-w-4 h-4 px-1 bg-rose-500 text-white rounded-full text-[9px] font-black flex items-center justify-center shadow-xs animate-pulse">
                                    {{ $totalNotifCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Popover Dropdown Notifikasi --}}
                        <div 
                            x-show="notifOpen" 
                            @click.away="notifOpen = false" 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 top-12 w-72 sm:w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 p-3 text-slate-800 space-y-2.5"
                            style="display: none;"
                        >
                            {{-- Header Popover --}}
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    <span class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Notifikasi</span>
                                </div>
                                @if($totalNotifCount > 0)
                                    <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                                        {{ $totalNotifCount }} Perlu Tindakan
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                                        Semua Beres
                                    </span>
                                @endif
                            </div>

                            {{-- Item 1: Tagihan & Riwayat Pembayaran --}}
                            <a 
                                href="{{ route('tenant.bills.index') }}" 
                                @click="notifOpen = false"
                                class="block p-2.5 rounded-xl border {{ $headerPendingCount > 0 ? 'bg-amber-50/70 border-amber-200 hover:bg-amber-50' : 'bg-slate-50/70 border-slate-200 hover:bg-slate-100' }} transition group"
                            >
                                <div class="flex items-start gap-2.5">
                                    <div class="w-8 h-8 rounded-lg {{ $headerPendingCount > 0 ? 'bg-amber-500 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center shrink-0 shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition truncate">Tagihan &amp; Riwayat</span>
                                            @if($headerPendingCount > 0)
                                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-rose-500 text-white shrink-0">
                                                    {{ $headerPendingCount }} Pending
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                            @if($headerPendingCount > 0)
                                                Ada {{ $headerPendingCount }} tagihan menunggu pelunasan.
                                            @else
                                                Semua tagihan lunas. Cek arsip riwayat.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>

                            {{-- Item 2: Pesanan & Aktivitas Layanan --}}
                            <a 
                                href="{{ route('tenant.services.index', ['tab' => 'orders']) }}" 
                                @click="notifOpen = false"
                                class="block p-2.5 rounded-xl border {{ $activeSrvNotif > 0 ? 'bg-blue-50/70 border-blue-200 hover:bg-blue-50' : 'bg-slate-50/70 border-slate-200 hover:bg-slate-100' }} transition group"
                            >
                                <div class="flex items-start gap-2.5">
                                    <div class="w-8 h-8 rounded-lg {{ $activeSrvNotif > 0 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center shrink-0 shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition truncate">Pesanan Layanan</span>
                                            @if($activeSrvNotif > 0)
                                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-blue-600 text-white shrink-0">
                                                    {{ $activeSrvNotif }} Aktif
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                            @if($activeSrvNotif > 0)
                                                {{ $activeSrvNotif }} pesanan jasa/kamar diproses.
                                            @else
                                                Belum ada pesanan aktif. Pesan sekarang.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>

                            {{-- Footer Popover --}}
                            <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                <a href="{{ route('tenant.bills.index') }}" @click="notifOpen = false" class="text-blue-600 hover:text-blue-700 font-bold hover:underline">
                                    Riwayat Pembayaran &rarr;
                                </a>
                                <button type="button" @click="notifOpen = false" class="text-slate-400 hover:text-slate-600 font-medium cursor-pointer">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                @endauth

                <button 
                    type="button" 
                    @click="drawerOpen = true" 
                    class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition border border-white/30 active:scale-95 shadow-xs cursor-pointer"
                    aria-label="Buka Menu"
                    title="Menu Lengkap"
                >
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </header>

        {{-- 2. MAIN SCROLLABLE MOBILE CONTENT --}}
        <main class="flex-1 px-4 py-4 space-y-4 pb-28 overflow-y-auto">
            {{ $slot }}
        </main>

        {{-- 3. FIXED BOTTOM MOBILE NAVIGATION BAR (STATUS AKTIF TEGAS DENGAN INDIKATOR PILL & SAFE AREA PADDING) --}}
        @if(!($hideBottomNav ?? false))
        <nav class="fixed bottom-0 left-0 right-0 max-w-md mx-auto z-40 min-h-[64px] bg-white/95 backdrop-blur-md border-t border-x border-slate-200/90 px-2 pt-1.5 flex items-center justify-around shadow-lg" style="padding-bottom: max(0.875rem, env(safe-area-inset-bottom, 14px));">
            @auth
                @if(auth()->user()->hasRole('penghuni'))
                    {{-- Tab 1: Beranda --}}
                    @php $isTabDashboard = request()->routeIs('tenant.dashboard'); @endphp
                    <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center relative {{ $isTabDashboard ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                        @if($isTabDashboard)
                            <span class="absolute -top-1.5 w-6 h-1 rounded-full bg-blue-600"></span>
                        @endif
                        <div class="relative">
                            @if($isTabDashboard)
                                <svg class="w-5 h-5 text-blue-600 fill-current" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Beranda</span>
                    </a>

                    {{-- Tab 2: Cari Kos --}}
                    @php $isTabSearch = request()->routeIs('tenant.search') || request()->routeIs('search'); @endphp
                    <a href="{{ route('tenant.search') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center relative {{ $isTabSearch ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                        @if($isTabSearch)
                            <span class="absolute -top-1.5 w-6 h-1 rounded-full bg-blue-600"></span>
                        @endif
                        <div class="relative">
                            @if($isTabSearch)
                                <svg class="w-5 h-5 text-blue-600 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Cari Kos</span>
                    </a>

                    {{-- Tab 3: Kos Saya --}}
                    @php $isTabBookings = request()->routeIs('tenant.bookings.*'); @endphp
                    <a href="{{ route('tenant.bookings.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center relative {{ $isTabBookings ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                        @if($isTabBookings)
                            <span class="absolute -top-1.5 w-6 h-1 rounded-full bg-blue-600"></span>
                        @endif
                        <div class="relative">
                            @if($isTabBookings)
                                <svg class="w-5 h-5 text-blue-600 fill-current" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Kos Saya</span>
                    </a>

                    {{-- Tab 4: Dompet --}}
                    @php $isTabWallet = request()->routeIs('tenant.wallet.*'); @endphp
                    <a href="{{ route('tenant.wallet.index') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center relative {{ $isTabWallet ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                        @if($isTabWallet)
                            <span class="absolute -top-1.5 w-6 h-1 rounded-full bg-blue-600"></span>
                        @endif
                        <div class="relative">
                            @if($isTabWallet)
                                <svg class="w-5 h-5 text-blue-600 fill-current" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] sm:text-xs mt-0.5 leading-none">Dompet</span>
                    </a>

                    {{-- Tab 5: Akun / Profil (Ikon User Konsisten & Aksesibel) --}}
                    @php $isTabProfile = request()->routeIs('profile*'); @endphp
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition text-center relative {{ $isTabProfile ? 'text-blue-600 font-extrabold' : 'text-slate-600 hover:text-slate-900 font-semibold' }}">
                        @if($isTabProfile)
                            <span class="absolute -top-1.5 w-6 h-1 rounded-full bg-blue-600"></span>
                        @endif
                        <div class="relative">
                            @if($isTabProfile)
                                <svg class="w-5 h-5 text-blue-600 fill-current" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @endif
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
                <div class="absolute inset-y-0 left-0 flex max-w-full pr-10 pointer-events-auto">
                    <div 
                        x-show="drawerOpen" 
                        x-transition:enter="transform transition ease-in-out duration-300" 
                        x-transition:enter-start="-translate-x-full" 
                        x-transition:enter-end="translate-x-0" 
                        x-transition:leave="transform transition ease-in-out duration-200" 
                        x-transition:leave-start="translate-x-0" 
                        x-transition:leave-end="-translate-x-full" 
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
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/90">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm shadow-2xs shrink-0">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1 truncate">
                                            <strong class="text-xs font-bold text-slate-900 block truncate">{{ auth()->user()->name }}</strong>
                                            <span class="text-[11px] text-slate-500 truncate block">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endauth

                            {{-- Section: Menu Utama --}}
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 block mb-1">Semua Fitur Utama</span>

                                <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span>Beranda Penghuni</span>
                                </a>

                                <a href="{{ route('tenant.search') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Cari &amp; Filter Kamar Kos</span>
                                </a>

                                <a href="{{ route('tenant.map') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span>Peta Interaktif Kos</span>
                                </a>

                                <a href="{{ route('tenant.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    <span>Kamar &amp; Booking Saya</span>
                                </a>

                                <a href="{{ route('tenant.wallet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span>Dompet &amp; Saldo (Top Up / Tarik)</span>
                                </a>

                                <a href="{{ route('tenant.bills.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                        <span>Tagihan &amp; Pembayaran</span>
                                    </div>
                                    @php
                                        $drawerPendingCount = auth()->check() ? \App\Models\Transaction::where('user_id', auth()->id())->where('status', 'pending')->count() : 0;
                                    @endphp
                                    @if($drawerPendingCount > 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white shadow-xs">
                                            {{ $drawerPendingCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>

                            {{-- Section: Layanan & Fasilitas --}}
                            <div class="space-y-1 pt-2 border-t border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 block mb-1">Layanan & Komunitas</span>

                                <a href="{{ route('tenant.services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <span>Pesan Layanan Kamar</span>
                                </a>

                                <a href="{{ route('tenant.services.index', ['tab' => 'orders']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Riwayat &amp; Status Layanan</span>
                                </a>

                                <a href="{{ route('tenant.wishlists.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    <span>Kos Favorit (Wishlist)</span>
                                </a>

                                <a href="{{ route('tenant.complaints.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    <span>Lapor Keluhan Fasilitas</span>
                                </a>

                                <a href="{{ route('tenant.marketplace.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Marketplace Anak Kos (COD)</span>
                                </a>

                                <a href="{{ route('tenant.help') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Pusat Bantuan &amp; CS</span>
                                </a>

                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Pengaturan Akun</span>
                                </a>
                            </div>
                        </div>

                        {{-- Drawer Footer: Logout --}}
                        <div class="p-4 border-t border-slate-100 bg-slate-50">
                            @auth
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-extrabold text-xs transition flex items-center justify-center gap-2 border border-slate-200 hover:border-rose-200 shadow-2xs active:scale-95 cursor-pointer">
                                        <svg class="w-4 h-4 text-slate-500 group-hover:text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
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

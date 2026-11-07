@props(['transparent' => false])

<nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 text-slate-800 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            {{-- Left: Logo & Search Bar --}}
            <div class="flex items-center gap-4 lg:gap-6 flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group focus:outline-none">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost Logo" class="h-10 w-10 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
                </a>

                {{-- Quick Search Pill in Navbar --}}
                <form action="{{ url('/cari') }}" method="GET" class="hidden sm:flex items-center">
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Cari kos di sini..." 
                            class="pl-10 pr-4 py-2 text-xs lg:text-sm bg-slate-100/90 hover:bg-slate-100 focus:bg-white text-slate-700 placeholder-slate-400 rounded-full border border-slate-200/70 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 w-44 md:w-56 lg:w-64 transition-all"
                        >
                    </div>
                </form>
            </div>

            {{-- Center: Desktop Nav Links (Icons + Labels) --}}
            <div class="hidden md:flex items-center gap-6 lg:gap-8 text-sm font-medium text-slate-600">
                {{-- Beranda --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2 transition-colors {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <svg class="w-4 h-4 {{ request()->is('/') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Beranda</span>
                </a>

                {{-- Layanan --}}
                <a href="{{ url('/#layanan') }}" class="flex items-center gap-2 transition-colors hover:text-blue-600">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Layanan</span>
                </a>

                {{-- Pasang Iklan --}}
                <a href="{{ url('/paket-iklan') }}" class="flex items-center gap-2 transition-colors {{ request()->is('paket-iklan*') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Pasang Iklan</span>
                </a>

                {{-- Marketplace --}}
                <a href="{{ url('/#marketplace') }}" class="flex items-center gap-2 transition-colors hover:text-blue-600">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Marketplace</span>
                </a>
            </div>

            {{-- Right: Auth Buttons (Masuk & Daftar) or User Dropdown --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    {{-- User Profile Menu --}}
                    <div class="relative" @click.outside="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" type="button" class="flex items-center gap-2.5 rounded-full p-1 text-sm transition hover:bg-slate-100 focus:outline-none">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-blue-500/20 shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-semibold text-slate-700 hidden lg:inline">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="userDropdownOpen" x-transition class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black/5 text-slate-800" style="display: none;">
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 capitalize">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'Member' }}
                                </span>
                            </div>

                            @if(Auth::user()->hasAnyRole(['superadmin', 'admin']))
                                <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Panel Admin
                                </a>
                            @endif

                            <a href="{{ route('owner.kosts.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Kelola Iklan Kos
                            </a>

                            <a href="{{ route('tenant.bookings.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Booking Saya
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>

                            <div class="my-1 border-t border-slate-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Tombol Masuk (Outlined) --}}
                    <a href="{{ route('login') }}" class="px-5 py-2 text-xs lg:text-sm font-semibold text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition shadow-2xs">
                        Masuk
                    </a>

                    {{-- Tombol Daftar (Solid Blue) --}}
                    <a href="{{ route('register') }}" class="px-5 py-2 text-xs lg:text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex md:hidden items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-6 space-y-3" style="display: none;">
        {{-- Mobile Search --}}
        <form action="{{ url('/cari') }}" method="GET" class="pt-1">
            <div class="relative flex items-center">
                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Cari kos di sini..." 
                    class="w-full pl-10 pr-4 py-2 text-xs bg-slate-100 rounded-full border border-slate-200"
                >
            </div>
        </form>

        <div class="space-y-1 text-sm font-medium pt-2">
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-blue-600 font-semibold bg-blue-50">Beranda</a>
            <a href="{{ url('/#layanan') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Layanan</a>
            <a href="{{ url('/paket-iklan') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Pasang Iklan</a>
            <a href="{{ url('/#marketplace') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Marketplace</a>
        </div>

        <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full text-center py-2 text-xs font-semibold text-blue-600 border border-blue-600 rounded-lg">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-2 text-xs font-semibold text-blue-600 border border-blue-600 rounded-lg">Masuk</a>
                <a href="{{ route('register') }}" class="w-full text-center py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

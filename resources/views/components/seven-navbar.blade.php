@props(['transparent' => false])

<nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 text-slate-800 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 sm:h-20 gap-4">
            {{-- Left: Logo Brand --}}
            <div class="flex items-center flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group focus:outline-none">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost Logo" class="h-10 w-10 sm:h-11 sm:w-11 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
                    <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors">
                        Seven<span class="text-blue-600">Kost</span>
                    </span>
                </a>
            </div>

            {{-- Center: Compact Desktop Nav Links (5 Fitur: Beranda, Pasang Iklan, Layanan, Marketplace, FAQ) --}}
            <div class="hidden md:flex items-center gap-4 lg:gap-7 text-xs sm:text-sm lg:text-[15px] font-bold text-slate-800">
                {{-- 1. Beranda --}}
                <a href="{{ url('/') }}" class="flex items-center gap-1.5 transition-colors pb-1 {{ request()->is('/') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">
                    <svg class="w-4 h-4 {{ request()->is('/') ? 'text-blue-600' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span>Beranda</span>
                </a>

                {{-- 2. Pasang Iklan --}}
                <a href="{{ url('/paket-iklan') }}" class="flex items-center gap-1.5 transition-colors pb-1 {{ request()->is('paket-iklan*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }}">
                    <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.39l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Pasang Iklan</span>
                </a>

                {{-- 3. Layanan --}}
                <a href="{{ route('services.index') }}" class="flex items-center gap-1.5 transition-colors pb-1 {{ request()->is('layanan*') ? 'text-blue-600 border-b-2 border-blue-600 font-bold' : 'hover:text-blue-600' }}">
                    <svg class="w-4 h-4 {{ request()->is('layanan*') ? 'text-blue-600' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Layanan</span>
                </a>

                {{-- 4. Marketplace --}}
                <a href="{{ route('marketplace.index') }}" class="flex items-center gap-1.5 transition-colors pb-1 {{ request()->is('marketplace*') ? 'text-blue-600 border-b-2 border-blue-600 font-bold' : 'hover:text-blue-600' }} text-slate-800">
                    <svg class="w-4 h-4 {{ request()->is('marketplace*') ? 'text-blue-600' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    <span>Marketplace</span>
                </a>

                {{-- 5. FAQ --}}
                <a href="{{ url('/bantuan') }}" class="flex items-center gap-1.5 transition-colors pb-1 {{ request()->is('bantuan*') ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-600' }} text-slate-800">
                    <svg class="w-4 h-4 {{ request()->is('bantuan*') ? 'text-blue-600' : 'text-slate-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>FAQ</span>
                </a>
            </div>

            {{-- Right: Auth Buttons (Masuk & Daftar) or User Dropdown --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    {{-- User Profile Menu --}}
                    <div class="relative" @click.outside="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" type="button" class="flex items-center gap-2.5 rounded-full p-1.5 text-sm transition hover:bg-slate-100 focus:outline-none">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm ring-2 ring-blue-500/20 shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-bold text-slate-800 hidden lg:inline">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="userDropdownOpen" x-transition class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-xl ring-1 ring-black/5 text-slate-800" style="display: none;">
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                <div class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 capitalize">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'Member' }}
                                </div>
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
                    {{-- Tombol Masuk (Outlined Blue, Prominent Font) --}}
                    <a href="{{ route('login') }}" class="px-6 py-2.5 text-sm lg:text-base font-bold text-blue-600 border-2 border-blue-600 rounded-xl hover:bg-blue-50 transition shadow-2xs">
                        Masuk
                    </a>

                    {{-- Tombol Daftar (Solid Blue, Prominent Font) --}}
                    <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm lg:text-base font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex md:hidden items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" style="display: none;"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-6 space-y-3" style="display: none;">
        <div class="space-y-1 text-base font-bold pt-1">
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg {{ request()->is('/') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}">Beranda</a>
            <a href="{{ url('/paket-iklan') }}" class="block px-3 py-2 rounded-lg {{ request()->is('paket-iklan*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}">Pasang Iklan</a>
            <a href="{{ route('services.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg {{ request()->is('layanan*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}">Layanan</a>
            <a href="{{ route('marketplace.index') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg {{ request()->is('marketplace*') ? 'text-blue-600 font-bold bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}">Marketplace</a>
            <a href="{{ url('/bantuan') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg {{ request()->is('bantuan*') ? 'text-blue-600 bg-blue-50' : 'text-slate-800 hover:bg-slate-50' }}">FAQ</a>
        </div>

        <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full text-center py-2.5 text-sm font-bold text-blue-600 border-2 border-blue-600 rounded-xl">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 text-sm font-bold text-blue-600 border-2 border-blue-600 rounded-xl">Masuk</a>
                <a href="{{ route('register') }}" class="w-full text-center py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

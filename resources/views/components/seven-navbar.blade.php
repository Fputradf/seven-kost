@props(['transparent' => false])

<nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="sticky top-0 z-50 w-full transition-all duration-200 {{ $transparent ? 'bg-navy-950/80 backdrop-blur-md border-b border-white/10 text-white' : 'bg-white/95 backdrop-blur-md border-b border-slate-200/80 text-slate-800 shadow-xs' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18">
            {{-- Brand Logo --}}
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center">
                    <x-seven-logo :light="$transparent" size="md" />
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                    <a href="{{ url('/cari') }}" class="transition-colors hover:text-blue-500 {{ request()->is('cari*') ? 'text-blue-600 font-semibold' : '' }}">
                        Cari Kos
                    </a>
                    <a href="{{ url('/peta') }}" class="transition-colors hover:text-blue-500 {{ request()->is('peta*') ? 'text-blue-600 font-semibold' : '' }}">
                        Peta Wilayah
                    </a>
                    <a href="{{ url('/paket-iklan') }}" class="transition-colors hover:text-blue-500 {{ request()->is('paket-iklan*') ? 'text-blue-600 font-semibold' : '' }}">
                        Paket Unggulan
                    </a>
                </div>
            </div>

            {{-- Right CTA & Auth Area --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    {{-- User Logged In --}}
                    <div class="relative" @click.outside="userDropdownOpen = false">
                        <button @click="userDropdownOpen = !userDropdownOpen" type="button" class="flex items-center gap-2.5 rounded-full p-1.5 pr-3 text-sm transition hover:bg-slate-100/10 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs ring-2 ring-blue-500/30">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <div class="text-xs font-semibold leading-tight {{ $transparent ? 'text-white' : 'text-slate-900' }}">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-[10px] text-slate-400 capitalize">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'Member' }}
                                </div>
                            </div>
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="userDropdownOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-2xl ring-1 ring-black/5 text-slate-800" style="display: none;">
                            <div class="px-3 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 capitalize">
                                    {{ Auth::user()->getRoleNames()->first() ?? 'Penghuni' }}
                                </span>
                            </div>

                            @if(Auth::user()->hasAnyRole(['superadmin', 'admin']))
                                <a href="{{ url('/admin') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Panel Admin Filament
                                </a>
                            @endif

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Pengaturan Profil
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
                    {{-- Guest Links --}}
                    <x-seven-button :href="route('login')" :variant="$transparent ? 'outline-white' : 'ghost'" size="sm">
                        Masuk
                    </x-seven-button>

                    <x-seven-button :href="route('register')" variant="primary" size="sm">
                        Daftar Akun
                    </x-seven-button>
                @endauth

                {{-- Pasang Iklan CTA --}}
                <x-seven-button :href="auth()->check() ? url('/iklan/buat') : route('login')" variant="navy" size="sm">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Pasang Iklan Kos
                </x-seven-button>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div class="flex items-center md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-3 text-slate-800" style="display: none;">
        <a href="{{ url('/cari') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-50">Cari Kos</a>
        <a href="{{ url('/peta') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-50">Peta Wilayah</a>
        <a href="{{ url('/paket-iklan') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-50">Paket Unggulan</a>

        <div class="pt-3 border-t border-slate-100 space-y-2">
            @auth
                <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                    Halo, {{ Auth::user()->name }} ({{ Auth::user()->getRoleNames()->first() ?? 'Member' }})
                </div>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-50">Dashboard</a>
                @if(Auth::user()->hasAnyRole(['superadmin', 'admin']))
                    <a href="{{ url('/admin') }}" class="block px-3 py-2 rounded-xl text-sm font-medium text-blue-600 hover:bg-blue-50">Panel Admin Filament</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50">Keluar</button>
                </form>
            @else
                <x-seven-button :href="route('login')" variant="outline" class="w-full" size="sm">Masuk</x-seven-button>
                <x-seven-button :href="route('register')" variant="primary" class="w-full" size="sm">Daftar Akun</x-seven-button>
            @endauth
        </div>
    </div>
</nav>


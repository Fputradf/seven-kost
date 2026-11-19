{{-- Mobile Bottom Navigation Bar (Khusus Layar HP / md:hidden) --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.06)]">
    <div class="max-w-md mx-auto grid grid-cols-5 h-16 items-center px-1">
        @auth
            @if(Auth::user()->hasRole('penghuni'))
                {{-- 1. Beranda / Portal Penghuni --}}
                @php $isTenantHome = request()->routeIs('tenant.dashboard'); @endphp
                <a 
                    href="{{ route('tenant.dashboard') }}" 
                    class="flex flex-col items-center justify-center py-1 transition-colors {{ $isTenantHome ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isTenantHome ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        @if($isTenantHome)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Beranda</span>
                </a>

                {{-- 2. Kos Saya / Kamar --}}
                @php $isTenantBookings = request()->routeIs('tenant.bookings*'); @endphp
                <a 
                    href="{{ route('tenant.bookings.index') }}" 
                    class="flex flex-col items-center justify-center py-1 transition-colors {{ $isTenantBookings ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isTenantBookings ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        @if($isTenantBookings)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Kos Saya</span>
                </a>

                {{-- 3. Layanan Kebutuhan Kos --}}
                @php $isServices = request()->is('layanan*') || request()->routeIs('services.*'); @endphp
                <a 
                    href="{{ route('services.index') }}" 
                    class="flex flex-col items-center justify-center py-1 transition-colors {{ $isServices ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isServices ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        @if($isServices)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Layanan</span>
                </a>

                {{-- 4. Favorit --}}
                @php $isTenantWishlists = request()->routeIs('tenant.wishlists*'); @endphp
                <a 
                    href="{{ route('tenant.wishlists.index') }}" 
                    class="flex flex-col items-center justify-center py-1 transition-colors {{ $isTenantWishlists ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isTenantWishlists ? 'text-rose-500 fill-rose-500' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        @if($isTenantWishlists)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Favorit</span>
                </a>

                {{-- 5. Lapor Kendala --}}
                @php $isTenantComplaints = request()->routeIs('tenant.complaints*'); @endphp
                <a 
                    href="{{ route('tenant.complaints.index') }}" 
                    class="flex flex-col items-center justify-center py-1 transition-colors {{ $isTenantComplaints ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 {{ $isTenantComplaints ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        @if($isTenantComplaints)
                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Lapor</span>
                </a>

            @elseif(Auth::user()->hasRole('pemilik'))
                {{-- Mode Pemilik Kos (Owner) --}}
                @php $isOwnerHome = request()->routeIs('owner.dashboard'); @endphp
                <a href="{{ route('owner.dashboard') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ $isOwnerHome ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Dasbor</span>
                </a>

                @php $isOwnerKosts = request()->routeIs('owner.kosts*'); @endphp
                <a href="{{ route('owner.kosts.index') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ $isOwnerKosts ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Kos Saya</span>
                </a>

                @php $isOwnerRooms = request()->routeIs('owner.rooms*'); @endphp
                <a href="{{ route('owner.rooms.index') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ $isOwnerRooms ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Kamar</span>
                </a>

                @php $isOwnerTenants = request()->routeIs('owner.tenants*'); @endphp
                <a href="{{ route('owner.tenants.index') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ $isOwnerTenants ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Penyewa</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-2xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-[10px] mt-1 leading-none">Akun</span>
                </a>

            @else
                {{-- Mode User Umum / Admin / Superadmin --}}
                <a href="{{ url('/') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('/') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Beranda</span>
                </a>
                <a href="{{ url('/cari') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('cari*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Cari Kos</span>
                </a>
                <a href="{{ route('services.index') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('layanan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Layanan</span>
                </a>
                <a href="{{ url('/bantuan') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('bantuan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[10px] mt-1 leading-none">Bantuan</span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('dashboard*') || request()->is('profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-2xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-[10px] mt-1 leading-none truncate max-w-[56px]">{{ Str::limit(Auth::user()->name, 7) }}</span>
                </a>
            @endif
        @else
            {{-- Mode Guest (Belum Login) - Tampilan Bersih & Relevan --}}
            {{-- 1. Beranda --}}
            <a 
                href="{{ url('/') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('/') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('/') ? 'text-blue-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    @if(request()->is('/'))
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Beranda</span>
            </a>

            {{-- 2. Cari Kos --}}
            <a 
                href="{{ url('/cari') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('cari*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('cari*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request()->is('cari*'))
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Cari Kos</span>
            </a>

            {{-- 3. Layanan --}}
            <a 
                href="{{ route('services.index') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('layanan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('layanan*') ? 'text-blue-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    @if(request()->is('layanan*'))
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Layanan</span>
            </a>

            {{-- 4. Bantuan --}}
            <a 
                href="{{ url('/bantuan') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('bantuan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('bantuan*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @if(request()->is('bantuan*'))
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Bantuan</span>
            </a>

            {{-- 5. Masuk --}}
            <a 
                href="{{ route('login') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    @if(request()->is('login*') || request()->is('register*'))
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></div>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Masuk</span>
            </a>
        @endauth
    </div>
</nav>

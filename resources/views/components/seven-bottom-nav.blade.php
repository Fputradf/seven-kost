{{-- Mobile Bottom Navigation Bar (Khusus Layar HP / md:hidden) --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-[0_-4px_25px_rgba(0,0,0,0.06)]">
    <div class="max-w-md mx-auto grid grid-cols-5 h-16 items-center px-1">
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
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Beranda</span>
        </a>

        {{-- 2. Layanan --}}
        <a 
            href="{{ route('services.index') }}" 
            class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('layanan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
        >
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('layanan*') ? 'text-blue-600' : 'text-slate-500' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                @if(request()->is('layanan*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Layanan</span>
        </a>

        {{-- 3. Cari Kos (Seragam & Rata dengan Menu Lainnya) --}}
        <a 
            href="{{ url('/cari') }}" 
            class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('cari*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
        >
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('cari*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                @if(request()->is('cari*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Cari Kos</span>
        </a>

        {{-- 4. Bantuan (FAQ & Bantuan) --}}
        <a 
            href="{{ url('/bantuan') }}" 
            class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('bantuan*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
        >
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('bantuan*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @if(request()->is('bantuan*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] mt-1 leading-none">Bantuan</span>
        </a>

        {{-- 5. Masuk (Guest) / Akun (Auth) --}}
        @auth
            <a 
                href="{{ route('dashboard') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('dashboard*') || request()->is('profile*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shadow-2xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @if(request()->is('dashboard*') || request()->is('profile*'))
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none truncate max-w-[56px]">{{ Str::limit(Auth::user()->name, 7) }}</span>
            </a>
        @else
            <a 
                href="{{ route('login') }}" 
                class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}"
            >
                <div class="relative">
                    <svg class="w-5 h-5 {{ request()->is('login*') || request()->is('register*') ? 'text-blue-600' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    @if(request()->is('login*') || request()->is('register*'))
                        <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] mt-1 leading-none">Masuk</span>
            </a>
        @endauth
    </div>
</nav>

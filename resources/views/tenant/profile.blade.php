<x-tenant-layout title="Profil Saya">
    <div class="px-4 py-5 space-y-5">

        {{-- FLASH STATUS MESSAGES --}}
        @if (session('status') === 'profile-updated')
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs animate-fade-in">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Profil Anda berhasil diperbarui!</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-xs">
                <div class="flex items-center gap-2 font-bold text-rose-900">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Terjadi kesalahan pada data yang dimasukkan:</span>
                </div>
                <ul class="list-disc list-inside pl-1 text-[11px] text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 1. HERO PROFILE CARD --}}
        <div class="relative overflow-hidden rounded-3xl p-5 text-white shadow-xl" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex items-center gap-4">
                {{-- Avatar --}}
                <div class="relative shrink-0">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-white/40 shadow-md">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-white/20 ring-2 ring-white/40 text-white flex items-center justify-center font-black text-2xl shadow-md">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center" title="Online / Aktif">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>

                {{-- User Info --}}
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h2 class="text-base font-black truncate text-white leading-tight">{{ $user->name }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-blue-500/30 border border-blue-300/40 text-blue-100 uppercase tracking-wider shrink-0">
                            Penghuni
                        </span>
                    </div>
                    <p class="text-xs text-sky-100/90 truncate">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="text-[11px] text-sky-200/80 font-medium mt-0.5 truncate flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $user->phone }}</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2. STATUS HUNIAN AKTIF KOS SAYA --}}
        <div class="p-4 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Status Hunian Saat Ini</span>
                </h3>
                <a href="{{ route('tenant.bookings.index') }}" class="text-[11px] font-extrabold text-blue-600 hover:text-blue-700">
                    Riwayat &rarr;
                </a>
            </div>

            @if($activeBooking)
                <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $activeBooking->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }} uppercase tracking-wider">
                            {{ $activeBooking->status === 'active' ? 'Hunian Aktif' : 'Disetujui (Menunggu Bayar)' }}
                        </span>
                        <h4 class="text-xs font-extrabold text-slate-900 mt-1 truncate">{{ $activeBooking->kost->name ?? 'Kos Anda' }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Kamar: {{ $activeBooking->room->room_number ?? '-' }} &bull; S/d {{ $activeBooking->end_date ? \Carbon\Carbon::parse($activeBooking->end_date)->format('d M Y') : '-' }}</p>
                    </div>
                    <a href="{{ route('tenant.bookings.show', $activeBooking->id) }}" class="px-3 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-extrabold shadow-2xs shrink-0 transition active:scale-95">
                        Detail
                    </a>
                </div>
            @else
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center space-y-2">
                    <p class="text-xs text-slate-500 font-medium">Anda belum memiliki hunian kos aktif saat ini.</p>
                    <a href="{{ route('tenant.search') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-2xs transition active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Kos Sekarang</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- 3. EDIT PROFILE INFORMATION FORM --}}
        <div class="p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4" x-data="{ avatarPreview: '{{ $user->avatar_url ?? '' }}' }">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Informasi Data Diri</span>
                </h3>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('patch')

                {{-- Upload Avatar Photo --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <div class="relative shrink-0">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-blue-500/30 shadow-xs">
                            </template>
                            <template x-if="!avatarPreview">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 ring-2 ring-slate-200 text-slate-500 flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="avatar" 
                                id="avatar" 
                                accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file) }"
                                class="block w-full text-[11px] text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            >
                        </div>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="Masukkan nama lengkap Anda"
                    >
                </div>

                {{-- Alamat Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                        placeholder="nama@email.com"
                    >
                </div>

                {{-- Nomor WhatsApp / HP --}}
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs text-slate-400 font-bold pointer-events-none">
                            +62
                        </span>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            class="w-full pl-11 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-medium focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                            placeholder="81234567890"
                        >
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-600/20 transition active:scale-98 cursor-pointer">
                        Simpan Perubahan Data Diri
                    </button>
                </div>
            </form>
        </div>

        {{-- 4. PINTASAN & PENGATURAN LAINNYA --}}
        <div class="p-4 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-2">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 block mb-1">Pintasan Fitur Lain</span>

            <a href="{{ route('tenant.wallet.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <strong class="text-xs font-bold text-slate-900 block">Dompet & Mutasi Saldo</strong>
                        <span class="text-[10px] text-slate-500">Top Up, pembayaran kos & tarik saldo</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('tenant.complaints.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div>
                        <strong class="text-xs font-bold text-slate-900 block">Lapor Keluhan Fasilitas</strong>
                        <span class="text-[10px] text-slate-500">Kirim laporan perbaikan kamar ke pemilik</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('help') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <strong class="text-xs font-bold text-slate-900 block">Pusat Bantuan & CS</strong>
                        <span class="text-[10px] text-slate-500">FAQ dan kontak customer service</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- 5. TOMBOL LOGOUT RESMI --}}
        <div class="pt-2 pb-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-extrabold text-xs transition border border-slate-200 hover:border-rose-200 flex items-center justify-center gap-2 shadow-2xs active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar dari Akun (Logout)</span>
                </button>
            </form>
        </div>

    </div>
</x-tenant-layout>

<x-owner-layout title="Pengaturan Profil">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- FLASH STATUS MESSAGES --}}
        @if (session('status') === 'profile-updated')
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Profil akun pemilik berhasil diperbarui!</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Kata sandi berhasil diperbarui!</span>
            </div>
        @endif

        {{-- HEADER INFO --}}
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center gap-5">
            <div class="relative shrink-0">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover ring-2 ring-blue-500/30 shadow-md">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-900 to-indigo-800 text-white flex items-center justify-center font-black text-3xl shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="text-center sm:text-left flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-1">
                    <h2 class="text-lg font-black text-slate-900">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 uppercase tracking-wider">
                        Mitra Pemilik Kos
                    </span>
                </div>
                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-xs text-slate-600 font-semibold mt-0.5">WhatsApp: {{ $user->phone }}</p>
                @endif
            </div>
        </div>

        {{-- FORM DATA DIRI --}}
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900">Informasi Pribadi & Kontak</h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs" placeholder="0812xxxxxxxx">
                </div>

                <div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- FORM GANTI PASSWORD --}}
        <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900">Perbarui Kata Sandi</h3>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="w-full sm:w-1/2 px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>
                </div>

                <div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs shadow-xs transition active:scale-95 cursor-pointer">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-owner-layout>

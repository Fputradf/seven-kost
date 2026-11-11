<x-guest-layout title="Lupa Password - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto">

        {{-- Kembali ke Login --}}
        <div class="mb-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>

        {{-- Card Putih Bersih --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200/80 p-8 sm:p-10">
            {{-- Brand Logo --}}
            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost Logo" class="h-10 w-10 object-contain">
                    <span class="text-xl font-black text-slate-900">Seven<span class="text-blue-600">Kost</span></span>
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Lupa Kata Sandi?
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                    Masukkan alamat email terdaftar Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                </p>
            </div>

            {{-- Session Status Feedback --}}
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email Terdaftar
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-md shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer text-center">
                        Kirim Link Reset Password
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-200 text-center text-xs text-slate-600">
                Ingat kembali password Anda?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline ml-1">
                    Masuk Sekarang
                </a>
            </div>
        </div>

    </div>
</x-guest-layout>

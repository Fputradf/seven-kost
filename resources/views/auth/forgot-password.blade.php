<x-guest-layout title="Lupa Password - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto">

        {{-- Navigasi Cepat Kembali ke Login --}}
        <div class="mb-4 flex items-center justify-between px-2">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-blue-200 hover:text-white transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>

        {{-- Card Container --}}
        <div class="relative rounded-3xl bg-[#0f2d59]/80 backdrop-blur-2xl border border-white/20 shadow-2xl shadow-[#08182d]/80 overflow-hidden p-6 sm:p-8">
            {{-- Glowing Top Line --}}
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-400"></div>

            {{-- Brand Logo --}}
            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center">
                    <x-seven-logo :light="true" size="md" />
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    Lupa Password? 🔐
                </h2>
                <p class="text-xs sm:text-sm text-blue-200/80 mt-2 leading-relaxed">
                    Jangan khawatir! Masukkan alamat email yang terdaftar dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                </p>
            </div>

            {{-- Session Status Feedback --}}
            @if (session('status'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs sm:text-sm flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                        Alamat Email Terdaftar
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               placeholder="nama@email.com"
                               class="w-full h-12 pl-10 pr-4 bg-white/10 border @error('email') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full h-12 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm sm:text-base font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                        <span>Kirim Link Reset Password</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-white/10 text-center text-xs text-blue-200">
                Ingat kembali password Anda?
                <a href="{{ route('login') }}" class="font-extrabold text-cyan-300 hover:text-white underline underline-offset-4 ml-1 transition">
                    Masuk Sekarang
                </a>
            </div>
        </div>

    </div>
</x-guest-layout>

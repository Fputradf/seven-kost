<x-guest-layout title="Atur Ulang Password - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto" x-data="{ showPassword: false, showPasswordConfirmation: false }">

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
                    Atur Ulang Kata Sandi 🔑
                </h2>
                <p class="text-xs sm:text-sm text-blue-200/80 mt-2 leading-relaxed">
                    Silakan tentukan kata sandi baru untuk akun SevenKost Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                        Alamat Email
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
                               value="{{ old('email', $request->email) }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="nama@email.com"
                               class="w-full h-11 pl-10 pr-4 bg-white/10 border @error('email') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-300 font-semibold flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                {{-- Input Password Baru --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                        Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" 
                               :type="showPassword ? 'text' : 'password'"
                               name="password" 
                               required 
                               autocomplete="new-password" 
                               placeholder="Minimal 8 karakter"
                               class="w-full h-11 pl-10 pr-10 bg-white/10 border @error('password') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                aria-label="Tampilkan sandi"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white active:scale-75 transition-all duration-150 focus:outline-none cursor-pointer">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Konfirmasi Password Baru --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" 
                               :type="showPasswordConfirmation ? 'text' : 'password'"
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password" 
                               placeholder="Ulangi kata sandi baru"
                               class="w-full h-11 pl-10 pr-10 bg-white/10 border @error('password_confirmation') border-rose-400/80 bg-rose-500/10 @else border-white/20 @enderror rounded-2xl text-sm text-white placeholder-blue-200/60 focus:bg-white/15 focus:outline-none focus:border-cyan-300 focus:ring-4 focus:ring-cyan-400/20 transition-all duration-200" />
                        <button type="button" 
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                aria-label="Tampilkan sandi konfirmasi"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-300 hover:text-white active:scale-75 transition-all duration-150 focus:outline-none cursor-pointer">
                            <svg x-show="!showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-rose-300 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full h-12 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm sm:text-base font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                        <span>Simpan Kata Sandi Baru</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>

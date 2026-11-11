<x-guest-layout title="Konfirmasi Password - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto" x-data="{ showPassword: false }">

        <div class="relative rounded-3xl bg-[#0f2d59]/80 backdrop-blur-2xl border border-white/20 shadow-2xl shadow-[#08182d]/80 overflow-hidden p-6 sm:p-8">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-400"></div>

            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center">
                    <x-seven-logo :light="true" size="md" />
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    Area Keamanan 🔒
                </h2>
                <p class="text-xs sm:text-sm text-blue-200/80 mt-2 leading-relaxed">
                    Halaman ini membutuhkan konfirmasi identitas. Silakan masukkan kata sandi Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-bold text-blue-100 uppercase tracking-wider mb-1.5">
                        Kata Sandi
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
                               autocomplete="current-password" 
                               placeholder="Masukkan password akun Anda"
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

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full h-11 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                        <span>Konfirmasi Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>

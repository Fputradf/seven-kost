<x-guest-layout title="Konfirmasi Password - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto" x-data="{ showPassword: false }">

        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200/80 p-8 sm:p-10">
            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost Logo" class="h-10 w-10 object-contain">
                    <span class="text-xl font-black text-slate-900">Seven<span class="text-blue-600">Kost</span></span>
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Area Keamanan
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                    Halaman ini memerlukan konfirmasi keamanan. Masukkan kata sandi Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input id="password" 
                               :type="showPassword ? 'text' : 'password'"
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="Masukkan kata sandi akun"
                               class="w-full pl-4 pr-10 py-3 rounded-xl border @error('password') border-red-400 bg-red-50/30 @else border-slate-300 bg-white @enderror text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition" />
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                aria-label="Tampilkan sandi"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer">
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
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-md shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer text-center">
                        Konfirmasi Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>

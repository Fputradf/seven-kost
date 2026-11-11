<x-guest-layout title="Verifikasi Email - SevenKost">
    <div class="w-full max-w-md mx-auto my-auto">

        <div class="relative rounded-3xl bg-[#0f2d59]/80 backdrop-blur-2xl border border-white/20 shadow-2xl shadow-[#08182d]/80 overflow-hidden p-6 sm:p-8">
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-400"></div>

            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center">
                    <x-seven-logo :light="true" size="md" />
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    Verifikasi Email Anda 📬
                </h2>
                <p class="text-xs sm:text-sm text-blue-200/80 mt-2 leading-relaxed">
                    Terima kasih telah mendaftar di SevenKost! Sebelum memulai, silakan periksa inbox email Anda dan klik link verifikasi yang telah kami kirimkan.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs sm:text-sm flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.</span>
                </div>
            @endif

            <div class="space-y-4 pt-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full h-11 flex justify-center items-center gap-2 px-6 rounded-2xl text-sm font-black text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-95 shadow-lg shadow-blue-500/40 hover:shadow-cyan-400/50 focus:outline-none focus:ring-4 focus:ring-cyan-400/30 transition-all duration-150 cursor-pointer">
                        <span>Kirim Ulang Email Verifikasi</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-rose-300 hover:text-rose-200 hover:underline transition">
                        Keluar dari Akun (Log Out)
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>

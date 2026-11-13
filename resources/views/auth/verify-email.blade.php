<x-guest-layout :title="'Verifikasi Email - ' . app_name()">
    <div class="w-full max-w-md mx-auto my-auto">

        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200/80 p-8 sm:p-10">
            <div class="mb-5 flex justify-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }} Logo" class="h-10 w-10 object-contain">
                    <span class="text-xl font-black text-slate-900">{{ app_name() }}</span>
                </a>
            </div>

            <div class="text-center mb-6">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Verifikasi Alamat Email
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                    Terima kasih telah mendaftar di SevenKost. Silakan periksa inbox email Anda dan klik tautan verifikasi yang telah kami kirimkan.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Tautan verifikasi baru telah dikirimkan ke alamat email Anda.</span>
                </div>
            @endif

            <div class="space-y-4 pt-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] transition shadow-md shadow-blue-600/20 focus:outline-none focus:ring-4 focus:ring-blue-100 cursor-pointer text-center">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center pt-2 border-t border-slate-200">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700 hover:underline">
                        Keluar dari Akun (Log Out)
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-guest-layout>

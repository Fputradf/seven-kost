<x-seven-layout title="Pusat Bantuan & FAQ - SevenKost">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
            Pusat Bantuan & FAQ
        </h1>
        <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto leading-relaxed mb-8">
            Halaman formulir pengaduan, panduan penggunaan, serta daftar tanya-jawab (FAQ) lengkap sedang dalam proses pengembangan.
        </p>

        <div class="inline-flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition">
                Kembali ke Beranda
            </a>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20SevenKost%2C%20saya%20butuh%20bantuan." target="_blank" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-bold text-sm shadow-xs transition">
                Hubungi WhatsApp Darurat
            </a>
        </div>
    </div>
</x-seven-layout>

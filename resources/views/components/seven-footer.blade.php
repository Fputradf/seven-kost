<footer {{ $attributes->merge(['class' => 'bg-white text-slate-700 pt-16 pb-8 border-t border-slate-200 shadow-xs mt-auto w-full']) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-10">
            {{-- Col 1: Brand & Logo (3 cols) --}}
            <div class="lg:col-span-3 space-y-3">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <img src="{{ app_logo_url() }}" alt="{{ app_name() }} Logo" class="h-11 w-11 object-contain drop-shadow-sm">
                    <div>
                        <span class="text-2xl font-black tracking-tight text-slate-900 block leading-none">{{ app_name() }}</span>
                        <span class="text-xs text-slate-500 mt-1 block font-medium">{{ setting('app_slogan', 'Cari Kos Jadi Lebih Mudah') }}</span>
                    </div>
                </a>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs pt-1">
                    {{ setting('app_description', 'Platform pencarian dan sewa kos nomor 1 di Indonesia dengan verifikasi resmi dan jangkauan 34 provinsi.') }}
                </p>
                <p class="text-xs text-slate-400 font-medium pt-3">
                    © {{ date('Y') }} {{ app_name() }}. All rights reserved.
                </p>
            </div>

            {{-- Col 2: Navigasi (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-base font-extrabold text-slate-900 mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-sm font-medium text-slate-600">
                    <li><a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-blue-600 transition">Layanan</a></li>
                    <li><a href="{{ url('/paket-iklan') }}" class="hover:text-blue-600 transition">Pasang Iklan</a></li>
                    <li><a href="{{ url('/#marketplace') }}" class="hover:text-blue-600 transition">Marketplace</a></li>
                </ul>
            </div>

            {{-- Col 3: Informasi (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-base font-extrabold text-slate-900 mb-4">Informasi</h4>
                <ul class="space-y-2.5 text-sm font-medium text-slate-600">
                    <li><a href="{{ url('/syarat-ketentuan') }}" class="hover:text-blue-600 transition">Tentang Kami</a></li>
                    <li><a href="{{ url('/syarat-ketentuan') }}" class="hover:text-blue-600 transition">Syarat & Ketentuan</a></li>
                    <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-blue-600 transition">Kebijakan Privasi</a></li>
                    <li><a href="{{ url('/bantuan') }}" class="hover:text-blue-600 transition">Pusat Bantuan & FAQ</a></li>
                </ul>
            </div>

            {{-- Col 4: Ikuti Kami (2 cols) --}}
            <div class="lg:col-span-2">
                <h4 class="text-base font-extrabold text-slate-900 mb-4">Ikuti Kami</h4>
                <div class="flex items-center gap-2.5 flex-wrap">
                    @foreach(app_social_links() as $social)
                        <a 
                            href="{{ $social['url'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-9 h-9 rounded-full bg-slate-100 {{ $social['color_class'] }} text-slate-600 flex items-center justify-center transition shadow-xs" 
                            aria-label="{{ $social['name'] }}"
                            title="{{ $social['name'] }}"
                        >
                            {!! $social['svg'] !!}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Col 5: Berlangganan Newsletter (3 cols) --}}
            <div class="lg:col-span-3">
                <h4 class="text-base font-extrabold text-slate-900 mb-2">Berlangganan Newsletter</h4>
                <p class="text-xs text-slate-500 mb-3">Dapatkan info kos terbaru dan promo menarik!</p>
                <form action="#" onsubmit="event.preventDefault(); alert('Terima kasih telah berlangganan newsletter SevenKost!');" class="relative flex items-center">
                    <input 
                        type="email" 
                        required 
                        placeholder="Email Anda" 
                        class="w-full text-sm bg-slate-50 text-slate-800 placeholder-slate-400 border border-slate-200 rounded-full pl-5 pr-14 py-3 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition shadow-inner"
                    >
                    <button type="submit" class="absolute right-1.5 w-9 h-9 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition shadow-md" aria-label="Langganan">
                        <svg class="w-4 h-4 -rotate-45 -mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</footer>

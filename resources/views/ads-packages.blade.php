<x-seven-layout title="Paket Iklan Unggulan - Seven Kost">

    <div class="bg-navy-950 text-white py-16 border-b border-slate-800 text-center relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 relative z-10">
            <span class="inline-block px-3.5 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider">
                🌟 Monetisasi Mitra Pemilik
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                Paket Promosi & Iklan Unggulan
            </h1>
            <p class="text-xs sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Jadikan properti kos Anda tampil di halaman utama, urutan pertama hasil pencarian, dan lencana khusus untuk menarik penyewa lebih cepat.
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ orderModalOpen: false, selectedPkgId: null, selectedPkgName: '', selectedPkgPrice: '' }">
        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-10 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 flex items-center gap-2 text-xs font-semibold">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- PRICING CARDS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @foreach($packages as $pkg)
                @php
                    $isPopular = str_contains(strtolower($pkg->name), 'premium') || str_contains(strtolower($pkg->name), '14');
                @endphp
                <div class="relative rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 {{ $isPopular ? 'bg-navy-950 text-white shadow-2xl scale-105 border-2 border-amber-400/50' : 'bg-white text-slate-900 border border-slate-200/80 shadow-md' }}">
                    @if($isPopular)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gradient-to-r from-amber-400 to-orange-400 text-slate-950 font-black text-[10px] uppercase tracking-wider shadow-sm">
                            Paling Populer
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-extrabold text-lg">{{ $pkg->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isPopular ? 'bg-white/10 text-amber-300' : 'bg-blue-50 text-blue-700' }}">
                                {{ $pkg->duration_days }} Hari
                            </span>
                        </div>

                        <div class="flex items-baseline gap-1 my-6">
                            <span class="text-3xl sm:text-4xl font-black">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs {{ $isPopular ? 'text-slate-400' : 'text-slate-500' }}">/ periode</span>
                        </div>

                        <p class="text-xs {{ $isPopular ? 'text-slate-300' : 'text-slate-500' }} leading-relaxed mb-6">
                            {{ $pkg->description }}
                        </p>

                        <ul class="space-y-3 text-xs font-medium {{ $isPopular ? 'text-slate-200' : 'text-slate-700' }} mb-8">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Lencana Unggulan Emas
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Prioritas Teratas Hasil Pencarian
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Penanda Khusus di Peta Leaflet
                            </li>
                        </ul>
                    </div>

                    <div>
                        @auth
                            <button 
                                @click="selectedPkgId = {{ $pkg->id }}; selectedPkgName = '{{ $pkg->name }}'; selectedPkgPrice = 'Rp {{ number_format($pkg->price, 0, ',', '.') }}'; orderModalOpen = true;"
                                type="button" 
                                class="w-full py-3 rounded-2xl text-xs font-bold transition shadow-sm {{ $isPopular ? 'bg-amber-400 hover:bg-amber-500 text-slate-950 font-black' : 'bg-blue-600 hover:bg-blue-700 text-white' }}"
                            >
                                Pesan Paket Ini
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-3 rounded-2xl text-xs font-bold text-center transition shadow-sm {{ $isPopular ? 'bg-amber-400 text-slate-950 font-black' : 'bg-blue-600 text-white' }}">
                                Masuk untuk Memesan
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- INFO PEMBAYARAN MANUAL --}}
        <div class="mt-16 bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs max-w-3xl mx-auto">
            <h3 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Instruksi Pembayaran Manual (Fase MVP)
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                Sesuai cakupan MVP Seven Kost, pembayaran dicatat dan diverifikasi secara manual oleh admin. Silakan transfer ke rekening resmi berikut dan unggah bukti transfer:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div>
                    <span class="text-slate-400 block">Bank BCA</span>
                    <strong class="text-slate-900 font-mono text-sm block">123-456-7890</strong>
                    <span class="text-slate-500">a.n. Seven Kost Nusantara PT</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Bank Mandiri</span>
                    <strong class="text-slate-900 font-mono text-sm block">137-00-9876543-2</strong>
                    <span class="text-slate-500">a.n. Seven Kost Nusantara PT</span>
                </div>
            </div>
        </div>

        {{-- MODAL PESAN PAKET IKLAN --}}
        @auth
            <div x-show="orderModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
                <div @click.outside="orderModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-5 text-slate-800">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-amber-500 uppercase">Konfirmasi Pemesanan</span>
                            <h3 class="text-base font-black text-slate-900" x-text="selectedPkgName"></h3>
                        </div>
                        <button @click="orderModalOpen = false" class="text-slate-400 hover:text-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    @if($myKosts->count() > 0)
                        <form action="{{ route('ads.packages.order') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="ads_package_id" :value="selectedPkgId">

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Pilih Properti Kos yang Dipromosikan</label>
                                <select name="kost_id" required class="w-full text-xs font-medium rounded-xl border-slate-200 py-2.5">
                                    @foreach($myKosts as $mKost)
                                        <option value="{{ $mKost->id }}">{{ $mKost->name }} ({{ $mKost->regency->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                <span class="text-xs text-slate-500">Nominal Transfer:</span>
                                <strong class="text-sm font-black text-blue-700" x-text="selectedPkgPrice"></strong>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Unggah Bukti Transfer (Struk/Screenshot)</label>
                                <input type="file" name="proof_of_payment" accept="image/*" required class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <div class="pt-2 flex gap-2">
                                <button type="button" @click="orderModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                                    Kirim Bukti Pembayaran
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-6 space-y-3">
                            <p class="text-xs text-slate-500">
                                Anda belum memiliki properti kos yang berstatus <strong>Terverifikasi</strong>. Daftarkan dan verifikasi iklan kos Anda terlebih dahulu sebelum membeli paket iklan unggulan.
                            </p>
                            <x-seven-button :href="route('owner.kosts.create')" variant="primary" size="sm">
                                Pasang Iklan Kos
                            </x-seven-button>
                        </div>
                    @endif
                </div>
            </div>
        @endauth
    </div>

</x-seven-layout>

<footer class="bg-navy-950 text-slate-300 pt-16 pb-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
            {{-- Col 1: Brand & Desc --}}
            <div class="lg:col-span-2 space-y-4">
                <x-seven-logo :light="true" size="lg" />
                <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                    Platform pencarian dan pengelolaan kos modern seluruh Indonesia. Menghubungkan pemilik kos dengan calon penghuni secara aman, cepat, dan terverifikasi.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-xs font-medium text-blue-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Cakupan 34 Provinsi di Indonesia
                    </span>
                </div>
            </div>

            {{-- Col 2: Kota Populer --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-4">Kota Populer</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ url('/cari?kota=3171') }}" class="hover:text-white transition">Kos Jakarta Selatan</a></li>
                    <li><a href="{{ url('/cari?kota=3471') }}" class="hover:text-white transition">Kos Yogyakarta</a></li>
                    <li><a href="{{ url('/cari?kota=3273') }}" class="hover:text-white transition">Kos Bandung</a></li>
                    <li><a href="{{ url('/cari?kota=3578') }}" class="hover:text-white transition">Kos Surabaya</a></li>
                    <li><a href="{{ url('/cari?kota=3573') }}" class="hover:text-white transition">Kos Malang</a></li>
                    <li><a href="{{ url('/cari?kota=5171') }}" class="hover:text-white transition">Kos Denpasar</a></li>
                </ul>
            </div>

            {{-- Col 3: Fitur Utama --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-4">Fitur Utama</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ url('/cari') }}" class="hover:text-white transition">Pencarian & Filter</a></li>
                    <li><a href="{{ url('/peta') }}" class="hover:text-white transition">Peta Interaktif Leaflet</a></li>
                    <li><a href="{{ url('/paket-iklan') }}" class="hover:text-white transition">Paket Iklan Unggulan</a></li>
                    <li><a href="{{ url('/iklan/buat') }}" class="hover:text-white transition">Pasang Iklan Pemilik</a></li>
                    <li><a href="{{ url('/verifikasi-kos') }}" class="hover:text-white transition">Lencana Terverifikasi</a></li>
                </ul>
            </div>

            {{-- Col 4: Bantuan & Legal --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-4">Bantuan & Legal</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                    <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi (UU PDP)</a></li>
                    <li><a href="#" class="hover:text-white transition">Laporkan Pelanggaran</a></li>
                    <li><a href="https://wa.me/" target="_blank" class="hover:text-white transition">Hubungi Kami (WhatsApp)</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>© {{ date('Y') }} Seven Kost. Hak Cipta Dilindungi Undang-Undang.</p>
            <p class="flex items-center gap-4">
                <span>Dibuat dengan Laravel 11 & Tailwind CSS</span>
                <span class="inline-block h-3 w-px bg-slate-800"></span>
                <span>Fase MVP 1 Bulan</span>
            </p>
        </div>
    </div>
</footer>


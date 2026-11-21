<x-tenant-layout title="Pusat Bantuan & FAQ">

    @php
        $user = auth()->user();
        $userName = $user ? $user->name : 'Penghuni';
        $userEmail = $user ? $user->email : '';
        $csPhone = setting('cs_whatsapp', '6281234567890');
        $cleanCsPhone = preg_replace('/[^0-9]/', '', $csPhone);
        if (str_starts_with($cleanCsPhone, '0')) {
            $cleanCsPhone = '62' . substr($cleanCsPhone, 1);
        }
        $csMessage = urlencode("Halo Tim Bantuan SevenKost, saya {$userName} ({$userEmail}) membutuhkan bantuan terkait portal penghuni SevenKost.");
        $waCsLink = "https://wa.me/{$cleanCsPhone}?text={$csMessage}";
    @endphp

    <div 
        class="space-y-4"
        x-data="{
            searchQuery: '',
            activeCategory: 'all',
            openFaq: 1,
            chatModalOpen: false,
            chatTopic: 'sewa',
            chatMessage: '',
            chatMessages: [
                {
                    sender: 'admin',
                    name: 'Customer Support SevenKost',
                    role: 'Admin',
                    time: 'Online',
                    text: 'Halo {{ $userName }}! Ada yang bisa kami bantu seputar sewa hunian, saldo dompet, atau fasilitas kamar kos Anda?'
                }
            ],
            chatSent: false,
            toggleFaq(id) {
                this.openFaq = this.openFaq === id ? null : id;
            },
            sendChatMessage() {
                if (!this.chatMessage.trim()) return;
                this.chatMessages.push({
                    sender: 'user',
                    name: '{{ $userName }}',
                    role: 'Penghuni',
                    time: 'Baru saja',
                    text: this.chatMessage
                });
                const topicLabel = this.chatTopic === 'sewa' ? 'Sewa Kos & Tagihan' : (this.chatTopic === 'dompet' ? 'Saldo Dompet' : (this.chatTopic === 'keluhan' ? 'Fasilitas Kamar' : 'Bantuan Umum'));
                this.chatMessage = '';
                this.chatSent = true;
                setTimeout(() => {
                    this.chatMessages.push({
                        sender: 'admin',
                        name: 'Admin Layanan',
                        role: 'CS Support',
                        time: 'Baru saja',
                        text: 'Pesan Anda mengenai [' + topicLabel + '] telah diterima. Anda juga dapat melanjutkan obrolan langsung via WhatsApp resmi kami untuk respon instan.'
                    });
                }, 1000);
            }
        }"
    >

        {{-- 1. HERO BANNER BANTUAN --}}
        <div class="relative overflow-hidden rounded-2xl text-white p-4 shadow-md shadow-blue-900/15 border border-blue-400/25 space-y-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 space-y-1">
                <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">Bantuan & FAQ Penghuni</span>
                <h1 class="text-base sm:text-lg font-black text-white !text-white leading-tight">
                    Ada yang Bisa Kami Bantu?
                </h1>
                <p class="text-xs text-blue-100 font-normal">
                    Panduan sewa kamar, perpanjangan otomatis, dompet saldo, hingga lapor keluhan fasilitas.
                </p>
            </div>

            {{-- Search Bar --}}
            <div class="relative pt-1">
                <span class="absolute inset-y-0 left-0 pt-1 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    placeholder="Cari pertanyaan... (tagihan, dompet, check out, komplain)" 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-white text-slate-800 placeholder-slate-400 text-xs font-medium border-0 focus:ring-2 focus:ring-blue-400 shadow-sm"
                >
            </div>
        </div>

        {{-- 2. QUICK CONTACT SHORTCUTS (3 KARTU AKSI CEPAT) --}}
        <div class="grid grid-cols-3 gap-2">
            {{-- WhatsApp CS --}}
            <a href="{{ $waCsLink }}" target="_blank" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-center space-y-1 hover:border-emerald-300 transition shadow-2xs group active:scale-95">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto group-hover:scale-105 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                </div>
                <span class="text-[11px] font-bold text-slate-800 block truncate">Chat WA CS</span>
            </a>

            {{-- Live Chat Support --}}
            <button type="button" @click="chatModalOpen = true" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-center space-y-1 hover:border-blue-300 transition shadow-2xs group active:scale-95 cursor-pointer">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mx-auto group-hover:scale-105 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <span class="text-[11px] font-bold text-slate-800 block truncate">Live Support</span>
            </button>

            {{-- Lapor Keluhan --}}
            <a href="{{ route('tenant.complaints.create') }}" class="p-2.5 rounded-xl bg-white border border-slate-200/90 text-center space-y-1 hover:border-amber-300 transition shadow-2xs group active:scale-95">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mx-auto group-hover:scale-110 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <span class="text-[11px] font-bold text-slate-800 block truncate">Lapor Kamar</span>
            </a>
        </div>

        {{-- 3. KATEGORI TABS (HORIZONTAL SCROLLABLE) --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs scrollbar-none">
            <button 
                type="button" 
                @click="activeCategory = 'all'" 
                class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap cursor-pointer"
                :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"
            >
                Semua Topik
            </button>
            <button 
                type="button" 
                @click="activeCategory = 'sewa'" 
                class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap cursor-pointer"
                :class="activeCategory === 'sewa' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"
            >
                Sewa & Tagihan
            </button>
            <button 
                type="button" 
                @click="activeCategory = 'dompet'" 
                class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap cursor-pointer"
                :class="activeCategory === 'dompet' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"
            >
                Dompet Saldo
            </button>
            <button 
                type="button" 
                @click="activeCategory = 'marketplace'" 
                class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap cursor-pointer"
                :class="activeCategory === 'marketplace' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"
            >
                Marketplace COD
            </button>
            <button 
                type="button" 
                @click="activeCategory = 'keluhan'" 
                class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 whitespace-nowrap cursor-pointer"
                :class="activeCategory === 'keluhan' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'"
            >
                Keluhan Fasilitas
            </button>
        </div>

        {{-- 4. DAFTAR FAQ (ACCORDION KHUSUS PENGHUNI) --}}
        <div class="space-y-2.5">

            {{-- FAQ 1: Tagihan Otomatis & Denda --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'sewa') && (searchQuery === '' || 'tagihan perpanjangan sewa otomatis denda h-7 h+1 habis jatuh tempo'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(1)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 1 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Bagaimana sistem perpanjangan sewa otomatis dan denda keterlambatan?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 1 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 1" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Sistem SevenKost secara otomatis menerbitkan tagihan perpanjangan sewa <strong>H-7 sebelum masa sewa kamar Anda berakhir</strong>. Anda dapat melunasinya langsung via Saldo Dompet atau Transfer Bank/E-Wallet (Xendit).
                    </p>
                    <p>
                        Jika melewati tanggal jatuh tempo (H+1 sewa habis), denda harian keterlambatan akan diakumulasikan secara otomatis sesuai konfigurasi resmi yang berlaku.
                    </p>
                </div>
            </div>

            {{-- FAQ 2: Cara Akhiri Sewa / Check Out --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'sewa') && (searchQuery === '' || 'akhiri sewa check out berhenti selesai kos kamar pindah'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(2)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 2 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Bagaimana cara mengakhiri sewa (Check-Out) jika saya tidak ingin memperpanjang?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 2 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 2" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Jika Anda ingin pindah atau selesai masa sewa, Anda dapat menekan tombol <strong>"Akhiri Sewa & Check-Out Kamar"</strong> yang terletak tepat di bawah tombol <em>Tanya Pemilik</em> pada kartu hunian aktif Dashboard atau halaman Detail Sewa.
                    </p>
                    <p>
                        Setelah dikonfirmasi, status sewa Anda akan ditandai selesai (*completed*), tagihan perpanjangan otomatis dibatalkan, dan kuota kamar dikembalikan ke pemilik kos.
                    </p>
                </div>
            </div>

            {{-- FAQ 3: Saldo Dompet & Top Up --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'dompet') && (searchQuery === '' || 'dompet saldo top up bayar sewa tarik withdrawal rekening dana ovo gopay'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(3)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 3 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Bagaimana cara mengisi dan menggunakan Saldo Dompet Digital?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 3 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 3" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Buka menu <strong>Dompet</strong> dari navigasi. Anda dapat melakukan <strong>Top Up</strong> via Virtual Account (BCA, Mandiri, BRI, BNI) atau QRIS/E-Wallet melalui payment gateway Xendit.
                    </p>
                    <p>
                        Saldo dompet dapat digunakan untuk melunasi tagihan sewa kos seketika dengan 1 klik tanpa biaya transaksi tambahan, serta dapat ditarik kembali (*withdrawal*) ke rekening bank pribadi Anda kapan saja.
                    </p>
                </div>
            </div>

            {{-- FAQ 4: Lapor Keluhan & Perbaikan --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'keluhan') && (searchQuery === '' || 'lapor keluhan fasilitas rusak ac bocor kran air mati wifi komplain'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(4)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 4 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Bagaimana jika ada kerusakan fasilitas kamar (AC, kran, WiFi)?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 4 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 4" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Buka menu <strong>Lapor Keluhan Fasilitas</strong> di drawer samping atau tombol <em>Lapor</em> pada kartu hunian aktif. Masukkan judul kerusakan, kategori (Listrik, Air, AC, Furnitur, Kebersihan), serta foto bukti kerusakan.
                    </p>
                    <p>
                        Tiket keluhan akan langsung masuk ke notifikasi pemilik kos dan dipantau hingga perbaikan selesai (*resolved*).
                    </p>
                </div>
            </div>

            {{-- FAQ 5: Marketplace COD --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'marketplace') && (searchQuery === '' || 'marketplace jual beli barang bekas kasur kipas cod aman'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(5)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 5 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Bagaimana aturan transaksi di Marketplace Anak Kos?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 5 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 5" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Marketplace SevenKost menggunakan sistem <strong>100% Cash on Delivery (COD)</strong> tanpa biaya perantara. Pembeli dan penjual menyepakati titik temu aman (seperti lobby kos atau area kampus).
                    </p>
                    <p>
                        Periksa kondisi fisik barang secara teliti sebelum serah terima uang. Tidak ada pembayaran di muka melalui aplikasi.
                    </p>
                </div>
            </div>

            {{-- FAQ 6: Status Pembayaran Pending --}}
            <div 
                x-show="(activeCategory === 'all' || activeCategory === 'sewa') && (searchQuery === '' || 'pembayaran pending xendit transfer sudah bayar status belum berubah'.includes(searchQuery.toLowerCase()))"
                class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition"
            >
                <button 
                    type="button" 
                    @click="toggleFaq(6)" 
                    class="w-full p-3.5 text-left flex items-start justify-between gap-3 font-bold text-xs sm:text-sm transition cursor-pointer"
                    :class="openFaq === 6 ? 'text-blue-700 bg-blue-50/50' : 'text-slate-900 hover:text-blue-600'"
                >
                    <span class="leading-snug">Sudah transfer via Xendit tetapi status masih menunggu pembayaran?</span>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200 mt-0.5" :class="openFaq === 6 ? 'rotate-180 text-blue-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openFaq === 6" x-collapse class="px-3.5 pb-3.5 pt-2 text-xs text-slate-600 space-y-2 border-t border-slate-100 leading-relaxed">
                    <p>
                        Sistem SevenKost dilengkapi fitur auto-sinkronisasi status Xendit. Anda dapat membuka halaman pembayaran dan menekan tombol <strong>"Sudah Bayar? Cek Status Pembayaran Sekarang"</strong> untuk verifikasi langsung dengan server payment gateway.
                    </p>
                </div>
            </div>

        </div>

        {{-- 5. KARTU HUBUNGI CS SUPPORT ONLINE --}}
        <div class="rounded-2xl p-4 text-white shadow-md border border-blue-400/25 space-y-3" style="background: linear-gradient(135deg, #091e3a 0%, #0f3d7c 50%, #1d4ed8 100%);">
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider block">Customer Support 24/7</span>
                <h3 class="text-sm sm:text-base font-black text-white">
                    Butuh Bantuan Lebih Lanjut?
                </h3>
                <p class="text-xs text-blue-100 font-normal leading-relaxed">
                    Tim Super Admin & Customer Service SevenKost siap membantu kendala hunian dan pembayaran Anda.
                </p>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <a href="{{ $waCsLink }}" target="_blank" class="flex-1 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 flex items-center justify-center gap-1.5 text-center">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Hubungi CS WhatsApp</span>
                </a>
                <button type="button" @click="chatModalOpen = true" class="py-2.5 px-3 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs border border-white/30 transition active:scale-95 cursor-pointer flex items-center justify-center gap-1.5 text-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Live Chat</span>
                </button>
            </div>
        </div>

        {{-- 6. MODAL LIVE CHAT DUKUNGAN REAL-TIME --}}
        <div 
            x-show="chatModalOpen" 
            x-transition.opacity 
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3" 
            style="display: none;"
        >
            <div @click.outside="chatModalOpen = false" class="bg-white rounded-2xl max-w-sm w-full p-4 shadow-2xl space-y-3 text-slate-800 flex flex-col max-h-[85vh]">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                            CS
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-slate-900 block leading-tight">Support SevenKost</strong>
                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Admin Aktif Online
                            </span>
                        </div>
                    </div>
                    <button @click="chatModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Pilihan Topik --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase">Topik Kendala:</label>
                    <select x-model="chatTopic" class="w-full text-xs py-1.5 px-2 rounded-lg border border-slate-200 bg-slate-50">
                        <option value="sewa">Kendala Sewa & Tagihan</option>
                        <option value="dompet">Saldo Dompet & Pembayaran</option>
                        <option value="keluhan">Fasilitas Kamar Kos</option>
                        <option value="umum">Pertanyaan Umum Lainnya</option>
                    </select>
                </div>

                {{-- Chat Feed Area --}}
                <div class="flex-1 overflow-y-auto space-y-2 py-2 pr-1 min-h-[160px] max-h-[240px] text-xs">
                    <template x-for="(msg, i) in chatMessages" :key="i">
                        <div :class="msg.sender === 'user' ? 'text-right' : 'text-left'" class="space-y-0.5">
                            <div class="text-[10px] text-slate-400 font-semibold" x-text="msg.name + ' • ' + msg.time"></div>
                            <div 
                                class="inline-block p-2.5 rounded-2xl max-w-[85%] text-xs leading-relaxed"
                                :class="msg.sender === 'user' ? 'bg-blue-600 text-white rounded-br-none text-left' : 'bg-slate-100 text-slate-800 rounded-bl-none'"
                                x-text="msg.text"
                            ></div>
                        </div>
                    </template>
                </div>

                {{-- Input Chat Bar --}}
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <div class="flex items-center gap-1.5">
                        <input 
                            type="text" 
                            x-model="chatMessage" 
                            @keydown.enter.prevent="sendChatMessage()"
                            placeholder="Ketik pesan Anda..." 
                            class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500"
                        >
                        <button 
                            type="button" 
                            @click="sendChatMessage()"
                            class="p-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </div>

                    <a href="{{ $waCsLink }}" target="_blank" class="w-full py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-[11px] border border-emerald-200 transition flex items-center justify-center gap-1.5 text-center">
                        <svg class="w-3.5 h-3.5 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Atau Lanjutkan di WhatsApp CS Resmi</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

</x-tenant-layout>

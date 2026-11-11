<x-seven-layout title="Pusat Bantuan & FAQ - SevenKost">

    {{-- Breadcrumbs Navigation --}}
    <div class="bg-white/80 backdrop-blur-md border-b border-slate-100 py-3.5 sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-semibold">Pusat Bantuan & FAQ</span>
            </nav>
        </div>
    </div>

    {{-- Root Container with Alpine.js FAQ State --}}
    <div 
        x-data="{
            searchQuery: '',
            activeCategory: 'all',
            openFaq: null,
            chatModalOpen: false,
            chatMessage: '',
            chatTopic: 'general',
            chatMessages: [
                {
                    sender: 'admin',
                    name: 'Super Admin SevenKost',
                    role: 'Superadmin',
                    time: 'Baru saja',
                    text: 'Halo! Selamat datang di Pusat Bantuan SevenKost. Ada yang bisa kami bantu seputar sewa kos, marketplace barang, atau layanan kami?'
                }
            ],
            chatSent: false,

            toggleFaq(id) {
                this.openFaq = this.openFaq === id ? null : id;
            },

            sendChatMessage() {
                if (!this.chatMessage.trim()) return;
                
                // Tambahkan pesan pengguna
                this.chatMessages.push({
                    sender: 'user',
                    name: 'Anda',
                    role: 'User',
                    time: 'Baru saja',
                    text: this.chatMessage
                });

                const topicLabel = this.chatTopic === 'booking' ? 'Kendala Sewa Kos' : (this.chatTopic === 'marketplace' ? 'Jual Beli COD' : (this.chatTopic === 'service' ? 'Layanan Kamar' : 'Bantuan Umum'));
                this.chatMessage = '';
                this.chatSent = true;

                // Simulasi balasan otomatis admin
                setTimeout(() => {
                    this.chatMessages.push({
                        sender: 'admin',
                        name: 'Maya Kartika (Admin Operasional)',
                        role: 'Admin Verifikator',
                        time: 'Baru saja',
                        text: 'Terima kasih atas pesan Anda mengenai [' + topicLabel + ']. Pesan Anda telah diteruskan ke meja antrean admin kami dan akan direspon dalam waktu 2-5 menit kerja.'
                    });
                }, 1000);
            }
        }"
        class="pb-20"
    >
        {{-- 1. HERO SECTION (SEARCH & HEADER) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-5 pb-2">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#031d44] via-[#05377a] to-[#0a66c2] text-white p-4 sm:p-5 md:p-6 shadow-md shadow-blue-950/15 border border-white/15">
                {{-- Decorative background glow --}}
                <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-blue-400/15 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute -top-6 right-1/4 w-28 h-28 bg-sky-300/15 rounded-full blur-lg pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl space-y-2">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-semibold text-blue-100">
                        <span class="w-1.5 h-1.5 bg-sky-300 inline-block"></span>
                        Pusat Bantuan & Tanya Jawab Resmi
                    </div>

                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight leading-tight">
                        Ada yang Bisa Kami <span class="text-sky-300">Bantu?</span>
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                        Temukan petunjuk lengkap cara memesan kos, transaksi jual beli aman di marketplace, pemesanan layanan harian, hingga pemasangan iklan properti & banner.
                    </p>

                    {{-- Search Input Bar --}}
                    <div class="pt-1 max-w-xl">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input 
                                type="text" 
                                x-model="searchQuery" 
                                placeholder="Ketik kata kunci pertanyaan (misal: booking, COD, laundry, iklan)..." 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white text-slate-800 placeholder-slate-400 text-xs sm:text-sm font-medium border-0 focus:ring-2 focus:ring-blue-400 shadow-md"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. KATEGORI FILTER TABS --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 relative z-10">
            <div class="bg-white rounded-2xl sm:rounded-3xl p-2.5 sm:p-3 shadow-md shadow-slate-200/50 border border-slate-200/80 flex items-center justify-start sm:justify-center gap-2 overflow-x-auto scrollbar-none">
                <button 
                    @click="activeCategory = 'all'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all"
                    :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    Semua Pertanyaan
                </button>
                <button 
                    @click="activeCategory = 'kost'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'kost' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🏠 Sewa & Booking Kos
                </button>
                <button 
                    @click="activeCategory = 'marketplace'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'marketplace' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🛍️ Jual Beli (100% COD)
                </button>
                <button 
                    @click="activeCategory = 'layanan'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'layanan' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🧺 Layanan Kebutuhan Kos
                </button>
                <button 
                    @click="activeCategory = 'iklan'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'iklan' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    📢 Pasang Iklan & Banner
                </button>
                <button 
                    @click="activeCategory = 'akun'" 
                    type="button" 
                    class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all flex items-center gap-1.5"
                    :class="activeCategory === 'akun' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-transparent text-slate-600 hover:bg-slate-100'"
                >
                    🔒 Akun & Keamanan
                </button>
            </div>
        </section>

        {{-- 3. DAFTAR FAQ (ACCORDION DENGAN SEARCH & FILTER) --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="space-y-4 max-w-4xl mx-auto">

                {{-- FAQ 1: Cara Sewa Kos --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'kost') && (searchQuery === '' || 'cara alur cari pesan booking sewa kamar kos idaman'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(1)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Bagaimana alur mencari dan memesan kamar kos di SevenKost?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 1 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Alur pemesanan kos di SevenKost dirancang cepat dan transparan:</p>
                        <ol class="list-decimal pl-5 space-y-1 text-slate-700">
                            <li>Cari kos berdasarkan nama kota, area kampus, atau fasilitas di kolom pencarian Beranda.</li>
                            <li>Buka halaman detail kos untuk melihat foto kamar, harga sewa bulanan, dan kelengkapan fasilitas.</li>
                            <li>Klik tombol <strong>Ajukan Sewa / Pesan Kamar</strong> pada tipe kamar yang dipilih.</li>
                            <li>Tentukan tanggal mulai masuk (check-in) dan durasi masa sewa (bulanan/tahunan).</li>
                            <li>Pemilik kos akan segera memverifikasi ketersediaan kamar dan menghubungi Anda langsung tanpa biaya perantara.</li>
                        </ol>
                    </div>
                </div>

                {{-- FAQ 2: Survei Kos --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'kost') && (searchQuery === '' || 'survei lokasi datang lihat fisik kamar janji temu'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(2)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Apakah saya bisa survei lokasi kamar kos terlebih dahulu?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 2 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Ya, sangat disarankan! Calon penyewa dapat membuat jadwal survei lokasi langsung ke bangunan kos sebelum memutuskan untuk membayar sewa. Anda dapat menghubungi pemilik atau penjaga kos melalui kontak resmi yang tertera di halaman detail properti untuk menentukan jam temu survei.</p>
                    </div>
                </div>

                {{-- FAQ 3: Verifikasi Kos --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'kost') && (searchQuery === '' || 'aman penipuan verifikasi kos asli terverifikasi palsu'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(3)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Apa jaminan bahwa listing kos di SevenKost aman dan tidak fiktif?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 3 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Listing properti yang memiliki tanda <strong>Kos Terverifikasi</strong> telah ditinjau secara ketat oleh tim admin kami dengan memvalidasi identitas kepemilikan pemilik, alamat akurat, dan keaslian foto properti untuk melindungi calon penyewa dari potensi penipuan.</p>
                    </div>
                </div>

                {{-- FAQ 4: Marketplace Cara Jual Barang --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'marketplace') && (searchQuery === '' || 'jual barang bekas perlengkapan kos kasur meja belajar posting'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(4)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Bagaimana cara menjual barang kos bekas yang sudah tidak terpakai?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 4 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Memasang iklan jual barang di Marketplace SevenKost 100% gratis:</p>
                        <ol class="list-decimal pl-5 space-y-1 text-slate-700">
                            <li>Masuk ke akun terdaftar Anda di SevenKost (akun pencari kos/penghuni atau pemilik).</li>
                            <li>Buka menu <strong>Marketplace</strong> dan klik tombol <strong>+ Posting Jual Barang</strong>.</li>
                            <li>Isi judul barang, kategori (kasur, elektronik, meja, rak, dll.), dan kondisi fisik barang.</li>
                            <li>Tentukan harga pas mahasiswa dan lokasi titik temu aman (contoh: Lobby Kos atau Area Kampus).</li>
                            <li>Unggah foto asli barang dan simpan. Iklan Anda langsung aktif dan dapat dilihat oleh anak kos lainnya!</li>
                        </ol>
                    </div>
                </div>

                {{-- FAQ 5: Marketplace Sistem COD --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'marketplace') && (searchQuery === '' || 'cod bayar tempat perantara rekening bersama pihak ketiga escrow'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(5)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Bagaimana sistem transaksi COD di Marketplace SevenKost?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 5 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Platform SevenKost menerapkan <strong>Sistem 100% COD (Cash on Delivery)</strong> murni. SevenKost tidak bertindak sebagai pihak ketiga penampung uang (escrow). Seluruh transaksi pembayaran dilakukan langsung antara pembeli dan penjual saat bertatap muka memeriksa kondisi barang secara fisik di lokasi titik temu aman.</p>
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs font-semibold">
                            ⚠️ Tips Keamanan: Jangan pernah melakukan transfer uang muka (DP) kepada siapa pun sebelum bertemu langsung dan memastikan kondisi barang sesuai kesepakatan.
                        </div>
                    </div>
                </div>

                {{-- FAQ 6: Layanan Siapa yang Bisa Pesan --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'layanan') && (searchQuery === '' || 'siapa pesan layanan laundry cleaning teknisi penghuni'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(6)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Siapa saja yang berhak memesan Layanan Kos (Laundry, Cleaning, Teknisi)?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 6 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Pemesanan layanan harian SevenKost khusus diperuntukkan bagi <strong>penghuni kos aktif</strong> yang terdaftar. Saat memesan, Anda akan diminta mengisi nomor kamar kos dan jadwal kedatangan mitra agar pengerjaan dapat dilakukan tepat waktu langsung ke kamar kos Anda.</p>
                    </div>
                </div>

                {{-- FAQ 7: Ragam Layanan --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'layanan') && (searchQuery === '' || 'paket laundry kiloan bersih kamar mandi toilet ac teknisi'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(7)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Apa saja jenis layanan yang tersedia di SevenKost?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 7 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 7" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <ul class="space-y-1.5 list-disc pl-5 text-slate-700">
                            <li><strong>Seven Laundry Kos</strong>: Cuci lipat & cuci setrika kiloan/satuan dengan layanan antar jemput langsung ke depan kamar kos.</li>
                            <li><strong>Seven Clean Kamar</strong>: Layanan kebersihan kamar kos, pembersihan debu, sapu pel, hingga pembersihan kerak toilet kamar mandi kos.</li>
                            <li><strong>Seven Care Teknisi</strong>: Perawatan rutin, service kebocoran, dan pencucian AC kamar kos oleh teknisi bersertifikasi.</li>
                        </ul>
                    </div>
                </div>

                {{-- FAQ 8: Pasang Iklan VIP Kos --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'iklan') && (searchQuery === '' || 'pasang iklan properti kos vip pemilik sundul prioritas'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(8)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Apa keuntungan paket Iklan VIP Properti bagi pemilik kos?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 8 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 8" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Dengan mengupgrade kos ke <strong>Paket VIP Terpilih</strong>, properti Anda akan mendapatkan:</p>
                        <ul class="list-disc pl-5 space-y-1 text-slate-700">
                            <li>Posisi prioritas teratas di halaman pencarian kota dan kampus.</li>
                            <li>Badge eksklusif VIP & highlight border biru cerah pada kartu kos.</li>
                            <li>Fitur sundul otomatis berkala agar kos selalu berada di deretan listing terdepan.</li>
                            <li>Kecepatan verifikasi iklan prioritas langsung oleh tim admin tanpa antrean.</li>
                        </ul>
                    </div>
                </div>

                {{-- FAQ 9: Banner Promosi --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'iklan') && (searchQuery === '' || 'banner promosi iklan sponsor komersial brand bisnis tayang'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(9)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Di mana saja Iklan Banner Promosi akan ditayangkan?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 9 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 9" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Iklan Banner Promosi Brand & Layanan ditayangkan pada slider premium di 3 halaman utama: <strong>Beranda Utama</strong>, <strong>Marketplace Barang Kos</strong>, dan <strong>Layanan Kos</strong>. Banner dilengkapi tautan langsung ke situs atau nomor kontak bisnis Anda.</p>
                    </div>
                </div>

                {{-- FAQ 10: Akun & Lupa Password --}}
                <div 
                    x-show="(activeCategory === 'all' || activeCategory === 'akun') && (searchQuery === '' || 'daftar akun lupa kata sandi password reset profil'.includes(searchQuery.toLowerCase()))"
                    class="bg-white rounded-2xl border border-slate-200/90 shadow-xs hover:border-blue-200 transition overflow-hidden"
                >
                    <button 
                        type="button" 
                        @click="toggleFaq(10)" 
                        class="w-full p-4 sm:p-5 text-left flex items-center justify-between gap-4 font-bold text-slate-900 text-sm sm:text-base focus:outline-none"
                    >
                        <span class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-blue-600 inline-block shrink-0"></span>
                            Bagaimana cara mendaftar akun atau mereset kata sandi yang lupa?
                        </span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200" :class="openFaq === 10 ? 'rotate-180 text-blue-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 10" x-collapse class="px-4 sm:px-5 pb-5 text-xs sm:text-sm text-slate-600 space-y-2 border-t border-slate-100 pt-3 leading-relaxed">
                        <p>Pendaftaran akun di SevenKost 100% gratis melalui menu <strong>Daftar</strong>. Jika Anda lupa kata sandi, buka menu <strong>Masuk</strong> lalu klik tautan <em>Lupa kata sandi?</em> dan masukkan email Anda untuk menerima instruksi pemulihan instan.</p>
                    </div>
                </div>

            </div>
        </section>

        {{-- 4. SECTION LIVE CHAT DENGAN ADMIN & SUPERADMIN --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-12">
            <div class="max-w-4xl mx-auto rounded-2xl bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-800 text-white p-4 sm:p-6 md:p-7 shadow-md border border-blue-700/50 relative overflow-hidden">
                {{-- Decorative background glow --}}
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-cyan-400/15 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6">
                    <div class="space-y-1.5 text-center md:text-left flex-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-white/10 backdrop-blur-md border border-white/20 text-[11px] font-semibold text-cyan-200">
                            <span class="w-2 h-2 bg-emerald-400 inline-block animate-pulse"></span>
                            Super Admin & Admin Operasional Online
                        </div>

                        <h2 class="text-lg sm:text-xl md:text-2xl font-bold tracking-tight leading-tight">
                            Tidak Menemukan Jawaban?
                        </h2>

                        <p class="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-xl font-normal">
                            Hubungi langsung tim support kami melalui fitur <strong>Live Chat Real-Time</strong> bersama Super Admin dan Admin Operasional untuk penyelesaian kendala sewa, iklan, dan transaksi.
                        </p>
                    </div>

                    {{-- CTA KANAN: JIKA GUEST ARAHKAN KE LOGIN, JIKA AUTH BUKA LIVE CHAT --}}
                    <div class="shrink-0 flex flex-col items-center md:items-end gap-2 w-full md:w-auto">
                        @auth
                            <button 
                                type="button" 
                                @click="chatModalOpen = true" 
                                class="w-full md:w-auto px-6 py-3.5 rounded-xl bg-blue-500 hover:bg-blue-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-500/30 transition transform hover:scale-102 active:scale-95 flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Mulai Live Chat Sekarang</span>
                            </button>
                            <p class="text-[11px] text-slate-300">
                                Masuk sebagai: <strong class="text-white">{{ auth()->user()->name }}</strong>
                            </p>
                        @else
                            <a 
                                href="{{ route('login') }}" 
                                class="w-full md:w-auto px-6 py-3.5 rounded-xl bg-blue-500 hover:bg-blue-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-blue-500/30 transition transform hover:scale-102 active:scale-95 flex items-center justify-center gap-2 text-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                <span>Masuk untuk Live Chat</span>
                            </a>
                            <p class="text-[11px] text-slate-300">
                                Fitur Live Chat khusus akun terdaftar
                            </p>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        {{-- 5. MODAL LIVE CHAT INTERAKTIF (KHUSUS PENGGUNA LOGIN) --}}
        @auth
            <div 
                x-show="chatModalOpen" 
                x-transition.opacity 
                class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4" 
                style="display: none;"
            >
                <div 
                    @click.outside="chatModalOpen = false" 
                    class="bg-white rounded-3xl max-w-lg w-full h-[540px] shadow-2xl flex flex-col justify-between overflow-hidden animate-in fade-in zoom-in-95 duration-200 border border-slate-200"
                >
                    {{-- Chat Header --}}
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg font-black shadow-inner">
                                SK
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <span>Live Chat Admin SevenKost</span>
                                    <span class="w-2 h-2 bg-emerald-400 inline-block"></span>
                                </h3>
                                <p class="text-[11px] text-slate-300">Super Admin & Maya Kartika (Admin Verifikator)</p>
                            </div>
                        </div>

                        <button @click="chatModalOpen = false" class="text-slate-300 hover:text-white p-1 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Chat Message Thread --}}
                    <div class="p-4 sm:p-5 flex-1 overflow-y-auto space-y-3.5 bg-slate-50 text-xs">
                        <template x-for="(msg, index) in chatMessages" :key="index">
                            <div :class="msg.sender === 'user' ? 'flex flex-col items-end' : 'flex flex-col items-start'">
                                <div class="flex items-center gap-1.5 mb-1 text-[10px] text-slate-500">
                                    <strong x-text="msg.name" :class="msg.sender === 'user' ? 'text-blue-700' : 'text-slate-800'"></strong>
                                    <span x-text="'• ' + msg.time"></span>
                                </div>
                                <div 
                                    :class="msg.sender === 'user' ? 'bg-blue-600 text-white rounded-2xl rounded-tr-xs' : 'bg-white text-slate-800 border border-slate-200/80 rounded-2xl rounded-tl-xs shadow-xs'" 
                                    class="p-3 max-w-[85%] leading-relaxed"
                                    x-text="msg.text"
                                ></div>
                            </div>
                        </template>
                    </div>

                    {{-- Chat Input Form --}}
                    <div class="p-3.5 sm:p-4 bg-white border-t border-slate-100 space-y-2">
                        {{-- Topik Bantuan --}}
                        <div class="flex items-center gap-2 text-[11px]">
                            <span class="font-bold text-slate-600">Topik:</span>
                            <select x-model="chatTopic" class="py-1 px-2.5 rounded-lg border-slate-200 text-[11px] text-slate-700 focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                                <option value="general">Bantuan Umum</option>
                                <option value="booking">Kendala Sewa & Booking Kos</option>
                                <option value="marketplace">Jual Beli Barang COD</option>
                                <option value="service">Layanan Kamar Kos</option>
                                <option value="ads">Pasang Iklan / Banner</option>
                            </select>
                        </div>

                        <form @submit.prevent="sendChatMessage()" class="flex items-center gap-2">
                            <input 
                                type="text" 
                                x-model="chatMessage" 
                                placeholder="Ketik pertanyaan atau kendala Anda di sini..." 
                                class="flex-1 py-2.5 px-3.5 rounded-xl border-slate-200 text-xs text-slate-800 focus:ring-blue-500 focus:border-blue-500 bg-slate-50"
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 shrink-0"
                            >
                                <span>Kirim</span>
                                <svg class="w-3.5 h-3.5 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

    </div>

</x-seven-layout>

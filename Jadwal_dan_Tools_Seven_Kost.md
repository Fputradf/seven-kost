# Jadwal Pengerjaan dan Tools: Seven Kost (MVP 1 Bulan)

Dikerjakan sendiri (solo) · Periode: Senin 9 Nov – Minggu 6 Des 2026 · Cakupan: seluruh Indonesia · Peta: Leaflet + GeoJSON

> **Asumsi stack:** Laravel 11 + MySQL + Blade/Tailwind/Alpine.js + Filament (panel admin). Dipilih karena paling cepat untuk satu orang, halaman publik dirender di server (bagus untuk SEO, sesuai BR-19), dan panel admin/superadmin bisa dibuat dalam hitungan jam. Jika stack kamu berbeda (mis. Next.js + PostgreSQL), bagian *Tools* tinggal diganti padanannya; jadwal tetap sama.

---

## 1. Cakupan MVP

| Masuk MVP | Ditunda (Fase 2-3) |
|---|---|
| 5 peran: pengunjung, penghuni, pemilik, admin, superadmin | Peran mitra layanan |
| Iklan kos, pencarian + filter + peta, detail kos (BR-01, 02) | Pembayaran sewa online + tagihan otomatis (BR-07) |
| Verifikasi listing + lencana (BR-03) | Laporan keuangan (BR-08) |
| Daftar/masuk + profil (BR-04) | Kuasa admin atas nama pemilik (BR-09) |
| Ajukan sewa + chat sederhana (BR-05) | Ulasan terverifikasi (BR-12) |
| Kelola kamar, penyewa, kontrak (BR-06) | Notifikasi WhatsApp (BR-16) |
| Paket iklan unggulan, dicatat manual oleh admin (BR-10 ringan) | Marketplace barang kos, layanan mitra (BR-18) |
| Banner halaman depan (BR-11) | Iklan berbasis saldo, analitik penuh (BR-17) |
| Moderasi + laporan pelanggaran dasar (BR-13) | |
| RBAC + atur tarif paket (BR-14, 15) | |
| Halaman SEO per wilayah (BR-19) | |

Jika masih ada waktu di Minggu 4, tambahkan ulasan atau marketplace versi paling sederhana. Jangan dimasukkan ke rencana inti.

---

## 2. Jadwal 4 Minggu

Aturan kerja: hari 1-6 tiap minggu adalah hari kerja, **hari 7 adalah cadangan** (pengganti hari yang molor).

### Minggu 1 (9-15 Nov): Fondasi, data wilayah, sisi pencari kos

| Hari | Tugas | Hasil |
|---|---|---|
| 1 | Setup project Laravel, Git, database, Tailwind, Filament; desain skema database | Project berjalan di lokal |
| 2 | Matriks Role × Fitur, autentikasi (daftar/masuk/lupa sandi), RBAC 5 peran | Login per peran berfungsi |
| 3 | Data wilayah: impor provinsi + kabupaten/kota ke database; siapkan file GeoJSON | Tabel wilayah terisi |
| 4 | Design system navy gradient (komponen: tombol, kartu, badge, navbar, footer) | Komponen UI siap pakai |
| 5 | Beranda (hero + pencarian + kos unggulan + slot banner) | Beranda tampil |
| 6 | Halaman hasil pencarian + filter (wilayah, harga, tipe, fasilitas) | Daftar kos terfilter |
| 7 | Cadangan | |

### Minggu 2 (16-22 Nov): Sisi pemilik kos dan peta

| Hari | Tugas | Hasil |
|---|---|---|
| 1 | Form buat/ubah iklan: info dasar, harga, fasilitas, aturan | Iklan tersimpan |
| 2 | Unggah foto (kompres otomatis) + pin lokasi di Leaflet + penentuan wilayah | Iklan lengkap dengan lokasi |
| 3 | Dashboard pemilik + status iklan (draft, menunggu, tayang, ditolak) | Alur status berjalan |
| 4 | Detail kos (galeri, peta, fasilitas, daftar kamar) | Halaman detail |
| 5 | Peta pencarian: marker per area terlihat, cluster, poligon wilayah terpilih | Peta di hasil pencarian |
| 6 | Kelola kamar, penyewa, kontrak | CRUD kamar dan penyewa |
| 7 | Cadangan | |

### Minggu 3 (23-29 Nov): Interaksi, admin, superadmin, monetisasi

| Hari | Tugas | Hasil |
|---|---|---|
| 1 | Ajukan sewa (penghuni) + terima/tolak (pemilik) + profil penghuni | Alur booking dasar |
| 2 | Chat sederhana (pesan tersimpan di database) atau tombol WhatsApp | Komunikasi pemilik-penghuni |
| 3 | Panel admin: antrean verifikasi, lencana terverifikasi, tolak dengan alasan | Verifikasi listing |
| 4 | Moderasi + laporan pelanggaran (tombol lapor di iklan) | Alur laporan |
| 5 | Paket iklan unggulan: halaman beli, pencatatan oleh admin, urutan tampil; atur tarif oleh superadmin | Monetisasi dasar |
| 6 | Banner halaman depan (unggah, jadwal, slot per wilayah) + RBAC/kelola admin + jejak audit | Panel superadmin |
| 7 | Cadangan | |

### Minggu 4 (30 Nov - 6 Des): SEO, rapikan, uji, rilis

| Hari | Tugas | Hasil |
|---|---|---|
| 1 | Halaman SEO per wilayah (`/kos/{provinsi}/{kota}`), sitemap, data terstruktur | Halaman otomatis semua wilayah |
| 2 | Responsif mobile, loading state, validasi, pesan "belum ada kos di wilayah ini" | UI mulus di ponsel |
| 3 | Uji alur ujung ke ujung dengan data dummy ribuan kos (performa peta + pencarian) | Daftar bug dan perbaikan |
| 4 | Keamanan dasar + persetujuan data pribadi (UU PDP) + batasi akses dokumen identitas | Checklist keamanan |
| 5 | Isi data nyata 10-20 kos di 1-3 wilayah, deploy produksi | Situs live |
| 6 | Perbaiki bug, dokumentasi singkat, siapkan demo | Siap demo |
| 7 | Cadangan | |

**Aturan potong jika molor:** (1) chat dalam aplikasi diganti tombol WhatsApp, (2) kelola kontrak disederhanakan, (3) banner dibuat satu slot saja, (4) jejak audit hanya untuk aksi admin penting.

---

## 3. Fitur dan Tools

### 3.1 Autentikasi dan Peran (BR-04, BR-14)
- **Fungsi:** daftar, masuk, lupa sandi, verifikasi email/OTP; 5 peran dengan hak akses berbeda.
- **Tools:** Laravel Breeze atau Fortify (autentikasi), `spatie/laravel-permission` (peran dan izin), Mailpit (uji email lokal), Resend atau SMTP (email produksi).
- **Catatan:** kata sandi di-hash bawaan Laravel (bcrypt/argon2).

### 3.2 Data Wilayah dan Peta (BR-01, BR-02, BR-19)
- **Fungsi:** pemilih wilayah bertingkat (provinsi → kabupaten/kota), pin lokasi kos, tampilan poligon wilayah, peta pencarian.
- **Tools:**
  - **Leaflet** (peta), **Leaflet.markercluster** (pengelompokan marker), **Turf.js** (cek apakah titik pin berada di dalam poligon wilayah).
  - **GeoJSON** per provinsi dan per kabupaten/kota, disimpan sebagai file statis (mis. `/public/geo/provinsi/{kode}.geojson`) dan dimuat hanya saat wilayah dipilih.
  - **Mapshaper** untuk menyederhanakan geometri agar file kecil.
  - **Tile peta:** OpenStreetMap untuk pengembangan; untuk produksi gunakan penyedia berlisensi (mis. MapTiler, Stadia) atau host sendiri.
  - **Nominatim** (pencarian alamat) hanya untuk volume rendah; patuhi kebijakan penggunaannya.
- **Cara kerja:**
  1. Tabel `provinsi` dan `kota` (kode resmi, nama, pusat koordinat).
  2. Pemilik memilih provinsi dan kota, lalu menaruh pin. Turf.js memastikan pin berada di dalam poligon kota yang dipilih.
  3. Kos menyimpan `lat`, `lng`, `provinsi_id`, `kota_id`.
  4. Pencarian memakai ID wilayah, bukan menggambar poligon di sisi klien.
- **Level kecamatan:** ditunda (jumlahnya ribuan, file besar). Cukup sampai kabupaten/kota untuk MVP.

> **Tentang data wilayah dan poligon.** Asisten AI (termasuk Claude) bisa menulis skrip impor dan membantu merapikan data, tetapi **tidak boleh dijadikan sumber koordinat poligon dari ingatan**: bentuk batas yang dihasilkan AI tidak akurat. Ambil dari sumber nyata (mis. data kode wilayah BPS/Kemendagri, geoBoundaries, GADM, atau repositori GeoJSON Indonesia sumber terbuka), **periksa lisensinya**, lalu minta AI membuat skrip yang mengunduh, menyederhanakan, dan memasukkannya ke database. Di lingkungan chat ini saya tidak bisa mengunduh file itu langsung, jadi skripnya dijalankan di komputermu.

### 3.3 Iklan Kos oleh Pemilik (BR-01)
- **Fungsi:** buat dan ubah iklan: foto, tipe (putra/putri/campur), harga, fasilitas, aturan, lokasi, ketersediaan kamar; status draft hingga tayang.
- **Tools:** Laravel Form Request (validasi), `spatie/laravel-medialibrary` + Intervention Image (unggah dan kompres foto), penyimpanan lokal atau Cloudflare R2/S3, Alpine.js (formulir bertahap).

### 3.4 Pencarian dan Filter Nasional (BR-02)
- **Fungsi:** cari berdasarkan wilayah, harga, fasilitas, tipe; tampilan daftar dan peta; urutan: unggulan, terdekat, termurah, terbaru.
- **Tools:** Eloquent + indeks database pada `kota_id`, `harga`, `tipe`, `status`; paginasi; Livewire atau Alpine.js untuk filter tanpa muat ulang halaman.
- **Upgrade nanti:** Meilisearch jika data sudah puluhan ribu.

### 3.5 Verifikasi Listing dan Lencana (BR-03)
- **Fungsi:** antrean verifikasi, setujui (beri lencana), tolak dengan alasan.
- **Tools:** **Filament v3** (resource, filter, aksi massal), notifikasi email ke pemilik lewat Laravel Notification.

### 3.6 Booking dan Chat (BR-05)
- **Fungsi:** penghuni mengajukan sewa; pemilik menerima atau menolak; komunikasi.
- **Tools:** tabel `booking` + `pesan`, polling sederhana atau Livewire; alternatif cepat: tombol `wa.me` ke nomor pemilik. Upgrade: Laravel Reverb (websocket).

### 3.7 Kelola Kos: Kamar, Penyewa, Kontrak (BR-06)
- **Fungsi:** data kamar, status hunian, penyewa, kontrak (tanggal mulai-akhir).
- **Tools:** Eloquent relasi (`kos → kamar → sewa`), Livewire atau halaman Blade biasa, ekspor PDF kontrak dengan `barryvdh/laravel-dompdf` (jika sempat).

### 3.8 Paket Iklan Unggulan (BR-10 ringan, BR-15)
- **Fungsi:** pemilik memilih paket, admin mencatat pembayaran manual, iklan tampil di atas dan berlabel; superadmin mengatur tarif tanpa ubah kode.
- **Tools:** tabel `paket` dan `langganan_iklan` dengan tanggal mulai-akhir; penjadwal Laravel (Scheduler) untuk menonaktifkan paket yang kedaluwarsa; Filament untuk atur tarif.
- **Ditunda:** payment gateway (Xendit atau lainnya) dan saldo iklan per klik.

### 3.9 Banner Halaman Depan (BR-11)
- **Fungsi:** unggah banner, jadwal tayang, slot per wilayah atau nasional, hitung klik.
- **Tools:** Filament (resource banner), tabel `banner_klik` + redirect pelacak, Swiper.js (carousel).

### 3.10 Moderasi dan Laporan (BR-13)
- **Fungsi:** pengguna melaporkan iklan; admin meninjau, menghapus, memperingatkan, atau memblokir.
- **Tools:** Filament, tabel `laporan` dengan status dan tenggat 3 hari kerja.

### 3.11 Superadmin: Pengguna, Hak Akses, Audit (BR-14)
- **Fungsi:** buat admin, atur hak akses, jejak audit aksi admin dan superadmin.
- **Tools:** Filament + `spatie/laravel-permission`, `spatie/laravel-activitylog`.

### 3.12 SEO Halaman Wilayah (BR-19)
- **Fungsi:** halaman otomatis `/kos/{provinsi}/{kota}` dengan judul, deskripsi, daftar kos, FAQ, dan data terstruktur.
- **Tools:** render server (Blade), `spatie/laravel-sitemap`, JSON-LD manual, cache halaman (Laravel Cache).
- **Catatan:** halaman wilayah tanpa kos diberi `noindex` sampai ada isi, agar tidak dianggap konten tipis.

### 3.13 Keamanan, Privasi, dan Deploy (Non-fungsional)
- **Fungsi:** perlindungan data pribadi (UU PDP), batasi akses dokumen identitas, pencadangan, pemantauan galat.
- **Tools:** Laravel (CSRF, validasi, rate limiting), penyimpanan privat untuk dokumen, Sentry atau Laravel Telescope (galat), cadangan harian (`spatie/laravel-backup`), VPS + Nginx (atau Laravel Forge), Cloudflare (CDN + SSL).

### 3.14 Pengujian
- **Tools:** Pest (uji fitur untuk alur kritis: login per peran, buat iklan, verifikasi, ajukan sewa), data dummy dengan Laravel Factory/Seeder (ribuan kos) untuk uji performa peta dan pencarian.

---

## 4. Alat Bantu Pengembangan (Opsional)

| Kebutuhan | Tools |
|---|---|
| Desain halaman dari prompt | Figma AI / v0 / Lovable (pakai prompt desain yang sudah dibuat) |
| Asisten pemrograman | Claude Code, GitHub Copilot |
| Manajemen tugas | GitHub Projects atau Trello (papan: Todo, Doing, Done) |
| Tampilan GeoJSON saat debug | geojson.io, QGIS |

---

## 5. Daftar Pemeriksaan Rilis

- [ ] Semua peran bisa masuk dan hanya melihat menu miliknya
- [ ] Pemilik bisa membuat iklan, admin memverifikasi, kos tampil di pencarian
- [ ] Pin lokasi terdeteksi di wilayah yang benar
- [ ] Peta tidak lambat dengan ribuan marker (cluster aktif)
- [ ] Halaman pencarian < 3 detik di 4G
- [ ] Persetujuan data pribadi dan kebijakan privasi tampil
- [ ] Cadangan harian berjalan
- [ ] Data nyata terisi di 1-3 wilayah
- [ ] Lisensi data wilayah dan tile peta sudah dicek

## 6. Pertanyaan Terbuka yang Masih Memengaruhi Rancangan

1. Marketplace (fase berikutnya): terbuka untuk umum atau hanya akun terverifikasi?
2. Pembayaran sewa online (fase berikutnya): wajib atau pilihan bagi pemilik?
3. Kuasa admin atas nama pemilik: cukup persetujuan di sistem atau perlu kuasa tertulis?
4. Kajian hukum (PSE, pajak, NIB) dijalankan paralel dan tidak masuk jadwal 1 bulan ini.

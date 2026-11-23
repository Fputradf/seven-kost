<x-partner-layout title="Rincian Pesanan #{{ $transaction->reference_id }}">
    @php
        $orderStatus = $payload['service_order_status'] ?? ($transaction->status === 'paid' ? 'confirmed' : 'pending');
        $isCancelled = in_array($orderStatus, ['cancelled', 'rejected']) || $transaction->status === 'failed';
        $isCompleted = $orderStatus === 'completed';
        $fulfillmentType = $payload['fulfillment_type'] ?? 'delivery';
        $isSelfService = $fulfillmentType === 'self_service';
    @endphp

    <div class="space-y-6 max-w-5xl">

        {{-- Top Back Navigation & Action Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('partner.orders.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Daftar Pesanan</span>
            </a>

            <div class="flex items-center gap-2">
                @if ($isSelfService)
                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        🧺 Mandiri (Antar & Ambil Sendiri)
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        🛵 Kurir Antar-Jemput
                    </span>
                @endif
                <span class="text-xs text-slate-400">ID Transaksi:</span>
                <span class="text-xs font-mono font-bold text-blue-900 bg-blue-50 border border-blue-200 px-3 py-1 rounded-xl">
                    #{{ $transaction->reference_id }}
                </span>
            </div>
        </div>

        {{-- 1. ORDER LIFECYCLE STEPPER --}}
        @php
            $isStep1Done = in_array($orderStatus, ['confirmed', 'on_the_way', 'in_progress', 'ready_for_pickup', 'completed']);
            $isStep2Active = $orderStatus === 'confirmed';
            $isStep2Done = in_array($orderStatus, ['on_the_way', 'in_progress', 'ready_for_pickup', 'completed']);
            $isStep3Active = in_array($orderStatus, ['on_the_way', 'in_progress']);
            $isStep3Done = in_array($orderStatus, ['ready_for_pickup', 'completed']);
            $isStep4Active = $orderStatus === 'ready_for_pickup';
            $isStep4Done = $orderStatus === 'completed';
        @endphp

        <div class="rounded-3xl bg-white border border-slate-200/80 p-6 sm:p-8 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Status Tahapan Pesanan</h3>
                <span class="text-xs font-bold {{ $isSelfService ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-blue-700 bg-blue-50 border border-blue-200' }} px-2.5 py-0.5 rounded-full">
                    {{ $isSelfService ? 'Alur Mandiri di Outlet Toko' : 'Alur Antar-Jemput Kurir' }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
                
                {{-- Step 1: Dikonfirmasi --}}
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center font-black text-sm flex-shrink-0 {{ $isStep1Done ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-200 text-slate-800' }}">
                        {{ $isStep1Done ? '✓' : '1' }}
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-xs font-black text-slate-900 block">Diterima Mitra</span>
                        <span class="text-[11px] font-bold text-slate-600 block">
                            {{ !empty($payload['confirmed_at']) ? \Carbon\Carbon::parse($payload['confirmed_at'])->format('H:i') . ' WIB' : 'Menunggu' }}
                        </span>
                    </div>
                </div>

                {{-- Step 2: Penugasan Kurir ATAU Pengantaran ke Toko --}}
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center font-black text-sm flex-shrink-0 {{ $isStep2Done ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : ($isStep2Active ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 shadow-md animate-pulse' : 'bg-slate-200 text-slate-800') }}">
                        {{ $isStep2Done ? '✓' : '2' }}
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-xs font-black {{ $isStep2Active ? 'text-indigo-900' : 'text-slate-900' }} block">
                            {{ $isSelfService ? 'Diantar ke Toko' : 'Penugasan Kurir' }}
                        </span>
                        <span class="text-[11px] font-bold {{ $isStep2Active ? 'text-indigo-600' : 'text-slate-600' }} block">
                            @if ($isStep2Done)
                                {{ $isSelfService ? 'Telah Diterima Toko' : (!empty($payload['dispatched_at']) ? \Carbon\Carbon::parse($payload['dispatched_at'])->format('H:i') . ' WIB' : 'Telah Ditugaskan') }}
                            @elseif ($isStep2Active)
                                {{ $isSelfService ? 'Tunggu Penghuni Antar' : 'Langkah Saat Ini' }}
                            @else
                                Menunggu Tahap 1
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Step 3: Sedang Dikerjakan --}}
                <div class="flex items-start gap-3">
                    <div style="{{ $isStep3Done || $isStep3Active ? 'background-color: #2563eb !important; color: #ffffff !important;' : '' }}" class="h-10 w-10 rounded-2xl flex items-center justify-center font-black text-sm flex-shrink-0 {{ $isStep3Done ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : ($isStep3Active ? 'bg-blue-600 text-white ring-4 ring-blue-100 shadow-md animate-pulse' : 'bg-slate-200 text-slate-800') }}">
                        {{ $isStep3Done ? '✓' : '3' }}
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-xs font-black {{ $isStep3Active ? 'text-blue-900' : 'text-slate-900' }} block">
                            Proses Pengerjaan
                        </span>
                        <span class="text-[11px] font-bold {{ $isStep3Active ? 'text-blue-600' : 'text-slate-600' }} block">
                            @if ($isStep3Done)
                                {{ !empty($payload['ready_at']) ? \Carbon\Carbon::parse($payload['ready_at'])->format('H:i') . ' WIB' : 'Selesai Dicuci' }}
                            @elseif ($isStep3Active)
                                Sedang Dikerjakan
                            @else
                                {{ $isSelfService ? 'Menunggu Cucian Tiba' : 'Menunggu Kurir' }}
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Step 4: Siap Diambil / Selesai --}}
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center font-black text-sm flex-shrink-0 {{ $isStep4Done ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : ($isStep4Active ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 shadow-md animate-pulse' : 'bg-slate-200 text-slate-800') }}">
                        {{ $isStep4Done ? '✓' : '4' }}
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-xs font-black {{ $isStep4Active ? 'text-emerald-900' : 'text-slate-900' }} block">
                            {{ $isSelfService ? 'Ambil di Toko & Selesai' : 'Selesai & Lunas' }}
                        </span>
                        <span class="text-[11px] font-bold {{ $isStep4Active ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }} block">
                            @if ($isStep4Done)
                                {{ !empty($payload['completed_at']) ? \Carbon\Carbon::parse($payload['completed_at'])->format('H:i') . ' WIB' : 'Telah Selesai' }}
                            @elseif ($isStep4Active)
                                Siap Diambil Penghuni
                            @else
                                Tahap Akhir
                            @endif
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- 2. ACTION CONTROLS PANEL SESUAI STATUS --}}
        @if ($isCancelled)
            <div class="rounded-3xl bg-rose-50 border border-rose-200 p-6 space-y-2">
                <div class="flex items-center gap-2 text-rose-800 font-bold text-base">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Pesanan Dibatalkan / Ditolak</span>
                </div>
                <p class="text-xs text-rose-700">
                    Alasan: <strong>{{ $payload['cancel_reason'] ?? ($payload['reject_reason'] ?? 'Pesanan dibatalkan.') }}</strong>
                </p>
            </div>
        @elseif ($isCompleted)
            <div class="rounded-3xl bg-emerald-50 border border-emerald-200 p-6 space-y-3">
                <div class="flex items-center gap-2 text-emerald-800 font-bold text-base">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Pesanan Telah Berhasil Diselesaikan!</span>
                </div>
                <p class="text-xs text-emerald-700">
                    Dana sebesar <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong> telah masuk ke Saldo Dompet Mitra Anda.
                </p>
                @if (!empty($payload['proof_image']))
                    <div class="pt-2">
                        <span class="text-xs font-bold text-emerald-900 block mb-2">Foto Bukti Pengerjaan:</span>
                        <a href="{{ asset('storage/' . $payload['proof_image']) }}" target="_blank" class="inline-block">
                            <img src="{{ asset('storage/' . $payload['proof_image']) }}" alt="Bukti Pengerjaan" class="h-36 rounded-2xl object-cover border border-emerald-300 shadow-xs hover:scale-105 transition-transform">
                        </a>
                    </div>
                @endif
            </div>
        @elseif ($orderStatus === 'pending')
            <div class="rounded-3xl bg-amber-50/80 border border-amber-200 p-6 sm:p-8 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-base font-bold text-amber-950">Pesanan Baru Menunggu Respon Mitra</h4>
                        <p class="text-xs text-amber-800 mt-1">Konfirmasi pesanan ini untuk memulai proses penyiapan dan penugasan kurir/petugas.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('partner.orders.accept', $transaction->reference_id) }}">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-500/20 transition cursor-pointer">
                                ✅ Terima Pesanan Ini
                            </button>
                        </form>

                        <button 
                            type="button" 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 transition cursor-pointer"
                        >
                            Tolak Pesanan
                        </button>
                    </div>
                </div>
            </div>
        @elseif ($orderStatus === 'confirmed')
            @if ($isSelfService)
                {{-- Mode Mandiri / Self-Service: Menunggu Penghuni Datang Mengantar ke Toko --}}
                <div class="rounded-3xl bg-emerald-50/90 border-2 border-emerald-300 p-6 sm:p-8 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-black text-emerald-800 uppercase tracking-wider block">Langkah 2: Penerimaan di Toko Mitra</span>
                            <h4 class="text-base font-black text-emerald-950">🧺 Pesanan Mandiri (Antar & Ambil Sendiri ke Toko)</h4>
                            <p class="text-xs font-semibold text-emerald-800 leading-relaxed max-w-2xl">
                                Penghuni kos memilih mengantarkan cucian/barang secara langsung ke toko Anda (Bebas Biaya Ongkir). Tidak memerlukan kurir antar-jemput. Setelah penghuni tiba dan menyerahkan cucian di outlet, klik tombol di samping untuk mengonfirmasi penerimaan barang dan mulai mencuci.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('partner.orders.start-work', $transaction->reference_id) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-5 py-3 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition cursor-pointer flex items-center justify-center gap-2">
                                <span>Cucian Tiba &amp; Mulai Pengerjaan</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Form Penugasan Kurir untuk Pengiriman Reguler --}}
                <div class="rounded-3xl bg-white border-2 border-indigo-300 p-6 sm:p-8 shadow-sm space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xl">
                            🛵
                        </div>
                        <div>
                            <span class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider block">Langkah 2: Penugasan Petugas</span>
                            <h4 class="text-base font-extrabold text-slate-900">Tugaskan Kurir / Petugas Pelaksana</h4>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-slate-700 leading-relaxed">
                        Masukkan nama dan kontak petugas yang akan meluncur ke kamar kos penghuni. Informasi ini akan langsung terhubung pada pelacakan langsung penghuni.
                    </p>

                    <form method="POST" action="{{ route('partner.orders.assign-courier', $transaction->reference_id) }}" class="space-y-4 pt-2">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-extrabold text-slate-900 mb-1.5">Nama Petugas / Kurir *</label>
                                <input 
                                    type="text" 
                                    name="courier_name" 
                                    value="{{ old('courier_name', $payload['courier_name'] ?? '') }}"
                                    placeholder="Misal: Agus Setiawan" 
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 placeholder-slate-400 text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-slate-900 mb-1.5">No. WhatsApp Petugas *</label>
                                <input 
                                    type="text" 
                                    name="courier_phone" 
                                    value="{{ old('courier_phone', $payload['courier_phone'] ?? ($partner->phone ?? '')) }}"
                                    placeholder="Misal: 081234567890" 
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 placeholder-slate-400 text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-slate-900 mb-1.5">Kendaraan / Plat Nomor</label>
                                <input 
                                    type="text" 
                                    name="vehicle_info" 
                                    value="{{ old('vehicle_info', $payload['vehicle_info'] ?? '') }}"
                                    placeholder="Misal: Honda Vario • B 4921 TZL" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 placeholder-slate-400 text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition shadow-2xs"
                                >
                            </div>
                        </div>

                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20 transition cursor-pointer">
                            Konfirmasi Penugasan & Kirim Petugas Menuju Lokasi
                        </button>
                    </form>
                </div>
            @endif
        @elseif ($orderStatus === 'on_the_way')
            <div class="rounded-3xl bg-blue-50/80 border border-blue-200 p-6 sm:p-8 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider block">Kurir Sedang Dalam Perjalanan</span>
                        <h4 class="text-base font-bold text-slate-900 mt-1">Petugas: {{ $payload['courier_name'] ?? '-' }} ({{ $payload['courier_phone'] ?? '-' }})</h4>
                        <p class="text-xs text-slate-600 mt-0.5">Kendaraan: {{ $payload['vehicle_info'] ?? 'Motor Operasional' }}</p>
                    </div>

                    <form method="POST" action="{{ route('partner.orders.start-work', $transaction->reference_id) }}">
                        @csrf
                        <button type="submit" style="background-color: #2563eb !important; color: #ffffff !important;" class="px-5 py-2.5 rounded-xl text-xs font-extrabold bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/25 transition cursor-pointer flex items-center justify-center gap-1.5">
                            <span>Petugas Tiba &amp; Mulai Pengerjaan</span>
                        </button>
                    </form>
                </div>
            </div>
        @elseif ($orderStatus === 'in_progress')
            <div class="rounded-3xl bg-white border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider block">
                            {{ $isSelfService ? 'Pengerjaan di Outlet Toko' : 'Pengerjaan Layanan' }}
                        </span>
                        <h4 class="text-base font-bold text-slate-900 mt-1">
                            {{ $isSelfService ? 'Proses Cucian di Toko' : 'Selesaikan Pesanan & Unggah Foto Bukti' }}
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $isSelfService ? 'Setelah cucian selesai dicuci dan dilipat, tandai siap diambil agar penghuni mendapat notifikasi untuk datang ke toko, atau langsung konfirmasi serah terima di bawah.' : 'Setelah pekerjaan selesai atau cucian diantar, unggah foto bukti pengerjaan untuk mengonfirmasi penyelesaian ke penghuni.' }}
                        </p>
                    </div>

                    @if ($isSelfService)
                        <form method="POST" action="{{ route('partner.orders.mark-ready', $transaction->reference_id) }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20 transition cursor-pointer flex items-center gap-1.5">
                                <span>Cucian Selesai &amp; Siap Diambil</span>
                            </button>
                        </form>
                    @endif
                </div>

                <form method="POST" action="{{ route('partner.orders.complete', $transaction->reference_id) }}" enctype="multipart/form-data" class="space-y-4 pt-3 border-t border-slate-100">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Foto Bukti Pengerjaan / Nota (Opsional)</label>
                            <input 
                                type="file" 
                                name="proof_image" 
                                accept="image/*"
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition"
                            >
                            <span class="text-[11px] text-slate-400 block mt-1">Format: JPG, PNG, WEBP (Maks 5 MB)</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Penyelesaian</label>
                            <input 
                                type="text" 
                                name="completion_notes" 
                                placeholder="{{ $isSelfService ? 'Misal: Cucian wangi 4kg siap di rak pickup' : 'Misal: Cucian telah ditaruh di meja kamar kos sesuai instruksi' }}" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                            >
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition cursor-pointer">
                        ✅ Selesaikan Pesanan &amp; Masukkan Dana ke Dompet
                    </button>
                </form>
            </div>
        @elseif ($orderStatus === 'ready_for_pickup')
            {{-- Panel Cucian Siap Diambil di Outlet Toko --}}
            <div class="rounded-3xl bg-amber-50/90 border-2 border-amber-300 p-6 sm:p-8 shadow-sm space-y-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-600 text-white uppercase">Menunggu Pengambilan</span>
                        <span class="text-xs font-extrabold text-amber-800">Cucian Siap di Outlet</span>
                    </div>
                    <h4 class="text-base font-black text-amber-950 mt-1">Cucian Sudah Selesai &amp; Menunggu Penghuni Datang Mengambil</h4>
                    <p class="text-xs font-semibold text-amber-900 mt-1">Penghuni telah diberitahukan bahwa cuciannya sudah selesai dan siap diambil di outlet toko Anda. Setelah penghuni datang dan mengambil cuciannya, konfirmasi serah terima di bawah untuk mencairkan dana pendapatan.</p>
                </div>

                <form method="POST" action="{{ route('partner.orders.complete', $transaction->reference_id) }}" enctype="multipart/form-data" class="space-y-4 pt-3 border-t border-amber-200/80">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Foto Bukti Serah Terima / Nota (Opsional)</label>
                            <input 
                                type="file" 
                                name="proof_image" 
                                accept="image/*"
                                class="w-full px-3 py-2 rounded-xl border border-amber-300 bg-white text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 transition"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Catatan Serah Terima</label>
                            <input 
                                type="text" 
                                name="completion_notes" 
                                placeholder="Misal: Diambil langsung oleh penghuni kamar kos" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-amber-300 bg-white text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition"
                            >
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-500 text-white shadow-md shadow-emerald-600/20 transition cursor-pointer">
                        ✅ Konfirmasi Serah Terima Selesai &amp; Masukkan Dana ke Dompet
                    </button>
                </form>
            </div>
        @endif

        {{-- 3. INFORMASI DETAIL PEMESAN & PESANAN --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Data Pemesan & Hunian --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 p-6 space-y-4 shadow-xs">
                <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Pemesan & Kamar</h4>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block">Nama Penghuni:</span>
                        <span class="text-sm font-bold text-slate-800">{{ $payload['occupant_name'] ?? 'Penghuni Kos' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Nomor WhatsApp:</span>
                        <span class="font-bold text-slate-800">{{ $payload['occupant_phone'] ?? '-' }}</span>
                        @if (!empty($payload['occupant_phone']))
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $payload['occupant_phone']);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-emerald-600 font-bold ml-2 hover:underline">
                                Hubungi WA &rarr;
                            </a>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-400 block">Nomor Kamar:</span>
                        <span class="font-extrabold text-blue-900 bg-blue-50 px-2 py-0.5 rounded">{{ $payload['room_number'] ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block">Alamat Kos:</span>
                        <span class="text-slate-700 leading-relaxed">{{ $payload['kost_address'] ?? '-' }}</span>
                    </div>

                    @if (!empty($payload['order_notes']))
                        <div class="rounded-2xl bg-amber-50 p-3 border border-amber-200 text-amber-900">
                            <strong>Catatan Khusus:</strong><br>
                            {{ $payload['order_notes'] }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rincian Biaya & Pembayaran --}}
            <div class="rounded-3xl bg-white border border-slate-200/80 p-6 space-y-4 shadow-xs">
                <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Rincian Paket & Pembayaran</h4>
                
                <div class="space-y-2.5 text-xs text-slate-600">
                    <div class="flex items-center justify-between">
                        <span>Paket Layanan:</span>
                        <span class="font-bold text-slate-900">{{ $payload['package_name'] ?? $transaction->title }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>Kuantitas:</span>
                        <span class="font-bold text-slate-900">{{ $payload['quantity'] ?? 1 }} {{ $payload['package_unit'] ?? 'unit' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>Subtotal Paket:</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($payload['subtotal'] ?? $transaction->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span>Ongkos Antar-Jemput ({{ $payload['distance_km'] ?? '0' }} km):</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($payload['delivery_fee'] ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-sm font-black text-slate-900">
                        <span>Total Tagihan:</span>
                        <span class="text-blue-900">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Metode Pembayaran:</span>
                        <span class="font-bold uppercase text-slate-700">{{ $transaction->payment_channel ?? 'Online' }}</span>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Status Pembayaran:</span>
                        <span class="font-bold uppercase text-emerald-600">{{ $transaction->status }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- MODAL TOLAK PESANAN --}}
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 class="text-base font-bold text-slate-900">Tolak Pesanan Ini?</h3>
            <p class="text-xs text-slate-500">Jika ditolak, dana pembayaran anak kos akan otomatis dikembalikan (refund 100%) ke saldo dompet mereka.</p>

            <form method="POST" action="{{ route('partner.orders.reject', $transaction->reference_id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan *</label>
                    <textarea 
                        name="reject_reason" 
                        rows="3" 
                        required
                        placeholder="Misal: Kapasitas kuota harian laundry penuh, atau teknisi sedang ada panggilan mendesak."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button 
                        type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-500 text-white shadow-xs transition"
                    >
                        Konfirmasi Tolak & Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-partner-layout>

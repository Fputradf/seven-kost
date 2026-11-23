<x-partner-layout title="Dasbor Operasional Mitra">
    <div class="space-y-6">

        {{-- 1. HERO BANNER PARTNER WITH BACKGROUND BANNER IMAGE --}}
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-5 text-white shadow-md border border-blue-400/25">
            <img src="{{ asset('images/frontend/banner-jakarta-wide.jpg') }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(12, 43, 84, 0.92) 0%, rgba(20, 70, 133, 0.85) 60%, rgba(30, 91, 168, 0.78) 100%);"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/10 text-white border border-white/20 backdrop-blur-xs">
                        <span>Mitra Resmi SevenKost</span>
                        <span>•</span>
                        <span>{{ $partner->category_label }}</span>
                    </div>
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                        Selamat Datang, {{ $partner->business_name }}
                    </h1>
                </div>

                {{-- Status Toko Badge & Action --}}
                <div class="flex items-center gap-2.5">
                    <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full {{ $partner->is_open ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                        <span class="text-xs font-semibold text-white">
                            {{ $partner->is_open ? 'Menerima Pesanan' : 'Toko Tutup / Libur' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('partner.profile.toggle-status') }}">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="px-3.5 py-1.5 rounded-xl font-semibold text-xs transition-all cursor-pointer {{ $partner->is_open ? 'bg-white/15 hover:bg-white/25 text-white border border-white/25' : 'bg-emerald-500 hover:bg-emerald-400 text-white shadow-xs' }}"
                        >
                            {{ $partner->is_open ? 'Tutup Sementara' : 'Buka Toko' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. STATISTIC METRIC CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            {{-- Card 1: Menunggu Konfirmasi --}}
            <a href="{{ route('partner.orders.index', ['tab' => 'pending']) }}" class="group block rounded-2xl bg-white p-5 border border-slate-200 shadow-xs hover:border-blue-300 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Butuh Konfirmasi</span>
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $pendingCount }}</span>
                    <span class="text-xs text-blue-600 font-bold">Pesanan Baru</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1 group-hover:text-blue-600 transition-colors">
                    <span>Lihat antrean masuk</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </p>
            </a>

            {{-- Card 2: Sedang Diproses --}}
            <a href="{{ route('partner.orders.index', ['tab' => 'processing']) }}" class="group block rounded-2xl bg-white p-5 border border-slate-200 shadow-xs hover:border-indigo-300 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sedang Berjalan</span>
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $processingCount }}</span>
                    <span class="text-xs text-indigo-600 font-bold">Aktif / OTW</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1 group-hover:text-indigo-600 transition-colors">
                    <span>Pantau proses pengerjaan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </p>
            </a>

            {{-- Card 3: Selesai Hari Ini --}}
            <a href="{{ route('partner.orders.index', ['tab' => 'completed']) }}" class="group block rounded-2xl bg-white p-5 border border-slate-200 shadow-xs hover:border-emerald-300 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Selesai Hari Ini</span>
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $completedTodayCount }}</span>
                    <span class="text-xs text-emerald-600 font-bold">Terselesaikan</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1 group-hover:text-emerald-600 transition-colors">
                    <span>Riwayat pengerjaan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </p>
            </a>

            {{-- Card 4: Saldo Dompet Mitra --}}
            <a href="{{ route('partner.finances.index') }}" class="group block rounded-2xl bg-white p-5 border border-slate-200 shadow-xs hover:border-sky-300 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Saldo Dompet Mitra</span>
                    <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-extrabold text-slate-900">
                        Rp {{ number_format((float) auth()->user()->wallet_balance, 0, ',', '.') }}
                    </span>
                </div>
                <p class="text-xs text-sky-600 font-bold mt-2 flex items-center gap-1">
                    <span>Ajukan Tarik Saldo</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </p>
            </a>

        </div>

        {{-- 3. PESANAN MENDESAK & BUTUH TINDAKAN --}}
        <div class="rounded-3xl bg-white border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-lg font-bold text-slate-900">Pesanan Masuk & Perlu Tindakan</h2>
                <a href="{{ route('partner.orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1.5 self-start sm:self-auto">
                    <span>Lihat Seluruh Pesanan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($urgentOrders->isEmpty())
                <div class="p-12 text-center">
                    <div class="h-14 w-14 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Semua Pesanan Terkendali</h3>
                </div>
            @else
                <div class="divide-y divide-slate-100 overflow-x-auto">
                    @foreach ($urgentOrders as $order)
                        @php
                            $payload = $order->payload ?? [];
                            $orderStatus = $payload['service_order_status'] ?? ($order->status === 'paid' ? 'confirmed' : 'pending');
                            $isSelfService = ($payload['fulfillment_type'] ?? 'delivery') === 'self_service';
                        @endphp
                        <div class="p-5 sm:p-6 hover:bg-slate-50/80 transition-colors flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-extrabold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                        #{{ $order->reference_id }}
                                    </span>

                                    @if ($isSelfService)
                                        <span class="text-xs font-black text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg">
                                            🧺 Mandiri (Antar Sendiri)
                                        </span>
                                    @else
                                        <span class="text-xs font-black text-blue-800 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-lg">
                                            🛵 Kurir Antar-Jemput
                                        </span>
                                    @endif

                                    @if ($orderStatus === 'pending')
                                        <span class="text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                            Menunggu Konfirmasi
                                        </span>
                                    @elseif ($orderStatus === 'confirmed')
                                        <span class="text-xs font-bold text-sky-800 bg-sky-50 border border-sky-200 px-2.5 py-0.5 rounded-lg">
                                            {{ $isSelfService ? 'Menunggu Diantar ke Toko' : 'Dikonfirmasi (Tugaskan Kurir)' }}
                                        </span>
                                    @elseif ($orderStatus === 'on_the_way')
                                        <span class="text-xs font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-lg">
                                            Menuju Lokasi Kos
                                        </span>
                                    @elseif ($orderStatus === 'in_progress')
                                        <span class="text-xs font-bold text-purple-800 bg-purple-50 border border-purple-200 px-2.5 py-0.5 rounded-lg">
                                            {{ $isSelfService ? 'Sedang Dicuci di Toko' : 'Sedang Dikerjakan' }}
                                        </span>
                                    @elseif ($orderStatus === 'ready_for_pickup')
                                        <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-lg">
                                            Siap Diambil di Outlet
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400">• {{ $order->created_at?->diffForHumans() }}</span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900">
                                    {{ $order->title }}
                                </h4>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
                                    <span class="flex items-center gap-1 font-semibold text-slate-800">
                                        👤 {{ $payload['occupant_name'] ?? 'Penghuni Kos' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        📍 {{ $payload['room_number'] ?? 'Kamar' }} ({{ $payload['kost_address'] ?? 'Alamat Kos' }})
                                    </span>
                                    <span class="flex items-center gap-1 font-bold text-slate-900">
                                        💰 Rp {{ number_format($order->amount, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if (!empty($payload['order_notes']))
                                    <p class="text-xs text-slate-800 bg-slate-100 border border-slate-200 rounded-xl px-3 py-1.5 inline-block">
                                        <strong>Catatan Penghuni:</strong> "{{ $payload['order_notes'] }}"
                                    </p>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 self-start lg:self-auto flex-shrink-0">
                                @if ($orderStatus === 'pending')
                                    <form method="POST" action="{{ route('partner.orders.accept', $order->reference_id) }}">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-xs transition"
                                        >
                                            Terima Pesanan
                                        </button>
                                    </form>
                                @elseif ($orderStatus === 'confirmed')
                                    @if ($isSelfService)
                                        <form method="POST" action="{{ route('partner.orders.start-work', $order->reference_id) }}">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                style="background-color: #2563eb !important; color: #ffffff !important;"
                                                class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition"
                                            >
                                                Mulai Pengerjaan
                                            </button>
                                        </form>
                                    @else
                                        <a 
                                            href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                            class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition inline-flex items-center"
                                        >
                                            <span>Tugaskan Kurir</span>
                                        </a>
                                    @endif
                                @elseif ($orderStatus === 'on_the_way')
                                    <form method="POST" action="{{ route('partner.orders.start-work', $order->reference_id) }}">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            style="background-color: #2563eb !important; color: #ffffff !important;"
                                            class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition"
                                        >
                                            Mulai Pengerjaan
                                        </button>
                                    </form>
                                @elseif ($orderStatus === 'in_progress')
                                    @if ($isSelfService)
                                        <form method="POST" action="{{ route('partner.orders.mark-ready', $order->reference_id) }}">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="px-3.5 py-2 rounded-xl text-xs font-black bg-indigo-600 hover:bg-indigo-500 text-white shadow-xs transition"
                                            >
                                                🧺 Siap Diambil
                                            </button>
                                        </form>
                                    @endif
                                    <a 
                                        href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                        class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-xs transition"
                                    >
                                        ✅ Selesaikan
                                    </a>
                                @elseif ($orderStatus === 'ready_for_pickup')
                                    <a 
                                        href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                        class="px-4 py-2 rounded-xl text-xs font-black bg-emerald-600 hover:bg-emerald-500 text-white shadow-xs transition"
                                    >
                                        ✅ Serah Terima
                                    </a>
                                @endif

                                <a 
                                    href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                    class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                >
                                    Rincian
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-partner-layout>

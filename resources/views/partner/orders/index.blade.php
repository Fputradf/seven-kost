<x-partner-layout title="Daftar Pesanan Layanan">
    <div class="space-y-5 max-w-5xl">

        {{-- Header Card Gradient with Background Banner Image --}}
        <div class="relative overflow-hidden rounded-2xl p-5 text-white shadow-md border border-blue-400/25">
            <img src="{{ asset('images/frontend/banner-jakarta-wide.jpg') }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(12, 43, 84, 0.92) 0%, rgba(20, 70, 133, 0.85) 60%, rgba(30, 91, 168, 0.78) 100%);"></div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Pesanan Layanan</h1>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($partner)
                        <div class="px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full {{ $partner->is_open ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                            <span class="text-xs font-semibold text-white">
                                {{ $partner->is_open ? 'Toko Buka' : 'Toko Tutup' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('partner.profile.toggle-status') }}">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit" 
                                class="px-3 py-1.5 rounded-xl font-semibold text-xs transition-all cursor-pointer {{ $partner->is_open ? 'bg-white/15 hover:bg-white/25 text-white border border-white/25' : 'bg-emerald-500 hover:bg-emerald-400 text-white shadow-xs' }}"
                                title="Klik untuk mengubah status buka/tutup toko"
                            >
                                {{ $partner->is_open ? 'Tutup Toko' : 'Buka Toko' }}
                            </button>
                        </form>
                    @endif

                    <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/10 border border-white/20 text-white backdrop-blur-xs">
                        Total: {{ $counts['all'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Search & Tab Filters --}}
        <div class="rounded-2xl bg-white border border-slate-200/90 p-3 sm:p-4 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                
                {{-- Tabs --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <a 
                        href="{{ route('partner.orders.index', ['tab' => 'all']) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $tab === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua ({{ $counts['all'] }})
                    </a>

                    <a 
                        href="{{ route('partner.orders.index', ['tab' => 'pending']) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $tab === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Menunggu ({{ $counts['pending'] }})
                    </a>

                    <a 
                        href="{{ route('partner.orders.index', ['tab' => 'processing']) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $tab === 'processing' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Diproses ({{ $counts['processing'] }})
                    </a>

                    <a 
                        href="{{ route('partner.orders.index', ['tab' => 'completed']) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $tab === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Selesai ({{ $counts['completed'] }})
                    </a>

                    <a 
                        href="{{ route('partner.orders.index', ['tab' => 'cancelled']) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $tab === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Batal ({{ $counts['cancelled'] }})
                    </a>
                </div>

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('partner.orders.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $search }}" 
                        placeholder="Cari pesanan..." 
                        class="w-full sm:w-44 px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                    >
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white hover:bg-slate-800 transition shrink-0">
                        Cari
                    </button>
                    @if ($search)
                        <a href="{{ route('partner.orders.index', ['tab' => $tab]) }}" class="text-xs text-slate-500 hover:text-slate-800 font-bold px-1 shrink-0">
                            Reset
                        </a>
                    @endif
                </form>

            </div>
        </div>

        {{-- Order List --}}
        @if ($filteredOrders->isEmpty())
            <div class="rounded-2xl bg-white border border-slate-200/90 p-12 text-center shadow-xs">
                <h3 class="text-sm font-semibold text-slate-600">Tidak ada pesanan pada kategori ini</h3>
            </div>
        @else
            <div class="space-y-3.5">
                @foreach ($filteredOrders as $order)
                    @php
                        $payload = $order->payload ?? [];
                        $orderStatus = $payload['service_order_status'] ?? ($order->status === 'paid' ? 'confirmed' : 'pending');
                        $isSelfService = ($payload['fulfillment_type'] ?? 'delivery') === 'self_service';
                    @endphp
                    <div class="rounded-2xl bg-white border border-slate-200/90 p-4 sm:p-5 shadow-xs hover:border-blue-300 hover:shadow-md transition-all space-y-3">
                        
                        {{-- Top Meta Info --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-2.5 border-b border-slate-100">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-mono font-bold text-blue-900 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                    #{{ $order->reference_id }}
                                </span>

                                @if ($isSelfService)
                                    <span class="text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-lg">
                                        Antar Sendiri
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                        Kurir Antar-Jemput
                                    </span>
                                @endif

                                @if ($orderStatus === 'pending')
                                    <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-lg">
                                        Menunggu Konfirmasi
                                    </span>
                                @elseif ($orderStatus === 'confirmed')
                                    <span class="text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                        {{ $isSelfService ? 'Tunggu Diantar ke Toko' : 'Dikonfirmasi Mitra' }}
                                    </span>
                                @elseif ($orderStatus === 'on_the_way')
                                    <span class="text-xs font-bold text-indigo-800 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-lg">
                                        Kurir Menuju Lokasi
                                    </span>
                                @elseif ($orderStatus === 'in_progress')
                                    <span class="text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg">
                                        {{ $isSelfService ? 'Sedang Dicuci' : 'Sedang Dikerjakan' }}
                                    </span>
                                @elseif ($orderStatus === 'ready_for_pickup')
                                    <span class="text-xs font-bold text-amber-800 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-lg">
                                        Siap Diambil
                                    </span>
                                @elseif ($orderStatus === 'completed')
                                    <span class="text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-lg">
                                        Selesai
                                    </span>
                                @elseif (in_array($orderStatus, ['cancelled', 'rejected']) || $order->status === 'failed')
                                    <span class="text-xs font-bold text-rose-800 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-lg">
                                        Batal / Ditolak
                                    </span>
                                @endif

                                <span class="text-xs text-slate-400">
                                    {{ $order->created_at?->format('d M Y, H:i') }} WIB
                                </span>
                            </div>

                            <div>
                                <span class="text-base font-bold text-slate-900">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Order Details Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                            {{-- Col 1: Layanan --}}
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $order->title }}</h4>
                                <p class="text-slate-500 mt-0.5">
                                    {{ $payload['quantity'] ?? 1 }} {{ $payload['package_unit'] ?? 'unit' }}
                                    @if ($isSelfService)
                                        • Bebas Ongkir
                                    @elseif (!empty($payload['delivery_fee']))
                                        • Ongkir Rp {{ number_format($payload['delivery_fee'], 0, ',', '.') }} ({{ $payload['distance_km'] ?? '0' }} km)
                                    @endif
                                </p>
                            </div>

                            {{-- Col 2: Pemesan & Lokasi --}}
                            <div>
                                <p class="font-bold text-slate-900">
                                    {{ $payload['occupant_name'] ?? 'Penghuni Kos' }}
                                    @if (!empty($payload['occupant_phone']))
                                        <span class="font-normal text-slate-500">({{ $payload['occupant_phone'] }})</span>
                                    @endif
                                </p>
                                <p class="text-slate-500 mt-0.5">
                                    {{ $payload['room_number'] ?? 'Kamar' }} • {{ $payload['kost_address'] ?? 'Alamat Kos' }}
                                </p>
                            </div>

                            {{-- Col 3: Jadwal & Petugas --}}
                            <div>
                                <p class="font-bold text-slate-900">
                                    {{ $payload['order_date'] ?? '-' }}, {{ $payload['order_time'] ?? '-' }} WIB
                                </p>
                                @if ($isSelfService)
                                    <p class="text-slate-500 mt-0.5">
                                        Penyerahan di toko mitra
                                    </p>
                                @elseif (!empty($payload['courier_name']))
                                    <p class="text-slate-500 mt-0.5">
                                        Petugas: {{ $payload['courier_name'] }} ({{ $payload['courier_phone'] ?? '-' }})
                                    </p>
                                @else
                                    <p class="text-slate-400 mt-0.5">Belum ada kurir</p>
                                @endif
                            </div>
                        </div>

                        {{-- Catatan Khusus Pemesan --}}
                        @if (!empty($payload['order_notes']))
                            <div class="rounded-xl bg-slate-50 border border-slate-200 p-2 text-xs text-slate-700">
                                <span class="font-bold text-slate-900">Catatan:</span> "{{ $payload['order_notes'] }}"
                            </div>
                        @endif

                        {{-- Bottom Actions --}}
                        <div class="pt-1.5 flex flex-wrap items-center justify-between gap-2.5">
                            <div>
                                @if (!empty($payload['occupant_phone']))
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $payload['occupant_phone']);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                    @endphp
                                    <a 
                                        href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo Kak ' . ($payload['occupant_name'] ?? '') . ', kami dari mitra SevenKost (' . $partner->business_name . ') terkait pesanan layanan #' . $order->reference_id) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition"
                                    >
                                        Hubungi WhatsApp
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($orderStatus === 'pending')
                                    <form method="POST" action="{{ route('partner.orders.accept', $order->reference_id) }}">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
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
                                                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                                            >
                                                Mulai Pengerjaan
                                            </button>
                                        </form>
                                    @else
                                        <a 
                                            href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition"
                                        >
                                            Tugaskan Kurir
                                        </a>
                                    @endif
                                @elseif ($orderStatus === 'on_the_way')
                                    <form method="POST" action="{{ route('partner.orders.start-work', $order->reference_id) }}">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            style="background-color: #2563eb !important; color: #ffffff !important;"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
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
                                                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white shadow-xs transition cursor-pointer"
                                            >
                                                Siap Diambil
                                            </button>
                                        </form>
                                    @endif
                                    <a 
                                        href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition"
                                    >
                                        Selesaikan
                                    </a>
                                @elseif ($orderStatus === 'ready_for_pickup')
                                    <a 
                                        href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition"
                                    >
                                        Serah Terima
                                    </a>
                                @endif

                                <a 
                                    href="{{ route('partner.orders.show', $order->reference_id) }}" 
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                >
                                    Rincian
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-partner-layout>

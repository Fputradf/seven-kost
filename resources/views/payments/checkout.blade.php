<x-seven-layout title="Pembayaran via Xendit - SevenKost">

    <div 
        x-data="{
            copied: false,
            copy(text) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text);
                } else {
                    const input = document.createElement('input');
                    input.value = text;
                    document.body.appendChild(input);
                    input.select();
                    document.execCommand('copy');
                    document.body.removeChild(input);
                }
                this.copied = true;
                setTimeout(() => this.copied = false, 2500);
            },
            pollStatus() {
                setInterval(async () => {
                    try {
                        const res = await fetch('{{ route('payments.status', $transaction->reference_id) }}');
                        if (!res.ok) return;
                        const data = await res.json();
                        if (data.is_paid) {
                            window.location.href = '{{ route('payments.success', $transaction->reference_id) }}';
                        }
                    } catch (e) {
                        // ignore network polling hiccups
                    }
                }, 3000);
            }
        }"
        x-init="pollStatus()"
        class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
    >
        {{-- BREADCRUMB & HEADER --}}
        <div class="mb-8">
            @php
                $backUrl = auth()->user()?->hasRole('pemilik') ? route('owner.promosi.index') : route('ads.packages.index');
                $backLabel = auth()->user()?->hasRole('pemilik') ? 'Kembali ke Promosi & VIP Pemilik' : 'Kembali ke Paket Iklan';
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>{{ $backLabel }}</span>
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 mb-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        Menunggu Pembayaran
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Checkout Pembayaran Xendit
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Selesaikan pembayaran menggunakan saluran yang Anda pilih. Sistem memverifikasi otomatis dalam beberapa detik.
                    </p>
                </div>

                {{-- XENDIT BADGE --}}
                <div class="flex items-center gap-2 self-start sm:self-center px-4 py-2 rounded-2xl bg-slate-100 border border-slate-200/80 text-xs font-bold text-slate-600">
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Secured by <strong class="text-slate-900 font-black">Xendit</strong></span>
                </div>
            </div>
        </div>

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-medium flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- KOLOM KIRI: INSTRUKSI PEMBAYARAN (7 Cols) --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- KARTU INSTRUKSI SALURAN --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                @if($paymentDetails['type'] === 'QRIS')
                                    📱
                                @elseif($paymentDetails['type'] === 'VA')
                                    🏦
                                @else
                                    💳
                                @endif
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Metode Pembayaran</span>
                                <h2 class="text-base font-extrabold text-slate-900">{{ $transaction->channel_label }}</h2>
                            </div>
                        </div>

                        <span class="text-xs font-mono font-bold text-slate-500 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200/60">
                            {{ $transaction->reference_id }}
                        </span>
                    </div>

                    {{-- VARIANT A: QRIS --}}
                    @if($paymentDetails['type'] === 'QRIS')
                        <div class="text-center space-y-4">
                            <p class="text-xs text-slate-600">
                                Pindai (Scan) kode QR di bawah ini menggunakan aplikasi mobile banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, OVO, Dana, ShopeePay):
                            </p>

                            {{-- QR CODE DISPLAY --}}
                            <div class="inline-block p-4 sm:p-6 bg-white rounded-3xl border-2 border-slate-200 shadow-md">
                                <div class="w-56 h-56 mx-auto bg-slate-900 rounded-2xl flex flex-col items-center justify-center p-3 relative overflow-hidden group">
                                    {{-- Simulating visual dynamic QR grid --}}
                                    <div class="w-full h-full bg-white rounded-xl p-3 flex flex-col items-center justify-between">
                                        <div class="w-full flex justify-between items-center px-1">
                                            <div class="w-7 h-7 border-4 border-slate-900 rounded-md flex items-center justify-center">
                                                <div class="w-3 h-3 bg-slate-900 rounded-xs"></div>
                                            </div>
                                            <span class="text-[9px] font-black tracking-widest text-slate-400">QRIS</span>
                                            <div class="w-7 h-7 border-4 border-slate-900 rounded-md flex items-center justify-center">
                                                <div class="w-3 h-3 bg-slate-900 rounded-xs"></div>
                                            </div>
                                        </div>

                                        <div class="my-auto py-2 flex flex-col items-center">
                                            <svg class="w-20 h-20 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M2 2h7v7H2V2zm2 2v3h3V4H4zm11-2h7v7h-7V2zm2 2v3h3V4h-3zM2 15h7v7H2v-7zm2 2v3h3v-3H4zm13-2h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 2h2v2h-2v-2zm-7-4h2v2h-2v-2zm0 2h2v2h-2v-2zm2-2h2v2h-2v-2zm0 2h2v2h-2v-2zM9 9h2v2H9V9zm2 2h2v2h-2v-2zm-2 2h2v2H9v-2zm4-4h2v2h-2V9zm2 2h2v2h-2v-2zm-2 2h2v2h-2v-2z"/>
                                            </svg>
                                            <span class="text-[10px] font-black text-blue-600 mt-1 uppercase tracking-wider">SEVEN KOST</span>
                                        </div>

                                        <div class="w-full flex justify-between items-center px-1">
                                            <div class="w-7 h-7 border-4 border-slate-900 rounded-md flex items-center justify-center">
                                                <div class="w-3 h-3 bg-slate-900 rounded-xs"></div>
                                            </div>
                                            <span class="text-[8px] font-mono text-slate-500">NMID: ID10200928</span>
                                            <div class="w-3 h-3 bg-slate-900 rounded-xs"></div>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[11px] font-mono font-bold text-slate-500 mt-3 block">
                                    Total: {{ $transaction->formatted_amount }}
                                </span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 text-xs text-blue-800 text-left space-y-1">
                                <div class="font-bold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    <span>Cara Membayar dengan QRIS:</span>
                                </div>
                                <ol class="list-decimal list-inside space-y-1 text-slate-600 pl-1 text-[11px]">
                                    <li>Buka aplikasi m-Banking atau E-Wallet pilihan Anda.</li>
                                    <li>Pilih menu <strong>Bayar / QRIS / Scan QR</strong>.</li>
                                    <li>Arahkan kamera ke kode QR di atas.</li>
                                    <li>Periksa nama merchant: <strong>SEVEN KOST</strong> dan nominal.</li>
                                    <li>Masukkan PIN transaksi Anda untuk menyelesaikan pembayaran.</li>
                                </ol>
                            </div>
                        </div>

                    {{-- VARIANT B: VIRTUAL ACCOUNT --}}
                    @elseif($paymentDetails['type'] === 'VA')
                        <div class="space-y-4">
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                        Nomor Virtual Account Bank {{ $paymentDetails['bank'] }}
                                    </span>
                                    <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                                        Verifikasi Otomatis
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-200">
                                    <span class="text-lg sm:text-2xl font-black font-mono tracking-wider text-slate-900">
                                        {{ $paymentDetails['va_number'] }}
                                    </span>
                                    <button 
                                        type="button" 
                                        @click="copy('{{ $paymentDetails['va_number'] }}')"
                                        class="px-4 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition flex items-center gap-1.5 shrink-0"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span x-text="copied ? 'Tersalin ✓' : 'Salin'"></span>
                                    </button>
                                </div>

                                <div class="text-[11px] text-slate-500 flex items-center justify-between">
                                    <span>Nama Akun Tujuan:</span>
                                    <strong class="text-slate-800">Seven Kost - {{ auth()->user()->name }}</strong>
                                </div>
                            </div>

                            {{-- Petunjuk VA --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 space-y-1.5">
                                <span class="font-bold text-slate-800 block">Petunjuk Pembayaran Transfer VA:</span>
                                <ol class="list-decimal list-inside space-y-1 text-[11px] text-slate-600 pl-1">
                                    <li>Masuk ke menu Transfer / Bayar Virtual Account pada m-Banking atau ATM Bank {{ $paymentDetails['bank'] }}.</li>
                                    <li>Masukkan nomor VA: <strong class="font-mono">{{ $paymentDetails['va_number'] }}</strong>.</li>
                                    <li>Pastikan nama tagihan sesuai dengan akun Anda dan nominal sebesar <strong class="text-slate-800">{{ $transaction->formatted_amount }}</strong>.</li>
                                    <li>Selesaikan transaksi dan simpan bukti transfer.</li>
                                </ol>
                            </div>
                        </div>

                    {{-- VARIANT C: EWALLET --}}
                    @else
                        <div class="space-y-4">
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">
                                    Saluran Dompet Digital (E-Wallet)
                                </span>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-200 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Nomor Ponsel Akun:</span>
                                        <strong class="text-base font-mono font-bold text-slate-900">{{ $paymentDetails['phone'] }}</strong>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">
                                        Instan Otomatis
                                    </span>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 text-xs text-blue-800 space-y-1">
                                <div class="font-bold">Langkah Pembayaran E-Wallet:</div>
                                <ol class="list-decimal list-inside space-y-1 text-slate-600 text-[11px] pl-1">
                                    <li>Buka aplikasi GoPay / OVO / ShopeePay / DANA pada smartphone Anda.</li>
                                    <li>Periksa notifikasi penagihan pembayaran dari Xendit / SevenKost.</li>
                                    <li>Konfirmasi pembayaran sesuai nominal yang tertera.</li>
                                </ol>
                            </div>
                        </div>
                    @endif

                    {{-- AUTO-POLLING STATUS NOTIFIER --}}
                    <div class="pt-2 flex items-center justify-center gap-2 text-xs font-medium text-slate-500">
                        <svg class="w-4 h-4 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menunggu verifikasi pembayaran otomatis dari Xendit...</span>
                    </div>

                    {{-- TOMBOL SIMULASI SANDBOX (SANGAT BERMANFAAT UNTUK PENGUJIAN LOKAL) --}}
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-amber-800">
                            <span class="flex items-center gap-1.5">
                                <span>⚡</span>
                                <span>Sandbox & Simulasi Pengujian Localhost</span>
                            </span>
                            <span class="text-[10px] bg-amber-200/80 text-amber-900 px-2 py-0.5 rounded-md font-mono">Dev Mode</span>
                        </div>
                        <p class="text-[11px] text-amber-700 leading-relaxed">
                            Di lingkungan pengujian, Anda dapat menekan tombol di bawah untuk menyimulasikan transaksi lunas seketika dari Xendit Gateway tanpa harus membuka m-banking.
                        </p>
                        <form action="{{ route('payments.simulate', $transaction->reference_id) }}" method="POST" class="pt-1">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-black text-xs shadow-md shadow-amber-600/20 transition-all flex items-center justify-center gap-2"
                            >
                                <span>⚡ Bayar Sekarang (Simulasi Lunas Otomatis)</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            {{-- KOLOM KANAN: RINGKASAN PESANAN (5 Cols) --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Ringkasan Tagihan</h3>
                        <span class="text-xs font-bold text-slate-500">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    {{-- DETAIL ITEM --}}
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                                @if($transaction->type === 'vip')
                                    👑
                                @else
                                    📢
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 block">
                                    {{ $transaction->type === 'vip' ? 'Langganan VIP Pemilik' : 'Iklan Banner Komersial' }}
                                </span>
                                <h4 class="font-extrabold text-xs sm:text-sm text-slate-900 leading-snug">
                                    {{ $transaction->title }}
                                </h4>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>Nama Pemesan:</span>
                                <span class="font-bold text-slate-900">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Email:</span>
                                <span class="font-medium text-slate-800">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>No. Referensi:</span>
                                <span class="font-mono text-slate-800">{{ $transaction->reference_id }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- RINCIAN BIAYA --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Layanan:</span>
                            <span class="font-medium text-slate-900">{{ $transaction->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Gateway (Xendit):</span>
                            <span class="font-bold text-emerald-600">Rp 0 (Gratis)</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Pajak (PPN):</span>
                            <span class="font-bold text-emerald-600">Termasuk</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200/80 flex items-baseline justify-between">
                            <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                            <span class="text-xl sm:text-2xl font-black text-blue-600">
                                {{ $transaction->formatted_amount }}
                            </span>
                        </div>
                    </div>

                    {{-- KEAMANAN GARANSI --}}
                    <div class="pt-4 border-t border-slate-100 flex items-center gap-2 text-[11px] text-slate-500">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Transaksi diproses melalui sistem perbankan terenkripsi 256-bit.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-seven-layout>

@props([
    'transaction',
    'booking' => null,
    'backUrl' => url('/'),
    'backLabel' => 'Kembali',
    'uploadRoute' => null,
    'simulateRoute' => null,
    'successRoute' => null,
])

@php
    use App\Models\AppSetting;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $payload = $transaction->payload ?? [];
    $proofImage = $payload['payment_proof_image'] ?? $transaction->payment_proof_path ?? null;
    $senderName = $payload['payment_sender_name'] ?? $transaction->proof_sender_name ?? null;
    $senderBank = $payload['payment_sender_bank'] ?? $transaction->proof_bank_name ?? null;
    $confirmationStatus = $payload['payment_confirmation_status'] ?? null;
    $isPaid = $transaction->isPaid();
    $isManualProofUploaded = !empty($proofImage);
    $isAwaitingConfirmation = $confirmationStatus === 'awaiting_confirmation' || ($transaction->status === 'pending' && $isManualProofUploaded);
    $isRejected = $confirmationStatus === 'rejected' || ($transaction->status === 'failed' && $isManualProofUploaded);
    
    // Konfigurasi Superadmin
    $isXenditActive = (bool) AppSetting::get('payment_xendit_enabled', true);
    $isManualActive = (bool) AppSetting::get('payment_manual_enabled', true);
    $manualQrImage = AppSetting::get('payment_manual_qr_image');
    $manualBanks = AppSetting::get('payment_manual_banks', [
        ['bank_name' => 'BCA', 'account_number' => '8290123891', 'account_holder' => 'PT Seven Kost Indonesia'],
        ['bank_name' => 'Mandiri', 'account_number' => '1370019283741', 'account_holder' => 'PT Seven Kost Indonesia'],
        ['bank_name' => 'BRI', 'account_number' => '034101002341308', 'account_holder' => 'PT Seven Kost Indonesia'],
        ['bank_name' => 'BNI', 'account_number' => '0839218392', 'account_holder' => 'PT Seven Kost Indonesia'],
    ]);
    if (!is_array($manualBanks)) {
        $manualBanks = json_decode($manualBanks, true) ?: [];
    }
    $manualInstructions = AppSetting::get('payment_manual_instructions', 'Silakan lakukan transfer sesuai total tagihan, simpan bukti transfer/struk ATM, lalu unggah pada form di bawah untuk diverifikasi tim SevenKost.');

    // Generate Scannable Real QRIS Data URI & Raw Inline SVG menggunakan chillerlan/php-qrcode
    $qrDisplayUrl = null;
    $qrSvgRaw = null;
    if (!empty($manualQrImage) && Storage::disk('public')->exists($manualQrImage)) {
        $qrDisplayUrl = asset('storage/' . $manualQrImage);
    } else {
        try {
            $qrDataString = '00020101021226670016ID.CO.SEVENKOST01189360099900000000000215' . $transaction->reference_id . '52045812530336054' . strlen((int)$transaction->amount) . (int)$transaction->amount . '5802ID5920SEVEN KOST INDONESIA6007JAKARTA6304' . strtoupper(substr(md5($transaction->reference_id), 0, 4));
            
            // Raw SVG for instant inline DOM rendering
            $svgOptions = new \chillerlan\QRCode\QROptions([
                'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG,
                'imageBase64' => false,
            ]);
            $rawSvg = (new \chillerlan\QRCode\QRCode($svgOptions))->render($qrDataString);
            $qrSvgRaw = preg_replace('/<\?xml.*?\?>/i', '', $rawSvg);
            
            // Data URI for download
            $qrDisplayUrl = (new \chillerlan\QRCode\QRCode)->render($qrDataString);
        } catch (\Throwable $e) {
            $qrDisplayUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($transaction->reference_id);
        }
    }

    // Generate VA Numbers dinamis berbasis reference_id
    $digitsOnly = preg_replace('/[^0-9]/', '', $transaction->reference_id);
    if (strlen($digitsOnly) < 6) {
        $digitsOnly = str_pad($digitsOnly . ($transaction->id ?? 1), 6, '0', STR_PAD_RIGHT);
    }
    $vaNumberBca = '88000' . substr($digitsOnly, -6);
    $vaNumberMandiri = '88708' . substr($digitsOnly, -6);
    $vaNumberBri = '12345' . substr($digitsOnly, -6);
    $vaNumberBni = '8277' . substr($digitsOnly, -6);
@endphp

<div 
    x-data="{
        activeTab: 'qris_ewallet', // 'qris_ewallet', 'va', 'manual'
        viewMode: 'select',        // 'select' (menu pilihan sesuai screenshot), 'qris', 'va', 'manual'
        selectedChannel: 'qris',   // 'qris', 'gopay', 'dana', 'ovo', 'shopeepay'
        selectedVaBank: 'bca',     // 'bca', 'mandiri', 'bri', 'bni'
        
        // Timer Hitung Mundur Riil Berbasis Jenis Transaksi
        timeLeft: {{ $transaction->remaining_seconds }},
        isExpired: {{ $transaction->isExpired() ? 'true' : 'false' }},
        formattedTime: '',
        timerInterval: null,
        
        // Copy & State
        checking: false,
        copiedText: null,
        previewUrl: null,
        showProofModal: false,
        
        init() {
            this.updateFormattedTime();
            this.startTimer();
            this.pollStatus();
            // Jika sudah ada bukti manual diunggah, langsung buka tab manual
            @if($isManualProofUploaded)
                this.viewMode = 'manual';
                this.activeTab = 'manual';
            @endif
        },
        startTimer() {
            if (this.isExpired) {
                this.formattedTime = 'Kadaluarsa';
                return;
            }
            this.timerInterval = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                    this.updateFormattedTime();
                } else {
                    this.isExpired = true;
                    this.formattedTime = 'Kadaluarsa';
                    clearInterval(this.timerInterval);
                }
            }, 1000);
        },
        updateFormattedTime() {
            if (this.timeLeft <= 0) {
                this.formattedTime = 'Kadaluarsa';
                return;
            }
            const days = Math.floor(this.timeLeft / 86400);
            const hours = Math.floor((this.timeLeft % 86400) / 3600);
            const minutes = Math.floor((this.timeLeft % 3600) / 60);
            const seconds = this.timeLeft % 60;

            if (days > 0) {
                this.formattedTime = `${days}h ${hours}j ${minutes}m`;
            } else if (hours > 0) {
                const h = hours.toString().padStart(2, '0');
                const m = minutes.toString().padStart(2, '0');
                const s = seconds.toString().padStart(2, '0');
                this.formattedTime = `${h}:${m}:${s}`;
            } else {
                const m = minutes.toString().padStart(2, '0');
                const s = seconds.toString().padStart(2, '0');
                this.formattedTime = `${m}:${s}`;
            }
        },
        openChannel(channel) {
            this.selectedChannel = channel;
            this.viewMode = 'qris';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        openVa(bank) {
            this.selectedVaBank = bank;
            this.viewMode = 'va';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        openManual() {
            this.viewMode = 'manual';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        backToSelect() {
            this.viewMode = 'select';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        copy(text, label) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text);
            } else {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
            this.copiedText = label;
            setTimeout(() => { this.copiedText = null; }, 2500);
        },
        onFileSelected(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        },
        pollStatus() {
            setInterval(async () => {
                try {
                    const res = await fetch('{{ route('payments.status', $transaction->reference_id) }}');
                    if (res.ok) {
                        const data = await res.json();
                        if (data.is_paid || data.status === 'paid') {
                            window.location.href = '{{ $successRoute ?: route('payments.success', $transaction->reference_id) }}';
                        }
                    }
                } catch (e) {}
            }, 5000);
        },
        async checkStatusManual() {
            this.checking = true;
            try {
                const res = await fetch('{{ route('payments.status', $transaction->reference_id) }}');
                if (res.ok) {
                    const data = await res.json();
                    if (data.is_paid || data.status === 'paid') {
                        window.location.href = '{{ $successRoute ?: route('payments.success', $transaction->reference_id) }}';
                        return;
                    }
                }
            } catch (e) {}
            this.checking = false;
        }
    }"
    class="max-w-md mx-auto pb-16 font-sans text-slate-800"
>

    {{-- Toast Notifikasi Disalin --}}
    <div 
        x-show="copiedText" 
        x-transition 
        class="fixed top-18 left-1/2 -translate-x-1/2 z-50 bg-slate-900/95 backdrop-blur-md text-white text-xs font-bold px-4 py-2 rounded-full shadow-2xl flex items-center gap-2 border border-white/10"
        style="display: none;"
    >
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        <span x-text="copiedText + ' berhasil disalin!'"></span>
    </div>

    @if(session('success'))
        <div class="mb-3 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold shadow-2xs flex items-start gap-2">
            <span class="w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
            <span class="leading-relaxed">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-3 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold shadow-2xs flex items-start gap-2">
            <span class="w-4 h-4 rounded-full bg-rose-600 text-white flex items-center justify-center text-[10px] shrink-0 mt-0.5">!</span>
            <span class="leading-relaxed">{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="mb-3 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold shadow-2xs space-y-1">
            @foreach($errors->all() as $err)
                <p>&bull; {{ $err }}</p>
            @endforeach
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- TOP NAVBAR & INVOICE SUMMARY                                   --}}
    {{-- ============================================================== --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs p-4 mb-4 space-y-3">
        {{-- Bar Header Atas persis screenshot: <  Pilih Metode Pembayaran --}}
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <template x-if="viewMode === 'select'">
                    <a href="{{ $backUrl }}" class="p-1.5 -ml-1 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                </template>
                <template x-if="viewMode !== 'select'">
                    <button type="button" @click="backToSelect()" class="p-1.5 -ml-1 text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                </template>
                <h1 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">Pilih Metode Pembayaran</h1>
            </div>
            
            <div class="flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full border transition"
                 :class="isExpired ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-50 text-slate-700 border-slate-200/80'">
                <svg class="w-3.5 h-3.5" :class="isExpired ? 'text-rose-500' : 'text-amber-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-mono font-extrabold" :class="isExpired ? 'text-rose-600' : 'text-amber-600'" x-text="formattedTime"></span>
            </div>
        </div>

        {{-- Ringkasan Tagihan Singkat --}}
        <div class="space-y-1.5 text-xs pt-1">
            <div class="flex items-center justify-between text-xs">
                <div>
                    <span class="text-[11px] text-slate-400 block font-medium">Total Pembayaran</span>
                    <span class="text-lg font-black text-blue-600 tracking-tight">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 block font-mono">#{{ $transaction->reference_id }}</span>
                    <span class="text-[11px] font-semibold text-slate-600 truncate max-w-[170px] inline-block">{{ $transaction->title }}</span>
                </div>
            </div>

            @if($transaction->type === 'topup')
                <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100 text-slate-500">
                    <span>Nominal Top Up Saldo:</span>
                    <strong class="text-slate-900 font-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- TAMPILAN 1: MENU PILIH METODE (PERSIS SEPERTI GAMBAR USER)     --}}
    {{-- ============================================================== --}}
    <div x-show="viewMode === 'select'" x-transition class="space-y-4">

        {{-- TABS SWITCHER: QRIS & E-Wallet | Virtual Account | Transfer Manual --}}
        <div class="bg-slate-100/90 p-1 rounded-2xl flex items-center gap-1 border border-slate-200/60 shadow-inner">
            {{-- Tab 1: QRIS & E-Wallet --}}
            <button 
                type="button" 
                @click="activeTab = 'qris_ewallet'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="activeTab === 'qris_ewallet' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>QRIS &amp; E-Wallet</span>
            </button>

            {{-- Tab 2: Virtual Account --}}
            <button 
                type="button" 
                @click="activeTab = 'va'"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="activeTab === 'va' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Virtual Account</span>
            </button>

            {{-- Tab 3: Transfer Manual (Bila Superadmin mengaktifkan) --}}
            <button 
                type="button" 
                @click="activeTab = 'manual'"
                class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                :class="activeTab === 'manual' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-600 hover:text-slate-900'"
            >
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span class="hidden sm:inline">Manual</span>
            </button>
        </div>

        {{-- TAB CONTENT 1: SALURAN PEMBAYARAN INSTAN (QRIS & E-WALLET) --}}
        <div x-show="activeTab === 'qris_ewallet'" class="space-y-2">
            <div class="px-2">
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">SALURAN PEMBAYARAN INSTAN</span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden divide-y divide-slate-100">
                
                {{-- ITEM 1: QRIS Standar Nasional (Populer) --}}
                <div 
                    @click="openChannel('qris')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        {{-- Logo Box QRIS --}}
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="px-1.5 py-0.5 rounded bg-rose-600 text-white font-black text-[11px] tracking-wider leading-none shadow-2xs">QRIS</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">QRIS Standar Nasional</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                    Populer
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 block font-medium">Scan QRIS untuk semua Bank &amp; E-Wallet</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- ITEM 2: GoPay --}}
                <div 
                    @click="openChannel('gopay')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        {{-- Logo Box GoPay --}}
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <div class="flex items-center gap-1">
                                <span class="w-3.5 h-3.5 rounded-full bg-sky-500 flex items-center justify-center text-white text-[9px] font-black leading-none">●</span>
                                <span class="text-sky-600 font-black text-xs tracking-tight">gopay</span>
                            </div>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">GoPay</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- ITEM 3: DANA --}}
                <div 
                    @click="openChannel('dana')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        {{-- Logo Box DANA --}}
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="px-2 py-0.5 rounded-lg bg-blue-500 text-white font-black text-[10px] tracking-wider leading-none">DANA</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">DANA</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- ITEM 4: OVO --}}
                <div 
                    @click="openChannel('ovo')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        {{-- Logo Box OVO --}}
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="w-5 h-5 rounded-full border-2 border-purple-700 flex items-center justify-center text-purple-700 font-black text-[10px] leading-none">o</span>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">OVO</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- ITEM 5: ShopeePay --}}
                <div 
                    @click="openChannel('shopeepay')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        {{-- Logo Box ShopeePay --}}
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <div class="flex items-center gap-1">
                                <span class="px-1 py-0.5 rounded bg-orange-500 text-white font-black text-[9px] leading-none">S</span>
                                <span class="text-orange-600 font-extrabold text-[10px] tracking-tight">Pay</span>
                            </div>
                        </div>
                        <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">ShopeePay</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

            </div>
        </div>

        {{-- TAB CONTENT 2: VIRTUAL ACCOUNT (VERIFIKASI OTOMATIS) --}}
        <div x-show="activeTab === 'va'" class="space-y-2">
            <div class="px-2">
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">VIRTUAL ACCOUNT (VERIFIKASI OTOMATIS)</span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden divide-y divide-slate-100">
                {{-- BCA VA --}}
                <div 
                    @click="openVa('bca')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="text-blue-800 font-black text-xs tracking-wider">BCA</span>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition block">BCA Virtual Account</span>
                            <span class="text-[11px] text-slate-400">Verifikasi instan otomatis</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- Mandiri VA --}}
                <div 
                    @click="openVa('mandiri')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="text-amber-700 font-black text-xs tracking-tight">mandiri</span>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition block">Mandiri Virtual Account</span>
                            <span class="text-[11px] text-slate-400">Livin' by Mandiri &amp; ATM</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- BRI VA --}}
                <div 
                    @click="openVa('bri')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="text-sky-700 font-black text-xs tracking-wider">BRI</span>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition block">BRI Virtual Account (BRIVA)</span>
                            <span class="text-[11px] text-slate-400">BRImo &amp; ATM BRI</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>

                {{-- BNI VA --}}
                <div 
                    @click="openVa('bni')"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-9 rounded-xl bg-white border border-slate-200/90 flex items-center justify-center shadow-2xs shrink-0 group-hover:border-slate-300">
                            <span class="text-teal-700 font-black text-xs tracking-wider">BNI</span>
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition block">BNI Virtual Account</span>
                            <span class="text-[11px] text-slate-400">BNI Mobile Banking &amp; ATM</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT 3: TRANSFER BANK MANUAL & BUKTI --}}
        <div x-show="activeTab === 'manual'" class="space-y-2">
            <div class="px-2">
                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">TRANSFER BANK MANUAL</span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden divide-y divide-slate-100">
                <div 
                    @click="openManual()"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition cursor-pointer select-none group"
                >
                    <div class="flex items-center gap-3.5">
                        <div class="w-14 h-9 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center shadow-2xs shrink-0">
                            <span class="text-amber-800 font-black text-[10px]">BANK</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-extrabold text-slate-900 group-hover:text-blue-600 transition">Transfer Bank Manual</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Upload Struk
                                </span>
                            </div>
                            <span class="text-[11px] text-slate-400">Transfer ke rekening resmi &amp; konfirmasi upload bukti</span>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================== --}}
    {{-- TAMPILAN 2: DETAIL QRIS & E-WALLET (BARCODE TAMPIL INSTAN)    --}}
    {{-- ============================================================== --}}
    <div x-show="viewMode === 'qris'" x-transition class="space-y-4" style="display: none;">
        
        {{-- Header Breadcrumb Ganti Metode --}}
        <div class="flex items-center justify-between px-1">
            <button 
                type="button" 
                @click="backToSelect()" 
                class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1.5 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Ganti Metode Pembayaran</span>
            </button>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider" x-text="selectedChannel.toUpperCase()"></span>
        </div>

        {{-- Standee Kartu QRIS Resmi Standar Nasional --}}
        <div class="bg-white rounded-3xl p-5 text-center text-slate-900 shadow-sm border border-slate-200 space-y-4">
            
            {{-- Header Standar QRIS Nasional --}}
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 px-1">
                <div class="flex items-center gap-2">
                    <div class="px-2.5 py-0.5 rounded-md bg-rose-600 text-white font-black text-xs tracking-wider shadow-2xs">
                        QRIS
                    </div>
                    <span class="text-[11px] font-black text-slate-800 tracking-wider">QR INDONESIA STANDAR</span>
                </div>
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">ASPI / BI</span>
            </div>

            {{-- Merchant Name & Info --}}
            <div>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">SEVEN KOST INDONESIA</h3>
                <p class="text-[11px] text-slate-500 font-mono mt-0.5 font-bold">NMID: ID102026778901</p>
                <template x-if="selectedChannel !== 'qris'">
                    <span class="mt-1.5 inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        Scan via aplikasi <span class="capitalize font-black" x-text="selectedChannel"></span>
                    </span>
                </template>
            </div>

            {{-- GAMBAR QR CODE SCANNABLE NYATA BERSIH --}}
            <div class="my-1 p-3.5 bg-white rounded-2xl inline-block border-2 border-slate-200 shadow-xs max-w-[240px] w-full">
                @if($qrSvgRaw)
                    <div class="w-48 h-48 sm:w-52 sm:h-52 mx-auto flex items-center justify-center [&>svg]:w-full [&>svg]:h-full [&>svg]:max-w-full [&>svg]:max-h-full">
                        {!! $qrSvgRaw !!}
                    </div>
                @elseif($qrDisplayUrl)
                    <img 
                        src="{{ $qrDisplayUrl }}" 
                        alt="QRIS Barcode SevenKost" 
                        class="w-48 h-48 sm:w-52 sm:h-52 mx-auto object-contain block rounded-lg"
                        loading="eager"
                    />
                @endif
            </div>

            {{-- Total Tagihan --}}
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs px-2">
                <span class="text-slate-500 font-semibold uppercase tracking-wider text-[11px]">Total Tagihan:</span>
                <span class="font-black text-lg text-blue-700">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol Aksi QRIS --}}
        <div class="grid grid-cols-2 gap-2 text-xs">
            <button 
                type="button" 
                @click="copy('{{ (int)$transaction->amount }}', 'Nominal tagihan')" 
                class="py-3 px-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-bold transition flex items-center justify-center gap-1.5 border border-slate-300 shadow-2xs cursor-pointer active:scale-98"
            >
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Salin Nominal</span>
            </button>

            <a 
                href="{{ $qrDisplayUrl }}" 
                download="QRIS-SevenKost-{{ $transaction->reference_id }}.svg"
                class="py-3 px-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer active:scale-98"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh QR</span>
            </a>
        </div>

        {{-- Panduan Singkat Scan QRIS --}}
        <div class="bg-white rounded-3xl p-4 text-[11px] text-slate-600 space-y-1.5 border border-slate-200/90 shadow-2xs text-left">
            <span class="font-bold text-slate-900 block text-xs">Cara Pembayaran QRIS / E-Wallet:</span>
            <p>1. Buka aplikasi m-Banking (BCA, Livin', BRImo, BNI) atau E-Wallet (GoPay, DANA, OVO, ShopeePay).</p>
            <p>2. Pilih menu <strong>Scan / Bayar QRIS</strong> lalu arahkan kamera ke barcode di atas atau unggah foto QR dari galeri.</p>
            <p>3. Pastikan nama penerima <strong>SEVEN KOST INDONESIA</strong> dan nominal <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>, lalu masukkan PIN Anda.</p>
            <p>4. Status pesanan akan otomatis terverifikasi dan menjadi <strong>Lunas</strong> dalam hitungan detik.</p>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- TAMPILAN 3: DETAIL VIRTUAL ACCOUNT                            --}}
    {{-- ============================================================== --}}
    <div x-show="viewMode === 'va'" x-transition class="space-y-4" style="display: none;">
        {{-- Header Breadcrumb Ganti Metode --}}
        <div class="flex items-center justify-between px-1">
            <button 
                type="button" 
                @click="backToSelect()" 
                class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1.5 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Ganti Metode Pembayaran</span>
            </button>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">VIRTUAL ACCOUNT</span>
        </div>

        {{-- Kotak Pilihan Bank VA Cepat --}}
        <div class="grid grid-cols-4 gap-2 text-xs">
            <button 
                type="button" 
                @click="selectedVaBank = 'bca'"
                class="py-2 px-1 rounded-xl border text-center transition cursor-pointer bg-white"
                :class="selectedVaBank === 'bca' ? 'border-blue-600 text-blue-700 font-black ring-2 ring-blue-500/20 bg-blue-50/40 shadow-xs' : 'border-slate-200 text-slate-700 hover:border-slate-300'"
            >
                BCA
            </button>
            <button 
                type="button" 
                @click="selectedVaBank = 'mandiri'"
                class="py-2 px-1 rounded-xl border text-center transition cursor-pointer bg-white"
                :class="selectedVaBank === 'mandiri' ? 'border-blue-600 text-blue-700 font-black ring-2 ring-blue-500/20 bg-blue-50/40 shadow-xs' : 'border-slate-200 text-slate-700 hover:border-slate-300'"
            >
                Mandiri
            </button>
            <button 
                type="button" 
                @click="selectedVaBank = 'bri'"
                class="py-2 px-1 rounded-xl border text-center transition cursor-pointer bg-white"
                :class="selectedVaBank === 'bri' ? 'border-blue-600 text-blue-700 font-black ring-2 ring-blue-500/20 bg-blue-50/40 shadow-xs' : 'border-slate-200 text-slate-700 hover:border-slate-300'"
            >
                BRI
            </button>
            <button 
                type="button" 
                @click="selectedVaBank = 'bni'"
                class="py-2 px-1 rounded-xl border text-center transition cursor-pointer bg-white"
                :class="selectedVaBank === 'bni' ? 'border-blue-600 text-blue-700 font-black ring-2 ring-blue-500/20 bg-blue-50/40 shadow-xs' : 'border-slate-200 text-slate-700 hover:border-slate-300'"
            >
                BNI
            </button>
        </div>

        {{-- Detail Nomor VA Card --}}
        <div class="bg-white rounded-3xl p-5 text-slate-900 space-y-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Nomor Virtual Account <span x-text="selectedVaBank.toUpperCase()" class="text-blue-600 font-extrabold"></span>:
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                    Otomatis
                </span>
            </div>

            {{-- VA Number Box --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 flex items-center justify-between gap-2">
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Nomor Pembayaran:</span>
                    <span 
                        class="font-mono text-xl sm:text-2xl font-black text-slate-900 tracking-wider"
                        x-text="selectedVaBank === 'bca' ? '{{ $vaNumberBca }}' : (selectedVaBank === 'mandiri' ? '{{ $vaNumberMandiri }}' : (selectedVaBank === 'bri' ? '{{ $vaNumberBri }}' : '{{ $vaNumberBni }}'))"
                    ></span>
                </div>
                <button 
                    type="button"
                    @click="copy(selectedVaBank === 'bca' ? '{{ $vaNumberBca }}' : (selectedVaBank === 'mandiri' ? '{{ $vaNumberMandiri }}' : (selectedVaBank === 'bri' ? '{{ $vaNumberBri }}' : '{{ $vaNumberBni }}')), 'Nomor VA')"
                    class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition flex items-center gap-1.5 shrink-0 cursor-pointer shadow-xs active:scale-98"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>Salin VA</span>
                </button>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <span>Nama Akun:</span>
                <strong class="text-slate-900">SEVEN KOST - {{ strtoupper($transaction->user?->name ?? 'PENGHUNI') }}</strong>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-600">
                <span>Total Nominal:</span>
                <strong class="text-sm font-black text-blue-600">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
            </div>

            {{-- Panduan Pembayaran VA --}}
            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                <span class="font-bold text-slate-700 block">Panduan Transfer VA:</span>
                <p>1. Buka aplikasi Mobile Banking atau ATM bank pilihan Anda.</p>
                <p>2. Pilih menu <strong>Transfer / Pembayaran &gt; Virtual Account</strong>.</p>
                <p>3. Masukkan nomor Virtual Account di atas dan pastikan nominal tagihan sudah sesuai.</p>
                <p>4. Konfirmasi pembayaran. Transaksi akan otomatis terverifikasi lunas tanpa perlu upload struk.</p>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- TAMPILAN 4: DETAIL TRANSFER BANK MANUAL & FORM BUKTI          --}}
    {{-- ============================================================== --}}
    <div x-show="viewMode === 'manual'" x-transition class="space-y-4" style="display: none;">
        {{-- Header Breadcrumb Ganti Metode --}}
        <div class="flex items-center justify-between px-1">
            <button 
                type="button" 
                @click="backToSelect()" 
                class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1.5 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Ganti Metode Pembayaran</span>
            </button>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TRANSFER BANK MANUAL</span>
        </div>

        {{-- Daftar Rekening Bank Tujuan --}}
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-800 block px-1">Pilih Rekening Bank Tujuan:</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                @foreach($manualBanks as $mb)
                    <div class="bg-white border border-slate-200 rounded-2xl p-3.5 space-y-1 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-blue-700 uppercase">{{ $mb['bank_name'] ?? 'BANK' }}</span>
                            <button 
                                type="button" 
                                @click="copy('{{ $mb['account_number'] ?? '' }}', 'Nomor rekening {{ $mb['bank_name'] ?? '' }}')" 
                                class="text-[10px] font-bold text-blue-600 hover:text-blue-700 cursor-pointer"
                            >
                                Salin Rekening
                            </button>
                        </div>
                        <span class="font-mono text-sm font-black text-slate-900 block">{{ $mb['account_number'] ?? '-' }}</span>
                        <span class="text-[10px] text-slate-500 block truncate">a.n. {{ $mb['account_holder'] ?? 'PT Seven Kost Indonesia' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Formulir Upload Bukti Transfer --}}
        <div class="bg-white rounded-3xl p-5 text-slate-900 space-y-3.5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-xs font-black text-slate-900 uppercase tracking-wider">Konfirmasi Bukti Transfer</span>
                @if($isAwaitingConfirmation)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse">
                        Menunggu Verifikasi
                    </span>
                @endif
            </div>

            {{-- Tampilan Bukti yang Sudah Diunggah --}}
            @if($isManualProofUploaded)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                    <span class="text-[11px] font-bold text-slate-700 block">Bukti Transfer Terunggah:</span>
                    <div class="flex items-center gap-3">
                        <img 
                            src="{{ asset('storage/' . $proofImage) }}" 
                            alt="Bukti Transfer" 
                            class="w-16 h-16 object-cover rounded-xl border border-slate-300 cursor-pointer hover:opacity-90 transition"
                            @click="showProofModal = true"
                        />
                        <div class="text-xs space-y-0.5 flex-1 min-w-0">
                            <p class="font-bold text-slate-800 truncate">Pengirim: {{ $senderName ?: '-' }}</p>
                            <p class="text-slate-500 text-[11px]">Bank: {{ $senderBank ?: '-' }}</p>
                            <p class="text-[10px] text-slate-400">Diunggah: {{ $transaction->updated_at?->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Upload Foto Baru --}}
            @if($uploadRoute)
                <form 
                    action="{{ $uploadRoute }}" 
                    method="POST" 
                    enctype="multipart/form-data" 
                    class="space-y-3"
                >
                    @csrf
                    <div>
                        <label for="proof-file-input" class="text-xs font-bold text-slate-700 block mb-1">
                            Unggah Foto Struk / Bukti Transfer:
                        </label>
                        <input 
                            id="proof-file-input"
                            type="file" 
                            name="payment_proof" 
                            accept="image/jpeg,image/png,image/webp" 
                            required 
                            @change="onFileSelected($event)" 
                            class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1.5 bg-slate-50"
                        />
                        <span class="text-[10px] text-slate-400 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 5MB)</span>
                    </div>

                    {{-- Live Preview Gambar Baru --}}
                    <template x-if="previewUrl">
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3">
                            <img :src="previewUrl" alt="Pratinjau Bukti" class="w-16 h-16 object-cover rounded-lg border border-slate-300">
                            <div class="text-xs text-slate-600">
                                <span class="font-bold text-emerald-600 block">✓ Foto Dipilih</span>
                                <span class="text-[11px] text-slate-400">Klik tombol Simpan Bukti di bawah untuk mengirim.</span>
                            </div>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="sender-name-input" class="text-[11px] font-bold text-slate-700 block mb-1">Nama Pemilik Rekening:</label>
                            <input 
                                id="sender-name-input"
                                type="text" 
                                name="sender_name" 
                                placeholder="Contoh: Budi Santoso" 
                                value="{{ old('sender_name', $senderName) }}"
                                class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2"
                            />
                        </div>
                        <div>
                            <label for="sender-bank-input" class="text-[11px] font-bold text-slate-700 block mb-1">Bank Pengirim:</label>
                            <input 
                                id="sender-bank-input"
                                type="text" 
                                name="sender_bank" 
                                placeholder="Contoh: BCA / Mandiri" 
                                value="{{ old('sender_bank', $senderBank) }}"
                                class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2"
                            />
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-98 text-white font-bold text-xs text-center transition shadow-xs flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>{{ $isManualProofUploaded ? 'Kirim Ulang Bukti Transfer' : 'Kirim Bukti Pembayaran' }}</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- FOOTER AKSI: CEK STATUS & SIMULASI                             --}}
    {{-- ============================================================== --}}
    <div class="pt-4 space-y-2.5">
        <button 
            type="button" 
            @click="checkStatusManual()" 
            :disabled="checking"
            class="w-full py-3 px-4 rounded-2xl bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-xs sm:text-sm border border-slate-200 shadow-2xs transition flex items-center justify-center gap-2 cursor-pointer active:scale-98"
        >
            <svg class="w-4 h-4 text-blue-600" :class="checking ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span x-text="checking ? 'Memeriksa status...' : 'Cek Status Pembayaran'">Cek Status Pembayaran</span>
        </button>

        {{-- Mode Sandbox / Simulasi Cepat (Pengujian Tanpa Bayar Riil) --}}
        @if($simulateRoute)
            <form action="{{ $simulateRoute }}" method="POST" class="pt-1">
                @csrf
                <button 
                    type="submit" 
                    onclick="return confirm('Simulasikan pembayaran lunas instan untuk transaksi ini?')" 
                    class="w-full py-2 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold text-[11px] transition flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Simulasi Pembayaran Lunas (Sandbox/Demo)</span>
                </button>
            </form>
        @endif

        <div class="text-center pt-2 flex items-center justify-center gap-3 text-xs">
            <a href="{{ $backUrl }}" class="font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; {{ $backLabel }}
            </a>
            @if(\Illuminate\Support\Facades\Route::has('tenant.bills.index'))
                <span class="text-slate-300">&bull;</span>
                <a href="{{ route('tenant.bills.index') }}" class="font-bold text-blue-600 hover:text-blue-800 transition">
                    Daftar Tagihan Saya &rarr;
                </a>
            @endif
        </div>
    </div>

    {{-- MODAL PRATINJAU BUKTI PEMBAYARAN FULL --}}
    @if($isManualProofUploaded)
        <div 
            x-show="showProofModal" 
            x-transition 
            class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 backdrop-blur-xs" 
            style="display: none;"
            @click.self="showProofModal = false"
        >
            <div class="bg-white rounded-3xl p-4 max-w-sm w-full space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-800">Bukti Transfer</span>
                    <button type="button" @click="showProofModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>
                <img src="{{ asset('storage/' . $proofImage) }}" alt="Bukti Transfer Penuh" class="w-full max-h-80 object-contain rounded-2xl">
                <button type="button" @click="showProofModal = false" class="w-full py-2 rounded-xl bg-slate-900 text-white font-bold text-xs">
                    Tutup Pratinjau
                </button>
            </div>
        </div>
    @endif

</div>

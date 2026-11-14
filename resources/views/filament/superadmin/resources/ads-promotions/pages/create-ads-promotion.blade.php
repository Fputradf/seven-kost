<x-filament-panels::page>
    {{-- Card gradient kompak pada Tambah Iklan Kos --}}
    <div 
        class="seven-gradient-card"
        style="position: relative !important; overflow: hidden !important; border-radius: 0.875rem !important; background: linear-gradient(135deg, #031d44 0%, #05377a 50%, #0a66c2 100%) !important; color: #ffffff !important; padding: 1rem 1.35rem !important; box-shadow: 0 4px 16px -2px rgba(3, 29, 68, 0.25) !important; border: 1px solid rgba(255, 255, 255, 0.2) !important; margin-bottom: 1rem !important;"
    >
        {{-- Decorative background glow --}}
        <div class="glow-orb-1" style="position: absolute !important; right: -1.5rem !important; bottom: -1.5rem !important; width: 7rem !important; height: 7rem !important; background: radial-gradient(circle, rgba(96, 165, 250, 0.25) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>
        <div class="glow-orb-2" style="position: absolute !important; top: -1.5rem !important; right: 20% !important; width: 6rem !important; height: 6rem !important; background: radial-gradient(circle, rgba(125, 211, 252, 0.2) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>

        <div class="card-content" style="position: relative !important; z-index: 10 !important; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div style="max-width: 42rem;">
                <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem; flex-wrap: wrap;">
                    <span class="status-pill" style="display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 600; background: rgba(255, 255, 255, 0.15); color: #bae6fd; border: 1px solid rgba(255, 255, 255, 0.2);">
                        <span class="status-dot" style="width: 0.45rem; height: 0.45rem; border-radius: 9999px; background-color: #34d399; display: inline-block;"></span>
                        Super Admin
                    </span>
                    <span style="font-size: 0.725rem; color: #bae6fd; font-weight: 500;">• Tambah Iklan Kos</span>
                </div>

                <h1 style="color: #ffffff !important; font-size: 1.25rem !important; font-weight: 700 !important; line-height: 1.35 !important; letter-spacing: 0 !important; margin: 0 0 0.2rem 0 !important;">
                    Tambah Pengajuan <span class="highlight" style="color: #7dd3fc !important;">Iklan Kos</span>
                </h1>

                <p style="color: #e2e8f0 !important; font-size: 0.8125rem !important; line-height: 1.45 !important; letter-spacing: 0.005em !important; margin: 0 !important;">
                    Pilih listing kos dan paket iklan, atur jadwal tayang promosi, serta konfirmasi status pembayaran promosi.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                <a 
                    href="{{ static::getResource()::getUrl('index') }}"
                    class="btn-save-primary"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem; padding: 0.5rem 1rem; border-radius: 0.625rem; background-color: rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; font-size: 0.775rem; font-weight: 600; border: 1px solid rgba(255, 255, 255, 0.25) !important; cursor: pointer; text-decoration: none;"
                >
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Render Form --}}
    {{ $this->content }}
</x-filament-panels::page>

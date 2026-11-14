<x-filament-panels::page>
    {{-- Card gradient pada Pengaturan Aplikasi & Tampilan (Identik dengan layanan.blade.php) --}}
    <div 
        class="seven-gradient-card"
        style="position: relative !important; overflow: hidden !important; border-radius: 1rem !important; background: linear-gradient(135deg, #031d44 0%, #05377a 45%, #0a66c2 100%) !important; color: #ffffff !important; padding: 1.5rem 1.75rem !important; box-shadow: 0 10px 25px -5px rgba(3, 29, 68, 0.4), 0 8px 10px -6px rgba(3, 29, 68, 0.2) !important; border: 1px solid rgba(255, 255, 255, 0.25) !important; margin-bottom: 1.5rem !important;"
    >
        {{-- Decorative background glow --}}
        <div class="glow-orb-1" style="position: absolute !important; right: -2rem !important; bottom: -2rem !important; width: 12rem !important; height: 12rem !important; background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>
        <div class="glow-orb-2" style="position: absolute !important; top: -2rem !important; right: 25% !important; width: 10rem !important; height: 10rem !important; background: radial-gradient(circle, rgba(125, 211, 252, 0.25) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>

        <div class="card-content" style="position: relative !important; z-index: 10 !important;">
            <div style="max-width: 44rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                    <span class="status-pill" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; background: rgba(255, 255, 255, 0.15); color: #bae6fd; border: 1px solid rgba(255, 255, 255, 0.25);">
                        <span class="status-dot" style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #34d399; display: inline-block;"></span>
                        Super Admin
                    </span>
                    <span style="font-size: 0.75rem; color: #bae6fd; font-weight: 500;">• Seven Kost</span>
                </div>

                <h1 style="color: #ffffff !important; font-size: 1.75rem !important; font-weight: 800 !important; line-height: 1.25 !important; margin: 0 0 0.375rem 0 !important;">
                    Pengaturan Aplikasi & <span class="highlight" style="color: #7dd3fc !important;">Tampilan</span>
                </h1>

                <p style="color: #e2e8f0 !important; font-size: 0.875rem !important; line-height: 1.5 !important; margin: 0 !important;">
                    Konfigurasi identitas brand, logo, kontak resmi, media sosial, serta skema biaya layanan & iklan.
                </p>
            </div>

            <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.5rem;">
                <button 
                    type="button" 
                    wire:click="save" 
                    wire:loading.attr="disabled"
                    class="btn-save-primary"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.65rem 1.35rem; border-radius: 0.75rem; background-color: #ffffff !important; color: #031d44 !important; font-size: 0.8125rem; font-weight: 700; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); border: none; cursor: pointer;"
                >
                    <svg wire:loading.remove wire:target="save" style="width: 1rem; height: 1rem; color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg wire:loading wire:target="save" style="width: 1rem; height: 1rem; color: #0284c7; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span>Simpan Perubahan</span>
                </button>

                <div class="badge-info" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; border-radius: 0.5rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.75rem; color: #e2e8f0;">
                    <span style="width: 0.4rem; height: 0.4rem; border-radius: 9999px; background-color: #34d399; display: inline-block;"></span>
                    <span>Auto-Compress Image Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Render Schema Form & Tabs --}}
    {{ $this->content }}

    {{-- Container Tombol Simpan di Bawah Form --}}
    <div 
        class="form-actions-container"
        style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 0; margin-top: 1rem; background: transparent; border: none;"
    >
        <button 
            type="button" 
            wire:click="save" 
            wire:loading.attr="disabled"
            class="btn-save-primary"
            style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.65rem 1.5rem; border-radius: 0.75rem; background-color: #2563eb !important; color: #ffffff !important; font-size: 0.8125rem; font-weight: 700; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); border: none; cursor: pointer; transition: all 0.15s ease;"
        >
            <svg wire:loading.remove wire:target="save" style="width: 1rem; height: 1rem; max-width: 1rem; max-height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <svg wire:loading wire:target="save" style="width: 1rem; height: 1rem; max-width: 1rem; max-height: 1rem; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</x-filament-panels::page>

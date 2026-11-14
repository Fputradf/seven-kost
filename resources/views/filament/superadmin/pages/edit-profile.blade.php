<x-filament-panels::page>
    {{-- Card gradient pada Pengaturan Akun (Identik dengan manage-settings.blade.php) --}}
    <div 
        class="seven-gradient-card"
        style="position: relative !important; overflow: hidden !important; border-radius: 1rem !important; background: linear-gradient(135deg, #031d44 0%, #05377a 45%, #0a66c2 100%) !important; color: #ffffff !important; padding: 1.5rem 1.75rem !important; box-shadow: 0 10px 25px -5px rgba(3, 29, 68, 0.4), 0 8px 10px -6px rgba(3, 29, 68, 0.2) !important; border: 1px solid rgba(255, 255, 255, 0.25) !important; margin-bottom: 1.5rem !important;"
    >
        {{-- Decorative background glow --}}
        <div class="glow-orb-1" style="position: absolute !important; right: -2rem !important; bottom: -2rem !important; width: 12rem !important; height: 12rem !important; background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>
        <div class="glow-orb-2" style="position: absolute !important; top: -2rem !important; right: 25% !important; width: 10rem !important; height: 10rem !important; background: radial-gradient(circle, rgba(125, 211, 252, 0.25) 0%, transparent 70%) !important; border-radius: 9999px !important; pointer-events: none !important;"></div>

        <div class="card-content" style="position: relative !important; z-index: 10 !important; display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <div style="max-width: 44rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                    <span class="status-pill" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; background: rgba(255, 255, 255, 0.15); color: #bae6fd; border: 1px solid rgba(255, 255, 255, 0.25);">
                        <span class="status-dot" style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background-color: #34d399; display: inline-block;"></span>
                        Super Admin
                    </span>
                    <span style="font-size: 0.75rem; color: #bae6fd; font-weight: 500;">• Profil & Kredensial</span>
                </div>

                <h1 style="color: #ffffff !important; font-size: 1.75rem !important; font-weight: 800 !important; line-height: 1.3 !important; letter-spacing: 0 !important; margin: 0 0 0.375rem 0 !important;">
                    Pengaturan <span class="highlight" style="color: #7dd3fc !important;">Akun Administrator</span>
                </h1>

                <p style="color: #e2e8f0 !important; font-size: 0.875rem !important; line-height: 1.6 !important; letter-spacing: 0.005em !important; margin: 0 !important;">
                    Kelola identitas akun, foto profil, informasi kontak darurat, serta perbarui kata sandi sistem Anda.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div class="badge-info" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; border-radius: 0.5rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.75rem; font-weight: 600; color: #e2e8f0;">
                    <span style="width: 0.45rem; height: 0.45rem; border-radius: 9999px; background-color: #34d399; display: inline-block;"></span>
                    <span>Hak Akses Penuh Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Render Schema Form & Tabs --}}
    {{ $this->content }}
</x-filament-panels::page>

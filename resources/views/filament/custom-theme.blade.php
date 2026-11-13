<style>
    /* ==========================================================================
       SEVEN KOST - SUPER ADMIN THEME (INSPIRASI: FOCUS DASHBOARD)
       - Left Column: Full-Height Deep Navy Sidebar (Top 0 to Bottom 100vh)
       - Gradient Direction: Vertikal ke bawah (180deg)
       - Compact Sidebar Width: 15.5rem (248px)
       - Header (Topbar): Terang & Bersih (Pure White #ffffff) di sebelah kanan sidebar
       - Form & Konten: Terang, responsif, tidak tertutup sidebar
       - Hilangkan garis putih/ring di sudut atas kiri
       ========================================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

    /* Typography: Modern, Smooth & Friendly Plus Jakarta Sans (Serasi dengan Form Utama) */
    body, .fi-body, .fi-sidebar, .fi-topbar, .fi-main, button, input, optgroup, select, textarea {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        letter-spacing: -0.01em;
    }

    /* Hilangkan semua outline/ring bawaan yang menimbulkan garis putih */
    .fi-topbar,
    .fi-sidebar,
    #fi-main-sidebar,
    .fi-sidebar-header-ctn,
    .fi-sidebar-header,
    .fi-topbar-ctn {
        --tw-ring-shadow: 0 0 #0000 !important;
        --tw-ring-color: transparent !important;
        --tw-ring-offset-shadow: 0 0 #0000 !important;
        outline: none !important;
        border-top: none !important;
    }

    /* ==========================================================================
       1. SIDEBAR KIRI (FULL HEIGHT DARI ATAS KE BAWAH)
       ========================================================================== */
    @media (min-width: 1024px) {
        .fi-sidebar,
        aside.fi-sidebar,
        #fi-main-sidebar {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            inset-inline-start: 0 !important;
            height: 100dvh !important;
            max-height: 100dvh !important;
            width: var(--sidebar-width, 15.5rem) !important;
            z-index: 40 !important;
            background: linear-gradient(180deg, #031d44 0%, #05377a 40%, #031d44 75%, #020b1f 100%) !important;
            border-inline-end: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-top: none !important;
            box-shadow: 4px 0 24px rgba(3, 29, 68, 0.12) !important;
        }

        /* Sidebar Header (Menampilkan Logo Seven Kost di Ujung Atas Sidebar) */
        .fi-sidebar-header-ctn {
            display: block !important;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .fi-sidebar-header {
            display: flex !important;
            height: var(--topbar-height, 4rem) !important;
            align-items: center !important;
            justify-content: flex-start !important;
            padding-inline: 1rem !important;
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        .fi-sidebar-header-logo-ctn {
            display: flex !important;
            align-items: center !important;
        }

        /* Sembunyikan duplikasi logo di topbar */
        .fi-topbar-start .fi-logo {
            display: none !important;
        }

        /* ==========================================================================
           2. TOPBAR HEADER (TERANG / PUTIH BERSIH DI KANAN SIDEBAR)
           ========================================================================== */
        .fi-topbar-ctn {
            position: sticky !important;
            top: 0 !important;
            z-index: 30 !important;
            margin-inline-start: var(--sidebar-width, 15.5rem) !important;
            width: calc(100% - var(--sidebar-width, 15.5rem)) !important;
        }

        .fi-topbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04) !important;
            height: var(--topbar-height, 4rem) !important;
            padding-inline: 1.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        /* Guard: Batasi ukuran seluruh SVG di dalam topbar agar tidak pernah membesar liar */
        .fi-topbar svg {
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            display: inline-block !important;
            flex-shrink: 0 !important;
        }

        /* Tombol icon di topbar header berwarna abu-abu gelap profesional */
        .fi-topbar .fi-icon-btn,
        .fi-topbar-open-sidebar-btn,
        .fi-topbar-close-sidebar-btn {
            color: #475569 !important;
        }

        /* Hapus tombol persempit / collapse sidebar di header */
        .fi-topbar-open-collapse-sidebar-btn,
        .fi-topbar-close-collapse-sidebar-btn,
        .fi-topbar-collapse-sidebar-btn,
        .fi-topbar-start .fi-icon-btn[aria-label*="Persempit"],
        .fi-topbar-start .fi-icon-btn[aria-label*="Collapse"],
        .fi-topbar-start .fi-icon-btn[title*="Persempit"],
        .fi-topbar-start .fi-icon-btn[title*="Collapse"] {
            display: none !important;
        }

        .fi-topbar .fi-icon-btn:hover {
            color: #1e293b !important;
            background-color: #f1f5f9 !important;
        }

        /* Topbar Right: Profile Navbar & Actions */
        .fi-topbar-end {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            margin-inline-start: auto !important;
        }

        /* User Menu Trigger: Circular Avatar with Clean Border */
        .fi-topbar .fi-user-menu-trigger {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 2px !important;
            border-radius: 9999px !important;
            background: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
        }

        .fi-topbar .fi-user-menu-trigger:hover {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
            transform: translateY(-1px) !important;
        }

        .fi-topbar .fi-user-menu-trigger:active {
            transform: translateY(0) !important;
        }

        .fi-topbar .fi-user-menu-trigger .fi-avatar,
        .fi-topbar .fi-user-menu-trigger img {
            width: 2.25rem !important;
            height: 2.25rem !important;
            border-radius: 9999px !important;
            display: block !important;
        }

        /* Dropdown Menu Panel (Modern, Sleek & Floating) */
        .fi-dropdown-panel {
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 14px 35px -5px rgba(3, 29, 68, 0.14), 0 6px 12px -3px rgba(0, 0, 0, 0.06) !important;
            overflow: hidden !important;
            background: #ffffff !important;
            padding: 0.375rem !important;
            margin-top: 0.5rem !important;
        }

        .fi-dropdown-list-item {
            border-radius: 0.625rem !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            transition: all 0.15s ease !important;
            padding: 0.5rem 0.75rem !important;
            color: #334155 !important;
        }

        .fi-dropdown-list-item:hover {
            background: #f1f5f9 !important;
            color: #031d44 !important;
        }

        .fi-dropdown-header {
            padding: 0.75rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            margin-bottom: 0.25rem !important;
            background: #f8fafc !important;
            border-radius: 0.625rem !important;
        }

        /* ==========================================================================
           3. KONTEN & FORMULIR UTAMA (TIDAK TERTUTUP SIDEBAR)
           ========================================================================== */
        .fi-main-ctn {
            margin-inline-start: var(--sidebar-width, 15.5rem) !important;
            width: calc(100% - var(--sidebar-width, 15.5rem)) !important;
            max-width: calc(100% - var(--sidebar-width, 15.5rem)) !important;
            min-height: calc(100dvh - var(--topbar-height, 4rem)) !important;
        }

        /* Mode Sidebar Terlipat (Collapsed) */
        .fi-body-has-sidebar-collapsible-on-desktop.fi-sidebar-closed .fi-sidebar,
        .fi-body-has-sidebar-collapsible-on-desktop.fi-sidebar-closed aside.fi-sidebar,
        .fi-body-has-sidebar-collapsible-on-desktop.fi-sidebar-closed #fi-main-sidebar {
            width: var(--sidebar-collapsed-width, 4.5rem) !important;
        }

        .fi-body-has-sidebar-collapsible-on-desktop.fi-sidebar-closed .fi-topbar-ctn,
        .fi-body-has-sidebar-collapsible-on-desktop.fi-sidebar-closed .fi-main-ctn {
            margin-inline-start: var(--sidebar-collapsed-width, 4.5rem) !important;
            width: calc(100% - var(--sidebar-collapsed-width, 4.5rem)) !important;
            max-width: calc(100% - var(--sidebar-collapsed-width, 4.5rem)) !important;
        }
    }

    /* Tampilan Mobile */
    @media (max-width: 1023px) {
        .fi-topbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .fi-sidebar {
            background: linear-gradient(180deg, #031d44 0%, #05377a 40%, #031d44 75%, #020b1f 100%) !important;
        }

        .fi-sidebar-header {
            display: flex !important;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
    }

    /* Brand Logo Ukuran Proporsional & Transparan Tanpa Lapisan Warna */
    .fi-logo img,
    .fi-sidebar-header-logo-ctn img {
        height: 2rem !important;
        width: 2rem !important;
        max-height: 2rem !important;
        max-width: 2rem !important;
        object-fit: contain !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Scrollbar halus di sidebar */
    .fi-sidebar,
    .fi-sidebar-nav {
        scrollbar-width: thin !important;
        scrollbar-color: rgba(255, 255, 255, 0.15) transparent !important;
    }
    .fi-sidebar::-webkit-scrollbar,
    .fi-sidebar-nav::-webkit-scrollbar {
        width: 4px !important;
    }
    .fi-sidebar::-webkit-scrollbar-thumb,
    .fi-sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15) !important;
        border-radius: 9999px !important;
    }

    /* Label Grup Navigasi di Sidebar (Misal: MANAJEMEN PENGGUNA & AKSES) */
    .fi-sidebar-group-label,
    .fi-sidebar-group-label span,
    .fi-sidebar-group-label button {
        color: #93c5fd !important;
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
    }

    .fi-sidebar-group-collapse-btn {
        color: #93c5fd !important;
    }

    /* Item Navigasi Link di Sidebar (Kompak, Bersih & Elegan) */
    .fi-sidebar-item-btn {
        border-radius: 0.5rem !important;
        margin: 0.1rem 0.375rem !important;
        padding: 0.45rem 0.65rem !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-sidebar-item-btn:hover {
        background: rgba(255, 255, 255, 0.08) !important;
    }

    /* Teks Label Item Navigasi */
    .fi-sidebar-item-label {
        color: #e2e8f0 !important;
        font-weight: 500 !important;
        font-size: 0.8125rem !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-label {
        color: #ffffff !important;
    }

    /* Icon Item Navigasi */
    .fi-sidebar-item-icon,
    .fi-sidebar-item-btn .fi-icon {
        color: #94a3b8 !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
    .fi-sidebar-item-btn:hover .fi-icon {
        color: #60a5fa !important;
    }

    /* Item Aktif (Pill Biru SevenKost Terang seperti contoh Focus) */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
    .fi-sidebar-item-btn.fi-active {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        box-shadow: 0 4px 12px -1px rgba(37, 99, 235, 0.4) !important;
    }

    .fi-sidebar-item.fi-active .fi-sidebar-item-label,
    .fi-sidebar-item-btn.fi-active .fi-sidebar-item-label {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .fi-sidebar-item.fi-active .fi-sidebar-item-icon,
    .fi-sidebar-item.fi-active .fi-icon,
    .fi-sidebar-item-btn.fi-active .fi-icon {
        color: #ffffff !important;
    }

    /* ==========================================================================
       4. KONTEN & FORMULIR (Latar Terang & Bersih)
       ========================================================================== */
    .fi-body {
        background-color: #f8fafc !important;
    }

    .fi-main-ctn {
        background-color: #f8fafc !important;
    }

    /* Card Containers */
    .fi-section,
    .fi-ta-ctn,
    .fi-wi-stats-overview-stat {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04) !important;
    }

    /* Input Fields */
    .fi-input-wrp {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
    }

    .fi-input-wrp:focus-within {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    /* Tombol Utama */
    .fi-btn-color-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3) !important;
        font-weight: 600 !important;
    }

    .fi-btn-color-primary:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4) !important;
    }

    /* ==========================================================================
       5. CARD GRADIENT MODERN (IDENTIK DENGAN LAYANAN.BLADE.PHP)
       ========================================================================== */
    .seven-gradient-card {
        position: relative !important;
        overflow: hidden !important;
        border-radius: 1rem !important;
        background: linear-gradient(135deg, #031d44 0%, #05377a 45%, #0a66c2 100%) !important;
        color: #ffffff !important;
        padding: 1.5rem 1.75rem !important;
        box-shadow: 0 10px 25px -5px rgba(3, 29, 68, 0.4), 0 8px 10px -6px rgba(3, 29, 68, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        margin-bottom: 1.5rem !important;
    }

    .seven-gradient-card .glow-orb-1 {
        position: absolute !important;
        right: -2rem !important;
        bottom: -2rem !important;
        width: 12rem !important;
        height: 12rem !important;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.25) 0%, transparent 70%) !important;
        border-radius: 9999px !important;
        pointer-events: none !important;
    }

    .seven-gradient-card .glow-orb-2 {
        position: absolute !important;
        top: -2rem !important;
        right: 25% !important;
        width: 10rem !important;
        height: 10rem !important;
        background: radial-gradient(circle, rgba(125, 211, 252, 0.2) 0%, transparent 70%) !important;
        border-radius: 9999px !important;
        pointer-events: none !important;
    }

    .seven-gradient-card .card-content {
        position: relative !important;
        z-index: 10 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 1.25rem !important;
    }

    @media (min-width: 1024px) {
        .seven-gradient-card .card-content {
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
    }

    .seven-gradient-card h1 {
        color: #ffffff !important;
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        margin: 0 !important;
    }

    .seven-gradient-card h1 .highlight {
        color: #7dd3fc !important;
    }

    .seven-gradient-card p {
        color: #e2e8f0 !important;
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
        margin-top: 0.375rem !important;
        max-width: 44rem !important;
    }

    .seven-gradient-card .status-pill {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        padding: 0.25rem 0.75rem !important;
        border-radius: 9999px !important;
        font-size: 0.6875rem !important;
        font-weight: 700 !important;
        background: rgba(255, 255, 255, 0.15) !important;
        color: #bae6fd !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(4px) !important;
    }

    .seven-gradient-card .status-dot {
        width: 0.5rem !important;
        height: 0.5rem !important;
        border-radius: 9999px !important;
        background-color: #34d399 !important;
    }

    .seven-gradient-card .btn-save-primary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        padding: 0.65rem 1.35rem !important;
        border-radius: 0.75rem !important;
        background-color: #ffffff !important;
        color: #031d44 !important;
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
        border: none !important;
        text-decoration: none !important;
    }

    .seven-gradient-card .btn-save-primary:hover {
        background-color: #f1f5f9 !important;
        color: #020b1f !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
    }

    .fi-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 65%, #eff6ff 100%) !important;
        border: 1px solid #dbeafe !important;
        border-radius: 0.875rem !important;
        box-shadow: 0 4px 16px -2px rgba(37, 99, 235, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.2s ease-in-out !important;
    }

    .fi-section:hover {
        border-color: #bfdbfe !important;
        box-shadow: 0 8px 24px -2px rgba(37, 99, 235, 0.08) !important;
    }

    .fi-section-header-heading {
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 1.05rem !important;
    }

    .fi-section-header-description {
        color: #64748b !important;
        font-size: 0.8125rem !important;
    }

    /* ==========================================================================
       CONTAINER LOGO & FAVICON (CLEAN & ELEGANT)
       ========================================================================== */
    .logo-light-uploader .filepond--panel-root {
        background-color: #ffffff !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s ease !important;
    }
    .logo-light-uploader:hover .filepond--panel-root {
        border-color: #3b82f6 !important;
        background-color: #f8fafc !important;
    }

    .logo-dark-uploader .filepond--panel-root {
        background: linear-gradient(135deg, #031d44 0%, #05377a 100%) !important;
        border: 1.5px dashed rgba(56, 189, 248, 0.4) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 4px 12px rgba(3, 29, 68, 0.15) !important;
        transition: all 0.2s ease !important;
    }
    .logo-dark-uploader:hover .filepond--panel-root {
        border-color: #38bdf8 !important;
        background: linear-gradient(135deg, #05295f 0%, #074b9e 100%) !important;
    }
    .logo-dark-uploader .filepond--drop-label {
        color: #bae6fd !important;
    }

    .favicon-uploader .filepond--panel-root {
        background-color: #ffffff !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s ease !important;
    }
    .favicon-uploader:hover .filepond--panel-root {
        border-color: #3b82f6 !important;
        background-color: #f8fafc !important;
    }
</style>
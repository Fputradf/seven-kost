<style>
    /* ==========================================================================
       SEVEN KOST - SUPER ADMIN THEME (REFINED EXECUTIVE DESIGN)
       - Font: Geist (Modern, Ultra-Sleek, Fluid & High-Craft Geometric Sans)
       - Style: Professional Crafted SaaS Dashboard (Anti AI-Slop)
       - Full-Height Deep Navy Sidebar (Top 0 to Bottom 100vh)
       - Topbar: Pure White (#ffffff) with crisp 1px border
       - Header Card: Sophisticated Midnight Navy (No tacky orbs/glows)
       - Forms & Inputs: High-craft, tactile, balanced spacing
       ========================================================================== */

    @import url('https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    /* Universal SVG Safety Guard */
    svg {
        max-width: 100%;
    }
    .fi-topbar svg,
    .fi-topbar-ctn svg,
    .fi-user-menu svg,
    .fi-user-menu-trigger svg,
    .fi-dropdown svg,
    .fi-icon-btn svg,
    .fi-header svg {
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
        flex-shrink: 0 !important;
    }

    /* Pastikan spinner loading tombol tidak tampil saat idle */
    .fi-btn .fi-loading-indicator:not([wire\:loading]),
    .fi-btn [wire\:loading]:not([wire\:target]),
    .fi-btn [x-cloak] {
        display: none !important;
    }

    /* ==========================================================================
       TYPOGRAPHY (GEIST: ULTRA-MODERN, CLEAN, SLEEK & FLUID)
       ========================================================================== */
    body, .fi-body, .fi-sidebar, .fi-topbar, .fi-main, button, input, optgroup, select, textarea, table, thead, tbody, tr, th, td {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        letter-spacing: -0.012em !important;
        line-height: 1.55 !important;
    }

    /* Headings: Sleek, punchy negative tracking for modern high-end feel */
    h1, h2, h3, h4, .fi-header-heading, .fi-section-header-heading {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-weight: 600 !important;
        letter-spacing: -0.025em !important;
        color: #0f172a !important;
    }

    /* Form Labels: Understated, clean, easy to scan */
    .fi-fo-field-wrp-label,
    .fi-fo-field-wrp-label label,
    label {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        letter-spacing: -0.01em !important;
        font-weight: 500 !important;
        font-size: 0.8125rem !important;
        color: #334155 !important;
        line-height: 1.5 !important;
        margin-bottom: 0.35rem !important;
    }

    .fi-fo-field-wrp-helper-text,
    .fi-fo-field-wrp-helper-text p {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        letter-spacing: -0.005em !important;
        line-height: 1.5 !important;
        color: #64748b !important;
        font-size: 0.775rem !important;
        margin-top: 0.35rem !important;
    }

    input, select, textarea {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        letter-spacing: -0.005em !important;
        line-height: 1.5 !important;
        font-size: 0.875rem !important;
        color: #0f172a !important;
    }

    .fi-tabs-item-label {
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        letter-spacing: -0.01em !important;
        font-weight: 500 !important;
    }

    /* Hilangkan semua outline/ring bawaan yang menimbulkan garis putih atau garis ganda yang merusak UI */
    .fi-topbar,
    .fi-sidebar,
    #fi-main-sidebar,
    .fi-sidebar-header-ctn,
    .fi-sidebar-header,
    .fi-topbar-ctn,
    .fi-section,
    .fi-section-content-ctn,
    .fi-section-content,
    .fi-card,
    .fi-input-wrp,
    .fi-fo-field,
    .fi-fo-field-content-col,
    .fi-fo-field-content-ctn,
    .fi-fo-field-content,
    .fi-sc,
    .fi-sc-component,
    .fi-wi-stats-overview-stat,
    .fi-wi-chart {
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
            background: linear-gradient(180deg, #091322 0%, #0d1e38 50%, #091322 100%) !important;
            border-inline-end: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-top: none !important;
            box-shadow: 4px 0 24px rgba(9, 19, 34, 0.12) !important;
        }

        /* Sidebar Header */
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
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
            height: var(--topbar-height, 4rem) !important;
            padding-inline: 1.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .fi-topbar svg {
            max-width: 1.25rem !important;
            max-height: 1.25rem !important;
            display: inline-block !important;
            flex-shrink: 0 !important;
        }

        .fi-topbar .fi-icon-btn,
        .fi-topbar-open-sidebar-btn,
        .fi-topbar-close-sidebar-btn {
            color: #475569 !important;
        }

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
            color: #0f172a !important;
            background-color: #f1f5f9 !important;
        }

        /* Topbar Right */
        .fi-topbar-end {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            margin-inline-start: auto !important;
        }

        .fi-topbar .fi-user-menu-trigger {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 2px !important;
            border-radius: 9999px !important;
            background: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
        }

        .fi-topbar .fi-user-menu-trigger:hover {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }

        .fi-topbar .fi-user-menu-trigger .fi-avatar,
        .fi-topbar .fi-user-menu-trigger img {
            width: 2.25rem !important;
            height: 2.25rem !important;
            border-radius: 9999px !important;
            display: block !important;
        }

        /* Dropdown Menu Panel */
        .fi-dropdown-panel {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.04) !important;
            overflow: hidden !important;
            background: #ffffff !important;
            padding: 0.375rem !important;
            margin-top: 0.5rem !important;
        }

        .fi-dropdown-list-item {
            border-radius: 0.5rem !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            transition: all 0.15s ease !important;
            padding: 0.5rem 0.75rem !important;
            color: #334155 !important;
        }

        .fi-dropdown-list-item:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
        }

        .fi-dropdown-header {
            padding: 0.75rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            margin-bottom: 0.25rem !important;
            background: #f8fafc !important;
            border-radius: 0.5rem !important;
        }

        /* ==========================================================================
           3. KONTEN & FORMULIR UTAMA
           ========================================================================== */
        .fi-main-ctn {
            margin-inline-start: var(--sidebar-width, 15.5rem) !important;
            width: calc(100% - var(--sidebar-width, 15.5rem)) !important;
            max-width: calc(100% - var(--sidebar-width, 15.5rem)) !important;
            min-height: calc(100dvh - var(--topbar-height, 4rem)) !important;
        }

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
            background: linear-gradient(180deg, #091322 0%, #0d1e38 50%, #091322 100%) !important;
        }

        .fi-sidebar-header {
            display: flex !important;
            background: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
    }

    /* Brand Logo */
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
        scrollbar-color: rgba(255, 255, 255, 0.12) transparent !important;
    }
    .fi-sidebar::-webkit-scrollbar,
    .fi-sidebar-nav::-webkit-scrollbar {
        width: 4px !important;
    }
    .fi-sidebar::-webkit-scrollbar-thumb,
    .fi-sidebar-nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.12) !important;
        border-radius: 9999px !important;
    }

    /* Label Grup Navigasi di Sidebar */
    .fi-sidebar-group-label,
    .fi-sidebar-group-label span,
    .fi-sidebar-group-label button {
        color: #94a3b8 !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.65rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
    }

    .fi-sidebar-group-collapse-btn {
        color: #94a3b8 !important;
    }

    /* Item Navigasi Link di Sidebar */
    .fi-sidebar-item-btn {
        border-radius: 0.5rem !important;
        margin: 0.1rem 0.375rem !important;
        padding: 0.45rem 0.65rem !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-sidebar-item-btn:hover {
        background: rgba(255, 255, 255, 0.06) !important;
    }

    .fi-sidebar-item-label {
        color: #cbd5e1 !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 500 !important;
        font-size: 0.8125rem !important;
        letter-spacing: -0.01em !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-label {
        color: #ffffff !important;
    }

    .fi-sidebar-item-icon,
    .fi-sidebar-item-btn .fi-icon {
        color: #94a3b8 !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
    .fi-sidebar-item-btn:hover .fi-icon {
        color: #60a5fa !important;
    }

    /* Item Aktif Sidebar */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
    .fi-sidebar-item-btn.fi-active {
        background: #1d4ed8 !important;
        box-shadow: 0 2px 6px rgba(29, 78, 216, 0.25) !important;
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
       4. KONTEN & FORMULIR (Latar Bersih, Tenang & Elegan)
       ========================================================================== */
    .fi-body {
        background-color: #f8fafc !important;
    }

    .fi-main-ctn {
        background-color: #f8fafc !important;
    }

    /* Card Containers */
    .fi-section {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
    }

    /* ==========================================================================
       DASHBOARD STAT CARDS TYPOGRAPHY & DESIGN (PLUS JAKARTA SANS)
       - Sleek, modern, fluid typography hierarchy
       - Clean tabular figures (anti-stiff, no clumsy blocky numbers)
       - Subdued high-contrast labels and soft descriptions
       ========================================================================== */
    .fi-wi-stats-overview-stat {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
        padding: 1.15rem 1.25rem !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03) !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .fi-wi-stats-overview-stat:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.06) !important;
    }

    /* Stat Label */
    .fi-wi-stats-overview-stat-label,
    .fi-wi-stats-overview-stat-label-ctn span {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        color: #64748b !important;
        letter-spacing: -0.01em !important;
        line-height: 1.4 !important;
    }

    /* Stat Value (Modern Fluid Tabular Figures) */
    .fi-wi-stats-overview-stat-value {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 1.55rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        letter-spacing: -0.03em !important;
        line-height: 1.25 !important;
        margin-top: 0.3rem !important;
        margin-bottom: 0.3rem !important;
        font-variant-numeric: tabular-nums !important;
    }

    /* Stat Description */
    .fi-wi-stats-overview-stat-description,
    .fi-wi-stats-overview-stat-description span {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 0.75rem !important;
        font-weight: 400 !important;
        color: #64748b !important;
        letter-spacing: -0.005em !important;
        line-height: 1.45 !important;
    }

    /* ==========================================================================
       DASHBOARD UNIFIED CHART WIDGET (CLEAN, FLUID EXECUTIVE ANALYTICS)
       ========================================================================== */
    .fi-wi-chart {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03) !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        width: 100% !important;
    }

    .fi-wi-chart:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.06) !important;
    }

    .fi-wi-chart .fi-section-header {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 1.15rem 1.35rem !important;
    }

    .fi-wi-chart .fi-section-header-heading {
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 1rem !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        letter-spacing: -0.015em !important;
    }

    .fi-wi-chart .fi-wi-chart-filter select {
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        border-radius: 0.5rem !important;
        border-color: #cbd5e1 !important;
        color: #334155 !important;
        padding-inline: 0.75rem !important;
    }

    /* Input Fields */
    .fi-input-wrp {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
        transition: all 0.15s ease !important;
    }

    .fi-input-wrp:focus-within {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12) !important;
    }

    /* Tombol Utama (Solid Executive Navy-Blue, No Tacky Neon Glow) */
    .fi-btn-color-primary {
        background: #1d4ed8 !important;
        background-color: #1d4ed8 !important;
        border: 1px solid #1d4ed8 !important;
        color: #ffffff !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px rgba(29, 78, 216, 0.15) !important;
        font-weight: 500 !important;
        font-size: 0.8125rem !important;
        transition: all 0.15s ease !important;
    }

    .fi-btn-color-primary:hover {
        background: #1e40af !important;
        background-color: #1e40af !important;
        border-color: #1e40af !important;
        box-shadow: 0 2px 4px rgba(29, 78, 216, 0.25) !important;
    }

    /* ==========================================================================
       5. HEADER CARD GRADIENT MODERN (ANTI-SLOP: REFINED MIDNIGHT EXECUTIVE)
       - Meniadakan orbs glow yang terasa generik / buatan AI
       - Menghadirkan kedalaman navy eksekutif yang berwibawa & tajam
       ========================================================================== */
    .seven-gradient-card {
        position: relative !important;
        overflow: hidden !important;
        border-radius: 0.875rem !important;
        background: linear-gradient(135deg, #091322 0%, #0d1e38 50%, #112646 100%) !important;
        color: #ffffff !important;
        padding: 1.15rem 1.5rem !important;
        box-shadow: 0 4px 20px -2px rgba(9, 19, 34, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        margin-bottom: 1rem !important;
    }

    /* Hapus orbs glow blur yang membuat kesan AI Slop */
    .seven-gradient-card .glow-orb-1,
    .seven-gradient-card .glow-orb-2 {
        display: none !important;
    }

    .seven-gradient-card .card-content {
        position: relative !important;
        z-index: 10 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.75rem !important;
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
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        line-height: 1.35 !important;
        letter-spacing: -0.025em !important;
        margin: 0 !important;
    }

    .seven-gradient-card h1 .highlight {
        color: #38bdf8 !important;
        font-weight: 600 !important;
    }

    .seven-gradient-card p {
        color: #94a3b8 !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.8125rem !important;
        line-height: 1.5 !important;
        letter-spacing: -0.005em !important;
        font-weight: 400 !important;
        margin-top: 0.25rem !important;
        max-width: 44rem !important;
    }

    /* Status Pill: Refined & Tactile Badge (Bukan AI Glassmorphism Berlebihan) */
    .seven-gradient-card .status-pill {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.35rem !important;
        padding: 0.2rem 0.6rem !important;
        border-radius: 6px !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.7rem !important;
        font-weight: 500 !important;
        letter-spacing: -0.01em !important;
        background: rgba(255, 255, 255, 0.07) !important;
        color: #cbd5e1 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: none !important;
    }

    .seven-gradient-card .status-dot {
        width: 6px !important;
        height: 6px !important;
        border-radius: 9999px !important;
        background-color: #10b981 !important;
        display: inline-block !important;
    }

    /* Tombol Aksi di Header Card */
    .seven-gradient-card .btn-save-primary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.5rem !important;
        background-color: #ffffff !important;
        color: #0f172a !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        letter-spacing: -0.01em !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
        border: 1px solid #e2e8f0 !important;
        text-decoration: none !important;
    }

    .seven-gradient-card .btn-save-primary:hover {
        background-color: #f8fafc !important;
        color: #020617 !important;
        border-color: #cbd5e1 !important;
        transform: none !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08) !important;
    }

    .fi-section {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.2s ease-in-out !important;
        overflow: hidden !important;
    }

    .fi-section:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.04) !important;
    }

    /* Hilangkan semua garis panjang pemisah internal yang memotong card/container di seluruh form */
    .fi-section > .fi-section-content-ctn,
    .fi-section-has-header > .fi-section-content-ctn,
    .fi-section-content-ctn,
    .fi-tabs.fi-contained,
    .fi-tabs-header,
    .fi-fieldset {
        border-top: none !important;
        border-bottom: none !important;
    }

    /* Mencegah card di dalam card (nested section) membuat garis ganda yang merusak UI */
    .fi-section .fi-section {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        background: transparent !important;
    }

    .fi-section-header {
        border: none !important;
        border-bottom: none !important;
        padding: 1.25rem 1.35rem 0.5rem 1.35rem !important;
    }

    .fi-section-content {
        padding: 1.25rem 1.35rem !important;
    }

    /* Hilangkan garis border tak terduga pada container field form */
    .fi-fo-field,
    .fi-fo-field-content-col,
    .fi-fo-field-content-ctn,
    .fi-fo-field-content {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Avatar Upload Center */
    .fi-fo-file-upload .filepond--root[data-style-panel-layout~='circle'] {
        margin-inline: auto !important;
    }
    .fi-fo-file-upload .filepond--drop-label {
        color: #64748b !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
    }
    .fi-fo-file-upload .filepond--panel-root {
        border: 2px dashed #cbd5e1 !important;
        background: #f8fafc !important;
        transition: all 0.2s ease !important;
    }
    .fi-fo-file-upload:hover .filepond--panel-root {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
    }

    .fi-section-header-heading {
        color: #0f172a !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        letter-spacing: -0.015em !important;
        line-height: 1.4 !important;
    }

    .fi-section-header-description {
        color: #64748b !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-size: 0.8125rem !important;
        letter-spacing: -0.005em !important;
        line-height: 1.5 !important;
        margin-top: 0.25rem !important;
    }

    .fi-section-footer {
        border: none !important;
        border-top: none !important;
        border-bottom: none !important;
        padding: 0.75rem 1.35rem 1.25rem 1.35rem !important;
        background: transparent !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 0.75rem !important;
        width: 100% !important;
    }

    .fi-section-footer > *,
    .fi-section-footer .fi-sc,
    .fi-section-footer .fi-sc-actions-ctn,
    .fi-section-footer .fi-sc-actions {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        width: 100% !important;
        margin-inline-start: auto !important;
    }

    /* Container Logo & Favicon */
    .logo-light-uploader .filepond--panel-root {
        background-color: #ffffff !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
        transition: all 0.2s ease !important;
    }
    .logo-light-uploader:hover .filepond--panel-root {
        border-color: #3b82f6 !important;
        background-color: #f8fafc !important;
    }

    .logo-dark-uploader .filepond--panel-root {
        background: #0f172a !important;
        border: 1.5px dashed rgba(56, 189, 248, 0.3) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.1) !important;
        transition: all 0.2s ease !important;
    }
    .logo-dark-uploader:hover .filepond--panel-root {
        border-color: #38bdf8 !important;
        background: #162447 !important;
    }
    .logo-dark-uploader .filepond--drop-label {
        color: #bae6fd !important;
    }

    .favicon-uploader .filepond--panel-root {
        background-color: #ffffff !important;
        border: 1.5px dashed #cbd5e1 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
        transition: all 0.2s ease !important;
    }
    .favicon-uploader:hover .filepond--panel-root {
        border-color: #3b82f6 !important;
        background-color: #f8fafc !important;
    }

    /* ==========================================================================
       CONTAINER FORM ACTIONS FOOTER (CARD MANDIRI YANG RAPI DI KIRI BAWAH)
       ========================================================================== */
    .fi-resource-edit-record-page .fi-sc-form > div:last-child > .fi-ac,
    .fi-resource-edit-record-page .fi-sc-form > .fi-sc-schema:last-child > .fi-ac,
    .fi-resource-create-record-page .fi-sc-form > div:last-child > .fi-ac,
    .fi-resource-create-record-page .fi-sc-form > .fi-sc-schema:last-child > .fi-ac,
    .fi-sc-form > .fi-sc-schema:last-child > .fi-ac,
    .fi-page-sub-navigation-content-ctn .fi-ac {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
        padding: 1rem 1.25rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.75rem !important;
        margin-top: 1.25rem !important;
        width: 100% !important;
    }

    .fi-resource-edit-record-page .fi-ac .fi-btn,
    .fi-resource-edit-record-page .fi-ac button,
    .fi-resource-create-record-page .fi-ac .fi-btn,
    .fi-resource-create-record-page .fi-ac button {
        margin: 0 !important;
    }

    .fi-resource-edit-record-page .fi-page-header-actions,
    .fi-resource-create-record-page .fi-page-header-actions {
        display: none !important;
    }

    /* Samakan ukuran panjang/lebar seluruh formulir dan card agar konsisten 100% penuh dengan tabel */
    .fi-resource-create-record-page,
    .fi-resource-edit-record-page,
    .fi-resource-create-record-page .fi-page-header-main-ctn,
    .fi-resource-edit-record-page .fi-page-header-main-ctn,
    .fi-resource-create-record-page .fi-page-main,
    .fi-resource-edit-record-page .fi-page-main,
    .fi-resource-create-record-page .fi-page-content,
    .fi-resource-edit-record-page .fi-page-content,
    .fi-resource-create-record-page .fi-sc-form,
    .fi-resource-edit-record-page .fi-sc-form,
    .fi-resource-create-record-page .fi-sc-form > div,
    .fi-resource-edit-record-page .fi-sc-form > div,
    .fi-resource-create-record-page .fi-sc-form > .fi-sc,
    .fi-resource-edit-record-page .fi-sc-form > .fi-sc,
    .fi-resource-create-record-page .fi-section-content,
    .fi-resource-edit-record-page .fi-section-content,
    .fi-resource-create-record-page .fi-section-content-ctn,
    .fi-resource-edit-record-page .fi-section-content-ctn,
    .fi-resource-create-record-page .fi-section,
    .fi-resource-edit-record-page .fi-section,
    .fi-resource-create-record-page .seven-gradient-card,
    .fi-resource-edit-record-page .seven-gradient-card,
    .fi-resource-marketplace-items .fi-section,
    .fi-resource-marketplace-items .fi-sc-form,
    .fi-resource-kosts .fi-section,
    .fi-resource-kosts .fi-sc-form,
    .fi-resource-reports .fi-section,
    .fi-resource-reports .fi-sc-form {
        width: 100% !important;
        max-width: 100% !important;
        grid-column: 1 / -1 !important;
    }

    /* Pastikan seluruh field form memanjang penuh ke samping kanan (anti-terjepit / anti-persempit) */
    .fi-resource-create-record-page .fi-sc-component,
    .fi-resource-edit-record-page .fi-sc-component,
    .fi-resource-create-record-page .fi-grid,
    .fi-resource-edit-record-page .fi-grid,
    .fi-resource-create-record-page .fi-fo-field,
    .fi-resource-edit-record-page .fi-fo-field,
    .fi-resource-create-record-page .fi-fo-field-content-col,
    .fi-resource-edit-record-page .fi-fo-field-content-col,
    .fi-resource-create-record-page .fi-fo-field-content-ctn,
    .fi-resource-edit-record-page .fi-fo-field-content-ctn,
    .fi-resource-create-record-page .fi-fo-field-content,
    .fi-resource-edit-record-page .fi-fo-field-content,
    .fi-resource-create-record-page .fi-input-wrp,
    .fi-resource-edit-record-page .fi-input-wrp {
        width: 100% !important;
    }

    /* ==========================================================================
       6. REFINED DATA TABLES (MODERN, CLEAN & CRISP)
       ========================================================================== */
    .fi-ta-ctn {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        --tw-ring-color: transparent !important;
        overflow: visible !important;
    }

    .fi-ta,
    .fi-ta-table,
    .fi-ta-table th,
    .fi-ta-table td,
    .fi-ta-cell,
    .fi-ta-header-cell,
    .fi-ta-text,
    .fi-ta-text-item-label,
    .fi-ta-text-item-description {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        -webkit-font-smoothing: antialiased !important;
    }

    .fi-ta-table thead,
    .fi-ta-header-cell {
        background-color: #f8fafc !important;
        border-bottom: 1.5px solid #e2e8f0 !important;
    }

    .fi-ta-header-cell {
        vertical-align: middle !important;
        text-align: start !important;
    }

    .fi-ta-header-cell,
    .fi-ta-header-cell-label,
    .fi-ta-header-cell-sort-btn {
        font-size: 0.725rem !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.025em !important;
        color: #64748b !important;
    }

    .fi-ta-header-cell-sort-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.35rem !important;
        text-align: start !important;
        width: auto !important;
    }

    .fi-ta-row,
    .fi-ta-table tbody tr {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        transition: background-color 0.15s ease-in-out !important;
    }

    .fi-ta-row:hover,
    .fi-ta-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .fi-ta-cell {
        vertical-align: middle !important;
        font-size: 0.85rem !important;
        line-height: 1.5 !important;
        letter-spacing: -0.005em !important;
    }

    .fi-ta-text-item-label {
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        color: #0f172a !important;
        line-height: 1.4 !important;
        letter-spacing: -0.01em !important;
    }

    .fi-ta-text-item-description {
        font-size: 0.775rem !important;
        font-weight: 400 !important;
        color: #64748b !important;
        line-height: 1.45 !important;
        letter-spacing: -0.005em !important;
        margin-top: 0.15rem !important;
    }

    .fi-ta .fi-badge {
        border-radius: 9999px !important;
        font-family: 'Geist', 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 500 !important;
        font-size: 0.725rem !important;
        letter-spacing: -0.005em !important;
        padding: 0.2rem 0.6rem !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    /* PISAHKAN TOOLBAR DARI TABEL DATA */
    .fi-ta-main {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.875rem !important;
    }

    /* Hilangkan semua garis panjang pemisah pada header tabel, toolbar, filter & indikator */
    .fi-ta-header-ctn,
    .fi-ta-header,
    .fi-ta-header-toolbar,
    .fi-ta-filters-above-content-ctn,
    .fi-ta-selection-indicator,
    .fi-ta-filter-indicators,
    .fi-ta-reorder-indicator {
        background: transparent !important;
        border: none !important;
        border-top: none !important;
        border-bottom: none !important;
        border-left: none !important;
        border-right: none !important;
        box-shadow: none !important;
        outline: none !important;
        --tw-ring-shadow: 0 0 #0000 !important;
        --tw-ring-color: transparent !important;
    }

    .fi-ta-header-ctn {
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.5rem !important;
    }

    @media (min-width: 640px) {
        .fi-ta-header-ctn:has(.fi-ta-header):has(.fi-ta-header-toolbar) {
            flex-direction: row !important;
            align-items: flex-end !important;
            justify-content: space-between !important;
            gap: 1rem !important;
        }

        .fi-ta-header-ctn:has(.fi-ta-header):has(.fi-ta-header-toolbar) .fi-ta-header {
            flex: 1 1 auto !important;
            padding-bottom: 0 !important;
        }

        .fi-ta-header-ctn:has(.fi-ta-header):has(.fi-ta-header-toolbar) .fi-ta-header-toolbar {
            flex: 0 0 auto !important;
            padding-bottom: 0 !important;
        }
    }

    .fi-ta-header {
        padding: 0 0 0.25rem 0 !important;
        margin: 0 !important;
    }

    .fi-ta-header-heading {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        letter-spacing: -0.02em !important;
        line-height: 1.35 !important;
    }

    .fi-ta-header-description {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 0.8125rem !important;
        font-weight: 400 !important;
        color: #64748b !important;
        letter-spacing: -0.005em !important;
        margin-top: 0.2rem !important;
        line-height: 1.4 !important;
    }

    /* Header Toolbar Tabel (Bebas Container Kaku - Mengambang Bersih Tanpa Box Card Terpisah) */
    .fi-ta-header-toolbar {
        background: transparent !important;
        background-color: transparent !important;
        border: none !important;
        border-top: none !important;
        border-bottom: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding: 0 0 0.5rem 0 !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 1rem !important;
        flex-wrap: wrap !important;
    }

    .fi-ta-content-ctn {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
    }

    .fi-ta-content-ctn:has(+ .fi-pagination),
    .fi-ta-content-ctn:has(+ .fi-ta-pagination) {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom: none !important;
    }

    .fi-pagination,
    .fi-ta-pagination {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom-left-radius: 0.75rem !important;
        border-bottom-right-radius: 0.75rem !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03) !important;
        padding: 0.75rem 1.25rem !important;
        margin-top: 0 !important;
    }

    /* Kotak Input Pencarian Modern (Sleek, Minimalis, Tanpa Double Container) */
    .fi-ta-search-field {
        min-width: 16rem !important;
    }

    .fi-ta-search-field .fi-input-wrp {
        border-radius: 0.625rem !important;
        border: 1px solid #e2e8f0 !important;
        background: #ffffff !important;
        box-shadow: 0 1px 2px 0 rgba(15, 23, 42, 0.03) !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-ta-search-field .fi-input-wrp:hover {
        border-color: #cbd5e1 !important;
    }

    .fi-ta-search-field .fi-input-wrp:focus-within {
        background: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1) !important;
    }

    .fi-ta-search-field input {
        font-family: 'Geist', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        font-size: 0.8125rem !important;
        color: #0f172a !important;
        font-weight: 400 !important;
        letter-spacing: -0.01em !important;
    }

    .fi-ta-search-field input::placeholder {
        color: #94a3b8 !important;
        font-weight: 400 !important;
        opacity: 1 !important;
    }

    .fi-ta-search-field svg {
        color: #64748b !important;
    }

    /* Tombol Aksi di Tabel */
    .fi-ta-actions .fi-btn,
    .fi-ta-actions .fi-icon-btn {
        border-radius: 0.5rem !important;
        transition: all 0.15s ease !important;
    }

    .fi-pagination-records-per-page-select-ctn,
    .fi-pagination-records-per-page-select {
        display: none !important;
    }

    /* Sembunyikan breadcrumbs */
    .fi-breadcrumbs,
    .fi-header-breadcrumbs,
    nav.fi-breadcrumbs {
        display: none !important;
    }

    /* Batasi Lebar Tabel & Teks Kolom */
    .fi-ta-table {
        table-layout: auto !important;
        width: 100% !important;
        min-width: 56rem !important;
    }

    .fi-ta-header-cell {
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .fi-ta-cell {
        vertical-align: middle !important;
    }

    .fi-ta-cell:has(.fi-ta-actions),
    .fi-ta-actions-cell {
        white-space: nowrap !important;
        text-align: right !important;
    }

    .fi-ta-text-item-label,
    .fi-ta-text-item-description {
        max-width: 13rem !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .fi-ta-cell .fi-badge {
        max-width: 11rem !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        display: inline-flex !important;
    }

    .fi-ta-cell .fi-badge span {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .fi-ta-actions {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 0.35rem !important;
        flex-wrap: nowrap !important;
    }

    /* Scrollbar Halus untuk Tabel */
    .fi-ta-content-ctn::-webkit-scrollbar {
        height: 6px !important;
    }
    .fi-ta-content-ctn::-webkit-scrollbar-track {
        background: #f8fafc !important;
        border-radius: 9999px !important;
    }
    .fi-ta-content-ctn::-webkit-scrollbar-thumb {
        background: #cbd5e1 !important;
        border-radius: 9999px !important;
    }
    .fi-ta-content-ctn::-webkit-scrollbar-thumb:hover {
        background: #94a3b8 !important;
    }
</style>

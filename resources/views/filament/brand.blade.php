@php
    $logoUrl = app_logo_url('dark');
    $appName = app_name('SevenKost');
@endphp
<div style="display: flex; align-items: center; gap: 0.625rem; height: 2.5rem; max-width: 15rem; overflow: hidden; user-select: none;">
    <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" style="height: 2.25rem; width: 2.25rem; max-height: 2.25rem; max-width: 2.25rem; object-fit: contain; flex-shrink: 0; background: transparent !important; border: none !important; box-shadow: none !important;">
    <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.1; overflow: hidden;">
        <span style="font-size: 1rem; font-weight: 800; color: #ffffff; letter-spacing: -0.025em; white-space: nowrap;">{{ $appName }}</span>
        <span style="font-size: 0.625rem; font-weight: 700; color: #7dd3fc; letter-spacing: 0.05em; text-transform: uppercase; white-space: nowrap; margin-top: 0.125rem;">Super Admin</span>
    </div>
</div>

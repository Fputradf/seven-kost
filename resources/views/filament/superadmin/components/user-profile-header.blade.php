@php
    $user = auth()->user();
    $name = $user ? $user->name : 'Super Administrator';
@endphp

<div style="display: flex; flex-direction: column; text-align: right; line-height: 1.4; user-select: none; font-family: 'Plus Jakarta Sans', sans-serif;">
    <span style="font-size: 0.8125rem; font-weight: 700; color: #0f172a; letter-spacing: 0.01em; line-height: 1.35;">{{ $name }}</span>
    <span style="font-size: 0.6875rem; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1.35;">Super Admin</span>
</div>

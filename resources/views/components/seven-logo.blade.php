@props(['size' => 'md', 'light' => false])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-7 text-lg',
        'lg' => 'h-11 text-2xl',
        default => 'h-9 text-xl',
    };
    $textColor = $light ? 'text-white' : 'text-slate-900';
@endphp

<div class="inline-flex items-center gap-2.5 font-bold tracking-tight select-none {{ $sizeClasses }}">
    <div class="relative flex items-center justify-center aspect-square h-full rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-400 text-white shadow-md shadow-blue-500/20">
        <svg class="w-3/5 h-3/5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
        <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-amber-400 text-[9px] font-black text-slate-950 ring-2 ring-white">7</span>
    </div>
    <div class="leading-none flex flex-col">
        <span class="{{ $textColor }} font-extrabold tracking-tight">Seven<span class="text-blue-500">Kost</span></span>
        <span class="text-[9px] font-medium tracking-wider text-slate-400 uppercase mt-0.5">Platform Kos Nasional</span>
    </div>
</div>


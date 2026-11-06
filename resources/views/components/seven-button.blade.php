@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none gap-2';

    $sizeClasses = match($size) {
        'sm' => 'text-xs px-3.5 py-2 rounded-lg',
        'lg' => 'text-base px-6 py-3.5 rounded-2xl',
        default => 'text-sm px-4.5 py-2.5 rounded-xl',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/35 focus:ring-blue-500 border border-blue-500/20',
        'navy' => 'bg-navy-950 hover:bg-slate-900 text-white shadow-md shadow-navy-950/20 focus:ring-navy-800 border border-slate-800',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-800 focus:ring-slate-300 border border-slate-200/80',
        'outline' => 'bg-transparent border-2 border-slate-200 hover:border-blue-600 text-slate-700 hover:text-blue-600 focus:ring-blue-400',
        'outline-white' => 'bg-transparent border border-white/30 hover:border-white text-white hover:bg-white/10 focus:ring-white',
        'ghost' => 'bg-transparent hover:bg-slate-100 text-slate-700 focus:ring-slate-200',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-md shadow-red-500/20 focus:ring-red-500',
        default => 'bg-blue-600 hover:bg-blue-700 text-white',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

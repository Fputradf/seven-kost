@props(['size' => 'md', 'light' => false, 'withText' => true, 'subtitle' => null])

@php
    $imgSizeClasses = match($size) {
        'xs' => 'h-7 w-7',
        'sm' => 'h-8 w-8',
        'lg' => 'h-12 w-12',
        'xl' => 'h-16 w-16',
        default => 'h-10 w-10',
    };
    $titleSize = match($size) {
        'xs' => 'text-base',
        'sm' => 'text-lg',
        'lg' => 'text-2xl',
        'xl' => 'text-3xl',
        default => 'text-xl',
    };
    $textColor = $light ? 'text-white' : 'text-slate-900';
    $kostColor = $light ? 'text-cyan-400' : 'text-blue-600';
    $subColor = $light ? 'text-slate-300' : 'text-slate-500';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 select-none']) }}>
    <img src="{{ asset('images/logo-seven-kost.png') }}" alt="SevenKost Logo" class="{{ $imgSizeClasses }} object-contain drop-shadow-sm flex-shrink-0">
    @if($withText)
        <div class="leading-none flex flex-col justify-center">
            <span class="{{ $textColor }} font-extrabold tracking-tight {{ $titleSize }}">Seven<span class="{{ $kostColor }}">Kost</span></span>
            @if($subtitle)
                <span class="text-[10px] font-medium tracking-normal {{ $subColor }} mt-1">{{ $subtitle }}</span>
            @endif
        </div>
    @endif
</div>

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
    $subColor = $light ? 'text-slate-300' : 'text-slate-500';

    $logoUrl = app_logo_url($light ? 'dark' : 'light');
    $appName = app_name('SevenKost');
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 select-none']) }}>
    <img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" class="{{ $imgSizeClasses }} object-contain drop-shadow-sm flex-shrink-0">
    @if($withText)
        <div class="leading-none flex flex-col justify-center">
            <span class="{{ $textColor }} font-extrabold tracking-tight {{ $titleSize }}">{{ $appName }}</span>
            @if($subtitle)
                <span class="text-[10px] font-medium tracking-normal {{ $subColor }} mt-1">{{ $subtitle }}</span>
            @endif
        </div>
    @endif
</div>

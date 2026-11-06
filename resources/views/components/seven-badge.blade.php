@props([
    'type' => 'default',
    'size' => 'sm',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'text-[10px] px-2 py-0.5 font-medium',
        'md' => 'text-xs px-3 py-1 font-semibold',
        default => 'text-[11px] px-2.5 py-1 font-semibold',
    };

    $typeClasses = match(strtolower($type)) {
        'putra' => 'bg-sky-50 text-sky-700 border border-sky-200/80',
        'putri' => 'bg-rose-50 text-rose-700 border border-rose-200/80',
        'campur' => 'bg-purple-50 text-purple-700 border border-purple-200/80',
        'verified', 'terverifikasi' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/80 inline-flex items-center gap-1',
        'featured', 'unggulan' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-sm shadow-amber-500/30 font-bold',
        'available', 'tersedia' => 'bg-emerald-100/70 text-emerald-800',
        'full', 'penuh' => 'bg-slate-100 text-slate-500',
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'rejected' => 'bg-red-50 text-red-700 border border-red-200',
        default => 'bg-slate-100 text-slate-700 border border-slate-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full tracking-wide select-none transition-colors {$sizeClasses} {$typeClasses}"]) }}>
    @if(in_array(strtolower($type), ['verified', 'terverifikasi']))
        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    @elseif(in_array(strtolower($type), ['featured', 'unggulan']))
        <svg class="w-3 h-3 mr-1 fill-current" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>
    @endif
    {{ $slot }}
</span>

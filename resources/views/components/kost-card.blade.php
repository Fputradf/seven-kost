@props([
    'title' => 'Melati Residence Premium',
    'slug' => '#',
    'type' => 'campur', // putra, putri, campur
    'city' => 'Jakarta Selatan',
    'province' => 'DKI Jakarta',
    'price' => 1500000,
    'rentType' => 'bulan',
    'image' => null,
    'isVerified' => true,
    'isFeatured' => false,
    'availableRooms' => 3,
    'facilities' => ['AC', 'WiFi', 'K. Mandi Dalam'],
])

<div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/50">
    {{-- Thumbnail & Badges --}}
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400">
                <svg class="h-12 w-12 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
        @endif

        {{-- Floating Badges Top --}}
        <div class="absolute top-3 inset-x-3 flex items-center justify-between gap-1.5 pointer-events-none">
            <div class="flex items-center gap-1.5 flex-wrap">
                <x-seven-badge :type="$type" class="uppercase">
                    {{ $type }}
                </x-seven-badge>

                @if($isFeatured)
                    <x-seven-badge type="featured">
                        Unggulan
                    </x-seven-badge>
                @endif
            </div>

            @if($isVerified)
                <x-seven-badge type="verified" class="bg-white/95 backdrop-blur-sm shadow-sm">
                    Terverifikasi
                </x-seven-badge>
            @endif
        </div>

        {{-- Floating Badge Bottom (Ketersediaan) --}}
        <div class="absolute bottom-3 left-3 pointer-events-none">
            @if($availableRooms > 0)
                <span class="rounded-lg bg-slate-900/80 backdrop-blur-md px-2.5 py-1 text-[11px] font-medium text-white shadow-sm">
                    Sisa {{ $availableRooms }} Kamar
                </span>
            @else
                <span class="rounded-lg bg-red-600/90 backdrop-blur-md px-2.5 py-1 text-[11px] font-medium text-white shadow-sm">
                    Penuh
                </span>
            @endif
        </div>
    </div>

    {{-- Content --}}
    <div class="flex flex-1 flex-col p-4.5">
        {{-- Location --}}
        <div class="flex items-center gap-1 text-xs font-medium text-slate-500 mb-1.5">
            <svg class="w-3.5 h-3.5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="truncate">{{ $city }}, {{ $province }}</span>
        </div>

        {{-- Title --}}
        <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition-colors">
            <a href="{{ $slug !== '#' ? url('/kos/' . $slug) : '#' }}">
                {{ $title }}
            </a>
        </h3>

        {{-- Facilities preview --}}
        @if(!empty($facilities))
            <div class="flex items-center gap-1.5 mt-2.5 flex-wrap">
                @foreach(array_slice($facilities, 0, 3) as $facility)
                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                        {{ $facility }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Divider & Price --}}
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-medium text-slate-400 block">Mulai dari</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-base font-extrabold text-blue-600">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-slate-500 font-normal">/ {{ $rentType }}</span>
                </div>
            </div>

            <a href="{{ $slug !== '#' ? url('/kos/' . $slug) : '#' }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-700 transition-colors hover:bg-blue-600 hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

@props([
    'title' => null,
])

<x-seven-layout :title="$title ? $title . ' - ' . app_name('SevenKost') . ' Penghuni' : (app_name('SevenKost') . ' - Portal Penghuni')">

    {{-- Main Tenant Body Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 min-h-[65vh]">
        {{ $slot }}
    </div>

</x-seven-layout>

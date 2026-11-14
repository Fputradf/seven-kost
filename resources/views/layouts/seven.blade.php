@props([
    'title' => null,
    'description' => null,
    'robots' => null,
    'canonical' => null,
    'transparentNavbar' => false,
    'hideBottomNav' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? (app_name() . ' - ' . setting('app_slogan', 'Cari & Sewa Kos Amanah Seluruh Indonesia')) }}</title>
    <meta name="description" content="{{ $description ?? setting('app_description', 'Platform pencarian kos terlengkap dan terverifikasi di seluruh Indonesia dengan peta interaktif dan kemudahan sewa kos langsung ke pemilik.') }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    @if(isset($canonical) && $canonical)
        <link rel="canonical" href="{{ $canonical }}">
    @endif

    <!-- Favicon (Dinamis dari Pengaturan Aplikasi & Fallback) -->
    <link rel="icon" href="{{ app_favicon_url() }}">
    <link rel="shortcut icon" href="{{ app_favicon_url() }}">
    <link rel="apple-touch-icon" href="{{ app_logo_url() }}">

    <!-- Fonts: Inter & Plus Jakarta Sans (Modern, Clean & Ultra Legible) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @stack('head')
</head>
@php
    $isHome = request()->is('/');
    $isTransparentHero = ($transparentNavbar ?? false) || $isHome;
@endphp
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col selection:bg-blue-600 selection:text-white">
    <x-seven-navbar :transparent="$transparentNavbar ?? false" />

    <main class="flex-1 w-full pb-16 md:pb-0 {{ $isTransparentHero ? '' : 'pt-[72px] sm:pt-[80px]' }}">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <x-seven-footer class="mt-auto w-full mb-16 md:mb-0" />

    @if(!($hideBottomNav ?? false))
        <x-seven-bottom-nav />
    @endif

    @stack('scripts')
</body>
</html>

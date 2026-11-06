<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Seven Kost - Cari & Sewa Kos Amanah Seluruh Indonesia' }}</title>
    <meta name="description" content="{{ $description ?? 'Platform pencarian kos terlengkap dan terverifikasi di seluruh Indonesia dengan peta interaktif dan kemudahan sewa kos langsung ke pemilik.' }}">
    <meta name="robots" content="{{ $robots ?? 'index, follow' }}">
    @if(isset($canonical) && $canonical)
        <link rel="canonical" href="{{ $canonical }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    @stack('head')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col selection:bg-blue-600 selection:text-white">
    <x-seven-navbar :transparent="$transparentNavbar ?? false" />

    <main class="flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <x-seven-footer />

    @stack('scripts')
</body>
</html>


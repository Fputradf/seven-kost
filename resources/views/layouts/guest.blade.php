@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'SevenKost - Platform Kos Terverifikasi Nasional') }}</title>

    <!-- Favicon (Logo Resmi SevenKost) -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-seven-kost.png') }}">

    <!-- Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen w-full font-sans antialiased bg-gradient-to-br from-[#08182f] via-[#0f2d59] to-[#17427d] text-white selection:bg-cyan-500 selection:text-white relative overflow-x-hidden flex items-center justify-center p-3 sm:p-6 lg:p-8">

    {{-- Bright Ambient Radial Glowing Orbs --}}
    <div class="fixed -top-24 left-1/4 w-[36rem] h-[36rem] bg-blue-500/25 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed -bottom-24 right-1/4 w-[36rem] h-[36rem] bg-cyan-400/20 blur-[130px] rounded-full pointer-events-none -z-10"></div>
    <div class="fixed top-1/2 -left-20 w-[28rem] h-[28rem] bg-indigo-500/20 blur-[120px] rounded-full pointer-events-none -z-10"></div>

    {{-- Subtle Geometric Grid Accent --}}
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#ffffff0f_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0f_1px,transparent_1px)] bg-[size:36px_36px] pointer-events-none -z-10 [mask-image:radial-gradient(ellipse_80%_70%_at_50%_50%,#000_60%,transparent_100%)]"></div>

    {{-- Content Wrapper --}}
    <div class="w-full flex items-center justify-center py-4">
        {{ $slot }}
    </div>

</body>
</html>

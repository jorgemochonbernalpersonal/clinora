<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Primary Meta Tags --}}
    <title>@yield('title', 'Clinora - Software para Psicólogos y Salud Mental')</title>
    <meta name="title" content="@yield('meta_title', 'Clinora - Software de Gestión para Psicólogos | Prueba Gratis')">
    <meta name="description" content="@yield('meta_description', 'El software definitivo para psicólogos. Gestiona historias clínicas, citas, facturación y teleconsulta. Cumple RGPD/LOPD. Prueba gratis 14 días.')">
    <meta name="keywords" content="@yield('meta_keywords', 'software psicólogos, gestión clínica psicología, historia clínica psicológica, software salud mental, telepsicología, RGPD psicología')">
    <meta name="author" content="Clinora">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="language" content="Spanish">
    <meta name="revisit-after" content="7 days">
    
    {{-- Geo Tags --}}
    <meta name="geo.region" content="ES">
    <meta name="geo.placename" content="España">
    
    {{-- Security Headers --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    {{-- Web App Manifest --}}
    <meta name="application-name" content="Clinora">
    <meta name="theme-color" content="#0EA5E9">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Clinora - Software de Gestión para Clínicas de Salud')">
    <meta property="og:description" content="@yield('og_description', 'Gestiona tu clínica de salud con la plataforma más completa. 2FA, GDPR compliant, verificación de email. Prueba gratis 14 días.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="es_ES">
    <meta property="og:site_name" content="Clinora">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'Clinora - Software de Gestión para Clínicas de Salud')">
    <meta property="twitter:description" content="@yield('twitter_description', 'Gestiona tu clínica de salud con la plataforma más completa. Prueba gratis 14 días.')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/twitter-image.jpg'))">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Alternate Languages (if you add more in the future) --}}
    <link rel="alternate" hreflang="es" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Fonts with preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    {{-- Styles / Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Structured Data --}}
    @stack('structured_data')
</head>
<body class="bg-background text-text-primary antialiased">
    {{-- Header --}}
    @include('partials.header')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Cookie Banner --}}
    @include('partials.cookie-banner')

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>


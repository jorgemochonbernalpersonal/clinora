<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Google Consent Mode v2 - MUST be first --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied',
            'functionality_storage': 'granted',
            'personalization_storage': 'denied',
            'security_storage': 'granted'
        });
        
        const cookieConsent = localStorage.getItem('cookie_consent');
        if (cookieConsent === 'accepted') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted',
                'personalization_storage': 'granted'
            });
        }
    </script>
    
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.defer=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KQV6Q5MS');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Google Analytics (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TJ20C7QSTH"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-TJ20C7QSTH', {
            'anonymize_ip': true,
            'cookie_flags': 'SameSite=None;Secure'
        });
    </script>
    <!-- End Google Analytics -->
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Google Search Console Verification --}}
    {{-- TODO: Replace with your actual verification code from https://search.google.com/search-console --}}
    {{-- <meta name="google-site-verification" content="YOUR_VERIFICATION_CODE_HERE" /> --}}

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
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Clinora - Software para Psicólogos | Gestión de Consultas')">
    <meta property="og:locale" content="es_ES">
    <meta property="og:site_name" content="Clinora">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Clinora - Software de Gestión para Clínicas de Salud')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Gestiona tu clínica de salud con la plataforma más completa. Prueba gratis 14 días.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/twitter-image.jpg'))">
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt', 'Clinora - Software para Psicólogos')">


    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Performance: Preconnect to critical third-party domains --}}
    <link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    
    {{-- Fonts with preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    {{-- Preload critical font (400) to reduce chain latency --}}
    <link rel="preload" href="https://fonts.bunny.net/files/instrument-sans-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
    {{-- Load fonts asynchronously - reduced to critical weights only (400, 600) --}}
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600&display=swap" rel="stylesheet">
    </noscript>
    {{-- Styles / Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-background text-text-primary antialiased">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KQV6Q5MS"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Scripts --}}
    @livewireScripts
    @stack('scripts')
    
    {{-- Load non-critical font weights (500, 700) asynchronously after page load --}}
    <script>
        (function() {
            if (document.readyState === 'complete') {
                loadNonCriticalFonts();
            } else {
                window.addEventListener('load', loadNonCriticalFonts);
            }
            function loadNonCriticalFonts() {
                var link = document.createElement('link');
                link.href = 'https://fonts.bunny.net/css?family=instrument-sans:500,700&display=optional';
                link.rel = 'stylesheet';
                document.head.appendChild(link);
            }
        })();
    </script>
</body>
</html>


{{--
    SEO Meta Tags Component
    
    Usage:
    @include('components.seo-meta', [
        'title' => 'Page Title',
        'description' => 'Page description',
        'keywords' => 'keyword1, keyword2',
        'image' => asset('images/og-image.jpg'),
        'type' => 'website', // or 'article'
    ])
--}}

@php
    $title = $title ?? config('app.name') . ' - Software para Psicólogos';
    $description = $description ?? 'El software definitivo para psicólogos. Gestiona historias clínicas, citas, facturación y teleconsulta.';
    $keywords = $keywords ?? 'software psicólogos, gestión clínica, RGPD';
    $image = $image ?? asset('images/og-image.jpg');
    $type = $type ?? 'website';
    $url = url()->current();
@endphp

{{-- Primary Meta Tags --}}
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="es_ES">
<meta property="og:site_name" content="Clinora">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $url }}">

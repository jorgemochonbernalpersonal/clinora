<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Clinora')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    {{-- Styles / Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-background text-text-primary antialiased">
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Scripts --}}
    @livewireScripts
    @stack('scripts')
</body>
</html>


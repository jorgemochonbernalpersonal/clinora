<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KQV6Q5MS');</script>
    <!-- End Google Tag Manager -->
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Dashboard - Clinora</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-background">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KQV6Q5MS"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-surface border-r border-gray-200 flex flex-col">
            {{-- Logo --}}
            <div class="p-6 border-b border-gray-200 flex justify-center items-center overflow-hidden">
                <a href="{{ profession_route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="h-20 w-auto mix-blend-multiply transform scale-125 hover:scale-135 transition-transform duration-200">
                </a>
            </div>
            
            {{-- Navigation --}}
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                {{-- Dashboard --}}
                <a href="{{ profession_route('dashboard') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </div>
                </a>
                
                {{-- Sección: Gestión Clínica --}}
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestión Clínica</span>
                </div>
                
                <a href="{{ profession_route('patients.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.patients.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="font-medium">Pacientes</span>
                    </div>
                </a>
                
                <a href="{{ profession_route('appointments.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.appointments.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Agenda</span>
                    </div>
                </a>
                
                <a href="{{ profession_route('clinical-notes.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.clinical-notes.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-medium">Notas Clínicas</span>
                    </div>
                </a>
                
                <a href="{{ profession_route('consent-forms.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.consent-forms.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Consentimientos</span>
                    </div>
                </a>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'evaluations']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="font-medium">Evaluaciones</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Q1</span>
                </a>
                
                {{-- Sección: Comunicación --}}
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Comunicación</span>
                </div>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'video-consultations']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Videoconsultas</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full">Q3</span>
                </a>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'reminders']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="font-medium">Recordatorios</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Q1</span>
                </a>
                
                {{-- Sección: Portal Paciente --}}
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Portal Paciente</span>
                </div>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'patient-portal']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Portal</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Q1</span>
                </a>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'booking-system']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Reservas Online</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Q1</span>
                </a>
                
                {{-- Sección: Administración --}}
                <div class="pt-4 pb-2">
                    <span class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</span>
                </div>
                
                <a href="{{ profession_route('under-construction', ['feature' => 'billing']) }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg text-text-primary hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                        <span class="font-medium">Facturación VeriFactu</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Q2</span>
                </a>
                
                <a href="{{ profession_route('subscription.index') }}" 
                   class="flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.subscription.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="font-medium">Suscripción</span>
                    </div>
                </a>
                
                 {{-- Logs del Sistema - Solo para administradores --}}
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                <a href="{{ profession_route('logs.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs(profession_prefix() . '.logs.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Logs del Sistema</span>
                </a>
                @endif
            </nav>
            
            {{-- User info & Logout --}}
            <div class="p-4 border-t border-gray-200" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 w-full hover:bg-gray-50 rounded-lg p-2 transition-colors">
                        {{-- Avatar --}}
                        <img 
                            src="{{ auth()->user()->avatar_url }}" 
                            alt="{{ auth()->user()->full_name }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200"
                        >
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-medium text-text-primary truncate">
                                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                            </p>
                            <p class="text-xs text-text-secondary truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-text-secondary" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute bottom-full left-0 right-0 mb-2 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                        <a href="{{ profession_route('profile.settings') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-text-primary">Mi Perfil</span>
                        </a>
                        <a href="{{ profession_route('security.settings') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm font-medium text-text-primary">Seguridad</span>
                        </a>
                        <div class="border-t border-gray-200"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors w-full text-left">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-sm font-medium text-red-600">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>
    
    @livewireScripts
    @stack('scripts')
    
    {{-- Email Verification Modal --}}
    @livewire('auth.email-verification-modal')
    
    {{-- Global Toast --}}
    <x-toast />
</body>
</html>

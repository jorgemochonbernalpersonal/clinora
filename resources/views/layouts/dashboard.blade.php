<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' : '' }}Dashboard - Clinora</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-background">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-surface border-r border-gray-200 flex flex-col">
            {{-- Logo --}}
            {{-- Logo --}}
            <div class="p-6 border-b border-gray-200 flex justify-center items-center overflow-hidden">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="h-20 w-auto mix-blend-multiply transform scale-125 hover:scale-135 transition-transform duration-200">
                </a>
            </div>
            
            {{-- Navigation --}}
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('patients.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('patients.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Pacientes</span>
                </a>
                
                <a href="{{ route('appointments.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('appointments.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Agenda</span>
                </a>
                
                <a href="{{ route('clinical-notes.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('clinical-notes.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Notas Clínicas</span>
                </a>
                
                {{-- Logs del Sistema - Solo para administradores --}}
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                <a href="{{ route('logs.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('logs.*') ? 'bg-primary-50 text-primary-600' : 'text-text-primary hover:bg-gray-50' }} transition-colors">
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
                        <a href="{{ route('profile.settings') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-text-primary">Mi Perfil</span>
                        </a>
                        <a href="{{ route('security.settings') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
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
    
    {{-- Email Verification Modal --}}
    @livewire('auth.email-verification-modal')
    
    {{-- Global Toast --}}
    <x-toast />
</body>
</html>

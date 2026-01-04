@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="h-12 w-auto mix-blend-multiply">
            </a>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-center text-text-primary mb-2">
            ¡Bienvenido de nuevo!
        </h2>
        <p class="text-center text-text-secondary mb-8">
            Ingresa tus credenciales para acceder a tu cuenta
        </p>

        <livewire:auth.login-form />

        <div class="mt-8 text-center text-sm">
            <p class="text-text-secondary">
                ¿Aún no tienes cuenta? 
                <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                    Regístrate gratis
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

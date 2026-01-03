@extends('layouts.app')

@section('title', 'Registro - Clinora')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-primary-50 to-background flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary-600 mb-2">Clinora</h1>
            <p class="text-text-secondary">Crea tu cuenta profesional</p>
            <p class="text-sm text-text-secondary mt-2">
                🧠 Actualmente solo para <strong>psicólogos</strong>
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-surface rounded-lg shadow-lg p-8 border border-gray-200">
            @livewire('auth.register-form')
            
            <div class="mt-6 text-center text-sm">
                <p class="text-text-secondary">
                    ¿Ya tienes cuenta? 
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </div>

        {{-- Back to home --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-text-secondary hover:text-text-primary text-sm">
                ← Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection

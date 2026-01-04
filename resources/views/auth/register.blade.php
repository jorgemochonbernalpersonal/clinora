@extends('layouts.guest')

@section('title', 'Crear Cuenta')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-lg shadow-xl p-8">
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="h-12 w-auto mix-blend-multiply">
            </a>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-center text-text-primary mb-2">
            Crea tu cuenta gratis
        </h2>
        <p class="text-center text-text-secondary mb-8">
            Únete a la plataforma de gestión definitiva para psicólogos
        </p>

        <livewire:auth.register-form />

        <div class="mt-8 text-center text-sm">
            <p class="text-text-secondary">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">
                    Inicia sesión
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

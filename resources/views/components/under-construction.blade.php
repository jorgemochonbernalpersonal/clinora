@extends('layouts.dashboard')

@section('content')
{{-- resources/views/components/under-construction.blade.php --}}
@props([
    'title' => '¡Próximamente!',
    'description' => 'Estamos trabajando en esta funcionalidad para traértela pronto.',
    'features' => [],
    'notifyMe' => true,
    'featureSlug' => ''
])

<div class="max-w-3xl mx-auto px-4 py-16 text-center">
    {{-- Ilustración --}}
    <div class="mb-8">
        <svg class="w-32 h-32 mx-auto text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
    </div>

    {{-- Título y descripción --}}
    <h1 class="text-3xl font-bold text-gray-900 mb-4">
        {{ $title }}
    </h1>
    
    <p class="text-lg text-gray-600 mb-8">
        {{ $description }}
    </p>

    {{-- Features previstas --}}
    @if(count($features) > 0)
    <div class="bg-gray-50 rounded-xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">¿Qué incluirá?</h2>
        <ul class="space-y-3 text-left max-w-md mx-auto">
            @foreach($features as $feature)
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-700">{{ $feature }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- CTA --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ profession_route('dashboard') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Dashboard
        </a>

        @if($notifyMe)
        <button onclick="alert('¡Genial! Te notificaremos cuando esta funcionalidad esté disponible.')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-primary-600 text-primary-600 font-medium rounded-lg hover:bg-primary-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notificarme cuando esté lista
        </button>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Sobre Nosotros - Clinora')
@section('meta_title', 'Sobre Nosotros - Clinora | Software de Gestión para Clínicas')
@section('meta_description', 'Conoce Clinora, el software diseñado por profesionales de la salud para simplificar la gestión de clínicas de psicología y salud.')

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => 'Sobre Nosotros - Clinora',
    'description' => 'Información sobre Clinora y nuestra misión',
    'url' => route('about'),
    'mainEntity' => [
        '@type' => 'Organization',
        'name' => 'Clinora',
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'description' => 'Software de gestión para clínicas de salud y psicología',
        'foundingDate' => '2024',
        'sameAs' => []
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-b from-primary-50 to-background py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-text-primary mb-6">
                    Sobre Clinora
                </h1>
                <p class="text-xl text-text-secondary max-w-2xl mx-auto">
                    Software diseñado por profesionales de la salud para simplificar la gestión de tu clínica
                </p>
            </div>
        </div>
    </section>

    {{-- Mission Section --}}
    <section class="py-16 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-text-primary mb-4">
                            Nuestra Misión
                        </h2>
                        <p class="text-text-secondary mb-4">
                            Clinora nace de la necesidad real de psicólogos y profesionales de la salud que buscaban una solución moderna, segura y fácil de usar para gestionar sus consultas.
                        </p>
                        <p class="text-text-secondary mb-4">
                            Creemos que la tecnología debe simplificar tu trabajo, no complicarlo. Por eso diseñamos cada función pensando en la experiencia del usuario y en las necesidades reales de los profesionales de la salud.
                        </p>
                        <p class="text-text-secondary">
                            Nuestro objetivo es permitirte dedicar más tiempo a lo que realmente importa: tus pacientes.
                        </p>
                    </div>
                    <div class="bg-primary-50 rounded-lg p-8 border-2 border-primary-200">
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-primary-500 rounded-full p-3 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-text-primary mb-1">Seguridad y Privacidad</h3>
                                    <p class="text-sm text-text-secondary">Cumplimiento total con GDPR y LOPD</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="bg-primary-500 rounded-full p-3 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-text-primary mb-1">Innovación Continua</h3>
                                    <p class="text-sm text-text-secondary">Actualizaciones frecuentes basadas en feedback</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="bg-primary-500 rounded-full p-3 text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-text-primary mb-1">Soporte Dedicado</h3>
                                    <p class="text-sm text-text-secondary">Te ayudamos en cada paso del camino</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="py-16 bg-surface">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-text-primary text-center mb-12">
                    Nuestros Valores
                </h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text-primary mb-2">Simplicidad</h3>
                        <p class="text-text-secondary text-sm">
                            Interfaz intuitiva que no requiere formación técnica
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text-primary mb-2">Privacidad</h3>
                        <p class="text-text-secondary text-sm">
                            Máxima protección de datos de pacientes
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="bg-primary-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-text-primary mb-2">Transparencia</h3>
                        <p class="text-text-secondary text-sm">
                            Precios claros sin costes ocultos
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-16 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-text-primary text-center mb-12">
                    ¿Por qué elegir Clinora?
                </h2>
                <div class="space-y-6">
                    <div class="bg-surface rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-text-primary mb-2">
                            🎯 Diseñado específicamente para psicólogos
                        </h3>
                        <p class="text-text-secondary">
                            No es un software genérico adaptado. Cada función está pensada para las necesidades específicas de profesionales de la salud mental.
                        </p>
                    </div>
                    <div class="bg-surface rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-text-primary mb-2">
                            💰 Modelo de precios justo
                        </h3>
                        <p class="text-text-secondary">
                            Paga solo por los pacientes que realmente atiendes cada mes. Sin cuotas fijas ni permanencias.
                        </p>
                    </div>
                    <div class="bg-surface rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-text-primary mb-2">
                            🔒 Cumplimiento normativo garantizado
                        </h3>
                        <p class="text-text-secondary">
                            GDPR, LOPD, encriptación end-to-end. Tu tranquilidad es nuestra prioridad.
                        </p>
                    </div>
                    <div class="bg-surface rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-text-primary mb-2">
                            🚀 Empieza en minutos
                        </h3>
                        <p class="text-text-secondary">
                            No necesitas instalaciones complicadas. Crea tu cuenta y empieza a usar Clinora inmediatamente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

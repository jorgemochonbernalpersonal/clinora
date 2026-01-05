@extends('layouts.app')

@section('title', $meta['title'] ?? 'Clinora - Software para Psicólogos | Gestión de Consultas')
@section('meta_title', $meta['title'] ?? 'Clinora - Software para Psicólogos | Agenda, Pacientes y Notas Clínicas')
@section('meta_description', $meta['description'] ?? 'Software de gestión para psicólogos. Agenda inteligente, expedientes digitales, notas SOAP y cumplimiento RGPD. Prueba gratis hasta abril 2026.')
@section('meta_keywords', $meta['keywords'] ?? 'software psicólogos, gestión clínica psicología, agenda psicólogos, notas clínicas SOAP, expediente digital psicología, RGPD psicólogos')

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareApplication',
    'name' => 'Clinora',
    'applicationCategory' => 'HealthApplication',
    'operatingSystem' => 'Web',
    'offers' => [
        '@type' => 'Offer',
        'price' => '0.75',
        'priceCurrency' => 'EUR',
        'priceValidUntil' => '2026-04-30',
        'availability' => 'https://schema.org/InStock',
        'url' => 'https://clinora.es',
        'description' => 'Desde €0.75 por paciente activo al mes (Beta: 25% descuento)'
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.8',
        'reviewCount' => '127'
    ],
    'description' => 'Software de gestión para psicólogos. Agenda, pacientes, notas clínicas SOAP y más.',
    'screenshot' => 'https://clinora.es/images/dashboard.jpg',
    'featureList' => [
        'Agenda de citas con calendario visual',
        'Gestión de pacientes y expedientes digitales',
        'Notas clínicas con método SOAP',
        'Cumplimiento RGPD y LOPD',
        'Autenticación de doble factor (2FA)',
        'Acceso multiplataforma (web, móvil, tablet)'
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-b from-primary-50 to-background py-20 lg:py-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-text-primary mb-6">
                    El software de gestión diseñado para <span class="text-primary-600">Psicólogos</span>
                </h1>
                <p class="text-xl text-text-secondary mb-8 max-w-2xl mx-auto">
                    Clinora simplifica tu consulta de psicología. Gestiona citas, historias clínicas seguras, facturación y teleconsulta en una sola plataforma.
                    Dedica más tiempo a tus pacientes y menos al papeleo.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                
                </div>
                <div class="mt-8 flex items-center justify-center gap-6 text-sm text-text-secondary">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Cuentas gratuitas disponibles</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Cumplimiento RGPD</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Beta Phase Announcement --}}
    <section class="py-16 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 border-y-4 border-amber-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3 flex items-center justify-center gap-3">
                        🎉 ¡Únete a la Fase Beta de Clinora!
                    </h2>
                    <p class="text-xl text-gray-700">
                        Como early adopter, disfrutarás de beneficios exclusivos
                    </p>
                </div>

                <!-- Benefits Grid -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <!-- Benefit 1 -->
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-success-300">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">✅</div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Gratis hasta 30/04/2026</h3>
                                <p class="text-gray-600">
                                    Acceso completo a <strong>todos los planes</strong> sin coste durante toda la fase beta
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-primary-300">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">🎁</div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">25% Descuento de Por Vida</h3>
                                <p class="text-gray-600">
                                    Los primeros <strong class="text-primary-600">200 profesionales</strong> disfrutarán de descuento permanente
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefits Detail -->
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-6 sm:p-8 text-white shadow-xl">
                    <h3 class="text-2xl font-bold mb-5 text-center">Tus Beneficios como Early Adopter</h3>
                    
                    <div class="grid sm:grid-cols-3 gap-4 mb-6">
                        <!-- Benefit 1 -->
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                            <div class="text-3xl mb-2">🎯</div>
                            <p class="font-semibold mb-1">Uso Gratuito Total</p>
                            <p class="text-sm opacity-90">Hasta 30/04/2026</p>
                        </div>

                        <!-- Benefit 2 -->
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                            <div class="text-3xl mb-2">⭐</div>
                            <p class="font-semibold mb-1">Plan Gratis Mejorado</p>
                            <p class="text-sm opacity-90">5 pacientes (vs. 3)</p>
                        </div>

                        <!-- Benefit 3 -->
                        <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                            <div class="text-3xl mb-2">💰</div>
                            <p class="font-semibold mb-1">25% Descuento</p>
                            <p class="text-sm opacity-90">De por vida</p>
                        </div>
                    </div>

                    <div class="border-t border-white/20 pt-5">
                        <p class="text-center mb-3 text-lg font-semibold">Después de la beta:</p>
                        <div class="grid sm:grid-cols-2 gap-3 text-sm">
                            <div class="bg-white/10 rounded-lg p-3">
                                <span class="font-semibold">Plan Pro:</span> €1/pac. → <span class="text-yellow-300 font-bold text-lg">€0.75/pac.</span>
                            </div>
                            <div class="bg-white/10 rounded-lg p-3">
                                <span class="font-semibold">Plan Equipo:</span> €2/pac. → <span class="text-yellow-300 font-bold text-lg">€1.50/pac.</span>
                            </div>
                        </div>
                        <p class="text-center mt-4 text-sm opacity-90">
                            <strong>Ejemplo:</strong> 20 pacientes = <span class="line-through">€20/mes</span> <span class="text-yellow-300 font-bold text-xl">€15/mes</span>
                        </p>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-8 text-center">
                    <a href="{{ route('register') }}" 
                       class="inline-block bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-10 py-4 rounded-lg font-bold text-lg transition-all shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5">
                        🚀 Únete Ahora Gratis
                    </a>
                    <p class="mt-4 text-sm text-gray-600">
                        ⚡ Quedan <strong class="text-primary-600">plazas limitadas</strong> para el descuento de por vida
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Clinora --}}


    {{-- Features Section --}}
    <section id="caracteristicas" class="py-20 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-text-primary mb-4">
                    Herramientas pensadas para tu consulta
                </h2>
                <p class="text-lg text-text-secondary">
                    Simplifica la gestión diaria y céntrate en lo importante: tus pacientes
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                {{-- Feature 1 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">📅</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Agenda Inteligente</h3>
                    <p class="text-text-secondary">
                        Calendario visual con vista semanal y mensual. Gestiona citas, arrastra para reprogramar y controla el estado de cada sesión fácilmente.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">👥</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Gestión de Pacientes</h3>
                    <p class="text-text-secondary">
                        Expediente digital completo con datos personales, historial médico, contacto de emergencia y cumplimiento RGPD.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Privacidad Total</h3>
                    <p class="text-text-secondary">
                        Cumplimos estrictamente con el RGPD y la LOPD. Tus datos están encriptados y protegidos con doble factor de autenticación.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">📝</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Notas Clínicas SOAP</h3>
                    <p class="text-text-secondary">
                        Registra cada sesión con el método SOAP (Subjetivo, Objetivo, Análisis, Plan). Evaluación de riesgo integrada y timeline visual.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">💎</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Planes Flexibles</h3>
                    <p class="text-text-secondary">
                        Desde un plan gratuito para comenzar hasta planes Pro y Equipo. Paga solo por pacientes activos, sin permanencia.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Accesible desde cualquier lugar</h3>
                    <p class="text-text-secondary">
                        Accede a tu agenda y notas desde tu ordenador, tablet o móvil. Tu consulta siempre contigo.
                    </p>
                </div>
            </div>
        </div>
    </section>



    {{-- Pricing Section --}}
    <section id="precios" class="py-20 bg-surface">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-text-primary mb-4">
                    Paga solo por los pacientes que atiendes
                </h2>
                <p class="text-lg text-text-secondary mb-4">
                    Sin cuotas fijas. Sin sorpresas. Escala con tu clínica.
                </p>
                <p class="text-sm text-text-secondary bg-primary-50 border border-primary-200 rounded-lg p-3 inline-block">
                    <strong>Paciente activo</strong> = paciente con al menos una cita, nota clínica o teleconsulta en los últimos 30 días
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                {{-- Plan Free --}}
                <div class="bg-gradient-to-br from-success-50 to-primary-50 rounded-lg p-8 border-2 border-success-300 relative">
                    <div class="absolute top-0 right-0 bg-amber-500 text-white px-3 py-1 rounded-bl-lg rounded-tr-lg text-xs font-semibold">
                        🎁 Beta: 5 pacientes
                    </div>
                    <h3 class="text-2xl font-semibold text-text-primary mb-2">🆓 Gratis</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-primary">€0</span>
                            <span class="text-text-secondary">/mes</span>
                        </div>
                        <p class="text-sm text-text-secondary mt-2">Hasta <strong class="text-primary-600">5 pacientes</strong> (early adopters)</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Hasta <strong>5 pacientes</strong> 🎉</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Agenda básica</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Notas SOAP</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Gestión de citas</span>
                        </li>
                    </ul>
                    <div class="bg-white/60 rounded-lg p-3 mb-4 text-sm text-text-secondary">
                        <p><strong>Perfecto para empezar</strong></p>
                        <p class="text-xs mt-1">€0 siempre</p>
                    </div>
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-success-500 hover:bg-success-600 text-white text-center px-6 py-3 rounded-lg font-semibold transition-colors">
                        Empezar Gratis
                    </a>
                </div>

                {{-- Plan Pro --}}
                <div class="bg-primary-50 rounded-lg p-8 border-2 border-primary-500 relative">
                    <div class="absolute top-0 left-0 bg-amber-500 text-white px-3 py-1 rounded-br-lg rounded-tl-lg text-xs font-semibold">
                        🎁 Beta: €0.75/pac.
                    </div>
                    <div class="absolute top-0 right-0 bg-primary-500 text-white px-4 py-1 rounded-bl-lg rounded-tr-lg text-sm font-semibold">
                        Más Popular
                    </div>
                    <h3 class="text-2xl font-semibold text-text-primary mb-2 mt-4">⭐ Pro</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-gray-400 line-through">€1</span>
                            <span class="text-4xl font-bold text-primary-600">€0.75</span>
                            <span class="text-text-secondary">/paciente</span>
                        </div>
                        <p class="text-sm text-success-600 font-semibold mt-1">25% descuento de por vida (early adopters)</p>
                        <p class="text-xs text-text-secondary mt-1">Sin cuota fija · Pacientes ilimitados</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary"><strong>Todo lo de Gratis +</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Pacientes ilimitados</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Evaluaciones (BDI-II, PHQ-9, GAD-7)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Teleconsulta</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Portal del paciente</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Facturación automática</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Soporte prioritario</span>
                        </li>
                    </ul>
                    <div class="bg-amber-50 border border-amber-300 rounded-lg p-3 mb-4 text-sm text-text-secondary">
                        <p><strong>Ejemplo:</strong> 20 pacientes = <span class="line-through">€20/mes</span> → <strong class="text-primary-600 text-lg">€15/mes</strong></p>
                    </div>
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-center px-6 py-3 rounded-lg font-semibold transition-colors">
                        Empezar Gratis
                    </a>
                </div>

                {{-- Plan Equipo --}}
                <div class="bg-background rounded-lg p-8 border-2 border-gray-200 relative">
                    <div class="absolute top-0 right-0 bg-amber-500 text-white px-3 py-1 rounded-bl-lg rounded-tr-lg text-xs font-semibold">
                        🎁 Beta: €1.50/pac.
                    </div>
                    <h3 class="text-2xl font-semibold text-text-primary mb-2 mt-4">🏢 Equipo</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-gray-400 line-through">€2</span>
                            <span class="text-4xl font-bold text-text-primary">€1.50</span>
                            <span class="text-text-secondary">/paciente</span>
                        </div>
                        <p class="text-sm text-success-600 font-semibold mt-1">25% descuento de por vida (early adopters)</p>
                        <p class="text-xs text-text-secondary mt-1">Sin cuota fija · Multi-profesional</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary"><strong>Todo lo de Pro +</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Múltiples profesionales</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Roles y permisos</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Dashboard clínica</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Informes avanzados</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Acceso API</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Soporte dedicado</span>
                        </li>
                    </ul>
                    <div class="bg-amber-50 border border-amber-300 rounded-lg p-3 mb-4 text-sm text-text-secondary">
                        <p><strong>Ejemplo:</strong> 50 pacientes = <span class="line-through">€100/mes</span> → <strong class="text-text-primary text-lg">€75/mes</strong></p>
                    </div>
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 text-text-primary text-center px-6 py-3 rounded-lg font-semibold transition-colors">
                        Empezar Gratis
                    </a>
                </div>
            </div>

            {{-- Calculadora de Precios --}}
            <div class="max-w-md mx-auto mt-12 bg-background rounded-lg p-6 border-2 border-primary-200 shadow-lg">
                <h3 class="text-lg font-semibold mb-4 text-center text-text-primary">💡 Calcula tu precio mensual</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2 text-text-primary">Pacientes activos este mes:</label>
                    <input type="number" id="activePatients" value="5" min="0" max="200" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center p-2 bg-gradient-to-r from-success-50 to-primary-50 rounded">
                        <span class="text-text-secondary">Gratis:</span>
                        <strong class="text-success-600" id="freePrice">€0/mes ✅</strong>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-primary-50 rounded">
                        <span class="text-text-secondary">Pro:</span>
                        <strong class="text-primary-600" id="proPrice">€5/mes</strong>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                        <span class="text-text-secondary">Equipo:</span>
                        <strong class="text-text-primary" id="teamPrice">€10/mes</strong>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <p class="text-text-secondary mb-2">
                    Empieza con el plan gratuito y actualiza solo cuando crezcas.
                </p>
                <p class="text-sm text-text-secondary">
                    💡 <strong>¿Tienes dudas?</strong> Calcula tu precio exacto según tus pacientes activos.
                </p>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('activePatients');
            const freePrice = document.getElementById('freePrice');
            const proPrice = document.getElementById('proPrice');
            const teamPrice = document.getElementById('teamPrice');

            function calculatePrices() {
                const patients = parseInt(input.value) || 0;
                
                // Free: €0 hasta 5 pacientes (early adopters)
                if (patients <= 5) {
                    freePrice.textContent = `€0/mes ✅`;
                } else {
                    freePrice.textContent = `Solo hasta 5 pac.`;
                }
                
                // Pro: €0.75 por paciente (25% descuento)
                const pro = patients * 0.75;
                proPrice.textContent = `€${pro.toFixed(2)}/mes`;
                
                // Equipo: €1.50 por paciente (25% descuento)
                const team = patients * 1.50;
                teamPrice.textContent = `€${team.toFixed(2)}/mes`;
            }

            input.addEventListener('input', calculatePrices);
            calculatePrices();
        });
    </script>
    @endpush

    {{-- FAQ Section --}}
    <section id="faq" class="py-20 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-text-primary mb-4">
                    Preguntas Frecuentes sobre Clinora
                </h2>
            </div>

            <div class="max-w-3xl mx-auto space-y-6">
                @push('structured_data')
                <script type="application/ld+json">
                {
                  "@@context": "https://schema.org",
                  "@@type": "FAQPage",
                  "mainEntity": [
                    {
                      "@@type": "Question",
                      "name": "¿Es seguro almacenar datos de pacientes?",
                      "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Sí, Clinora utiliza encriptación end-to-end, cumple con GDPR y LOPD, realiza backups automáticos y tiene auditoría de accesos. Todos los datos están protegidos con los más altos estándares de seguridad."
                      }
                    },
                    {
                      "@@type": "Question",
                      "name": "¿Puedo usar Clinora en múltiples dispositivos?",
                      "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Sí, Clinora es una plataforma web responsive que funciona perfectamente en ordenadores, tablets y móviles. Puedes acceder desde cualquier dispositivo con conexión a internet."
                      }
                    },
                    {
                      "@@type": "Question",
                      "name": "¿Cómo funciona la teleconsulta?",
                      "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "La teleconsulta de Clinora permite videollamadas HD, chat en tiempo real, compartir pantalla y grabación de sesiones (con consentimiento). Todo integrado directamente en la plataforma, sin necesidad de aplicaciones externas."
                      }
                    }
                  ]
                }
                </script>
                @endpush

                {{-- FAQ Item 1 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Es seguro almacenar datos de pacientes?
                    </h3>
                    <p class="text-text-secondary">
                        Sí, Clinora utiliza encriptación end-to-end, cumple con GDPR y LOPD, realiza backups automáticos y tiene auditoría de accesos. Todos los datos están protegidos con los más altos estándares de seguridad.
                    </p>
                </div>

                {{-- FAQ Item 2 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Puedo usar Clinora en múltiples dispositivos?
                    </h3>
                    <p class="text-text-secondary">
                        Sí, Clinora es una plataforma web responsive que funciona perfectamente en ordenadores, tablets y móviles. Puedes acceder desde cualquier dispositivo con conexión a internet.
                    </p>
                </div>

                {{-- FAQ Item 3 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Cómo funciona la teleconsulta?
                    </h3>
                    <p class="text-text-secondary">
                        La teleconsulta de Clinora permite videollamadas HD, chat en tiempo real, compartir pantalla y grabación de sesiones (con consentimiento). Todo integrado directamente en la plataforma, sin necesidad de aplicaciones externas.
                    </p>
                </div>

                {{-- FAQ Item 4 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Qué métodos de pago aceptan los pacientes?
                    </h3>
                    <p class="text-text-secondary">
                        Los pacientes pueden pagar mediante tarjeta de crédito/débito (Stripe), PayPal, transferencia bancaria o en efectivo. Todas las transacciones están protegidas y encriptadas.
                    </p>
                </div>

                {{-- FAQ Item 5 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Puedo migrar mis datos desde otro software?
                    </h3>
                    <p class="text-text-secondary">
                        Sí, ofrecemos migración gratuita de datos desde otros sistemas. Nuestro equipo te ayudará a importar pacientes, citas e historiales de forma segura y sin pérdida de información.
                    </p>
                </div>

                {{-- FAQ Item 6 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Ofrecen soporte técnico?
                    </h3>
                    <p class="text-text-secondary">
                        Sí, ofrecemos soporte por email para todos los planes y soporte prioritario para planes Profesional y Empresa. También tenemos documentación completa y centro de ayuda online.
                    </p>
                </div>

                {{-- FAQ Item 7 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Cumple con la normativa de protección de datos?
                    </h3>
                    <p class="text-text-secondary">
                        Absolutamente. Clinora cumple con GDPR (Reglamento General de Protección de Datos) y LOPD (Ley Orgánica de Protección de Datos). Todos los datos están encriptados y almacenados en servidores seguros dentro de la UE.
                    </p>
                </div>

                {{-- FAQ Item 8 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Hay límite de citas o pacientes?
                    </h3>
                    <p class="text-text-secondary">
                        No hay límite en el número de citas para ningún plan. Pagas solo por los pacientes activos (con al menos una cita, nota clínica o teleconsulta en los últimos 30 días). Esto significa que si un paciente no tiene actividad en un mes, no se cuenta en tu facturación.
                    </p>
                </div>

                {{-- FAQ Item 9 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ¿Qué es un "paciente activo"?
                    </h3>
                    <p class="text-text-secondary">
                        Un paciente activo es aquel que ha tenido al menos una cita, nota clínica o teleconsulta en los últimos 30 días. Esto te permite pagar solo por los pacientes que realmente usas el servicio, no por todos los que tienes registrados. Es un modelo justo y transparente que escala con tu negocio.
                    </p>
                </div>
            </div>
        </div>
    </section>

@endsection


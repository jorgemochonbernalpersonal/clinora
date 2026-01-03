@extends('layouts.app')

@section('title', $meta['title'] ?? 'Clinora - Software de Gestión para Clínicas de Salud')
@section('meta_title', $meta['title'] ?? 'Clinora - Software de Gestión para Clínicas de Salud | Prueba Gratis')
@section('meta_description', $meta['description'] ?? 'Gestiona tu clínica de salud con Clinora. Software SaaS para psicólogos, fisioterapeutas y nutricionistas.')
@section('meta_keywords', $meta['keywords'] ?? 'software gestión clínica, software psicólogos, telemedicina')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "SoftwareApplication",
  "name": "Clinora",
  "applicationCategory": "HealthApplication",
  "operatingSystem": "Web",
  "offers": {
    "@@type": "Offer",
    "price": "1",
    "priceCurrency": "EUR",
    "priceValidUntil": "2025-12-31",
    "availability": "https://schema.org/InStock",
    "url": "https://clinora.com/precios",
    "description": "Desde €1 por paciente activo al mes"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "4.8",
    "reviewCount": "127"
  },
  "description": "Plataforma SaaS para gestión de clínicas de salud y bienestar",
  "screenshot": "https://clinora.com/images/dashboard.jpg",
  "featureList": [
    "Gestión de citas",
    "Teleconsulta",
    "Facturación",
    "Portal del paciente",
    "Notas clínicas"
  ]
}
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
                    <a href="{{ route('register') }}" 
                       class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors shadow-lg hover:shadow-xl">
                        Empieza Gratis
                    </a>
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
                    <div class="text-3xl mb-4">🧠</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Evaluaciones Psicológicas</h3>
                    <p class="text-text-secondary">
                        Registra resultados de tests, escalas y cuestionarios (BDI-II, ansiedad, etc.) directamente en la ficha del paciente.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">👥</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Portal del Paciente</h3>
                    <p class="text-text-secondary">
                        Tus pacientes pueden reservar citas online, firmar consentimientos y descargar facturas de forma autónoma.
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
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Notas de Sesión</h3>
                    <p class="text-text-secondary">
                        Plantillas personalizables para diferentes tipos de terapia (CBT, Psicoanálisis, Sistemica) y notas estructuradas.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-surface rounded-lg p-6 border border-gray-200">
                    <div class="text-3xl mb-4">💶</div>
                    <h3 class="text-xl font-semibold text-text-primary mb-2">Facturación Automatizada</h3>
                    <p class="text-text-secondary">
                        Genera facturas automáticamente tras cada sesión. Envío por email con un solo clic y control de pagos pendientes.
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
                <div class="bg-gradient-to-br from-success-50 to-primary-50 rounded-lg p-8 border-2 border-success-300">
                    <h3 class="text-2xl font-semibold text-text-primary mb-2">🆓 Gratis</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-primary">€0</span>
                            <span class="text-text-secondary">/mes</span>
                        </div>
                        <p class="text-sm text-text-secondary mt-2">Hasta 3 pacientes activos</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-success-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-text-secondary">Hasta 3 pacientes</span>
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
                    <div class="absolute top-0 right-0 bg-primary-500 text-white px-4 py-1 rounded-bl-lg rounded-tr-lg text-sm font-semibold">
                        Más Popular
                    </div>
                    <h3 class="text-2xl font-semibold text-text-primary mb-2">⭐ Pro</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-primary">€1</span>
                            <span class="text-text-secondary">/paciente activo</span>
                        </div>
                        <p class="text-sm text-text-secondary mt-2">Sin cuota fija · Pacientes ilimitados</p>
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
                    <div class="bg-primary-100 rounded-lg p-3 mb-4 text-sm text-text-secondary">
                        <p><strong>Ejemplo:</strong> 20 pacientes = <strong class="text-text-primary">€20/mes</strong></p>
                    </div>
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-center px-6 py-3 rounded-lg font-semibold transition-colors">
                        Empezar Gratis
                    </a>
                </div>

                {{-- Plan Equipo --}}
                <div class="bg-background rounded-lg p-8 border-2 border-gray-200">
                    <h3 class="text-2xl font-semibold text-text-primary mb-2">🏢 Equipo</h3>
                    <div class="mb-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-text-primary">€2</span>
                            <span class="text-text-secondary">/paciente activo</span>
                        </div>
                        <p class="text-sm text-text-secondary mt-2">Sin cuota fija · Multi-profesional</p>
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
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm text-text-secondary">
                        <p><strong>Ejemplo:</strong> 50 pacientes = <strong class="text-text-primary">€100/mes</strong></p>
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
                
                // Free: €0 hasta 3 pacientes
                if (patients <= 3) {
                    freePrice.textContent = `€0/mes ✅`;
                } else {
                    freePrice.textContent = `Solo hasta 3 pac.`;
                }
                
                // Pro: €1 por paciente
                const pro = patients * 1;
                proPrice.textContent = `€${pro}/mes`;
                
                // Equipo: €2 por paciente  
                const team = patients * 2;
                teamPrice.textContent = `€${team}/mes`;
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

    {{-- Final CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-primary-500 to-primary-600 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">
                    ¿Listo para transformar la gestión de tu clínica?
                </h2>
                <p class="text-xl mb-8 text-primary-100">
                    Únete a cientos de profesionales que ya confían en Clinora
                </p>
                <a href="{{ route('register') }}" 
                   class="inline-block bg-white text-primary-600 hover:bg-primary-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors shadow-lg hover:shadow-xl">
                    Crear Cuenta Gratuita
                </a>
                <div class="mt-6 flex flex-wrap justify-center gap-6 text-sm text-primary-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Sin tarjeta de crédito</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Cancelación en cualquier momento</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Migración de datos gratuita</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@extends('layouts.app')

@section('title', 'Contacto - Clinora')
@section('meta_title', 'Contacto - Clinora | Software de Gestión para Clínicas')
@section('meta_description', 'Contacta con el equipo de Clinora. Resolvemos tus dudas sobre nuestro software de gestión para psicólogos y clínicas de salud.')

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contacto - Clinora",
  "description": "Formulario de contacto de Clinora",
  "url": "{{ url('/contacto') }}",
  "mainEntity": {
    "@type": "Organization",
    "name": "Clinora",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "contactPoint": {
      "@type": "ContactPoint",
      "contactType": "customer support",
      "email": "info@clinora.es",
      "availableLanguage": ["es"]
    }
  }
}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-b from-primary-50 to-background py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-text-primary mb-4">
                    ¿Tienes alguna pregunta?
                </h1>
                <p class="text-xl text-text-secondary">
                    Estamos aquí para ayudarte. Completa el formulario y te responderemos lo antes posible.
                </p>
            </div>
        </div>
    </section>

    {{-- Contact Form Section --}}
    <section class="py-16 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    {{-- Contact Form --}}
                    <div class="bg-surface rounded-lg p-8 border border-gray-200">
                        <h2 class="text-2xl font-bold text-text-primary mb-6">Envíanos un mensaje</h2>
                        
                        <form action="#" method="POST" class="space-y-6">
                            @csrf
                            
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-text-primary mb-2">
                                    Nombre completo *
                                </label>
                                <input type="text" id="name" name="name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Tu nombre">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-text-primary mb-2">
                                    Email *
                                </label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="tu@email.com">
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-text-primary mb-2">
                                    Teléfono (opcional)
                                </label>
                                <input type="tel" id="phone" name="phone"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="+34 600 000 000">
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label for="subject" class="block text-sm font-medium text-text-primary mb-2">
                                    Asunto *
                                </label>
                                <select id="subject" name="subject" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Selecciona un tema</option>
                                    <option value="info">Información general</option>
                                    <option value="demo">Solicitar demo</option>
                                    <option value="pricing">Precios y planes</option>
                                    <option value="technical">Soporte técnico</option>
                                    <option value="other">Otro</option>
                                </select>
                            </div>

                            {{-- Message --}}
                            <div>
                                <label for="message" class="block text-sm font-medium text-text-primary mb-2">
                                    Mensaje *
                                </label>
                                <textarea id="message" name="message" required rows="5"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                          placeholder="Cuéntanos en qué podemos ayudarte..."></textarea>
                            </div>

                            {{-- Privacy Policy --}}
                            <div class="flex items-start">
                                <input type="checkbox" id="privacy" name="privacy" required
                                       class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                <label for="privacy" class="ml-2 text-sm text-text-secondary">
                                    Acepto la <a href="{{ route('legal.privacy') }}" class="text-primary-600 hover:text-primary-700 underline">política de privacidad</a> *
                                </label>
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit"
                                    class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                Enviar mensaje
                            </button>
                        </form>
                    </div>

                    {{-- Contact Info --}}
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold text-text-primary mb-6">Información de contacto</h2>
                            
                            <div class="space-y-6">
                                {{-- Email --}}
                                <div class="flex items-start gap-4">
                                    <div class="bg-primary-50 rounded-lg p-3">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-text-primary mb-1">Email</h3>
                                        <a href="mailto:info@clinora.es" class="text-primary-600 hover:text-primary-700">
                                            info@clinora.es
                                        </a>
                                    </div>
                                </div>

                                {{-- Response Time --}}
                                <div class="flex items-start gap-4">
                                    <div class="bg-primary-50 rounded-lg p-3">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-text-primary mb-1">Tiempo de respuesta</h3>
                                        <p class="text-text-secondary">24-48 horas laborables</p>
                                    </div>
                                </div>

                                {{-- Support --}}
                                <div class="flex items-start gap-4">
                                    <div class="bg-primary-50 rounded-lg p-3">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-text-primary mb-1">Soporte</h3>
                                        <p class="text-text-secondary">Lunes a Viernes, 9:00 - 18:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CTA Box --}}
                        <div class="bg-primary-50 rounded-lg p-6 border-2 border-primary-200">
                            <h3 class="font-bold text-text-primary mb-2">¿Prefieres probarlo directamente?</h3>
                            <p class="text-text-secondary text-sm mb-4">
                                Crea una cuenta gratuita y explora todas las funcionalidades sin compromisos.
                            </p>
                            <a href="{{ route('register') }}" 
                               class="inline-block w-full text-center bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                Crear cuenta gratis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQs Quick Links --}}
    <section class="py-16 bg-surface">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-text-primary mb-4">¿Tienes dudas frecuentes?</h2>
                <p class="text-text-secondary mb-8">
                    Quizás encuentres la respuesta en nuestras preguntas frecuentes
                </p>
                <a href="{{ url('/#faq') }}" 
                   class="inline-block bg-surface hover:bg-gray-100 text-text-primary border-2 border-gray-300 px-6 py-3 rounded-lg font-semibold transition-colors">
                    Ver Preguntas Frecuentes
                </a>
            </div>
        </div>
    </section>
@endsection

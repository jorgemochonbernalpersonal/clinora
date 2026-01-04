@extends('layouts.app')

@section('title', 'Política de Cookies - Clinora')
@section('meta_description', 'Información sobre las cookies que utiliza Clinora y cómo gestionarlas.')

@section('content')
<div class="bg-background min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-lg p-8 md:p-12">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                    🍪 Política de Cookies
                </h1>
                <p class="text-text-secondary">
                    Última actualización: {{ date('d/m/Y') }}
                </p>
            </div>

            {{-- Introducción --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">¿Qué son las cookies?</h2>
                <p class="text-text-secondary mb-4">
                    Las cookies son pequeños archivos de texto que los sitios web almacenan en tu dispositivo cuando los visitas. 
                    Se utilizan ampliamente para hacer que los sitios web funcionen de manera más eficiente, así como para 
                    proporcionar información a los propietarios del sitio.
                </p>
            </section>

            {{-- Tipos de cookies --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">Cookies que utiliza Clinora</h2>
                
                {{-- Cookies necesarias --}}
                <div class="bg-primary-50 border-l-4 border-primary-500 p-4 mb-4 rounded-r-lg">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        🔒 Cookies Estrictamente Necesarias
                    </h3>
                    <p class="text-text-secondary text-sm mb-3">
                        Estas cookies son esenciales para que puedas navegar por el sitio web y utilizar sus funciones.
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-primary-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Cookie</th>
                                    <th class="px-4 py-2 text-left">Propósito</th>
                                    <th class="px-4 py-2 text-left">Duración</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-b">
                                    <td class="px-4 py-2 font-mono text-xs">XSRF-TOKEN</td>
                                    <td class="px-4 py-2">Protección contra ataques CSRF</td>
                                    <td class="px-4 py-2">2 horas</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-2 font-mono text-xs">clinora_session</td>
                                    <td class="px-4 py-2">Identificación de sesión de usuario</td>
                                    <td class="px-4 py-2">2 horas</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-xs">remember_web_*</td>
                                    <td class="px-4 py-2">Función "Recordarme" en login</td>
                                    <td class="px-4 py-2">5 años</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Cookies funcionales --}}
                <div class="bg-secondary-50 border-l-4 border-secondary-500 p-4 mb-4 rounded-r-lg">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">
                        ⚙️ Cookies Funcionales
                    </h3>
                    <p class="text-text-secondary text-sm mb-3">
                        Estas cookies permiten que el sitio web recuerde las elecciones que haces y proporcionen funciones mejoradas.
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-secondary-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Cookie</th>
                                    <th class="px-4 py-2 text-left">Propósito</th>
                                    <th class="px-4 py-2 text-left">Duración</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-b">
                                    <td class="px-4 py-2 font-mono text-xs">cookies_accepted</td>
                                    <td class="px-4 py-2">Almacena tu preferencia de cookies</td>
                                    <td class="px-4 py-2">1 año</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-mono text-xs">theme_preference</td>
                                    <td class="px-4 py-2">Guarda tu preferencia de tema (oscuro/claro)</td>
                                    <td class="px-4 py-2">1 año</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- Gestión de cookies --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">Cómo gestionar las cookies</h2>
                <p class="text-text-secondary mb-4">
                    Puedes controlar y/o eliminar las cookies como desees. Puedes eliminar todas las cookies que ya están 
                    en tu dispositivo y puedes configurar la mayoría de los navegadores para evitar que se almacenen.
                </p>
                
                <div class="bg-accent-50 border border-accent-200 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-text-primary mb-2">Configuración del Navegador:</h3>
                    <ul class="space-y-2 text-sm text-text-secondary">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-accent-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Chrome:</strong> Configuración > Privacidad y seguridad > Cookies</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-accent-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Firefox:</strong> Opciones > Privacidad y seguridad > Cookies</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-accent-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span><strong>Safari:</strong> Preferencias > Privacidad > Cookies</span>
                        </li>
                    </ul>
                </div>

                <p class="text-text-secondary text-sm italic">
                    ⚠️ Ten en cuenta que si deshabilitas las cookies, algunas funciones del sitio pueden no funcionar correctamente.
                </p>
            </section>

            {{-- Más información --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">Más información</h2>
                <p class="text-text-secondary mb-4">
                    Para obtener más información sobre cómo protegemos tu privacidad, consulta nuestra:
                </p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('legal.privacy') }}" class="text-primary-600 hover:text-primary-700 underline">
                            📋 Política de Privacidad
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('legal.gdpr') }}" class="text-primary-600 hover:text-primary-700 underline">
                            🛡️ Información RGPD
                        </a>
                    </li>
                </ul>
            </section>

            {{-- Contacto --}}
            <section class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-text-primary mb-3">¿Tienes preguntas?</h2>
                <p class="text-text-secondary mb-2">
                    Si tienes alguna pregunta sobre nuestra política de cookies, contáctanos en:
                </p>
                <p class="text-primary-600 font-medium">
                    📧 info@clinora.es
                </p>
            </section>
        </div>
    </div>
</div>
@endsection

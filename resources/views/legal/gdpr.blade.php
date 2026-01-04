@extends('layouts.app')

@section('title', 'Información RGPD - Clinora')
@section('meta_description', 'Información detallada sobre el cumplimiento del RGPD en Clinora y tus derechos.')

@section('content')
<div class="bg-background min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-lg p-8 md:p-12">
            {{-- Header --}}
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center p-3 bg-success-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-2">
                    Cumplimiento RGPD
                </h1>
                <p class="text-text-secondary">
                    Reglamento General de Protección de Datos (UE) 2016/679
                </p>
            </div>

            <div class="prose prose-lg max-w-none text-text-secondary">
                <p>
                    Clinora está plenamente comprometida con el cumplimiento del Reglamento General de Protección de Datos (RGPD) 
                    y la Ley Orgánica de Protección de Datos y Garantía de Derechos Digitales (LOPDGDD).
                </p>
                <p>
                    A continuación detallamos cómo garantizamos tus derechos y protegemos la información.
                </p>
            </div>

            {{-- Información Clave Grid --}}
            <div class="grid md:grid-cols-2 gap-6 my-12">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">Responsable del Tratamiento</h3>
                    <p class="text-sm">
                        <strong>Identidad:</strong> Clinora S.L.<br>
                        <strong>Domicilio:</strong> Calle Principal 123, Madrid<br>
                        <strong>Email:</strong> info@clinora.es
                    </p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-text-primary mb-2">Delegado de Protección de Datos (DPO)</h3>
                    <p class="text-sm">
                        Hemos designado un DPO para velar por el cumplimiento normativo.<br>
                        <strong>Contacto:</strong> dpo@clinora.com
                    </p>
                </div>
            </div>

            {{-- Derechos ARCO --}}
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-text-primary mb-6">Tus Derechos (ARCO)</h2>
                <div class="space-y-4">
                    <div class="flex gap-4 p-4 bg-surface border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl">🔍</div>
                        <div>
                            <h3 class="font-semibold text-text-primary">Acceso</h3>
                            <p class="text-sm text-text-secondary">Derecho a saber qué datos tuyos estamos tratando.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 bg-surface border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl">✏️</div>
                        <div>
                            <h3 class="font-semibold text-text-primary">Rectificación</h3>
                            <p class="text-sm text-text-secondary">Derecho a corregir datos inexactos o incompletos.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 bg-surface border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl">🗑️</div>
                        <div>
                            <h3 class="font-semibold text-text-primary">Supresión ("Olvido")</h3>
                            <p class="text-sm text-text-secondary">Derecho a solicitar la eliminación de tus datos cuando ya no sean necesarios.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 bg-surface border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl">✋</div>
                        <div>
                            <h3 class="font-semibold text-text-primary">Oposición</h3>
                            <p class="text-sm text-text-secondary">Derecho a oponerte al tratamiento de tus datos en determinadas circunstancias.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-4 bg-surface border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl">📦</div>
                        <div>
                            <h3 class="font-semibold text-text-primary">Portabilidad</h3>
                            <p class="text-sm text-text-secondary">Derecho a recibir tus datos en un formato estructurado y de uso común.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Cómo ejercer derechos --}}
            <section class="bg-primary-50 border border-primary-200 rounded-lg p-8 text-center">
                <h2 class="text-2xl font-bold text-text-primary mb-4">¿Cómo ejercer tus derechos?</h2>
                <p class="text-text-secondary mb-6 max-w-2xl mx-auto">
                    Puedes ejercer cualquiera de estos derechos enviando una solicitud a nuestro equipo de privacidad. 
                    Te responderemos en un plazo máximo de 30 días.
                </p>
                <a href="mailto:info@clinora.es" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors shadow-lg">
                    Contactar con Privacidad
                </a>
            </section>

             {{-- Autoridad de Control --}}
             <div class="mt-12 text-center text-sm text-text-secondary">
                <p>
                    Si consideras que no hemos tratado tus datos adecuadamente, tienes derecho a presentar una reclamación ante la 
                    <a href="https://www.aepd.es" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:underline">Agencia Española de Protección de Datos (AEPD)</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

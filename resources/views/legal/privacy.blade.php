@extends('layouts.app')

@section('title', 'Política de Privacidad - Clinora')
@section('meta_description', 'Conoce cómo Clinora recopila, usa y protege tus datos personales y los de tus pacientes.')

@section('content')
<div class="bg-background min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-lg p-8 md:p-12">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                    🔒 Política de Privacidad
                </h1>
                <p class="text-text-secondary">
                    Última actualización: {{ date('d/m/Y') }}
                </p>
            </div>

            {{-- Introducción --}}
            <section class="mb-8">
                <p class="text-text-secondary mb-4">
                    En Clinora, nos tomamos muy en serio tu privacidad y la seguridad de los datos de tus pacientes. 
                    Esta Política de Privacidad describe cómo recopilamos, usamos y protegemos la información personal que nos proporcionas.
                </p>
            </section>

            {{-- 1. Información que recopilamos --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">1. Información que recopilamos</h2>
                <div class="space-y-4 text-text-secondary">
                    <p>Recopilamos diferentes tipos de información para prestarte el servicio:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Datos del Profesional:</strong> Nombre, dirección de correo electrónico, número de teléfono, número de colegiado y datos de facturación.</li>
                        <li><strong>Datos de Pacientes:</strong> Información que tú ingresas en la plataforma, como nombres, historias clínicas, notas de evolución y datos de contacto. Estos datos son procesados bajo tu estricta responsabilidad.</li>
                        <li><strong>Datos de Uso:</strong> Información sobre cómo interactúas con nuestra plataforma, dirección IP, tipo de navegador y páginas visitadas.</li>
                    </ul>
                </div>
            </section>

            {{-- 2. Base Legal --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">2. Base Legal para el Tratamiento</h2>
                <p class="text-text-secondary mb-4">
                    Tratamos tus datos basándonos en:
                </p>
                <div class="bg-secondary-50 border-l-4 border-secondary-500 p-4 rounded-r-lg">
                    <ul class="space-y-2 text-sm text-text-secondary">
                        <li>✅ <strong>Ejecución del contrato:</strong> Para prestarte el servicio de gestión clínica.</li>
                        <li>✅ <strong>Consentimiento:</strong> Para enviarte comunicaciones o procesar datos específicos.</li>
                        <li>✅ <strong>Interés legítimo:</strong> Para mejorar nuestro software y garantizar la seguridad.</li>
                        <li>✅ <strong>Obligación legal:</strong> Para cumplir con normativas fiscales y sanitarias.</li>
                    </ul>
                </div>
            </section>

            {{-- 3. Uso de la Información --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">3. Cómo usamos tu información</h2>
                <ul class="list-disc pl-5 space-y-2 text-text-secondary">
                    <li>Proveer, mantener y mejorar nuestros servicios.</li>
                    <li>Procesar transacciones y enviar facturas.</li>
                    <li>Enviar notificaciones técnicas, actualizaciones de seguridad y soporte.</li>
                    <li>Cumplir con obligaciones legales y normativas.</li>
                </ul>
            </section>

             {{-- 4. Seguridad de Datos --}}
             <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">4. Seguridad de los Datos</h2>
                <p class="text-text-secondary mb-4">
                    Implementamos medidas de seguridad técnicas y organizativas avanzadas:
                </p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-text-primary mb-2">Encriptación</h3>
                        <p class="text-sm text-text-secondary">Todos los datos se transmiten vía SSL/TLS y se almacenan encriptados en reposo.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-text-primary mb-2">Acceso Restringido</h3>
                        <p class="text-sm text-text-secondary">Autenticación de dos factores (2FA) y controles de acceso estrictos.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-text-primary mb-2">Backups</h3>
                        <p class="text-sm text-text-secondary">Copias de seguridad diarias automáticas y redundantes.</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-text-primary mb-2">Infraestructura</h3>
                        <p class="text-sm text-text-secondary">Servidores ubicados dentro de la Unión Europea.</p>
                    </div>
                </div>
            </section>

            {{-- 5. Tus Derechos --}}
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-text-primary mb-4">5. Tus Derechos</h2>
                <p class="text-text-secondary mb-4">
                    Como usuario, tienes derecho a acceder, rectificar, suprimir, limitar, oponerte y portar tus datos. 
                    Puedes ejercer estos derechos enviando un correo a <a href="mailto:privacy@clinora.com" class="text-primary-600 hover:underline">privacy@clinora.com</a>.
                </p>
                <p class="text-text-secondary mb-4">
                    Para más detalles sobre tus derechos bajo el RGPD, visita nuestra página de <a href="{{ route('legal.gdpr') }}" class="text-primary-600 hover:underline">Información RGPD</a>.
                </p>
            </section>

            {{-- Contacto --}}
            <section class="border-t border-gray-200 pt-8 mt-8">
                <p class="text-text-secondary text-sm">
                    Si tienes preguntas sobre esta Política de Privacidad, contáctanos en <a href="mailto:privacy@clinora.com" class="text-primary-600 hover:underline">privacy@clinora.com</a>.
                    Empresa Responsable: Clinora S.L. - Calle Principal 123, Madrid, España.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection

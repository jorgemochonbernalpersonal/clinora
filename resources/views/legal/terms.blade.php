@extends('layouts.app')

@section('title', 'Términos de Servicio - Clinora')
@section('meta_description', 'Términos y condiciones de uso de la plataforma Clinora.')

@section('content')
<div class="bg-background min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-surface rounded-lg shadow-lg p-8 md:p-12">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary mb-4">
                    📜 Términos de Servicio
                </h1>
                <p class="text-text-secondary">
                    Última actualización: {{ date('d/m/Y') }}
                </p>
            </div>

            {{-- 1. Aceptación --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">1. Aceptación de los Términos</h2>
                <p class="text-text-secondary">
                    Al registrarte y acceder a Clinora ("el Servicio"), aceptas quedar vinculado por estos Términos de Servicio. 
                    Si no estás de acuerdo con alguna parte de estos términos, no podrás acceder al Servicio.
                </p>
            </section>

            {{-- 2. Uso del Servicio --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">2. Uso del Servicio</h2>
                <ul class="list-disc pl-5 space-y-2 text-text-secondary">
                    <li>Debes ser un profesional de la salud habilitado o personal autorizado para usar este servicio.</li>
                    <li>Eres responsable de mantener la seguridad de tu cuenta y contraseña.</li>
                    <li>Eres el único responsable de la exactitud y legalidad de los datos de pacientes que ingresas.</li>
                    <li>El uso del servicio para actividades ilegales o no autorizadas está estrictamente prohibido.</li>
                </ul>
            </section>

            {{-- 3. Planes y Pagos --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">3. Planes y Facturación</h2>
                <div class="space-y-4 text-text-secondary">
                    <p>
                        <strong>Modelo de Precios:</strong> Clinora opera bajo un modelo de pago por uso basado en "pacientes activos".
                        Un paciente activo se define como aquel que ha tenido al menos una cita, nota clínica o interacción en los últimos 30 días.
                    </p>
                    <p>
                        <strong>Facturación:</strong> El servicio se factura mensualmente. Puedes cancelar tu suscripción en cualquier momento.
                    </p>
                    <p>
                        <strong>Cambios de Precio:</strong> Clinora se reserva el derecho de modificar las tarifas con un aviso previo de 30 días.
                    </p>
                </div>
            </section>

            {{-- 4. Datos y Propiedad --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">4. Propiedad de los Datos</h2>
                <p class="text-text-secondary mb-3">
                    Tú conservas todos los derechos sobre los datos que introduces en Clinora. Nosotros no reclamamos la propiedad sobre la información de tus pacientes.
                </p>
                <div class="bg-primary-50 border-l-4 border-primary-500 p-4 rounded-r-lg">
                    <p class="text-sm text-text-secondary">
                        Podemos acceder a tus datos solo para fines de soporte técnico, copias de seguridad o cuando la ley lo requiera, siempre bajo estrictos acuerdos de confidencialidad.
                    </p>
                </div>
            </section>

            {{-- 5. Limitación de Responsabilidad --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">5. Limitación de Responsabilidad</h2>
                <p class="text-text-secondary">
                    Clinora se proporciona "tal cual". No garantizamos que el servicio sea ininterrumpido o libre de errores. 
                    En ningún caso Clinora será responsable por daños indirectos, incidentales o consecuentes derivados del uso del servicio.
                </p>
            </section>

            {{-- 6. Terminación --}}
            <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">6. Terminación</h2>
                <p class="text-text-secondary">
                    Podemos suspender o terminar tu acceso al servicio inmediatamente, sin previo aviso, si incumples estos Términos.
                    A tu solicitud, proporcionaremos una exportación de tus datos antes de eliminar tu cuenta permanentemente.
                </p>
            </section>

             {{-- 7. Ley Aplicable --}}
             <section class="mb-8">
                <h2 class="text-xl font-semibold text-text-primary mb-3">7. Ley Aplicable</h2>
                <p class="text-text-secondary">
                    Estos términos se regirán e interpretarán de acuerdo con las leyes de España.
                </p>
            </section>

            <div class="text-center mt-12">
                <a href="{{ url('/') }}" class="text-primary-600 font-semibold hover:underline">Volver al inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection

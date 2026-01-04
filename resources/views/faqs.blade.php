@extends('layouts.app')

@section('title', 'Preguntas Frecuentes - Clinora')
@section('meta_title', 'Preguntas Frecuentes - Clinora | Software de Gestión para Clínicas')
@section('meta_description', 'Encuentra respuestas a las preguntas más frecuentes sobre Clinora, el software de gestión para psicólogos y clínicas de salud.')

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "¿Es seguro almacenar datos de pacientes?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sí, Clinora utiliza encriptación end-to-end, cumple con GDPR y LOPD, realiza backups automáticos y tiene auditoría de accesos. Todos los datos están protegidos con los más altos estándares de seguridad."
      }
    },
    {
      "@type": "Question",
      "name": "¿Puedo usar Clinora en múltiples dispositivos?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sí, Clinora es una plataforma web responsive que funciona perfectamente en ordenadores, tablets y móviles. Puedes acceder desde cualquier dispositivo con conexión a internet."
      }
    },
    {
      "@type": "Question",
      "name": "¿Cómo funciona la teleconsulta?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "La teleconsulta de Clinora permite videollamadas HD, chat en tiempo real, compartir pantalla y grabación de sesiones (con consentimiento). Todo integrado directamente en la plataforma, sin necesidad de aplicaciones externas."
      }
    },
{
      "@type": "Question",
      "name": "¿Qué métodos de pago aceptan los pacientes?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Los pacientes pueden pagar mediante tarjeta de crédito/débito (Stripe), PayPal, transferencia bancaria o en efectivo. Todas las transacciones están protegidas y encriptadas."
      }
    },
    {
      "@type": "Question",
      "name": "¿Puedo migrar mis datos desde otro software?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sí, ofrecemos migración gratuita de datos desde otros sistemas. Nuestro equipo te ayudará a importar pacientes, citas e historiales de forma segura y sin pérdida de información."
      }
    },
    {
      "@type": "Question",
      "name": "¿Ofrecen soporte técnico?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Sí, ofrecemos soporte por email para todos los planes y soporte prioritario para planes Profesional y Empresa. También tenemos documentación completa y centro de ayuda online."
      }
    },
    {
      "@type": "Question",
      "name": "¿Cumple con la normativa de protección de datos?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Absolutamente. Clinora cumple con GDPR (Reglamento General de Protección de Datos) y LOPD (Ley Orgánica de Protección de Datos). Todos los datos están encriptados y almacenados en servidores seguros dentro de la UE."
      }
    },
    {
      "@type": "Question",
      "name": "¿Hay límite de citas o pacientes?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No hay límite en el número de citas para ningún plan. Pagas solo por los pacientes activos (con al menos una cita, nota clínica o teleconsulta en los últimos 30 días). Esto significa que si un paciente no tiene actividad en un mes, no se cuenta en tu facturación."
      }
    },
    {
      "@type": "Question",
      "name": "¿Qué es un 'paciente activo'?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Un paciente activo es aquel que ha tenido al menos una cita, nota clínica o teleconsulta en los últimos 30 días. Esto te permite pagar solo por los pacientes que realmente usas el servicio, no por todos los que tienes registrados. Es un modelo justo y transparente que escala con tu negocio."
      }
    }
  ]
}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-b from-primary-50 to-background py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-text-primary mb-4">
                    Preguntas Frecuentes
                </h1>
                <p class="text-xl text-text-secondary">
                    Encuentra respuestas a las dudas más comunes sobre Clinora
                </p>
            </div>
        </div>
    </section>

    {{-- FAQs Section --}}
    <section class="py-20 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-6">
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

    {{-- CTA Section --}}
    <section class="py-16 bg-primary-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl font-bold text-text-primary mb-4">
                    ¿No encuentras lo que buscas?
                </h2>
                <p class="text-text-secondary mb-6">
                    Contáctanos y estaremos encantados de ayudarte
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('contact') }}" 
                       class="bg-primary-500 hover:bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Contactar
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-white hover:bg-gray-50 text-primary-600 border-2 border-primary-500 px-8 py-3 rounded-lg font-semibold transition-colors">
                        Probar gratis
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

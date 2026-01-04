@extends('emails.layouts.base')

@section('content')
    <h1>🎉 ¡Tu clínica está creciendo!</h1>
    
    <p>Hola {{ $professional->user->first_name }},</p>
    
    <p>
        ¡Felicidades! Has alcanzado <strong>{{ $currentPatients }} de {{ $limit }} pacientes</strong> 
        en tu plan Gratis. Esto significa que tu práctica está creciendo, ¡y eso es fantástico! 🌱
    </p>
    
    <div class="success-box">
        <p>
            <strong>📊 Tu progreso:</strong><br>
            {{ $currentPatients }}/{{ $limit }} pacientes utilizados ({{ $percentage }}%)
        </p>
    </div>
    
    <p>
        Cuando agregues tu próximo paciente, alcanzarás el límite de tu plan actual. 
        Pero no te preocupes, ¡tenemos planes diseñados para acompañar tu crecimiento!
    </p>
    
    <h2 style="color: #1e293b; font-size: 20px; margin: 30px 0 15px 0;">
        ¿Qué obtienes con el Plan Pro?
    </h2>
    
    <ul style="color: #475569; line-height: 1.8; padding-left: 20px;">
        <li><strong>Pacientes ilimitados</strong> - Crece sin límites</li>
        <li><strong>Teleconsulta</strong> - Atiende a distancia con videollamadas</li>
        <li><strong>Evaluaciones psicológicas</strong> - BDI-II, PHQ-9, GAD-7 y más</li>
        <li><strong>Portal del paciente</strong> - Tus pacientes reservan citas online</li>
        <li><strong>Facturación automática</strong> - Genera facturas con un clic</li>
        <li><strong>Soporte prioritario</strong> - Respuestas rápidas cuando las necesites</li>
    </ul>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="mailto:info@clinora.es?subject=Consulta sobre Plan Pro&body=Hola, me gustaría información sobre el Plan Pro." 
           class="button">
            📧 Consultar Plan Pro
        </a>
    </div>
    
    <div style="background-color: #f0f9ff; border-radius: 8px; padding: 20px; margin: 25px 0;">
        <p style="margin: 0; color: #0369a1; font-size: 14px; text-align: center;">
            <strong>💰 Precio transparente:</strong> €1 por paciente activo al mes<br>
            <span style="font-size: 12px; color: #64748b;">
                Paciente activo = con al menos una cita o nota en los últimos 30 días
            </span>
        </p>
    </div>
    
    <p style="font-size: 14px; color: #64748b; margin-top: 30px;">
        ¿Tienes preguntas? Estamos aquí para ayudarte. Responde a este email o contáctanos en 
        <a href="mailto:info@clinora.es" style="color: #0EA5E9;">info@clinora.es</a>
    </p>
    
    <p style="margin-top: 30px;">
        Un saludo,<br>
        <strong>El equipo de Clinora</strong>
    </p>
@endsection

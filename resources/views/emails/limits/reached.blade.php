@extends('emails.layouts.base')

@section('content')
    <h1>🚀 ¡Has alcanzado el límite de tu plan!</h1>
    
    <p>Hola {{ $professional->user->first_name }},</p>
    
    <p>
        ¡Enhorabuena! Has llegado al máximo de <strong>{{ $limit }} pacientes</strong> 
        de tu plan Gratis. Esto es una gran señal de que tu práctica está prosperando. 👏
    </p>
    
    <div class="alert-box">
        <p>
            <strong>⚠️ Importante:</strong> El siguiente paciente que intentes agregar 
            requerirá actualizar a un plan superior.
        </p>
    </div>
    
    <h2 style="color: #1e293b; font-size: 20px; margin: 30px 0 15px 0;">
        Da el siguiente paso con el Plan Pro
    </h2>
    
    <p>
        Diseñado específicamente para profesionales en crecimiento como tú:
    </p>
    
    <div class="stats-container" style="display: table; width: 100%; margin: 25px 0;">
        <div style="display: table-row;">
            <div class="stat-card" style="display: table-cell; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; margin-right: 10px;">
                <p class="stat-number" style="font-size: 32px; font-weight: 700; color: #f59e0b; margin: 0;">
                    ∞
                </p>
                <p class="stat-label" style="font-size: 14px; color: #92400e; margin: 5px 0 0 0;">
                    Pacientes Ilimitados
                </p>
            </div>
            <div class="stat-card" style="display: table-cell; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #0EA5E9; padding: 20px; border-radius: 8px;">
                <p class="stat-number" style="font-size: 32px; font-weight: 700; color: #0EA5E9; margin: 0;">
                    €1
                </p>
                <p class="stat-label" style="font-size: 14px; color: #0369a1; margin: 5px 0 0 0;">
                    por paciente/mes*
                </p>
            </div>
        </div>
    </div>
    
    <p style="font-size: 12px; color: #94a3b8; margin-top: 5px;">
        * Solo pagas por pacientes activos (con cita o nota en los últimos 30 días)
    </p>
    
    <h3 style="color: #1e293b; font-size: 18px; margin: 25px 0 10px 0;">
        ✨ Todo lo incluido en Pro:
    </h3>
    
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr>
            <td style="padding: 12px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <strong style="color: #10b981;">✓</strong> Pacientes ilimitados
            </td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                <strong style="color: #10b981;">✓</strong> Teleconsulta con videollamadas HD
            </td>
        </tr>
        <tr>
            <td style="padding: 12px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <strong style="color: #10b981;">✓</strong> Evaluaciones psicológicas (BDI-II, PHQ-9, GAD-7)
            </td>
        </tr>
        <tr>
            <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                <strong style="color: #10b981;">✓</strong> Portal del paciente (reservas online)
            </td>
        </tr>
        <tr>
            <td style="padding: 12px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <strong style="color: #10b981;">✓</strong> Facturación automática
            </td>
        </tr>
        <tr>
            <td style="padding: 12px;">
                <strong style="color: #10b981;">✓</strong> Soporte prioritario
            </td>
        </tr>
    </table>
    
    <div style="text-align: center; margin: 35px 0;">
        <a href="mailto:info@clinora.es?subject=Actualizar a Plan Pro&body=Hola, he alcanzado el límite de mi plan Gratis y me gustaría actualizar a Pro." 
           class="button">
            🚀 Actualizar a Plan Pro
        </a>
    </div>
    
    <div style="background-color: #ecfccb; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #84cc16;">
        <p style="margin: 0; color: #3f6212; font-size: 15px;">
            <strong>💡 Ejemplo de precio:</strong><br>
            Si tienes 10 pacientes activos este mes = <strong>€10/mes</strong><br>
            Si tienes 20 pacientes activos este mes = <strong>€20/mes</strong><br>
            <br>
            <span style="font-size: 13px;">Sin cuotas fijas. Sin sorpresas. Creces cuando creces.</span>
        </p>
    </div>
    
    <p style="font-size: 14px; color: #64748b; margin-top: 30px; line-height: 1.6;">
        ¿Dudas? Escríbenos a <a href="mailto:info@clinora.es" style="color: #0EA5E9;">info@clinora.es</a> 
        o responde directamente a este email. Estaremos encantados de ayudarte a elegir el plan perfecto para ti.
    </p>
    
    <p style="margin-top: 35px;">
        ¡Gracias por confiar en Clinora! 🙏<br>
        <strong>El equipo de Clinora</strong>
    </p>
@endsection

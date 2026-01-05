@extends('emails.layouts.base')

@section('content')
    <h1>📊 Tu resumen semanal - Clinora</h1>
    
    <p>Hola {{ $professional->user->first_name }},</p>
    
    <p>
        Aquí está tu resumen de actividad de la semana del 
        <strong>{{ $weekStart->format('d/m/Y') }}</strong> al 
        <strong>{{ $weekEnd->format('d/m/Y') }}</strong>:
    </p>
    
    <!-- Stats Cards -->
    <div class="stats-container" style="display: table; width: 100%; margin: 30px 0;">
        <div style="display: table-row;">
            <div class="stat-card" style="display: table-cell; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #0EA5E9; padding: 20px; border-radius: 8px; margin-right: 10px; width: 50%;">
                <p class="stat-number" style="font-size: 36px; font-weight: 700; color: #0EA5E9; margin: 0;">
                    {{ $stats['appointments_completed'] }}
                </p>
                <p class="stat-label" style="font-size: 14px; color: #0369a1; margin: 5px 0 0 0;">
                    Citas Completadas
                </p>
            </div>
            <div class="stat-card" style="display: table-cell; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; width: 50%;">
                <p class="stat-number" style="font-size: 36px; font-weight: 700; color: #f59e0b; margin: 0;">
                    {{ $stats['clinical_notes_created'] }}
                </p>
                <p class="stat-label" style="font-size: 14px; color: #92400e; margin: 5px 0 0 0;">
                    Notas Creadas
                </p>
            </div>
        </div>
    </div>
    
    @if($stats['appointments_completed'] > 0 || $stats['clinical_notes_created'] > 0)
        <div class="success-box">
            <p>
                <strong>🎉 ¡Gran trabajo esta semana!</strong><br>
                Has mantenido una actividad consistente en tu práctica.
            </p>
        </div>
    @endif
    
    <!-- Upcoming Appointments -->
    @if(count($upcomingAppointments) > 0)
        <h2 style="color: #1e293b; font-size: 20px; margin: 30px 0 15px 0;">
            📅 Próximas citas de la semana
        </h2>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            @foreach($upcomingAppointments as $appointment)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; background-color: {{ $loop->even ? '#f8fafc' : 'transparent' }};">
                        <strong style="color: #1e293b;">{{ $appointment->contact->full_name }}</strong><br>
                        <span style="color: #64748b; font-size: 14px;">
                            📆 {{ $appointment->start_time->format('d/m/Y') }} a las {{ $appointment->start_time->format('H:i') }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </table>
        
        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ profession_route('appointments.index') }}" class="button">
                Ver todas las citas
            </a>
        </div>
    @else
        <div style="background-color: #f8fafc; border-radius: 8px; padding: 20px; margin: 25px 0;">
            <p style="margin: 0; color: #64748b; text-align: center;">
                No tienes citas programadas para la próxima semana
            </p>
        </div>
    @endif
    
    <!-- Weekly Tip -->
    <div style="background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%); border-radius: 8px; padding: 25px; margin: 30px 0; border-left: 4px solid #14b8a6;">
        <h3 style="color: #134e4a; margin: 0 0 10px 0; font-size: 18px;">
            💡 Consejo de la semana
        </h3>
        <p style="margin: 0; color: #115e59; line-height: 1.6;">
            {{ $weeklyTip }}
        </p>
    </div>
    
    <!-- Plan Info for Free Users -->
    @if($professional->isOnFreePlan())
        <div style="background-color: #fef3c7; border-radius: 8px; padding: 20px; margin: 25px 0; border-left: 4px solid #f59e0b;">
            <p style="margin: 0 0 10px 0; color: #92400e;">
                <strong>💼 Tu plan actual: Gratis ({{ $stats['total_patients'] }}/3 pacientes)</strong>
            </p>
            <p style="margin: 0; color: #92400e; font-size: 14px;">
                ¿Tu práctica está creciendo? 
                <a href="mailto:info@clinora.es?subject=Consulta Plan Pro" style="color: #0EA5E9; text-decoration: underline;">
                    Descubre el Plan Pro
                </a> 
                para gestionar pacientes ilimitados y más funciones.
            </p>
        </div>
    @endif
    
    <p style="margin-top: 30px;">
        ¡Que tengas una excelente semana! 🌟<br>
        <strong>El equipo de Clinora</strong>
    </p>
    
    <p style="font-size: 13px; color: #94a3b8; margin-top: 25px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
        Recibes este email porque estás suscrito a los resúmenes semanales de Clinora. 
        Puedes <a href="{{ route('profile.settings') }}" style="color: #0EA5E9;">gestionar tus preferencias</a> en cualquier momento.
    </p>
@endsection

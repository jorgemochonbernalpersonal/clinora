@extends('layouts.dashboard')

@section('title', 'Gestión de Suscripción')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary">
            Gestión de Suscripción
        </h1>
        <p class="text-text-secondary mt-1">
            Administra tu plan y revisa tu uso mensual
        </p>
    </div>

    <!-- Current Plan Card -->
    <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-8 mb-8 border-2 border-primary-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-text-primary mb-1">
                    Plan {{ $professional->subscription_plan->label() }}
                </h2>
                <p class="text-text-secondary">
                    @if($professional->isOnFreePlan())
                        Gratis para siempre
                    @elseif($professional->isOnProPlan())
                        €1 por paciente activo/mes
                    @else
                        €2 por paciente activo/mes
                    @endif
                </p>
            </div>
            <x-plan-badge :plan="$professional->subscription_plan->value" class="text-lg px-4 py-2" />
        </div>

        <!-- Usage Stats -->
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6">
                <p class="text-sm text-text-secondary mb-1">Pacientes Totales</p>
                <p class="text-3xl font-bold text-text-primary">{{ $stats['total_patients'] }}</p>
                @if($professional->isOnFreePlan())
                    <p class="text-sm text-warning-600 mt-2">Límite: {{ $stats['patient_limit'] }}</p>
                @endif
            </div>
            <div class="bg-white rounded-lg p-6">
                <p class="text-sm text-text-secondary mb-1">Pacientes Activos (este mes)</p>
                <p class="text-3xl font-bold text-primary-600">{{ $stats['active_patients'] }}</p>
                <p class="text-sm text-text-secondary mt-2">Con actividad últimos 30 días</p>
            </div>
            <div class="bg-white rounded-lg p-6">
                <p class="text-sm text-text-secondary mb-1">Costo Estimado (este mes)</p>
                <p class="text-3xl font-bold text-success-600">€{{ number_format($estimatedCost, 2) }}</p>
                @if(!$professional->isOnFreePlan())
                    <p class="text-sm text-text-secondary mt-2">{{ $stats['active_patients'] }} × €{{ $professional->isOnProPlan() ? '1' : '2' }}</p>
                @endif
            </div>
        </div>

        @if($professional->isOnFreePlan())
            <div class="mt-6 flex justify-center">
                <a href="mailto:info@clinora.es?subject=Actualizar Plan" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    Actualizar a Pro o Equipo
                </a>
            </div>
        @endif
    </div>

    <!-- Monthly History -->
    <div class="bg-white rounded-xl shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold text-text-primary mb-6">Historial de Uso (últimos 6 meses)</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-3 px-4 text-text-secondary font-medium">Mes</th>
                        <th class="text-center py-3 px-4 text-text-secondary font-medium">Pacientes Activos</th>
                        <th class="text-right py-3 px-4 text-text-secondary font-medium">Costo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyHistory as $month)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-text-primary font-medium">{{ $month['month_full'] }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center gap-2">
                                    <span class="text-2xl font-bold text-primary-600">{{ $month['active_patients'] }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <span class="text-lg font-semibold text-success-600">
                                    €{{ number_format($month['cost'], 2) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Email Preferences -->
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-text-primary mb-6">Preferencias de Notificaciones</h2>
        
        <form action="{{ route('subscription.update-preferences') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4 mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="email_limit_alerts" 
                           value="1"
                           {{ auth()->user()->email_limit_alerts ?? true ? 'checked' : '' }}
                           class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                    <div>
                        <div class="font-medium text-text-primary">Alertas de Límite</div>
                        <div class="text-sm text-text-secondary">Recibir emails cuando alcances 66% o 100% del límite de pacientes</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="email_weekly_summary" 
                           value="1"
                           {{ auth()->user()->email_weekly_summary ?? true ? 'checked' : '' }}
                           class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                    <div>
                        <div class="font-medium text-text-primary">Resumen Semanal</div>
                        <div class="text-sm text-text-secondary">Recibir un resumen de tu actividad cada domingo</div>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           name="email_marketing" 
                           value="1"
                           {{ auth()->user()->email_marketing ?? true ? 'checked' : '' }}
                           class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                    <div>
                        <div class="font-medium text-text-primary">Noticias y Actualizaciones</div>
                        <div class="text-sm text-text-secondary">Recibir información sobre nuevas funciones y mejoras</div>
                    </div>
                </label>
            </div>

            <button type="submit" 
                    class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                Guardar Preferencias
            </button>
        </form>
    </div>
@endsection

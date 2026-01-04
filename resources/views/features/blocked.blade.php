@props(['feature', 'requiredPlan' => 'Pro', 'featureTitle', 'featureDescription', 'benefits' => []])

@php
    use App\Core\Subscriptions\Services\PlanLimitsService;
    
    $planLimits = app(PlanLimitsService::class);
    $professional = auth()->user()->professional;
    
    // Get feature details
    $displayFeature = $planLimits->getFeatureName($feature);
    $requiredPlanEnum = $planLimits->getRequiredPlan($feature);
@endphp

@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-4">
        <!-- Feature Blocked Banner -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-8 text-white mb-8 text-center">
            <div class="text-6xl mb-4">🔒</div>
            <h1 class="text-3xl font-bold mb-2">{{ $featureTitle ?? $displayFeature }}</h1>
            <p class="text-primary-100 text-lg">
                Esta función requiere el plan <strong>{{ $requiredPlanEnum->label() ?? $requiredPlan }}</strong>
            </p>
        </div>

        <!-- Current Plan Info -->
        <div class="bg-warning-50 border-l-4 border-warning-500 p-6 rounded-lg mb-8">
            <div class="flex items-start gap-3">
                <span class="text-2xl">ℹ️</span>
                <div>
                    <h3 class="font-semibold text-warning-900 mb-1">Tu plan actual: {{ $professional->subscription_plan->label() }}</h3>
                    <p class="text-warning-700">
                        {{ $featureDescription ?? "Esta característica no está disponible en tu plan actual." }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Feature Preview/Benefits -->
        @if(count($benefits) > 0)
            <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                <h2 class="text-2xl font-bold text-text-primary mb-6">¿Qué obtendrás con {{ $displayFeature }}?</h2>
                <ul class="space-y-4">
                    @foreach($benefits as $benefit)
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-success-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-text-secondary">{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Plan Comparison -->
        <div class="bg-white rounded-xl shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-text-primary mb-6 text-center">Compara los planes</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Plan Gratis -->
                <div class="border rounded-lg p-6 {{ $professional->isOnFreePlan() ? 'border-primary-500 ring-2 ring-primary-200' : 'border-gray-200' }}">
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold">🆓 Gratis</h3>
                        @if($professional->isOnFreePlan())
                            <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-semibold mt-2">ACTUAL</span>
                        @endif
                    </div>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold">€0</div>
                        <div class="text-sm text-gray-500">por siempre</div>
                    </div>
                    <ul class="text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="{{ $feature === 'patients' ? 'text-success-500' : 'text-gray-400' }}">{{ $feature === 'patients' ? '✓' : '✗' }}</span>
                            Hasta 3 pacientes
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-400">✗</span>
                            {{ $displayFeature }}
                        </li>
                    </ul>
                </div>

                <!-- Plan Pro -->
                <div class="border-2 border-primary-500 rounded-lg p-6 relative {{ $professional->isOnProPlan() ? 'ring-2 ring-primary-300' : '' }}">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-primary-500 text-white px-4 py-1 rounded-full text-xs font-semibold">
                        Recomendado
                    </div>
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold">⭐ Pro</h3>
                        @if($professional->isOnProPlan())
                            <span class="inline-block bg-primary-500 text-white px-2 py-1 rounded text-xs font-semibold mt-2">ACTUAL</span>
                        @endif
                    </div>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold">€1</div>
                        <div class="text-sm text-gray-500">por paciente activo/mes</div>
                    </div>
                    <ul class="text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-success-500">✓</span>
                            Pacientes ilimitados
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-success-500">✓</span>
                            <strong>{{ $displayFeature }}</strong>
                        </li>
                    </ul>
                    
                    @if(!$professional->isOnProPlan())
                        <a href="mailto:info@clinora.es?subject=Actualizar a Plan Pro" 
                           class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-center px-4 py-2 rounded-lg font-semibold mt-4 transition-colors">
                            Actualizar a Pro
                        </a>
                    @endif
                </div>

                <!-- Plan Equipo -->
                <div class="border rounded-lg p-6 {{ $professional->isOnTeamPlan() ? 'border-gray-800 ring-2 ring-gray-300' : 'border-gray-200' }}">
                    <div class="text-center mb-4">
                        <h3 class="text-xl font-bold">🏢 Equipo</h3>
                        @if($professional->isOnTeamPlan())
                            <span class="inline-block bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold mt-2">ACTUAL</span>
                        @endif
                    </div>
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold">€2</div>
                        <div class="text-sm text-gray-500">por paciente activo/mes</div>
                    </div>
                    <ul class="text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="text-success-500">✓</span>
                            Todo lo de Pro
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-success-500">✓</span>
                            Multi-profesional
                        </li>
                    </ul>
                    
                    @if(!$professional->isOnTeamPlan())
                        <a href="mailto:info@clinora.es?subject=Actualizar a Plan Equipo" 
                           class="block w-full bg-gray-700 hover:bg-gray-800 text-white text-center px-4 py-2 rounded-lg font-semibold mt-4 transition-colors">
                            Actualizar a Equipo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors">
                ← Volver al Dashboard
            </a>
        </div>
    </div>
@endsection

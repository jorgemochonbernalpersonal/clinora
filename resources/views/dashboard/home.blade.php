@extends('layouts.dashboard')

@section('title', 'Dashboard')

@php
    $user = session('user');
    $professional = auth()->user()->professional;
    $planLimits = app(\App\Core\Subscriptions\Services\PlanLimitsService::class);
    $stats = $planLimits->getUsageStats($professional);
@endphp

@section('content')
    {{-- Onboarding Components --}}
    <x-onboarding.welcome-modal />
    <x-onboarding.checklist />
    
    {{-- Upgrade Modal --}}
    <x-upgrade-modal />
    
    {{-- Page Header with Subscription Info --}}
    <div class="mb-8 flex items-start justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">
                ¡Bienvenido, {{ $user['first_name'] ?? 'Doctor' }}!
            </h1>
            <p class="text-text-secondary mt-1">
                Aquí tienes un resumen de tu actividad
            </p>
        </div>
        
        {{-- Subscription Status Card --}}
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-4 border-2 border-primary-200 min-w-[280px]">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-text-secondary font-medium">Plan Actual</span>
                <x-plan-badge :plan="$professional->subscription_plan->value" />
            </div>
            <p class="text-2xl font-bold text-text-primary mb-2">
                {{ $professional->subscription_plan->label() }}
            </p>
            
            @if($professional->isOnFreePlan())
                <div class="mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-text-secondary">Pacientes</span>
                        <span class="font-semibold {{ $stats['is_at_limit'] ? 'text-warning-600' : 'text-text-primary' }}">
                            {{ $stats['total_patients'] }} / {{ $stats['patient_limit'] }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-500 h-2 rounded-full transition-all" 
                             style="width: {{ min($stats['percentage_used'], 100) }}%"></div>
                    </div>
                </div>
                
                @if(!$stats['can_add_patient'])
                    <div class="bg-warning-100 border border-warning-300 rounded px-2 py-1 text-xs text-warning-700 mb-2">
                        ⚠️ Límite alcanzado
                    </div>
                @endif
                
                <button onclick="document.querySelector('[x-data]').show = true" 
                        class="w-full bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    Actualizar Plan
                </button>
            @else
                <div class="text-sm text-text-secondary">
                    <span class="font-semibold text-text-primary">{{ $stats['active_patients'] }}</span> pacientes activos este mes
                </div>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Patients --}}
        <div class="bg-surface rounded-lg p-6 border border-gray-200 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-secondary text-sm mb-1">Total Pacientes</p>
                    <p class="text-3xl font-bold text-text-primary">--</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Appointments Today --}}
        <div class="bg-surface rounded-lg p-6 border border-gray-200 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-secondary text-sm mb-1">Citas Hoy</p>
                    <p class="text-3xl font-bold text-text-primary">--</p>
                </div>
                <div class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Appointments This Week --}}
        <div class="bg-surface rounded-lg p-6 border border-gray-200 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-text-secondary text-sm mb-1">Citas Esta Semana</p>
                    <p class="text-3xl font-bold text-text-primary">--</p>
                </div>
                <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-surface rounded-lg p-6 border border-gray-200 mb-8">
        <h2 class="text-xl font-semibold text-text-primary mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('patients.index') }}" class="flex items-center gap-3 px-4 py-3 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-primary-700">Nuevo Paciente</span>
            </a>
            
            <a href="{{ route('appointments.index') }}" class="flex items-center gap-3 px-4 py-3 bg-secondary-50 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-secondary-700">Nueva Cita</span>
            </a>
            
            <a href="{{ route('clinical-notes.index') }}" class="flex items-center gap-3 px-4 py-3 bg-accent-50 hover:bg-accent-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="font-medium text-accent-700">Nueva Nota</span>
            </a>
        </div>
    </div>

    {{-- Getting Started --}}
    <div class="bg-gradient-to-r from-primary-50 to-secondary-50 rounded-lg p-6 border border-primary-200">
        <h2 class="text-xl font-semibold text-text-primary mb-3">🎉 ¡Empieza a usar Clinora!</h2>
        <p class="text-text-secondary mb-4">
            Para aprovechar al máximo la plataforma, te recomendamos:
        </p>
        <ul class="space-y-2">
            <li class="flex items-center gap-2 text-text-secondary">
                <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Añade tus primeros pacientes</span>
            </li>
            <li class="flex items-center gap-2 text-text-secondary">
                <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Programa tus próximas citas</span>
            </li>
            <li class="flex items-center gap-2 text-text-secondary">
                <svg class="w-5 h-5 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Crea tus primeras notas clínicas</span>
            </li>
        </ul>
    </div>
@endsection

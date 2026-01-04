@php
    use App\Core\Subscriptions\Services\PlanLimitsService;
    use App\Shared\Enums\SubscriptionPlan;
    
    $planLimits = app(PlanLimitsService::class);
    $professional = auth()->user()->professional;
    $stats = $planLimits->getUsageStats($professional);
    
    $upgradeRequired = session('upgrade_required');
    $featureName = $upgradeRequired['feature_name'] ?? 'esta funcionalidad';
    $requiredPlan = $upgradeRequired['required_plan'] ?? 'Pro';
@endphp

<!-- Upgrade Modal -->
<div x-data="{ show: {{ $upgradeRequired ? 'true' : 'false' }} }" 
     x-show="show"
     x-cloak
     @keydown.escape.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
         @click="show = false"></div>
    
    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full p-8 transform transition-all"
             @click.away="show = false">
            
            <!-- Close Button -->
            <button @click="show = false" 
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🎉</div>
                @if($stats['is_at_limit'])
                    <h2 class="text-3xl font-bold text-text-primary mb-2">
                        ¡Tu clínica está creciendo!
                    </h2>
                    <p class="text-lg text-text-secondary">
                        Has alcanzado el límite de <strong>{{ $stats['patient_limit'] }} pacientes</strong> del Plan Gratis
                    </p>
                @else
                    <h2 class="text-3xl font-bold text-text-primary mb-2">
                        Desbloquea {{ $featureName }}
                    </h2>
                    <p class="text-lg text-text-secondary">
                        Esta función requiere el plan <strong>{{ $requiredPlan }}</strong>
                    </p>
                @endif
            </div>
            
            <!-- Plans Comparison -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <!-- Plan Gratis -->
                <div class="bg-gray-50 rounded-lg p-6 {{ $professional->isOnFreePlan() ? 'ring-2 ring-gray-300' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-text-primary">🆓 Gratis</h3>
                        @if($professional->isOnFreePlan())
                            <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-semibold">ACTUAL</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="text-3xl font-bold text-text-primary">€0</div>
                        <div class="text-sm text-text-secondary">por siempre</div>
                    </div>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Hasta 3 pacientes</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Agenda básica</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Notas SOAP</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Plan Pro -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-6 border-2 border-primary-500 relative {{ $professional->isOnProPlan() ? 'ring-2 ring-primary-600' : '' }}">
                    <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-primary-500 text-white px-4 py-1 rounded-full text-xs font-semibold">
                        Más Popular
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-text-primary">⭐ Pro</h3>
                        @if($professional->isOnProPlan())
                            <span class="bg-primary-500 text-white px-2 py-1 rounded text-xs font-semibold">ACTUAL</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="text-3xl font-bold text-text-primary">€1</div>
                        <div class="text-sm text-text-secondary">por paciente activo/mes</div>
                    </div>
                    <ul class="space-y-2 text-sm mb-6">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-primary font-medium">Todo lo de Gratis +</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Pacientes ilimitados</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Teleconsulta</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Evaluaciones</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Portal del paciente</span>
                        </li>
                    </ul>
                    @if(!$professional->isOnProPlan())
                        <a href="mailto:info@clinora.es?subject=Actualizar a Plan Pro" 
                           class="block w-full bg-primary-500 hover:bg-primary-600 text-white text-center px-4 py-2 rounded-lg font-semibold transition-colors">
                            Actualizar a Pro
                        </a>
                    @endif
                </div>
                
                <!-- Plan Equipo -->
                <div class="bg-gray-50 rounded-lg p-6 {{ $professional->isOnTeamPlan() ? 'ring-2 ring-gray-800' : '' }}">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-text-primary">🏢 Equipo</h3>
                        @if($professional->isOnTeamPlan())
                            <span class="bg-gray-800 text-white px-2 py-1 rounded text-xs font-semibold">ACTUAL</span>
                        @endif
                    </div>
                    <div class="mb-4">
                        <div class="text-3xl font-bold text-text-primary">€2</div>
                        <div class="text-sm text-text-secondary">por paciente activo/mes</div>
                    </div>
                    <ul class="space-y-2 text-sm mb-6">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-primary font-medium">Todo lo de Pro +</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Multi-profesional</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Informes avanzados</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-text-secondary">Acceso API</span>
                        </li>
                    </ul>
                    @if(!$professional->isOnTeamPlan())
                        <a href="mailto:info@clinora.es?subject=Actualizar a Plan Equipo" 
                           class="block w-full bg-gray-700 hover:bg-gray-800 text-white text-center px-4 py-2 rounded-lg font-semibold transition-colors">
                            Actualizar a Equipo
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Info Footer -->
            <div class="text-center text-sm text-text-secondary border-t pt-6">
                <p class="mb-2">
                    📧 Para actualizar tu plan, <a href="mailto:info@clinora.es?subject=Actualizar Plan" class="text-primary-600 hover:underline font-medium">contáctanos por email</a>
                </p>
                <p class="text-xs">
                    Todos los planes incluyen: ✅ Cumplimiento RGPD · ✅ Soporte técnico · ✅ Actualizaciones gratuitas
                </p>
            </div>
        </div>
    </div>
</div>

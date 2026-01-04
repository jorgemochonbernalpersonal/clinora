@props(['show' => false])

@php
    $onboarding = app(\App\Services\OnboardingService::class);
    $shouldShow = $onboarding->shouldShowWelcome(auth()->user());
@endphp

@if($shouldShow || $show)
<div x-data="{ show: true }" 
     x-show="show"
     x-cloak
     @keydown.escape.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full p-8 transform transition-all">
            
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="text-6xl mb-4">👋</div>
                <h2 class="text-3xl font-bold text-text-primary mb-2">
                    ¡Bienvenido a Clinora!
                </h2>
                <p class="text-lg text-text-secondary">
                    Estamos emocionados de ayudarte a gestionar tu práctica profesional
                </p>
            </div>
            
            <!-- Quick Steps -->
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-text-primary mb-4">🚀 Empieza en 3 pasos:</h3>
                <ol class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="bg-primary-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">1</span>
                        <span class="text-text-secondary"><strong>Agrega tu primer paciente</strong> - Gestiona su información de forma segura</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-primary-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</span>
                        <span class="text-text-secondary"><strong>Programa una cita</strong> - Organiza tu agenda fácilmente</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-primary-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</span>
                        <span class="text-text-secondary"><strong>Crea una nota clínica</strong> - Sistema SOAP integrado</span>
                    </li>
                </ol>
            </div>
            
            <!-- Features Highlight -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-2xl mb-1">🔒</div>
                    <div class="text-xs font-semibold text-text-primary">100% Seguro</div>
                    <div class="text-xs text-text-secondary">Cumple RGPD</div>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-2xl mb-1">☁️</div>
                    <div class="text-xs font-semibold text-text-primary">En la Nube</div>
                    <div class="text-xs text-text-secondary">Accede desde cualquier lugar</div>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-2xl mb-1">🎯</div>
                    <div class="text-xs font-semibold text-text-primary">Fácil de Usar</div>
                    <div class="text-xs text-text-secondary">Interfaz intuitiva</div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-3">
                <form action="{{ route('onboarding.welcome-seen') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        ¡Empezar!
                    </button>
                </form>
                
                <form action="{{ route('onboarding.welcome-seen') }}" method="POST">
                    @csrf
                    <input type="hidden" name="skip" value="1">
                    <button type="submit" 
                            class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                        Saltar
                    </button>
                </form>
            </div>
            
            <p class="text-center text-xs text-gray-500 mt-4">
                Podrás ver tus pasos completados en tu dashboard
            </p>
        </div>
    </div>
</div>
@endif

@php
    $onboarding = app(\App\Services\OnboardingService::class);
    $progress = $onboarding->getProgress(auth()->user());
@endphp

@if(!$progress['is_complete'])
<div x-data="{ open: {{ session('show_checklist', 'true') }} }" 
     class="fixed bottom-6 right-6 z-40">
    
    <!-- Collapsed Button -->
    <button @click="open = !open" 
            x-show="!open"
            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-3 rounded-lg shadow-lg font-semibold flex items-center gap-2 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        Primeros Pasos ({{ $progress['completed_count'] }}/{{ $progress['total'] }})
    </button>
    
    <!-- Expanded Checklist -->
    <div x-show="open" 
         x-transition
         class="bg-white rounded-lg shadow-2xl p-6 w-80">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg text-text-primary">🎯 Primeros Pasos</h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-text-secondary">Progreso</span>
                <span class="font-semibold text-primary-600">{{ $progress['percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary-500 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ $progress['percentage'] }}%"></div>
            </div>
        </div>
        
        <!-- Checklist -->
        <ul class="space-y-3">
            @foreach($progress['steps'] as $stepKey => $stepLabel)
                @php
                    $isCompleted = in_array($stepKey, $progress['completed']);
                    $links = [
                        'first_patient' => route('patients.create'),
                        'first_appointment' => route('appointments.create'),
                        'first_note' => route('clinical-notes.create'),
                    ];
                @endphp
                
                <li class="flex items-start gap-3 {{ $isCompleted ? 'opacity-60' : '' }}">
                    @if($isCompleted)
                        <svg class="w-6 h-6 text-success-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex-shrink-0"></div>
                    @endif
                    
                    <div class="flex-1">
                        <span class="text-sm {{ $isCompleted ? 'line-through text-gray-500' : 'text-text-primary font-medium' }}">
                            {{ $stepLabel }}
                        </span>
                        
                        @if(!$isCompleted && isset($links[$stepKey]))
                            <a href="{{ $links[$stepKey] }}" class="text-xs text-primary-600 hover:underline block mt-1">
                                → Hacerlo ahora
                            </a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        
        @if($progress['is_complete'])
            <div class="mt-4 bg-success-50 border-l-4 border-success-500 p-3 rounded">
                <p class="text-sm text-success-800 font-medium">
                    🎉 ¡Completado! Ya dominas lo básico de Clinora.
                </p>
            </div>
        @endif
    </div>
</div>
@endif

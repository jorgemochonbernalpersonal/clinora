<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-text-primary">Notas Clínicas</h2>
            <p class="text-text-secondary">Historial de sesiones SOAP</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="bg-accent-500 hover:bg-accent-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Nota
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-success-50 border border-success-200 text-success-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Notes List --}}
    <div class="space-y-4">
        @forelse($notes as $note)
            <div class="bg-surface rounded-lg border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-text-primary">
                            {{ $note['contact']['full_name'] ?? 'Paciente' }}
                        </h3>
                        <div class="flex items-center gap-3 text-sm text-text-secondary mt-1">
                            <span>Sesión #{{ $note['session_number'] ?? 'N/A' }}</span>
                            <span>•</span>
                            <span>{{ \Carbon\Carbon::parse($note['created_at'])->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $note['risk_assessment'] === 'none' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $note['risk_assessment'] === 'low' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $note['risk_assessment'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $note['risk_assessment'] === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $note['risk_assessment'] === 'critical' ? 'bg-red-100 text-red-800' : '' }}">
                        Riesgo: {{ ucfirst($note['risk_assessment']) }}
                    </span>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="font-semibold text-sm text-text-primary mb-2">📝 Subjetivo</h4>
                        <p class="text-sm text-text-secondary">{{ $note['subjective'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="font-semibold text-sm text-text-primary mb-2">🔍 Objetivo</h4>
                        <p class="text-sm text-text-secondary">{{ $note['objective'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="font-semibold text-sm text-text-primary mb-2">💡 Evaluación</h4>
                        <p class="text-sm text-text-secondary">{{ $note['assessment'] }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-4">
                        <h4 class="font-semibold text-sm text-text-primary mb-2">📋 Plan</h4>
                        <p class="text-sm text-text-secondary">{{ $note['plan'] }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-lg p-12 border border-gray-200 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-lg font-medium text-text-primary">No hay notas clínicas</p>
                <p class="text-sm text-text-secondary mt-1">Crea tu primera nota SOAP para empezar</p>
            </div>
        @endforelse
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
            <div class="bg-surface rounded-lg p-8 max-w-4xl w-full mx-4 my-8">
                <h3 class="text-2xl font-bold text-text-primary mb-6">Nueva Nota Clínica SOAP</h3>
                
                <form wire:submit.prevent="createClinicalNote" class="space-y-6">
                    {{-- Patient Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-2">Paciente *</label>
                        <select 
                            wire:model="contact_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500"
                            required>
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}">
                                    {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- SOAP Format --}}
                    <div class="grid md:grid-cols-2 gap-4">
                        {{-- Subjective --}}
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">
                                📝 Subjetivo * <span class="text-xs text-text-secondary">(Lo que dice el paciente)</span>
                            </label>
                            <textarea 
                                wire:model="subjective"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500"
                                placeholder="Síntomas reportados, sentimientos, preocupaciones..."
                                required></textarea>
                            @error('subjective') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Objective --}}
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">
                                🔍 Objetivo * <span class="text-xs text-text-secondary">(Observaciones clínicas)</span>
                            </label>
                            <textarea 
                                wire:model="objective"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500"
                                placeholder="Comportamiento observado, signos físicos, tests..."
                                required></textarea>
                            @error('objective') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Assessment --}}
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">
                                💡 Evaluación * <span class="text-xs text-text-secondary">(Diagnóstico/Análisis)</span>
                            </label>
                            <textarea 
                                wire:model="assessment"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500"
                                placeholder="Impresión diagnóstica, progreso, análisis..."
                                required></textarea>
                            @error('assessment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Plan --}}
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">
                                📋 Plan * <span class="text-xs text-text-secondary">(Tratamiento/Intervención)</span>
                            </label>
                            <textarea 
                                wire:model="plan"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500"
                                placeholder="Intervenciones, tareas, próximos pasos..."
                                required></textarea>
                            @error('plan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Risk Assessment --}}
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-2">Evaluación de Riesgo *</label>
                        <div class="grid grid-cols-5 gap-2">
                            <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer transition-colors
                                {{ $risk_assessment === 'none' ? 'bg-gray-100 border-gray-300' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model="risk_assessment" value="none" class="sr-only">
                                <span class="text-sm">Ninguno</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer transition-colors
                                {{ $risk_assessment === 'low' ? 'bg-green-100 border-green-300' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model="risk_assessment" value="low" class="sr-only">
                                <span class="text-sm">Bajo</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer transition-colors
                                {{ $risk_assessment === 'medium' ? 'bg-yellow-100 border-yellow-300' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model="risk_assessment" value="medium" class="sr-only">
                                <span class="text-sm">Medio</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer transition-colors
                                {{ $risk_assessment === 'high' ? 'bg-orange-100 border-orange-300' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model="risk_assessment" value="high" class="sr-only">
                                <span class="text-sm">Alto</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer transition-colors
                                {{ $risk_assessment === 'critical' ? 'bg-red-100 border-red-300' : 'border-gray-200 hover:bg-gray-50' }}">
                                <input type="radio" wire:model="risk_assessment" value="critical" class="sr-only">
                                <span class="text-sm">Crítico</span>
                            </label>
                        </div>
                        @error('risk_assessment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-4 border-t">
                        <button 
                            type="button"
                            wire:click="closeCreateModal"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Guardar Nota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

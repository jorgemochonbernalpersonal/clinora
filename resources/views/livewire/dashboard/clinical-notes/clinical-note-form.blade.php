<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        :title="$isEditing ? 'Editar Nota Clínica' : 'Nueva Nota Clínica'"
        description="Registre la evolución y detalles de la sesión."
        :cancel-route="profession_route('clinical-notes.index')"
        :is-editing="$isEditing"
        submit-label="{{ $isEditing ? 'Guardar Cambios' : 'Crear Nota' }}"
        submit-icon="save"
        loading-target="save"
    />

    <x-slot:main>
        {{-- Sección: Evolución SOAP --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Evolución S.O.A.P.
            </h3>
            
            <div class="space-y-8">
                {{-- Subjetivo --}}
                <x-forms.field name="subjective" label="Subjetivo (Lo que dice el paciente)" required>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded">S</span>
                    </div>
                    <x-forms.textarea 
                        name="subjective" 
                        :rows="5" 
                        placeholder="Descripción del paciente sobre su estado, síntomas..."
                        size="sm"
                    />
                </x-forms.field>

                {{-- Objetivo --}}
                <x-forms.field name="objective" label="Objetivo (Lo que observa el terapeuta)">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded">O</span>
                    </div>
                    <x-forms.textarea 
                        name="objective" 
                        :rows="5" 
                        placeholder="Observaciones clínicas, apariencia, conducta..."
                        size="sm"
                    />
                </x-forms.field>
                
                {{-- Análisis --}}
                <x-forms.field name="assessment" label="Análisis (Evaluación)">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded">A</span>
                    </div>
                    <x-forms.textarea 
                        name="assessment" 
                        :rows="5" 
                        placeholder="Interpretación clínica, cambios, hipótesis..."
                        size="sm"
                    />
                </x-forms.field>

                {{-- Plan --}}
                <x-forms.field name="plan" label="Plan (Intervención)" required>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-0.5 rounded">P</span>
                    </div>
                    <x-forms.textarea 
                        name="plan" 
                        :rows="5" 
                        placeholder="Tareas, cambios en tratamiento, próximos objetivos..."
                        size="sm"
                    />
                </x-forms.field>
            </div>
        </div>
    </x-slot:main>

    <x-slot:sidebar>
        {{-- Sección: Paciente y Sesión --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Paciente y Sesión
            </h3>
            
            <div class="space-y-5">
                <x-forms.field name="contact_id" label="Paciente" required>
                    <x-forms.select 
                        name="contact_id" 
                        wire:model.live="contact_id"
                        :options="collect($contacts)->mapWithKeys(fn($contact) => [$contact->id => $contact->full_name])->toArray()"
                        placeholder="Buscar paciente..."
                        size="sm"
                    />
                </x-forms.field>
                
                <div class="grid grid-cols-2 gap-3">
                    <x-forms.field name="session_date" label="Fecha" required>
                        <x-forms.input 
                            type="date" 
                            name="session_date" 
                            size="sm"
                        />
                    </x-forms.field>
                    
                    <x-forms.field name="session_number" label="Sesión Nº" required>
                        <x-forms.input 
                            type="number" 
                            name="session_number" 
                            size="sm"
                        />
                    </x-forms.field>
                </div>

                <x-forms.field name="duration_minutes" label="Duración (min)" required>
                    <x-forms.input 
                        type="number" 
                        name="duration_minutes" 
                        size="sm"
                    />
                </x-forms.field>

                <x-forms.field name="risk_assessment" label="Evaluación de Riesgo" required>
                    <x-forms.select 
                        name="risk_assessment" 
                        :options="[
                            'none' => 'Sin Riesgo',
                            'low' => 'Riesgo Bajo',
                            'moderate' => 'Riesgo Medio',
                            'high' => 'Riesgo Alto',
                            'imminent' => 'Riesgo Inminente'
                        ]"
                        size="sm"
                    />
                </x-forms.field>
            </div>
        </div>
    </x-slot:sidebar>
</x-forms.layout>

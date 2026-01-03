<x-form-card title="{{ $isEditing ? 'Editar Nota Clínica' : 'Nueva Nota Clínica' }}" submit="save">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Patient & Metadata --}}
        <div class="space-y-4">
            <div>
                <label for="contact_id" class="block text-sm font-medium text-gray-700">Paciente</label>
                <select 
                    id="contact_id" 
                    wire:model.live="contact_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                >
                    <option value="">Seleccionar Paciente...</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                    @endforeach
                </select>
                @error('contact_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="session_date" class="block text-sm font-medium text-gray-700">Fecha</label>
                    <input 
                        type="date" 
                        id="session_date" 
                        wire:model="session_date" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    >
                    @error('session_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="session_number" class="block text-sm font-medium text-gray-700">Sesión Nº</label>
                    <input 
                        type="number" 
                        id="session_number" 
                        wire:model="session_number" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                    >
                    @error('session_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Duration & Risk --}}
        <div class="space-y-4">
            <div>
                 <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duración (min)</label>
                 <input 
                    type="number" 
                    id="duration_minutes" 
                    wire:model="duration_minutes" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                >
                @error('duration_minutes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="risk_assessment" class="block text-sm font-medium text-gray-700">Evaluación de Riesgo</label>
                <select 
                    id="risk_assessment" 
                    wire:model="risk_assessment" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                >
                    <option value="sin_riesgo">Sin Riesgo</option>
                    <option value="riesgo_bajo">Riesgo Bajo</option>
                    <option value="riesgo_medio">Riesgo Medio</option>
                    <option value="riesgo_alto">Riesgo Alto</option>
                    <option value="riesgo_inminente">Riesgo Inminente</option>
                </select>
                @error('risk_assessment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 pt-6">
        <h4 class="text-sm font-medium text-gray-900 mb-4">Notas SOAP</h4>
        
        <div class="space-y-6">
            <div>
                <label for="subjective" class="block text-sm font-medium text-gray-700 mb-1">Subjetivo (S)</label>
                <textarea 
                    id="subjective" 
                    wire:model="subjective" 
                    rows="6" 
                    placeholder="Descripción del paciente sobre su estado..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                ></textarea>
                @error('subjective') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="objective" class="block text-sm font-medium text-gray-700 mb-1">Objetivo (O)</label>
                <textarea 
                    id="objective" 
                    wire:model="objective" 
                    rows="6" 
                    placeholder="Observaciones clínicas objetivas..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                ></textarea>
                @error('objective') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="assessment" class="block text-sm font-medium text-gray-700 mb-1">Análisis (A)</label>
                <textarea 
                    id="assessment" 
                    wire:model="assessment" 
                    rows="6" 
                    placeholder="Evaluación e interpretación clínica..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                ></textarea>
                @error('assessment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="plan" class="block text-sm font-medium text-gray-700 mb-1">Plan (P)</label>
                <textarea 
                    id="plan" 
                    wire:model="plan" 
                    rows="6" 
                    placeholder="Intervenciones y tareas..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                ></textarea>
                @error('plan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <x-slot name="actions">
        <a href="{{ route('clinical-notes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Cancelar
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ $isEditing ? 'Guardar Cambios' : 'Crear Nota' }}
        </button>
    </x-slot>
</x-form-card>

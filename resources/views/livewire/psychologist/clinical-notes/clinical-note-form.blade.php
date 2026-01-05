<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <form wire:submit="save">
        {{-- Header / Actions (Sticky) --}}
        <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8 flex justify-between items-center shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $isEditing ? 'Editar Nota Clínica' : 'Nueva Nota Clínica' }}</h1>
                <p class="text-sm text-gray-500">Registre la evolución y detalles de la sesión.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ profession_route('clinical-notes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="inline-flex items-center px-6 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors"
                >
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    {{ $isEditing ? 'Guardar Cambios' : 'Crear Nota' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Column 1: SOAP Editor (Left, Wide) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Nota SOAP --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Evolución S.O.A.P.
                    </h3>
                    
                    <div class="space-y-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded">S</span>
                                Subjetivo (Lo que dice el paciente)
                            </label>
                            <textarea wire:model="subjective" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Descripción del paciente sobre su estado, síntomas..."></textarea>
                            @error('subjective') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded">O</span>
                                Objetivo (Lo que observa el terapeuta)
                            </label>
                            <textarea wire:model="objective" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Observaciones clínicas, apariencia, conducta..."></textarea>
                            @error('objective') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded">A</span>
                                Análisis (Evaluación)
                            </label>
                            <textarea wire:model="assessment" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Interpretación clínica, cambios, hipótesis..."></textarea>
                            @error('assessment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-0.5 rounded">P</span>
                                Plan (Intervención)
                            </label>
                            <textarea wire:model="plan" rows="5" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Tareas, cambios en tratamiento, próximos objetivos..."></textarea>
                            @error('plan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Column 2: Meta Sidebar (Right, Sticky) --}}
            <div class="space-y-6">
                
                {{-- Selección de Paciente --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                     <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Paciente y Sesión
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                            <select wire:model.live="contact_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">Buscar paciente...</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                                @endforeach
                            </select>
                             @error('contact_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                                <input type="date" wire:model="session_date" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sesión Nº</label>
                                <input type="number" wire:model="session_number" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duración (min)</label>
                            <input type="number" wire:model="duration_minutes" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>

                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Evaluación de Riesgo</label>
                            <select wire:model="risk_assessment" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
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

            </div>

        </div>
    </form>
</div>

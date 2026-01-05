<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <form wire:submit="save">
        {{-- Header --}}
        <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8 flex justify-between items-center shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nuevo Consentimiento Informado</h1>
                <p class="text-sm text-gray-500">Genere un consentimiento para su paciente</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ profession_route('consent-forms.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">
                    <svg wire:loading.remove wire:target="save" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Crear Consentimiento
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                    
                    <div class="space-y-4">
                        {{-- Patient --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                            <select wire:model="contactId" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">Seleccione un paciente</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                                @endforeach
                            </select>
                            @error('contactId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Consent Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Consentimiento *</label>
                            <select wire:model.live="consentType" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                @foreach($consentTypes as $type => $label)
                                    <option value="{{ $type }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('consentType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título (opcional)</label>
                            <input type="text" 
                                   wire:model="consentTitle" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Dejar vacío para usar el título por defecto">
                        </div>
                    </div>
                </div>

                {{-- Treatment Details (for initial_treatment) --}}
                @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_INITIAL_TREATMENT)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles del Tratamiento</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duración Estimada</label>
                            <input type="text" 
                                   wire:model="treatmentDuration" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Ej: 6 meses, 12 sesiones">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia de Sesiones</label>
                            <input type="text" 
                                   wire:model="sessionFrequency" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Ej: Semanal, Quincenal">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duración de Sesión (minutos)</label>
                            <input type="number" 
                                   wire:model="sessionDuration" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="50-60">
                        </div>
                    </div>
                </div>
                @endif

                {{-- Teleconsultation Details --}}
                @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_TELECONSULTATION)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles de Teleconsulta</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plataforma *</label>
                            <input type="text" 
                                   wire:model="platform" 
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   placeholder="Ej: Clinora, Zoom, Google Meet">
                            @error('platform') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Información de Seguridad</label>
                            <textarea wire:model="securityInfo" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                      placeholder="Ej: Cifrado end-to-end, servidores en UE, cumplimiento RGPD"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="recordingConsent" 
                                   id="recording_consent"
                                   class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <label for="recording_consent" class="ml-2 text-sm text-gray-700">
                                Consentimiento para grabación de sesiones
                            </label>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Info Box --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">ℹ️ Información</h4>
                    <p class="text-sm text-blue-800">
                        El consentimiento se generará automáticamente con la información proporcionada. 
                        El paciente deberá leerlo y firmarlo antes de iniciar el tratamiento.
                    </p>
                </div>

                {{-- Requirements --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-900 mb-2">⚠️ Requisitos</h4>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• El paciente debe estar seleccionado</li>
                        <li>• El tipo de consentimiento es obligatorio</li>
                        @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_TELECONSULTATION)
                        <li>• La plataforma es requerida</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

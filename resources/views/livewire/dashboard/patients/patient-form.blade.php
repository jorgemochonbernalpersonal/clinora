<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <form wire:submit="save">
        {{-- Header / Actions (Sticky) --}}
        <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8 flex justify-between items-center shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $isEditing ? 'Editar Paciente' : 'Nuevo Paciente' }}</h1>
                <p class="text-sm text-gray-500">Complete la ficha clínica y administrativa.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="photo, save"
                    class="inline-flex items-center px-6 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg wire:loading.remove wire:target="save" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $isEditing ? 'Guardar Cambios' : 'Crear Paciente' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Column 1: Main Clinical Data (Left, Wide) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Sección: Datos Básicos --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Datos Identificativos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" wire:model="first_name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Apellidos *</label>
                            <input type="text" wire:model="last_name" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">DNI/NIE</label>
                            <input type="text" wire:model="dni" placeholder="12345678A" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                            <input type="date" wire:model="date_of_birth" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Género</label>
                            <select wire:model="gender" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="other">Otro</option>
                                <option value="male">Masculino</option>
                                <option value="female">Femenino</option>
                                <option value="prefer_not_to_say">Prefiero no decirlo</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Sección: Información Clínica (Destacada) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-primary-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Historia Clínica
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Motivo de Consulta Inicial</label>
                            <textarea wire:model="initial_consultation_reason" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="¿Por qué acude el paciente?"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Antecedentes Médicos</label>
                                <textarea wire:model="medical_history" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Antecedentes Psiquiátricos</label>
                                <textarea wire:model="psychiatric_history" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Medicación Actual</label>
                            <textarea wire:model="current_medication" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Fármacos y dosis..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Sección: Información Adicional (Colapsable o Tabs, aquí plano para full width) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 overflow-hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Socioeconómica y Otros</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado Civil</label>
                            <select wire:model="marital_status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="single">Soltero/a</option>
                                <option value="married">Casado/a</option>
                                <option value="divorced">Divorciado/a</option>
                                <option value="widowed">Viudo/a</option>
                                <option value="cohabiting">Pareja de hecho</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ocupación</label>
                            <input type="text" wire:model="occupation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nivel Educativo</label>
                            <select wire:model="education_level" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="">Seleccionar...</option>
                                <option value="primary">Primaria</option>
                                <option value="secondary">Secundaria</option>
                                <option value="vocational">FP</option>
                                <option value="university">Universidad</option>
                                <option value="postgraduate">Postgrado</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-6">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Fuente de Derivación</h4>
                                <input type="text" wire:model="referral_source" placeholder="¿Cómo nos conoció?" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Seguro Médico</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" wire:model="insurance_company" placeholder="Compañía" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                    <input type="text" wire:model="insurance_policy_number" placeholder="Póliza" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                </div>
                            </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">Notas Administrativas</h4>
                        <textarea wire:model="notes" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Observaciones internas, recordatorios..."></textarea>
                    </div>
                </div>

                 {{-- GDPR Consent --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 flex items-start gap-4">
                     <div class="flex h-5 items-center mt-1">
                        <input id="gdpr_check" wire:model="data_protection_consent" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="gdpr_check" class="font-medium text-gray-700 block">Consentimiento de Protección de Datos (RGPD) *</label>
                        <p class="text-sm text-gray-500 mt-1">
                            Autorizo el tratamiento de mis datos personales de salud con fines asistenciales y administrativos.
                        </p>
                        @error('data_protection_consent') <span class="text-red-500 text-xs block mt-1">Requerido.</span> @enderror
                    </div>
                </div>

            </div>

            {{-- Column 2: Sidebar (Right, Sticky on Desktop) --}}
            <div class="space-y-6">
                
                {{-- Foto y Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col items-center sticky top-24">
                     <div class="relative group mb-4">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif ($patient && $patient->profile_photo_path)
                                 <img src="{{ asset('storage/' . $patient->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-gray-300 bg-gray-50">
                                     <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                </div>
                            @endif
                        </div>
                        <label for="photo" class="absolute bottom-2 right-2 bg-primary-600 rounded-full p-2 cursor-pointer shadow-md hover:bg-primary-700 transition-colors text-white">
                            <svg wire:loading.remove wire:target="photo" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <svg wire:loading wire:target="photo" class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </label>
                        <input type="file" id="photo" wire:model="photo" class="hidden" accept="image/*">
                    </div>
                    @error('photo') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
                    <p class="text-sm text-gray-500 mb-6 text-center">Foto de Perfil</p>

                    <div class="w-full border-t border-gray-100 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Contacto Rápido</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Email</label>
                                <input type="email" wire:model="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase">Teléfono</label>
                                <input type="tel" wire:model="phone" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                            </div>
                             <div>
                                <label class="text-xs text-gray-500 uppercase">Dirección</label>
                                <textarea wire:model="address_street" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Calle, Número..."></textarea>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <input type="text" wire:model="address_city" placeholder="Ciudad" class="block w-full rounded-md border-gray-300 text-xs">
                                    <input type="text" wire:model="address_postal_code" placeholder="CP" class="block w-full rounded-md border-gray-300 text-xs">
                                </div>
                                <input type="text" wire:model="address_country" placeholder="País" class="block w-full rounded-md border-gray-300 text-xs mt-2">
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full border-t border-gray-100 pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-red-800 mb-2">Emergencia</h4>
                        <div class="bg-red-50 p-3 rounded-lg space-y-2">
                             <input type="text" wire:model="emergency_contact_name" placeholder="Nombre Contacto" class="block w-full bg-white rounded border-red-200 text-sm">
                             <input type="tel" wire:model="emergency_contact_phone" placeholder="Teléfono" class="block w-full bg-white rounded border-red-200 text-sm">
                             <input type="text" wire:model="emergency_contact_relationship" placeholder="Parentesco (ej: Pareja, Padre...)" class="block w-full bg-white rounded border-red-200 text-sm">
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

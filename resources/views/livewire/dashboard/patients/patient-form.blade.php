<x-form-card title="{{ $isEditing ? 'Editar Paciente' : 'Nuevo Paciente' }}" submit="save" maxWidth="4xl">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
        
        {{-- Photo --}}
        <div class="md:col-span-3 flex flex-col items-center space-y-4">
             <div class="relative group">
                <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif ($patient && $patient->profile_photo_path)
                         <img src="{{ asset('storage/' . $patient->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center w-full h-full text-gray-300">
                             <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    @endif
                </div>
                
                 {{-- Upload Button Overlay --}}
                <label for="photo" class="absolute bottom-0 right-0 bg-primary-600 rounded-full p-2 cursor-pointer shadow-md hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </label>
                <input type="file" id="photo" wire:model="photo" class="hidden" accept="image/*">
            </div>
            
             @error('photo') <span class="text-red-500 text-xs text-center block">{{ $message }}</span> @enderror
            <p class="text-xs text-gray-500 text-center">Toca el icono de cámara para subir foto</p>
        </div>

        {{-- Form Fields --}}
        <div class="md:col-span-9 space-y-4">
            {{-- Datos Básicos (Siempre visible) --}}
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Datos Básicos</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input 
                            type="text" 
                            id="first_name" 
                            wire:model="first_name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                        <input 
                            type="text" 
                            id="last_name" 
                            wire:model="last_name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="dni" class="block text-sm font-medium text-gray-700">DNI/NIE</label>
                        <input 
                            type="text" 
                            id="dni" 
                            wire:model="dni" 
                            placeholder="12345678A"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                        @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">F. Nacimiento</label>
                        <input 
                            type="date" 
                            id="date_of_birth" 
                            wire:model="date_of_birth" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                        @error('date_of_birth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Género</label>
                        <select 
                            id="gender" 
                            wire:model="gender" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                            <option value="other">Otro / Prefiero no decir</option>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                            <option value="prefer_not_to_say">Prefiero no decir</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Contacto (Colapsable) --}}
            <div class="bg-white rounded-lg border border-gray-200" x-data="{ open: false }">
                <button 
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <h4 class="text-sm font-semibold text-gray-900">Información de Contacto</h4>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="px-4 pb-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                wire:model="email" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            >
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input 
                                type="tel" 
                                id="phone" 
                                wire:model="phone" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            >
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="address_city" class="block text-sm font-medium text-gray-700">Ciudad</label>
                            <input type="text" id="address_city" wire:model="address_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('address_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="address_postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
                            <input type="text" id="address_postal_code" wire:model="address_postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('address_postal_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="address_country" class="block text-sm font-medium text-gray-700">País</label>
                            <input type="text" id="address_country" wire:model="address_country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('address_country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información Clínica (Colapsable) --}}
            <div class="bg-white rounded-lg border border-gray-200" x-data="{ open: false }">
                <button 
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <h4 class="text-sm font-semibold text-gray-900">Información Clínica</h4>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="px-4 pb-4 space-y-4">
                    <div>
                        <label for="initial_consultation_reason" class="block text-sm font-medium text-gray-700">Motivo de Consulta Inicial</label>
                        <textarea 
                            id="initial_consultation_reason" 
                            wire:model="initial_consultation_reason" 
                            rows="2"
                            placeholder="Describa el motivo principal..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        ></textarea>
                        @error('initial_consultation_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="first_appointment_date" class="block text-sm font-medium text-gray-700">Fecha Primera Consulta</label>
                        <input 
                            type="date" 
                            id="first_appointment_date" 
                            wire:model="first_appointment_date" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        >
                        @error('first_appointment_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="medical_history" class="block text-sm font-medium text-gray-700">Antecedentes Médicos</label>
                            <textarea 
                                id="medical_history" 
                                wire:model="medical_history" 
                                rows="2"
                                placeholder="Enfermedades, cirugías, alergias..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            ></textarea>
                            @error('medical_history') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="psychiatric_history" class="block text-sm font-medium text-gray-700">Antecedentes Psiquiátricos</label>
                            <textarea 
                                id="psychiatric_history" 
                                wire:model="psychiatric_history" 
                                rows="2"
                                placeholder="Tratamientos previos..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                            ></textarea>
                            @error('psychiatric_history') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="current_medication" class="block text-sm font-medium text-gray-700">Medicación Actual</label>
                        <textarea 
                            id="current_medication" 
                            wire:model="current_medication" 
                            rows="2"
                            placeholder="Liste los medicamentos..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        ></textarea>
                        @error('current_medication') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Información Adicional (Colapsable) --}}
            <div class="bg-white rounded-lg border border-gray-200" x-data="{ open: false }">
                <button 
                    type="button"
                    @click="open = !open"
                    class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors"
                >
                    <h4 class="text-sm font-semibold text-gray-900">Información Adicional</h4>
                    <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="px-4 pb-4 space-y-4">
                    {{-- Socioeconómica --}}
                    <div>
                        <h5 class="text-xs font-semibold text-gray-600 uppercase mb-3">Información Socioeconómica</h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="marital_status" class="block text-sm font-medium text-gray-700">Estado Civil</label>
                                <select 
                                    id="marital_status" 
                                    wire:model="marital_status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="single">Soltero/a</option>
                                    <option value="married">Casado/a</option>
                                    <option value="divorced">Divorciado/a</option>
                                    <option value="widowed">Viudo/a</option>
                                    <option value="cohabiting">Pareja de hecho</option>
                                    <option value="other">Otro</option>
                                </select>
                                @error('marital_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700">Ocupación/Profesión</label>
                                <input 
                                    type="text" 
                                    id="occupation" 
                                    wire:model="occupation" 
                                    placeholder="Ej: Psicólogo, Estudiante..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                @error('occupation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="education_level" class="block text-sm font-medium text-gray-700">Nivel Educativo</label>
                                <select 
                                    id="education_level" 
                                    wire:model="education_level" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="primary">Primaria</option>
                                    <option value="secondary">Secundaria</option>
                                    <option value="vocational">Formación Profesional</option>
                                    <option value="university">Universidad</option>
                                    <option value="postgraduate">Postgrado</option>
                                    <option value="other">Otro</option>
                                </select>
                                @error('education_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Seguro Médico --}}
                    <div>
                        <h5 class="text-xs font-semibold text-gray-600 uppercase mb-3">Seguro Médico / Mutua</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="insurance_company" class="block text-sm font-medium text-gray-700">Compañía de Seguros</label>
                                <input 
                                    type="text" 
                                    id="insurance_company" 
                                    wire:model="insurance_company" 
                                    placeholder="Ej: Adeslas, Sanitas, DKV..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                @error('insurance_company') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700">Número de Póliza</label>
                                <input 
                                    type="text" 
                                    id="insurance_policy_number" 
                                    wire:model="insurance_policy_number" 
                                    placeholder="Número de póliza"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                >
                                @error('insurance_policy_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Contacto de Emergencia --}}
                    <div>
                        <h5 class="text-xs font-semibold text-gray-600 uppercase mb-3">Contacto de Emergencia</h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="emergency_contact_name" wire:model="emergency_contact_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" id="emergency_contact_phone" wire:model="emergency_contact_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Relación</label>
                                <input type="text" id="emergency_contact_relationship" wire:model="emergency_contact_relationship" placeholder="Ej: Madre, Pareja" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Notas --}}
                    <div>
                        <h5 class="text-xs font-semibold text-gray-600 uppercase mb-3">Notas Adicionales</h5>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea id="notes" wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
                            @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <x-slot name="actions">
        <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Cancelar
        </a>
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            {{ $isEditing ? 'Guardar Cambios' : 'Crear Paciente' }}
        </button>
    </x-slot>
</x-form-card>

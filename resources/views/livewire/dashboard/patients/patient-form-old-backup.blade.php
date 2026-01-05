<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{ 
    openSections: {
        basic: true,
        contact: true,
        clinical: false,
        social: false,
        emergency: false
    },
    toggleSection(section) {
        this.openSections[section] = !this.openSections[section];
    }
}">
    <form wire:submit="save">
        {{-- Header / Actions (Sticky) --}}
        <div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8 flex justify-between items-center shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $isEditing ? 'Editar Paciente' : 'Nuevo Paciente' }}</h1>
                <p class="text-sm text-gray-500">Complete la ficha clínica y administrativa.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ profession_route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
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
                
                {{-- Sección: Datos Básicos (Colapsable) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button 
                        type="button"
                        @click="toggleSection('basic')"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Datos Identificativos
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openSections.basic }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openSections.basic" x-transition class="px-6 pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.blur="first_name" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base @error('first_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="Nombre del paciente"
                                >
                                @error('first_name') 
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.blur="last_name" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base @error('last_name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="Apellidos del paciente"
                                >
                                @error('last_name') 
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">DNI/NIE</label>
                                <input 
                                    type="text" 
                                    wire:model.blur="dni" 
                                    placeholder="12345678A" 
                                    maxlength="9"
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base uppercase"
                                    style="text-transform: uppercase"
                                >
                                @error('dni') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                                <input 
                                    type="date" 
                                    wire:model.blur="date_of_birth" 
                                    max="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                >
                                @if($this->age)
                                    <p class="mt-1 text-xs text-gray-500">
                                        Edad: {{ $this->age }} años
                                    </p>
                                @endif
                                @error('date_of_birth') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                                <select wire:model="gender" class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base bg-white">
                                    <option value="other">Otro</option>
                                    <option value="male">Masculino</option>
                                    <option value="female">Femenino</option>
                                    <option value="prefer_not_to_say">Prefiero no decirlo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Contacto (Colapsable) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button 
                        type="button"
                        @click="toggleSection('contact')"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Información de Contacto
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openSections.contact }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openSections.contact" x-transition class="px-6 pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input 
                                    type="email" 
                                    wire:model.blur="email" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="paciente@ejemplo.com"
                                >
                                @error('email') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input 
                                    type="tel" 
                                    wire:model.blur="phone" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                    placeholder="+34 600 000 000"
                                >
                                @error('phone') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                <textarea 
                                    wire:model="address_street" 
                                    rows="2" 
                                    class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                    placeholder="Calle, Número, Piso..."
                                ></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                                    <input 
                                        type="text" 
                                        wire:model="address_city" 
                                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                        placeholder="Ciudad"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal</label>
                                    <input 
                                        type="text" 
                                        wire:model="address_postal_code" 
                                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                        placeholder="28001"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                                    <input 
                                        type="text" 
                                        wire:model="address_country" 
                                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                        placeholder="España"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Información Clínica (Colapsable) --}}
                <div class="bg-white rounded-xl shadow-sm border-2 border-primary-200 overflow-hidden">
                    <button 
                        type="button"
                        @click="toggleSection('clinical')"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-primary-50 transition-colors bg-primary-50/50"
                    >
                        <h3 class="text-lg font-semibold text-primary-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Historia Clínica
                        </h3>
                        <svg class="w-5 h-5 text-primary-500 transition-transform" :class="{ 'rotate-180': openSections.clinical }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openSections.clinical" x-transition class="px-6 pb-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de Consulta Inicial</label>
                                <textarea 
                                    wire:model="initial_consultation_reason" 
                                    rows="3" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base" 
                                    placeholder="¿Por qué acude el paciente? Describa el motivo principal de la consulta..."
                                ></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Primera Cita</label>
                                    <input 
                                        type="date" 
                                        wire:model="first_appointment_date" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                    >
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Antecedentes Médicos</label>
                                    <textarea 
                                        wire:model="medical_history" 
                                        rows="4" 
                                        class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                        placeholder="Enfermedades previas, cirugías, alergias..."
                                    ></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Antecedentes Psiquiátricos</label>
                                    <textarea 
                                        wire:model="psychiatric_history" 
                                        rows="4" 
                                        class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                        placeholder="Tratamientos previos, diagnósticos anteriores..."
                                    ></textarea>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medicación Actual</label>
                                <textarea 
                                    wire:model="current_medication" 
                                    rows="3" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base" 
                                    placeholder="Fármacos, dosis y frecuencia. Ej: Sertralina 50mg, 1 comprimido al día..."
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección: Información Socioeconómica (Colapsable) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button 
                        type="button"
                        @click="toggleSection('social')"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Información Socioeconómica
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openSections.social }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="openSections.social" x-transition class="px-6 pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estado Civil</label>
                                <select wire:model="marital_status" class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base bg-white">
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                                <input 
                                    type="text" 
                                    wire:model="occupation" 
                                    class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                                    placeholder="Profesión o trabajo"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nivel Educativo</label>
                                <select wire:model="education_level" class="mt-1 block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base bg-white">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuente de Derivación</label>
                            <input 
                                type="text" 
                                wire:model="referral_source" 
                                placeholder="¿Cómo nos conoció? (ej: Recomendación, Google, Redes sociales...)" 
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base"
                            >
                        </div>
                        
                        <div class="border-t border-gray-100 pt-6 mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notas Administrativas</label>
                            <textarea 
                                wire:model="notes" 
                                rows="3" 
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base" 
                                placeholder="Observaciones internas, recordatorios, información adicional..."
                            ></textarea>
                        </div>
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
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100" 
                             x-data="{ photoPreview: null }"
                             x-init="
                                const fileInput = document.getElementById('photo');
                                fileInput.addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => { photoPreview = e.target.result; };
                                        reader.readAsDataURL(file);
                                    }
                                });
                             ">
                            @if ($patient && $patient->profile_photo_path)
                                <img :src="photoPreview" x-show="photoPreview" class="w-full h-full object-cover">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($patient->profile_photo_path) }}" x-show="!photoPreview" class="w-full h-full object-cover">
                            @else
                                <img :src="photoPreview" x-show="photoPreview" class="w-full h-full object-cover">
                                <div x-show="!photoPreview" class="flex items-center justify-center w-full h-full text-gray-300 bg-gray-50">
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
                        <label class="text-xs font-semibold text-gray-900 uppercase mb-2 block">Etiquetas / Tags</label>
                        <input type="text" wire:model="tags" placeholder="Ej: VIP, Ansiedad, Mañanas..." class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 text-base">
                        <p class="text-[10px] text-gray-400 mt-1">Separadas por comas</p>
                    </div>

                    <div class="w-full border-t border-gray-100 pt-4 mt-4" x-data="{ open: false }">
                        <button 
                            type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between text-sm font-semibold text-red-800 mb-2 hover:text-red-900"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Contacto de Emergencia
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="bg-red-50 p-4 rounded-lg space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-red-900 mb-1">Nombre del Contacto</label>
                                <input 
                                    type="text" 
                                    wire:model="emergency_contact_name" 
                                    placeholder="Nombre completo" 
                                    class="block w-full bg-white px-4 py-2.5 rounded-lg border border-red-200 text-base focus:border-red-400 focus:ring-2 focus:ring-red-400 focus:ring-opacity-20"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-red-900 mb-1">Teléfono</label>
                                <input 
                                    type="tel" 
                                    wire:model="emergency_contact_phone" 
                                    placeholder="+34 600 000 000" 
                                    class="block w-full bg-white px-4 py-2.5 rounded-lg border border-red-200 text-base focus:border-red-400 focus:ring-2 focus:ring-red-400 focus:ring-opacity-20"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-red-900 mb-1">Relación</label>
                                <input 
                                    type="text" 
                                    wire:model="emergency_contact_relationship" 
                                    placeholder="Ej: Pareja, Padre, Madre, Hermano/a..." 
                                    class="block w-full bg-white px-4 py-2.5 rounded-lg border border-red-200 text-base focus:border-red-400 focus:ring-2 focus:ring-red-400 focus:ring-opacity-20"
                                >
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

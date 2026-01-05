<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        :title="$isEditing ? 'Editar Paciente' : 'Nuevo Paciente'"
        description="Complete la ficha clínica y administrativa."
        :cancel-route="profession_route('patients.index')"
        :is-editing="$isEditing"
        submit-label="{{ $isEditing ? 'Guardar Cambios' : 'Crear Paciente' }}"
        loading-target="photo, save"
    />

    {{-- Flash Messages --}}
    <x-forms.flash-messages />

    <x-slot:main>
        {{-- Sección: Datos Básicos --}}
        <x-forms.section section="basic" title="Datos Identificativos" icon="user" :open="true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-forms.field name="first_name" label="Nombre" required>
                    <x-forms.input name="first_name" placeholder="Nombre del paciente" />
                </x-forms.field>

                <x-forms.field name="last_name" label="Apellidos" required>
                    <x-forms.input name="last_name" placeholder="Apellidos del paciente" />
                </x-forms.field>

                <x-forms.field name="dni" label="DNI/NIE">
                    <x-forms.input name="dni" placeholder="12345678A" maxlength="9" style="text-transform: uppercase" />
                </x-forms.field>

                <x-forms.field name="date_of_birth" label="Fecha de Nacimiento">
                    <x-forms.input type="date" name="date_of_birth" :max="date('Y-m-d')" />
                    @if($this->age)
                        <p class="mt-1 text-xs text-gray-500">Edad: {{ $this->age }} años</p>
                    @endif
                </x-forms.field>

                <x-forms.field name="gender" label="Género">
                    <x-forms.select name="gender" :options="[
                        'other' => 'Otro',
                        'male' => 'Masculino',
                        'female' => 'Femenino',
                        'prefer_not_to_say' => 'Prefiero no decirlo'
                    ]" />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección: Información de Contacto --}}
        <x-forms.section section="contact" title="Información de Contacto" icon="contact" :open="true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <x-forms.field name="email" label="Email">
                    <x-forms.input type="email" name="email" placeholder="paciente@ejemplo.com" />
                </x-forms.field>

                <x-forms.field name="phone" label="Teléfono">
                    <x-forms.input type="tel" name="phone" placeholder="+34 600 000 000" />
                </x-forms.field>
            </div>
            
            <div class="space-y-4">
                <x-forms.field name="address_street" label="Dirección">
                    <x-forms.textarea name="address_street" :rows="2" placeholder="Calle, Número, Piso..." />
                </x-forms.field>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-forms.field name="address_city" label="Ciudad">
                        <x-forms.input name="address_city" placeholder="Ciudad" />
                    </x-forms.field>
                    
                    <x-forms.field name="address_postal_code" label="Código Postal">
                        <x-forms.input name="address_postal_code" placeholder="28001" />
                    </x-forms.field>
                    
                    <x-forms.field name="address_country" label="País">
                        <x-forms.input name="address_country" placeholder="España" />
                    </x-forms.field>
                </div>
            </div>
        </x-forms.section>

        {{-- Sección: Historia Clínica (Highlighted) --}}
        <x-forms.section section="clinical" title="Historia Clínica" icon="clinical" :highlighted="true">
            <div class="space-y-4">
                <x-forms.field name="initial_consultation_reason" label="Motivo de Consulta Inicial">
                    <x-forms.textarea name="initial_consultation_reason" :rows="3" placeholder="¿Por qué acude el paciente? Describa el motivo principal de la consulta..." />
                </x-forms.field>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-forms.field name="first_appointment_date" label="Fecha Primera Cita">
                        <x-forms.input type="date" name="first_appointment_date" />
                    </x-forms.field>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-forms.field name="medical_history" label="Antecedentes Médicos">
                        <x-forms.textarea name="medical_history" :rows="4" placeholder="Enfermedades previas, cirugías, alergias..." />
                    </x-forms.field>
                    
                    <x-forms.field name="psychiatric_history" label="Antecedentes Psiquiátricos">
                        <x-forms.textarea name="psychiatric_history" :rows="4" placeholder="Tratamientos previos, diagnósticos anteriores..." />
                    </x-forms.field>
                </div>

                <x-forms.field name="current_medication" label="Medicación Actual">
                    <x-forms.textarea name="current_medication" :rows="3" placeholder="Fármacos, dosis y frecuencia. Ej: Sertralina 50mg, 1 comprimido al día..." />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección: Información Socioeconómica --}}
        <x-forms.section section="social" title="Información Socioeconómica" icon="social">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <x-forms.field name="marital_status" label="Estado Civil">
                    <x-forms.select name="marital_status" :options="[
                        '' => 'Seleccionar...',
                        'single' => 'Soltero/a',
                        'married' => 'Casado/a',
                        'divorced' => 'Divorciado/a',
                        'widowed' => 'Viudo/a',
                        'cohabiting' => 'Pareja de hecho',
                        'other' => 'Otro'
                    ]" />
                </x-forms.field>
                
                <x-forms.field name="occupation" label="Ocupación">
                    <x-forms.input name="occupation" placeholder="Profesión o trabajo" />
                </x-forms.field>
                
                <x-forms.field name="education_level" label="Nivel Educativo">
                    <x-forms.select name="education_level" :options="[
                        '' => 'Seleccionar...',
                        'primary' => 'Primaria',
                        'secondary' => 'Secundaria',
                        'vocational' => 'FP',
                        'university' => 'Universidad',
                        'postgraduate' => 'Postgrado',
                        'other' => 'Otro'
                    ]" />
                </x-forms.field>
            </div>
            
            <div class="border-t border-gray-100 pt-6">
                <x-forms.field name="referral_source" label="Fuente de Derivación">
                    <x-forms.input name="referral_source" placeholder="¿Cómo nos conoció? (ej: Recomendación, Google, Redes sociales...)" />
                </x-forms.field>
            </div>
            
            <div class="border-t border-gray-100 pt-6 mt-6">
                <x-forms.field name="notes" label="Notas Administrativas">
                    <x-forms.textarea name="notes" :rows="3" placeholder="Observaciones internas, recordatorios, información adicional..." />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- GDPR Consent --}}
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
            <x-forms.checkbox name="data_protection_consent" label="Consentimiento de Protección de Datos (RGPD) *">
                Autorizo el tratamiento de mis datos personales de salud con fines asistenciales y administrativos.
            </x-forms.checkbox>
            @error('data_protection_consent') 
                <span class="text-red-500 text-xs block mt-1">Requerido.</span> 
            @enderror
        </div>
    </x-slot:main>

    <x-slot:sidebar>
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
                <x-forms.input name="tags" placeholder="Ej: VIP, Ansiedad, Mañanas..." />
                <p class="text-[10px] text-gray-400 mt-1">Separadas por comas</p>
            </div>

            {{-- Contacto de Emergencia --}}
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
                    <x-forms.field name="emergency_contact_name" label="Nombre del Contacto">
                        <x-forms.input name="emergency_contact_name" placeholder="Nombre completo" class="bg-white border-red-200 focus:border-red-400 focus:ring-red-400" />
                    </x-forms.field>
                    
                    <x-forms.field name="emergency_contact_phone" label="Teléfono">
                        <x-forms.input type="tel" name="emergency_contact_phone" placeholder="+34 600 000 000" class="bg-white border-red-200 focus:border-red-400 focus:ring-red-400" />
                    </x-forms.field>
                    
                    <x-forms.field name="emergency_contact_relationship" label="Relación">
                        <x-forms.input name="emergency_contact_relationship" placeholder="Ej: Pareja, Padre, Madre, Hermano/a..." class="bg-white border-red-200 focus:border-red-400 focus:ring-red-400" />
                    </x-forms.field>
                </div>
            </div>
        </div>
    </x-slot:sidebar>
</x-forms.layout>

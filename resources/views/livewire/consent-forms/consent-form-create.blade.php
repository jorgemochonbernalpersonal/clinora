<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        title="Nuevo Consentimiento Informado"
        description="Genere un consentimiento para su paciente"
        :cancel-route="profession_route('consent-forms.index')"
        submit-label="Crear Consentimiento"
        loading-target="save"
    />

    {{-- Flash Messages --}}
    <x-forms.flash-messages />

    <x-slot:main>
        {{-- Sección: Información Básica --}}
        <x-forms.section section="basic" title="Información Básica" icon="user" :open="true">
            <div class="space-y-4">
                <x-forms.field name="contactId" label="Paciente" required>
                    <x-forms.select 
                        name="contactId" 
                        wire:model="contactId"
                        :options="collect($contacts)->mapWithKeys(fn($contact) => [$contact->id => $contact->full_name])->toArray()"
                        placeholder="Seleccione un paciente"
                    />
                </x-forms.field>

                <x-forms.field name="consentType" label="Tipo de Consentimiento" required>
                    <x-forms.select 
                        name="consentType" 
                        :options="$consentTypes"
                        wire:model.live="consentType"
                    />
                </x-forms.field>

                <x-forms.field name="consentTitle" label="Título (opcional)" help="Dejar vacío para usar el título por defecto">
                    <x-forms.input 
                        name="consentTitle" 
                        wire:model="consentTitle"
                        placeholder="Dejar vacío para usar el título por defecto"
                    />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección: Detalles del Tratamiento (solo para initial_treatment) --}}
        @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_INITIAL_TREATMENT)
        <x-forms.section section="treatment" title="Detalles del Tratamiento" icon="clinical">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.field name="treatmentDuration" label="Duración Estimada">
                    <x-forms.input 
                        name="treatmentDuration" 
                        wire:model="treatmentDuration"
                        placeholder="Ej: 6 meses, 12 sesiones"
                    />
                </x-forms.field>

                <x-forms.field name="sessionFrequency" label="Frecuencia de Sesiones">
                    <x-forms.input 
                        name="sessionFrequency" 
                        wire:model="sessionFrequency"
                        placeholder="Ej: Semanal, Quincenal"
                    />
                </x-forms.field>

                <x-forms.field name="sessionDuration" label="Duración de Sesión (minutos)">
                    <x-forms.input 
                        type="number" 
                        name="sessionDuration" 
                        wire:model="sessionDuration"
                        placeholder="50-60"
                    />
                </x-forms.field>
            </div>
        </x-forms.section>
        @endif

        {{-- Sección: Detalles de Teleconsulta (solo para teleconsultation) --}}
        @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_TELECONSULTATION)
        <x-forms.section section="teleconsultation" title="Detalles de Teleconsulta" icon="contact">
            <div class="space-y-4">
                <x-forms.field name="platform" label="Plataforma" required>
                    <x-forms.input 
                        name="platform" 
                        wire:model="platform"
                        placeholder="Ej: Clinora, Zoom, Google Meet"
                    />
                </x-forms.field>

                <x-forms.field name="securityInfo" label="Información de Seguridad">
                    <x-forms.textarea 
                        name="securityInfo" 
                        wire:model="securityInfo"
                        :rows="3"
                        placeholder="Ej: Cifrado end-to-end, servidores en UE, cumplimiento RGPD"
                    />
                </x-forms.field>

                <x-forms.checkbox 
                    name="recordingConsent" 
                    label="Consentimiento para grabación de sesiones"
                />
            </div>
        </x-forms.section>
        @endif

        {{-- Sección: Información del Tutor Legal (solo para menores) --}}
        @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_MINORS)
        <x-forms.section section="minor" title="Información del Tutor Legal" icon="user">
            <div class="space-y-4">
                <x-forms.field name="legalGuardianName" label="Nombre del Tutor Legal" required>
                    <x-forms.input 
                        name="legalGuardianName" 
                        wire:model="legalGuardianName"
                        placeholder="Nombre completo del padre, madre o tutor"
                    />
                </x-forms.field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-forms.field name="legalGuardianRelationship" label="Parentesco / Relación">
                        <x-forms.input 
                            name="legalGuardianRelationship" 
                            wire:model="legalGuardianRelationship"
                            placeholder="Ej: Padre, Madre, Tutor legal"
                        />
                    </x-forms.field>

                    <x-forms.field name="legalGuardianIdDocument" label="DNI / NIE del Tutor">
                        <x-forms.input 
                            name="legalGuardianIdDocument" 
                            wire:model="legalGuardianIdDocument"
                            placeholder="Documento de identidad"
                        />
                    </x-forms.field>
                </div>

                <div class="space-y-2">
                    <x-forms.checkbox 
                        name="minorAssent" 
                        label="El menor ha dado su asentimiento (obligatorio si >12 años)"
                        wire:model.live="minorAssent"
                    />

                    @if($minorAssent)
                    <x-forms.field name="minorAssentDetails" label="Detalles del Asentimiento">
                        <x-forms.textarea 
                            name="minorAssentDetails" 
                            wire:model="minorAssentDetails"
                            :rows="2"
                            placeholder="Opcional: Detalles sobre cómo se ha obtenido el asentimiento"
                        />
                    </x-forms.field>
                    @endif
                </div>
            </div>
        </x-forms.section>
        @endif
    </x-slot:main>

    <x-slot:sidebar>
        {{-- Info Box --}}
        <x-forms.info-box type="info" title="ℹ️ Información" sticky>
            El consentimiento se generará automáticamente con la información proporcionada. 
            El paciente deberá leerlo y firmarlo antes de iniciar el tratamiento.
        </x-forms.info-box>

        {{-- Requirements --}}
        <x-forms.info-box type="warning" title="⚠️ Requisitos">
            <ul class="space-y-1">
                <li>• El paciente debe estar seleccionado</li>
                <li>• El tipo de consentimiento es obligatorio</li>
                @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_TELECONSULTATION)
                <li>• La plataforma es requerida</li>
                @endif
                @if($consentType === \App\Core\ConsentForms\Models\ConsentForm::TYPE_MINORS)
                <li>• El nombre del tutor es requerido</li>
                @endif
            </ul>
        </x-forms.info-box>
    </x-slot:sidebar>
</x-forms.layout>

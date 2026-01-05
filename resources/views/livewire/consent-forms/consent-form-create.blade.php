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
                        placeholder="Ej: 6 meses, 12 sesiones"
                    />
                </x-forms.field>

                <x-forms.field name="sessionFrequency" label="Frecuencia de Sesiones">
                    <x-forms.input 
                        name="sessionFrequency" 
                        placeholder="Ej: Semanal, Quincenal"
                    />
                </x-forms.field>

                <x-forms.field name="sessionDuration" label="Duración de Sesión (minutos)">
                    <x-forms.input 
                        type="number" 
                        name="sessionDuration" 
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
                        placeholder="Ej: Clinora, Zoom, Google Meet"
                    />
                </x-forms.field>

                <x-forms.field name="securityInfo" label="Información de Seguridad">
                    <x-forms.textarea 
                        name="securityInfo" 
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
            </ul>
        </x-forms.info-box>
    </x-slot:sidebar>
</x-forms.layout>

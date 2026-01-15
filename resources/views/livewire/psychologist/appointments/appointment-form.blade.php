<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        :title="$isEditing ? 'Editar Cita' : 'Nueva Cita'"
        description="Programe y gestione los detalles de la sesión."
        :cancel-route="profession_route('appointments.index')"
        :is-editing="$isEditing"
        submit-label="{{ $isEditing ? 'Guardar Cambios' : 'Agendar Cita' }}"
        submit-icon="save"
        loading-target="save"
    />

    <x-slot:main>
        {{-- Sección: Horario y Tipo --}}
        <x-forms.section section="schedule" title="Horario y Tipo" icon="schedule" :open="true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <x-forms.field name="start_time" label="Inicio" required>
                    <x-forms.input type="datetime-local" name="start_time" size="sm" />
                </x-forms.field>
                
                <x-forms.field name="end_time" label="Fin" required>
                    <x-forms.input type="datetime-local" name="end_time" size="sm" />
                </x-forms.field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-forms.field name="type" label="Tipo de Sesión" required>
                    <x-forms.select 
                        name="type" 
                        :options="collect(\App\Shared\Enums\AppointmentType::cases())->mapWithKeys(fn($type) => [$type->value => $type->label()])->toArray()"
                        size="sm"
                    />
                </x-forms.field>
                
                <x-forms.field name="status" label="Estado" required>
                    <x-forms.select 
                        name="status" 
                        :options="collect(\App\Shared\Enums\AppointmentStatus::cases())->mapWithKeys(fn($status) => [$status->value => $status->label()])->toArray()"
                        size="sm"
                    />
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección: Precio y Facturación --}}
        @if($isEditing && $appointment)
            <x-forms.section section="billing" title="Precio y Facturación" icon="money" :open="false">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-forms.field name="price" label="Precio (€)">
                        <x-forms.input 
                            type="number" 
                            step="0.01"
                            name="price" 
                            wire:model="price"
                            size="sm" 
                        />
                    </x-forms.field>
                    
                    <x-forms.field name="is_paid" label="Estado de Pago">
                        <x-forms.checkbox 
                            name="is_paid" 
                            wire:model="is_paid"
                            label="Marcar como pagada"
                        />
                    </x-forms.field>
                </div>
                
                @if($appointment->price && !$appointment->invoice)
                    <div class="mt-4 p-4 bg-info-50 border border-info-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-info-900">Esta cita tiene precio pero no tiene factura</p>
                                <p class="text-xs text-info-700 mt-1">Puedes crear una factura automáticamente desde aquí</p>
                            </div>
                            <a href="{{ route('psychologist.invoices.create-from-appointment', $appointment->id) }}" 
                               class="btn btn-sm btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Crear Factura
                            </a>
                        </div>
                    </div>
                @elseif($appointment->invoice)
                    <div class="mt-4 p-4 bg-success-50 border border-success-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-success-900">Esta cita ya tiene factura</p>
                                <p class="text-xs text-success-700 mt-1">Factura: {{ $appointment->invoice->invoice_number }}</p>
                            </div>
                            <a href="{{ route('psychologist.invoices.show', $appointment->invoice->id) }}" 
                               class="btn btn-sm btn-outline">
                                Ver Factura
                            </a>
                        </div>
                    </div>
                @endif
            </x-forms.section>
        @endif

        {{-- Sección: Notas y Observaciones --}}
        <x-forms.section section="notes" title="Notas y Observaciones" icon="clinical">
            <x-forms.field name="notes" label="Notas">
                <x-forms.textarea 
                    name="notes" 
                    :rows="4" 
                    placeholder="Escriba aquí los detalles previos o notas sobre la cita..."
                    size="sm"
                />
            </x-forms.field>
        </x-forms.section>
    </x-slot:main>

    <x-slot:sidebar>
        {{-- Selección de Paciente --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Paciente
            </h3>
            
            <div class="mb-4">
                <x-forms.field name="contact_id" label="Seleccionar Paciente" required>
                    <x-forms.select 
                        name="contact_id" 
                        :options="collect($contacts)->mapWithKeys(fn($contact) => [$contact->id => $contact->full_name])->toArray()"
                        placeholder="Buscar paciente..."
                        size="sm"
                    />
                </x-forms.field>
            </div>

            <x-forms.info-box type="info">
                Seleccione un paciente para asociarlo a esta cita.
            </x-forms.info-box>
        </div>
    </x-slot:sidebar>
</x-forms.layout>

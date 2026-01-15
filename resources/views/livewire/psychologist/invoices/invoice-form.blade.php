<x-forms.layout wire:submit="save">
    {{-- Header --}}
    <x-forms.header 
        :title="$isEditing ? 'Editar Factura' : 'Nueva Factura'"
        description="Crea o edita una factura para tu paciente."
        :cancel-route="route('psychologist.invoices.index')"
        :is-editing="$isEditing"
        submit-label="{{ $isEditing ? 'Guardar Cambios' : 'Crear Factura' }}"
        submit-icon="save"
        loading-target="save"
    />

    <x-slot:main>
        {{-- Sección: Información Básica --}}
        <x-forms.section section="basic" title="Información Básica" icon="document" :open="true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-forms.field name="contact_id" label="Paciente" required>
                    <x-forms.select 
                        name="contact_id" 
                        wire:model.live="contact_id"
                        :options="$contacts->mapWithKeys(fn($c) => [$c->id => $c->first_name . ' ' . $c->last_name])->toArray()"
                        size="sm"
                    />
                </x-forms.field>

                @if(count($appointments) > 0)
                    <x-forms.field name="appointment_id" label="Cita Relacionada">
                        <x-forms.select 
                            name="appointment_id" 
                            wire:model="appointment_id"
                            :options="collect([null => 'Ninguna'])->merge($appointments->mapWithKeys(fn($a) => [$a->id => $a->start_time->format('d/m/Y H:i')]))->toArray()"
                            size="sm"
                        />
                    </x-forms.field>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <x-forms.field name="delivery_note_code" label="Código de Albarán" required>
                    <x-forms.input 
                        name="delivery_note_code" 
                        wire:model="delivery_note_code"
                        size="sm"
                        placeholder="Ej: ALB-2025-0001"
                        :disabled="$isEditing"
                        :class="$isEditing ? 'bg-gray-100 cursor-not-allowed' : ''"
                    />
                    <x-forms.help-text>
                        @if($isEditing)
                            💡 El código de albarán no se puede modificar una vez creada la factura.
                        @else
                            💡 El código de albarán se genera automáticamente de forma secuencial. Puedes modificarlo si es necesario.
                        @endif
                    </x-forms.help-text>
                </x-forms.field>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                <x-forms.field name="issue_date" label="Fecha de Emisión" required>
                    <x-forms.input type="date" name="issue_date" size="sm" />
                </x-forms.field>
                
                <x-forms.field name="due_date" label="Fecha de Vencimiento" required>
                    <x-forms.input type="date" name="due_date" size="sm" />
                </x-forms.field>

                <x-forms.field name="currency" label="Moneda" required>
                    <x-forms.select 
                        name="currency" 
                        :options="['EUR' => 'EUR', 'USD' => 'USD']"
                        size="sm"
                    />
                </x-forms.field>
            </div>

            <div class="mt-5">
                <x-forms.field name="is_b2b" label="Tipo de Cliente">
                    <x-forms.checkbox 
                        name="is_b2b" 
                        wire:model="is_b2b"
                        label="Cliente B2B (Empresa/Autónomo/Seguro) - Aplicará retención IRPF"
                    />
                    <x-forms.help-text>
                        Si está marcado, se aplicará retención IRPF (15% o 7% según años de actividad). 
                        Para pacientes particulares, dejar sin marcar.
                    </x-forms.help-text>
                </x-forms.field>
            </div>
        </x-forms.section>

        {{-- Sección: Items de Factura --}}
        <x-forms.section section="items" title="Items de Factura" icon="list" :open="true">
            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-5">
                                <x-forms.field name="items.{{ $index }}.description" label="Descripción" required>
                                    <x-forms.input 
                                        name="items.{{ $index }}.description" 
                                        wire:model="items.{{ $index }}.description"
                                        size="sm" 
                                    />
                                </x-forms.field>
                            </div>
                            <div class="md:col-span-2">
                                <x-forms.field name="items.{{ $index }}.quantity" label="Cantidad" required>
                                    <x-forms.input 
                                        type="number" 
                                        step="0.01"
                                        name="items.{{ $index }}.quantity" 
                                        wire:model="items.{{ $index }}.quantity"
                                        size="sm" 
                                    />
                                </x-forms.field>
                            </div>
                            <div class="md:col-span-2">
                                <x-forms.field name="items.{{ $index }}.unit_price" label="Precio Unitario" required>
                                    <x-forms.input 
                                        type="number" 
                                        step="0.01"
                                        name="items.{{ $index }}.unit_price" 
                                        wire:model="items.{{ $index }}.unit_price"
                                        size="sm" 
                                    />
                                </x-forms.field>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-text-secondary mb-2">Subtotal</label>
                                <div class="input input-sm bg-gray-50">
                                    {{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }} €
                                </div>
                            </div>
                            <div class="md:col-span-1 flex items-end">
                                @if(count($items) > 1)
                                    <button 
                                        type="button"
                                        wire:click="removeItem({{ $index }})"
                                        class="btn btn-sm btn-ghost text-danger-600 hover:text-danger-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <x-forms.field name="items.{{ $index }}.notes" label="Notas">
                                <x-forms.textarea 
                                    name="items.{{ $index }}.notes" 
                                    wire:model="items.{{ $index }}.notes"
                                    :rows="2" 
                                    size="sm"
                                />
                            </x-forms.field>
                        </div>
                    </div>
                @endforeach

                <button 
                    type="button"
                    wire:click="addItem"
                    class="btn btn-sm btn-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Item
                </button>
            </div>

            {{-- Resumen --}}
            <div class="mt-6 border-t border-gray-200 pt-4">
                <div class="flex justify-end">
                    <div class="w-full max-w-md space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-text-secondary">Subtotal:</span>
                            <span class="font-medium">{{ number_format(collect($items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['unit_price'] ?? 0)), 2) }} €</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-text-secondary">IVA:</span>
                            <span class="font-medium">Exento</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-text-secondary">Retención IRPF:</span>
                            <span class="font-medium">Se calculará automáticamente</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                            <span>Total:</span>
                            <span>{{ number_format(collect($items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['unit_price'] ?? 0)), 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-forms.section>

        {{-- Sección: Notas --}}
        <x-forms.section section="notes" title="Notas" icon="note">
            <x-forms.field name="notes" label="Notas">
                <x-forms.textarea 
                    name="notes" 
                    :rows="4" 
                    size="sm"
                    placeholder="Notas adicionales para la factura..."
                />
            </x-forms.field>
        </x-forms.section>
    </x-slot:main>
</x-forms.layout>

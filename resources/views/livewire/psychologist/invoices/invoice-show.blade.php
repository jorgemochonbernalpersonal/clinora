<div class="space-y-6 pb-8 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-text-primary">Factura: {{ $invoice->invoice_number }}</h1>
                <p class="text-text-secondary mt-1">
                    Emitida el {{ $invoice->issue_date->format('d/m/Y') }}
                    @if($invoice->isOverdue())
                        <span class="text-danger-600 font-medium">• Vencida</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($invoice->canBeEdited())
                <a href="{{ route('psychologist.invoices.edit', $invoice->id) }}" 
                   class="btn btn-outline">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
            @endif
            @if($invoice->status->value === 'draft')
                <button wire:click="sendInvoice" 
                        wire:loading.attr="disabled"
                        wire:target="sendInvoice"
                        class="btn btn-outline relative">
                    <span wire:loading.remove wire:target="sendInvoice" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar como Enviada
                    </span>
                    <span wire:loading wire:target="sendInvoice" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    </span>
                </button>
            @endif
            @if($invoice->status->value === 'sent' || $invoice->status->value === 'draft')
                @if($invoice->contact->email)
                    <button wire:click="sendInvoiceByEmail" 
                            wire:loading.attr="disabled"
                            class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Enviar por Email
                    </button>
                @else
                    <div class="tooltip" data-tip="El paciente no tiene email registrado">
                        <button disabled class="btn btn-outline opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Enviar por Email
                        </button>
                    </div>
                @endif
            @endif
            @if($invoice->status->value === 'sent' || $invoice->status->value === 'draft')
                @if($this->stripeEnabled && $invoice->status->value === 'sent')
                    <button wire:click="payWithStripe" 
                            wire:loading.attr="disabled"
                            class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Pagar con Tarjeta
                    </button>
                @endif
                <button wire:click="markAsPaid" 
                        wire:loading.attr="disabled"
                        wire:target="markAsPaid"
                        class="btn btn-success relative">
                    <span wire:loading.remove wire:target="markAsPaid" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Marcar Pagada
                    </span>
                    <span wire:loading wire:target="markAsPaid" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
            @endif
            @if($invoice->canBeCancelled())
                <button wire:click="cancelInvoice" 
                        wire:loading.attr="disabled"
                        class="btn btn-ghost text-danger-600">
                    Cancelar
                </button>
            @endif
            <a href="{{ route('psychologist.invoices.pdf', $invoice->id) }}" 
               target="_blank"
               class="btn btn-outline">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Invoice Info Card --}}
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Información de la Factura</h3>
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                @if($invoice->status->value === 'paid') bg-green-100 text-green-800
                @elseif($invoice->status->value === 'overdue') bg-red-100 text-red-800
                @elseif($invoice->status->value === 'sent') bg-blue-100 text-blue-800
                @else bg-yellow-100 text-yellow-800
                @endif">
                {{ $invoice->status->label() }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Paciente</p>
                <p class="font-semibold text-gray-900">{{ $invoice->contact->first_name }} {{ $invoice->contact->last_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Código Albarán</p>
                <p class="font-semibold text-gray-900">{{ $invoice->delivery_note_code ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Fecha Emisión</p>
                <p class="font-semibold text-gray-900">{{ $invoice->issue_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Fecha Vencimiento</p>
                <p class="font-semibold {{ $invoice->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $invoice->due_date->format('d/m/Y') }}
                    @if($invoice->isOverdue())
                        <span class="text-xs text-red-500 ml-1">(Vencida)</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <p class="font-semibold text-2xl text-primary-600">{{ number_format($invoice->total, 2) }} €</p>
            </div>
        </div>
    </div>

    {{-- Invoice Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Professional Info --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Datos del Profesional</h2>
                <div class="space-y-2 text-text-secondary">
                    <p class="font-medium">{{ $invoice->professional->name }}</p>
                    <p>{{ $invoice->professional->full_address }}</p>
                    @if($invoice->professional->license_number)
                        <p>Nº Colegiado: {{ $invoice->professional->license_number }}</p>
                    @endif
                </div>
            </div>

            {{-- Client Info --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Datos del Cliente</h2>
                <div class="space-y-2 text-text-secondary">
                    <p class="font-medium">{{ $invoice->contact->first_name }} {{ $invoice->contact->last_name }}</p>
                    @if($invoice->contact->email)
                        <p>{{ $invoice->contact->email }}</p>
                    @endif
                    @if($invoice->contact->phone)
                        <p>{{ $invoice->contact->phone }}</p>
                    @endif
                    @if($invoice->contact->dni)
                        <p>DNI/NIF: {{ $invoice->contact->dni }}</p>
                    @endif
                    @if($invoice->is_b2b)
                        <p class="text-sm text-info-600">Cliente B2B (Empresa/Autónomo)</p>
                    @endif
                </div>
            </div>

            {{-- Invoice Items --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="font-bold text-gray-900">Items</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($item->unit_price, 2) }} €</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($item->subtotal, 2) }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Totals --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-semibold text-text-primary mb-4">Resumen</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-text-secondary">Subtotal:</span>
                        <span class="font-medium">{{ number_format($invoice->subtotal, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-text-secondary">IVA:</span>
                        <span class="font-medium">Exento</span>
                    </div>
                    @if($invoice->irpf_retention > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-text-secondary">Retención IRPF ({{ $invoice->irpf_rate }}%):</span>
                            <span class="font-medium text-danger-600">-{{ number_format($invoice->irpf_retention, 2) }} €</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                        <span>Total:</span>
                        <span class="text-primary-600">{{ number_format($invoice->total, 2) }} €</span>
                    </div>
                </div>
            </div>

            {{-- Related Appointment --}}
            @if($invoice->appointment)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-semibold text-text-primary mb-4">Cita Relacionada</h2>
                    <div class="space-y-2 text-sm text-text-secondary">
                        <p><strong>Fecha:</strong> {{ $invoice->appointment->start_time->format('d/m/Y H:i') }}</p>
                        <p><strong>Tipo:</strong> {{ $invoice->appointment->type->label() }}</p>
                        <a href="{{ route('psychologist.appointments.edit', $invoice->appointment->id) }}" 
                           class="text-primary-600 hover:text-primary-800">
                            Ver cita →
                        </a>
                    </div>
                </div>
            @endif

            {{-- Notes --}}
            @if($invoice->notes)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-semibold text-text-primary mb-4">Notas</h2>
                    <p class="text-sm text-text-secondary whitespace-pre-line">{{ $invoice->notes }}</p>
                </div>
            @endif

            {{-- Payment Info --}}
            @if($invoice->status->value === 'paid' && $invoice->paid_at)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-semibold text-text-primary mb-4">Información de Pago</h2>
                    <div class="space-y-2 text-sm text-text-secondary">
                        <p><strong>Fecha de pago:</strong> {{ $invoice->paid_at->format('d/m/Y H:i') }}</p>
                        @if($invoice->stripe_payment_intent_id)
                            <p><strong>Método:</strong> Tarjeta (Stripe)</p>
                            <p><strong>ID de pago:</strong> <code class="text-xs">{{ $invoice->stripe_payment_intent_id }}</code></p>
                        @else
                            <p><strong>Método:</strong> Manual</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- VeriFactu Info --}}
            @if($invoice->xml_path)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h2 class="text-lg font-semibold text-text-primary mb-4">VeriFactu</h2>
                    <div class="space-y-2 text-sm text-text-secondary">
                        <p class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>XML generado y cumpliendo con normativa AEAT</span>
                        </p>
                        <p class="text-xs text-text-secondary mt-2">
                            Esta factura cumple con la normativa VeriFactu de la Agencia Estatal de Administración Tributaria.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

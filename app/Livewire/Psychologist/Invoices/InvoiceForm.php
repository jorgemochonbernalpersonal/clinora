<?php

namespace App\Livewire\Psychologist\Invoices;

use App\Core\Appointments\Models\Appointment;
use App\Core\Billing\Models\Invoice;
use App\Core\Billing\Models\InvoicingSetting;
use App\Core\Billing\Services\InvoiceService;
use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class InvoiceForm extends Component
{
    public ?Invoice $invoice = null;
    
    // Form fields
    public $contact_id;
    public $appointment_id = null;
    public $issue_date;
    public $due_date;
    public $currency = 'EUR';
    public $notes = '';
    public $items = [];
    public $is_b2b = false;
    public $delivery_note_code = '';
    public $delivery_note_code_auto = '';
    public $delivery_note_code_modified = false;
    
    // UI State
    public $isEditing = false;
    public $contacts = [];
    public $appointments = [];

    protected function rules()
    {
        $professional = auth()->user()->professional;
        
        return [
            'contact_id' => [
                'required',
                'exists:contacts,id,professional_id,' . $professional->id . ',deleted_at,NULL'
            ],
            'appointment_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($professional) {
                    if ($value && !empty($value)) {
                        $appointment = Appointment::where('id', $value)
                            ->where('professional_id', $professional->id)
                            ->first();
                        
                        if (!$appointment) {
                            $fail('La cita seleccionada no existe o no pertenece a tu práctica.');
                        }
                    }
                },
            ],
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'delivery_note_code' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.notes' => 'nullable|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function mount($id = null, $appointmentId = null)
    {
        $this->contacts = Contact::where('professional_id', auth()->user()->professional->id)
            ->orderBy('first_name')
            ->get();

        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');

        // Generar código de albarán automáticamente
        $professional = auth()->user()->professional;
        $settings = InvoicingSetting::getOrCreateForProfessional($professional->id);
        $this->delivery_note_code_auto = $settings->generateDeliveryNoteCode();
        $this->delivery_note_code = $this->delivery_note_code_auto;

        if ($id) {
            $this->loadInvoice($id);
        } elseif ($appointmentId) {
            $this->loadFromAppointment($appointmentId);
        } else {
            // Default item
            $this->items = [
                [
                    'description' => '',
                    'notes' => '',
                    'quantity' => 1,
                    'unit_price' => 0,
                ]
            ];
        }
        
        // Initialize appointment_id as null if not set
        if (!isset($this->appointment_id)) {
            $this->appointment_id = null;
        }
    }

    public function loadFromAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        
        if (!$appointment || $appointment->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Cita no encontrada');
            return redirect()->route('psychologist.invoices.create');
        }

        if ($appointment->invoice) {
            session()->flash('error', 'Ya existe una factura para esta cita');
            return redirect()->route('psychologist.invoices.show', $appointment->invoice->id);
        }

        $this->appointment_id = $appointment->id;
        $this->contact_id = $appointment->contact_id;
        $this->currency = $appointment->currency ?? 'EUR';
        
        $this->items = [
            [
                'description' => $this->getServiceDescription($appointment),
                'notes' => '',
                'quantity' => 1,
                'unit_price' => $appointment->price ?? 0,
            ]
        ];
    }

    public function loadInvoice($id)
    {
        $invoice = Invoice::with(['items', 'contact', 'appointment'])
            ->find($id);
        
        if (!$invoice || $invoice->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Factura no encontrada');
            return redirect()->route('psychologist.invoices.index');
        }

        if (!$invoice->canBeEdited()) {
            session()->flash('error', 'Esta factura no puede ser editada');
            return redirect()->route('psychologist.invoices.show', $invoice->id);
        }

        $this->invoice = $invoice;
        $this->isEditing = true;
        
        $this->contact_id = $invoice->contact_id;
        $this->appointment_id = $invoice->appointment_id;
        $this->issue_date = $invoice->issue_date->format('Y-m-d');
        $this->due_date = $invoice->due_date->format('Y-m-d');
        $this->currency = $invoice->currency;
        $this->notes = $invoice->notes ?? '';
        $this->is_b2b = $invoice->is_b2b ?? false;
        $this->delivery_note_code = $invoice->delivery_note_code ?? '';
        $this->delivery_note_code_auto = $invoice->delivery_note_code ?? '';
        $this->delivery_note_code_modified = false; // En edición, no se puede modificar
        
        $this->items = $invoice->items->map(function ($item) {
            return [
                'description' => $item->description,
                'notes' => $item->notes ?? '',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ];
        })->toArray();
    }

    public function updatedContactId()
    {
        if ($this->contact_id) {
            $this->appointments = Appointment::where('contact_id', $this->contact_id)
                ->where('professional_id', auth()->user()->professional->id)
                ->whereDoesntHave('invoice') // Only show appointments without invoice
                ->orderBy('start_time', 'desc')
                ->get();
        } else {
            $this->appointments = [];
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'notes' => '',
            'quantity' => 1,
            'unit_price' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindex array
    }

    public function save()
    {
        // Normalize appointment_id - convert empty string to null
        if ($this->appointment_id === '' || $this->appointment_id === '0') {
            $this->appointment_id = null;
        }
        
        $validated = $this->validate();
        
        $invoiceService = app(InvoiceService::class);
        $professional = auth()->user()->professional;

        try {
            if ($this->isEditing) {
                $invoice = $invoiceService->updateInvoice(
                    $this->invoice->id,
                    $validated,
                    auth()->id()
                );
                session()->flash('success', 'Factura actualizada correctamente.');
            } else {
                // Add is_b2b to validated data
                $validated['is_b2b'] = $this->is_b2b;
                
                // Si el código de albarán fue modificado, usar el personalizado
                // Si no, se generará automáticamente en el servicio
                if ($this->delivery_note_code_modified) {
                    $validated['delivery_note_code'] = $this->delivery_note_code;
                }
                
                $invoice = $invoiceService->createManualInvoice(
                    $professional,
                    $validated['contact_id'],
                    $validated,
                    auth()->id()
                );
                session()->flash('success', 'Factura creada correctamente.');
            }
            
            return redirect()->route('psychologist.invoices.show', $invoice->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la factura: ' . $e->getMessage());
        }
    }

    private function getServiceDescription(Appointment $appointment): string
    {
        $typeLabels = [
            'in_person' => 'presencial',
            'online' => 'online',
            'home_visit' => 'domicilio',
            'phone' => 'telefónica',
        ];

        $type = $typeLabels[$appointment->type->value] ?? 'presencial';
        
        return "Sesión de psicología clínica - {$type}";
    }

    public function updatedDeliveryNoteCode($value)
    {
        if ($value !== $this->delivery_note_code_auto) {
            $this->delivery_note_code_modified = true;
        } else {
            $this->delivery_note_code_modified = false;
        }
    }

    public function render()
    {
        return view('livewire.psychologist.invoices.invoice-form');
    }
}

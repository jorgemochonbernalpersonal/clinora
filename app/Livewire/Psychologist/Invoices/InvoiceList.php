<?php

namespace App\Livewire\Psychologist\Invoices;

use App\Core\Appointments\Models\Appointment;
use App\Core\Billing\Models\Invoice;
use App\Core\Billing\Services\InvoiceService;
use App\Shared\Enums\InvoiceStatus;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class InvoiceList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFilter = 'all';
    public $contactFilter = null;
    public $fromDate = null;
    public $toDate = null;
    public $showOverdue = false;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'contactFilter',
        'fromDate',
        'toDate',
        'showOverdue' => ['except' => false],
    ];

    public function mount()
    {
        // Set default date range to current month
        if (!$this->fromDate) {
            $this->fromDate = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$this->toDate) {
            $this->toDate = now()->endOfMonth()->format('Y-m-d');
        }
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingContactFilter()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

    public function updatingShowOverdue()
    {
        $this->resetPage();
    }

    public function getInvoicesProperty()
    {
        $professional = auth()->user()->professional;
        $invoiceService = app(InvoiceService::class);

        $filters = array_filter([
            'status' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'contact_id' => $this->contactFilter,
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'overdue' => $this->showOverdue,
            'search' => $this->searchTerm ?: null,
        ]);

        return $invoiceService->getInvoicesForProfessional(
            $professional,
            $filters,
            true,
            15
        );
    }

    public function getStatisticsProperty()
    {
        $professional = auth()->user()->professional;
        $invoiceService = app(InvoiceService::class);

        $fromDate = $this->fromDate ? \Carbon\Carbon::parse($this->fromDate) : null;
        $toDate = $this->toDate ? \Carbon\Carbon::parse($this->toDate) : null;

        $stats = $invoiceService->getStatistics($professional, $fromDate, $toDate);
        
        // Add status counts
        $stats['draft'] = Invoice::forProfessional($professional->id)
            ->where('status', InvoiceStatus::DRAFT)
            ->when($fromDate, fn($q) => $q->where('issue_date', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->where('issue_date', '<=', $toDate))
            ->count();
            
        $stats['sent'] = Invoice::forProfessional($professional->id)
            ->where('status', InvoiceStatus::SENT)
            ->when($fromDate, fn($q) => $q->where('issue_date', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->where('issue_date', '<=', $toDate))
            ->count();

        return $stats;
    }

    public function getUninvoicedAppointmentsProperty()
    {
        $professional = auth()->user()->professional;
        
        // Get appointments that don't have an invoice yet using the relationship
        return Appointment::where('professional_id', $professional->id)
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->whereDoesntHave('invoice')
            ->where('status', 'completed')
            ->with('contact:id,first_name,last_name')
            ->orderBy('start_time', 'desc')
            ->limit(10)
            ->get();
    }

    public function createInvoiceFromAppointment($appointmentId)
    {
        $appointment = Appointment::find($appointmentId);
        
        if (!$appointment || $appointment->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Sesión no encontrada');
            return;
        }

        if ($appointment->invoice) {
            session()->flash('error', 'Ya existe una factura para esta sesión');
            return redirect()->route('psychologist.invoices.show', $appointment->invoice->id);
        }

        try {
            $invoiceService = app(InvoiceService::class);
            $invoice = $invoiceService->createFromAppointment($appointment, auth()->id());
            
            session()->flash('success', 'Factura creada correctamente desde la sesión');
            return redirect()->route('psychologist.invoices.show', $invoice->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    public function createInvoiceFromMultipleAppointments($appointmentIds)
    {
        $professional = auth()->user()->professional;
        $appointments = Appointment::whereIn('id', $appointmentIds)
            ->where('professional_id', $professional->id)
            ->whereDoesntHave('invoice')
            ->with('contact')
            ->get();

        if ($appointments->isEmpty()) {
            session()->flash('error', 'No se encontraron sesiones válidas para facturar');
            return;
        }

        // Verificar que todas las sesiones sean del mismo paciente
        $contactIds = $appointments->pluck('contact_id')->unique();
        if ($contactIds->count() > 1) {
            session()->flash('error', 'Solo puedes facturar sesiones del mismo paciente juntas');
            return;
        }

        try {
            $invoiceService = app(InvoiceService::class);
            $contact = $appointments->first()->contact;
            
            // Crear factura manual con múltiples items
            $items = $appointments->map(function ($appointment) use ($invoiceService) {
                return [
                    'description' => $invoiceService->getServiceDescription($appointment),
                    'quantity' => 1,
                    'unit_price' => $appointment->price ?? 0,
                    'notes' => $appointment->start_time->format('d/m/Y H:i'),
                ];
            })->toArray();

            $invoice = $invoiceService->createManualInvoice(
                $professional,
                $contact->id,
                [
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'currency' => 'EUR',
                    'items' => $items,
                    'notes' => 'Factura agrupada de ' . $appointments->count() . ' sesiones',
                ],
                auth()->id()
            );

            // Vincular las citas a la factura (aunque solo una puede tener appointment_id directo)
            // Guardamos los IDs en notas internas
            $invoice->update([
                'internal_notes' => 'Sesiones facturadas: ' . $appointments->pluck('id')->implode(', ')
            ]);

            session()->flash('success', 'Factura creada con ' . $appointments->count() . ' sesiones');
            return redirect()->route('psychologist.invoices.show', $invoice->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear la factura: ' . $e->getMessage());
        }
    }

    public function deleteInvoice($id)
    {
        $invoice = Invoice::find($id);
        
        if (!$invoice || $invoice->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Factura no encontrada');
            return;
        }

        if (!$invoice->canBeCancelled()) {
            session()->flash('error', 'Esta factura no puede ser cancelada');
            return;
        }

        $invoice->cancel();
        session()->flash('success', 'Factura cancelada correctamente');
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::find($id);
        
        if (!$invoice || $invoice->professional_id !== auth()->user()->professional->id) {
            session()->flash('error', 'Factura no encontrada');
            return;
        }

        $invoice->markAsPaid();
        session()->flash('success', 'Factura marcada como pagada');
    }

    public function render()
    {
        return view('livewire.psychologist.invoices.invoice-list', [
            'invoices' => $this->invoices,
            'statistics' => $this->statistics,
            'statuses' => InvoiceStatus::cases(),
            'uninvoicedAppointments' => $this->uninvoicedAppointments,
        ]);
    }
}

<?php

namespace App\Core\Billing\Services;

use App\Core\Appointments\Models\Appointment;
use App\Core\Billing\Models\Invoice;
use App\Core\Billing\Models\InvoiceItem;
use App\Core\Billing\Models\InvoicingSetting;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    /**
     * Create invoice from appointment
     */
    public function createFromAppointment(
        Appointment $appointment,
        ?int $createdBy = null
    ): Invoice {
        $professional = $appointment->professional;
        $contact = $appointment->contact;

        // Determine if it's B2B (business client)
        $isB2B = $this->isBusinessClient($contact);

        // Generar códigos de factura y albarán
        $settings = InvoicingSetting::getOrCreateForProfessional($professional->id);
        $invoiceNumber = $settings->generateAndIncrementInvoiceCode();
        $deliveryNoteCode = $settings->generateAndIncrementDeliveryNoteCode();

        $invoice = Invoice::create([
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'appointment_id' => $appointment->id,
            'invoice_number' => $invoiceNumber,
            'delivery_note_code' => $deliveryNoteCode,
            'series' => $professional->invoice_series ?? 'A',
            'issue_date' => now(),
            'due_date' => now()->addDays(config('billing.invoice.due_days', 30)),
            'subtotal' => $appointment->price ?? 0,
            'is_iva_exempt' => true, // Psicólogos siempre exentos de IVA
            'is_b2b' => $isB2B,
            'irpf_rate' => $isB2B ? $this->getIrpfRate($professional) : null,
            'status' => InvoiceStatus::DRAFT,
            'currency' => $appointment->currency ?? 'EUR',
            'created_by' => $createdBy,
        ]);

        // Calculate totals
        $invoice->calculateTotal();
        $invoice->save();

        // Create invoice item
        $invoice->items()->create([
            'description' => $this->getServiceDescription($appointment),
            'quantity' => 1,
            'unit_price' => $appointment->price ?? 0,
            'tax_rate' => 0, // IVA exento
        ]);

        return $invoice->fresh(['items', 'professional', 'contact', 'appointment']);
    }

    /**
     * Create manual invoice
     */
    public function createManualInvoice(
        Professional $professional,
        int $contactId,
        array $data,
        ?int $createdBy = null
    ): Invoice {
        $contact = Contact::findOrFail($contactId);
        
        // Verify contact belongs to professional
        if ($contact->professional_id !== $professional->id) {
            throw new \Exception('El paciente no pertenece a tu práctica');
        }

        // Use explicit is_b2b from data if provided, otherwise auto-detect
        $isB2B = isset($data['is_b2b']) ? (bool) $data['is_b2b'] : $this->isBusinessClient($contact);

        // Generar códigos de factura y albarán
        $settings = InvoicingSetting::getOrCreateForProfessional($professional->id);
        $invoiceNumber = $settings->generateAndIncrementInvoiceCode();
        // Si se proporciona un delivery_note_code personalizado, usarlo; si no, generar automático e incrementar
        if (isset($data['delivery_note_code']) && !empty($data['delivery_note_code'])) {
            $deliveryNoteCode = $data['delivery_note_code'];
        } else {
            $deliveryNoteCode = $settings->generateAndIncrementDeliveryNoteCode();
        }

        $invoice = Invoice::create([
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'appointment_id' => $data['appointment_id'] ?? null,
            'invoice_number' => $invoiceNumber,
            'delivery_note_code' => $deliveryNoteCode,
            'series' => $professional->invoice_series ?? 'A',
            'issue_date' => $data['issue_date'] ?? now(),
            'due_date' => $data['due_date'] ?? now()->addDays(config('billing.invoice.due_days', 30)),
            'subtotal' => 0, // Will be calculated from items
            'is_iva_exempt' => true,
            'is_b2b' => $isB2B,
            'irpf_rate' => $isB2B ? $this->getIrpfRate($professional) : null,
            'status' => InvoiceStatus::DRAFT,
            'currency' => $data['currency'] ?? 'EUR',
            'notes' => $data['notes'] ?? null,
            'created_by' => $createdBy,
        ]);

        // Add items if provided
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $itemData) {
                $invoice->items()->create([
                    'description' => $itemData['description'],
                    'notes' => $itemData['notes'] ?? null,
                    'quantity' => $itemData['quantity'] ?? 1,
                    'unit_price' => $itemData['unit_price'],
                    'tax_rate' => 0, // IVA exento
                ]);
            }
        }

        // Recalculate totals
        $invoice->recalculateTotals();

        return $invoice->fresh(['items', 'professional', 'contact', 'appointment']);
    }

    /**
     * Update invoice
     */
    public function updateInvoice(
        string $id,
        array $data,
        ?int $updatedBy = null
    ): Invoice {
        $invoice = Invoice::findOrFail($id);

        if (!$invoice->canBeEdited()) {
            throw new \Exception('Esta factura no puede ser editada');
        }

        $data['updated_by'] = $updatedBy;
        $invoice->update($data);

        // If items were updated, recalculate
        if (isset($data['items'])) {
            // Delete existing items
            $invoice->items()->delete();
            
            // Create new items
            foreach ($data['items'] as $itemData) {
                $invoice->items()->create([
                    'description' => $itemData['description'],
                    'notes' => $itemData['notes'] ?? null,
                    'quantity' => $itemData['quantity'] ?? 1,
                    'unit_price' => $itemData['unit_price'],
                    'tax_rate' => 0,
                ]);
            }
            
            $invoice->recalculateTotals();
        } else {
            // Recalculate totals if subtotal changed
            if (isset($data['subtotal'])) {
                $invoice->calculateTotal();
                $invoice->save();
            }
        }

        return $invoice->fresh(['items', 'professional', 'contact', 'appointment']);
    }

    /**
     * Get invoices for professional
     */
    public function getInvoicesForProfessional(
        Professional $professional,
        array $filters = [],
        bool $paginate = true,
        int $perPage = 50
    ): Collection|LengthAwarePaginator {
        $query = Invoice::forProfessional($professional->id)
            ->with(['contact', 'appointment'])
            ->orderBy('issue_date', 'desc');

        // Apply filters
        if (isset($filters['status'])) {
            $query->byStatus(InvoiceStatus::from($filters['status']));
        }

        if (isset($filters['contact_id'])) {
            $query->forContact($filters['contact_id']);
        }

        if (isset($filters['from_date'])) {
            $query->where('issue_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('issue_date', '<=', $filters['to_date']);
        }

        if (isset($filters['overdue']) && $filters['overdue']) {
            $query->overdue();
        }

        // Search filter (by invoice number or contact name)
        if (isset($filters['search']) && !empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoice_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('contact', function ($contactQuery) use ($searchTerm) {
                      $contactQuery->where('first_name', 'like', "%{$searchTerm}%")
                                   ->orWhere('last_name', 'like', "%{$searchTerm}%")
                                   ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    /**
     * Get single invoice
     */
    public function getInvoice(string $id, array $with = []): Invoice
    {
        $invoice = Invoice::with(['items', 'professional', 'contact', 'appointment'])
            ->findOrFail($id);

        if (!empty($with)) {
            $invoice->load($with);
        }

        return $invoice;
    }

    /**
     * Send invoice (mark as sent and generate PDF/XML)
     */
    public function sendInvoice(string $id): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        
        // Generate PDF and XML if not already generated
        if (!$invoice->pdf_path) {
            try {
                $pdfService = app(InvoicePdfService::class);
                $pdfPath = $pdfService->generatePdf($invoice);
                $invoice->pdf_path = $pdfPath;
            } catch (\Exception $e) {
                Log::error('Error generating invoice PDF', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        if (!$invoice->xml_path) {
            try {
                $xmlService = app(VeriFactuXmlService::class);
                $xmlPath = $xmlService->generateXml($invoice);
                $invoice->xml_path = $xmlPath;
            } catch (\Exception $e) {
                Log::error('Error generating invoice XML', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $invoice->markAsSent();
        $invoice->save();
        
        return $invoice->fresh();
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(string $id): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->markAsPaid();
        return $invoice->fresh();
    }

    /**
     * Cancel invoice
     */
    public function cancelInvoice(string $id): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->cancel();
        return $invoice->fresh();
    }

    /**
     * Check if contact is a business client (B2B)
     */
    private function isBusinessClient(Contact $contact): bool
    {
        // Check if DNI indicates business (CIF/NIF format)
        // In Spain, CIF starts with letter, NIF for companies can be identified
        // CIF format: Letter + 7-8 digits + letter
        if (!empty($contact->dni)) {
            $dni = strtoupper(trim($contact->dni));
            // CIF format: Letter + 7-8 digits + letter
            if (preg_match('/^[A-Z]\d{7,8}[A-Z0-9]$/', $dni)) {
                return true;
            }
        }

        // Could add more checks here (e.g., custom field for client type)
        // For now, default to false (particular client)
        return false;
    }

    /**
     * Get IRPF rate based on professional's years of activity
     */
    private function getIrpfRate(Professional $professional): float
    {
        $yearsActive = $professional->created_at->diffInYears(now());
        return $yearsActive < 3 ? 7.0 : 15.0;
    }

    /**
     * Generate invoice number
     */
    /**
     * Generate invoice number (legacy method, now uses InvoicingSetting)
     * @deprecated Use InvoicingSetting::generateAndIncrementInvoiceCode() instead
     */
    private function generateInvoiceNumber(Professional $professional): string
    {
        $settings = InvoicingSetting::getOrCreateForProfessional($professional->id);
        return $settings->generateAndIncrementInvoiceCode();
    }

    /**
     * Get service description for appointment
     */
    public function getServiceDescription(Appointment $appointment): string
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

    /**
     * Verify invoice belongs to professional
     */
    public function verifyOwnership(string $invoiceId, Professional $professional): bool
    {
        $invoice = Invoice::find($invoiceId);
        
        if (!$invoice) {
            return false;
        }

        return $invoice->professional_id === $professional->id;
    }

    /**
     * Get invoice statistics for professional
     */
    public function getStatistics(Professional $professional, ?\Carbon\Carbon $fromDate = null, ?\Carbon\Carbon $toDate = null): array
    {
        $query = Invoice::forProfessional($professional->id);

        if ($fromDate) {
            $query->where('issue_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->where('issue_date', '<=', $toDate);
        }

        $invoices = $query->get();

        return [
            'total_invoices' => $invoices->count(),
            'total_amount' => $invoices->sum('total'),
            'paid_amount' => $invoices->where('status', InvoiceStatus::PAID)->sum('total'),
            'pending_amount' => $invoices->whereIn('status', [InvoiceStatus::DRAFT, InvoiceStatus::SENT])->sum('total'),
            'overdue_amount' => $invoices->where('status', InvoiceStatus::OVERDUE)->sum('total'),
            'paid_count' => $invoices->where('status', InvoiceStatus::PAID)->count(),
            'pending_count' => $invoices->whereIn('status', [InvoiceStatus::DRAFT, InvoiceStatus::SENT])->count(),
            'overdue_count' => $invoices->where('status', InvoiceStatus::OVERDUE)->count(),
        ];
    }
}

<?php

namespace App\Core\Billing\Models;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\InvoiceStatus;
use App\Shared\Traits\HasProfessional;
use App\Shared\Traits\HasContact;
use App\Shared\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, HasProfessional, HasContact, HasAuditLog;

    protected $fillable = [
        'professional_id',
        'contact_id',
        'appointment_id',
        'invoice_number',
        'delivery_note_code',
        'series',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'tax',
        'irpf_retention',
        'total',
        'currency',
        'is_iva_exempt',
        'irpf_rate',
        'is_b2b',
        'notes',
        'internal_notes',
        'pdf_path',
        'xml_path',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'paid_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'status' => InvoiceStatus::class,
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'irpf_retention' => 'decimal:2',
        'total' => 'decimal:2',
        'is_iva_exempt' => 'boolean',
        'is_b2b' => 'boolean',
        'irpf_rate' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the professional
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Get the contact/patient
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the appointment (if linked)
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get invoice items
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Calculate IRPF retention based on client type
     */
    public function calculateIrpfRetention(): float
    {
        if (!$this->is_b2b) {
            return 0; // Pacientes particulares no tienen retención
        }

        // Use provided rate or calculate default
        $rate = $this->irpf_rate ?? $this->getDefaultIrpfRate();
        
        return round($this->subtotal * ($rate / 100), 2);
    }

    /**
     * Get default IRPF rate (15% or 7% if new professional)
     */
    private function getDefaultIrpfRate(): float
    {
        $professional = $this->professional;
        if (!$professional) {
            return 15.0;
        }

        $yearsActive = $professional->created_at->diffInYears(now());
        
        return $yearsActive < 3 ? 7.0 : 15.0;
    }

    /**
     * Calculate total with IRPF retention
     */
    public function calculateTotal(): float
    {
        $this->irpf_retention = $this->calculateIrpfRetention();
        $this->tax = 0; // IVA exento para psicólogos
        
        $this->total = round($this->subtotal - $this->irpf_retention, 2);
        
        return $this->total;
    }

    /**
     * Recalculate all totals from items
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->calculateTotal();
        $this->save();
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && 
               !in_array($this->status, [InvoiceStatus::PAID, InvoiceStatus::CANCELLED]);
    }

    /**
     * Check if invoice can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === InvoiceStatus::DRAFT;
    }

    /**
     * Check if invoice can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return !in_array($this->status, [InvoiceStatus::PAID, InvoiceStatus::CANCELLED, InvoiceStatus::REFUNDED]);
    }

    /**
     * Mark invoice as sent
     */
    public function markAsSent(): void
    {
        if ($this->status === InvoiceStatus::DRAFT) {
            $this->update(['status' => InvoiceStatus::SENT]);
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => InvoiceStatus::PAID,
            'paid_at' => now(),
        ]);
        
        // If linked to appointment, mark it as paid
        if ($this->appointment) {
            $this->appointment->update(['is_paid' => true]);
        }
    }

    /**
     * Cancel invoice
     */
    public function cancel(): void
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => InvoiceStatus::CANCELLED]);
        }
    }

    /**
     * Scope to filter by professional
     */
    public function scopeForProfessional($query, $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }

    /**
     * Scope to filter by contact
     */
    public function scopeForContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, InvoiceStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', [InvoiceStatus::PAID, InvoiceStatus::CANCELLED]);
    }

    /**
     * Scope for recent invoices
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('issue_date', '>=', now()->subDays($days))
                     ->orderBy('issue_date', 'desc');
    }
}

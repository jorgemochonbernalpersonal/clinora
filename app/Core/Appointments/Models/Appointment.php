<?php

namespace App\Core\Appointments\Models;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use App\Shared\Traits\HasProfessional;
use App\Shared\Traits\HasContact;
use App\Shared\Traits\HasAuditLog;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes, HasProfessional, HasContact, HasAuditLog;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return AppointmentFactory::new();
    }

    protected $fillable = [
        'professional_id',
        'contact_id',
        'start_time',
        'end_time',
        'type',
        'status',
        'title',
        'notes',
        'internal_notes',
        'cancellation_reason',
        'price',
        'currency',
        'is_paid',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'type' => AppointmentType::class,
        'status' => AppointmentStatus::class,
        'is_paid' => 'boolean',
        'price' => 'decimal:2',
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
     * Get the clinical note for this appointment
     */
    public function clinicalNote(): HasOne
    {
        return $this->hasOne(ClinicalNote::class);
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if appointment is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->start_time->isFuture() && 
               in_array($this->status->value, ['scheduled', 'confirmed']);
    }

    /**
     * Check if appointment is today
     */
    public function isToday(): bool
    {
        return $this->start_time->isToday();
    }

    /**
     * Check if appointment can be cancelled
     */
    public function canBeCancelled(): bool
    {
        // Can cancel if not already cancelled/completed and is in the future
        return !in_array($this->status->value, ['cancelled', 'completed', 'no_show']) 
               && $this->start_time->isFuture();
    }

    /**
     * Cancel this appointment
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => AppointmentStatus::CANCELLED,
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Mark as completed
     */
    public function complete(): void
    {
        $this->update(['status' => AppointmentStatus::COMPLETED]);
    }

    /**
     * Scope to get upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now())
                     ->whereIn('status', ['scheduled', 'confirmed'])
                     ->orderBy('start_time');
    }

    /**
     * Scope to get today's appointments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', today())
                     ->orderBy('start_time');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Scope to filter by professional
     */
    public function scopeForProfessional($query, $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }
    
    /**
     * Scope to filter by contact/patient
     */
    public function scopeForContact($query, $contactId)
    {
        return $query->where('contact_id', $contactId);
    }
    
    /**
     * Scope for appointments this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->orderBy('start_time');
    }
    
    /**
     * Scope for appointments this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('start_time', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->orderBy('start_time');
    }
    
    /**
     * Scope to eager load contact with basic fields only
     */
    public function scopeWithContactBasic($query)
    {
        return $query->with('contact:id,first_name,last_name,email,phone');
    }
    
    /**
     * Scope to eager load professional with basic fields only
     */
    public function scopeWithProfessionalBasic($query)
    {
        return $query->with('professional:id,user_id,profession_type');
    }
    
    /**
     * Scope for past appointments
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<', now())
                     ->orderBy('start_time', 'desc');
    }
    
    /**
     * Scope for completed appointments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', AppointmentStatus::COMPLETED)
                     ->orderBy('start_time', 'desc');
    }
}

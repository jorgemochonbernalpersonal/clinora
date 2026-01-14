<?php

namespace App\Modules\Psychology\ClinicalNotes\Models;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Core\Appointments\Models\Appointment;
use App\Shared\Traits\HasProfessional;
use App\Shared\Traits\HasContact;
use App\Shared\Traits\HasAuditLog;
use Database\Factories\ClinicalNoteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicalNote extends Model
{
    use HasFactory, SoftDeletes, HasProfessional, HasContact, HasAuditLog;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ClinicalNoteFactory::new();
    }

    protected $fillable = [
        'professional_id',
        'contact_id',
        'appointment_id',
        'session_number',
        'session_date',
        'duration_minutes',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'interventions_used',
        'homework',
        'risk_assessment',
        'risk_details',
        'progress_rating',
        'is_signed',
        'signed_at',
    ];

    protected $casts = [
        'session_date' => 'date',
        'interventions_used' => 'array',
        'is_signed' => 'boolean',
        'signed_at' => 'datetime',
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
     * Get the related appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Sign this clinical note
     */
    public function sign(): void
    {
        if ($this->is_signed) {
            throw new \Exception('Clinical note is already signed');
        }

        $this->update([
            'is_signed' => true,
            'signed_at' => now(),
        ]);
    }

    /**
     * Check if note is signed
     */
    public function isSigned(): bool
    {
        return $this->is_signed === true;
    }

    /**
     * Get the next session number for a contact
     */
    public static function getNextSessionNumber(int $contactId): int
    {
        $lastNote = static::where('contact_id', $contactId)
                          ->orderBy('session_number', 'desc')
                          ->first();

        return $lastNote ? $lastNote->session_number + 1 : 1;
    }

    /**
     * Scope to get notes for a specific contact
     */
    public function scopeByContact($query, int $contactId)
    {
        return $query->where('contact_id', $contactId)
                     ->orderBy('session_date', 'desc');
    }

    /**
     * Scope to get only signed notes
     */
    public function scopeSigned($query)
    {
        return $query->where('is_signed', true);
    }

    /**
     * Scope to get notes with high risk assessment
     */
    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_assessment', ['high', 'imminent']);
    }
}


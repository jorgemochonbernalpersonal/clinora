<?php

namespace App\Core\Contacts\Models;

use App\Core\Users\Models\Professional;
use App\Core\Appointments\Models\Appointment;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Core\ConsentForms\Models\ConsentForm;
use App\Shared\Traits\HasProfessional;
use App\Shared\Traits\HasAuditLog;
use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes, HasProfessional, HasAuditLog;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ContactFactory::new();
    }

    protected $fillable = [
        'professional_id',
        'first_name',
        'last_name',
        'dni',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'marital_status',
        'occupation',
        'education_level',
        'address_street',
        'address_city',
        'address_postal_code',
        'address_country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'initial_consultation_reason',
        'first_appointment_date',
        'medical_history',
        'psychiatric_history',
        'current_medication',
        'referral_source',
        'data_protection_consent',
        'data_protection_consent_at',
        'notes',
        'tags',
        'profile_photo_path',
        'is_active',
        'archived_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'first_appointment_date' => 'date',
        'tags' => 'array',
        'is_active' => 'boolean',
        'data_protection_consent' => 'boolean',
        'archived_at' => 'datetime',
        'data_protection_consent_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];
    
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name='.urlencode($this->full_name).'&color=0EA5E9&background=E0F2FE';
    }

    /**
     * Get the professional that owns the contact
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Get all appointments for this contact
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get all clinical notes for this contact
     */
    public function clinicalNotes(): HasMany
    {
        return $this->hasMany(ClinicalNote::class);
    }

    /**
     * Get all consent forms for this contact
     */
    public function consentForms(): HasMany
    {
        return $this->hasMany(ConsentForm::class);
    }

    /**
     * Check if contact has a valid consent form of a specific type
     *
     * @param string $consentType
     * @return bool
     */
    public function hasValidConsent(string $consentType): bool
    {
        return $this->consentForms()
            ->where('consent_type', $consentType)
            ->where('is_valid', true)
            ->whereNotNull('signed_at')
            ->whereNull('revoked_at')
            ->exists();
    }

    /**
     * Get the most recent valid consent form of a specific type
     *
     * @param string $consentType
     * @return ConsentForm|null
     */
    public function getValidConsent(string $consentType): ?ConsentForm
    {
        return $this->consentForms()
            ->where('consent_type', $consentType)
            ->where('is_valid', true)
            ->whereNotNull('signed_at')
            ->whereNull('revoked_at')
            ->orderBy('signed_at', 'desc')
            ->first();
    }

    /**
     * Get the patient user (if this contact has portal access)
     */
    public function patientUser(): HasOne
    {
        return $this->hasOne(\App\Core\Users\Models\PatientUser::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get age from date of birth
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_street,
            $this->address_city,
            $this->address_postal_code,
            $this->address_country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Archive this contact
     */
    public function archive(): void
    {
        $this->update([
            'is_active' => false,
            'archived_at' => now(),
        ]);
    }

    /**
     * Unarchive this contact
     */
    public function unarchive(): void
    {
        $this->update([
            'is_active' => true,
            'archived_at' => null,
        ]);
    }

    /**
     * Scope to get only active contacts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search by name
     */
    public function scopeSearchByName($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        });
    }
    
    /**
     * Scope to filter by professional
     */
    public function scopeForProfessional($query, $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }
    
    /**
     * Scope to get archived contacts
     */
    public function scopeArchived($query)
    {
        return $query->where('is_active', false)
                     ->whereNotNull('archived_at');
    }
    
    /**
     * Scope to get recent contacts
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }
    
    /**
     * Scope to get contacts with upcoming appointments
     */
    public function scopeWithUpcomingAppointments($query)
    {
        return $query->whereHas('appointments', function($q) {
            $q->where('start_time', '>', now())
              ->whereIn('status', ['scheduled', 'confirmed']);
        });
    }
    
    /**
     * Scope to search by email or phone
     */
    public function scopeSearchByContact($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
    
    /**
     * Scope to filter by gender
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }
    
    /**
     * Scope to get contacts with data protection consent
     */
    public function scopeWithConsent($query)
    {
        return $query->where('data_protection_consent', true)
                     ->whereNotNull('data_protection_consent_at');
    }
    
    /**
     * Scope to get contacts without consent
     */
    public function scopeWithoutConsent($query)
    {
        return $query->where(function($q) {
            $q->where('data_protection_consent', false)
              ->orWhereNull('data_protection_consent_at');
        });
    }
    
    /**
     * Scope to order by name
     */
    public function scopeOrderByName($query, string $direction = 'asc')
    {
        return $query->orderBy('first_name', $direction)
                     ->orderBy('last_name', $direction);
    }
}

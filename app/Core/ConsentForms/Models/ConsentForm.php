<?php

namespace App\Core\ConsentForms\Models;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Traits\HasAuditLog;
use App\Shared\Traits\HasContact;
use App\Shared\Traits\HasProfessional;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ConsentForm Model
 *
 * Represents an informed consent form signed by a patient
 * for psychological treatment or specific procedures.
 */
class ConsentForm extends Model
{
    use HasFactory, SoftDeletes, HasProfessional, HasContact, HasAuditLog;

    /**
     * Consent type constants
     */
    public const TYPE_INITIAL_TREATMENT = 'initial_treatment';

    public const TYPE_TELECONSULTATION = 'teleconsultation';
    public const TYPE_MINORS = 'minors';
    public const TYPE_RECORDING = 'recording';
    public const TYPE_RESEARCH = 'research';
    public const TYPE_THIRD_PARTY_COMMUNICATION = 'third_party_communication';
    public const TYPE_MEDICATION_REFERRAL = 'medication_referral';
    public const TYPE_GROUP_THERAPY = 'group_therapy';
    public const TYPE_EMDR = 'emdr';
    public const TYPE_HYPNOSIS = 'hypnosis';
    public const TYPE_COUPLE_THERAPY = 'couple_therapy';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consent_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'professional_id',
        'contact_id',
        'consent_type',
        'consent_title',
        'consent_text',
        'additional_data',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'legal_guardian_name',
        'legal_guardian_relationship',
        'legal_guardian_id_document',
        'minor_assent',
        'minor_assent_details',
        'patient_signature_data',
        'patient_ip_address',
        'patient_device_info',
        'guardian_signature_data',
        'witness_name',
        'witness_signature_data',
        'signed_at',
        'delivered_at',
        'delivered_by',
        'is_valid',
        'revoked_at',
        'revocation_reason',
        'document_version',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'additional_data' => 'array',
        'minor_assent' => 'boolean',
        'is_valid' => 'boolean',
        'signed_at' => 'datetime',
        'delivered_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Get all available consent types
     *
     * @return array
     */
    public static function getConsentTypes(): array
    {
        return [
            self::TYPE_INITIAL_TREATMENT => 'Consentimiento Inicial de Tratamiento',
            self::TYPE_TELECONSULTATION => 'Teleconsulta',
            self::TYPE_MINORS => 'Consentimiento para Menores',
            self::TYPE_RECORDING => 'Grabación de Sesiones',
            self::TYPE_RESEARCH => 'Participación en Investigación',
            self::TYPE_THIRD_PARTY_COMMUNICATION => 'Comunicación con Terceros',
            self::TYPE_MEDICATION_REFERRAL => 'Derivación a Psiquiatría',
            self::TYPE_GROUP_THERAPY => 'Terapia de Grupo',
            self::TYPE_EMDR => 'EMDR (Desensibilización y Reprocesamiento)',
            self::TYPE_HYPNOSIS => 'Hipnosis Clínica',
            self::TYPE_COUPLE_THERAPY => 'Terapia de Pareja',
        ];
    }

    /**
     * Get the human-readable label for the consent type
     *
     * @return string
     */
    public function getConsentTypeLabelAttribute(): string
    {
        return self::getConsentTypes()[$this->consent_type] ?? $this->consent_type;
    }

    /**
     * Sign the consent form
     *
     * @param string|null $signatureData Base64 encoded signature
     * @param string|null $ipAddress IP address of the signer
     * @param string|null $deviceInfo Device information
     * @return void
     * @throws \Exception
     */
    public function sign(?string $signatureData = null, ?string $ipAddress = null, ?string $deviceInfo = null): void
    {
        if ($this->isSigned()) {
            throw new \Exception('Consent form is already signed');
        }

        if ($this->isRevoked()) {
            throw new \Exception('Cannot sign a revoked consent form');
        }

        $this->update([
            'patient_signature_data' => $signatureData,
            'patient_ip_address' => $ipAddress ?? request()->ip(),
            'patient_device_info' => $deviceInfo ?? request()->userAgent(),
            'signed_at' => now(),
            'is_valid' => true,
        ]);
    }

    /**
     * Sign as guardian (for minors)
     *
     * @param string|null $signatureData Base64 encoded signature
     * @param string|null $guardianName
     * @param string|null $guardianRelationship
     * @param string|null $guardianIdDocument
     * @return void
     * @throws \Exception
     */
    public function signAsGuardian(
        ?string $signatureData = null,
        ?string $guardianName = null,
        ?string $guardianRelationship = null,
        ?string $guardianIdDocument = null
    ): void {
        if ($this->isSigned()) {
            throw new \Exception('Consent form is already signed');
        }

        $this->update([
            'guardian_signature_data' => $signatureData,
            'legal_guardian_name' => $guardianName,
            'legal_guardian_relationship' => $guardianRelationship,
            'legal_guardian_id_document' => $guardianIdDocument,
            'signed_at' => now(),
            'is_valid' => true,
        ]);
    }

    /**
     * Revoke the consent form
     *
     * @param string|null $reason Reason for revocation
     * @return void
     * @throws \Exception
     */
    public function revoke(?string $reason = null): void
    {
        if ($this->isRevoked()) {
            throw new \Exception('Consent form is already revoked');
        }

        $this->update([
            'revoked_at' => now(),
            'revocation_reason' => $reason,
            'is_valid' => false,
        ]);
    }

    /**
     * Check if the consent form is signed
     *
     * @return bool
     */
    public function isSigned(): bool
    {
        return !is_null($this->signed_at);
    }

    /**
     * Check if the consent form is revoked
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return !is_null($this->revoked_at);
    }

    /**
     * Check if the consent form is pending (not signed)
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return !$this->isSigned() && !$this->isRevoked();
    }

    /**
     * Check if this is for a minor
     *
     * @return bool
     */
    public function isForMinor(): bool
    {
        return $this->consent_type === self::TYPE_MINORS || !is_null($this->legal_guardian_name);
    }

    /**
     * Scope to get only signed consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSigned($query)
    {
        return $query
            ->whereNotNull('signed_at')
            ->where('is_valid', true);
    }

    /**
     * Scope to get only pending consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query
            ->whereNull('signed_at')
            ->whereNull('revoked_at');
    }

    /**
     * Scope to get only revoked consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRevoked($query)
    {
        return $query->whereNotNull('revoked_at');
    }

    /**
     * Scope to get consent forms by type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('consent_type', $type);
    }

    /**
     * Scope to get valid (signed and not revoked) consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query
            ->where('is_valid', true)
            ->whereNull('revoked_at');
    }

    /**
     * Check if a contact has a valid consent of a specific type
     *
     * @param int $contactId
     * @param string $type
     * @return bool
     */
    public static function hasValidConsent(int $contactId, string $type): bool
    {
        return static::where('contact_id', $contactId)
            ->where('consent_type', $type)
            ->valid()
            ->exists();
    }

    /**
     * Get the latest valid consent form for a contact of a specific type
     *
     * @param int $contactId
     * @param string $type
     * @return self|null
     */
    public static function getLatestValidConsent(int $contactId, string $type): ?self
    {
        return static::where('contact_id', $contactId)
            ->where('consent_type', $type)
            ->valid()
            ->orderBy('signed_at', 'desc')
            ->first();
    }

    /**
     * Mark the consent form as delivered to the patient
     *
     * @return void
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'delivered_at' => now(),
            'delivered_by' => auth()->id(),
        ]);
    }

    /**
     * Check if the consent form has been delivered to the patient
     *
     * @return bool
     */
    public function isDelivered(): bool
    {
        return !is_null($this->delivered_at);
    }

    /**
     * Scope to get delivered consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDelivered($query)
    {
        return $query->whereNotNull('delivered_at');
    }

    /**
     * Scope to get pending delivery consent forms
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingDelivery($query)
    {
        return $query
            ->signed()
            ->whereNull('delivered_at');
    }
}

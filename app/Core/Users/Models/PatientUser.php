<?php

namespace App\Core\Users\Models;

use App\Core\Contacts\Models\Contact;
use App\Models\User;
use Database\Factories\PatientUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PatientUser Model
 * 
 * Represents the relationship between a User and a Contact (patient)
 * for the patient portal functionality.
 * 
 * This model connects:
 * - User (authentication)
 * - Contact (patient data)
 * - Professional (the professional managing this patient)
 */
class PatientUser extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return PatientUserFactory::new();
    }

    protected $fillable = [
        'user_id',
        'contact_id',
        'professional_id',
        'portal_activated_at',
        'email_notifications_enabled',
        'sms_notifications_enabled',
    ];

    protected $casts = [
        'portal_activated_at' => 'datetime',
        'email_notifications_enabled' => 'boolean',
        'sms_notifications_enabled' => 'boolean',
    ];

    /**
     * Get the user (authentication)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact (patient data)
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the professional managing this patient
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Check if portal is activated
     */
    public function isPortalActivated(): bool
    {
        return !is_null($this->portal_activated_at);
    }

    /**
     * Activate the patient portal
     */
    public function activatePortal(): void
    {
        $this->update([
            'portal_activated_at' => now(),
        ]);
    }

    /**
     * Deactivate the patient portal
     */
    public function deactivatePortal(): void
    {
        $this->update([
            'portal_activated_at' => null,
        ]);
    }
}


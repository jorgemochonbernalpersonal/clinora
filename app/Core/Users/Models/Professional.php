<?php

namespace App\Core\Users\Models;

use App\Shared\Enums\SubscriptionPlan;
use Database\Factories\ProfessionalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Professional extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    // Profession type constants
    public const PROFESSION_PSYCHOLOGIST = 'psychologist';
    public const PROFESSION_THERAPIST = 'therapist';
    public const PROFESSION_NUTRITIONIST = 'nutritionist';
    public const PROFESSION_PSYCHIATRIST = 'psychiatrist';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProfessionalFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'profession_type',

        'license_number',
        'profession',
        'specialties',

        'address_street',
        'address_city',
        'address_postal_code',
        'address_country',

        'invoice_series',
        'subscription_plan',
        'subscription_status',
        'is_early_adopter',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'specialties' => 'array',
        'subscription_plan' => SubscriptionPlan::class,
        'is_early_adopter' => 'boolean',
    ];

    /**
     * Get the user that owns the professional profile
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Check if professional has active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_status === 'active';
    }

    /**
     * Check if professional has a specific plan
     */
    public function hasPlan(SubscriptionPlan $plan): bool
    {
        return $this->subscription_plan === $plan;
    }

    /**
     * Check if professional is on free plan
     */
    public function isOnFreePlan(): bool
    {
        return $this->subscription_plan === SubscriptionPlan::GRATIS;
    }

    /**
     * Check if professional is on pro plan
     */
    public function isOnProPlan(): bool
    {
        return $this->subscription_plan === SubscriptionPlan::PRO;
    }

    /**
     * Check if professional is on team plan
     */
    public function isOnTeamPlan(): bool
    {
        return $this->subscription_plan === SubscriptionPlan::EQUIPO;
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
     * Get professional name (proxy to user)
     */
    public function getNameAttribute(): string
    {
        return $this->user->full_name ?? '';
    }

    /**
     * Get professional phone (proxy to user)
     */
    public function getPhoneAttribute(): ?string
    {
        return $this->user->phone;
    }

    /**
     * Get professional timezone (proxy to user)
     */
    public function getTimezoneAttribute(): string
    {
        return $this->user->timezone ?? 'Europe/Madrid';
    }

    /**
     * Get professional language (proxy to user)
     */
    public function getLanguageAttribute(): string
    {
        return $this->user->language ?? 'es';
    }

    /**
     * Get the profession route prefix based on profession type
     */
    public function getProfessionRoute(): string
    {
        return match($this->profession_type) {
            self::PROFESSION_PSYCHOLOGIST => 'psychologist',
            self::PROFESSION_THERAPIST => 'therapist',
            self::PROFESSION_NUTRITIONIST => 'nutritionist',
            self::PROFESSION_PSYCHIATRIST => 'psychiatrist',
            default => 'psychologist',
        };
    }

    /**
     * Check if professional is a psychologist
     */
    public function isPsychologist(): bool
    {
        return $this->profession_type === self::PROFESSION_PSYCHOLOGIST;
    }

    /**
     * Check if professional is a therapist
     */
    public function isTherapist(): bool
    {
        return $this->profession_type === self::PROFESSION_THERAPIST;
    }

    /**
     * Check if professional is a nutritionist
     */
    public function isNutritionist(): bool
    {
        return $this->profession_type === self::PROFESSION_NUTRITIONIST;
    }

    /**
     * Check if professional is a psychiatrist
     */
    public function isPsychiatrist(): bool
    {
        return $this->profession_type === self::PROFESSION_PSYCHIATRIST;
    }

    /**
     * Check if professional is an early adopter (registered during beta)
     */
    public function isEarlyAdopter(): bool
    {
        return $this->is_early_adopter === true;
    }

    /**
     * Get the patient limit for this professional
     * Early adopters on free plan get 5 patients instead of 3
     */
    public function getPatientLimit(): ?int
    {
        if ($this->isOnFreePlan()) {
            return $this->isEarlyAdopter() ? 5 : 3;
        }
        return null; // Unlimited for Pro and Equipo
    }

    /**
     * Get the price per patient for this professional's plan
     * Early adopters get 25% discount on Pro and Equipo plans
     */
    public function getPricePerPatient(): float
    {
        if ($this->isOnFreePlan()) {
            return 0.0;
        }

        $basePrice = $this->isOnProPlan() ? 1.0 : 2.0;
        
        // Apply 25% discount for early adopters
        if ($this->isEarlyAdopter()) {
            $basePrice *= 0.75;
        }

        return $basePrice;
    }
}

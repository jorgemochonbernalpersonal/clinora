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
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'specialties' => 'array',
        'subscription_plan' => SubscriptionPlan::class,
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
}

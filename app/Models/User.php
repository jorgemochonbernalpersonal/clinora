<?php

namespace App\Models;

use App\Core\Authentication\Notifications\VerifyEmailNotification;
use App\Core\Users\Models\Professional;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'avatar_path',
        'user_type',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'password_changed_at',
        'language',
        'timezone',
        'theme',
        'notifications_enabled',
        'email_notifications',
        'sms_notifications',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'notifications_enabled' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the professional profile
     */
    public function professional(): HasOne
    {
        return $this->hasOne(Professional::class);
    }

    /**
     * Get the patient user profile (for patient portal)
     */
    public function patientUser(): HasOne
    {
        return $this->hasOne(\App\Core\Users\Models\PatientUser::class);
    }

    /**
     * Check if user is a professional
     */
    public function isProfessional(): bool
    {
        return $this->user_type === 'professional';
    }

    /**
     * Check if user is a patient
     */
    public function isPatient(): bool
    {
        return $this->user_type === 'patient';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Send the email verification notification (custom)
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Get avatar URL or placeholder
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar_path);
        }
        
        // Generate placeholder with initials
        return $this->getAvatarPlaceholderUrl();
    }

    /**
     * Get avatar placeholder URL (using UI Avatars service)
     */
    public function getAvatarPlaceholderUrl(): string
    {
        $name = urlencode($this->first_name . ' ' . $this->last_name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=0D8ABC&color=fff";
    }

    /**
     * Get initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $firstInitial = $this->first_name ? strtoupper(substr($this->first_name, 0, 1)) : '';
        $lastInitial = $this->last_name ? strtoupper(substr($this->last_name, 0, 1)) : '';
        return $firstInitial . $lastInitial;
    }

    /**
     * Set metadata value
     */
    public function setMetadata(string $key, $value): void
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->update(['metadata' => $metadata]);
    }

    /**
     * Get metadata value
     */
    public function getMetadata(string $key, $default = null)
    {
        $metadata = $this->metadata ?? [];
        return $metadata[$key] ?? $default;
    }

    /**
     * Check if metadata key exists
     */
    public function hasMetadata(string $key): bool
    {
        $metadata = $this->metadata ?? [];
        return isset($metadata[$key]);
    }

    /**
     * Check if user has verified email
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
}

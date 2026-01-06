<?php

namespace App\Models;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Clinical Note Access Log
 * 
 * Tracks all accesses to clinical notes for GDPR compliance
 * and security auditing (Art. 32 RGPD)
 */
class ClinicalNoteAccessLog extends Model
{
    protected $fillable = [
        'clinical_note_id',
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'is_authorized',
        'authorization_note',
    ];

    protected $casts = [
        'is_authorized' => 'boolean',
    ];

    /**
     * Get the clinical note that was accessed
     */
    public function clinicalNote(): BelongsTo
    {
        return $this->belongsTo(ClinicalNote::class);
    }

    /**
     * Get the user who accessed the note
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an access to a clinical note
     */
    public static function logAccess(
        int $clinicalNoteId,
        int $userId,
        string $action = 'view',
        bool $isAuthorized = true,
        ?string $authorizationNote = null
    ): self {
        return static::create([
            'clinical_note_id' => $clinicalNoteId,
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_method' => request()->method(),
            'request_url' => request()->fullUrl(),
            'is_authorized' => $isAuthorized,
            'authorization_note' => $authorizationNote,
        ]);
    }

    /**
     * Scope to get unauthorized access attempts
     */
    public function scopeUnauthorized($query)
    {
        return $query->where('is_authorized', false);
    }

    /**
     * Scope to get recent accesses
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get accesses by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}

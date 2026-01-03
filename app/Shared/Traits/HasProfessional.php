<?php

namespace App\Shared\Traits;

use App\Core\Users\Models\Professional;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HasProfessional Trait
 * 
 * Use this trait for models that belong to a Professional
 * (e.g., Contacts, Appointments, Invoices, etc.)
 */
trait HasProfessional
{
    /**
     * Get the professional that owns the record
     *
     * @return BelongsTo
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    /**
     * Scope query to a specific professional
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $professionalId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProfessional($query, string $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }
}

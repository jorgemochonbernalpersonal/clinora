<?php

namespace App\Shared\Traits;

use App\Core\Contacts\Models\Contact;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HasContact Trait
 * 
 * Use this trait for models that belong to a Contact/Patient
 * (e.g., Appointments, Invoices, Clinical Notes, etc.)
 */
trait HasContact
{
    /**
     * Get the contact that owns the record
     *
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Scope query to a specific contact
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $contactId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForContact($query, string $contactId)
    {
        return $query->where('contact_id', $contactId);
    }
}

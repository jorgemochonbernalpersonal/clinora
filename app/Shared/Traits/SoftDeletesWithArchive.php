<?php

namespace App\Shared\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * SoftDeletesWithArchive Trait
 * 
 * Extends SoftDeletes to add archive functionality
 * Allows marking records as archived without fully deleting them
 */
trait SoftDeletesWithArchive
{
    use SoftDeletes;

    /**
     * Archive the model instance
     *
     * @return bool
     */
    public function archive(): bool
    {
        $this->archived_at = now();
        
        if (auth()->check()) {
            $this->archived_by = auth()->id();
        }

        return $this->save();
    }

    /**
     * Restore an archived model instance
     *
     * @return bool
     */
    public function unarchive(): bool
    {
        $this->archived_at = null;
        $this->archived_by = null;

        return $this->save();
    }

    /**
     * Determine if the model is archived
     *
     * @return bool
     */
    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    /**
     * Scope query to only archived records
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope query to exclude archived records
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutArchived($query)
    {
        return $query->whereNull('archived_at');
    }
}

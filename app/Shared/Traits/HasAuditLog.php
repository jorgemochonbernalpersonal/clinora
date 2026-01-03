<?php

namespace App\Shared\Traits;

/**
 * HasAuditLog Trait
 * 
 * Automatically track created_by, updated_by, and deleted_by
 * for models that require audit logging
 */
trait HasAuditLog
{
    /**
     * Boot the trait and register event listeners
     */
    protected static function bootHasAuditLog(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check() && method_exists($model, 'runSoftDelete')) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }
}

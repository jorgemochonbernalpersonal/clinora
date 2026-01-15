<?php

namespace App\Core\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'description',
        'notes',
        'quantity',
        'unit_price',
        'tax_rate',
        'subtotal',
        'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the invoice
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Calculate subtotal (quantity * unit_price)
     */
    public function calculateSubtotal(): float
    {
        $this->subtotal = round($this->quantity * $this->unit_price, 2);
        return $this->subtotal;
    }

    /**
     * Calculate total (subtotal + tax, but for psychologists tax is 0)
     */
    public function calculateTotal(): float
    {
        $this->calculateSubtotal();
        // For psychologists, tax is always 0 (IVA exempt)
        $this->total = $this->subtotal;
        return $this->total;
    }

    /**
     * Boot method to auto-calculate totals
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->calculateTotal();
        });
    }
}

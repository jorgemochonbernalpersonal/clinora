<?php

namespace App\Shared\Enums;

/**
 * Invoice Status Enum
 */
enum InvoiceStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case PARTIALLY_PAID = 'partially_paid';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::SENT => 'Enviada',
            self::PAID => 'Pagada',
            self::OVERDUE => 'Vencida',
            self::CANCELLED => 'Cancelada',
            self::REFUNDED => 'Reembolsada',
            self::PARTIALLY_PAID => 'Parcialmente Pagada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::SENT => 'blue',
            self::PAID => 'green',
            self::OVERDUE => 'red',
            self::CANCELLED => 'gray',
            self::REFUNDED => 'orange',
            self::PARTIALLY_PAID => 'yellow',
        };
    }
}

<?php

namespace App\Shared\Exceptions;

use Exception;

/**
 * Payment Failed Exception
 * 
 * Thrown when a payment processing fails
 */
class PaymentFailedException extends Exception
{
    public function __construct(string $message = 'Error al procesar el pago', ?\Throwable $previous = null)
    {
        parent::__construct($message, 402, $previous);
    }
}

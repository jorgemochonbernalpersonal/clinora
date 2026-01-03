<?php

namespace App\Shared\Exceptions;

use Exception;

/**
 * Appointment Conflict Exception
 * 
 * Thrown when an appointment conflicts with another
 */
class AppointmentConflictException extends Exception
{
    public function __construct(string $message = 'Conflicto de horario: el profesional ya tiene una cita programada en este horario')
    {
        parent::__construct($message, 409);
    }
}

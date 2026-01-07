<?php

namespace App\Shared\Enums;

/**
 * Appointment Status Enum
 */
enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case CONFIRMED = 'confirmed';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case NO_SHOW = 'no_show';

    /**
     * Get a human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::SCHEDULED => 'Programada',
            self::CONFIRMED => 'Confirmada',
            self::IN_PROGRESS => 'En Progreso',
            self::COMPLETED => 'Completada',
            self::CANCELLED => 'Cancelada',
            self::NO_SHOW => 'No Asistió',
        };
    }

    /**
     * Get the color for UI display
     */
    public function color(): string
    {
        return match($this) {
            self::SCHEDULED => 'blue',
            self::CONFIRMED => 'green',
            self::IN_PROGRESS => 'yellow',
            self::COMPLETED => 'gray',
            self::CANCELLED => 'red',
            self::NO_SHOW => 'orange',
        };
    }
}

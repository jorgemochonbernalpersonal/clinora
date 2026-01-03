<?php

namespace App\Shared\Enums;

/**
 * Appointment Type Enum
 */
enum AppointmentType: string
{
    case IN_PERSON = 'in_person';
    case ONLINE = 'online';
    case HOME_VISIT = 'home_visit';
    case PHONE = 'phone';

    public function label(): string
    {
        return match($this) {
            self::IN_PERSON => 'Presencial',
            self::ONLINE => 'Online',
            self::HOME_VISIT => 'Domicilio',
            self::PHONE => 'Telefónica',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::IN_PERSON => 'building',
            self::ONLINE => 'video',
            self::HOME_VISIT => 'home',
            self::PHONE => 'phone',
        };
    }
}

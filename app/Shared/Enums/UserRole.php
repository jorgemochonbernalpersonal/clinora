<?php

namespace App\Shared\Enums;

/**
 * User Role Enum
 * 
 * Defines all possible user roles in the system
 */
enum UserRole: string
{
    case ADMIN = 'admin';
    case PROFESSIONAL = 'professional';
    case PATIENT = 'patient';
    case SUPPORT = 'support';

    /**
     * Get a human-readable label for the role
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::PROFESSIONAL => 'Profesional',
            self::PATIENT => 'Paciente',
            self::SUPPORT => 'Soporte',
        };
    }

    /**
     * Get all available roles
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Shared\Enums;

enum SubscriptionPlan: string
{
    case GRATIS = 'gratis';
    case PRO = 'pro';
    case EQUIPO = 'equipo';

    /**
     * Get all plan values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get plan display name
     */
    public function label(): string
    {
        return match($this) {
            self::GRATIS => 'Gratis',
            self::PRO => 'Pro',
            self::EQUIPO => 'Equipo',
        };
    }

    /**
     * Get default plan
     */
    public static function default(): self
    {
        return self::GRATIS;
    }
}


<?php

namespace App\Shared\Enums;

/**
 * Profession Type Enum
 * 
 * Defines all available profession types in the system.
 * Each profession can have its own module with specific features.
 */
enum ProfessionType: string
{
    case PSYCHOLOGIST = 'psychologist';
    case THERAPIST = 'therapist';
    case NUTRITIONIST = 'nutritionist';
    case PSYCHIATRIST = 'psychiatrist';
    case PHYSIOTHERAPIST = 'physiotherapist';
    case DIETITIAN = 'dietitian';
    
    /**
     * Get human-readable label for this profession
     */
    public function label(): string
    {
        return match($this) {
            self::PSYCHOLOGIST => 'Psicólogo/a',
            self::THERAPIST => 'Terapeuta',
            self::NUTRITIONIST => 'Nutricionista',
            self::PSYCHIATRIST => 'Psiquiatra',
            self::PHYSIOTHERAPIST => 'Fisioterapeuta',
            self::DIETITIAN => 'Dietista',
        };
    }
    
    /**
     * Get route prefix for this profession
     */
    public function routePrefix(): string
    {
        return $this->value;
    }
    
    /**
     * Get module class for this profession
     * Returns null if the profession doesn't have a dedicated module yet
     */
    public function moduleClass(): ?string
    {
        return match($this) {
            self::PSYCHOLOGIST => \App\Modules\Psychology\PsychologyModule::class,
            // Add new modules here as they are created
            // self::NUTRITIONIST => \App\Modules\Nutrition\NutritionModule::class,
            default => null,
        };
    }
    
    /**
     * Check if this profession has a dedicated module
     */
    public function hasModule(): bool
    {
        return $this->moduleClass() !== null;
    }
    
    /**
     * Get all professions that have modules available
     */
    public static function available(): array
    {
        return array_filter(
            self::cases(),
            fn(self $type) => $type->hasModule()
        );
    }
    
    /**
     * Get all profession types as array of values
     */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
    
    /**
     * Get all profession types as array of labels
     */
    public static function labels(): array
    {
        return array_map(fn(self $case) => $case->label(), self::cases());
    }
}

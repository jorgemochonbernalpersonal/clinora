<?php

namespace App\Shared\Helpers;

use App\Shared\Services\ModuleRegistry;
use App\Shared\Interfaces\ModuleInterface;

/**
 * Module Helper
 * 
 * Helper functions for working with profession modules
 */
class ModuleHelper
{
    /**
     * Get the current user's profession module
     */
    public static function getCurrentModule(): ?ModuleInterface
    {
        $user = auth()->user();
        
        if (!$user || !$user->professional) {
            return null;
        }

        $registry = app(ModuleRegistry::class);
        
        return $registry->getModuleForProfessional($user->professional);
    }

    /**
     * Get module for a profession type
     */
    public static function getModule(string $professionType): ?ModuleInterface
    {
        $registry = app(ModuleRegistry::class);
        
        return $registry->getModule($professionType);
    }

    /**
     * Check if current user has a specific profession type
     */
    public static function hasProfession(string $professionType): bool
    {
        $user = auth()->user();
        
        return $user 
            && $user->professional 
            && $user->professional->profession_type === $professionType;
    }

    /**
     * Get profession route prefix
     */
    public static function getRoutePrefix(): ?string
    {
        $module = self::getCurrentModule();
        
        if (!$module) {
            return null;
        }

        return $module->getProfessionType();
    }
}


<?php

namespace App\Shared\Services;

use App\Shared\Interfaces\ModuleInterface;
use Illuminate\Support\Collection;

/**
 * Module Registry
 * 
 * Central registry for managing profession-specific modules
 */
class ModuleRegistry
{
    private array $modules = [];

    /**
     * Register a module
     */
    public function register(ModuleInterface $module): void
    {
        $this->modules[$module->getProfessionType()] = $module;
    }

    /**
     * Get a module by profession type
     */
    public function getModule(string $professionType): ?ModuleInterface
    {
        return $this->modules[$professionType] ?? null;
    }

    /**
     * Get all registered modules
     */
    public function getAllModules(): Collection
    {
        return collect($this->modules);
    }

    /**
     * Get all enabled modules
     */
    public function getEnabledModules(): Collection
    {
        return $this->getAllModules()->filter(fn(ModuleInterface $module) => $module->isEnabled());
    }

    /**
     * Check if a profession type has a registered module
     */
    public function hasModule(string $professionType): bool
    {
        return isset($this->modules[$professionType]);
    }

    /**
     * Get module for a professional
     */
    public function getModuleForProfessional($professional): ?ModuleInterface
    {
        if (!$professional || !isset($professional->profession_type)) {
            return null;
        }

        return $this->getModule($professional->profession_type);
    }
}


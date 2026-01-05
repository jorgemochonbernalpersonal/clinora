<?php

namespace App\Shared\Interfaces;

/**
 * Module Interface
 * 
 * Defines the contract that all profession-specific modules must implement
 */
interface ModuleInterface
{
    /**
     * Get the module name
     */
    public function getName(): string;

    /**
     * Get the profession type this module handles
     */
    public function getProfessionType(): string;

    /**
     * Get the human-readable label for this profession
     */
    public function getLabel(): string;

    /**
     * Get web routes file path
     */
    public function getWebRoutesPath(): ?string;

    /**
     * Get API routes file path
     */
    public function getApiRoutesPath(): ?string;

    /**
     * Get migrations directory path
     */
    public function getMigrationsPath(): ?string;

    /**
     * Get views namespace
     */
    public function getViewsNamespace(): ?string;

    /**
     * Check if module is enabled
     */
    public function isEnabled(): bool;

    /**
     * Get service provider class name
     */
    public function getServiceProviderClass(): string;
}


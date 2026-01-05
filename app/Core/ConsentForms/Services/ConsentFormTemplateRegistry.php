<?php

namespace App\Core\ConsentForms\Services;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use InvalidArgumentException;

/**
 * Registry for consent form templates by module and type
 * 
 * Allows modules to register their own templates for different consent types.
 */
class ConsentFormTemplateRegistry
{
    /**
     * Registered templates: [module => [type => TemplateClass]]
     *
     * @var array<string, array<string, string>>
     */
    private static array $templates = [];

    /**
     * Register templates for a module
     *
     * @param string $module Module name (e.g., 'psychology', 'nutrition')
     * @param array<string, string> $templates Array of [consent_type => TemplateClass]
     * @return void
     */
    public static function register(string $module, array $templates): void
    {
        foreach ($templates as $type => $templateClass) {
            if (!is_subclass_of($templateClass, ConsentFormTemplateInterface::class)) {
                throw new InvalidArgumentException(
                    "Template class {$templateClass} must implement ConsentFormTemplateInterface"
                );
            }
        }

        self::$templates[$module] = array_merge(
            self::$templates[$module] ?? [],
            $templates
        );
    }

    /**
     * Get template for a specific module and consent type
     *
     * @param string $module Module name
     * @param string $consentType Consent type (e.g., 'initial_treatment')
     * @return ConsentFormTemplateInterface
     * @throws InvalidArgumentException If template not found
     */
    public static function get(string $module, string $consentType): ConsentFormTemplateInterface
    {
        if (!isset(self::$templates[$module][$consentType])) {
            throw new InvalidArgumentException(
                "No template registered for module '{$module}' and type '{$consentType}'"
            );
        }

        $templateClass = self::$templates[$module][$consentType];
        return app($templateClass);
    }

    /**
     * Check if a template exists for module and type
     *
     * @param string $module Module name
     * @param string $consentType Consent type
     * @return bool
     */
    public static function has(string $module, string $consentType): bool
    {
        return isset(self::$templates[$module][$consentType]);
    }

    /**
     * Get all registered templates for a module
     *
     * @param string $module Module name
     * @return array<string, string> Array of [type => TemplateClass]
     */
    public static function getModuleTemplates(string $module): array
    {
        return self::$templates[$module] ?? [];
    }

    /**
     * Get all registered modules
     *
     * @return array<string> List of module names
     */
    public static function getModules(): array
    {
        return array_keys(self::$templates);
    }

    /**
     * Clear all registered templates (useful for testing)
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$templates = [];
    }
}


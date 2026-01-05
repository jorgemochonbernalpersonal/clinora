<?php

namespace App\Core\ConsentForms\Contracts;

use App\Core\Users\Models\Professional;

/**
 * Interface for consent form templates
 * 
 * Each module (Psychology, Nutrition, etc.) can implement this interface
 * to provide profession-specific consent form templates.
 */
interface ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content/template
     *
     * @param array $data Data for the consent form
     * @param Professional $professional The professional creating the consent
     * @return string The generated consent form content (HTML/text)
     */
    public function generate(array $data, Professional $professional): string;

    /**
     * Get required fields for this consent type
     *
     * @return array List of required field names
     */
    public function getRequiredFields(): array;

    /**
     * Validate data before generating template
     *
     * @param array $data Data to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $data): bool;

    /**
     * Get additional fields specific to this template
     *
     * @return array Additional field definitions
     */
    public function getAdditionalFields(): array;
}


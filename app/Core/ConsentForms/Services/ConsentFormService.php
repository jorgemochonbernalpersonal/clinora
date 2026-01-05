<?php

namespace App\Core\ConsentForms\Services;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Services\ConsentFormTemplateRegistry;
use App\Core\Users\Models\Professional;
use App\Core\Contacts\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ConsentFormService
{
    /**
     * Create a new consent form
     *
     * @param array $data
     * @param int $createdBy
     * @return ConsentForm
     * @throws InvalidArgumentException
     */
    public function create(array $data, int $createdBy): ConsentForm
    {
        $professional = Professional::findOrFail($data['professional_id']);
        $contact = Contact::findOrFail($data['contact_id']);
        
        // Get module from professional (map profession_type to module name)
        $module = $this->getModuleNameFromProfessionType($professional->profession_type ?? 'psychologist');
        $consentType = $data['consent_type'] ?? ConsentForm::TYPE_INITIAL_TREATMENT;

        // Get template from registry
        if (!ConsentFormTemplateRegistry::has($module, $consentType)) {
            throw new InvalidArgumentException(
                "No template available for module '{$module}' and consent type '{$consentType}'"
            );
        }

        $template = ConsentFormTemplateRegistry::get($module, $consentType);

        // Validate data
        if (!$template->validate($data)) {
            throw new InvalidArgumentException('Invalid data for consent form');
        }

        // Add contact to data for template generation
        $data['contact'] = $contact;

        // Store additional data before generating consent text
        $additionalDataFields = [
            'treatment_duration',
            'session_frequency',
            'session_duration',
            'treatment_techniques',
            'platform',
            'security_info',
            'recording_consent',
            'treatment_goals',
        ];
        
        $additionalData = [];
        foreach ($additionalDataFields as $field) {
            if (isset($data[$field])) {
                $additionalData[$field] = $data[$field];
            }
        }
        
        // Store additional data
        $data['additional_data'] = !empty($additionalData) ? $additionalData : null;

        // Generate consent text using template
        $data['consent_text'] = $template->generate($data, $professional);

        // Set default title if not provided
        if (!isset($data['consent_title'])) {
            $data['consent_title'] = ConsentForm::getConsentTypes()[$consentType] ?? 'Consentimiento';
        }

        // Add audit fields
        $data['created_by'] = $createdBy;
        $data['document_version'] = '1.0';
        
        // Remove contact from data before saving (it's a relation, not a column)
        unset($data['contact']);

        try {
            return DB::transaction(function () use ($data) {
                return ConsentForm::create($data);
            });
        } catch (\Exception $e) {
            Log::error('Error creating consent form', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw $e;
        }
    }

    /**
     * Get available consent types for a professional
     *
     * @param Professional $professional
     * @return array
     */
    public function getAvailableTypes(Professional $professional): array
    {
        $module = $this->getModuleNameFromProfessionType($professional->profession_type ?? 'psychologist');
        $templates = ConsentFormTemplateRegistry::getModuleTemplates($module);

        $availableTypes = [];
        foreach ($templates as $type => $templateClass) {
            $availableTypes[$type] = ConsentForm::getConsentTypes()[$type] ?? $type;
        }

        return $availableTypes;
    }

    /**
     * Map profession_type to module name
     * 
     * @param string $professionType
     * @return string
     */
    private function getModuleNameFromProfessionType(string $professionType): string
    {
        return match($professionType) {
            'psychologist' => 'psychology',
            'therapist' => 'therapy',
            'nutritionist' => 'nutrition',
            'psychiatrist' => 'psychiatry',
            default => 'psychology', // Default fallback
        };
    }
}


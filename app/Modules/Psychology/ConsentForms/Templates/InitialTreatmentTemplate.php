<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Initial Treatment Consent Form Template for Psychology
 */
class InitialTreatmentTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';

        return view('modules.psychology.consent-forms.initial-treatment', [
            'professional' => $professional,
            'contact' => $contact,
            'contactName' => $contactName,
            'data' => $data,
        ])->render();
    }

    /**
     * Get required fields
     */
    public function getRequiredFields(): array
    {
        return [
            'professional_id',
            'contact_id',
            'consent_type',
        ];
    }

    /**
     * Validate data
     */
    public function validate(array $data): bool
    {
        $required = $this->getRequiredFields();
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get additional fields
     */
    public function getAdditionalFields(): array
    {
        return [
            'treatment_duration' => [
                'type' => 'string',
                'label' => 'Duración estimada del tratamiento',
                'required' => false,
            ],
            'session_frequency' => [
                'type' => 'string',
                'label' => 'Frecuencia de sesiones',
                'required' => false,
            ],
            'session_duration' => [
                'type' => 'integer',
                'label' => 'Duración de cada sesión (minutos)',
                'required' => false,
            ],
            'treatment_techniques' => [
                'type' => 'array',
                'label' => 'Técnicas que se utilizarán',
                'required' => false,
            ],
        ];
    }
}


<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Group Therapy Consent Form Template for Psychology
 */
class GroupTherapyTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';

        return view('modules.psychology.consent-forms.group-therapy', [
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
            'group_size' => [
                'type' => 'integer',
                'label' => 'Tamaño del grupo',
                'required' => false,
            ],
            'session_duration' => [
                'type' => 'integer',
                'label' => 'Duración de cada sesión (minutos)',
                'required' => false,
            ],
            'group_theme' => [
                'type' => 'string',
                'label' => 'Temática del grupo',
                'required' => false,
            ],
        ];
    }
}

<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Teleconsultation Consent Form Template for Psychology
 */
class TeleconsultationTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';

        return view('modules.psychology.consent-forms.teleconsultation', [
            'professional' => $professional,
            'contact' => $contact,
            'contactName' => $contactName,
            'data' => $data,
            'platform' => $data['platform'] ?? 'Clinora',
            'security_info' => $data['security_info'] ?? 'Cifrado end-to-end',
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
            'platform',
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
            'platform' => [
                'type' => 'string',
                'label' => 'Plataforma de videollamada',
                'required' => true,
            ],
            'security_info' => [
                'type' => 'string',
                'label' => 'Información de seguridad',
                'required' => false,
            ],
            'recording_consent' => [
                'type' => 'boolean',
                'label' => 'Consentimiento para grabación',
                'required' => false,
            ],
        ];
    }
}


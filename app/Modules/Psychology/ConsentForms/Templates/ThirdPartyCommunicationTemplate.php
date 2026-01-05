<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Third Party Communication Consent Form Template for Psychology
 * 
 * Used when the patient consents to communication with other professionals or third parties
 */
class ThirdPartyCommunicationTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';
        
        $thirdPartyName = $data['third_party_name'] ?? '';
        $thirdPartyType = $data['third_party_type'] ?? 'profesional sanitario';
        $communicationPurpose = $data['communication_purpose'] ?? 'Coordinación del tratamiento';
        $informationToShare = $data['information_to_share'] ?? 'Información clínica relevante para el tratamiento';
        $communicationMethod = $data['communication_method'] ?? 'Escrita y/o telefónica';
        $duration = $data['duration'] ?? 'Durante el tiempo que dure el tratamiento';

        $date = now()->format('d/m/Y');
        $professionalName = "{$professional->user->first_name} {$professional->user->last_name}";
        $licenseInfo = $professional->license_number ? "<p><strong>Número de Colegiado:</strong> {$professional->license_number}</p>" : '';
        
        $html = <<<HTML
<div class="consent-form third-party-communication">
    <h2>Consentimiento para Comunicación con Terceros</h2>
    
    <div class="consent-section">
        <h3>1. Información del Paciente</h3>
        <p><strong>Nombre:</strong> {$contactName}</p>
        <p><strong>Fecha:</strong> {$date}</p>
    </div>

    <div class="consent-section">
        <h3>2. Información del Profesional</h3>
        <p><strong>Psicólogo/a:</strong> {$professionalName}</p>
        {$licenseInfo}
    </div>

    <div class="consent-section">
        <h3>3. Tercero Autorizado</h3>
        <p><strong>Tipo de tercero:</strong> {$thirdPartyType}</p>
HTML;
        
        if ($thirdPartyName) {
            $html .= <<<HTML
        <p><strong>Nombre:</strong> {$thirdPartyName}</p>
HTML;
        }
        
        $html .= <<<HTML
    </div>

    <div class="consent-section">
        <h3>4. Propósito de la Comunicación</h3>
        <p>{$communicationPurpose}</p>
    </div>

    <div class="consent-section">
        <h3>5. Información que se Compartirá</h3>
        <p>{$informationToShare}</p>
        <p><strong>Nota:</strong> Solo se compartirá la información estrictamente necesaria para el propósito acordado.</p>
    </div>

    <div class="consent-section">
        <h3>6. Método de Comunicación</h3>
        <p>{$communicationMethod}</p>
    </div>

    <div class="consent-section">
        <h3>7. Duración del Consentimiento</h3>
        <p>{$duration}</p>
        <p><strong>Importante:</strong> Puede revocar este consentimiento en cualquier momento, lo que no afectará la legalidad del tratamiento basado en el consentimiento antes de su revocación.</p>
    </div>

    <div class="consent-section">
        <h3>8. Confidencialidad</h3>
        <p>El tercero autorizado está obligado a mantener la confidencialidad de la información recibida y solo podrá utilizarla para los fines acordados en este consentimiento.</p>
    </div>

    <div class="consent-section">
        <h3>9. Derechos del Paciente</h3>
        <p>Como paciente, tiene derecho a:</p>
        <ul>
            <li>Conocer qué información se compartirá</li>
            <li>Revocar este consentimiento en cualquier momento</li>
            <li>Acceder a la información compartida (según normativa vigente)</li>
            <li>Ser informado de cualquier comunicación realizada</li>
        </ul>
    </div>

    <div class="consent-section">
        <h3>10. Consentimiento</h3>
        <p>He leído y comprendido la información proporcionada. Consiento en:</p>
        <ul>
            <li>La comunicación con el tercero especificado</li>
            <li>El compartir de la información descrita</li>
            <li>Los métodos de comunicación indicados</li>
        </ul>
        <p>Entiendo que puedo revocar este consentimiento en cualquier momento.</p>
    </div>

    <div class="consent-section signature">
        <p><strong>Firma del Paciente:</strong> _________________________</p>
        <p><strong>Fecha:</strong> _________________________</p>
    </div>
</div>
HTML;

        return $html;
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
            'third_party_name' => [
                'type' => 'string',
                'label' => 'Nombre del tercero (opcional)',
                'required' => false,
            ],
            'third_party_type' => [
                'type' => 'string',
                'label' => 'Tipo de tercero',
                'required' => false,
            ],
            'communication_purpose' => [
                'type' => 'string',
                'label' => 'Propósito de la comunicación',
                'required' => false,
            ],
            'information_to_share' => [
                'type' => 'string',
                'label' => 'Información que se compartirá',
                'required' => false,
            ],
            'communication_method' => [
                'type' => 'string',
                'label' => 'Método de comunicación',
                'required' => false,
            ],
            'duration' => [
                'type' => 'string',
                'label' => 'Duración del consentimiento',
                'required' => false,
            ],
        ];
    }
}


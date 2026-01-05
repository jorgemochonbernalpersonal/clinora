<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Medication Referral Consent Form Template for Psychology
 * 
 * Used when referring a patient to psychiatry for medication evaluation
 */
class MedicationReferralTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';
        
        $referralReason = $data['referral_reason'] ?? 'Evaluación para posible tratamiento farmacológico';
        $psychiatristName = $data['psychiatrist_name'] ?? '';
        $psychiatristLicense = $data['psychiatrist_license'] ?? '';
        $expectedOutcome = $data['expected_outcome'] ?? 'Evaluación y, si es necesario, prescripción de medicación psicotrópica';

        $date = now()->format('d/m/Y');
        $professionalName = "{$professional->user->first_name} {$professional->user->last_name}";
        $licenseInfo = $professional->license_number ? "<p><strong>Número de Colegiado:</strong> {$professional->license_number}</p>" : '';
        
        $html = <<<HTML
<div class="consent-form medication-referral">
    <h2>Consentimiento Informado para Derivación a Psiquiatría</h2>
    
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
        <h3>3. Motivo de la Derivación</h3>
        <p>{$referralReason}</p>
    </div>

    <div class="consent-section">
        <h3>4. Información sobre la Derivación</h3>
        <p>Le informo que, tras la evaluación psicológica realizada, considero apropiado derivarle a un/a psiquiatra para:</p>
        <ul>
            <li>Evaluación médica especializada</li>
            <li>Valoración de la necesidad de tratamiento farmacológico</li>
            <li>Coordinación del tratamiento psicológico con tratamiento médico si fuera necesario</li>
        </ul>
    </div>

HTML;
        
        $sectionNumber = 5;
        $psychiatristSection = '';
        if ($psychiatristName) {
            $psychiatristInfo = $psychiatristLicense ? "<p><strong>Número de Colegiado:</strong> {$psychiatristLicense}</p>" : '';
            $psychiatristSection = <<<HTML

    <div class="consent-section">
        <h3>{$sectionNumber}. Psiquiatra Recomendado</h3>
        <p><strong>Nombre:</strong> {$psychiatristName}</p>
        {$psychiatristInfo}
    </div>
HTML;
            $sectionNumber++;
        }
        
        $section6 = $sectionNumber;
        $section7 = $sectionNumber + 1;
        $section8 = $sectionNumber + 2;
        $section9 = $sectionNumber + 3;
        
        $html .= $psychiatristSection;
        $html .= <<<HTML

    <div class="consent-section">
        <h3>{$section6}. Proceso de Derivación</h3>
        <p>El proceso de derivación incluirá:</p>
        <ul>
            <li>Comunicación entre profesionales (con su consentimiento)</li>
            <li>Compartir información clínica relevante para la evaluación</li>
            <li>Coordinación del tratamiento conjunto si procede</li>
        </ul>
    </div>

    <div class="consent-section">
        <h3>{$section7}. Confidencialidad</h3>
        <p>La información compartida entre profesionales se mantendrá bajo estricta confidencialidad y solo se utilizará para los fines terapéuticos acordados.</p>
    </div>

    <div class="consent-section">
        <h3>{$section8}. Continuidad del Tratamiento Psicológico</h3>
        <p>La derivación a psiquiatría no implica la finalización del tratamiento psicológico. Ambos tratamientos pueden complementarse según las necesidades del paciente.</p>
    </div>

    <div class="consent-section">
        <h3>{$section9}. Consentimiento</h3>
        <p>He leído y comprendido la información proporcionada sobre la derivación a psiquiatría. Consiento en:</p>
        <ul>
            <li>Ser derivado/a a evaluación psiquiátrica</li>
            <li>Que se comparta información clínica relevante entre profesionales</li>
            <li>La coordinación del tratamiento si fuera necesario</li>
        </ul>
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
            'referral_reason' => [
                'type' => 'string',
                'label' => 'Motivo de la derivación',
                'required' => false,
            ],
            'psychiatrist_name' => [
                'type' => 'string',
                'label' => 'Nombre del psiquiatra (opcional)',
                'required' => false,
            ],
            'psychiatrist_license' => [
                'type' => 'string',
                'label' => 'Número de colegiado del psiquiatra (opcional)',
                'required' => false,
            ],
            'expected_outcome' => [
                'type' => 'string',
                'label' => 'Resultado esperado',
                'required' => false,
            ],
        ];
    }
}


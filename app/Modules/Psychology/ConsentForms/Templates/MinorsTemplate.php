<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Minors Consent Form Template for Psychology
 * 
 * Used for parental consent when treating minors
 */
class MinorsTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';
        $contactAge = $contact && $contact->date_of_birth ? now()->diffInYears($contact->date_of_birth) : null;
        
        $legalGuardianName = $data['legal_guardian_name'] ?? '';
        $legalGuardianRelationship = $data['legal_guardian_relationship'] ?? 'tutor legal';
        $legalGuardianIdDocument = $data['legal_guardian_id_document'] ?? '';
        $minorAssent = $data['minor_assent'] ?? false;
        $minorAssentDetails = $data['minor_assent_details'] ?? '';
        $treatmentDescription = $data['treatment_description'] ?? 'Tratamiento psicológico adaptado a las necesidades del menor';
        $treatmentGoals = $data['treatment_goals'] ?? 'Mejorar el bienestar psicológico y emocional';

        $date = now()->format('d/m/Y');
        $professionalName = "{$professional->user->first_name} {$professional->user->last_name}";
        $licenseInfo = $professional->license_number ? "<p><strong>Número de Colegiado:</strong> {$professional->license_number}</p>" : '';
        
        $ageInfo = $contactAge ? "<p><strong>Edad del menor:</strong> {$contactAge} años</p>" : '';
        
        $html = <<<HTML
<div class="consent-form minors">
    <h2>Consentimiento Informado para Tratamiento de Menor</h2>
    
    <div class="consent-section">
        <h3>1. Información del Menor</h3>
        <p><strong>Nombre:</strong> {$contactName}</p>
        {$ageInfo}
        <p><strong>Fecha:</strong> {$date}</p>
    </div>

    <div class="consent-section">
        <h3>2. Información del Profesional</h3>
        <p><strong>Psicólogo/a:</strong> {$professionalName}</p>
        {$licenseInfo}
    </div>

    <div class="consent-section">
        <h3>3. Información del Tutor Legal</h3>
        <p><strong>Nombre:</strong> {$legalGuardianName}</p>
        <p><strong>Relación con el menor:</strong> {$legalGuardianRelationship}</p>
HTML;
        
        if ($legalGuardianIdDocument) {
            $html .= <<<HTML
        <p><strong>DNI/NIE:</strong> {$legalGuardianIdDocument}</p>
HTML;
        }
        
        $html .= <<<HTML
    </div>

    <div class="consent-section">
        <h3>4. Descripción del Tratamiento</h3>
        <p>{$treatmentDescription}</p>
    </div>

    <div class="consent-section">
        <h3>5. Objetivos del Tratamiento</h3>
        <p>{$treatmentGoals}</p>
    </div>

    <div class="consent-section">
        <h3>6. Confidencialidad</h3>
        <p>La información del menor se mantendrá bajo estricta confidencialidad. Sin embargo, como tutor legal, usted será informado sobre el progreso del tratamiento y cualquier situación que requiera su conocimiento.</p>
        <p>El menor también tiene derecho a la confidencialidad en ciertos aspectos, especialmente si es mayor de 12 años, según la normativa vigente.</p>
    </div>
HTML;
        
        if ($contactAge && $contactAge >= 12) {
            $assentText = $minorAssent ? 'Sí' : 'No';
            $html .= <<<HTML

    <div class="consent-section">
        <h3>7. Asentimiento del Menor</h3>
        <p>Dado que el menor tiene {$contactAge} años, su asentimiento es importante:</p>
        <p><strong>El menor ha dado su asentimiento:</strong> {$assentText}</p>
HTML;
            
            if ($minorAssentDetails) {
                $html .= <<<HTML
        <p><strong>Detalles del asentimiento:</strong> {$minorAssentDetails}</p>
HTML;
            }
            
            $html .= <<<HTML
    </div>
HTML;
        }
        
        $sectionNumber1 = ($contactAge && $contactAge >= 12) ? '8' : '7';
        $sectionNumber2 = ($contactAge && $contactAge >= 12) ? '9' : '8';
        
        $html .= <<<HTML

    <div class="consent-section">
        <h3>{$sectionNumber1}. Derechos del Tutor</h3>
        <p>Como tutor legal, tiene derecho a:</p>
        <ul>
            <li>Ser informado sobre el tratamiento y el progreso</li>
            <li>Participar en decisiones importantes sobre el tratamiento</li>
            <li>Revocar el consentimiento en cualquier momento</li>
            <li>Acceder a la información clínica del menor (según normativa vigente)</li>
        </ul>
    </div>

    <div class="consent-section">
        <h3>{$sectionNumber2}. Consentimiento</h3>
        <p>He leído y comprendido la información proporcionada sobre el tratamiento psicológico del menor. Como tutor legal, consiento en:</p>
        <ul>
            <li>El tratamiento psicológico del menor según los términos descritos</li>
            <li>La participación del menor en las sesiones de terapia</li>
            <li>El seguimiento del tratamiento y comunicación con el profesional</li>
        </ul>
    </div>

    <div class="consent-section signature">
        <p><strong>Firma del Tutor Legal:</strong> _________________________</p>
        <p><strong>Fecha:</strong> _________________________</p>
HTML;
        
        if ($contactAge && $contactAge >= 12 && $minorAssent) {
            $html .= <<<HTML
        <p style="margin-top: 20px;"><strong>Firma del Menor (Asentimiento):</strong> _________________________</p>
HTML;
        }
        
        $html .= <<<HTML
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
            'legal_guardian_name',
        ];
    }

    /**
     * Validate data
     */
    public function validate(array $data): bool
    {
        $required = $this->getRequiredFields();
        
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
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
            'legal_guardian_name' => [
                'type' => 'string',
                'label' => 'Nombre del tutor legal',
                'required' => true,
            ],
            'legal_guardian_relationship' => [
                'type' => 'string',
                'label' => 'Relación con el menor',
                'required' => false,
            ],
            'legal_guardian_id_document' => [
                'type' => 'string',
                'label' => 'DNI/NIE del tutor',
                'required' => false,
            ],
            'minor_assent' => [
                'type' => 'boolean',
                'label' => 'Asentimiento del menor (si >12 años)',
                'required' => false,
            ],
            'minor_assent_details' => [
                'type' => 'string',
                'label' => 'Detalles del asentimiento',
                'required' => false,
            ],
            'treatment_description' => [
                'type' => 'string',
                'label' => 'Descripción del tratamiento',
                'required' => false,
            ],
            'treatment_goals' => [
                'type' => 'string',
                'label' => 'Objetivos del tratamiento',
                'required' => false,
            ],
        ];
    }
}


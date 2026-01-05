<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Research Consent Form Template for Psychology
 * 
 * Used when a patient consents to participate in research studies
 */
class ResearchTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';
        
        $researchTitle = $data['research_title'] ?? 'Estudio de investigación';
        $researchPurpose = $data['research_purpose'] ?? 'Mejorar el conocimiento sobre tratamientos psicológicos';
        $procedures = $data['procedures'] ?? 'Participación en cuestionarios y evaluaciones';
        $duration = $data['duration'] ?? 'Durante el tiempo que dure el estudio';
        $risks = $data['risks'] ?? 'Riesgos mínimos, similares a los de una sesión de terapia';
        $benefits = $data['benefits'] ?? 'Contribución al avance del conocimiento científico';
        $confidentiality = $data['confidentiality'] ?? 'Los datos serán anonimizados y tratados de forma confidencial';
        $voluntaryParticipation = $data['voluntary_participation'] ?? true;

        $date = now()->format('d/m/Y');
        $professionalName = "{$professional->user->first_name} {$professional->user->last_name}";
        $licenseInfo = $professional->license_number ? "<p><strong>Número de Colegiado:</strong> {$professional->license_number}</p>" : '';
        
        $html = <<<HTML
<div class="consent-form research">
    <h2>Consentimiento Informado para Participación en Investigación</h2>
    
    <div class="consent-section">
        <h3>1. Información del Participante</h3>
        <p><strong>Nombre:</strong> {$contactName}</p>
        <p><strong>Fecha:</strong> {$date}</p>
    </div>

    <div class="consent-section">
        <h3>2. Investigador Principal</h3>
        <p><strong>Nombre:</strong> {$professionalName}</p>
        {$licenseInfo}
    </div>

    <div class="consent-section">
        <h3>3. Título del Estudio</h3>
        <p><strong>{$researchTitle}</strong></p>
    </div>

    <div class="consent-section">
        <h3>4. Propósito de la Investigación</h3>
        <p>{$researchPurpose}</p>
    </div>

    <div class="consent-section">
        <h3>5. Procedimientos</h3>
        <p>{$procedures}</p>
    </div>

    <div class="consent-section">
        <h3>6. Duración</h3>
        <p>{$duration}</p>
    </div>

    <div class="consent-section">
        <h3>7. Riesgos y Beneficios</h3>
        <p><strong>Riesgos:</strong> {$risks}</p>
        <p><strong>Beneficios:</strong> {$benefits}</p>
    </div>

    <div class="consent-section">
        <h3>8. Confidencialidad</h3>
        <p>{$confidentiality}</p>
        <p>Sus datos personales serán protegidos según la normativa vigente (RGPD y LOPDGDD).</p>
    </div>

    <div class="consent-section">
        <h3>9. Participación Voluntaria</h3>
        <p>Su participación en este estudio es completamente voluntaria. Puede retirarse en cualquier momento sin que esto afecte a su tratamiento psicológico.</p>
    </div>

    <div class="consent-section">
        <h3>10. Preguntas</h3>
        <p>Si tiene alguna pregunta sobre este estudio, puede contactar con el investigador principal en cualquier momento.</p>
    </div>

    <div class="consent-section">
        <h3>11. Consentimiento</h3>
        <p>He leído y comprendido la información proporcionada sobre este estudio de investigación. Consiento en:</p>
        <ul>
            <li>Participar voluntariamente en este estudio</li>
            <li>Permitir el uso de mis datos de forma anonimizada para fines de investigación</li>
            <li>Entender que puedo retirarme en cualquier momento</li>
        </ul>
    </div>

    <div class="consent-section signature">
        <p><strong>Firma del Participante:</strong> _________________________</p>
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
            'research_title' => [
                'type' => 'string',
                'label' => 'Título del estudio',
                'required' => false,
            ],
            'research_purpose' => [
                'type' => 'string',
                'label' => 'Propósito de la investigación',
                'required' => false,
            ],
            'procedures' => [
                'type' => 'string',
                'label' => 'Procedimientos',
                'required' => false,
            ],
            'duration' => [
                'type' => 'string',
                'label' => 'Duración',
                'required' => false,
            ],
            'risks' => [
                'type' => 'string',
                'label' => 'Riesgos',
                'required' => false,
            ],
            'benefits' => [
                'type' => 'string',
                'label' => 'Beneficios',
                'required' => false,
            ],
            'confidentiality' => [
                'type' => 'string',
                'label' => 'Información sobre confidencialidad',
                'required' => false,
            ],
        ];
    }
}


<?php

namespace App\Modules\Psychology\ConsentForms\Templates;

use App\Core\ConsentForms\Contracts\ConsentFormTemplateInterface;
use App\Core\Users\Models\Professional;

/**
 * Recording Consent Form Template for Psychology
 * 
 * Used when a patient consents to recording of therapy sessions
 */
class RecordingTemplate implements ConsentFormTemplateInterface
{
    /**
     * Generate the consent form content
     */
    public function generate(array $data, Professional $professional): string
    {
        $contact = $data['contact'] ?? null;
        $contactName = $contact ? "{$contact->first_name} {$contact->last_name}" : '';
        
        $recordingPurpose = $data['recording_purpose'] ?? 'Supervisión profesional y mejora del tratamiento';
        $recordingType = $data['recording_type'] ?? 'Audio y/o video';
        $storageLocation = $data['storage_location'] ?? 'Almacenamiento seguro y cifrado';
        $retentionPeriod = $data['retention_period'] ?? 'Durante el tiempo necesario para los fines acordados';
        $accessControl = $data['access_control'] ?? 'Solo el profesional y supervisores autorizados';
        $deletionPolicy = $data['deletion_policy'] ?? 'Las grabaciones serán eliminadas al finalizar el tratamiento o cuando usted lo solicite';

        $date = now()->format('d/m/Y');
        $professionalName = "{$professional->user->first_name} {$professional->user->last_name}";
        $licenseInfo = $professional->license_number ? "<p><strong>Número de Colegiado:</strong> {$professional->license_number}</p>" : '';
        
        $html = <<<HTML
<div class="consent-form recording">
    <h2>Consentimiento para Grabación de Sesiones</h2>
    
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
        <h3>3. Propósito de la Grabación</h3>
        <p>{$recordingPurpose}</p>
        <p>Las grabaciones se utilizarán exclusivamente para los fines terapéuticos y de supervisión profesional acordados.</p>
    </div>

    <div class="consent-section">
        <h3>4. Tipo de Grabación</h3>
        <p><strong>{$recordingType}</strong></p>
    </div>

    <div class="consent-section">
        <h3>5. Almacenamiento y Seguridad</h3>
        <p><strong>Ubicación:</strong> {$storageLocation}</p>
        <p><strong>Control de acceso:</strong> {$accessControl}</p>
        <p>Las grabaciones estarán protegidas mediante cifrado y medidas de seguridad adecuadas.</p>
    </div>

    <div class="consent-section">
        <h3>6. Período de Retención</h3>
        <p>{$retentionPeriod}</p>
    </div>

    <div class="consent-section">
        <h3>7. Política de Eliminación</h3>
        <p>{$deletionPolicy}</p>
    </div>

    <div class="consent-section">
        <h3>8. Confidencialidad</h3>
        <p>Las grabaciones se mantendrán bajo estricta confidencialidad. No se compartirán con terceros sin su consentimiento expreso, excepto en los casos legalmente establecidos.</p>
    </div>

    <div class="consent-section">
        <h3>9. Derechos del Paciente</h3>
        <p>Como paciente, tiene derecho a:</p>
        <ul>
            <li>Revocar este consentimiento en cualquier momento</li>
            <li>Solicitar la eliminación de las grabaciones</li>
            <li>Acceder a las grabaciones (según normativa vigente)</li>
            <li>Ser informado sobre el uso de las grabaciones</li>
        </ul>
    </div>

    <div class="consent-section">
        <h3>10. Consentimiento</h3>
        <p>He leído y comprendido la información proporcionada sobre la grabación de sesiones. Consiento en:</p>
        <ul>
            <li>La grabación de mis sesiones de terapia según los términos descritos</li>
            <li>El almacenamiento seguro de las grabaciones</li>
            <li>El uso de las grabaciones para los fines acordados</li>
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
            'recording_purpose' => [
                'type' => 'string',
                'label' => 'Propósito de la grabación',
                'required' => false,
            ],
            'recording_type' => [
                'type' => 'string',
                'label' => 'Tipo de grabación (audio/video)',
                'required' => false,
            ],
            'storage_location' => [
                'type' => 'string',
                'label' => 'Ubicación del almacenamiento',
                'required' => false,
            ],
            'retention_period' => [
                'type' => 'string',
                'label' => 'Período de retención',
                'required' => false,
            ],
            'access_control' => [
                'type' => 'string',
                'label' => 'Control de acceso',
                'required' => false,
            ],
            'deletion_policy' => [
                'type' => 'string',
                'label' => 'Política de eliminación',
                'required' => false,
            ],
        ];
    }
}


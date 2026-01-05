<?php

namespace Database\Factories;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\ConsentForms\Models\ConsentForm>
 */
class ConsentFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $consentType = fake()->randomElement([
            ConsentForm::TYPE_INITIAL_TREATMENT,
            ConsentForm::TYPE_TELECONSULTATION,
        ]);

        $isSigned = fake()->boolean(60); // 60% probabilidad de estar firmado
        $signedAt = $isSigned ? fake()->dateTimeBetween('-30 days', 'now') : null;

        $additionalData = $this->getAdditionalDataForType($consentType);

        return [
            'professional_id' => Professional::factory(),
            'contact_id' => Contact::factory(),
            'consent_type' => $consentType,
            'consent_title' => ConsentForm::getConsentTypes()[$consentType] ?? 'Consentimiento',
            'consent_text' => $this->generateConsentText($consentType),
            'additional_data' => $additionalData,
            'document_version' => '1.0',
            'is_valid' => $isSigned,
            'signed_at' => $signedAt,
            'patient_ip_address' => $isSigned ? fake()->ipv4() : null,
            'patient_device_info' => $isSigned ? fake()->userAgent() : null,
            'patient_signature_data' => $isSigned ? $this->generateFakeSignature() : null,
            'revoked_at' => null,
            'revocation_reason' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Get additional data based on consent type
     */
    private function getAdditionalDataForType(string $consentType): array
    {
        $data = [];

        switch ($consentType) {
            case ConsentForm::TYPE_INITIAL_TREATMENT:
                $data = [
                    'treatment_duration' => fake()->randomElement(['6 meses', '12 sesiones', '3 meses', 'Indefinido']),
                    'session_frequency' => fake()->randomElement(['Semanal', 'Quincenal', 'Mensual']),
                    'session_duration' => fake()->randomElement([50, 60, 90]),
                    'treatment_techniques' => fake()->randomElements(
                        ['TCC', 'EMDR', 'Mindfulness', 'Terapia de Aceptación', 'Psicoeducación'],
                        fake()->numberBetween(2, 4)
                    ),
                ];
                break;

            case ConsentForm::TYPE_TELECONSULTATION:
                $data = [
                    'platform' => fake()->randomElement(['Clinora', 'Zoom', 'Google Meet', 'Microsoft Teams']),
                    'security_info' => 'Cifrado end-to-end, servidores en UE, cumplimiento RGPD',
                    'recording_consent' => fake()->boolean(30),
                ];
                break;
        }

        return $data;
    }

    /**
     * Generate consent text based on type
     */
    private function generateConsentText(string $consentType): string
    {
        $baseText = "CONSENTIMIENTO INFORMADO\n\n";
        $baseText .= "Yo, el paciente, he leído y comprendido la información proporcionada.\n\n";
        
        switch ($consentType) {
            case ConsentForm::TYPE_INITIAL_TREATMENT:
                $baseText .= "Consiento voluntariamente recibir tratamiento psicológico.\n";
                $baseText .= "Entiendo los objetivos, metodología y duración del tratamiento.\n";
                break;
                
            case ConsentForm::TYPE_TELECONSULTATION:
                $baseText .= "Consiento participar en sesiones de teleconsulta.\n";
                $baseText .= "Entiendo los requisitos técnicos y las limitaciones.\n";
                break;
        }
        
        $baseText .= "\nFirma: _________________\n";
        $baseText .= "Fecha: " . now()->format('d/m/Y');

        return $baseText;
    }

    /**
     * Generate a fake signature (base64 encoded simple line)
     */
    private function generateFakeSignature(): string
    {
        // Simple base64 encoded placeholder for signature
        // In real implementation, this would be actual signature data
        return base64_encode('fake_signature_data_' . fake()->uuid());
    }

    /**
     * Create a signed consent form
     */
    public function signed(): static
    {
        return $this->state(function (array $attributes) {
            $signedAt = fake()->dateTimeBetween('-30 days', 'now');
            
            return [
                'is_valid' => true,
                'signed_at' => $signedAt,
                'patient_ip_address' => fake()->ipv4(),
                'patient_device_info' => fake()->userAgent(),
                'patient_signature_data' => $this->generateFakeSignature(),
            ];
        });
    }

    /**
     * Create a pending (unsigned) consent form
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_valid' => false,
            'signed_at' => null,
            'patient_ip_address' => null,
            'patient_device_info' => null,
            'patient_signature_data' => null,
        ]);
    }

    /**
     * Create a revoked consent form
     */
    public function revoked(): static
    {
        return $this->state(function (array $attributes) {
            $signedAt = fake()->dateTimeBetween('-60 days', '-10 days');
            $revokedAt = fake()->dateTimeBetween($signedAt, 'now');
            
            return [
                'is_valid' => false,
                'signed_at' => $signedAt,
                'revoked_at' => $revokedAt,
                'revocation_reason' => fake()->optional()->sentence(),
                'patient_ip_address' => fake()->ipv4(),
                'patient_device_info' => fake()->userAgent(),
                'patient_signature_data' => $this->generateFakeSignature(),
            ];
        });
    }

    /**
     * Create a consent form for a minor
     */
    public function forMinor(): static
    {
        return $this->state(fn (array $attributes) => [
            'consent_type' => ConsentForm::TYPE_MINORS,
            'legal_guardian_name' => fake()->name(),
            'legal_guardian_relationship' => fake()->randomElement(['Padre', 'Madre', 'Tutor legal']),
            'legal_guardian_id_document' => fake()->numerify('########?'),
            'minor_assent' => fake()->boolean(70),
            'minor_assent_details' => fake()->optional()->sentence(),
        ]);
    }

    /**
     * Create a teleconsultation consent form
     */
    public function teleconsultation(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'consent_type' => ConsentForm::TYPE_TELECONSULTATION,
                'consent_title' => 'Consentimiento para Teleconsulta',
                'additional_data' => [
                    'platform' => fake()->randomElement(['Clinora', 'Zoom', 'Google Meet']),
                    'security_info' => 'Cifrado end-to-end, servidores en UE, cumplimiento RGPD',
                    'recording_consent' => fake()->boolean(30),
                ],
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Core\Appointments\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote>
 */
class ClinicalNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sessionDate = fake()->dateTimeBetween('-30 days', 'now');

        return [
            'professional_id' => Professional::factory(),
            'contact_id' => Contact::factory(),
            'appointment_id' => Appointment::factory(),
            'session_number' => fake()->numberBetween(1, 20),
            'session_date' => $sessionDate,
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
            'subjective' => fake()->paragraph(3),
            'objective' => fake()->paragraph(2),
            'assessment' => fake()->paragraph(2),
            'plan' => fake()->paragraph(2),
            'interventions_used' => fake()->randomElements(
                ['TCC', 'EMDR', 'Mindfulness', 'Terapia de Aceptación', 'Psicoeducación'],
                fake()->numberBetween(1, 3)
            ),
            'homework' => fake()->optional()->sentence(),
            'risk_assessment' => fake()->randomElement(['sin_riesgo', 'riesgo_bajo', 'riesgo_medio', 'riesgo_alto']),
            'risk_details' => function (array $attributes) {
                return in_array($attributes['risk_assessment'], ['riesgo_bajo', 'riesgo_medio', 'riesgo_alto'])
                    ? fake()->sentence()
                    : null;
            },
            'progress_rating' => fake()->numberBetween(1, 10),
            'is_signed' => fake()->boolean(70), // 70% probabilidad de estar firmada
            'signed_at' => function (array $attributes) {
                return $attributes['is_signed'] ? fake()->dateTimeBetween($attributes['session_date'], 'now') : null;
            },
        ];
    }

    /**
     * Create a signed clinical note
     */
    public function signed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_signed' => true,
            'signed_at' => fake()->dateTimeBetween($attributes['session_date'] ?? '-30 days', 'now'),
        ]);
    }

    /**
     * Create an unsigned clinical note
     */
    public function unsigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_signed' => false,
            'signed_at' => null,
        ]);
    }
}


<?php

namespace Database\Factories;

use App\Core\Users\Models\PatientUser;
use App\Core\Users\Models\Professional;
use App\Core\Contacts\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\Users\Models\PatientUser>
 */
class PatientUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['user_type' => 'patient']),
            'contact_id' => Contact::factory(),
            'professional_id' => Professional::factory(),
            'portal_activated_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'), // 70% activados
            'email_notifications_enabled' => true,
            'sms_notifications_enabled' => fake()->boolean(30), // 30% con SMS habilitado
        ];
    }

    /**
     * Create a patient user with activated portal
     */
    public function activated(): static
    {
        return $this->state(fn (array $attributes) => [
            'portal_activated_at' => now(),
        ]);
    }

    /**
     * Create a patient user with deactivated portal
     */
    public function deactivated(): static
    {
        return $this->state(fn (array $attributes) => [
            'portal_activated_at' => null,
        ]);
    }
}


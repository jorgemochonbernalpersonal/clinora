<?php

namespace Database\Factories;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\Contacts\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'professional_id' => Professional::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'dni' => fake()->optional()->numerify('########') . fake()->optional()->randomLetter(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'gender' => fake()->randomElement(['male', 'female', 'other', 'prefer_not_to_say']),
            'marital_status' => fake()->optional()->randomElement(['single', 'married', 'divorced', 'widowed', 'cohabiting', 'other']),
            'occupation' => fake()->optional()->jobTitle(),
            'education_level' => fake()->optional()->randomElement(['primary', 'secondary', 'vocational', 'university', 'postgraduate', 'other']),
            'address_street' => fake()->streetAddress(),
            'address_city' => fake()->city(),
            'address_postal_code' => fake()->postcode(),
            'address_country' => 'España',
            'emergency_contact_name' => fake()->optional()->name(),
            'emergency_contact_phone' => fake()->optional()->phoneNumber(),
            'emergency_contact_relationship' => fake()->optional()->randomElement(['Familiar', 'Amigo', 'Vecino', 'Madre', 'Pareja']),
            'initial_consultation_reason' => fake()->optional()->paragraph(),
            'first_appointment_date' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'medical_history' => fake()->optional()->paragraph(),
            'psychiatric_history' => fake()->optional()->paragraph(),
            'current_medication' => fake()->optional()->sentence(),
            'insurance_company' => fake()->optional()->randomElement(['Adeslas', 'Sanitas', 'DKV', 'Asisa', 'Mapfre', 'Aegon']),
            'insurance_policy_number' => fake()->optional()->numerify('#########'),
            'notes' => fake()->optional()->sentence(),
            'tags' => fake()->optional()->randomElements(['Urgente', 'VIP', 'Nuevo', 'Seguimiento'], fake()->numberBetween(0, 2)),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the contact is inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}


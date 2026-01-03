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
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'gender' => fake()->randomElement(['M', 'F', 'O']),
            'address_street' => fake()->streetAddress(),
            'address_city' => fake()->city(),
            'address_postal_code' => fake()->postcode(),
            'address_country' => 'España',
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relationship' => fake()->randomElement(['Familiar', 'Amigo', 'Vecino']),
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


<?php

namespace Database\Factories;

use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Shared\Enums\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\Users\Models\Professional>
 */
class ProfessionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->professional(),
            'license_number' => fake()->numerify('LIC-####'),
            'profession' => fake()->randomElement(['Psicólogo', 'Psiquiatra', 'Terapeuta', 'Counselor']),
            'specialties' => fake()->randomElements(
                ['Ansiedad', 'Depresión', 'Trauma', 'Parejas', 'Infantil', 'Adolescentes'],
                fake()->numberBetween(1, 3)
            ),
            'address_street' => fake()->streetAddress(),
            'address_city' => fake()->city(),
            'address_postal_code' => fake()->postcode(),
            'address_country' => 'España',
            'subscription_plan' => SubscriptionPlan::GRATIS,
            'subscription_status' => 'active',
        ];
    }
}


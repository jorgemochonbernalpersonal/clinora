<?php

namespace Database\Factories;

use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Shared\Enums\ProfessionType;
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
            'profession_type' => fake()->randomElement(ProfessionType::cases()),
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
    
    /**
     * Create a psychologist professional
     */
    public function psychologist(): static
    {
        return $this->state(fn (array $attributes) => [
            'profession_type' => ProfessionType::PSYCHOLOGIST,
            'profession' => 'Psicólogo/a',
        ]);
    }
    
    /**
     * Create a therapist professional
     */
    public function therapist(): static
    {
        return $this->state(fn (array $attributes) => [
            'profession_type' => ProfessionType::THERAPIST,
            'profession' => 'Terapeuta',
        ]);
    }
    
    /**
     * Create a nutritionist professional
     */
    public function nutritionist(): static
    {
        return $this->state(fn (array $attributes) => [
            'profession_type' => ProfessionType::NUTRITIONIST,
            'profession' => 'Nutricionista',
            'specialties' => fake()->randomElements(
                ['Nutrición Deportiva', 'Pérdida de Peso', 'Diabetes', 'Vegetarianismo', 'Nutrición Infantil'],
                fake()->numberBetween(1, 3)
            ),
        ]);
    }
}


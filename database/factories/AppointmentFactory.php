<?php

namespace Database\Factories;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Core\Appointments\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('now', '+30 days');
        $duration = fake()->randomElement([30, 45, 60, 90]); // minutos
        $endTime = (clone $startTime)->modify("+{$duration} minutes");

        return [
            'professional_id' => Professional::factory(),
            'contact_id' => Contact::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => fake()->randomElement(AppointmentType::cases()),
            'status' => fake()->randomElement(AppointmentStatus::cases()),
            'title' => fake()->optional()->sentence(3),
            'notes' => fake()->optional()->paragraph(),
            'internal_notes' => fake()->optional()->paragraph(),
            'price' => fake()->randomFloat(2, 50, 150),
            'currency' => 'EUR',
            'is_paid' => fake()->boolean(30), // 30% probabilidad de estar pagado
        ];
    }

    /**
     * Create a scheduled appointment
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppointmentStatus::SCHEDULED,
        ]);
    }

    /**
     * Create a completed appointment
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppointmentStatus::COMPLETED,
            'start_time' => fake()->dateTimeBetween('-30 days', 'now'),
            'end_time' => function (array $attributes) {
                return (clone $attributes['start_time'])->modify('+60 minutes');
            },
        ]);
    }

    /**
     * Create an online appointment
     */
    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AppointmentType::ONLINE,
        ]);
    }
}


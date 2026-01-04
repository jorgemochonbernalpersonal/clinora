<?php

namespace Tests\Feature;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Professional $professional;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);
    }

    /** @test */
    public function it_can_list_appointments_for_authenticated_professional(): void
    {
        Sanctum::actingAs($this->user);

        Appointment::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $response = $this->getJson('/api/v1/appointments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'start_time', 'end_time', 'status'],
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function it_can_filter_appointments_by_status(): void
    {
        Sanctum::actingAs($this->user);

        Appointment::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        Appointment::factory()->count(2)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $response = $this->getJson('/api/v1/appointments?status=scheduled');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function it_can_create_an_appointment(): void
    {
        Sanctum::actingAs($this->user);

        $startTime = now()->addDay()->setTime(10, 0);
        $endTime = $startTime->copy()->addHour();

        $data = [
            'contact_id' => $this->contact->id,
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
            'title' => 'Consulta inicial',
        ];

        $response = $this->postJson('/api/v1/appointments', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'start_time', 'end_time', 'status'],
            ]);

        $this->assertDatabaseHas('appointments', [
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'title' => 'Consulta inicial',
        ]);
    }

    /** @test */
    public function it_prevents_creating_appointment_with_conflict(): void
    {
        Sanctum::actingAs($this->user);

        // Create existing appointment
        Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Try to create overlapping appointment
        $data = [
            'contact_id' => $this->contact->id,
            'start_time' => now()->addDay()->setTime(10, 30)->toDateTimeString(),
            'end_time' => now()->addDay()->setTime(11, 30)->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
        ];

        $response = $this->postJson('/api/v1/appointments', $data);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
            ]);
    }

    /** @test */
    public function it_prevents_creating_appointment_for_other_professional_contact(): void
    {
        Sanctum::actingAs($this->user);

        $otherProfessional = Professional::factory()->create();
        $otherContact = Contact::factory()->create([
            'professional_id' => $otherProfessional->id,
        ]);

        $data = [
            'contact_id' => $otherContact->id,
            'start_time' => now()->addDay()->toDateTimeString(),
            'end_time' => now()->addDay()->addHour()->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
        ];

        $response = $this->postJson('/api/v1/appointments', $data);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'El paciente no pertenece a tu práctica',
            ]);
    }

    /** @test */
    public function it_can_show_an_appointment(): void
    {
        Sanctum::actingAs($this->user);

        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $response = $this->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'start_time', 'end_time', 'status'],
            ]);

        $this->assertEquals($appointment->id, $response->json('data.id'));
    }

    /** @test */
    public function it_returns_404_for_other_professional_appointment(): void
    {
        Sanctum::actingAs($this->user);

        $otherProfessional = Professional::factory()->create();
        $otherContact = Contact::factory()->create([
            'professional_id' => $otherProfessional->id,
        ]);
        
        $appointment = Appointment::factory()->create([
            'professional_id' => $otherProfessional->id,
            'contact_id' => $otherContact->id,
        ]);

        $response = $this->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_appointment(): void
    {
        Sanctum::actingAs($this->user);

        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'title' => 'Consulta inicial',
        ]);

        $response = $this->putJson("/api/v1/appointments/{$appointment->id}", [
            'title' => 'Consulta de seguimiento',
            'start_time' => $appointment->start_time->toDateTimeString(),
            'end_time' => $appointment->end_time->toDateTimeString(),
            'type' => $appointment->type->value,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
            ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'title' => 'Consulta de seguimiento',
        ]);
    }

    /** @test */
    public function it_can_cancel_an_appointment(): void
    {
        Sanctum::actingAs($this->user);

        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $response = $this->deleteJson("/api/v1/appointments/{$appointment->id}", [
            'reason' => 'Paciente canceló',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cita cancelada exitosamente',
            ]);

        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);
    }

    /** @test */
    public function it_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/appointments');

        $response->assertStatus(401);
    }
}


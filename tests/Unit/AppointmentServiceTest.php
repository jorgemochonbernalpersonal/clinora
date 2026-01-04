<?php

namespace Tests\Unit;

use App\Core\Appointments\Models\Appointment;
use App\Core\Appointments\Repositories\AppointmentRepository;
use App\Core\Appointments\Services\AppointmentService;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use App\Shared\Exceptions\AppointmentConflictException;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private AppointmentService $service;
    private AppointmentRepository $repository;
    private Professional $professional;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new AppointmentRepository(new Appointment());
        $this->service = new AppointmentService($this->repository);
        
        // Create a professional and contact for testing
        $user = \App\Models\User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);
    }

    /** @test */
    public function it_can_create_an_appointment(): void
    {
        $startTime = now()->addDay();
        $endTime = $startTime->copy()->addHour();

        $data = [
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
            'title' => 'Consulta inicial',
        ];

        $appointment = $this->service->createAppointment(
            $this->professional,
            $this->contact,
            $data,
            $this->professional->user_id
        );

        $this->assertInstanceOf(Appointment::class, $appointment);
        $this->assertEquals($this->professional->id, $appointment->professional_id);
        $this->assertEquals($this->contact->id, $appointment->contact_id);
        $this->assertEquals(AppointmentStatus::SCHEDULED, $appointment->status);
        $this->assertEquals('Consulta inicial', $appointment->title);
    }

    /** @test */
    public function it_throws_exception_when_creating_appointment_with_conflict(): void
    {
        // Create existing appointment
        Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Try to create overlapping appointment
        $data = [
            'start_time' => now()->addDay()->setTime(10, 30)->toDateTimeString(),
            'end_time' => now()->addDay()->setTime(11, 30)->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
        ];

        $this->expectException(AppointmentConflictException::class);

        $this->service->createAppointment(
            $this->professional,
            $this->contact,
            $data,
            $this->professional->user_id
        );
    }

    /** @test */
    public function it_allows_appointment_when_existing_is_cancelled(): void
    {
        // Create cancelled appointment
        Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::CANCELLED,
        ]);

        // Should allow new appointment at same time
        $data = [
            'start_time' => now()->addDay()->setTime(10, 0)->toDateTimeString(),
            'end_time' => now()->addDay()->setTime(11, 0)->toDateTimeString(),
            'type' => AppointmentType::IN_PERSON->value,
        ];

        $appointment = $this->service->createAppointment(
            $this->professional,
            $this->contact,
            $data,
            $this->professional->user_id
        );

        $this->assertInstanceOf(Appointment::class, $appointment);
    }

    /** @test */
    public function it_can_update_an_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'title' => 'Consulta inicial',
        ]);

        $updated = $this->service->updateAppointment(
            $appointment->id,
            ['title' => 'Consulta de seguimiento'],
            $this->professional->user_id
        );

        $this->assertEquals('Consulta de seguimiento', $updated->title);
    }

    /** @test */
    public function it_throws_exception_when_updating_appointment_with_conflict(): void
    {
        // Create two appointments
        $appointment1 = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
        ]);

        $appointment2 = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(12, 0),
            'end_time' => now()->addDay()->setTime(13, 0),
        ]);

        // Try to move appointment2 to conflict with appointment1
        $this->expectException(AppointmentConflictException::class);

        $this->service->updateAppointment(
            $appointment2->id,
            [
                'start_time' => now()->addDay()->setTime(10, 30)->toDateTimeString(),
                'end_time' => now()->addDay()->setTime(11, 30)->toDateTimeString(),
            ],
            $this->professional->user_id
        );
    }

    /** @test */
    public function it_can_get_appointments_for_professional(): void
    {
        Appointment::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->getAppointmentsForProfessional(
            $this->professional,
            [],
            false
        );

        $this->assertCount(5, $result);
    }

    /** @test */
    public function it_can_filter_appointments_by_status(): void
    {
        Appointment::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        Appointment::factory()->count(2)->create([
            'professional_id' => $this->professional->id,
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $result = $this->service->getAppointmentsForProfessional(
            $this->professional,
            ['status' => AppointmentStatus::SCHEDULED->value],
            false
        );

        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_can_get_a_single_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $result = $this->service->getAppointment($appointment->id);

        $this->assertInstanceOf(Appointment::class, $result);
        $this->assertEquals($appointment->id, $result->id);
    }

    /** @test */
    public function it_can_cancel_an_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $cancelled = $this->service->cancelAppointment($appointment->id, 'Paciente canceló');

        $this->assertEquals(AppointmentStatus::CANCELLED, $cancelled->status);
        $this->assertEquals('Paciente canceló', $cancelled->cancellation_reason);
    }

    /** @test */
    public function it_cannot_cancel_completed_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Esta cita no puede ser cancelada');

        $this->service->cancelAppointment($appointment->id);
    }

    /** @test */
    public function it_can_complete_an_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'status' => AppointmentStatus::IN_PROGRESS,
        ]);

        $completed = $this->service->completeAppointment($appointment->id);

        $this->assertEquals(AppointmentStatus::COMPLETED, $completed->status);
    }

    /** @test */
    public function it_can_verify_appointment_ownership(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $otherProfessional = Professional::factory()->create();

        $this->assertTrue($this->service->verifyOwnership($appointment->id, $this->professional));
        $this->assertFalse($this->service->verifyOwnership($appointment->id, $otherProfessional));
    }

    /** @test */
    public function it_can_get_upcoming_appointments(): void
    {
        Appointment::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        Appointment::factory()->count(2)->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->subDay(),
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $result = $this->service->getUpcomingAppointments($this->professional, 10);

        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_can_get_today_appointments(): void
    {
        Appointment::factory()->count(2)->create([
            'professional_id' => $this->professional->id,
            'start_time' => today()->setTime(10, 0),
        ]);

        Appointment::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay(),
        ]);

        $result = $this->service->getTodayAppointments($this->professional);

        $this->assertCount(2, $result);
    }
}


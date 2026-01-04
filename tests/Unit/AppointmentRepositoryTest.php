<?php

namespace Tests\Unit;

use App\Core\Appointments\Models\Appointment;
use App\Core\Appointments\Repositories\AppointmentRepository;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AppointmentRepository $repository;
    private Professional $professional;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new AppointmentRepository(new Appointment());
        
        $user = \App\Models\User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);
    }

    /** @test */
    public function it_can_find_appointment_by_id(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $result = $this->repository->find($appointment->id);

        $this->assertInstanceOf(Appointment::class, $result);
        $this->assertEquals($appointment->id, $result->id);
    }

    /** @test */
    public function it_can_find_all_appointments(): void
    {
        Appointment::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $result = $this->repository->findAll();

        $this->assertCount(5, $result);
    }

    /** @test */
    public function it_can_filter_appointments_by_professional(): void
    {
        $otherProfessional = Professional::factory()->create();
        $otherContact = Contact::factory()->create([
            'professional_id' => $otherProfessional->id,
        ]);

        Appointment::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        Appointment::factory()->count(2)->create([
            'professional_id' => $otherProfessional->id,
            'contact_id' => $otherContact->id,
        ]);

        $result = $this->repository->findAll([
            'professional_id' => $this->professional->id,
        ]);

        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_can_find_conflicts(): void
    {
        // Create existing appointment
        Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Check for overlapping appointment
        $conflicts = $this->repository->findConflicts(
            $this->professional->id,
            now()->addDay()->setTime(10, 30),
            now()->addDay()->setTime(11, 30)
        );

        $this->assertCount(1, $conflicts);
    }

    /** @test */
    public function it_excludes_cancelled_appointments_from_conflicts(): void
    {
        // Create cancelled appointment
        Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::CANCELLED,
        ]);

        // Should not conflict
        $conflicts = $this->repository->findConflicts(
            $this->professional->id,
            now()->addDay()->setTime(10, 0),
            now()->addDay()->setTime(11, 0)
        );

        $this->assertCount(0, $conflicts);
    }

    /** @test */
    public function it_can_exclude_appointment_from_conflict_check(): void
    {
        // Create appointment
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->professional->id,
            'start_time' => now()->addDay()->setTime(10, 0),
            'end_time' => now()->addDay()->setTime(11, 0),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        // Should not conflict with itself
        $conflicts = $this->repository->findConflicts(
            $this->professional->id,
            now()->addDay()->setTime(10, 0),
            now()->addDay()->setTime(11, 0),
            $appointment->id
        );

        $this->assertCount(0, $conflicts);
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

        $result = $this->repository->getUpcomingForProfessional($this->professional->id, 10);

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

        $result = $this->repository->getTodayForProfessional($this->professional->id);

        $this->assertCount(2, $result);
    }
}


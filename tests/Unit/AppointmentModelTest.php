<?php

namespace Tests\Unit;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_duration_in_minutes(): void
    {
        $appointment = Appointment::factory()->create([
            'start_time' => now()->setTime(10, 0),
            'end_time' => now()->setTime(11, 30),
        ]);

        $this->assertEquals(90, $appointment->duration);
    }

    /** @test */
    public function it_belongs_to_professional(): void
    {
        $professional = Professional::factory()->create();
        $appointment = Appointment::factory()->create([
            'professional_id' => $professional->id,
        ]);

        $this->assertInstanceOf(Professional::class, $appointment->professional);
        $this->assertEquals($professional->id, $appointment->professional->id);
    }

    /** @test */
    public function it_belongs_to_contact(): void
    {
        $contact = Contact::factory()->create();
        $appointment = Appointment::factory()->create([
            'contact_id' => $contact->id,
        ]);

        $this->assertInstanceOf(Contact::class, $appointment->contact);
        $this->assertEquals($contact->id, $appointment->contact->id);
    }

    /** @test */
    public function it_can_check_if_upcoming(): void
    {
        $upcoming = Appointment::factory()->create([
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $past = Appointment::factory()->create([
            'start_time' => now()->subDay(),
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $this->assertTrue($upcoming->isUpcoming());
        $this->assertFalse($past->isUpcoming());
    }

    /** @test */
    public function it_can_check_if_today(): void
    {
        $today = Appointment::factory()->create([
            'start_time' => today()->setTime(10, 0),
        ]);

        $tomorrow = Appointment::factory()->create([
            'start_time' => now()->addDay(),
        ]);

        $this->assertTrue($today->isToday());
        $this->assertFalse($tomorrow->isToday());
    }

    /** @test */
    public function it_can_check_if_cancellable(): void
    {
        $cancellable = Appointment::factory()->create([
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $notCancellable = Appointment::factory()->create([
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $past = Appointment::factory()->create([
            'start_time' => now()->subDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->assertTrue($cancellable->canBeCancelled());
        $this->assertFalse($notCancellable->canBeCancelled());
        $this->assertFalse($past->canBeCancelled());
    }

    /** @test */
    public function it_can_be_cancelled(): void
    {
        $appointment = Appointment::factory()->create([
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $appointment->cancel('Paciente canceló');

        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);
        $this->assertEquals('Paciente canceló', $appointment->fresh()->cancellation_reason);
    }

    /** @test */
    public function it_can_be_completed(): void
    {
        $appointment = Appointment::factory()->create([
            'status' => AppointmentStatus::IN_PROGRESS,
        ]);

        $appointment->complete();

        $this->assertEquals(AppointmentStatus::COMPLETED, $appointment->fresh()->status);
    }

    /** @test */
    public function it_can_scope_upcoming_appointments(): void
    {
        Appointment::factory()->count(3)->create([
            'start_time' => now()->addDay(),
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        Appointment::factory()->count(2)->create([
            'start_time' => now()->subDay(),
            'status' => AppointmentStatus::COMPLETED,
        ]);

        $upcoming = Appointment::upcoming()->get();

        $this->assertCount(3, $upcoming);
    }

    /** @test */
    public function it_can_scope_today_appointments(): void
    {
        Appointment::factory()->count(2)->create([
            'start_time' => today()->setTime(10, 0),
        ]);

        Appointment::factory()->count(3)->create([
            'start_time' => now()->addDay(),
        ]);

        $today = Appointment::today()->get();

        $this->assertCount(2, $today);
    }
}


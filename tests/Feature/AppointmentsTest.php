<?php

namespace Tests\Feature;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Shared\Enums\AppointmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that we can create appointments
     */
    public function test_can_create_appointment(): void
    {
        $this->seedTestData();

        $professional = Professional::first();
        $contact = Contact::first();

        $appointment = Appointment::create([
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(1)->addHour(),
            'type' => \App\Shared\Enums\AppointmentType::IN_PERSON,
            'status' => AppointmentStatus::SCHEDULED,
            'title' => 'Consulta inicial',
        ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'title' => 'Consulta inicial',
            'status' => AppointmentStatus::SCHEDULED->value,
        ]);
    }

    /**
     * Test that appointments belong to a professional and contact
     */
    public function test_appointment_relationships(): void
    {
        $this->seedTestData();

        $appointment = Appointment::first();

        $this->assertInstanceOf(Professional::class, $appointment->professional);
        $this->assertInstanceOf(Contact::class, $appointment->contact);
    }

    /**
     * Test that we have 5 appointments after seeding
     */
    public function test_seeder_creates_five_appointments(): void
    {
        $this->seedTestData();

        $this->assertDatabaseCount('appointments', 5);
    }

    /**
     * Test that some appointments are completed
     */
    public function test_some_appointments_are_completed(): void
    {
        $this->seedTestData();

        $completedCount = Appointment::where('status', AppointmentStatus::COMPLETED->value)->count();
        
        $this->assertGreaterThan(0, $completedCount);
    }

    /**
     * Test that appointments can be cancelled
     */
    public function test_appointment_can_be_cancelled(): void
    {
        $this->seedTestData();

        $appointment = Appointment::where('status', AppointmentStatus::SCHEDULED->value)->first();
        
        if ($appointment && $appointment->canBeCancelled()) {
            $appointment->cancel('Test cancellation');
            
            $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);
            $this->assertEquals('Test cancellation', $appointment->fresh()->cancellation_reason);
        }
    }

    /**
     * Test that appointments can be completed
     */
    public function test_appointment_can_be_completed(): void
    {
        $this->seedTestData();

        $appointment = Appointment::where('status', AppointmentStatus::SCHEDULED->value)->first();
        
        if ($appointment) {
            $appointment->complete();
            
            $this->assertEquals(AppointmentStatus::COMPLETED, $appointment->fresh()->status);
        }
    }

    /**
     * Test that appointments calculate duration correctly
     */
    public function test_appointment_calculates_duration(): void
    {
        $this->seedTestData();

        $appointment = Appointment::first();
        
        $expectedDuration = $appointment->start_time->diffInMinutes($appointment->end_time);
        
        $this->assertEquals($expectedDuration, $appointment->duration);
    }
}


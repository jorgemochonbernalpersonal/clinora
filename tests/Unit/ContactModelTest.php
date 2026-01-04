<?php

namespace Tests\Unit;

use App\Core\Appointments\Models\Appointment;
use App\Core\ClinicalNotes\Models\ClinicalNote;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_full_name_attribute(): void
    {
        $contact = Contact::factory()->create([
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
        ]);

        $this->assertEquals('Juan Pérez', $contact->full_name);
    }

    /** @test */
    public function it_calculates_age_from_date_of_birth(): void
    {
        $contact = Contact::factory()->create([
            'date_of_birth' => now()->subYears(30),
        ]);

        $this->assertEquals(30, $contact->age);
    }

    /** @test */
    public function it_returns_null_age_when_no_date_of_birth(): void
    {
        $contact = Contact::factory()->create([
            'date_of_birth' => null,
        ]);

        $this->assertNull($contact->age);
    }

    /** @test */
    public function it_has_full_address_attribute(): void
    {
        $contact = Contact::factory()->create([
            'address_street' => 'Calle Mayor 123',
            'address_city' => 'Madrid',
            'address_postal_code' => '28013',
            'address_country' => 'España',
        ]);

        $this->assertStringContainsString('Calle Mayor 123', $contact->full_address);
        $this->assertStringContainsString('Madrid', $contact->full_address);
    }

    /** @test */
    public function it_belongs_to_professional(): void
    {
        $professional = Professional::factory()->create();
        $contact = Contact::factory()->create([
            'professional_id' => $professional->id,
        ]);

        $this->assertInstanceOf(Professional::class, $contact->professional);
        $this->assertEquals($professional->id, $contact->professional->id);
    }

    /** @test */
    public function it_has_many_appointments(): void
    {
        $contact = Contact::factory()->create();
        
        Appointment::factory()->count(3)->create([
            'contact_id' => $contact->id,
        ]);

        $this->assertCount(3, $contact->appointments);
    }

    /** @test */
    public function it_has_many_clinical_notes(): void
    {
        $contact = Contact::factory()->create();
        
        ClinicalNote::factory()->count(2)->create([
            'contact_id' => $contact->id,
        ]);

        $this->assertCount(2, $contact->clinicalNotes);
    }

    /** @test */
    public function it_can_be_archived(): void
    {
        $contact = Contact::factory()->create([
            'is_active' => true,
        ]);

        $contact->archive();

        $this->assertFalse($contact->fresh()->is_active);
        $this->assertNotNull($contact->fresh()->archived_at);
    }

    /** @test */
    public function it_can_be_unarchived(): void
    {
        $contact = Contact::factory()->create([
            'is_active' => false,
            'archived_at' => now(),
        ]);

        $contact->unarchive();

        $this->assertTrue($contact->fresh()->is_active);
        $this->assertNull($contact->fresh()->archived_at);
    }

    /** @test */
    public function it_can_scope_active_contacts(): void
    {
        Contact::factory()->count(3)->create(['is_active' => true]);
        Contact::factory()->count(2)->create(['is_active' => false]);

        $active = Contact::active()->get();

        $this->assertCount(3, $active);
    }

    /** @test */
    public function it_can_search_by_name(): void
    {
        Contact::factory()->create([
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
        ]);

        Contact::factory()->create([
            'first_name' => 'María',
            'last_name' => 'García',
        ]);

        $results = Contact::searchByName('Juan')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Juan', $results->first()->first_name);
    }
}


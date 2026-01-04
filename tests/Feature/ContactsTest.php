<?php

namespace Tests\Feature;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that we can create contacts
     */
    public function test_can_create_contact(): void
    {
        $this->seedTestData();

        $professional = Professional::first();
        
        $contact = Contact::create([
            'professional_id' => $professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'date_of_birth' => '1990-01-01',
            'gender' => 'M',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
        ]);
    }

    /**
     * Test that contacts belong to a professional
     */
    public function test_contact_belongs_to_professional(): void
    {
        $this->seedTestData();

        $contact = Contact::first();
        
        $this->assertInstanceOf(Professional::class, $contact->professional);
        $this->assertNotNull($contact->professional_id);
    }

    /**
     * Test that we have 5 contacts after seeding
     */
    public function test_seeder_creates_five_contacts(): void
    {
        $this->seedTestData();

        $this->assertDatabaseCount('contacts', 5);
    }

    /**
     * Test that contacts can be archived
     */
    public function test_contact_can_be_archived(): void
    {
        $this->seedTestData();

        $contact = Contact::first();
        $contact->archive();

        $this->assertFalse($contact->fresh()->is_active);
        $this->assertNotNull($contact->fresh()->archived_at);
    }

    /**
     * Test that contacts have full name attribute
     */
    public function test_contact_has_full_name(): void
    {
        $this->seedTestData();

        $contact = Contact::first();
        
        $this->assertNotEmpty($contact->full_name);
        $this->assertStringContainsString($contact->first_name, $contact->full_name);
        $this->assertStringContainsString($contact->last_name, $contact->full_name);
    }

    /**
     * Test that contacts can be soft deleted
     */
    public function test_contact_can_be_soft_deleted(): void
    {
        $this->seedTestData();

        $contact = Contact::first();
        $contact->delete();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }
}


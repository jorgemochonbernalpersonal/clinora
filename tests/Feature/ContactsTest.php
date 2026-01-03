<?php

namespace Tests\Feature;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Tests\TestCase;

class ContactsTest extends TestCase
{
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
}


<?php

namespace Tests\Unit;

use App\Core\Contacts\Models\Contact;
use App\Core\Contacts\Repositories\ContactRepository;
use App\Core\Contacts\Services\ContactService;
use App\Core\Users\Models\Professional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    private ContactService $service;
    private ContactRepository $repository;
    private Professional $professional;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new ContactRepository(new Contact());
        $this->service = new ContactService($this->repository);
        
        // Create a professional for testing
        $user = \App\Models\User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_create_a_contact_for_professional(): void
    {
        $data = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
            'is_active' => true,
        ];

        $contact = $this->service->createForProfessional(
            $this->professional,
            $data,
            $this->professional->user_id
        );

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals($this->professional->id, $contact->professional_id);
        $this->assertEquals('Juan', $contact->first_name);
        $this->assertEquals('Pérez', $contact->last_name);
        $this->assertEquals('juan@example.com', $contact->email);
        $this->assertTrue($contact->is_active);
    }

    /** @test */
    public function it_can_update_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
        ]);

        $updated = $this->service->updateContact(
            $contact->id,
            ['first_name' => 'Carlos'],
            $this->professional->user_id
        );

        $this->assertEquals('Carlos', $updated->first_name);
        $this->assertEquals('Pérez', $updated->last_name);
    }

    /** @test */
    public function it_can_get_contacts_for_professional_with_pagination(): void
    {
        Contact::factory()->count(25)->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->getContactsForProfessional(
            $this->professional,
            [],
            true,
            20
        );

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(20, $result->perPage());
        $this->assertEquals(25, $result->total());
    }

    /** @test */
    public function it_can_get_contacts_for_professional_without_pagination(): void
    {
        Contact::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->getContactsForProfessional(
            $this->professional,
            [],
            false
        );

        $this->assertCount(5, $result);
    }

    /** @test */
    public function it_can_filter_contacts_by_active_status(): void
    {
        Contact::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
            'is_active' => true,
        ]);

        Contact::factory()->count(2)->create([
            'professional_id' => $this->professional->id,
            'is_active' => false,
        ]);

        $result = $this->service->getContactsForProfessional(
            $this->professional,
            ['is_active' => true],
            false
        );

        $this->assertCount(3, $result);
        $result->each(function ($contact) {
            $this->assertTrue($contact->is_active);
        });
    }

    /** @test */
    public function it_can_get_a_single_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->getContact($contact->id);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertEquals($contact->id, $result->id);
    }

    /** @test */
    public function it_can_get_contact_with_relationships(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->getContact($contact->id, ['professional']);

        $this->assertTrue($result->relationLoaded('professional'));
    }

    /** @test */
    public function it_can_archive_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'is_active' => true,
        ]);

        $archived = $this->service->archiveContact($contact->id);

        $this->assertFalse($archived->is_active);
        $this->assertNotNull($archived->archived_at);
    }

    /** @test */
    public function it_can_unarchive_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'is_active' => false,
            'archived_at' => now(),
        ]);

        $unarchived = $this->service->unarchiveContact($contact->id);

        $this->assertTrue($unarchived->is_active);
        $this->assertNull($unarchived->archived_at);
    }

    /** @test */
    public function it_can_delete_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->service->deleteContact($contact->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function it_can_search_contacts(): void
    {
        Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
        ]);

        Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'María',
            'last_name' => 'García',
            'email' => 'maria@example.com',
        ]);

        $result = $this->service->searchContacts('Juan', $this->professional);

        $this->assertCount(1, $result);
        $this->assertEquals('Juan', $result->first()->first_name);
    }

    /** @test */
    public function it_can_verify_contact_ownership(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $otherProfessional = Professional::factory()->create();

        $this->assertTrue($this->service->verifyOwnership($contact->id, $this->professional));
        $this->assertFalse($this->service->verifyOwnership($contact->id, $otherProfessional));
    }

    /** @test */
    public function it_returns_false_when_contact_does_not_exist_for_ownership_check(): void
    {
        $this->assertFalse($this->service->verifyOwnership(99999, $this->professional));
    }
}


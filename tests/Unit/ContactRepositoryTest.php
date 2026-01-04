<?php

namespace Tests\Unit;

use App\Core\Contacts\Models\Contact;
use App\Core\Contacts\Repositories\ContactRepository;
use App\Core\Users\Models\Professional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ContactRepository $repository;
    private Professional $professional;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repository = new ContactRepository(new Contact());
        
        $user = \App\Models\User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_find_contact_by_id(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->repository->find($contact->id);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertEquals($contact->id, $result->id);
    }

    /** @test */
    public function it_returns_null_when_contact_not_found(): void
    {
        $result = $this->repository->find(99999);

        $this->assertNull($result);
    }

    /** @test */
    public function it_can_find_or_fail_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->repository->findOrFail($contact->id);

        $this->assertInstanceOf(Contact::class, $result);
        $this->assertEquals($contact->id, $result->id);
    }

    /** @test */
    public function it_throws_exception_when_find_or_fail_not_found(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->findOrFail(99999);
    }

    /** @test */
    public function it_can_find_all_contacts(): void
    {
        Contact::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->repository->findAll();

        $this->assertCount(5, $result);
    }

    /** @test */
    public function it_can_filter_contacts_by_professional(): void
    {
        $otherProfessional = Professional::factory()->create();

        Contact::factory()->count(3)->create([
            'professional_id' => $this->professional->id,
        ]);

        Contact::factory()->count(2)->create([
            'professional_id' => $otherProfessional->id,
        ]);

        $result = $this->repository->findAll([
            'professional_id' => $this->professional->id,
        ]);

        $this->assertCount(3, $result);
    }

    /** @test */
    public function it_can_search_contacts_by_name(): void
    {
        Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
        ]);

        Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'María',
            'last_name' => 'García',
        ]);

        $result = $this->repository->findAll([
            'professional_id' => $this->professional->id,
            'search' => 'Juan',
        ]);

        $this->assertCount(1, $result);
        $this->assertEquals('Juan', $result->first()->first_name);
    }

    /** @test */
    public function it_can_create_contact(): void
    {
        $data = [
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
        ];

        $contact = $this->repository->create($data);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('Juan', $contact->first_name);
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Juan',
        ]);
    }

    /** @test */
    public function it_can_update_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
        ]);

        $updated = $this->repository->update($contact->id, [
            'first_name' => 'Carlos',
        ]);

        $this->assertEquals('Carlos', $updated->first_name);
        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Carlos',
        ]);
    }

    /** @test */
    public function it_can_delete_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->repository->delete($contact->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function it_can_archive_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'is_active' => true,
        ]);

        $archived = $this->repository->archive($contact->id);

        $this->assertFalse($archived->is_active);
        $this->assertNotNull($archived->archived_at);
    }

    /** @test */
    public function it_can_unarchive_contact(): void
    {
        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'is_active' => false,
            'archived_at' => now(),
        ]);

        $unarchived = $this->repository->unarchive($contact->id);

        $this->assertTrue($unarchived->is_active);
        $this->assertNull($unarchived->archived_at);
    }

    /** @test */
    public function it_can_paginate_contacts(): void
    {
        Contact::factory()->count(25)->create([
            'professional_id' => $this->professional->id,
        ]);

        $result = $this->repository->paginate([
            'professional_id' => $this->professional->id,
        ], 10);

        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(25, $result->total());
    }
}


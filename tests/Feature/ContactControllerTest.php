<?php

namespace Tests\Feature;

use App\Core\Contacts\Models\Contact;
use App\Core\Subscriptions\Services\PlanLimitsService;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Professional $professional;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_list_contacts_for_authenticated_professional(): void
    {
        Sanctum::actingAs($this->user);

        Contact::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
        ]);

        $response = $this->getJson('/api/v1/contacts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'first_name', 'last_name', 'email'],
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function it_can_search_contacts(): void
    {
        Sanctum::actingAs($this->user);

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

        $response = $this->getJson('/api/v1/contacts?search=Juan');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Juan', $response->json('data.0.first_name'));
    }

    /** @test */
    public function it_can_create_a_contact(): void
    {
        Sanctum::actingAs($this->user);

        // Mock plan limits to allow adding patient
        $this->mock(PlanLimitsService::class, function ($mock) {
            $mock->shouldReceive('canAddPatient')
                ->andReturn(true);
            $mock->shouldReceive('getUsageStats')
                ->andReturn([
                    'total_patients' => 0,
                    'patient_limit' => 100,
                ]);
        });

        $data = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
            'phone' => '123456789',
        ];

        $response = $this->postJson('/api/v1/contacts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'first_name', 'last_name', 'email'],
            ]);

        $this->assertDatabaseHas('contacts', [
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
        ]);
    }

    /** @test */
    public function it_prevents_creating_contact_when_limit_reached(): void
    {
        Sanctum::actingAs($this->user);

        // Mock plan limits to deny adding patient
        $this->mock(PlanLimitsService::class, function ($mock) {
            $mock->shouldReceive('canAddPatient')
                ->andReturn(false);
            $mock->shouldReceive('getUsageStats')
                ->andReturn([
                    'total_patients' => 10,
                    'patient_limit' => 10,
                ]);
        });

        $data = [
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan@example.com',
        ];

        $response = $this->postJson('/api/v1/contacts', $data);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'upgrade_required' => true,
            ]);
    }

    /** @test */
    public function it_can_show_a_contact(): void
    {
        Sanctum::actingAs($this->user);

        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'first_name', 'last_name', 'email'],
            ]);

        $this->assertEquals($contact->id, $response->json('data.id'));
    }

    /** @test */
    public function it_returns_404_for_other_professional_contact(): void
    {
        Sanctum::actingAs($this->user);

        $otherProfessional = Professional::factory()->create();
        $contact = Contact::factory()->create([
            'professional_id' => $otherProfessional->id,
        ]);

        $response = $this->getJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_a_contact(): void
    {
        Sanctum::actingAs($this->user);

        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'first_name' => 'Juan',
        ]);

        $response = $this->putJson("/api/v1/contacts/{$contact->id}", [
            'first_name' => 'Carlos',
            'last_name' => $contact->last_name,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Paciente actualizado exitosamente',
            ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Carlos',
        ]);
    }

    /** @test */
    public function it_can_archive_a_contact(): void
    {
        Sanctum::actingAs($this->user);

        $contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/v1/contacts/{$contact->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Paciente archivado exitosamente',
            ]);

        $this->assertFalse($contact->fresh()->is_active);
        $this->assertNotNull($contact->fresh()->archived_at);
    }

    /** @test */
    public function it_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/contacts');

        $response->assertStatus(401);
    }
}


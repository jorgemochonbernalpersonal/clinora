<?php

namespace Tests\Feature;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Modules\Psychology\ConsentForms\Models\ConsentForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfessionalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $professional1;
    private User $professional2;
    private User $admin;
    private Professional $prof1;
    private Professional $prof2;
    private Contact $contact1;
    private Contact $contact2;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles
        Role::create(['name' => 'professional']);
        Role::create(['name' => 'admin']);

        // Professional 1
        $this->professional1 = User::factory()->create();
        $this->prof1 = Professional::factory()->create([
            'user_id' => $this->professional1->id,
        ]);
        $this->professional1->assignRole('professional');

        // Professional 2
        $this->professional2 = User::factory()->create();
        $this->prof2 = Professional::factory()->create([
            'user_id' => $this->professional2->id,
        ]);
        $this->professional2->assignRole('professional');

        // Admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Contactos
        $this->contact1 = Contact::factory()->create([
            'professional_id' => $this->prof1->id,
        ]);

        $this->contact2 = Contact::factory()->create([
            'professional_id' => $this->prof2->id,
        ]);
    }

    /** @test */
    public function professional_can_only_view_own_contacts(): void
    {
        Sanctum::actingAs($this->professional1);

        $response = $this->getJson('/api/v1/contacts');

        $response->assertStatus(200);

        $contactIds = collect($response->json('data'))->pluck('id')->toArray();

        // Solo debe ver contact1
        $this->assertContains($this->contact1->id, $contactIds);
        $this->assertNotContains($this->contact2->id, $contactIds);
    }

    /** @test */
    public function professional_cannot_view_other_professional_contact(): void
    {
        Sanctum::actingAs($this->professional1);

        // Intentar ver el contacto de professional2
        $response = $this->getJson("/api/v1/contacts/{$this->contact2->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function professional_cannot_create_contact_for_another_professional(): void
    {
        Sanctum::actingAs($this->professional1);

        $data = [
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'email' => 'test@example.com',
            'professional_id' => $this->prof2->id, // Intentar asignar a otro professional
        ];

        $response = $this->postJson('/api/v1/contacts', $data);

        // El sistema debe ignorar el professional_id enviado y usar el del usuario autenticado
        $response->assertStatus(201);

        $contact = Contact::latest()->first();
        $this->assertEquals($this->prof1->id, $contact->professional_id);
    }

    /** @test */
    public function professional_can_only_view_own_appointments(): void
    {
        $appointment1 = Appointment::factory()->create([
            'professional_id' => $this->prof1->id,
            'contact_id' => $this->contact1->id,
        ]);

        $appointment2 = Appointment::factory()->create([
            'professional_id' => $this->prof2->id,
            'contact_id' => $this->contact2->id,
        ]);

        Sanctum::actingAs($this->professional1);

        $response = $this->getJson('/api/v1/appointments');

        $response->assertStatus(200);

        $appointmentIds = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertContains($appointment1->id, $appointmentIds);
        $this->assertNotContains($appointment2->id, $appointmentIds);
    }

    /** @test */
    public function professional_cannot_view_other_professional_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->prof2->id,
            'contact_id' => $this->contact2->id,
        ]);

        Sanctum::actingAs($this->professional1);

        $response = $this->getJson("/api/v1/appointments/{$appointment->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function professional_can_only_view_own_clinical_notes(): void
    {
        $note1 = ClinicalNote::factory()->create([
            'professional_id' => $this->prof1->id,
            'contact_id' => $this->contact1->id,
        ]);

        $note2 = ClinicalNote::factory()->create([
            'professional_id' => $this->prof2->id,
            'contact_id' => $this->contact2->id,
        ]);

        Sanctum::actingAs($this->professional1);

        $response = $this->getJson('/api/v1/clinical-notes');

        $response->assertStatus(200);

        $noteIds = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertContains($note1->id, $noteIds);
        $this->assertNotContains($note2->id, $noteIds);
    }

    /** @test */
    public function professional_cannot_view_other_professional_clinical_note(): void
    {
        $note = ClinicalNote::factory()->create([
            'professional_id' => $this->prof2->id,
            'contact_id' => $this->contact2->id,
        ]);

        Sanctum::actingAs($this->professional1);

        $response = $this->getJson("/api/v1/clinical-notes/{$note->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function professional_can_only_view_own_consent_forms(): void
    {
        $consent1 = ConsentForm::factory()->create([
            'professional_id' => $this->prof1->id,
            'contact_id' => $this->contact1->id,
        ]);

        $consent2 = ConsentForm::factory()->create([
            'professional_id' => $this->prof2->id,
            'contact_id' => $this->contact2->id,
        ]);

        Sanctum::actingAs($this->professional1);

        $response = $this->getJson('/api/v1/consent-forms');

        $response->assertStatus(200);

        $consentIds = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertContains($consent1->id, $consentIds);
        $this->assertNotContains($consent2->id, $consentIds);
    }

    /** @test */
    public function professional_cannot_update_other_professional_contact(): void
    {
        Sanctum::actingAs($this->professional1);

        $response = $this->putJson("/api/v1/contacts/{$this->contact2->id}", [
            'first_name' => 'Updated Name',
        ]);

        $response->assertStatus(404);

        // Verificar que no se actualizó
        $this->contact2->refresh();
        $this->assertNotEquals('Updated Name', $this->contact2->first_name);
    }

    /** @test */
    public function professional_cannot_delete_other_professional_contact(): void
    {
        Sanctum::actingAs($this->professional1);

        $response = $this->deleteJson("/api/v1/contacts/{$this->contact2->id}");

        $response->assertStatus(404);

        // Verificar que no se eliminó
        $this->assertDatabaseHas('contacts', [
            'id' => $this->contact2->id,
        ]);
    }

    /** @test */
    public function admin_can_view_all_professionals_data(): void
    {
        Sanctum::actingAs($this->admin);

        // Admin puede ver todos los contactos
        $response = $this->getJson('/api/v1/admin/contacts');

        $response->assertStatus(200);

        $contactIds = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertContains($this->contact1->id, $contactIds);
        $this->assertContains($this->contact2->id, $contactIds);
    }

    /** @test */
    public function admin_can_view_any_professional_appointment(): void
    {
        $appointment = Appointment::factory()->create([
            'professional_id' => $this->prof1->id,
            'contact_id' => $this->contact1->id,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/v1/admin/appointments/{$appointment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /** @test */
    public function admin_can_manage_professionals(): void
    {
        Sanctum::actingAs($this->admin);

        // Ver lista de professionals
        $response = $this->getJson('/api/v1/admin/professionals');

        $response->assertStatus(200);

        $professionalIds = collect($response->json('data'))->pluck('id')->toArray();

        $this->assertContains($this->prof1->id, $professionalIds);
        $this->assertContains($this->prof2->id, $professionalIds);
    }

    /** @test */
    public function non_admin_cannot_access_admin_endpoints(): void
    {
        Sanctum::actingAs($this->professional1);

        $response = $this->getJson('/api/v1/admin/professionals');

        $response->assertStatus(403); // Forbidden
    }
}

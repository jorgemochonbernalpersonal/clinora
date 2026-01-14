<?php

namespace Tests\Feature;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Modules\Psychology\ConsentForms\Models\ConsentForm;
use App\Models\User;
use App\Shared\Enums\ConsentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConsentFormControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Professional $professional;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);
    }

    /** @test */
    public function it_can_create_consent_form(): void
    {
        Sanctum::actingAs($this->user);

        $data = [
            'contact_id' => $this->contact->id,
            'consent_type' => 'informed_consent',
            'content' => 'Test consent form content',
        ];

        $response = $this->postJson('/api/v1/consent-forms', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'consent_type', 'contact_id', 'professional_id'],
            ]);

        $this->assertDatabaseHas('consent_forms', [
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'consent_type' => 'informed_consent',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_creation(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/consent-forms', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['contact_id', 'consent_type']);
    }

    /** @test */
    public function it_can_list_consent_forms_for_professional(): void
    {
        Sanctum::actingAs($this->user);

        ConsentForm::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $response = $this->getJson('/api/v1/consent-forms');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'consent_type', 'signed_at', 'is_valid'],
                ],
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function it_can_generate_signed_url_for_public_signing(): void
    {
        Sanctum::actingAs($this->user);

        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $response = $this->getJson("/api/v1/consent-forms/{$consentForm->id}/public-link");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['public_url', 'expires_at'],
            ]);

        $publicUrl = $response->json('data.public_url');
        $this->assertStringContainsString('consent-forms', $publicUrl);
        $this->assertStringContainsString('signature=', $publicUrl);
    }

    /** @test */
    public function it_can_sign_consent_form_with_valid_signed_url(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        // Generar signed URL
        $signedUrl = URL::temporarySignedRoute(
            'consent-forms.public.sign',
            now()->addHours(24),
            ['consentForm' => $consentForm->id]
        );

        $signatureData = [
            'signature_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'signed_by_name' => $this->contact->first_name . ' ' . $this->contact->last_name,
        ];

        $response = $this->postJson($signedUrl, $signatureData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Consentimiento firmado exitosamente',
            ]);

        $consentForm->refresh();
        $this->assertNotNull($consentForm->signed_at);
        $this->assertNotNull($consentForm->signature_data);
        $this->assertEquals($this->contact->full_name, $consentForm->signed_by_name);
    }

    /** @test */
    public function it_requires_valid_signature_for_signing(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'consent-forms.public.sign',
            now()->addHours(24),
            ['consentForm' => $consentForm->id]
        );

        $response = $this->postJson($signedUrl, [
            'signature_data' => '', // Invalid: empty
            'signed_by_name' => $this->contact->full_name,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['signature_data']);
    }

    /** @test */
    public function it_prevents_signing_already_signed_consent_form(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => now(),
            'signature_data' => 'existing_signature',
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'consent-forms.public.sign',
            now()->addHours(24),
            ['consentForm' => $consentForm->id]
        );

        $response = $this->postJson($signedUrl, [
            'signature_data' => 'new_signature',
            'signed_by_name' => $this->contact->full_name,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Este consentimiento ya ha sido firmado',
            ]);
    }

    /** @test */
    public function it_rejects_expired_signed_url(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        // URL expirada (pasado)
        $expiredUrl = URL::temporarySignedRoute(
            'consent-forms.public.sign',
            now()->subHours(1), // Expiró hace 1 hora
            ['consentForm' => $consentForm->id]
        );

        $response = $this->postJson($expiredUrl, [
            'signature_data' => 'test_signature',
            'signed_by_name' => $this->contact->full_name,
        ]);

        $response->assertStatus(403); // Forbidden - Invalid signature
    }

    /** @test */
    public function it_can_send_consent_form_by_email(): void
    {
        Mail::fake();
        Sanctum::actingAs($this->user);

        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $response = $this->postJson("/api/v1/consent-forms/{$consentForm->id}/send-email");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Email enviado exitosamente',
            ]);

        Mail::assertSent(\App\Mail\ConsentFormInvitation::class, function ($mail) use ($consentForm) {
            return $mail->hasTo($this->contact->email);
        });
    }

    /** @test */
    public function it_prevents_accessing_other_professional_consent_forms(): void
    {
        Sanctum::actingAs($this->user);

        $otherProfessional = Professional::factory()->create();
        $otherContact = Contact::factory()->create([
            'professional_id' => $otherProfessional->id,
        ]);

        $otherConsentForm = ConsentForm::factory()->create([
            'professional_id' => $otherProfessional->id,
            'contact_id' => $otherContact->id,
        ]);

        $response = $this->getJson("/api/v1/consent-forms/{$otherConsentForm->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_stores_signing_metadata(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'consent-forms.public.sign',
            now()->addHours(24),
            ['consentForm' => $consentForm->id]
        );

        $response = $this->postJson($signedUrl, [
            'signature_data' => 'test_signature_data',
            'signed_by_name' => $this->contact->full_name,
        ], [
            'User-Agent' => 'Mozilla/5.0 Test Browser',
            'X-Forwarded-For' => '192.168.1.1',
        ]);

        $response->assertStatus(200);

        $consentForm->refresh();
        $this->assertNotNull($consentForm->ip_address);
        $this->assertNotNull($consentForm->user_agent);
        $this->assertStringContainsString('Mozilla', $consentForm->user_agent);
    }

    /** @test */
    public function it_requires_authentication_for_professional_endpoints(): void
    {
        $response = $this->getJson('/api/v1/consent-forms');

        $response->assertStatus(401);
    }
}

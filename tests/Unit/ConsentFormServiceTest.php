<?php

namespace Tests\Unit;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Modules\Psychology\ConsentForms\Models\ConsentForm;
use App\Modules\Psychology\ConsentForms\Services\ConsentFormService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ConsentFormServiceTest extends TestCase
{
    use RefreshDatabase;

    private ConsentFormService $service;
    private Professional $professional;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ConsentFormService::class);

        $user = User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        Storage::fake('public');
    }

    /** @test */
    public function it_creates_consent_form_with_valid_data(): void
    {
        $data = [
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'consent_type' => 'informed_consent',
            'content' => 'Test consent content',
        ];

        $consentForm = $this->service->create($data);

        $this->assertInstanceOf(ConsentForm::class, $consentForm);
        $this->assertEquals($this->professional->id, $consentForm->professional_id);
        $this->assertEquals($this->contact->id, $consentForm->contact_id);
        $this->assertEquals('informed_consent', $consentForm->consent_type);
        $this->assertNull($consentForm->signed_at);
    }

    /** @test */
    public function it_generates_valid_signed_url(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $signedUrl = $this->service->generatePublicSigningUrl($consentForm);

        $this->assertNotNull($signedUrl);
        $this->assertStringContainsString('consent-forms', $signedUrl);
        $this->assertStringContainsString('signature=', $signedUrl);
        $this->assertStringContainsString('expires=', $signedUrl);

        // Verificar que la URL es válida
        $this->assertTrue(URL::hasValidSignature($signedUrl));
    }

    /** @test */
    public function it_processes_signature_correctly(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        $signedByName = $this->contact->full_name;
        $ipAddress = '192.168.1.1';
        $userAgent = 'Mozilla/5.0 Test Browser';

        $result = $this->service->processSignature(
            $consentForm,
            $signatureData,
            $signedByName,
            $ipAddress,
            $userAgent
        );

        $this->assertTrue($result);

        $consentForm->refresh();
        $this->assertNotNull($consentForm->signed_at);
        $this->assertEquals($signatureData, $consentForm->signature_data);
        $this->assertEquals($signedByName, $consentForm->signed_by_name);
        $this->assertEquals($ipAddress, $consentForm->ip_address);
        $this->assertEquals($userAgent, $consentForm->user_agent);
        $this->assertTrue($consentForm->is_valid);
    }

    /** @test */
    public function it_rejects_signing_already_signed_consent_form(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => now(),
            'signature_data' => 'existing_signature',
        ]);

        $result = $this->service->processSignature(
            $consentForm,
            'new_signature',
            $this->contact->full_name,
            '192.168.1.1',
            'Test Browser'
        );

        $this->assertFalse($result);

        $consentForm->refresh();
        $this->assertEquals('existing_signature', $consentForm->signature_data);
    }

    /** @test */
    public function it_generates_pdf_after_signing(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $this->service->processSignature(
            $consentForm,
            'signature_data',
            $this->contact->full_name,
            '192.168.1.1',
            'Test Browser'
        );

        // Generar PDF
        $pdfPath = $this->service->generatePdf($consentForm);

        $this->assertNotNull($pdfPath);
        $this->assertStringContainsString('.pdf', $pdfPath);

        $consentForm->refresh();
        $this->assertEquals($pdfPath, $consentForm->pdf_path);
    }

    /** @test */
    public function it_sends_consent_form_by_email(): void
    {
        Mail::fake();

        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => null,
        ]);

        $this->service->sendByEmail($consentForm);

        Mail::assertSent(\App\Mail\ConsentFormInvitation::class, function ($mail) use ($consentForm) {
            return $mail->hasTo($this->contact->email) &&
                   $mail->consentForm->id === $consentForm->id;
        });
    }

    /** @test */
    public function it_validates_signature_data_format(): void
    {
        $this->assertTrue($this->service->isValidSignatureData('data:image/png;base64,abc123'));
        $this->assertTrue($this->service->isValidSignatureData('data:image/jpeg;base64,xyz789'));
        
        $this->assertFalse($this->service->isValidSignatureData('invalid_signature'));
        $this->assertFalse($this->service->isValidSignatureData(''));
        $this->assertFalse($this->service->isValidSignatureData(null));
    }

    /** @test */
    public function it_can_invalidate_consent_form(): void
    {
        $consentForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'signed_at' => now(),
            'is_valid' => true,
        ]);

        $this->service->invalidate($consentForm, 'Revocado por el paciente');

        $consentForm->refresh();
        $this->assertFalse($consentForm->is_valid);
        $this->assertNotNull($consentForm->invalidated_at);
        $this->assertEquals('Revocado por el paciente', $consentForm->invalidation_reason);
    }

    /** @test */
    public function it_lists_consent_forms_for_professional(): void
    {
        ConsentForm::factory()->count(5)->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $otherProfessional = Professional::factory()->create();
        ConsentForm::factory()->count(3)->create([
            'professional_id' => $otherProfessional->id,
        ]);

        $forms = $this->service->getForProfessional($this->professional->id);

        $this->assertCount(5, $forms);
        $this->assertTrue($forms->every(fn($form) => $form->professional_id === $this->professional->id));
    }

    /** @test */
    public function it_can_duplicate_consent_form(): void
    {
        $originalForm = ConsentForm::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
            'content' => 'Original content',
            'signed_at' => now(),
        ]);

        $duplicatedForm = $this->service->duplicate($originalForm);

        $this->assertInstanceOf(ConsentForm::class, $duplicatedForm);
        $this->assertNotEquals($originalForm->id, $duplicatedForm->id);
        $this->assertEquals($originalForm->professional_id, $duplicatedForm->professional_id);
        $this->assertEquals($originalForm->contact_id, $duplicatedForm->contact_id);
        $this->assertEquals($originalForm->content, $duplicatedForm->content);
        
        // El duplicado no debe estar firmado
        $this->assertNull($duplicatedForm->signed_at);
        $this->assertNull($duplicatedForm->signature_data);
    }
}

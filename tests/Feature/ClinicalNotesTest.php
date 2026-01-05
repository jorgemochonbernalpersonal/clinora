<?php

namespace Tests\Feature;

use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalNotesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that we can create clinical notes
     */
    public function test_can_create_clinical_note(): void
    {
        $this->seedTestData();

        $professional = Professional::first();
        $contact = Contact::first();

        $note = ClinicalNote::create([
            'professional_id' => $professional->id,
            'contact_id' => $contact->id,
            'session_number' => 1,
            'session_date' => now(),
            'duration_minutes' => 60,
            'subjective' => 'El paciente reporta...',
            'objective' => 'Observaciones...',
            'assessment' => 'Evaluación...',
            'plan' => 'Plan de tratamiento...',
            'risk_assessment' => 'sin_riesgo',
        ]);

        $this->assertDatabaseHas('clinical_notes', [
            'id' => $note->id,
            'session_number' => 1,
            'risk_assessment' => 'sin_riesgo',
        ]);
    }

    /**
     * Test that clinical notes belong to professional and contact
     */
    public function test_clinical_note_relationships(): void
    {
        $this->seedTestData();

        $note = ClinicalNote::first();

        $this->assertInstanceOf(Professional::class, $note->professional);
        $this->assertInstanceOf(Contact::class, $note->contact);
    }

    /**
     * Test that we have 5 clinical notes after seeding
     */
    public function test_seeder_creates_five_clinical_notes(): void
    {
        $this->seedTestData();

        $this->assertDatabaseCount('clinical_notes', 5);
    }

    /**
     * Test that clinical notes can be signed
     */
    public function test_clinical_note_can_be_signed(): void
    {
        $this->seedTestData();

        $note = ClinicalNote::first();
        
        $this->assertFalse($note->is_signed);
        
        $note->update([
            'is_signed' => true,
            'signed_at' => now(),
        ]);

        $this->assertTrue($note->fresh()->is_signed);
        $this->assertNotNull($note->fresh()->signed_at);
    }
}


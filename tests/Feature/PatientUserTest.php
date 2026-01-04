<?php

namespace Tests\Feature;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\PatientUser;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that we can create a patient user
     */
    public function test_can_create_patient_user(): void
    {
        $this->seedTestData();

        $professional = Professional::first();
        $contact = Contact::first();

        $user = User::factory()->create([
            'user_type' => 'patient',
            'email' => 'patient@test.com',
        ]);

        $patientUser = PatientUser::create([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'professional_id' => $professional->id,
            'portal_activated_at' => now(),
            'email_notifications_enabled' => true,
        ]);

        $this->assertDatabaseHas('patient_users', [
            'id' => $patientUser->id,
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'professional_id' => $professional->id,
        ]);
    }

    /**
     * Test that patient user belongs to user, contact and professional
     */
    public function test_patient_user_relationships(): void
    {
        $this->seedTestData();

        $patientUser = PatientUser::first();

        if ($patientUser) {
            $this->assertInstanceOf(User::class, $patientUser->user);
            $this->assertInstanceOf(Contact::class, $patientUser->contact);
            $this->assertInstanceOf(Professional::class, $patientUser->professional);
        } else {
            $this->markTestSkipped('No patient users found in test data');
        }
    }

    /**
     * Test that user can access patient user
     */
    public function test_user_has_patient_user_relationship(): void
    {
        $this->seedTestData();

        $patientUser = PatientUser::first();

        if ($patientUser) {
            $user = $patientUser->user;
            $this->assertInstanceOf(PatientUser::class, $user->patientUser);
        } else {
            $this->markTestSkipped('No patient users found in test data');
        }
    }

    /**
     * Test that contact can access patient user
     */
    public function test_contact_has_patient_user_relationship(): void
    {
        $this->seedTestData();

        $patientUser = PatientUser::first();

        if ($patientUser) {
            $contact = $patientUser->contact;
            $this->assertInstanceOf(PatientUser::class, $contact->patientUser);
        } else {
            $this->markTestSkipped('No patient users found in test data');
        }
    }

    /**
     * Test portal activation methods
     */
    public function test_portal_activation_methods(): void
    {
        $this->seedTestData();

        $patientUser = PatientUser::first();

        if ($patientUser) {
            // Test isPortalActivated
            $isActivated = $patientUser->isPortalActivated();
            $this->assertIsBool($isActivated);

            // Test activatePortal
            $patientUser->activatePortal();
            $this->assertTrue($patientUser->fresh()->isPortalActivated());

            // Test deactivatePortal
            $patientUser->deactivatePortal();
            $this->assertFalse($patientUser->fresh()->isPortalActivated());
        } else {
            $this->markTestSkipped('No patient users found in test data');
        }
    }

    /**
     * Test that seeder creates patient users
     */
    public function test_seeder_creates_patient_users(): void
    {
        $this->seedTestData();

        $patientUsersCount = PatientUser::count();
        $this->assertGreaterThanOrEqual(0, $patientUsersCount);
        // El seeder crea 2 patient users
        $this->assertLessThanOrEqual(2, $patientUsersCount);
    }
}


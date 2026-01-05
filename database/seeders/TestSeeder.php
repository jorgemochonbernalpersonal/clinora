<?php

namespace Database\Seeders;

use App\Core\Appointments\Models\Appointment;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\PatientUser;
use App\Core\Users\Models\Professional;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $professionalRole = Role::firstOrCreate(['name' => 'professional']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $patientRole = Role::firstOrCreate(['name' => 'patient']);

        // Crear 1 usuario profesional con perfil
        $professionalUser = User::factory()->professional()->create([
            'email' => 'test.professional@clinora.test',
            'first_name' => 'Dr. Test',
            'last_name' => 'Profesional',
            'email_verified_at' => now(),
        ]);

        $professional = Professional::factory()->create([
            'user_id' => $professionalUser->id,
            'profession' => 'Psicólogo',
            'subscription_plan' => \App\Shared\Enums\SubscriptionPlan::PRO,
        ]);

        $professionalUser->assignRole($professionalRole);

        // Crear 5 pacientes (contacts)
        $contacts = Contact::factory()->count(5)->create([
            'professional_id' => $professional->id,
        ]);

        // Crear 2 usuarios de pacientes con acceso al portal (PatientUser)
        // Solo los primeros 2 contactos tendrán acceso al portal
        $patientUsers = collect();
        foreach ($contacts->take(2) as $index => $contact) {
            $patientNumber = $index + 1;
            $patientUser = User::factory()->create([
                'email' => "test.patient{$patientNumber}@clinora.test",
                'first_name' => $contact->first_name,
                'last_name' => $contact->last_name,
                'user_type' => 'patient',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);

            $patientUser->assignRole($patientRole);

            $patientUserRecord = PatientUser::factory()->create([
                'user_id' => $patientUser->id,
                'contact_id' => $contact->id,
                'professional_id' => $professional->id,
                'portal_activated_at' => now(), // Portal activado
                'email_notifications_enabled' => true,
            ]);

            $patientUsers->push($patientUserRecord);
        }

        // Crear 5 citas (appointments) - algunas completadas, algunas programadas
        $appointments = collect();
        
        // 3 citas completadas
        $completedAppointments = Appointment::factory()->count(3)->completed()->create([
            'professional_id' => $professional->id,
            'contact_id' => fn() => $contacts->random(),
        ]);
        $appointments = $appointments->merge($completedAppointments);

        // 2 citas programadas
        $scheduledAppointments = Appointment::factory()->count(2)->scheduled()->create([
            'professional_id' => $professional->id,
            'contact_id' => fn() => $contacts->random(),
        ]);
        $appointments = $appointments->merge($scheduledAppointments);

        // Crear 5 notas clínicas
        ClinicalNote::factory()->count(5)->create([
            'professional_id' => $professional->id,
            'contact_id' => fn() => $contacts->random(),
            'appointment_id' => fn() => $appointments->random(),
        ]);

        $this->command->info('✅ Datos de prueba creados:');
        $this->command->info("   - 1 Profesional: {$professionalUser->email}");
        $this->command->info("   - 5 Pacientes (Contacts)");
        $this->command->info("   - 2 Usuarios de Portal (PatientUser) con acceso:");
        foreach ($patientUsers as $index => $pu) {
            $patientNumber = $index + 1;
            $this->command->info("      • test.patient{$patientNumber}@clinora.test (password: password)");
        }
        $this->command->info("   - 5 Citas (Appointments)");
        $this->command->info("   - 5 Notas Clínicas (ClinicalNotes)");
    }
}


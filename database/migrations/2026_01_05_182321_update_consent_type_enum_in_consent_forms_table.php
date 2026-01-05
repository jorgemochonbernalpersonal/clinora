<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Para PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE consent_forms DROP CONSTRAINT IF EXISTS consent_forms_consent_type_check");
            DB::statement("ALTER TABLE consent_forms ADD CONSTRAINT consent_forms_consent_type_check CHECK (consent_type IN ('initial_treatment', 'teleconsultation', 'minors', 'recording', 'research', 'third_party_communication', 'medication_referral', 'group_therapy', 'emdr', 'hypnosis', 'couple_therapy'))");
        }
        
        // Para MySQL/MariaDB - necesitamos recrear la columna
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE consent_forms MODIFY COLUMN consent_type ENUM(
                'initial_treatment',
                'teleconsultation',
                'minors',
                'recording',
                'research',
                'third_party_communication',
                'medication_referral',
                'group_therapy',
                'emdr',
                'hypnosis',
                'couple_therapy'
            ) NOT NULL DEFAULT 'initial_treatment'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Para PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE consent_forms DROP CONSTRAINT IF EXISTS consent_forms_consent_type_check");
            DB::statement("ALTER TABLE consent_forms ADD CONSTRAINT consent_forms_consent_type_check CHECK (consent_type IN ('initial_treatment', 'teleconsultation', 'minors', 'recording', 'research', 'third_party_communication', 'medication_referral'))");
        }
        
        // Para MySQL/MariaDB
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE consent_forms MODIFY COLUMN consent_type ENUM(
                'initial_treatment',
                'teleconsultation',
                'minors',
                'recording',
                'research',
                'third_party_communication',
                'medication_referral'
            ) NOT NULL DEFAULT 'initial_treatment'");
        }
    }
};

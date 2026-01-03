<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Identificación legal (importante para facturación)
            $table->string('dni')->nullable()->after('last_name');
            $table->index('dni');
            
            // Información clínica inicial
            $table->text('initial_consultation_reason')->nullable()->after('notes');
            $table->date('first_appointment_date')->nullable()->after('initial_consultation_reason');
            
            // Antecedentes médicos
            $table->text('medical_history')->nullable()->after('first_appointment_date');
            $table->text('psychiatric_history')->nullable()->after('medical_history');
            $table->text('current_medication')->nullable()->after('psychiatric_history');
            
            // Información socioeconómica
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'cohabiting', 'other'])->nullable()->after('gender');
            $table->string('occupation')->nullable()->after('marital_status');
            $table->enum('education_level', ['primary', 'secondary', 'vocational', 'university', 'postgraduate', 'other'])->nullable()->after('occupation');
            
            // Seguro médico / Mutua (para facturación)
            $table->string('insurance_company')->nullable()->after('education_level');
            $table->string('insurance_policy_number')->nullable()->after('insurance_company');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['dni']);
            $table->dropColumn([
                'dni',
                'initial_consultation_reason',
                'first_appointment_date',
                'medical_history',
                'psychiatric_history',
                'current_medication',
                'marital_status',
                'occupation',
                'education_level',
                'insurance_company',
                'insurance_policy_number',
            ]);
        });
    }
};

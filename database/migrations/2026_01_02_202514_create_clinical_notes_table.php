<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            
            // Información de sesión
            $table->integer('session_number');
            $table->date('session_date');
            $table->integer('duration_minutes');
            
            // Formato SOAP
            $table->text('subjective')->nullable();  // S: Lo que dice el paciente
            $table->text('objective')->nullable();   // O: Observaciones del terapeuta
            $table->text('assessment')->nullable();  // A: Análisis clínico
            $table->text('plan')->nullable();        // P: Plan de intervención
            
            // Adicionales
            $table->json('interventions_used')->nullable();  // Técnicas usadas en sesión
            $table->text('homework')->nullable();            // Tareas para el paciente
            
            // Evaluación de riesgo
            $table->enum('risk_assessment', ['sin_riesgo', 'riesgo_bajo', 'riesgo_moderado', 'riesgo_alto', 'riesgo_inminente'])->default('sin_riesgo');
            $table->text('risk_details')->nullable();
            
            // Progreso
            $table->integer('progress_rating')->nullable()->comment('Rating 1-10');
            
            // Firma
            $table->boolean('is_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('professional_id');
            $table->index('contact_id');
            $table->index('appointment_id');
            $table->index('session_date');
            $table->index(['contact_id', 'session_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_notes');
    }
};

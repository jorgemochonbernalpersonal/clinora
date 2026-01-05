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
        Schema::create('consent_forms', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            
            // Tipo de consentimiento
            $table->enum('consent_type', [
                'initial_treatment',           // Consentimiento inicial de tratamiento
                'teleconsultation',            // Para sesiones online
                'minors',                      // Consentimiento parental para menores
                'recording',                   // Grabación de sesiones
                'research',                    // Participación en investigación
                'third_party_communication',   // Comunicación con otros profesionales
                'medication_referral'          // Derivación a psiquiatría
            ])->default('initial_treatment');
            
            // Contenido del consentimiento
            $table->string('consent_title')->nullable();
            $table->longText('consent_text'); // Documento completo del consentimiento
            
            // Información específica para menores
            $table->string('legal_guardian_name')->nullable();
            $table->string('legal_guardian_relationship')->nullable(); // padre, madre, tutor, etc.
            $table->string('legal_guardian_id_document', 50)->nullable(); // DNI/NIE del tutor
            $table->boolean('minor_assent')->default(false); // Asentimiento del menor (si >12 años)
            $table->text('minor_assent_details')->nullable();
            
            // Firma electrónica del paciente
            $table->text('patient_signature_data')->nullable(); // Base64 de la firma
            $table->string('patient_ip_address', 45)->nullable();
            $table->text('patient_device_info')->nullable(); // User agent, dispositivo, etc.
            
            // Firma del tutor (para menores)
            $table->text('guardian_signature_data')->nullable();
            
            // Testigos (si aplica)
            $table->string('witness_name')->nullable();
            $table->text('witness_signature_data')->nullable();
            
            // Validación
            $table->timestamp('signed_at')->nullable();
            $table->boolean('is_valid')->default(false);
            
            // Revocación
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();
            
            // Versión del documento (control de versiones del template)
            $table->string('document_version', 20)->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('professional_id');
            $table->index('contact_id');
            $table->index('consent_type');
            $table->index('is_valid');
            $table->index('signed_at');
            $table->index(['contact_id', 'consent_type', 'is_valid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_forms');
    }
};

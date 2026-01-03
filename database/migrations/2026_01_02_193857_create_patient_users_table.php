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
        Schema::create('patient_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Relación con contact (paciente) y professional
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            
            // Configuración del portal del paciente
            $table->timestamp('portal_activated_at')->nullable();
            $table->boolean('email_notifications_enabled')->default(true);
            $table->boolean('sms_notifications_enabled')->default(false);
            
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index('contact_id');
            $table->index('professional_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_users');
    }
};

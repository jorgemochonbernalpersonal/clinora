<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            
            // Datos personales
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            
            // Dirección
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->default('España');
            
            // Contacto de emergencia
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Metadatos
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('archived_at')->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('professional_id');
            $table->index('email');
            $table->index('is_active');
            $table->index(['first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

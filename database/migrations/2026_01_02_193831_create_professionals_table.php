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
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            
            // Información del profesional
            $table->string('name');
            $table->string('license_number')->nullable();
            
            // Profesión (ENUM solo con psychology por ahora)
            $table->enum('profession', ['psychology'])->default('psychology');
            
            // Especialidades (JSON para flexibilidad)
            $table->json('specialties')->nullable();
            
            // Información de contacto
            $table->string('phone')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_country')->default('España');
            
            // Configuración profesional
            $table->string('timezone')->default('Europe/Madrid');
            $table->string('currency', 3)->default('EUR');
            $table->string('language', 2)->default('es');
            
            // Facturación
            $table->string('invoice_series')->default('A');
            
            // Suscripción
            $table->enum('subscription_plan', ['gratis', 'pro', 'equipo'])->default('gratis');
            $table->enum('subscription_status', ['active', 'cancelled', 'expired'])
                  ->default('active');
            
            $table->timestamps();
            
            // Índices
            $table->index('user_id');
            $table->index('profession');
            $table->index('license_number');
            $table->index('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Autenticación básica
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // Información personal
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            
            // Tipo de usuario
            $table->enum('user_type', ['professional', 'patient', 'assistant', 'admin'])
                  ->default('professional');
            
            // Seguridad
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->json('two_factor_recovery_codes')->nullable();
            
            // Estado de la cuenta
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            
            // Preferencias
            $table->string('language', 2)->default('es');
            $table->string('timezone')->default('Europe/Madrid');
            
            // Soft deletes para auditoría
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('email');
            $table->index('user_type');
            $table->index('is_active');
            $table->index('last_login_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->index('token'); // Para búsquedas más rápidas
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

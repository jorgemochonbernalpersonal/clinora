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
        Schema::table('users', function (Blueprint $table) {
            // Si existe 'name', migrar datos antes de agregar nuevas columnas
            if (Schema::hasColumn('users', 'name')) {
                // Dividir 'name' en first_name y last_name si es posible
                // Por ahora, solo agregamos las nuevas columnas
            }

            // Agregar first_name y last_name si no existen
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }

            // Agregar phone si no existe
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('last_name');
            }

            // Agregar user_type si no existe
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['professional', 'patient', 'assistant', 'admin'])
                      ->default('professional')
                      ->after('phone');
            }

            // Agregar campos de seguridad (two factor)
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('user_type');
            }
            
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            }
            
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->json('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            }

            // Agregar campos de estado
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('two_factor_recovery_codes');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable()->after('last_login_at');
            }
            
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable()->after('last_login_ip');
            }

            // Agregar preferencias
            if (!Schema::hasColumn('users', 'language')) {
                $table->string('language', 2)->default('es')->after('password_changed_at');
            }
            
            if (!Schema::hasColumn('users', 'timezone')) {
                $table->string('timezone')->default('Europe/Madrid')->after('language');
            }

            // Agregar soft deletes si no existe
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            // Agregar índices si no existen
            if (!Schema::hasColumn('users', 'user_type')) {
                // Ya se agregó arriba, pero verificamos índices
            }
        });

        // Migrar datos de 'name' a 'first_name' y 'last_name' si existe la columna name
        if (Schema::hasColumn('users', 'name')) {
            \DB::statement("
                UPDATE users 
                SET first_name = SUBSTRING_INDEX(name, ' ', 1),
                    last_name = CASE 
                        WHEN LOCATE(' ', name) > 0 
                        THEN SUBSTRING(name, LOCATE(' ', name) + 1)
                        ELSE ''
                    END
                WHERE first_name IS NULL OR last_name IS NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // No eliminamos columnas para evitar pérdida de datos
            // Si necesitas revertir, hazlo manualmente
        });
    }
};

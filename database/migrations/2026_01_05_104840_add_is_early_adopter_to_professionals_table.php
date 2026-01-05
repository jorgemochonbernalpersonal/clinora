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
        Schema::table('professionals', function (Blueprint $table) {
            // Campo para marcar a los early adopters de la beta
            $table->boolean('is_early_adopter')->default(false)->after('subscription_status');
            
            // Índice para optimizar consultas
            $table->index('is_early_adopter');
        });

        // Marcar como early adopters a todos los usuarios registrados antes del 30/04/2026
        // (en este momento todos los existentes lo son porque estamos en fase beta)
        DB::table('professionals')->update(['is_early_adopter' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropIndex(['is_early_adopter']);
            $table->dropColumn('is_early_adopter');
        });
    }
};

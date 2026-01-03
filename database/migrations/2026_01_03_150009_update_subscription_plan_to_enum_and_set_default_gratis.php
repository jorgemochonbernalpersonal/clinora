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
        // Primero actualizar los valores existentes de 'basic' a 'gratis'
        DB::table('professionals')
            ->where('subscription_plan', 'basic')
            ->orWhereNull('subscription_plan')
            ->update(['subscription_plan' => 'gratis']);

        // Cambiar el campo de string a enum
        Schema::table('professionals', function (Blueprint $table) {
            $table->enum('subscription_plan', ['gratis', 'pro', 'equipo'])
                  ->default('gratis')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a string
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('subscription_plan')->default('basic')->change();
        });

        // Revertir valores de 'gratis' a 'basic'
        DB::table('professionals')
            ->where('subscription_plan', 'gratis')
            ->update(['subscription_plan' => 'basic']);
    }
};

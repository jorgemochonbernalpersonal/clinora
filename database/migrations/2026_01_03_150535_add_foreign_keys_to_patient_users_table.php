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
        Schema::table('patient_users', function (Blueprint $table) {
            // Agregar foreign keys que estaban comentadas
            $table->foreign('contact_id')
                  ->references('id')
                  ->on('contacts')
                  ->onDelete('cascade');
                  
            $table->foreign('professional_id')
                  ->references('id')
                  ->on('professionals')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_users', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropForeign(['professional_id']);
        });
    }
};

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
            // Drop unique constraint on user_id
            $table->dropUnique(['user_id']);
            
            // Add composite unique constraint
            $table->unique(['user_id', 'professional_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_users', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'professional_id']);
            $table->unique('user_id');
        });
    }
};

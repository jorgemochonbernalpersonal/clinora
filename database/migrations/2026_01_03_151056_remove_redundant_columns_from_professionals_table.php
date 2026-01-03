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
            $table->dropColumn(['name', 'phone', 'language', 'timezone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('name')->after('user_id');
            $table->string('phone')->nullable()->after('specialties');
            $table->string('timezone')->default('Europe/Madrid')->after('address_country');
            $table->string('language', 2)->default('es')->after('currency');
        });
    }
};

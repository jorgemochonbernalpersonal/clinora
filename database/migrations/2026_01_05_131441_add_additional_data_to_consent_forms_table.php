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
        Schema::table('consent_forms', function (Blueprint $table) {
            // Store additional data used to generate the consent form (treatment duration, platform, etc.)
            $table->json('additional_data')->nullable()->after('consent_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consent_forms', function (Blueprint $table) {
            $table->dropColumn('additional_data');
        });
    }
};

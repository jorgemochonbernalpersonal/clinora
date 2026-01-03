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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('referral_source')->nullable()->after('notes');
            $table->boolean('data_protection_consent')->default(false)->after('referral_source');
            $table->timestamp('data_protection_consent_at')->nullable()->after('data_protection_consent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['referral_source', 'data_protection_consent', 'data_protection_consent_at']);
        });
    }
};

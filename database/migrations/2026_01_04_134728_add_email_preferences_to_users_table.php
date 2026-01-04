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
            $table->boolean('email_marketing')->default(true)->after('email_notifications');
            $table->boolean('email_limit_alerts')->default(true)->after('email_marketing');
            $table->boolean('email_weekly_summary')->default(true)->after('email_limit_alerts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_marketing', 'email_limit_alerts', 'email_weekly_summary']);
        });
    }
};

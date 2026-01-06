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
        Schema::table('clinical_notes', function (Blueprint $table) {
            // Data retention policy fields
            $table->timestamp('archived_at')->nullable()->after('updated_at');
            $table->timestamp('deletion_notified_at')->nullable()->after('archived_at');
            
            // Index for performance
            $table->index(['session_date', 'archived_at']);
            $table->index('deletion_notified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinical_notes', function (Blueprint $table) {
            $table->dropIndex(['session_date', 'archived_at']);
            $table->dropIndex(['deletion_notified_at']);
            $table->dropColumn(['archived_at', 'deletion_notified_at']);
        });
    }
};

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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->after('xml_path');
            $table->string('stripe_payment_intent_id')->nullable()->after('stripe_session_id');
            $table->timestamp('paid_at')->nullable()->after('stripe_payment_intent_id');
            
            $table->index('stripe_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['stripe_session_id']);
            $table->dropColumn(['stripe_session_id', 'stripe_payment_intent_id', 'paid_at']);
        });
    }
};

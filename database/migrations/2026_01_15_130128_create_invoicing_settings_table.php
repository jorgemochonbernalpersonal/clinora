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
        Schema::create('invoicing_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->unique()->constrained()->onDelete('cascade');
            
            // Configuración de facturas
            $table->string('invoice_prefix')->default('FAC-{YEAR}-');
            $table->integer('invoice_padding')->default(4);
            $table->integer('invoice_counter')->default(1);
            $table->boolean('invoice_year_reset')->default(true);
            
            // Configuración de albaranes
            $table->string('delivery_note_prefix')->default('ALB-{YEAR}-');
            $table->integer('delivery_note_padding')->default(4);
            $table->integer('delivery_note_counter')->default(1);
            $table->boolean('delivery_note_year_reset')->default(true);
            
            // Control de reseteo anual
            $table->integer('last_reset_year')->default(now()->year);
            
            $table->timestamps();
            
            $table->index('professional_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicing_settings');
    }
};

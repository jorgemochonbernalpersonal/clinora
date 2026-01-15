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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            
            // Descripción del servicio
            $table->string('description', 500);
            $table->text('notes')->nullable();
            
            // Cantidad y precio
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('unit_price', 10, 2);
            
            // Impuestos (0 para psicólogos exentos de IVA)
            $table->decimal('tax_rate', 5, 2)->default(0);
            
            // Totales
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            
            $table->timestamps();
            
            // Índices
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};

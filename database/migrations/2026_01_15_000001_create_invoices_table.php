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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            
            // Numeración y serie
            $table->string('invoice_number', 50)->unique();
            $table->string('series', 10)->default('A');
            
            // Fechas
            $table->date('issue_date');
            $table->date('due_date');
            
            // Estado
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled', 'refunded', 'partially_paid'])
                  ->default('draft');
            
            // Importes
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0); // IVA (0 para psicólogos exentos)
            $table->decimal('irpf_retention', 10, 2)->default(0); // Retención IRPF
            $table->decimal('total', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            
            // Datos fiscales específicos para psicólogos
            $table->boolean('is_iva_exempt')->default(true); // Psicólogos siempre exentos
            $table->decimal('irpf_rate', 5, 2)->nullable(); // 15% o 7% según años de actividad
            $table->boolean('is_b2b')->default(false); // Si es empresa/autónomo/seguro
            
            // Notas y documentos
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('xml_path')->nullable(); // Para VeriFactu (futuro)
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index(['professional_id', 'issue_date']);
            $table->index(['professional_id', 'status']);
            $table->index('contact_id');
            $table->index('status');
            $table->index('invoice_number');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

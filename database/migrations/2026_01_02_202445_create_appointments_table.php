<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            
            // Fecha y hora
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration')->virtualAs('TIMESTAMPDIFF(MINUTE, start_time, end_time)');
            
            // Tipo y estado
            $table->enum('type', ['in_person', 'online', 'home_visit', 'phone'])->default('in_person');
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            
            // Detalles
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Facturación
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->boolean('is_paid')->default(false);
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('professional_id');
            $table->index('contact_id');
            $table->index('start_time');
            $table->index('status');
            $table->index(['professional_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

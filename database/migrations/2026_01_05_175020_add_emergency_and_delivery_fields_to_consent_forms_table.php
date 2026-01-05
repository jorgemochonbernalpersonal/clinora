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
            // Contacto de emergencia (para protocolo de riesgo vital)
            $table->string('emergency_contact_name')->nullable()->after('additional_data');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            
            // Constancia de entrega al paciente
            $table->timestamp('delivered_at')->nullable()->after('signed_at');
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null')->after('delivered_at');
            
            // Índice para búsqueda de documentos entregados
            $table->index('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consent_forms', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropColumn([
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'delivered_at',
                'delivered_by',
            ]);
        });
    }
};

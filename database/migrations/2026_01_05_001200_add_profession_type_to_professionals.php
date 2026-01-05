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
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('profession_type')->default('psychologist')->after('id');
            $table->index('profession_type');
        });

        // Todos los profesionales existentes son psicólogos por defecto
        DB::table('professionals')->update(['profession_type' => 'psychologist']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropIndex(['profession_type']);
            $table->dropColumn('profession_type');
        });
    }
};

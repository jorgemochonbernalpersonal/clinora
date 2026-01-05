<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update all existing professionals to have psychologist as default
        DB::table('professionals')
            ->whereNull('profession_type')
            ->update(['profession_type' => 'psychologist']);
    }

    public function down(): void
    {
        // Revert to null if needed
        DB::table('professionals')
            ->where('profession_type', 'psychologist')
            ->update(['profession_type' => null]);
    }
};

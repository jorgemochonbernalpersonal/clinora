<?php

use App\Shared\Enums\ProfessionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure all existing professionals have a valid profession_type
        // This migration is safe to run multiple times
        
        DB::table('professionals')
            ->whereNull('profession_type')
            ->orWhere('profession_type', '')
            ->update(['profession_type' => ProfessionType::PSYCHOLOGIST->value]);
        
        // Add comment to document valid values
        $validValues = implode(', ', ProfessionType::values());
        DB::statement("
            ALTER TABLE professionals 
            MODIFY profession_type VARCHAR(50) 
            COMMENT 'Valid values: {$validValues}'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove comment
        DB::statement("
            ALTER TABLE professionals 
            MODIFY profession_type VARCHAR(50) 
            COMMENT ''
        ");
    }
};

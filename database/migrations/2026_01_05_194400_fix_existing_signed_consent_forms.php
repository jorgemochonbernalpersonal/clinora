<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing signed consent forms where is_valid is false or null
        $affected = DB::table('consent_forms')
            ->whereNotNull('signed_at')
            ->whereNull('revoked_at')
            ->where(function($query) {
                $query->where('is_valid', '=', false)
                      ->orWhereNull('is_valid');
            })
            ->update(['is_valid' => true]);
            
        \Log::info('Fixed is_valid for existing signed consent forms', [
            'rows_affected' => $affected,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed - this is a data fix
    }
};

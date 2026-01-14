<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // We use a raw query because ENUM changes are tricky in many migration systems
        // and we want to ensure any existing data is preserved/mapped correctly.
        // For MySQL:
        DB::statement("ALTER TABLE clinical_notes MODIFY COLUMN risk_assessment ENUM('none', 'low', 'moderate', 'high', 'imminent', 'sin_riesgo', 'riesgo_bajo', 'riesgo_moderado', 'riesgo_medio', 'riesgo_alto', 'riesgo_inminente') DEFAULT 'none'");

        DB::table('clinical_notes')->where('risk_assessment', 'sin_riesgo')->update(['risk_assessment' => 'none']);
        DB::table('clinical_notes')->where('risk_assessment', 'riesgo_bajo')->update(['risk_assessment' => 'low']);
        DB::table('clinical_notes')->where('risk_assessment', 'riesgo_moderado')->update(['risk_assessment' => 'moderate']);
        DB::table('clinical_notes')->where('risk_assessment', 'riesgo_medio')->update(['risk_assessment' => 'moderate']);
        DB::table('clinical_notes')->where('risk_assessment', 'riesgo_alto')->update(['risk_assessment' => 'high']);
        DB::table('clinical_notes')->where('risk_assessment', 'riesgo_inminente')->update(['risk_assessment' => 'imminent']);

        DB::statement("ALTER TABLE clinical_notes MODIFY COLUMN risk_assessment ENUM('none', 'low', 'moderate', 'high', 'imminent') DEFAULT 'none'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE clinical_notes MODIFY COLUMN risk_assessment ENUM('none', 'low', 'moderate', 'high', 'imminent', 'sin_riesgo', 'riesgo_bajo', 'riesgo_moderado', 'riesgo_alto', 'riesgo_inminente') DEFAULT 'sin_riesgo'");

        DB::table('clinical_notes')->where('risk_assessment', 'none')->update(['risk_assessment' => 'sin_riesgo']);
        DB::table('clinical_notes')->where('risk_assessment', 'low')->update(['risk_assessment' => 'riesgo_bajo']);
        DB::table('clinical_notes')->where('risk_assessment', 'moderate')->update(['risk_assessment' => 'riesgo_moderado']);
        DB::table('clinical_notes')->where('risk_assessment', 'high')->update(['risk_assessment' => 'riesgo_alto']);
        DB::table('clinical_notes')->where('risk_assessment', 'imminent')->update(['risk_assessment' => 'riesgo_inminente']);

        DB::statement("ALTER TABLE clinical_notes MODIFY COLUMN risk_assessment ENUM('sin_riesgo', 'riesgo_bajo', 'riesgo_moderado', 'riesgo_alto', 'riesgo_inminente') DEFAULT 'sin_riesgo'");
    }
};

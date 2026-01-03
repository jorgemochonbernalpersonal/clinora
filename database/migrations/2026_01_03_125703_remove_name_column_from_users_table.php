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
        // Primero, migrar datos de 'name' a 'first_name' y 'last_name' si existen
        // y las nuevas columnas están vacías
        if (Schema::hasColumn('users', 'name')) {
            DB::statement("
                UPDATE users 
                SET first_name = COALESCE(first_name, SUBSTRING_INDEX(name, ' ', 1)),
                    last_name = COALESCE(
                        last_name, 
                        CASE 
                            WHEN LOCATE(' ', name) > 0 
                            THEN SUBSTRING(name, LOCATE(' ', name) + 1)
                            ELSE ''
                        END
                    )
                WHERE (first_name IS NULL OR first_name = '') 
                   OR (last_name IS NULL OR last_name = '')
                   AND name IS NOT NULL 
                   AND name != ''
            ");
        }

        // Eliminar la columna 'name'
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar la columna 'name' concatenando first_name y last_name
            $table->string('name')->after('email');
        });

        // Migrar datos de vuelta
        DB::statement("
            UPDATE users 
            SET name = CONCAT(
                COALESCE(first_name, ''),
                CASE 
                    WHEN first_name IS NOT NULL AND last_name IS NOT NULL THEN ' '
                    ELSE ''
                END,
                COALESCE(last_name, '')
            )
        ");
    }
};

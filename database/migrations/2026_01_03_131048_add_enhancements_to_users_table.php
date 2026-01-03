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
        Schema::table('users', function (Blueprint $table) {
            // Avatar
            $table->string('avatar_path')->nullable()->after('phone');
            
            // Preferencias ampliadas
            $table->enum('theme', ['light', 'dark', 'auto'])->default('light')->after('timezone');
            $table->boolean('notifications_enabled')->default(true)->after('theme');
            $table->boolean('email_notifications')->default(true)->after('notifications_enabled');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            
            // Metadata flexible (JSON)
            $table->json('metadata')->nullable()->after('sms_notifications');
            
            // Índices optimizados
            $table->index('email_verified_at');
            $table->index('deleted_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['created_at']);
            
            // Eliminar columnas
            $table->dropColumn([
                'avatar_path',
                'theme',
                'notifications_enabled',
                'email_notifications',
                'sms_notifications',
                'metadata',
            ]);
        });
    }
};

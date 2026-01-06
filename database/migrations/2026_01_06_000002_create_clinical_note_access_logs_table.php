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
        Schema::create('clinical_note_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_note_id')->constrained('clinical_notes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Action performed
            $table->enum('action', ['view', 'edit', 'delete', 'export'])->default('view');
            
            // Request information
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('request_method', 10)->nullable();
            $table->text('request_url')->nullable();
            
            // Authorization
            $table->boolean('is_authorized')->default(true);
            $table->text('authorization_note')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['clinical_note_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['is_authorized', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_note_access_logs');
    }
};

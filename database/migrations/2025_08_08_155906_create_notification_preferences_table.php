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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category'); // system, project, finance, hr, etc.
            $table->json('channels')->nullable(); // email, push, sms, in_app
            $table->json('frequency')->nullable(); // immediate, daily, weekly, never
            $table->json('quiet_hours')->nullable(); // {"start": "22:00", "end": "08:00"}
            $table->boolean('is_active')->default(true);
            $table->json('custom_settings')->nullable(); // Additional user-specific settings
            $table->timestamps();

            $table->unique(['user_id', 'category']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};

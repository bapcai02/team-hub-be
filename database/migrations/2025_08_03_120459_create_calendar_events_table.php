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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('event_type', ['meeting', 'task', 'reminder', 'other'])->default('other');
            $table->string('color', 7)->default('#4285f4'); // Hex color code
            $table->boolean('is_all_day')->default(false);
            $table->string('location')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index(['user_id', 'start_time']);
            $table->index(['event_type', 'start_time']);
            $table->index(['status', 'start_time']);
        });

        // Create pivot table for event participants
        Schema::create('calendar_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('calendar_events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_event_participants');
        Schema::dropIfExists('calendar_events');
    }
};

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, push, sms, in_app
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data for the notification
            $table->string('status')->default('pending'); // pending, sent, failed, cancelled
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->timestamp('scheduled_at')->nullable(); // For scheduled notifications
            $table->timestamp('sent_at')->nullable();
            $table->integer('retry_count')->default(0);
            $table->text('error_message')->nullable();
            $table->json('recipients')->nullable(); // Array of recipient IDs
            $table->string('channel')->default('all'); // all, email, push, sms
            $table->boolean('is_read')->default(false);
            $table->string('category')->nullable(); // system, project, finance, hr, etc.
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['scheduled_at', 'status']);
            $table->index(['priority', 'status']);
            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}; 
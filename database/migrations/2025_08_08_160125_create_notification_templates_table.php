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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // system, project, finance, hr, etc.
            $table->string('type'); // email, push, sms, in_app
            $table->string('title_template');
            $table->text('message_template');
            $table->json('variables')->nullable(); // Available variables for the template
            $table->json('channels')->default('["email", "push", "in_app"]');
            $table->string('priority')->default('normal');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional template settings
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'type']);
            $table->index(['is_active', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};

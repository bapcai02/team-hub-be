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
        Schema::table('documents', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->enum('category', ['project', 'meeting', 'policy', 'template', 'other'])->default('other')->after('description');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('category');
            $table->json('tags')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['description', 'category', 'status', 'tags']);
        });
    }
};
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
        // Update roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('description');
        });

        // Update permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('module')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_active']);
        });

        // Revert permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['description', 'module', 'is_active']);
        });
    }
}; 
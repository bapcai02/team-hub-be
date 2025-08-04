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
        Schema::create('device_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Category code
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add category_id to devices table
        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        
        Schema::dropIfExists('device_categories');
    }
}; 
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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // Device code
            $table->enum('type', ['laptop', 'desktop', 'tablet', 'phone', 'printer', 'scanner', 'other']);
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])->default('available');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('location');
            $table->string('department');
            $table->date('purchase_date');
            $table->date('warranty_expiry');
            $table->json('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('device_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->string('action'); // assigned, unassigned, maintenance, retired, etc.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_history');
        Schema::dropIfExists('devices');
    }
}; 
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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->foreignId('salary_component_id')->constrained('salary_components')->onDelete('cascade');
            $table->string('component_name');
            $table->enum('type', ['allowance', 'deduction', 'bonus', 'overtime']);
            $table->decimal('amount', 12, 2);
            $table->decimal('rate', 12, 2)->default(0); // Rate per hour, percentage, etc.
            $table->integer('quantity')->default(1); // Hours, days, etc.
            $table->text('description')->nullable();
            $table->boolean('is_taxable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};

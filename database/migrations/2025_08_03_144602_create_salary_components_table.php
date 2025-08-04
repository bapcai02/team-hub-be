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
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['allowance', 'deduction', 'bonus', 'overtime']);
            $table->enum('calculation_type', ['fixed', 'percentage', 'formula']);
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0); // For percentage-based components
            $table->text('formula')->nullable(); // For formula-based calculations
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};

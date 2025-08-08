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
        Schema::create('business_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['employment', 'service', 'partnership', 'client', 'vendor', 'other']);
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable(); // External party
            $table->unsignedBigInteger('employee_id')->nullable(); // For employment contracts
            $table->decimal('value', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'expired', 'terminated', 'completed']);
            $table->enum('signature_status', ['unsigned', 'partially_signed', 'fully_signed']);
            $table->json('terms')->nullable(); // Contract terms and conditions
            $table->json('signatures')->nullable(); // Digital signatures data
            $table->string('file_path')->nullable(); // Generated PDF path
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_contracts');
    }
};

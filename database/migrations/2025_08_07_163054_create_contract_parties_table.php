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
        Schema::create('contract_parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->enum('party_type', ['client', 'vendor', 'partner', 'employee']);
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_title')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->json('additional_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_parties');
    }
};

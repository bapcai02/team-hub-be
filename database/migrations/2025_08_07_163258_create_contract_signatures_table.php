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
        Schema::create('contract_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('signer_id'); // User who signed
            $table->string('signer_name');
            $table->string('signer_email');
            $table->string('signer_title')->nullable(); // Position/Title
            $table->string('signature_type'); // digital, electronic, handwritten
            $table->text('signature_data'); // Base64 encoded signature or digital signature
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('signed_at');
            $table->json('metadata')->nullable(); // Additional signature metadata
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_signatures');
    }
};

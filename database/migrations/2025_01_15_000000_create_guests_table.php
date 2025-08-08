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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->text('address')->nullable();
            $table->enum('type', ['guest', 'partner', 'vendor', 'client']);
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->text('notes')->nullable();
            $table->string('avatar')->nullable();
            $table->date('first_visit_date')->nullable();
            $table->date('last_visit_date')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('guest_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->dateTime('check_in');
            $table->dateTime('check_out')->nullable();
            $table->string('purpose');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('host_id')->nullable();
            $table->enum('status', ['scheduled', 'checked_in', 'checked_out', 'cancelled']);
            $table->timestamps();
        });

        Schema::create('guest_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();
        });

        Schema::create('guest_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id');
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_position')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_contacts');
        Schema::dropIfExists('guest_documents');
        Schema::dropIfExists('guest_visits');
        Schema::dropIfExists('guests');
    }
}; 
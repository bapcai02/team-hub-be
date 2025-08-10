<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix uploads table - update related_type enum to include 'document_version'
        DB::statement("ALTER TABLE uploads MODIFY related_type ENUM('task', 'message', 'document', 'document_version')");
        
        // Check if document_shares table exists, if not create it
        if (!Schema::hasTable('document_shares')) {
            Schema::create('document_shares', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('permission', ['view', 'edit', 'comment'])->default('view');
                $table->unsignedBigInteger('shared_by');
                $table->timestamp('shared_at')->useCurrent();
                $table->timestamp('expires_at')->nullable();
                $table->softDeletes();
                
                // Indexes
                $table->index(['document_id', 'user_id']);
                $table->unique(['document_id', 'user_id']);
                
                // Foreign keys
                $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('shared_by')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert uploads table
        DB::statement("ALTER TABLE uploads MODIFY related_type ENUM('task', 'message', 'document')");
        
        // Drop document_shares table
        Schema::dropIfExists('document_shares');
    }
};
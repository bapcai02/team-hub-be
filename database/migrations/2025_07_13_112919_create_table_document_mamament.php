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
        
        // 50. documents
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->enum('visibility', ['public', 'private', 'department']);
            $table->timestamps();
            $table->softDeletes();
        });

        // 51. document_blocks
        Schema::create('document_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->string('type');
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        // 52. document_versions
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->longText('content_snapshot');
            $table->string('hash');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
        });

        // 53. document_comments
        Schema::create('document_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('block_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->timestamp('created_at')->useCurrent();
        });

        // 54. uploads
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uploaded_by');
            $table->text('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('size');
            $table->enum('related_type', ['task', 'message', 'document']);
            $table->unsignedBigInteger('related_id');
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

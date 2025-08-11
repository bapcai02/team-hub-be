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
        // 1. Bảng chat_polls (bình chọn)
        Schema::create('chat_polls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('question', 500);
            $table->boolean('allow_multiple')->default(false);
            $table->boolean('anonymous')->default(false);
            $table->dateTime('expires_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Bảng chat_poll_options (tùy chọn bình chọn)
        Schema::create('chat_poll_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->string('text', 200);
            $table->integer('votes')->default(0);
            $table->timestamps();
        });

        // 3. Bảng chat_poll_votes (phiếu bầu)
        Schema::create('chat_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        // 4. Bảng chat_locations (chia sẻ vị trí)
        Schema::create('chat_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('address', 500)->nullable();
            $table->timestamps();
        });

        // 5. Bảng chat_message_replies (trả lời theo thread)
        Schema::create('chat_message_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_message_id');
            $table->unsignedBigInteger('reply_message_id');
            $table->timestamps();
        });

        // 6. Bảng chat_message_status (trạng thái tin nhắn)
        Schema::create('chat_message_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
        });

        // 7. Bảng chat_typing_status (trạng thái đang gõ)
        Schema::create('chat_typing_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_typing')->default(false);
            $table->dateTime('last_typing_at')->nullable();
            $table->timestamps();
        });

        // 8. Bảng chat_conversation_settings (cài đặt cuộc trò chuyện)
        Schema::create('chat_conversation_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('name', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_private')->default(false);
            $table->boolean('allow_invites')->default(true);
            $table->boolean('read_only')->default(false);
            $table->boolean('slow_mode')->default(false);
            $table->integer('slow_mode_interval')->default(30);
            $table->json('theme')->nullable();
            $table->timestamps();
        });

        // 9. Bảng chat_conversation_roles (vai trò trong nhóm)
        Schema::create('chat_conversation_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['admin', 'member', 'moderator'])->default('member');
            $table->dateTime('joined_at')->useCurrent();
            $table->boolean('is_online')->default(false);
            $table->dateTime('last_seen_at')->nullable();
        });

        // 10. Bảng chat_archived_conversations (cuộc trò chuyện đã lưu trữ)
        Schema::create('chat_archived_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('archived_at')->useCurrent();
            $table->dateTime('restored_at')->nullable();
        });

        // 11. Bảng chat_pinned_messages (tin nhắn đã ghim)
        Schema::create('chat_pinned_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('pinned_by');
            $table->timestamps();
        });

        // 12. Bảng chat_search_history (lịch sử tìm kiếm)
        Schema::create('chat_search_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('conversation_id');
            $table->string('search_query', 500);
            $table->timestamps();
        });

        // Tạo indexes cho performance
        Schema::table('chat_polls', function (Blueprint $table) {
            $table->index('conversation_id');
            $table->index('created_by');
        });

        Schema::table('chat_poll_options', function (Blueprint $table) {
            $table->index('poll_id');
        });

        Schema::table('chat_poll_votes', function (Blueprint $table) {
            $table->index(['poll_id', 'user_id']);
            $table->index('option_id');
        });

        Schema::table('chat_locations', function (Blueprint $table) {
            $table->index('message_id');
        });

        Schema::table('chat_message_replies', function (Blueprint $table) {
            $table->index('parent_message_id');
            $table->index('reply_message_id');
        });

        Schema::table('chat_message_status', function (Blueprint $table) {
            $table->index(['message_id', 'user_id']);
        });

        Schema::table('chat_typing_status', function (Blueprint $table) {
            $table->index(['conversation_id', 'user_id']);
        });

        Schema::table('chat_conversation_settings', function (Blueprint $table) {
            $table->index('conversation_id');
        });

        Schema::table('chat_conversation_roles', function (Blueprint $table) {
            $table->index(['conversation_id', 'user_id']);
        });

        Schema::table('chat_archived_conversations', function (Blueprint $table) {
            $table->index(['conversation_id', 'user_id']);
        });

        Schema::table('chat_pinned_messages', function (Blueprint $table) {
            $table->index('conversation_id');
            $table->index('message_id');
        });

        Schema::table('chat_search_history', function (Blueprint $table) {
            $table->index(['user_id', 'conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_search_history');
        Schema::dropIfExists('chat_pinned_messages');
        Schema::dropIfExists('chat_archived_conversations');
        Schema::dropIfExists('chat_conversation_roles');
        Schema::dropIfExists('chat_conversation_settings');
        Schema::dropIfExists('chat_typing_status');
        Schema::dropIfExists('chat_message_status');
        Schema::dropIfExists('chat_message_replies');
        Schema::dropIfExists('chat_locations');
        Schema::dropIfExists('chat_poll_votes');
        Schema::dropIfExists('chat_poll_options');
        Schema::dropIfExists('chat_polls');
    }
}; 
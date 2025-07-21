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
         // 41. conversations
         Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['personal', 'group']);
            $table->string('name')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 42. conversation_participants
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('joined_at')->useCurrent();
        });

        // 43. messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('content');
            $table->enum('type', ['text', 'file', 'image', 'video', 'meeting']);
            $table->timestamps();
        });

        // 44. message_reactions
        Schema::create('message_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('user_id');
            $table->string('emoji');
            $table->timestamp('created_at')->useCurrent();
        });

        // 45. message_attachments
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id');
            $table->text('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('uploaded_at')->useCurrent();
        });

        // 46. meetings
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration_minutes');
            $table->text('link');
            $table->enum('status', ['scheduled', 'ongoing', 'finished', 'cancelled']);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // 47. meeting_participants
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
        });

        // 48. events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('owner_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('type', ['personal', 'work', 'meeting', 'leave']);
            $table->enum('visibility', ['private', 'public', 'department']);
            $table->timestamps();
            $table->softDeletes();
        });

        // 49. event_participants
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['invited', 'accepted', 'declined']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

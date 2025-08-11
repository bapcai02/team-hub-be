<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessageReply extends Model
{
    protected $table = 'chat_message_replies';
    
    protected $fillable = [
        'parent_message_id',
        'reply_message_id',
    ];

    /**
     * Get the parent message
     */
    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    /**
     * Get the reply message
     */
    public function replyMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_message_id');
    }
} 
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
        Log::info('MessageSent event created', ['message' => $message]);
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting to channel: chat.global');
        return new Channel('chat.global');
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}

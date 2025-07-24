<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // Broadcast to a public channel for all users
        return new Channel('chat');
    }

    public function broadcastAs(): string
    {
        return 'chat-event';
    }
}

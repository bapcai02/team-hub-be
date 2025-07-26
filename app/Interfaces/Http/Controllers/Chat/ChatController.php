<?php

namespace App\Interfaces\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Helpers\ApiResponseHelper;

class ChatController
{
    /**
     * List all chat messages (latest first).
     */
    public function index(Request $request)
    {
        // You can add pagination or filter by conversation if needed
        $messages = []; // TODO: Replace with actual message fetching logic
        return ApiResponseHelper::responseApi(['messages' => $messages], 'message_list_success');
    }

    /**
     * Send a chat message and broadcast to all users.
     */
    public function send(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = $request->user();
        $message = [
            'user_id' => $user ? $user->id : null,
            'content' => $request->input('content'),
            'sent_at' => now()->toDateTimeString(),
        ];

        // Broadcast to all users via Reverb
        event(new MessageSent($message));

        return ApiResponseHelper::responseApi(['message' => $message], 'message_sent_success', 201);
    }

    /**
     * Test broadcast functionality.
     */
    public function testBroadcast(Request $request)
    {
        $testMessage = [
            'user_id' => $request->user() ? $request->user()->id : null,
            'content' => 'Test broadcast message at ' . now()->toDateTimeString(),
            'sent_at' => now()->toDateTimeString(),
        ];

        // Broadcast test message to all users
        event(new MessageSent($testMessage));

        return ApiResponseHelper::responseApi(['message' => $testMessage], 'test_broadcast_success');
    }
} 
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class ChatController
{
    /**
     * List all chat messages (latest first).
     */
    public function index(Request $request)
    {
        // You can add pagination or filter by conversation if needed
        $messages = Message::orderByDesc('id')->limit(100)->get();
        return response()->json(['messages' => $messages]);
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
        $message = Message::create([
            'user_id' => $user ? $user->id : null,
            'content' => $request->input('content'),
            'sent_at' => now(),
        ]);

        // Broadcast to all users via Reverb
        event(new MessageSent($message));

        return response()->json(['message' => $message], 201);
    }

    /**
     * (Optional) Delete a message (only by owner).
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $message = Message::findOrFail($id);
        if (!$user || $message->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $message->delete();
        return response()->json(['message' => 'Message deleted']);
    }
} 
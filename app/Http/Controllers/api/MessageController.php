<?php

namespace App\Http\Controllers\api;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
      /**
     * Retrieve message history for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch both sent and received messages for the authenticated user
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        // Create the message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
        ]);

        // Broadcast the new message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
    }

    /**
     * Mark messages as seen.
     */
    public function markAsSeen(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        // Update the `is_seen` status for the provided message IDs
        Message::whereIn('id', $request->message_ids)
            ->where('recipient_id', Auth::id()) // Ensure only the recipient can mark them as seen
            ->update(['is_seen' => true]);

        return response()->json(['message' => 'Messages marked as seen']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
    public function sendPrivateMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id, // Private message recipient
            'content' => $request->content,
            'timestamp' => now(),
        ]);

        // Broadcast the private message to the recipient
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => 'Message sent']);
    }
}

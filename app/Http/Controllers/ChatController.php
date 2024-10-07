<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use App\Events\UserConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }

    public function updateUserStatus()
    {
        $user = Auth::user();
        $user->is_online = true;
        $user->save();

        broadcast(new UserConnection($user));

        return response()->json(['status' => 'User status updated!']);
    }

    public function markAsSeen(Message $message)
    {
        $message->update(['is_seen' => true]);
        return response()->json(['message' => 'Message marked as seen']);
    }
}

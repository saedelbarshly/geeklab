<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use App\Events\UserConnection;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SendMessageRequest;

class ChatController extends Controller
{
    public function sendMessage(SendMessageRequest $request)
    {
        try {
            $message = Message::create([
                'recipient_id' => $request->recipient_id,
                'content' => $request->content,
            ]);
            broadcast(new MessageSent($message))->toOthers();
            return response()->json(['status' => 'Message sent!'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
      
    }

    public function updateUserStatus()
    {
        try {
            $user = Auth::user();
            $user->is_online = true;
            $user->save();
            broadcast(new UserConnection($user));
            return response()->json(['status' => 'User status updated!'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
       
    }

    public function markAsSeen(Message $message)
    {
        try {
            $message->update(['is_seen' => true]);
            return response()->json(['message' => 'Message marked as seen'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
        
    }
}

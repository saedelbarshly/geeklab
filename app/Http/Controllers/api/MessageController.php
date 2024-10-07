<?php

namespace App\Http\Controllers\api;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MarkAsSeenRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\MessageResource;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
      /**
     * Retrieve message history for the authenticated user.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
    
            return MessageResource::collection($messages)->response()->getData(true);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }

    /**
     * Send a new message.
     */
    public function store(SendMessageRequest $request)
    {
        try {
            $message = Message::create([
                'recipient_id' => $request->recipient_id,
                'content' => $request->content,
            ]);
            
            broadcast(new MessageSent($message))->toOthers();
            return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }

    /**
     * Mark messages as seen.
     */
    public function markAsSeen(MarkAsSeenRequest $request)
    {
        try {
            Message::whereIn('id', $request->message_ids)
                ->where('recipient_id', Auth::id())
                ->update(['is_seen' => true]);
    
            return response()->json(['message' => 'Messages marked as seen'],200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Somthing went wrong !"],400);
        }
    }
}

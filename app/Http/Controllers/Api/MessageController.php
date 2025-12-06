<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string',
            'type' => 'sometimes|in:text,quote_proposal,system',
            'metadata' => 'sometimes|array',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($validated['conversation_id']);

        // Verify user is participant
        if (!$conversation->users->contains($user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'sender_id' => $user->id,
            'content' => $validated['content'],
            'type' => $validated['type'] ?? 'text',
            'metadata' => $validated['metadata'] ?? null,
        ]);

        $message->load('sender');

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'data' => [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'metadata' => $message->metadata,
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                    'role' => $message->sender->role,
                ],
                'created_at' => $message->created_at->toISOString(),
            ]
        ], 201);
    }

    public function index(Conversation $conversation): JsonResponse
    {
        $user = Auth::user();
        
        // Verify user is participant
        if (!$conversation->users->contains($user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'type' => $m->type,
                'metadata' => $m->metadata,
                'sender' => [
                    'id' => $m->sender->id,
                    'name' => $m->sender->name,
                    'role' => $m->sender->role,
                ],
                'created_at' => $m->created_at->toISOString(),
            ]);

        return response()->json(['data' => $messages]);
    }
}

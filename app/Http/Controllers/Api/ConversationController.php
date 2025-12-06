<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $conversations = $user->conversations()
            ->with(['users', 'latestMessage.sender'])
            ->withCount('messages')
            ->get()
            ->map(function ($conversation) {
                return [
                    'id' => $conversation->id,
                    'title' => $conversation->title,
                    'status' => $conversation->status,
                    'participants' => $conversation->users->map(fn($u) => [
                        'id' => $u->id,
                        'name' => $u->name,
                        'role' => $u->role,
                    ]),
                    'latest_message' => $conversation->latestMessage ? [
                        'content' => $conversation->latestMessage->content,
                        'sender_name' => $conversation->latestMessage->sender->name,
                        'created_at' => $conversation->latestMessage->created_at->toISOString(),
                    ] : null,
                    'messages_count' => $conversation->messages_count,
                    'created_at' => $conversation->created_at->toISOString(),
                ];
            });

        return response()->json(['data' => $conversations]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'participant_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        
        $conversation = Conversation::create([
            'title' => $validated['title'],
            'status' => 'active',
        ]);

        $conversation->users()->attach([$user->id, $validated['participant_id']]);
        
        $conversation->load('users');

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
                'participants' => $conversation->users->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'role' => $u->role,
                ]),
            ]
        ], 201);
    }

    public function show(Conversation $conversation): JsonResponse
    {
        $user = Auth::user();
        
        // Verify user is participant
        if (!$conversation->users->contains($user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conversation->load(['users', 'messages.sender', 'documents.uploader']);

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
                'participants' => $conversation->users->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'role' => $u->role,
                    'email' => $u->email,
                ]),
                'messages' => $conversation->messages->map(fn($m) => [
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
                ]),
                'documents' => $conversation->documents->map(fn($d) => [
                    'id' => $d->id,
                    'filename' => $d->original_filename,
                    'file_size' => $d->file_size_formatted,
                    'document_type' => $d->document_type,
                    'uploader' => [
                        'id' => $d->uploader->id,
                        'name' => $d->uploader->name,
                    ],
                    'created_at' => $d->created_at->toISOString(),
                ]),
            ]
        ]);
    }
}

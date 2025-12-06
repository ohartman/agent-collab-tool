<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Conversation;
use App\Events\DocumentUploaded;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'document_type' => 'sometimes|in:policy,quote,form,other',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($validated['conversation_id']);

        // Verify user is participant
        if (!$conversation->users->contains($user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents', $filename, 'public');

        $document = Document::create([
            'conversation_id' => $validated['conversation_id'],
            'uploaded_by' => $user->id,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'document_type' => $validated['document_type'] ?? 'other',
        ]);

        $document->load('uploader');

        // Broadcast the document upload
        broadcast(new DocumentUploaded($document))->toOthers();

        return response()->json([
            'data' => [
                'id' => $document->id,
                'filename' => $document->original_filename,
                'file_size' => $document->file_size_formatted,
                'document_type' => $document->document_type,
                'uploader' => [
                    'id' => $document->uploader->id,
                    'name' => $document->uploader->name,
                ],
                'created_at' => $document->created_at->toISOString(),
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

        $documents = $conversation->documents()
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'filename' => $d->original_filename,
                'file_size' => $d->file_size_formatted,
                'document_type' => $d->document_type,
                'uploader' => [
                    'id' => $d->uploader->id,
                    'name' => $d->uploader->name,
                ],
                'created_at' => $d->created_at->toISOString(),
            ]);

        return response()->json(['data' => $documents]);
    }

    public function download(Document $document)
    {
        $user = Auth::user();
        
        // Verify user is participant in conversation
        if (!$document->conversation->users->contains($user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }
}

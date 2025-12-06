<?php

namespace App\Events;

use App\Models\Document;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Document $document
    ) {
        $this->document->load('uploader');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('conversation.' . $this->document->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'document.uploaded';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->document->id,
            'conversation_id' => $this->document->conversation_id,
            'filename' => $this->document->original_filename,
            'file_size' => $this->document->file_size_formatted,
            'document_type' => $this->document->document_type,
            'uploader' => [
                'id' => $this->document->uploader->id,
                'name' => $this->document->uploader->name,
            ],
            'created_at' => $this->document->created_at->toISOString(),
        ];
    }
}

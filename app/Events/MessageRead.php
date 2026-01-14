<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageIds; // Array of IDs read
    public $roomId;
    public $readerId;

    public function __construct(array $messageIds, int $roomId, int $readerId)
    {
        $this->messageIds = $messageIds;
        $this->roomId = $roomId;
        $this->readerId = $readerId;
    }

    public function broadcastOn()
    {
        // Only people inside THIS room will know the message was read.
        return new PrivateChannel('chat.room.' . $this->roomId);
    }

    public function broadcastAs()
    {
        return 'message.read';
    }

    public function broadcastWith()
    {
        return [
            'ids' => $this->messageIds,
            'reader_id' => $this->readerId,
            'read_at' => now()->toISOString()
        ];
    }
}
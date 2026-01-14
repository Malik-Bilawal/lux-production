<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;
    public $roomId;

    /**
     * @param array $payload The data to send to frontend (sender info, text, etc)
     * @param int $roomId The ID of the ChatRoom
     */
    public function __construct(array $payload, int $roomId)
    {
        $this->payload = $payload;
        $this->roomId = $roomId;
    }

    public function broadcastOn()
    {
        // STANDARD: 'chat.room.{id}'
        // This matches the channel authorization in routes/channels.php
        return new PrivateChannel('chat.room.' . $this->roomId);
    }

    public function broadcastAs()
    {
        // Frontend listener: .listen('.message.sent', (e) => {})
        return 'message.sent';
    }

    public function broadcastWith()
    {
        // Explicitly define what goes over the wire
        return $this->payload;
    }
}
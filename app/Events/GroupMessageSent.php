<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatRoomId;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chatRoomId = null)
    {
        $this->message = $message;
        $this->chatRoomId = $chatRoomId;
        
        if ($chatRoomId && is_array($message)) {
            $this->message['chat_room_id'] = $chatRoomId;
        }
    }

    public function broadcastOn(): Channel|array
    {
        if ($this->chatRoomId) {
            return new PresenceChannel('group-chat.' . $this->chatRoomId);
        } else {
            return new PrivateChannel('admin-chat');
        }
    }

 
    public function broadcastAs(): string
    {
        return 'message.sent';
    }


    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->chatRoomId ? 'group' : 'admin',
            'chat_room_id' => $this->chatRoomId,
            'timestamp' => now()->toISOString()
        ];
    }
}
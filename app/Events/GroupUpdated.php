<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId;
    public $groupName;

    public function __construct($groupId, $groupName)
    {
        $this->groupId = $groupId;
        $this->groupName = $groupName;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('group-chat-' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'group-updated';
    }

    public function broadcastWith()
    {
        return [
            'group_id' => $this->groupId,
            'group_name' => $this->groupName
        ];
    }
}
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMemberRemoved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId;
    public $memberId;
    public $memberName;

    public function __construct($groupId, $memberId, $memberName)
    {
        $this->groupId = $groupId;
        $this->memberId = $memberId;
        $this->memberName = $memberName;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('group-chat-' . $this->groupId);
    }

    public function broadcastAs()
    {
        return 'member-removed';
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->memberId,
            'user_name' => $this->memberName,
            'group_id' => $this->groupId
        ];
    }
}
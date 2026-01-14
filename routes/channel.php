<?php

use App\Models\Admin;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\ChatRoomMember;
use Illuminate\Support\Facades\Broadcast;

// Presence channel for online admins in group chat
Broadcast::channel('presence-group-chat', function ($admin) {
    if ($admin instanceof Admin && !$admin->is_blocked) {
        return [
            'id' => $admin->id,
            'name' => $admin->name,
            'avatar' => $admin->avatar_url,
            'role' => $admin->role->name ?? 'Admin'
        ];
    }
});

// Private channel for individual group chats
Broadcast::channel('private-group-chat-{groupId}', function ($admin, $groupId) {
    // Check if admin is a member of this group
    $isMember = ChatRoomUser::where('chat_room_id', $groupId)
        ->where('admin_id', $admin->id)
        ->exists();
    
    return $isMember && !$admin->is_blocked;
});

// Private channel for admin-to-admin messages (if needed)
Broadcast::channel('private-admin-chat.{adminId}', function ($admin, $adminId) {
    return (int) $admin->id === (int) $adminId && !$admin->is_blocked;
});
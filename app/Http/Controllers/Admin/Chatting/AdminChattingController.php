<?php

namespace App\Http\Controllers\Admin\Chatting;

use App\Models\Admin;
use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminChattingController extends Controller
{
    public function index()
    {
        $admins = Admin::with('role')
            ->where('id', '!=', auth('admin')->id())
            ->where('status', 'active')
            ->get();
        
        return view('admin.admin-chat', compact('admins'));
    }

    public function sendMessage(Request $request)
    {
        Log::info('✅ [sendMessage] Called', $request->all());

        try {
            $validator = Validator::make($request->all(), [
                'message'     => 'required|string|max:1000',
                'type'        => 'required|in:group',
                'chat_room_id'=> 'required|integer|exists:chat_rooms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors()->first()
                ], 422);
            }

            $validated = $validator->validated();
            $senderId = auth('admin')->id();
            $chatRoomId = $validated['chat_room_id'];

            // Verify user is member of the group
            $chatRoom = ChatRoom::where('id', $chatRoomId)
                ->whereHas('users', function($q) use ($senderId) {
                    $q->where('admin_id', $senderId);
                })->first();

            if (!$chatRoom) {
                return response()->json([
                    'status' => false,
                    'error' => 'You are not a member of this group'
                ], 403);
            }

            // Encrypt and create message
            $encryptedMessage = Crypt::encryptString($validated['message']);

            $message = Message::create([
                'chat_room_id' => $chatRoomId,
                'sender_id'    => $senderId,
                'message'      => $encryptedMessage,
                'type'         => 'text',
                'is_read'      => false,
            ]);

            // Prepare broadcast data
            $broadcastData = [
                'id' => $message->id,
                'chat_room_id' => $chatRoomId,
                'sender_id' => $message->sender_id,
                'sender_name' => auth('admin')->user()->name,
                'sender_pic' => auth('admin')->user()->profile_pic ?? '/images/default-avatar.png',
                'message' => $validated['message'],
                'type' => $message->type,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->toISOString(),
                'chat_type' => 'group'
            ];

            // Broadcast event
            broadcast(new MessageSent($broadcastData))->toOthers();

            // Update last message timestamp
            $chatRoom->update(['last_activity' => now()]);

            return response()->json([
                'status' => true, 
                'message' => $broadcastData
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [sendMessage] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false, 
                'error' => 'Failed to send message'
            ], 500);
        }
    }

    public function sendMedia(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimetypes:image/*,video/*,audio/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:51200',
            'chat_room_id' => 'required|integer|exists:chat_rooms,id',
            'type' => 'required|in:group'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->errors()->first()
            ], 422);
        }

        $senderId = auth('admin')->id();
        $chatRoomId = $request->chat_room_id;

        $chatRoom = ChatRoom::where('id', $chatRoomId)
            ->whereHas('users', function ($q) use ($senderId) {
                $q->where('admin_id', $senderId);
            })->first();

        if (!$chatRoom) {
            return response()->json([
                'status' => false,
                'error' => 'You are not a member of this group'
            ], 403);
        }

        $file = $request->file('file');
        $folder = "chat-media/{$chatRoomId}";
        $filePath = $file->store($folder, 'public');

        $mimeType = $file->getMimeType();
        $messageType = 'file';

        if (str_starts_with($mimeType, 'image/')) {
            $messageType = 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $messageType = 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            $messageType = 'audio';
        }

        $encryptedMessage = Crypt::encryptString($filePath);

        $message = Message::create([
            'chat_room_id' => $chatRoomId,
            'sender_id'    => $senderId,
            'message'      => $encryptedMessage,
            'type'         => $messageType,
            'file_name'    => $file->getClientOriginalName(),
            'file_size'    => $file->getSize(),
            'is_read'      => false,
        ]);

        // ✅ Prepare broadcast payload
        $broadcastData = [
            'id' => $message->id,
            'chat_room_id' => $chatRoomId,
            'sender_id' => $message->sender_id,
            'sender_name' => auth('admin')->user()->name,
            'sender_pic' => auth('admin')->user()->profile_pic ?? '/images/default-avatar.png',
            'message' => Storage::url($filePath),
            'type' => $messageType,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'is_read' => $message->is_read,
            'created_at' => $message->created_at->toISOString(),
            'chat_type' => 'group'
        ];

        broadcast(new MessageSent($broadcastData))->toOthers();

        $chatRoom->update(['last_activity' => now()]);

        return response()->json([
            'status' => true,
            'message' => 'Media sent successfully',
            'message_data' => $broadcastData
        ]);
    } catch (\Exception $e) {
        Log::error('❌ [sendMedia] Failed', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => false,
            'error' => 'Failed to send media'
        ], 500);
    }
}


public function getMessages($chatId, Request $request)
{
    try {
        $authId = auth('admin')->id();
        
        // 1. Security Check (Fast)
        $hasAccess = DB::table('chat_room_user')
            ->where('chat_room_id', $chatId)
            ->where('admin_id', $authId)
            ->exists();

        if (!$hasAccess) {
            return response()->json(['status' => false, 'error' => 'Access denied'], 403);
        }

        $messages = Message::with(['sender:id,name,profile_pic'])
            ->where('chat_room_id', $chatId)
            ->orderBy('created_at', 'desc') 
            ->paginate(50);


        $decryptedMessages = collect($messages->items())->map(function ($msg) use ($authId) {
            try {
                if ($msg->type === 'text') {
                    $msg->message = Crypt::decryptString($msg->message);
                } else {
                    $filePath = Crypt::decryptString($msg->message);
                    $msg->message = Storage::url($filePath);
                }
            } catch (\Exception $e) {
                $msg->message = '[Encrypted content]';
            }
            
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->name ?? 'Unknown',
                'sender_pic' => $msg->sender->profile_pic ?? '/images/default-avatar.png',
                'message' => $msg->message,
                'type' => $msg->type,
                'is_read' => $msg->is_read, 
                'is_me' => $msg->sender_id === $authId,
                'created_at' => $msg->created_at->toISOString(),
            ];
        })->reverse()->values();


        $this->markMessagesAsRead(new Request(['chat_room_id' => $chatId]));

        return response()->json([
            'status' => true,
            'messages' => $decryptedMessages,
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'has_more' => $messages->hasMorePages(),
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('❌ [getMessages] Failed', ['error' => $e->getMessage()]);
        return response()->json(['status' => false, 'error' => 'Error loading chat'], 500);
    }
}
    public function getSidebarData()
    {
        try {
            $adminId = auth('admin')->id();

            // Get group chats only
            $groupChats = ChatRoom::where('type', 'group')
                ->whereHas('users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })
                ->with(['lastMessage', 'users'])
                ->get()
                ->map(function($chat) use ($adminId) {
                    $unreadCount = Message::where('chat_room_id', $chat->id)
                        ->where('sender_id', '!=', $adminId)
                        ->where('is_read', false)
                        ->count();

                    $lastMessage = $chat->lastMessage;
                    $messagePreview = null;
                    
                    if ($lastMessage) {
                        try {
                            if ($lastMessage->type === 'text') {
                                $decrypted = Crypt::decryptString($lastMessage->message);
                                $messagePreview = strlen($decrypted) > 50 ? 
                                    substr($decrypted, 0, 50) . '...' : $decrypted;
                            } else {
                                $messagePreview = '[' . ucfirst($lastMessage->type) . '] ' . ($lastMessage->file_name ?? 'Media');
                            }
                        } catch (\Exception $e) {
                            $messagePreview = '[Encrypted message]';
                        }
                    }

                    return [
                        'id' => $chat->id,
                        'name' => $chat->name,
                        'profile_pic' => $chat->image ? Storage::url($chat->image) : '/images/default-group.png',
                        'type' => 'group',
                        'is_group' => true,
                        'last_message' => $messagePreview,
                        'last_message_time' => $lastMessage->created_at ?? $chat->created_at,
                        'unread_count' => $unreadCount,
                        'member_count' => $chat->users->count(),
                        'description' => $chat->description,
                        'created_by' => $chat->created_by,
                    ];
                });

            $allGroups = $groupChats;
            $unreadGroups = $allGroups->where('unread_count', '>', 0)->values();

            return response()->json([
                'status' => true,
                'data' => [
                    'all' => $allGroups,
                    'unread' => $unreadGroups,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [getSidebarData] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'error' => 'Failed to load group list'
            ], 500);
        }
    }

    public function getAdminMembers()
    {
        try {
            $adminId = auth('admin')->id();
            
            $admins = Admin::where('id', '!=', $adminId)
                ->where('status', 'active')
                ->select('id', 'name', 'email', 'profile_pic', 'role_id')
                ->with('role:name,id')
                ->get()
                ->map(function($admin) {
                    return [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'profile_pic' => $admin->profile_pic ?? '/images/default-avatar.png',
                        'role' => $admin->role->name ?? 'Admin'
                    ];
                });

            return response()->json($admins);

        } catch (\Exception $e) {
            Log::error('❌ [getAdminMembers] Failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve admin members.'], 500);
        }
    }

    public function createGroup(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'members' => 'required|array|min:1',
                'members.*' => 'exists:admins,id'
            ]);

            $adminId = auth('admin')->id();

            // Create group chat room
            $chatRoom = ChatRoom::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => 'group',
                'created_by' => $adminId,
                'image' => null,
                'last_activity' => now(),
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('chat-groups', 'public');
                $chatRoom->update(['image' => $imagePath]);
            }

            // Add members to group (including creator)
            $members = array_unique(array_merge($validated['members'], [$adminId]));
            $chatRoom->users()->attach($members);

            return response()->json([
                'status' => true,
                'message' => 'Group created successfully',
                'group' => [
                    'id' => $chatRoom->id,
                    'name' => $chatRoom->name,
                    'description' => $chatRoom->description,
                    'image' => $chatRoom->image ? Storage::url($chatRoom->image) : null,
                    'profile_pic' => $chatRoom->image ? Storage::url($chatRoom->image) : '/images/default-group.png'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [createGroup] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'error' => 'Failed to create group'
            ], 500);
        }
    }

    public function updateGroup(Request $request, $groupId)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $adminId = auth('admin')->id();

            // Verify user is the creator of the group
            $chatRoom = ChatRoom::where('id', $groupId)
                ->where('created_by', $adminId)
                ->first();

            if (!$chatRoom) {
                return response()->json([
                    'status' => false,
                    'error' => 'Only group creator can update group info'
                ], 403);
            }

            $chatRoom->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($chatRoom->image) {
                    Storage::disk('public')->delete($chatRoom->image);
                }
                
                $imagePath = $request->file('image')->store('chat-groups', 'public');
                $chatRoom->update(['image' => $imagePath]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Group updated successfully',
                'group' => [
                    'id' => $chatRoom->id,
                    'name' => $chatRoom->name,
                    'description' => $chatRoom->description,
                    'image' => $chatRoom->image ? Storage::url($chatRoom->image) : null,
                    'profile_pic' => $chatRoom->image ? Storage::url($chatRoom->image) : '/images/default-group.png'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [updateGroup] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'error' => 'Failed to update group'
            ], 500);
        }
    }

    public function getGroup($groupId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->whereHas('users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();

            return response()->json([
                'status' => true,
                'group' => [
                    'id' => $chatRoom->id,
                    'name' => $chatRoom->name,
                    'description' => $chatRoom->description,
                    'image' => $chatRoom->image ? Storage::url($chatRoom->image) : null,
                    'profile_pic' => $chatRoom->image ? Storage::url($chatRoom->image) : '/images/default-group.png',
                    'created_by' => $chatRoom->created_by,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function getGroupInfo($groupId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->whereHas('users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();

            $memberCount = $chatRoom->users()->count();

            return response()->json([
                'status' => true,
                'member_count' => $memberCount
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function markMessagesAsRead(Request $request)
    {
        // 1. Validate input
        $request->validate(['chat_room_id' => 'required|integer']);
        
        $adminId = auth('admin')->id();
        $roomId = $request->chat_room_id;
    
        try {

            $unreadMessages = Message::where('chat_room_id', $roomId)
                ->where('sender_id', '!=', $adminId) 
                ->whereDoesntHave('statuses', function ($q) use ($adminId) {
                    $q->where('user_id', $adminId);
                })
                ->pluck('id'); 
    
            if ($unreadMessages->isEmpty()) {
                return response()->json(['status' => true, 'message' => 'Already up to date']);
            }
    

            $insertData = [];
            $now = now();
            
            foreach ($unreadMessages as $msgId) {
                $insertData[] = [
                    'message_id' => $msgId,
                    'user_id'    => $adminId, 
                    'is_read'    => true,
                    'read_at'    => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
    
            \App\Models\MessageStatus::insert($insertData);
    
       
            broadcast(new \App\Events\MessageRead(
                $unreadMessages->toArray(), 
                $roomId, 
                $adminId
            ))->toOthers();
    
            return response()->json(['status' => true, 'count' => count($insertData)]);
    
        } catch (\Exception $e) {
            Log::error('❌ [markMessagesAsRead] Failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'error' => 'Failed to mark read'], 500);
        }
    }

    public function getGroupMembers($groupId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->whereHas('users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();

            $members = $chatRoom->users()
                ->select('admins.id', 'admins.name', 'admins.email', 'admins.profile_pic')
                ->get()
                ->map(function($member) use ($chatRoom) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'profile_pic' => $member->profile_pic ?? '/images/default-avatar.png',
                        'is_creator' => $member->id === $chatRoom->created_by,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $members
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function clearChat($chatRoomId)
    {
        try {
            $adminId = auth('admin')->id();
    
            $chatRoom = ChatRoom::where('id', $chatRoomId)
                ->whereHas('users', function ($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();
    
            // Fetch messages with media before deleting
            $messages = Message::where('chat_room_id', $chatRoomId)->get();
    
            foreach ($messages as $msg) {
                // Decrypt message if it's encrypted file path
                try {
                    $filePath = Crypt::decryptString($msg->message);
    
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                } catch (\Exception $e) {
                    // ignore if not a file or failed decrypt
                }
            }
    
            // Now delete all message records
            Message::where('chat_room_id', $chatRoomId)->delete();
    
            // Optionally reset last_activity timestamp
            $chatRoom->update(['last_activity' => now()]);
    
            return response()->json([
                'status' => true,
                'message' => 'Chat cleared successfully (media deleted too)'
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ [clearChat] Failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'error' => 'Failed to clear chat'], 500);
        }
    }
        public function leaveGroup($groupId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->whereHas('users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();

            // Don't allow creator to leave group (they should delete it instead)
            if ($chatRoom->created_by === $adminId) {
                return response()->json([
                    'status' => false,
                    'error' => 'Group creator cannot leave the group. Please delete the group instead.'
                ], 403);
            }

            // Remove user from group
            $chatRoom->users()->detach($adminId);

            return response()->json([
                'status' => true,
                'message' => 'You have left the group'
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function kickMember($groupId, $memberId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->where('created_by', $adminId) // Only creator can kick members
                ->firstOrFail();

            // Don't allow kicking yourself
            if ($memberId == $adminId) {
                return response()->json([
                    'status' => false,
                    'error' => 'You cannot kick yourself from the group'
                ], 403);
            }

            // Remove member from group
            $chatRoom->users()->detach($memberId);

            return response()->json([
                'status' => true,
                'message' => 'Member removed from group'
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function markMessageAsRead($messageId)
    {
        try {
            $adminId = auth('admin')->id();

            $message = Message::where('id', $messageId)
                ->whereHas('chatRoom.users', function($q) use ($adminId) {
                    $q->where('admin_id', $adminId);
                })->firstOrFail();

            $message->update(['is_read' => true]);

            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            return response()->json(['status' => false], 500);
        }
    }

    public function addMembersToGroup(Request $request, $groupId)
    {
        try {
            $validated = $request->validate([
                'members' => 'required|array|min:1',
                'members.*' => 'exists:admins,id'
            ]);

            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->where('created_by', $adminId) 
                ->firstOrFail();

            $currentMembers = $chatRoom->users()->pluck('admin_id')->toArray();
            $newMembers = array_diff($validated['members'], $currentMembers);

            if (empty($newMembers)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Selected members are already in the group'
                ], 422);
            }

            // Add new members to group
            $chatRoom->users()->attach($newMembers);

            return response()->json([
                'status' => true,
                'message' => 'Members added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [addMembersToGroup] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'error' => 'Failed to add members'
            ], 500);
        }
    }

    public function deleteGroup($groupId)
    {
        try {
            $adminId = auth('admin')->id();
    
            $chatRoom = ChatRoom::where('id', $groupId)
                ->where('created_by', $adminId) // Only creator can delete
                ->firstOrFail();
    
            // 🔥 Fetch all messages first (to delete their files)
            $messages = Message::where('chat_room_id', $groupId)->get();
    
            foreach ($messages as $msg) {
                try {
                    // Decrypt stored media path (if encrypted)
                    $filePath = Crypt::decryptString($msg->message);
    
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                } catch (\Exception $e) {
                    // skip if not decryptable or not file message
                }
            }
    
            // 🧹 Delete all messages from DB
            Message::where('chat_room_id', $groupId)->delete();
    
            // 🧍 Delete all users in this group
            ChatRoomUser::where('chat_room_id', $groupId)->delete();
    
            // 🖼️ Delete group profile image (if exists)
            if (!empty($chatRoom->image) && Storage::disk('public')->exists($chatRoom->image)) {
                Storage::disk('public')->delete($chatRoom->image);
            }
    
            // 🚮 Optional: delete the entire group folder if media stored under chat-media/{groupId}
            $groupMediaDir = "chat-media/{$groupId}";
            if (Storage::disk('public')->exists($groupMediaDir)) {
                Storage::disk('public')->deleteDirectory($groupMediaDir);
            }
    
            // 💀 Finally delete the group itself
            $chatRoom->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Group and all related data deleted successfully'
            ]);
    
        } catch (\Exception $e) {
            Log::error('❌ [deleteGroup] Failed', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'error' => 'Failed to delete group'
            ], 500);
        }
    }
    
    public function getAvailableAdmins($groupId)
    {
        try {
            $adminId = auth('admin')->id();

            $chatRoom = ChatRoom::where('id', $groupId)
                ->where('created_by', $adminId)
                ->firstOrFail();

            // Get current member IDs
            $currentMemberIds = $chatRoom->users()->pluck('admin_id')->toArray();

            // Get admins not in the group
            $availableAdmins = Admin::where('id', '!=', $adminId)
                ->where('status', 'active')
                ->whereNotIn('id', $currentMemberIds)
                ->select('id', 'name', 'email', 'profile_pic', 'role_id')
                ->with('role:name,id')
                ->get()
                ->map(function($admin) {
                    return [
                        'id' => $admin->id,
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'profile_pic' => $admin->profile_pic ?? '/images/default-avatar.png',
                        'role' => $admin->role->name ?? 'Admin'
                    ];
                });

            return response()->json($availableAdmins);

        } catch (\Exception $e) {
            Log::error('❌ [getAvailableAdmins] Failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to retrieve available admins.'], 500);
        }
    }
}
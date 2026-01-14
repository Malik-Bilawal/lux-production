<?php

namespace App\Http\Controllers\Admin\Chatting;

use App\Models\Admin;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatGroupController extends Controller
{

    public function getMembers()
{
    $admin = Auth::guard('admin')->user();
    $admins = \App\Models\Admin::where('id', '!=', $admin->id)
        ->select('id', 'name', 'profile_pic')
        ->get();

    return response()->json($admins);
}


public function createGroup(Request $request)
{
    try {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:admins,id',
            'image' => 'nullable|image|max:2048'
        ]);

        // Create group first without image
        $group = ChatRoom::create([
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => 'group',
            'created_by'  => $admin->id,
            'image'       => null, // we'll update later
        ]);

        // Add creator + selected members
        $memberIds = array_unique(array_merge([$admin->id], $validated['members']));
        foreach ($memberIds as $memberId) {
            ChatRoomUser::create([
                'chat_room_id' => $group->id,
                'user_id' => $memberId
            ]);
        }

        // Handle image upload if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
        
            // Define folder path based on group ID
            $folderPath = 'uploads/admin_groups/' . $group->id;
        
            // Store image in storage/app/public/uploads/admin_groups/{group_id}/...
            $imagePath = $image->store($folderPath, 'public');
        
            // Update group with the relative storage path
            $group->update(['image' => $imagePath]);
        
            // Build full URL to return to frontend
            $imageUrl = asset('storage/' . $imagePath);
        } else {
            $imageUrl = null;
        }
        

        return response()->json([
            'status' => true,
            'message' => 'Group created successfully!',
            'data' => [
                'id' => $group->id,
                'name' => $group->name,
                'description' => $group->description,
                'image' => $imageUrl,
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to create group',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function groupMembers($groupId)
{
    try {
        \Log::info("Fetching members for group ID: {$groupId}");

        $group = \App\Models\ChatRoom::with('users')->findOrFail($groupId);

        // Fetch all admins
        $allAdmins = Admin::all(); // get all admins from admins table

        // Map admins with membership flag
        $members = $allAdmins->map(function ($admin) use ($group) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'profile_pic' => $admin->profile_pic
                    ? asset('storage/'.$admin->profile_pic)
                    : asset('images/default-user.png'),
                'is_member' => $group->users->contains($admin->id) // users() relation stores admin IDs
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $members
        ]);
    } catch (\Throwable $e) {
        \Log::error("💥 Error fetching group members for ID {$groupId}", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Server error while fetching group members'
        ], 500);
    }
}



public function addMember($groupId, $userId)
{
    $group = ChatRoom::findOrFail($groupId);
    if (!$group->users->contains($userId)) {
        $group->users()->attach($userId);
    }
    return response()->json(['status' => true]);
}
public function removeMember($groupId, $userId)
{

    try {
        $group = ChatRoom::with('users')->findOrFail($groupId);


        if ($group->users->contains($userId)) {
            $group->users()->detach($userId);
        } 
        return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      
        return response()->json([
            'status' => false,
            'message' => 'Failed to remove member'
        ], 500);
    }
}


public function deleteGroup($groupId)
{
    try {
        $group = ChatRoom::findOrFail($groupId);

        // Optional: check if current admin is allowed to delete
        $adminId = auth('admin')->id();
        if ($group->created_by != $adminId) {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to delete this group.'
            ], 403);
        }

        // Delete associated users
        ChatRoomUser::where('chat_room_id', $group->id)->delete();

        // Optional: delete messages
        // ChatMessage::where('chat_room_id', $group->id)->delete();

        // Delete group image folder if exists
        if ($group->image) {
            $folder = dirname(storage_path('app/public/' . $group->image));
            if (file_exists($folder)) {
                \Illuminate\Support\Facades\File::deleteDirectory($folder);
            }
        }

        // Delete the group itself
        $group->delete();

        return response()->json([
            'status' => true,
            'message' => 'Group deleted successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to delete group.',
            'error' => $e->getMessage()
        ], 500);
    }
}



}

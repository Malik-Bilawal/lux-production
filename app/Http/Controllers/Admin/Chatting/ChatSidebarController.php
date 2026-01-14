<?php

namespace App\Http\Controllers\Admin\Chatting;

use App\Models\Admin;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ChatSidebarController extends Controller
{
    public function index()
    {
        try {
            $admin = Auth::guard('admin')->user();
    
            if (!$admin) {
                Log::warning('❌ Unauthorized access to chat sidebar');
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
    
            // ✅ Fetch all admins except the current one
            $admins = \App\Models\Admin::where('id', '!=', $admin->id)
                ->select('id', 'name', 'profile_pic')
                ->get();
    
            $groupChats = \App\Models\ChatRoom::where('type', 'group')
            ->whereHas('users', function ($q) use ($admin) {
                $q->where('user_id', $admin->id);
            })
            ->select('id', 'name', 'description', 'image', 'created_by', 'created_at')
            ->withCount('messages')
            ->latest()
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'profile_pic' => $group->image ? asset($group->image) : asset('images/default-group.png'),
                    'last_message' => optional($group->messages()->latest()->first())->body ?? 'No messages yet',
                    'last_message_time' => optional($group->messages()->latest()->first())->created_at
                        ? $group->messages()->latest()->first()->created_at->diffForHumans()
                        : '',
                    'unread_count' => 0,
                    'type' => 'group',
                ];
            });
        
    
            $adminsMapped = $admins->map(function ($a) {
                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'profile_pic' => $a->profile_pic ?? asset('images/default-user.png'),
                    'last_message' => 'No messages yet',
                    'last_message_time' => '',
                    'unread_count' => 0,
                    'type' => 'private'
                ];
            });
    
            return response()->json([
                'status' => true,
                'data' => [
                    'privateChats' => $adminsMapped,
                    'groupChats' => $groupChats,
                    'unreadChats' => [],
                ]
            ]);
    
        } catch (\Throwable $e) {
            Log::error('💥 ChatSidebarController error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => false, 'message' => 'Server error'], 500);
        }
    }
    
}

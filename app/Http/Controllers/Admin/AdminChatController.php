<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminMessage;
use Illuminate\Http\Request;
use App\Events\AdminMessageSent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminChatController extends Controller
{
    public function index()
    {
        $admins = Admin::with('role')->get();
        return view('admin.admin-chat', compact('admins'));
    }
    
    public function sendMessage(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        Log::info('sendMessage called', ['admin' => $admin?->id]);
    
        if (!$admin) {
            Log::warning('Unauthorized access attempt');
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        if ($admin->is_blocked) {
            Log::warning('Blocked admin tried to send message', ['admin' => $admin->id]);
            return response()->json(['error' => 'You are blocked'], 403);
        }
    
        $type = $request->input('type', 'text');
        Log::info('Message type:', ['type' => $type]);
    
        $filePath = null;
        $fileName = null;
    
        if ($request->hasFile('file')) {
            // validation...
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->store("admin_chat/{$type}/{$admin->id}", 'public');
        }
    
        $message = AdminMessage::create([
            'sender_id' => $admin->id,
            'sender_name' => $admin->name,
            'sender_role' => $admin->role?->name ?? 'Admin',
            'sender_avatar' => $admin->avatar_url,
            'message' => $type === 'text' ? $request->message : null,
            'type' => $type,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);
    
        Log::info('Message created', ['message_id' => $message->id]);
    
        broadcast(new AdminMessageSent($message))->toOthers();
    
        return response()->json($message);
    }
    public function getMessages()
{
    $admin = Auth::guard('admin')->user();
    if (!$admin) return response()->json([], 401);

    $messages = AdminMessage::latest()->take(50)->get();
    return response()->json($messages);
}


public function block(Request $request, $adminId)
{
    $admin = Admin::findOrFail($adminId);
    $admin->is_blocked = true;
    $admin->save();

    return redirect()->back()->with('success', $admin->name.' has been blocked.');
}

public function unblock(Request $request, $adminId)
{
    $admin = Admin::findOrFail($adminId);
    $admin->is_blocked = false;
    $admin->save();

    return redirect()->back()->with('success', $admin->name.' has been unblocked.');
}



public function clearHistory()
{
    AdminMessage::truncate();

    $mediaPath = public_path('uploads/chat'); 
    if (File::exists($mediaPath)) {
        File::deleteDirectory($mediaPath); 
        File::makeDirectory($mediaPath); 
    }

    return redirect()->back()->with('success', 'All chat history and media files have been cleared.');
}

    
}
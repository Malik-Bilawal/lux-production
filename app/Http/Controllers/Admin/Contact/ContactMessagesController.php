<?php

namespace App\Http\Controllers\Admin\Contact;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ContactMessagesController extends Controller
{

    public function index(Request $request)
    {
        $query = ContactMessage::query();
    
        // --- Search (Full-text recommended, fallback LIKE if not) ---
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
    
        // --- Status Filter ---
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // --- Date Range Filter ---
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
            }
        }
    
        // --- Sorting ---
        switch ($request->sort) {
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            case 'name_asc': $query->orderBy('name', 'asc'); break;
            case 'name_desc': $query->orderBy('name', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }
    
        $messages = $query->paginate(10)->appends($request->all());
    
        $stats = Cache::remember('contact_stats', 3600, fn() =>
            ContactMessage::selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending') as pending,
                SUM(status = 'replied') as replied
            ")->first()
        );
    
        $totalMessages   = $stats->total;
        $pendingMessages = $stats->pending;
        $repliedMessages = $stats->replied;
    
        return view('admin.contact.index', compact('messages', 'totalMessages', 'pendingMessages', 'repliedMessages'));
    }
    
    
    public function sndReply(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'id' => 'required|integer', 
        ]);
    
        try {
            Mail::to($request->email, $request->name)
                ->send(new ContactReplyMail($request->subject, $request->message, $request->name));
    
            ContactMessage::where('id', $request->id)
            ->update([
                'status' => 'replied',
                'replied_at' => now(), 
            ]);
        
            
    
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Mail sending failed: ' . $e->getMessage()
            ], 500);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully to ' . $request->email
        ]);
    }

    public function markAsRead($id)
{
    $message = ContactMessage::findOrFail($id);

    if ($message->status === 'pending') {
        $message->status = 'read';
        $message->save();
    }

    return response()->json(['success' => true]);
}
    

public function getAvgResponseTime()
{
    $messages = ContactMessage::whereNotNull('replied_at')->get();

    if ($messages->count() === 0) {
        return response()->json(['avg_response_time' => null]);
    }

    $totalMinutes = 0;

    foreach ($messages as $msg) {
        $created = Carbon::parse($msg->created_at);
        $replied = Carbon::parse($msg->replied_at);

        $totalMinutes += $created->diffInMinutes($replied);
    }

    $avgMinutes = $totalMinutes / $messages->count();

    $avgHours = round($avgMinutes / 60, 1);

    return response()->json([
        'avg_response_time' => $avgHours . 'h'
    ]);
}


public function destroy(ContactMessage $message)
{
    $message->delete(); // Soft delete
    return redirect()->back()->with('success', 'Message archived successfully.');
}

    
}

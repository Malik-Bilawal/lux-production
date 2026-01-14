<?php

namespace App\Http\Controllers\Admin\Referrals;

use App\Models\Referral;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ReferralStatusMail;
use App\Models\ReferralRejection;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReferralManagementController extends Controller
{
    public function index(Request $request)
    {
        $totalPartners    = Referral::count();
        $pendingPartners  = Referral::where('status', 'pending')->count();
        $approvedPartners = Referral::where('status', 'approved')->count();
        $rejectedPartners = ReferralRejection::count();        
        $query = Referral::where('status', 'pending');

        // STATS OCONTING
        
    // Previous month stats
    $lastMonthTotal    = Referral::whereMonth('created_at', now()->subMonth()->month)->count();
    $lastMonthPending  = Referral::where('status', 'pending')
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->count();
    $lastMonthApproved = Referral::where('status', 'approved')
                                ->whereMonth('created_at', now()->subMonth()->month)
                                ->count();
    $lastMonthRejected = ReferralRejection::whereMonth('created_at', now()->subMonth()->month)->count();

    // Calculate percentage change helper
    $percentChange = function ($current, $previous) {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 2);
    };

    // Dynamic changes
    $totalChange    = $percentChange($totalPartners, $lastMonthTotal);
    $pendingChange  = $percentChange($pendingPartners, $lastMonthPending);
    $approvedChange = $percentChange($approvedPartners, $lastMonthApproved);
    $rejectedChange = $percentChange($rejectedPartners, $lastMonthRejected);
    
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    
        // 🔽 Sorting
        switch ($request->sort) {
            case 'a-z':
                $query->orderBy('name', 'asc');
                break;
            case 'z-a':
                $query->orderBy('name', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
        }
    
        $referrals = $query->paginate(15)->appends($request->all());
    
        return view('admin.referral.index', compact(
            'totalPartners',
            'pendingPartners',
            'approvedPartners',
            'rejectedPartners',
            'referrals',
            'totalChange',
         'pendingChange',
        'approvedChange',
        'rejectedChange'
        ));
    }
    


    
    public function updateStatus(Request $request, $id)
    {
        Log::info("updateStatus called", [
            'referral_id' => $id,
            'request_data' => $request->all()
        ]);
    
        $referral = Referral::findOrFail($id);
        Log::info("Referral loaded", $referral->toArray());
    
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
    
        Log::info("Status requested", ['status' => $request->status]);
    
        if ($request->status === 'approved') {
    
            // --- START: PROFESSIONAL CODE GENERATION (THE UPDATE) ---
            Log::info("Generating *professional* referral code...");
    
            // 1. Get first name
            $name = explode(' ', $referral->name)[0]; // e.g., "Malik"
            
            // 2. Clean it: uppercase, letters-only, max 8 chars
            $baseCode = strtoupper(preg_replace("/[^A-Za-z]/", '', $name)); 
            $baseCode = substr($baseCode, 0, 8);
            
            Log::info("Base code generated", ['base_code' => $baseCode]);
    
            // 3. Find a unique version
            $code = $baseCode; // First attempt (e.g., "MALIK")
            $counter = 1;
    
            while (Referral::where('referral_code', $code)->exists()) {
                // If "MALIK" is taken, try "MALIK1"
                // If "MALIK1" is taken, try "MALIK2", etc.
                $code = $baseCode . $counter;
                $counter++;
            }
            // --- END: PROFESSIONAL CODE GENERATION ---
    
            Log::info("Final unique code generated", ['code' => $code]);
    
            $referral->status = 'approved';
            $referral->referral_code = $code; // Save the new, clean code
            $referral->save();
    
            Log::info("Referral saved after approval");
    
            $loginLink = route('referral.login');
            Log::info("Sending approval email", ['email' => $referral->email]);
    
            Mail::to($referral->email)->send(
                new ReferralStatusMail($referral, 'Approved', $loginLink)
            );
    
            Log::info("Approval email sent");
    
        } else { // This is the 'rejected' block
            
            Log::info("Processing rejection...");
    
            // 1. LOG THE REJECTION
            ReferralRejection::create([
                'referral_id' => $referral->id,
                'email' => $referral->email,
                // You should add a reason here from the request
                // 'reason' => $request->rejection_reason 
            ]);
    
            $referral->status = 'rejected';
            $referral->save();
            Log::info("Referral status set to rejected");
    
            // 3. SEND REJECTION EMAIL
            Log::info("Sending rejection email", ['email' => $referral->email]);
            Mail::to($referral->email)->send(
                new ReferralStatusMail($referral, 'Rejected', null)
            );
            Log::info("Rejection email sent");
        }
        
        return redirect()->back()->with('success', 'Referral status updated & email sent!');
    }
    
}

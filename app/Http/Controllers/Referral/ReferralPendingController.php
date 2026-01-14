<?php

namespace App\Http\Controllers\Referral;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralPendingController extends Controller
{

    public function index(Request $request)
    {
        $email = $request->session()->get('referral_email');
    
        if (!$email) {
            return redirect()->route('user.welcome')->with('error', 'Referral info not found.');
        }
    
        $referral = Referral::select('email', 'created_at')->where('email', $email)->firstOrFail();
    
        return view('referral.pending', compact('referral'));
    }
    
    
    
}

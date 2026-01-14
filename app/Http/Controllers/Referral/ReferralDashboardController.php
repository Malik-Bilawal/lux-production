<?php

namespace App\Http\Controllers\Referral;

use App\Models\Referral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReferralDashboardController extends Controller
{
    public function index()
    {

        $referral = Auth::guard('referral')->user();


        return view('referral.dashboard', compact('referral'));
    }


public function updateProfile(Request $request, $id)
{
    $referral = Referral::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'payment_method' => 'nullable|string|max:50',
        'account_number' => 'nullable|string|max:50',
        'profile_picture' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('profile_picture')) {
        if ($referral->profile_picture && Storage::disk('public')->exists($referral->profile_picture)) {
            Storage::disk('public')->delete($referral->profile_picture);
        }

        $path = $request->file('profile_picture')->store("referral/profile_images/{$referral->id}", 'public');
        $referral->profile_picture = $path;
    }

    $referral->update($request->only('name','phone','payment_method','account_number'));

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

    
    
}

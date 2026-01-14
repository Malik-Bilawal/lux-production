<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewArrivalBanner;

class NewArrivalBannerController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|boolean',
        ]);
    
        $newArrivalBanner = NewArrivalBanner::first() ?? new NewArrivalBanner();
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/sliders/newArrival-banner', 'public');
            $newArrivalBanner->image = $imagePath;
        }
    
        // 👇 Set status based on checkbox (default to 0 if not checked)
        $newArrivalBanner->status = $request->has('status') ? 1 : 0;
    
        if (!$newArrivalBanner->save()) {
            return back()->with('error', 'Something went wrong, banner not saved.');
        }
    
        return back()->with('success', 'New Arrival Banner updated successfully!');
    }
}    

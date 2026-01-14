<?php

namespace App\Http\Controllers\admin\sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NeckWristBanner;

class NeckWristBannerController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'tags' => 'nullable|max:255',
            'button_url' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
    
        $neckWristbanner = NeckWristBanner::first() ?? new NeckWristBanner();
    
        $neckWristbanner->title = $request->title;
        $neckWristbanner->description = $request->description;
        $neckWristbanner->tags = $request->tags;
        $neckWristbanner->button_text = $request->button_text;
        $neckWristbanner->button_url = $request->button_url;
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/sliders/neckWrist-banner', 'public');
            $neckWristbanner->image = $imagePath;
        }
    
        $result = $neckWristbanner->save();
    
        if (!$result) {
            return back()->with('error', 'Something went wrong, banner not saved.');
        }
    
        return redirect()->back()->with('success', 'Watch Banner updated successfully!');
    }
    
}

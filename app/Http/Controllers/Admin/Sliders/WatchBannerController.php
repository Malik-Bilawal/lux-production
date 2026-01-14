<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WatchBanner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class WatchBannerController extends Controller
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
    
        $watchBanner = WatchBanner::first() ?? new WatchBanner();
    
        $watchBanner->title = $request->title;
        $watchBanner->description = $request->description;
        $watchBanner->tags = $request->tags;
        $watchBanner->button_text = $request->button_text;
        $watchBanner->button_url = $request->button_url;
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/sliders/watch-banner', 'public');
            $watchBanner->image = $imagePath;
        }
    
        $result = $watchBanner->save();
    
        if (!$result) {
            return back()->with('error', 'Something went wrong, banner not saved.');
        }
    
        return redirect()->back()->with('success', 'Watch Banner updated successfully!');
    }
    
    
}


<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeVideo;

class HomeVideoController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'video_link' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $video = HomeVideo::first(); // sirf 1 record
        $data = $request->only(['video_link', 'caption']);

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $name = time() . '.' . $image->getClientOriginalExtension();
        
            // Store in: storage/app/public/uploads/sliders/video-thumbnails/
            $path = $image->storeAs('uploads/sliders/video-thmbnails', $name, 'public');
        
            // Store relative path for display and DB
            $data['thumbnail'] = $path;
        }

        if ($video) {
            $video->update($data);
        } else {
            HomeVideo::create($data);
        }

        return redirect()->back()->with('success', 'Video Section Updated Successfully!');
    }
}
<?php

namespace App\Http\Controllers\Admin\Sliders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\HomeSlider;
use App\Models\HomeVideo;
use App\Models\WatchBanner;
use App\Models\NeckWristBanner;
use App\Models\NewArrivalBanner;



class HomeSliderController extends Controller
{

    
    public function index()
    {
        $sliders = HomeSlider::latest()->get();
        $video = HomeVideo::latest()->first();
        $watchBanner = WatchBanner::latest()->first();
        $neckWristBanner = NeckWristBanner::latest()->first();
        $newArrivalBanner = NewArrivalBanner::latest()->first();  
  

        return view('admin.sliders', compact('sliders', 'video', 'watchBanner', 'neckWristBanner', 'newArrivalBanner'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $name = time() . '_' . uniqid() . '.' . $img->extension();
        
                // Store in: storage/app/public/uploads/sliders/home-sliders/
                $path = $img->storeAs('uploads/sliders/home-sliders', $name, 'public');
        
                HomeSlider::create([
                    'image' => $path, // Save relative path
                    'status' => 1,
                ]);
            }
        }

        return back()->with('success', 'Slider images uploaded!');
    }

    public function destroy($id)
    {
        $slider = HomeSlider::findOrFail($id);

        if (file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $slider->delete();

        return back()->with('success', 'Slider image deleted!');
    }



}
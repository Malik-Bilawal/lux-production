<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageHero;
use App\Models\HomeVideo;
use App\Models\HomeSlider;
use App\Models\WatchBanner;
use Illuminate\Http\Request;
use App\Models\NeckWristBanner;
use Illuminate\Validation\Rule;
use App\Models\NewArrivalBanner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $headers = PageHero::all(); // Or paginate()
        $total = $headers->count();
        $active = $headers->where('is_active', true)->count();
        $inactive = $total - $active;

        $sliders = HomeSlider::latest()->get();
        $video = HomeVideo::latest()->first();
        $watchBanner = WatchBanner::latest()->first();
        $neckWristBanner = NeckWristBanner::latest()->first();
        $newArrivalBanner = NewArrivalBanner::latest()->first();  






        return view('admin.sliders', compact('sliders', 'video', 'watchBanner', 'neckWristBanner', 'newArrivalBanner', 'headers', 'total', 'active', 'inactive'));
    }

    public function storePageHero(Request $request)
    {
        $validated = $request->validate([
            'page_type' => 'required|string|unique:page_heroes,page_type',
            'main_heading' => 'required|string|max:255',
            'eyebrow_text' => 'nullable|string|max:255',
            'highlight_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean', 
        ]);


        $validated['is_active'] = $request->has('is_active');

        PageHero::create($validated);

        return redirect()->back()->with('success', 'Page Header created successfully.');
    }

    public function updatePageHero(Request $request, $id)
    {
        $header = PageHero::findOrFail($id);

        // 1. Validate
        $validated = $request->validate([
            'page_type' => ['required', 'string', Rule::unique('page_heroes')->ignore($header->id)],
            'main_heading' => 'required|string|max:255',
            'eyebrow_text' => 'nullable|string|max:255',
            'highlight_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        // 2. Handle Checkbox
        $validated['is_active'] = $request->has('is_active');

        // 3. Update
        $header->update($validated);

        // 4. Redirect
        return redirect()->back()->with('success', 'Page Header updated successfully.');
    }
    public function destroyPageHero($id)
    {
        $header = PageHero::findOrFail($id);
        $header->delete();

        return redirect()->back()->with('success', 'Page Header deleted successfully.');
    }

   // HOMEVIDEO
   // HOMEVIDEO 
   // HOMEVIDEO 


   public function storeHomeVideo(Request $request)
   {
       $request->validate([
           'video' => 'nullable|mimes:mp4,webm,ogg|max:40480', 
           'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:10048', 
       ]);
   
       $existingVideo = HomeVideo::first(); 
       $data = [];
   
       if (!$existingVideo) {
           $existingVideo = HomeVideo::create([
               'status' => 1,
               'video_link' => null,
               'thumbnail' => null,
           ]);
       }
   
       if ($request->hasFile('video')) {
           if ($existingVideo->video_link && Storage::disk('public')->exists($existingVideo->video_link)) {
               Storage::disk('public')->delete($existingVideo->video_link);
           }
   
           $videoFile = $request->file('video');
           $videoName = time() . '.' . $videoFile->getClientOriginalExtension();
           $videoPath = $videoFile->storeAs(
               "uploads/sliders/home-videos/{$existingVideo->id}", 
               $videoName,
               'public'
           );
           $data['video_link'] = $videoPath;
       }
   
       if ($request->hasFile('thumbnail')) {
           if ($existingVideo->thumbnail && Storage::disk('public')->exists($existingVideo->thumbnail)) {
               Storage::disk('public')->delete($existingVideo->thumbnail);
           }
   
           $image = $request->file('thumbnail');
           $imageName = time() . '.' . $image->getClientOriginalExtension();
           $thumbPath = $image->storeAs(
               "uploads/sliders/video-thumbnails/{$existingVideo->id}", 
               $imageName,
               'public'
           );
           $data['thumbnail'] = $thumbPath;
       }
   
       $data['status'] = 1;
   
       $existingVideo->update($data);
   
       return redirect()->back()->with('success', 'Video section updated successfully!');
   }
   
      
  //WATCH BANNER
  //WATCH BANNER
  //WATCH BANNER


  public function updateWatchBanner(Request $request)
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
          if ($watchBanner->image && \Storage::disk('public')->exists($watchBanner->image)) {
              \Storage::disk('public')->delete($watchBanner->image);
          }
  
          $imagePath = $request->file('image')->store('uploads/sliders/watch-banner', 'public');
          $watchBanner->image = $imagePath;
      }
  
      $result = $watchBanner->save();
  
      if (!$result) {
          return back()->with('error', 'Something went wrong, banner not saved.');
      }
  
      return redirect()->back()->with('success', 'Watch Banner updated successfully!');
  }

 // NECK WRIST BANNER
 // NECK WRIST BANNER
 // NECK WRIST BANNER


 public function updateNeckWristBanner(Request $request)
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
         if ($neckWristbanner->image && \Storage::disk('public')->exists($neckWristbanner->image)) {
             \Storage::disk('public')->delete($neckWristbanner->image);
         }
     
         $imagePath = $request->file('image')->store('uploads/sliders/neckWrist-banner', 'public');
         $neckWristbanner->image = $imagePath;
     }
 
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

 

//  New Arrival Banner
//  New Arrival Banner
//  New Arrival Banner


public function updateNewArrivalBanner(Request $request)
{
    $request->validate([
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'status' => 'nullable|boolean',
    ]);

    $newArrivalBanner = NewArrivalBanner::first() ?? new NewArrivalBanner();

    if ($request->hasFile('image')) {
        if ($newArrivalBanner->image && Storage::disk('public')->exists($newArrivalBanner->image)) {
            Storage::disk('public')->delete($newArrivalBanner->image);
        }

        $imagePath = $request->file('image')->store('uploads/sliders/newArrival-banner', 'public');
        $newArrivalBanner->image = $imagePath;
    }

    $newArrivalBanner->status = $request->has('status') ? 1 : 0;

    if (!$newArrivalBanner->save()) {
        return back()->with('error', 'Something went wrong, banner not saved.');
    }

    return back()->with('success', 'New Arrival Banner updated successfully!');
}

}

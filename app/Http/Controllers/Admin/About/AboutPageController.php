<?php

namespace App\Http\Controllers\Admin\About;

use App\Models\Founder;
use App\Models\OurStory;
use App\Models\OurValue;
use App\Models\OurJourney;
use App\Models\TeamMember;
use App\Models\AboutUsBlock;
use App\Models\AboutUsStats;
use Illuminate\Http\Request;
use App\Models\AboutUsVision;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    public function index()
{
   

// Content Block
    $blocks = AboutUsBlock::orderBy('order', 'asc')->get(); 
    $total = $blocks->count();
    $active = $blocks->where('is_active', true)->count();
    $inactive = $total - $active;


    //Stats
    $stats = AboutUsStats::orderBy('order', 'asc')->get();
    $totalStats = $stats->count();
    $activeStats = $stats->where('is_active', true)->count();
    $inactiveStats = $totalStats - $activeStats;


    //Vision
    $vision = AboutUsVision::first();
    return view('admin.about', compact('vision', 'stats', 'totalStats', 'activeStats', 'inactiveStats', 'blocks', 'total',  'active', 'inactive',));
}



/**
     * Store a newly created resource in storage.
     */
    public function storAboutBlock(Request $request)
    {
        $validated = $request->validate([
            'block_text' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'signature_text' => 'nullable|string|max:255',
            'fig_label' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120', // Max 5MB
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $block = AboutUsBlock::create($validated);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->hashName();
            
            $folderPath = "uploads/about/ContentImages/{$block->id}";
            
            $path = $file->storeAs($folderPath, $filename, 'public');

            $block->update(['image_url' => $path]);
        }

        return redirect()->back()->with('success', 'Content block created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAboutBlock(Request $request, $id)
    {
        $block = AboutUsBlock::findOrFail($id);

        $validated = $request->validate([
            'block_text' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'signature_text' => 'nullable|string|max:255',
            'fig_label' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($block->image_url && Storage::disk('public')->exists($block->image_url)) {
                Storage::disk('public')->delete($block->image_url);
            }

            $file = $request->file('image');
            $filename = $file->hashName();
            
            $folderPath = "uploads/about/ContentImages/{$block->id}";
            
            $path = $file->storeAs($folderPath, $filename, 'public');
            
            $validated['image_url'] = $path;
        }

        $block->update($validated);

        return redirect()->back()->with('success', 'Content block updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyAboutBlock($id)
    {
        $block = AboutUsBlock::findOrFail($id);


        $folderPath = "uploads/about/ContentImages/{$block->id}";

        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        $block->delete();

        return redirect()->back()->with('success', 'Content block and associated files deleted.');
    }



    public function storeAboutStats(Request $request)
    {
        $validated = $request->validate([
            'number_value' => 'required|string|max:50', 
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
            'order'        => 'nullable|integer',
            'is_active'    => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        AboutUsStats::create($validated);

        return redirect()->back()->with('success', 'Statistic added successfully.');
    }

    /**
     * Update an existing statistic.
     * Route Name: admin.about-stats.update
     */
    public function updateAboutStats(Request $request, $id)
    {
        $stat = AboutUsStats::findOrFail($id);

        $validated = $request->validate([
            'number_value' => 'required|string|max:50',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:500',
            'order'        => 'nullable|integer',
            'is_active'    => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $stat->update($validated);

        return redirect()->back()->with('success', 'Statistic updated successfully.');
    }

    /**
     * Delete a statistic.
     * Route Name: admin.about-stats.delete
     */
    public function destroyAboutStats($id)
    {
        $stat = AboutUsStats::findOrFail($id);
        
        $stat->delete();

        return redirect()->back()->with('success', 'Statistic deleted successfully.');
    }


    //VISON
    public function storeVision(Request $request)
    {
        // Ensure only one exists
        if (\App\Models\AboutUsVision::exists()) {
            return redirect()->back()->with('error', 'A Vision statement already exists. Please edit the existing one.');
        }

        $validated = $request->validate([
            'quote' => 'required|string',
            'description' => 'nullable|string',
            'initials' => 'nullable|string|max:5',
            'footer_text' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        \App\Models\AboutUsVision::create($validated);

        return redirect()->back()->with('success', 'Vision created successfully.');
    }

    public function updateVision(Request $request, $id)
    {
        $vision = \App\Models\AboutUsVision::findOrFail($id);

        $validated = $request->validate([
            'quote' => 'required|string',
            'description' => 'nullable|string',
            'initials' => 'nullable|string|max:5',
            'footer_text' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vision->update($validated);

        return redirect()->back()->with('success', 'Vision updated successfully.');
    }

    public function destroyVision($id)
    {
        $vision = \App\Models\AboutUsVision::findOrFail($id);
        $vision->delete();

        return redirect()->back()->with('success', 'Vision deleted successfully.');
    }
}

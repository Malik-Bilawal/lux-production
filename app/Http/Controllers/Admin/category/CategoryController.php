<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index()
    {
    
        $categories = Category::withCount('products')
            ->latest()
            ->paginate(15); 

        return view('admin.categories.index', compact('categories'));
    }
    

    public function create()
    {
        return view('admin.categories.create');
    }

    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tagline' => 'nullable|string|max:255',
            'second_tagline' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'home_sort_order' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'second_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
    
        $data = $request->only([
            'name', 
            'title', 
            'description', 
            'tagline', 
            'second_tagline', 
            'sort_order',
            'home_sort_order'
        ]);
    
        // Set defaults
        $data['status'] = $request->status ?? 1;
        $data['sort_order'] = $request->sort_order ?? 0;
        $data['home_sort_order'] = $request->home_sort_order ?? 99; 

$slug = Str::slug($request->name);
$counter = 1;
while (Category::where('slug', $slug)->exists()) {
    $slug = Str::slug($request->name) . '-' . $counter++;
}
$data['slug'] = $slug;
    
        $category = Category::create($data);
    
        $folder = "uploads/categories/{$category->id}";
    
        // Handle images
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'primary_' . time() . '.' . $image->getClientOriginalExtension();
            $category->image = $image->storeAs($folder, $imageName, 'public');
        }
    
        if ($request->hasFile('second_image')) {
            $image2 = $request->file('second_image');
            $imageName2 = 'secondary_' . time() . '.' . $image2->getClientOriginalExtension();
            $category->second_image = $image2->storeAs($folder, $imageName2, 'public');
        }
    
        $category->save();
    
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Collection created successfully.');
    }
    
    
        public function edit($id)
        {
            $category = Category::findOrFail($id);
            return view('admin.categories.edit', compact('category'));
        }
    
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'tagline' => 'nullable|string|max:255',
                'second_tagline' => 'nullable|string|max:255',
                'sort_order' => 'nullable|integer',
                'home_sort_order' => 'nullable|integer',
                'status' => 'nullable|in:0,1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
                'second_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            ]);
        
            $category = Category::findOrFail($id);
        
            $data = $request->only([
                'name', 
                'title', 
                'description', 
                'tagline', 
                'second_tagline', 
                'sort_order',
                'home_sort_order'
            ]);
        
            $data['status'] = $request->status ?? 1;
            $data['home_sort_order'] = $request->home_sort_order ?? 99;
            $data['slug'] = Str::slug($request->name); // Auto-generate slug
        
            $folder = "uploads/categories/{$category->id}";
        
            // Handle images
            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $image = $request->file('image');
                $imageName = 'primary_' . time() . '.' . $image->getClientOriginalExtension();
                $data['image'] = $image->storeAs($folder, $imageName, 'public');
            } else {
                $data['image'] = $category->image;
            }
        
            if ($request->hasFile('second_image')) {
                if ($category->second_image && Storage::disk('public')->exists($category->second_image)) {
                    Storage::disk('public')->delete($category->second_image);
                }
                $image2 = $request->file('second_image');
                $imageName2 = 'secondary_' . time() . '.' . $image2->getClientOriginalExtension();
                $data['second_image'] = $image2->storeAs($folder, $imageName2, 'public');
            } else {
                $data['second_image'] = $category->second_image;
            }
        
            $category->update($data);
        
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Collection updated successfully.');
        }
        
        
    
        public function destroy($id)
        {
            $category = Category::findOrFail($id);
        
            // 1. Delete Images specific files
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
    
            if ($category->second_image && Storage::disk('public')->exists($category->second_image)) {
                Storage::disk('public')->delete($category->second_image);
            }
        
            // 2. Delete the whole folder for this category
            $folder = "uploads/categories/{$category->id}";
            if (Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->deleteDirectory($folder);
            }
        
            // 3. Delete Record
            $category->delete();
        
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Category deleted successfully!');
        }
    }
   
    
    

    

    
    

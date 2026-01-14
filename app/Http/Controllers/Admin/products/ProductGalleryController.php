<?php

namespace App\Http\Controllers\Admin\products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductGalleryController extends Controller
{
    // Show create form
    public function create(Request $request)
    {
        $products = Product::latest()->get();
        $product_id = $request->product_id;

        return view('admin.products.gallery-create', compact('products', 'product_id'));
    }

    // Store product gallery
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'add_to_cart_uri' => 'required|url',
            'buy_now_uri' => 'required|url',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'craftsmanship_desc' => 'required|string',
            'material_desc' => 'required|string',
            'key_features' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Save banner
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('uploads/products/gallery-banner', 'public');
        }

        // Save product gallery
        $gallery = ProductGallery::create([
            'product_id' => $request->product_id,
            'add_to_cart_uri' => $request->add_to_cart_uri,
            'buy_now_uri' => $request->buy_now_uri,
            'banner' => $bannerPath,
            'craftsmanship_desc' => $request->craftsmanship_desc,
            'material_desc' => $request->material_desc,
            'key_features' => $request->key_features,
        ]);

        // Save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('uploads/products/gallery-images', 'public');
                ProductImage::create([
                    'product_id' => $gallery->product_id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product gallery created successfully!');
    }

    // Show edit form
    public function edit($product_id)
    {
        $product = Product::with(['gallery', 'images'])->findOrFail($product_id);
    
        if (!$product->gallery) {
            return redirect()->back()->with('error', 'Gallery not found for this product.');
        }
    
        return view('admin.products.gallery-edit', compact('product'));
    }

    // Update gallery
    public function update(Request $request, $product)
    {
        \Log::info("Reached update method");
    
        // Log incoming request
        \Log::info('Incoming Request:', $request->all());
    
        try {
            // Step 1: Validation
            \Log::info("Starting validation...");
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'add_to_cart_uri' => 'required|url',
                'buy_now_uri' => 'required|url',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'craftsmanship_desc' => 'required|string',
                'material_desc' => 'required|string',
                'key_features' => 'required|string',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            \Log::info("Validation passed");
    
            // Step 2: Fetch gallery
            \Log::info("Fetching gallery for product ID: " . $product);
            $gallery = ProductGallery::where('product_id', $product)->first();
    
            if (!$gallery) {
                \Log::error("Gallery not found for product ID: $product");
                return redirect()->back()->with('error', 'Gallery not found for this product.');
            }
    
            // Step 3: Banner Upload
            if ($request->hasFile('banner')) {
                \Log::info("Banner file found, preparing to update...");
    
                if ($gallery->banner && Storage::disk('public')->exists($gallery->banner)) {
                    \Log::info("Deleting old banner: " . $gallery->banner);
                    Storage::disk('public')->delete($gallery->banner);
                }
    
                $newBannerPath = $request->file('banner')->store('uploads/products/gallery-banner', 'public');
                $gallery->banner = $newBannerPath;
    
            }
    
            // Step 4: Updating gallery fields
            $gallery->update([
                'product_id' => $request->product_id,
                'add_to_cart_uri' => $request->add_to_cart_uri,
                'buy_now_uri' => $request->buy_now_uri,
                'craftsmanship_desc' => $request->craftsmanship_desc,
                'material_desc' => $request->material_desc,
                'key_features' => $request->key_features,
            ]);
    
    
            if ($request->hasFile('images')) {
    
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/products/gallery-images', 'public');
                    ProductImage::create([
                        'product_id' => $gallery->product_id,
                        'image_path' => $path,
                    ]);
                }
            }
    
            // Final Step: Redirect
            \Log::info("Redirecting to edit page with success message.");
            return redirect()->route('admin.products.gallery.edit', $request->product_id)
                ->with('success', 'Product gallery updated successfully!');
    
        } catch (\Exception $e) {
            \Log::error("Exception during update: " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please check logs.');
        }
    }
    
}

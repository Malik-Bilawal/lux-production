<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ShowProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->paginate(5); 
        $categories = Category::all(); 
        $statuses = ['active', 'inactive'];

        return view('admin.products.index', compact('products', 'categories', 'statuses'));
    }


    
    public function create()
{

    $products = Product::latest()->get(); 
        $categories = Category::all(); 
        $statuses = ['active', 'inactive'];
    return view('admin.products.create', compact('products', 'categories', 'statuses'));
}


    public function store(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all());

            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'cut_price' => 'nullable|numeric',
                'description' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'category_id' => 'required|exists:categories,id', 
                'stock_quantity' => 'nullable|integer',
                'rating' => 'nullable|numeric|min:0|max:5',
                'status' => 'required|in:active,inactive',
            ]);

            $product = new Product();
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->description = $request->description;
            $product->category_id = $request->category_id; 
            $product->price = $request->price;
            $product->cut_price = $request->cut_price;
            $product->stock_quantity = $request->stock_quantity ?? 0;
            $product->rating = $request->rating ?? 0;
            $product->status = $request->status;
            $product->tags = $request->tags;
            $product->is_top_selling = $request->has('is_top_selling') ? 1 : 0;
            $product->is_new_arrival = $request->has('is_new_arrival') ? 1 : 0;
            $product->is_feature_card = $request->has('is_feature_card') ? 1 : 0;

            $offer = null;
if ($request->cut_price && $request->price && $request->cut_price > $request->price) {
    $offer = round((($request->cut_price - $request->price) / $request->cut_price) * 100);
}
$product->offer = $offer;


            // ✅ Image upload
if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '.' . $image->getClientOriginalExtension();

    // Store image in: storage/app/public/uploads/products/main-image/
    $imagePath = $image->storeAs('uploads/products/main-image', $imageName, 'public');

    // Save path in DB
    $product->image = $imagePath;
}

            $product->save();

            return redirect()->route('admin.products.gallery-create', ['product_id' => $product->id])
            ->with('success', 'Product created successfully. Now add gallery images.');
                } catch (\Exception $e) {
            Log::error('Product store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Check logs.');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); 
        return view('admin.products.edit', compact('product', 'categories'));
    }
 
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'cut_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);
    
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->cut_price = $request->cut_price;
        $product->stock_quantity = $request->stock_quantity ?? 0;
        $product->rating = $request->rating ?? 0;
        $product->status = $request->status;
        $product->tags = $request->tags;
        $product->is_top_selling = $request->has('is_top_selling') ? 1 : 0;
        $product->is_new_arrival = $request->has('is_new_arrival') ? 1 : 0;
        $product->is_feature_card = $request->has('is_feature_card') ? 1 : 0;
    
        $offer = null;
        if ($request->cut_price && $request->price && $request->cut_price > $request->price) {
            $offer = round((($request->cut_price - $request->price) / $request->cut_price) * 100);
        }
        $product->offer = $offer;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Save in storage/app/public/uploads/categories/
            $imagePath = $image->storeAs('uploads/categories', $imageName, 'public');
        
            // Store the relative path in DB
            $product->image = $imagePath;
        }
    
        $product->save();
    
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }


    public function filter(Request $request)
    {
        $query = Product::query();
    
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('stock')) {
            if ($request->stock == 'in-stock') {
                $query->where('stock_quantity', '>', 10);
            } elseif ($request->stock == 'low-stock') {
                $query->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0);
            } elseif ($request->stock == 'out-of-stock') {
                $query->where('stock_quantity', '=', 0);
            }
        }
    
        $categories = Category::all();
    
        $products = $query->latest()->paginate(5)->withQueryString();


        return view('admin.products.index', compact('products', 'categories'));
    
    }

    public function search(Request $request)
{
    $query = Product::query();

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('price', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('offer', 'like', "%{$search}%")
              ->orWhere('stock_quantity', 'like', "%{$search}%");



        });
    }

    $products = $query->latest()->paginate()->withQueryString();
    $categories = Category::all();
    $statuses = ['active', 'inactive'];

    return view('admin.products.index', compact('products', 'categories', 'statuses'));
}



    public function destroy(Product $product)
{
    if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
        unlink(public_path('uploads/products/' . $product->image));
    }

    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
}


    
}
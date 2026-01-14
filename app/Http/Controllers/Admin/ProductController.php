<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display product management page
     */
    public function index()
    {
        return view('admin.products.index');
    }

    /**
     * Get products with filters (AJAX)
     */
    public function getProducts(Request $request)
    {
        try {
            $query = Product::with(['category', 'images' => function($query) {
                $query->orderBy('type')->orderBy('sort_order');
            }, 'productDetail']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('tags', 'like', "%{$search}%")
                      ->orWhereHas('productDetail', function($q) use ($search) {
                          $q->where('model_name', 'like', "%{$search}%")
                            ->orWhere('reference_number', 'like', "%{$search}%")
                            ->orWhere('detailed_description', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('stock')) {
                switch ($request->stock) {
                    case 'in-stock':
                        $query->where('stock_quantity', '>', 10);
                        break;
                    case 'low-stock':
                        $query->whereBetween('stock_quantity', [1, 10]);
                        break;
                    case 'out-of-stock':
                        $query->where('stock_quantity', 0);
                        break;
                }
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortDir = $request->get('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDir);

            $perPage = $request->get('per_page', 15);
            $products = $query->paginate($perPage);

            $formattedProducts = $products->map(function ($product) {
                $mainImage = $product->images->where('type', 'main_image')->sortBy('sort_order')->first();
                $subImage = $product->images->where('type', 'sub_image')->sortBy('sort_order')->first();
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'title' => $product->title,
                    'price' => number_format($product->price, 2),
                    'cut_price' => $product->cut_price ? number_format($product->cut_price, 2) : null,
                    'stock_quantity' => $product->stock_quantity,
                    'status' => $product->status,
                    'status_badge' => $product->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                    'status_text' => ucfirst($product->status),
                    'offer' => $product->offer,
                    'image' => $mainImage ? Storage::disk('public')->url($mainImage->image_path) : asset('images/abc.png'),
                    'sub_image' => $subImage ? Storage::disk('public')->url($subImage->image_path) : null,
                    'category' => [
                        'id' => $product->category->id ?? null,
                        'name' => $product->category->name ?? 'N/A',
                    ],
                    'product_detail' => $product->productDetail ? [
                        'model_name' => $product->productDetail->model_name,
                        'reference_number' => $product->productDetail->reference_number,
                    ] : null,
                    'sort_order' => $product->sort_order,
                    'created_at' => $product->created_at->format('Y-m-d H:i'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i'),
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get categories for dropdown (AJAX)
     */
    public function getCategories()
    {
        try {
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            
            return response()->json([
                'success' => true,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching categories', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories'
            ], 500);
        }
    }

    /**
     * Get analytics data (AJAX)
     */
    public function getAnalytics()
    {
        try {
            $analytics = [
                'total_products' => Product::count(),
                'active_products' => Product::where('status', 'active')->count(),
                'inactive_products' => Product::where('status', 'inactive')->count(),
                'out_of_stock' => Product::where('stock_quantity', 0)->count(),
                'low_stock' => Product::whereBetween('stock_quantity', [1, 10])->count(),
                'top_selling' => Product::where('is_top_selling', true)->count(),
                'new_arrivals' => Product::where('is_new_arrival', true)->count(),
                'featured' => Product::where('is_feature_card', true)->count(),
            ];

            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching analytics', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics'
            ], 500);
        }
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), array_merge(
                Product::validationRules(),
                [
                    'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'sub_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'desktop_detail_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'mobile_detail_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'model_name' => 'nullable|string|max:255',
                    'reference_number' => 'nullable|string|max:255',
'specs' => 'nullable|string',
                    'detailed_description' => 'nullable|string',
                    'gallery_images_sort' => 'nullable|array',
                    'desktop_detail_images_sort' => 'nullable|array',
                    'mobile_detail_images_sort' => 'nullable|array',
                ]
            ));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->only([
                'name', 'title', 'description', 'category_id', 'price', 
                'cut_price', 'stock_quantity', 'rating', 'status', 'sort_order'
            ]);
            if ($request->filled('tags')) {
                $inputTags = $request->tags;
                $tagsArray = [];
            
                if (is_array($inputTags)) {
                    $tagsArray = $inputTags;
                } 
                elseif (is_string($inputTags)) {
                    $decoded = json_decode($inputTags, true);
                    
                    if (is_array($decoded)) {
                        $tagsArray = $decoded;
                    } elseif (str_contains($inputTags, ',')) {
                        $tagsArray = explode(',', $inputTags);
                    } else {
                        $tagsArray = [$inputTags];
                    }
                }
        
                $cleanTags = array_values(array_map('trim', $tagsArray));
                
                $data['tags'] = json_encode($cleanTags);
            
            } else {
                $data['tags'] = null; 
            }

            $data['slug'] = Str::slug($request->name);

            if ($request->cut_price && $request->price && $request->cut_price > $request->price) {
                $data['offer'] = round((($request->cut_price - $request->price) / $request->cut_price) * 100);
            } else {
                $data['offer'] = null;
            }

            $data['is_top_selling'] = $request->boolean('is_top_selling') ? 1 : 0;
            $data['is_new_arrival'] = $request->boolean('is_new_arrival') ? 1 : 0;
            $data['is_feature_card'] = $request->boolean('is_feature_card') ? 1 : 0;

            $product = Product::create($data);

            if ($request->filled('model_name') || $request->filled('reference_number') || 
                $request->filled('specs') || $request->filled('detailed_description')) {

                 
                
                ProductDetail::create([
                    'product_id' => $product->id,
                    'model_name' => $request->model_name,
                    'reference_number' => $request->reference_number,
'specs' => $request->specs ?: null,
                    'detailed_description' => $request->detailed_description,
                ]);
            }

            $this->handleProductImages($product, $request);

            $this->reorderProductSortOrders($product->category_id, $product->id, $product->sort_order);

            DB::commit();

            Log::info('Product created successfully', ['product_id' => $product->id]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'product' => $product->load(['images', 'productDetail'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create product', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }


    public function show($id)
    {
        try {
            $product = Product::with([
                'images' => function($query) {
                    $query->orderBy('type')->orderBy('sort_order');
                },
                'productDetail',
                'category'
            ])->findOrFail($id);
    
            $tags = [];

            if (!empty($product->tags)) {
                if (is_array($product->tags)) {
                    $tags = $product->tags;
                } elseif (is_string($product->tags)) {
                    $decoded = json_decode($product->tags, true);
                    
                    $tags = is_array($decoded) ? $decoded : [];
                }
            }
            $specs = [];
            $detail = $product->productDetail ?? null;
            
            if ($detail && !empty($detail->specs)) {
      
                if (is_array($detail->specs)) {
                    $specs = $detail->specs;
                } elseif (is_string($detail->specs)) {
                    $specs = json_decode($detail->specs, true);
                    if (!is_array($specs)) $specs = [];
                }
            }
    
      
            $images = [
                'main_image' => [],
                'sub_image' => [],
                'gallery_images' => [],
                'desktop_detail_images' => [],
                'mobile_detail_images' => []
            ];
    
            foreach ($product->images as $image) {
                $images[$image->type][] = [
                    'id' => $image->id,
                    'url' => Storage::disk('public')->url($image->image_path),
                    'sort_order' => $image->sort_order,
                ];
            }
    
   
            $productArray = $product->toArray();
            $productArray['tags'] = $tags;
    
            if (!empty($productArray['product_detail']['specs'] ?? null)) {
                $specsValue = $productArray['product_detail']['specs'];
                $productArray['product_detail']['specs'] = is_array($specsValue)
                    ? $specsValue
                    : [$specsValue];
            }
    
    
            return response()->json([
                'success' => true,
                'product' => $productArray,
                'images' => $images,
                'specs' => $specs,
                'product_detail' => $product->productDetail
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error fetching product', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }
    

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::with(['images', 'productDetail'])->findOrFail($id);

            $deletedIds = $request->input('deleted_image_ids');
    
            if (is_string($deletedIds)) {
                $deletedIds = json_decode($deletedIds, true);
            }
            
            if (!is_array($deletedIds)) {
                $deletedIds = [];
            }
    
            $request->merge(['deleted_image_ids' => $deletedIds]);
            $validator = Validator::make($request->all(), array_merge(
                Product::validationRules($id),
                [
                    'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'sub_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'desktop_detail_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'mobile_detail_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                    'model_name' => 'nullable|string|max:255',
                    'reference_number' => 'nullable|string|max:255',
                    'specs' => 'nullable|string',
                    'detailed_description' => 'nullable|string',
                    'deleted_image_ids' => 'nullable|array',
                    'deleted_image_ids.*' => 'integer|exists:product_images,id'
                ]
            ));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->only([
                'name', 'title', 'description', 'category_id', 'price',
                'cut_price', 'stock_quantity', 'rating', 'status', 'sort_order'
            ]);

            if ($request->filled('tags')) {
                if (is_string($request->tags) && str_contains($request->tags, ',')) {
                    $tags = explode(',', $request->tags);
                } else {
                    $tags = is_string($request->tags) ? [$request->tags] : $request->tags;
                }
                $data['tags'] = json_encode(array_map('trim', $tags));
            } else {
                $data['tags'] = null;
            }

            if ($product->name !== $request->name) {
                $data['slug'] = Str::slug($request->name);
            }

            if ($request->cut_price && $request->price && $request->cut_price > $request->price) {
                $data['offer'] = round((($request->cut_price - $request->price) / $request->cut_price) * 100);
            } else {
                $data['offer'] = null;
            }

            $data['is_top_selling'] = $request->boolean('is_top_selling') ? 1 : 0;
            $data['is_new_arrival'] = $request->boolean('is_new_arrival') ? 1 : 0;
            $data['is_feature_card'] = $request->boolean('is_feature_card') ? 1 : 0;

            $product->update($data);

            if ($request->filled('model_name') || $request->filled('reference_number') || 
                $request->filled('specs') || $request->filled('detailed_description')) {
                
                ProductDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'model_name' => $request->model_name,
                        'reference_number' => $request->reference_number,
                        'specs' => $request->specs ? : null,
                        'detailed_description' => $request->detailed_description,
                    ]
                );
            }

            if ($request->filled('deleted_image_ids')) {
                $this->deleteProductImages($product->id, $request->deleted_image_ids);
            }

            $this->handleProductImages($product, $request, true);

            $this->reorderProductSortOrders($product->category_id, $product->id, $product->sort_order);

            DB::commit();

            Log::info('Product updated successfully', ['product_id' => $product->id]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => $product->fresh(['images', 'productDetail'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update product', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::with(['images', 'productDetail'])->findOrFail($id);

            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }

            $folderTypes = [
                'main-image',
                'sub-image',
                'gallery-images',
                'desktop-detail-images',
                'mobile-detail-images'
            ];

            foreach ($folderTypes as $type) {
                $directory = "uploads/products/{$type}/{$id}";
                if (Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->deleteDirectory($directory);
                }
            }

            if ($product->productDetail) {
                $product->productDetail->delete();
            }
            
            $product->images()->delete();
            $product->delete();

            DB::commit();

            Log::info('Product deleted successfully', ['product_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete product', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update product sort order
     */
    public function updateSortOrder(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $request->validate([
                'sort_order' => 'required|integer|min:1',
            ]);

            $oldSortOrder = $product->sort_order;
            $product->sort_order = $request->sort_order;
            $product->save();

            $this->reorderProductSortOrders($product->category_id, $product->id, $request->sort_order, $oldSortOrder);

            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating sort order', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sort order',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update image sort order
     */
    public function updateImageSortOrder(Request $request, $productId)
    {
        try {
            $request->validate([
                'image_id' => 'required|integer|exists:product_images,id',
                'sort_order' => 'required|integer|min:1',
                'type' => 'required|in:main_image,sub_image,gallery_images,desktop_detail_images,mobile_detail_images'
            ]);

            $image = ProductImage::where('product_id', $productId)
                ->where('id', $request->image_id)
                ->firstOrFail();

            $oldSortOrder = $image->sort_order;
            $image->sort_order = $request->sort_order;
            $image->save();

            $this->reorderImageSortOrders($productId, $image->type, $request->sort_order, $oldSortOrder);

            return response()->json([
                'success' => true,
                'message' => 'Image sort order updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating image sort order', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image sort order',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Handle product images upload and storage
     */
    private function handleProductImages(Product $product, Request $request, $isUpdate = false)
    {
        $productId = $product->id;
        
        if ($request->hasFile('main_image')) {
            if ($isUpdate) {
                $existingMainImages = ProductImage::where('product_id', $productId)
                    ->where('type', 'main_image')
                    ->get();
                
                foreach ($existingMainImages as $image) {
                    $this->deleteImageFile($image->image_path);
                    $image->delete();
                }
            }
            
            $this->storeProductImage($productId, $request->file('main_image'), 'main_image', 1);
        }

        if ($request->hasFile('sub_image')) {
            if ($isUpdate) {
                $existingSubImages = ProductImage::where('product_id', $productId)
                    ->where('type', 'sub_image')
                    ->get();
                
                foreach ($existingSubImages as $image) {
                    $this->deleteImageFile($image->image_path);
                    $image->delete();
                }
            }
            
            $this->storeProductImage($productId, $request->file('sub_image'), 'sub_image', 1);
        }

        if ($request->hasFile('gallery_images')) {
            $sortOrders = $request->gallery_images_sort ?? [];
            $sortIndex = ProductImage::where('product_id', $productId)
                ->where('type', 'gallery_images')
                ->max('sort_order') ?? 0;
            
            foreach ($request->file('gallery_images') as $index => $image) {
                $sortOrder = $sortOrders[$index] ?? ($sortIndex + $index + 1);
                $this->storeProductImage($productId, $image, 'gallery_images', $sortOrder);
            }
        }

        if ($request->hasFile('desktop_detail_images')) {
            $sortOrders = $request->desktop_detail_images_sort ?? [];
            $sortIndex = ProductImage::where('product_id', $productId)
                ->where('type', 'desktop_detail_images')
                ->max('sort_order') ?? 0;
            
            foreach ($request->file('desktop_detail_images') as $index => $image) {
                $sortOrder = $sortOrders[$index] ?? ($sortIndex + $index + 1);
                $this->storeProductImage($productId, $image, 'desktop_detail_images', $sortOrder);
            }
        }

        if ($request->hasFile('mobile_detail_images')) {
            $sortOrders = $request->mobile_detail_images_sort ?? [];
            $sortIndex = ProductImage::where('product_id', $productId)
                ->where('type', 'mobile_detail_images')
                ->max('sort_order') ?? 0;
            
            foreach ($request->file('mobile_detail_images') as $index => $image) {
                $sortOrder = $sortOrders[$index] ?? ($sortIndex + $index + 1);
                $this->storeProductImage($productId, $image, 'mobile_detail_images', $sortOrder);
            }
        }

        foreach (['gallery_images', 'desktop_detail_images', 'mobile_detail_images'] as $type) {
            $this->reorderImageSortOrders($productId, $type);
        }
    }

    /**
     * Store single product image with proper folder structure
     */
    private function storeProductImage($productId, $imageFile, $type, $sortOrder)
    {
        $folderName = str_replace('_', '-', $type);
        
        $folder = "uploads/products/{$folderName}/{$productId}";
        
        $filename = $type . '-' . time() . '-' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
        
        $path = $imageFile->storeAs($folder, $filename, 'public');
        
        ProductImage::create([
            'product_id' => $productId,
            'image_path' => $path,
            'type' => $type,
            'sort_order' => $sortOrder
        ]);

        Log::info("Product image stored", [
            'product_id' => $productId,
            'type' => $type,
            'path' => $path,
            'sort_order' => $sortOrder
        ]);
    }

    /**
     * Delete product images by IDs
     */
    private function deleteProductImages($productId, $imageIds)
    {
        $images = ProductImage::where('product_id', $productId)
            ->whereIn('id', $imageIds)
            ->get();

        foreach ($images as $image) {
            $this->deleteImageFile($image->image_path);
            $image->delete();
        }

        Log::info('Product images deleted', [
            'product_id' => $productId,
            'image_ids' => $imageIds
        ]);
    }

    /**
     * Delete single image file
     */
    private function deleteImageFile($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            return true;
        }
        return false;
    }

    /**
     * Reorder image sort orders by type
     */
    private function reorderImageSortOrders($productId, $type, $newSortOrder = null, $oldSortOrder = null)
    {
        $images = ProductImage::where('product_id', $productId)
            ->where('type', $type)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        if ($newSortOrder !== null && $oldSortOrder !== null) {
            foreach ($images as $image) {
                if ($image->sort_order == $oldSortOrder) {
                    $image->sort_order = $newSortOrder;
                    $image->save();
                } elseif ($image->sort_order >= $newSortOrder && $image->sort_order < $oldSortOrder) {
                    $image->sort_order++;
                    $image->save();
                }
            }
        }

        $order = 1;
        $images = ProductImage::where('product_id', $productId)
            ->where('type', $type)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        foreach ($images as $image) {
            if ($image->sort_order != $order) {
                $image->sort_order = $order;
                $image->save();
            }
            $order++;
        }
    }

    /**
     * Reorder product sort orders within category
     */
    private function reorderProductSortOrders($categoryId, $currentProductId, $newSortOrder, $oldSortOrder = null)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('id', '!=', $currentProductId)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        $order = 1;
        foreach ($products as $product) {
            if ($order == $newSortOrder) {
                $order++;
            }
            
            if ($oldSortOrder !== null) {
                if ($product->sort_order == $oldSortOrder) {
                    continue;
                }
                if ($oldSortOrder < $newSortOrder) {
                    if ($product->sort_order > $oldSortOrder && $product->sort_order <= $newSortOrder) {
                        $product->sort_order--;
                        $product->save();
                    }
                } else {
                    if ($product->sort_order >= $newSortOrder && $product->sort_order < $oldSortOrder) {
                        $product->sort_order++;
                        $product->save();
                    }
                }
            }

            if ($product->sort_order != $order) {
                $product->sort_order = $order;
                $product->save();
            }
            
            $order++;
        }

        $this->normalizeSortOrders($categoryId);
    }

    /**
     * Normalize sort orders to ensure sequential numbers
     */
    private function normalizeSortOrders($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        $order = 1;
        foreach ($products as $product) {
            if ($product->sort_order != $order) {
                $product->sort_order = $order;
                $product->save();
            }
            $order++;
        }
    }
}
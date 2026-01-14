<?php

namespace App\Http\Controllers\User\Products;

use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        $rateLimitKey = 'review-submission:' . (Auth::id() ?: $request->ip());
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many review submissions. Please try again later.'
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 3600);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'You must be logged in to post a review.'
                ], 401);
            }

            $orderItem = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->whereIn('status', ['completed', 'delivered']);
                })
                ->latest('created_at')
                ->first();

            if (!$orderItem) {
                return response()->json([
                    'success' => false,
                    'error' => 'You can only review products you have purchased.'
                ], 403);
            }

            // Check if already reviewed for this order
            $alreadyReviewed = Review::where('order_id', $orderItem->order_id)
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            if ($alreadyReviewed) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already reviewed this product from this order.'
                ], 409);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'rating' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:5',
                    Rule::in([1, 2, 3, 4, 5])
                ],
                'comment' => [
                    'required',
                    'string',
                    'min:10',
                    'max:2000',
                    function ($attribute, $value, $fail) {
                        // Basic spam check
                        $spamKeywords = ['http://', 'https://', 'www.', '.com'];
                        foreach ($spamKeywords as $keyword) {
                            if (stripos($value, $keyword) !== false) {
                                $fail('Please remove website links from your review.');
                                break;
                            }
                        }
                    }
                ],
                'images.*' => [
                    'nullable',
                    'image',
                    'mimes:jpeg,png,jpg,webp',
                    'max:5120',
                    'dimensions:max_width=5000,max_height=5000,min_width=100,min_height=100'
                ]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'order_id' => $orderItem->order_id,
                'rating' => $request->rating,
                'comment' => strip_tags($request->comment), 
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $maxImages = min(count($images), 3);
                
                for ($i = 0; $i < $maxImages; $i++) {
                    $image = $images[$i];
                    $filename = 'review_' . $review->id . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                    
                    $path = $image->storeAs('reviews/' . $review->id, $filename, 'public');
                    
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'path' => $path,
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize()
                    ]);
                }
            }

            $this->updateProductRating($product);

            \Log::info('Review submitted', [
                'review_id' => $review->id,
                'product_id' => $product->id,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully and is pending approval.',
                'review_id' => $review->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Review store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Update product average rating
     */
    private function updateProductRating(Product $product)
    {
        try {
            $averageRating = Review::where('product_id', $product->id)
                ->where('status', 'approved')
                ->avg('rating');
            
            $product->update([
                'rating' => round($averageRating, 1)
            ]);
        } catch (\Exception $e) {
            \Log::error('Update product rating error: ' . $e->getMessage());
        }
    }

    /**
     * Get product reviews
     */
    public function index(Product $product)
    {
        try {
            $reviews = Review::with(['user', 'images'])
                ->where('product_id', $product->id)
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'reviews' => $reviews,
                'average_rating' => $product->rating,
                'total_reviews' => $product->reviews()->where('status', 'approved')->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Get reviews error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load reviews.'
            ], 500);
        }
    }
}
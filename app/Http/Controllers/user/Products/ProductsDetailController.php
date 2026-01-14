<?php
namespace App\Http\Controllers\User\Products;
use Exception;
use App\Models\Sale;
use App\Models\Review;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Services\User\CartService;
use App\Models\ProductNotification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\User\CartRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class ProductsDetailController extends Controller
{
    protected $cartService;
    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    /**
     * Display product details
     */
    public function show($slug)
    {
    
            $product = Product::with([
                'category',
                'images',
                'productDetail',
                'reviews.user',
                'reviews.images'
            ])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

            $allImages = collect();
            
            if ($product->mainImage) {
                $allImages->push($product->mainImage->image_path);
            }
            
            if ($product->subImage) {
                $allImages->push($product->subImage->image_path);
            }
            
            $galleryImages = $product->galleryImages->pluck('image_path');
            $allImages = $allImages->merge($galleryImages);
            
            $desktopImages = $product->desktopDetailImages->pluck('image_path');
            
            $mobileImages = $product->mobileDetailImages->pluck('image_path');

            $saleOffer = Sale::where('status', 'active')
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->first();

            $user = Auth::user();
            $userHasPurchased = false;
            $userAlreadyReviewed = false;

            if ($user) {
                $orders = OrderItem::where('product_id', $product->id)
                    ->whereHas('order', function ($q) use ($user) {
                        $q->where('user_id', $user->id)
                            ->whereIn('status', ['completed', 'delivered']);
                    })
                    ->orderByDesc('id')
                    ->get();

                $userHasPurchased = $orders->isNotEmpty();

                if ($userHasPurchased) {
                    $latestOrderItem = $orders->first();
                    $userAlreadyReviewed = Review::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->where('order_id', $latestOrderItem->order_id)
                        ->exists();
                }
            }

            $reviews = $product->reviews;
            $totalReviews = $reviews->count();
            $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

            $starCounts = collect(range(1, 5))->mapWithKeys(function ($i) use ($reviews) {
                return [$i => $reviews->where('rating', $i)->count()];
            })->toArray();

            $starPercentages = [];
            foreach ($starCounts as $star => $count) {
                $starPercentages[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            }

            return view('user.products.product-detail', compact(
                'product',
                'allImages',
                'desktopImages',
                'mobileImages',
                'saleOffer',
                'userHasPurchased',
                'userAlreadyReviewed',
                'reviews',
                'totalReviews',
                'averageRating',
                'starCounts',
                'starPercentages'
            ));

     
    }

    /**
     * Handle product notification subscription
     */
    public function storeNotification(Request $request, $productId)
    {
        $rateLimitKey = 'product-notification:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again in 60 seconds.'
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 60);

        try {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email:rfc,dns',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        $disposableDomains = config('app.disposable_email_domains', [
                            'tempmail.com', 'mailinator.com', 'guerrillamail.com',
                            '10minutemail.com', 'yopmail.com', 'throwawaymail.com'
                        ]);
                        
                        $domain = explode('@', $value)[1] ?? '';
                        
                        if (in_array(strtolower($domain), $disposableDomains)) {
                            $fail('Disposable email addresses are not allowed.');
                        }
                    }
                ],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($productId);

            if ($product->is_in_stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is already in stock.'
                ], 400);
            }

            $userId = Auth::id();
            $guestToken = $userId ? null : $this->getGuestToken($request);

            $existingNotification = ProductNotification::where('product_id', $product->id)
                ->where(function ($query) use ($userId, $guestToken, $request) {
                    $query->where('user_id', $userId)
                        ->orWhere('guest_token', $guestToken)
                        ->orWhere('email', $request->email);
                })
                ->first();

            if ($existingNotification) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already subscribed to notifications for this product.'
                ], 409);
            }

            ProductNotification::create([
                'product_id' => $product->id,
                'user_id' => $userId,
                'guest_token' => $guestToken,
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'notified' => false,
            ]);

            \Log::info('Product notification created', [
                'product_id' => $product->id,
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'We will notify you when the product is back in stock!'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Notification store error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }


    private function getGuestToken(Request $request)
    {
        if (!$request->session()->has('guest_token')) {
            $request->session()->put('guest_token', Str::uuid()->toString());
        }
        
        return $request->session()->get('guest_token');
    }



    
        public function addToCart(CartRequest $request)
        {


            try {   

                


                $count = $this->cartService->addToCart(
                    $request->product_id, 
                    $request->quantity, 
                    CartService::TYPE_CART
                );
    
                return response()->json([
                    'success'    => true,
                    'message'    => 'Product added to cart!',
                    'cart_count' => $count
                ]);
    
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }
    
        public function buyNow(CartRequest $request)
        {
            try {
                $this->cartService->addToCart(
                    $request->product_id, 
                    $request->quantity, 
                    CartService::TYPE_BUY_NOW
                );
    
                session(['checkout_mode' => 'buy_now']);
    
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('user.checkout', ['mode' => 'buy_now'])
                ]);
                
    
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }
    }
   

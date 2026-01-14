<?php

namespace App\Http\Controllers\User\Checkouts;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\ShippingCountry;
use App\Models\OrderAddress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $maxAttempts = 20;
    protected $decayMinutes = 15;
    protected $orderAttempts = 5;
    protected $orderDecayMinutes = 30;

    public function checkoutPage(Request $request)
    {
        try {
            $ip = $request->ip();
            $userAgent = hash('sha256', substr($request->userAgent() ?? '', 0, 200));
            $key = 'checkout-page:' . $ip . ':' . $userAgent;

            Log::info('Checkout page accessed', ['ip' => $ip, 'user_agent' => $request->userAgent()]);

            if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                Log::warning('Rate limit exceeded', [
                    'ip' => $ip,
                    'user_agent' => $request->userAgent(),
                    'available_in_seconds' => $seconds
                ]);
                return redirect()->route('user.welcome')
                    ->with('error', 'Too many attempts. Please wait ' . ceil($seconds / 60) . ' minutes.');
            }

            RateLimiter::hit($key, $this->decayMinutes * 60);
            Log::info('Rate limiter hit recorded', ['key' => $key]);

            $mode = $this->sanitizeInput($request->get('mode', 'cart'));
            $checkoutMode = $this->resolveCheckoutMode($request, $mode);
            Log::info('Checkout mode resolved', ['mode' => $checkoutMode]);

            $cartItems = $this->getCartItems($checkoutMode, $request);
            Log::info('Cart items retrieved', ['count' => $cartItems->count()]);

            if ($cartItems->isEmpty()) {
                Log::info('Cart is empty, redirecting user');
                return redirect()->route('user.welcome')
                    ->with('info', 'Your cart is empty.');
            }

            $validatedItems = $this->validateCartItems($cartItems);
            Log::info('Cart items validated', ['has_errors' => $validatedItems['has_errors'] ?? false]);

            if ($validatedItems['has_errors']) {
                Log::warning('Cart validation errors', ['errors' => $validatedItems['errors']]);
                return redirect()->route('user.welcome')
                    ->with('errors', $validatedItems['errors']);
            }

            $sale = Cache::remember('active_sale', 60, function () {
                Log::info('Fetching active sale from DB');
                return Sale::where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->select(['discount', 'end_time', 'id', 'title'])
                    ->first();
            });
            Log::info('Active sale loaded', ['sale_id' => $sale->id ?? null]);

            $shippingMethods = Cache::remember('shipping_methods_active', 3600, function () {
                Log::info('Fetching active shipping methods from DB');
                return ShippingMethod::where('status', 'active')
                    ->orderByRaw("CASE WHEN name LIKE '%Standard%' THEN 0 ELSE 1 END")
                    ->select(['id', 'name', 'cost', 'delivery_time', 'free_threshold'])
                    ->get();
            });
            Log::info('Shipping methods loaded', ['count' => $shippingMethods->count()]);

            $shippingCountries = Cache::remember('shipping_countries_active', 3600, function () {
                Log::info('Fetching active shipping countries from DB');

                return ShippingCountry::where('status', 'active')
                    ->select(['id', 'name', 'code', 'shipping_rate', 'free_shipping_threshold'])
                    ->orderByRaw("CASE WHEN LOWER(code) = 'pk' THEN 0 ELSE 1 END")
                    ->orderBy('name')
                    ->get();
            });

            Log::info('Shipping countries loaded', ['count' => $shippingCountries->count()]);

            $paymentMethods = Cache::remember('payment_methods_active', 3600, function () {
                Log::info('Fetching active payment methods from DB');
                return PaymentMethod::where('status', 'active')
                    ->select(['id', 'name', 'icon'])
                    ->get();
            });
            Log::info('Payment methods loaded', ['count' => $paymentMethods->count()]);

            $defaultShippingMethod = $shippingMethods->first();
            $defaultShippingCountry = $shippingCountries->first();
            Log::info('Default shipping method & country selected', [
                'method_id' => $defaultShippingMethod->id ?? null,
                'country_id' => $defaultShippingCountry->id ?? null
            ]);

            $summary = $this->calculateCheckoutSummary(
                $validatedItems['cart_items'],
                $sale,
                $defaultShippingMethod,
                null,
                $defaultShippingCountry,
                null
            );
            Log::info('Checkout summary calculated', ['summary' => $summary]);

            $hasFixedCountryRate = $defaultShippingCountry && $defaultShippingCountry->shipping_rate > 0;
            $selectedCountryShippingRate = $hasFixedCountryRate ? $defaultShippingCountry->shipping_rate : 0;

            $initialData = $this->prepareInitialData(
                $validatedItems['cart_items'],
                $summary,
                $shippingMethods,
                $shippingCountries,
                $paymentMethods,
                $sale,
                $defaultShippingMethod->id ?? null,
                $defaultShippingCountry->id ?? null,
                $hasFixedCountryRate,
                $selectedCountryShippingRate
            );
            Log::info('Initial checkout data prepared');

            return view('user.checkout.checkout', compact('initialData'));
        } catch (\Exception $e) {
            Log::error('Checkout Page Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('user.welcome')
                ->with('error', 'Unable to load checkout. Please try again.');
        }
    }

    // FIXED: Apply Promo Code with proper hash handling
    public function applyPromo(Request $request)
    {
        try {
            $fingerprint = $this->generateRequestFingerprint($request);
            $key = 'promo-apply:' . $fingerprint;

            if (RateLimiter::tooManyAttempts($key, 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please try again in 5 minutes.'
                ], 429);
            }
            RateLimiter::hit($key, 300);

            $validator = Validator::make($request->all(), [
                'code' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-_]*$/',
                'shipping_id' => 'nullable|integer|exists:shipping_methods,id',
                'country_id' => 'required|integer|exists:shipping_countries,id',
                'cart_hash' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Invalid input.'], 422);
            }

            // Get cart items
            $checkoutMode = $this->resolveCheckoutMode($request);
            $cartItems = $this->getCartItems($checkoutMode, $request);

            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty.'], 400);
            }

            // Get shipping and country data
            $shippingMethod = $request->shipping_id ?
                ShippingMethod::find($request->shipping_id) : null;
            $shippingCountry = ShippingCountry::find($request->country_id);

            if (!$shippingCountry) {
                return response()->json(['success' => false, 'message' => 'Please select a country first.'], 400);
            }

            // For fixed rate countries, shipping method is not required
            $hasFixedCountryRate = $shippingCountry->shipping_rate > 0;

            if (!$hasFixedCountryRate && !$shippingMethod) {
                return response()->json(['success' => false, 'message' => 'Please select a shipping method.'], 400);
            }

            // Generate fresh cart hash WITHOUT promo code for comparison
            $freshHashWithoutPromo = $this->generateCartHash(
                $cartItems,
                $shippingMethod ? $shippingMethod->id : null,
                $shippingCountry->id,
                null // No promo code for hash comparison
            );

            // Validate cart hash - Compare without promo code
            if (!hash_equals($freshHashWithoutPromo, $request->cart_hash)) {
                // If hash doesn't match, return new hash so client can retry
                return response()->json([
                    'success' => false,
                    'message' => 'Cart updated. Please try again.',
                    'new_hash' => $freshHashWithoutPromo
                ], 400);
            }

            $subtotal = $cartItems->sum(
                fn($item) => ($item->price ?? $item->product->price ?? 0) * $item->quantity
            );

            $sale = Cache::remember('active_sale', 60, function () {
                return Sale::where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->first();
            });

            $saleDiscount = $sale ? ($subtotal * $sale->discount) / 100 : 0;

            $shippingDetails = $this->calculateShippingCost($shippingMethod, $shippingCountry, $subtotal);
    
            // 2. Extract the numeric cost for calculations
            $shippingCostValue = $shippingDetails['cost'];
        
            // If no promo code provided
            if (empty($request->code)) {
                // Use the numeric value here
                $total = max(0.01, $subtotal - $saleDiscount + $shippingCostValue);
        
                Cache::forget('applied_promo:' . $fingerprint);
        
                return response()->json([
                    'success' => true,
                    'message' => $shippingMethod ? 'Shipping updated!' : 'Summary updated!',
                    'data' => [
                        'subtotal' => round($subtotal, 2),
                        'sale_discount' => round($saleDiscount, 2),
                        'promo_discount' => 0,
                        'promo_percent' => 0,
                        'shipping_cost' => round($shippingCostValue, 2), // Numeric value
                        'is_free_shipping' => $shippingDetails['is_free'], // Metadata
                        'shipping_source' => $shippingDetails['free_source'], // Metadata
                        'total' => round($total, 2),
                        'promo_code_id' => null,
                        'currency' => 'PKR',
                        'currency_symbol' => 'Rs',
                        'cart_hash' => $freshHashWithoutPromo,
                    ]
                ]);
            }
        
            // Process promo code
            $promoData = $this->processPromoCode(
                $request->code,
                $subtotal,
                $fingerprint,
                $cartItems
            );
        
            if (!$promoData['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $promoData['message']
                ], 400);
            }
        
            // Use the numeric shipping value for the final total
            $total = max(0.01, $subtotal - $promoData['discount'] + $shippingCostValue);

            // Generate NEW hash WITH promo code for response
            $freshHashWithPromo = $this->generateCartHash(
                $cartItems,
                $shippingMethod ? $shippingMethod->id : null,
                $shippingCountry->id,
                $request->code
            );

            if ($promoData['promo_id']) {
                Cache::put(
                    'applied_promo:' . $fingerprint,
                    [
                        'code' => strtoupper(trim($request->code)),
                        'promo_id' => $promoData['promo_id'],
                        'discount' => $promoData['discount'],
                        'timestamp' => now()->timestamp
                    ],
                    now()->addHours(2)
                );
            }

            return response()->json([
                'success' => true,
                'message' => $promoData['message'],
                'data' => [
                    'subtotal' => round($subtotal, 2),
                    'sale_discount' => round($saleDiscount, 2),
                    'promo_discount' => round($promoData['discount'], 2),
                    'promo_percent' => $promoData['percent'],
                    'shipping_cost' => round($shippingCostValue, 2), 
                    'is_free_shipping' => $shippingDetails['is_free'], 
                    'shipping_source' => $shippingDetails['free_source'], 
                    'total' => round($total, 2),
                    'promo_code_id' => $promoData['promo_id'],
                    'currency' => 'PKR',
                    'currency_symbol' => 'Rs',
                    'cart_hash' => $freshHashWithPromo,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Apply promo error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'code' => $request->code ?? null
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Could not apply code.'
            ], 500);
        }
    }

    // FIXED: Cart Summary method
    public function cartSummary(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shipping_method_id' => 'nullable|integer|exists:shipping_methods,id',
                'country_id' => 'required|integer|exists:shipping_countries,id',
                'code' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Invalid input'], 422);
            }

            $checkoutMode = $this->resolveCheckoutMode($request);
            $cartItems = $this->getCartItems($checkoutMode, $request);

            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
            }

            $shippingMethod = $request->shipping_method_id ?
                ShippingMethod::find($request->shipping_method_id) : null;
            $shippingCountry = ShippingCountry::find($request->country_id);

            if (!$shippingCountry) {
                return response()->json(['success' => false, 'message' => 'Please select a country'], 400);
            }

            // For fixed rate countries, shipping method is not required
            $hasFixedCountryRate = $shippingCountry->shipping_rate > 0;

            if (!$hasFixedCountryRate && !$shippingMethod) {
                return response()->json(['success' => false, 'message' => 'Please select a shipping method'], 400);
            }

            $sale = Cache::remember('active_sale', 60, function () {
                return Sale::where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->first();
            });

            // Calculate summary WITHOUT promo code in hash for cart summary endpoint
            $summary = $this->calculateCheckoutSummary(
                $cartItems,
                $sale,
                $shippingMethod,
                null,
                $shippingCountry,
                null // No promo code for cart summary hash
            );

            return response()->json([
                'success' => true,
                'cart_hash' => $summary['cart_hash'],
                'subtotal' => $summary['subtotal'] ?? 0,
                'total' => $summary['total'] ?? 0,
                'shipping_cost' => $summary['shipping_cost'] ?? 0,
                'promo_discount' => $summary['promo_discount'] ?? 0,
                'sale_discount' => $summary['sale_discount'] ?? 0,
                'has_fixed_country_rate' => $hasFixedCountryRate,
                'country_shipping_rate' => $hasFixedCountryRate ? $shippingCountry->shipping_rate : 0,
                'country_free_shipping_threshold' => $shippingCountry->free_shipping_threshold ?? 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Cart summary error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to calculate summary'], 500);
        }
    }

    // FIXED: Place Order method
    public function placeOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $fingerprint = $this->generateRequestFingerprint($request);
            $key = 'order-place:' . $fingerprint;

            if (RateLimiter::tooManyAttempts($key, $this->orderAttempts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many order attempts. Please wait and try again.'
                ], 429);
            }
            RateLimiter::hit($key, $this->orderDecayMinutes * 60);

            // Validate all inputs
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:100',
                'first_name' => 'required|string|max:50|regex:/^[\pL\s\-]+$/u',
                'last_name' => 'required|string|max:50|regex:/^[\pL\s\-]+$/u',
                'address' => 'required|string|max:200',
                'address2' => 'nullable|string|max:200',
                'city' => 'required|string|max:50',
                'zip' => 'nullable|string|max:20',
                'phone' => 'required|string|max:20|regex:/^[\d\s\-\+\(\)]+$/',
                'country_id' => 'required|integer|exists:shipping_countries,id',
                'shipping_method_id' => 'nullable|integer|exists:shipping_methods,id',
                'payment_method_id' => 'required|integer|exists:payment_methods,id',
                'agree_terms' => 'required|accepted',
                'total_amount' => 'required|numeric|min:0.01',
                'billing_same' => 'required',
                'billing_first_name' => 'exclude_if:billing_same,true,1,"true"|required|string|max:50',
                'billing_last_name'  => 'exclude_if:billing_same,true,1,"true"|required|string|max:50',
                'billing_address'    => 'exclude_if:billing_same,true,1,"true"|required|string|max:200',
                'billing_city'       => 'exclude_if:billing_same,true,1,"true"|required|string|max:50',
                'billing_phone'      => 'exclude_if:billing_same,true,1,"true"|required|string|max:20',
                'billing_zip'        => 'exclude_if:billing_same,true,1,"true"|nullable|string|max:20',

            ]);
            if ($validator->fails()) {
                Log::warning('Order Validation Failed', [
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->except(['card_number', 'cvv']),
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Please check your information.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $checkoutMode = $this->resolveCheckoutMode($request);
            [$userId, $guestToken] = $this->resolveUser($request);

            $cartItems = $this->getCartQuery($userId, $guestToken)
                ->with(['product' => function ($query) {
                    $query->lockForUpdate()
                        ->select('id', 'name', 'price', 'stock_quantity', 'status');
                }])
                ->where('is_buy_now', $checkoutMode === 'buy_now' ? 1 : 0)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }

            $validation = $this->validateOrderItems($cartItems);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $shippingMethod = $request->shipping_method_id ?
                ShippingMethod::find($request->shipping_method_id) : null;
            $shippingCountry = ShippingCountry::findOrFail($request->country_id);
            $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

            $sale = Cache::remember('active_sale', 60, function () {
                return Sale::where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now())
                    ->first();
            });

            $promoCode = null;
            $promoDiscount = 0;
            $sessionKey = 'applied_promo:' . $fingerprint;
            if (Cache::has($sessionKey)) {
                $promoData = Cache::get($sessionKey);
                if (isset($promoData['timestamp']) && (now()->timestamp - $promoData['timestamp'] <= 300)) {
                    $promoCode = PromoCode::where('id', $promoData['promo_id'])
                        ->where('status', 'active')
                        ->first();
                    $promoDiscount = $promoData['discount'] ?? 0;
                }
            }

            $totals = $this->calculateCartTotals(
                $cartItems,
                $shippingMethod,
                $shippingCountry,
                $promoCode,
                $sale
            );

            if (abs($totals['total'] - $request->total_amount) > 0.01) {
                Log::warning('Price tampering detected', [
                    'calculated' => $totals['total'],
                    'submitted' => $request->total_amount,
                    'ip' => $request->ip()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Price mismatch detected. Please refresh and try again.'
                ], 400);
            }

            $orderNumber = $this->generateSecureOrderNumber();

            $order = Order::create([
                'order_code' => $orderNumber,
                'user_id' => $userId,
                'guest_token' => $guestToken,
                'status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'sale_discount' => $totals['sale_discount'],
                'promo_discount' => $totals['promo_discount'],
                'promo_code_id' => $promoCode?->id,
                'shipping_cost' => $totals['shipping_cost'],
                'is_free_shipping'      => $totals['is_free'],       
                'shipping_free_source'  => $totals['free_source'],  
                'total_amount' => $totals['total'],

                'country_id'               => $shippingCountry->id,
                'shipping_country_name'    => $shippingCountry->name,
                'shipping_method_id'       => $shippingMethod?->id,
                'shipping_method_name'     => $shippingMethod ? $shippingMethod->name : 'Fixed Country Rate',

                'billing_same_as_shipping' => (bool)$request->billing_same,
                'payment_method_id' => $paymentMethod->id,
                'notes' => $request->notes,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 200),
                'fingerprint' => $fingerprint,
                'placed_at' => now(),
            ]);
            $shippingData = [
                'order_id'   => $order->id,
                'first_name' => $this->sanitizeInput($request->first_name),
                'last_name'  => $this->sanitizeInput($request->last_name),
                'email'      => $this->sanitizeInput($request->email),
                'phone'      => $this->sanitizeInput($request->phone),
                'address_1'  => $this->sanitizeInput($request->address),
                'address_2'  => $this->sanitizeInput($request->address2),
                'city'       => $this->sanitizeInput($request->city),
                'zip'        => $this->sanitizeInput($request->zip),
                'country'    => $request->country_id,
            ];

            OrderAddress::create(array_merge($shippingData, ['type' => 'shipping']));


            $isBillingSame = filter_var($request->billing_same, FILTER_VALIDATE_BOOLEAN);

            if ($isBillingSame) {
                OrderAddress::create(array_merge($shippingData, ['type' => 'billing']));
            } else {
                OrderAddress::create([
                    'order_id'   => $order->id,
                    'type'       => 'billing',
                    'email'      => $this->sanitizeInput($request->email),
                    'first_name' => $this->sanitizeInput($request->billing_first_name),
                    'last_name'  => $this->sanitizeInput($request->billing_last_name),
                    'phone'      => $this->sanitizeInput($request->billing_phone),
                    'address_1'  => $this->sanitizeInput($request->billing_address),
                    'address_2'  => $this->sanitizeInput($request->billing_address2),
                    'city'       => $this->sanitizeInput($request->billing_city),
                    'zip'        => $this->sanitizeInput($request->billing_zip),
                    'country'    => $request->country_id,
                ]);
            }

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                if ($product->stock_quantity < $cartItem->quantity) {
                    throw new \Exception("Product {$product->name} just went out of stock.");
                }
            
                $price = $product->price;
                $price = $cartItem->price ?? $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $price * $cartItem->quantity,
                ]);

                $product->decrement('stock_quantity', $cartItem->quantity);
            
            }
            // Clear cart
            $cartItems->each->delete();

            Cache::forget($sessionKey);

            if ($request->save_info && $userId) {
                $user = Auth::user();
                $user->update([
                    'first_name' => $this->sanitizeInput($request->first_name),
                    'last_name' => $this->sanitizeInput($request->last_name),
                    'phone' => $this->sanitizeInput($request->phone),
                ]);
            }

            DB::commit();

            // Invalidate relevant caches
            Cache::forget('shipping_methods_active');
            Cache::forget('payment_methods_active');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_number' => $orderNumber,
                'redirect_url' => route('user.order.confirmation', ['order' => $order->order_code])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }

    public function orderConfirmation($order_code)
    {
        return view('user.checkout.confirmation', ['order_code' => $order_code]);
    }

    /* -------------------- HELPER METHODS -------------------- */

    private function generateSecureOrderNumber(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(bin2hex(random_bytes(4)));
        return 'ORD-' . $timestamp . '-' . $random;
    }

    private function generateRequestFingerprint(Request $request): string
    {
        $components = [
            $request->ip(),
            hash('sha256', $request->userAgent() ?? 'unknown_ua'),
            $request->header('Accept-Language') ?? 'en',
        ];
        return hash('sha256', implode('|', $components) . config('app.key'));
    }

    private function generateCartHash($cartItems, $shippingId = null, $countryId = null, $promoCode = null): string
    {
        $cartData = $cartItems->map(function ($item) {
            return $item->id . $item->product_id . $item->quantity . ($item->price ?? $item->product->price ?? 0);
        })->implode('');

        $data = $cartData . ($shippingId ?? '') . ($countryId ?? '') . ($promoCode ?? '');

        return hash_hmac('sha256', $data, config('app.key'));
    }

    private function validateCartItems($cartItems): array
    {
        $errors = [];
        $validItems = collect();
        $productIds = $cartItems->pluck('product_id')->unique()->toArray();

        // Batch load products
        $products = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->get(['id', 'name', 'price', 'stock_quantity', 'slug', 'status'])
            ->keyBy('id');

        $totalItems = 0;
        $hasErrors = false;

        foreach ($cartItems as $cartItem) {
            $product = $products->get($cartItem->product_id);

            if (!$product) {
                $errors[$cartItem->id] = 'Product no longer available.';
                $hasErrors = true;
                continue;
            }

            if ($product->stock_quantity < $cartItem->quantity) {
                $errors[$cartItem->id] = "Only {$product->stock_quantity} left in stock.";
                $hasErrors = true;
                continue;
            }

            if ($cartItem->quantity > 50) {
                $errors[$cartItem->id] = 'Maximum 50 items per product allowed.';
                $hasErrors = true;
                continue;
            }

            $totalItems += $cartItem->quantity;
            $cartItem->setRelation('product', $product);
            $validItems->push($cartItem);
        }

        if ($totalItems > 100) {
            $hasErrors = true;
            $errors['cart'] = 'Maximum 100 items allowed per order.';
        }

        Log::info('Cart items validated', [
            'has_errors' => $hasErrors,
            'errors' => $errors,
            'cart_items' => $validItems->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'stock_quantity' => $item->product->stock_quantity ?? null,
                'status' => $item->product->status ?? null,
            ]),
            'total_items' => $totalItems
        ]);

        return [
            'has_errors' => $hasErrors,
            'errors' => $errors,
            'cart_items' => $validItems,
            'total_items' => $totalItems
        ];
    }


    private function validateOrderItems($cartItems): array
    {
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;

            if (!$product || $product->status !== 'active') {
                return [
                    'valid' => false,
                    'message' => 'Product "' . ($product->name ?? 'Unknown') . '" is no longer available.'
                ];
            }

            if ($product->stock_quantity < $cartItem->quantity) {
                return [
                    'valid' => false,
                    'message' => 'Insufficient stock for "' . $product->name . '". Only ' . $product->stock_quantity . ' left.'
                ];
            }
        }

        return ['valid' => true];
    }

    private function processPromoCode($code, $subtotal, $fingerprint, $cartItems): array
    {
        $code = strtoupper(trim($code));

        if (empty($code)) {
            return [
                'success' => true,
                'message' => 'Shipping updated!',
                'discount' => 0,
                'percent' => 0,
                'promo_id' => null
            ];
        }

        $promo = PromoCode::where('code', $code)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promo) {
            return [
                'success' => false,
                'message' => 'Invalid promo code.'
            ];
        }

        // Validate minimum purchase
        if ($promo->min_purchase_amount && $subtotal < $promo->min_purchase_amount) {
            $needed = number_format($promo->min_purchase_amount - $subtotal, 2);
            return [
                'success' => false,
                'message' => "Add items worth Rs. {$needed} more to use this code."
            ];
        }

        // Validate usage limit
        if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) {
            return [
                'success' => false,
                'message' => 'This promo code has expired.'
            ];
        }

        // Validate per-user limit
        $usageCount = $this->getPromoUsageCount($promo->id, $fingerprint);
        if ($promo->usage_per_user && $usageCount >= $promo->usage_per_user) {
            return [
                'success' => false,
                'message' => 'You have already used this code the maximum number of times.'
            ];
        }

        // Calculate discount
        $discount = ($subtotal * $promo->discount_percent) / 100;

        if ($promo->max_discount_amount && $discount > $promo->max_discount_amount) {
            $discount = $promo->max_discount_amount;
        }

        return [
            'success' => true,
            'message' => 'Promo code applied!',
            'discount' => round($discount, 2),
            'percent' => $promo->discount_percent,
            'promo_id' => $promo->id
        ];
    }

    private function getPromoUsageCount($promoId, $fingerprint): int
    {
        if (Auth::check()) {
            return Order::where('user_id', Auth::id())
                ->where('promo_code_id', $promoId)
                ->count();
        }

        $guestUsageKey = 'guest_promo_usage:' . $fingerprint . ':' . $promoId;
        return (int) Cache::get($guestUsageKey, 0);
    }

    private function prepareInitialData($cartItems, $summary, $shippingMethods, $shippingCountries, $paymentMethods, $sale, $defaultShippingMethodId, $defaultShippingCountryId, $hasFixedCountryRate = false, $selectedCountryShippingRate = 0)
    {
        $cartData = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => e($item->product->name),
                'price' => (float) ($item->price ?? $item->product->price),
                'quantity' => (int) $item->quantity,
                'image' => $this->sanitizeUrl($item->product->mainImage->image_path ?? null),
                'max_stock' => (int) $item->product->stock_quantity,
            ];
        })->toArray();

        $data = [
            'cart' => $cartData,
            'summary' => $summary,
            'shippingMethods' => $shippingMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'cost' => (float) $method->cost,
                    'delivery_time' => $method->delivery_time,
                    'free_threshold' => $method->free_threshold ? (float) $method->free_threshold : null,
                ];
            })->toArray(),
            'shippingCountries' => $shippingCountries->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'shipping_rate' => $country->shipping_rate ? (float) $country->shipping_rate : 0,
                    'free_shipping_threshold' => $country->free_shipping_threshold ? (float) $country->free_shipping_threshold : null,
                ];
            })->toArray(),
            'paymentMethods' => $paymentMethods->toArray(),
            'sale' => $sale ? [
                'id' => $sale->id,
                'title' => $sale->title,
                'discount' => (float) $sale->discount,
                'end_time' => $sale->end_time,
            ] : null,
            'user' => Auth::check() ? Auth::user()->only(['email', 'first_name', 'last_name', 'phone']) : null,
            'csrf_token' => csrf_token(),
            'default_shipping_method_id' => $defaultShippingMethodId,
            'default_shipping_country_id' => $defaultShippingCountryId,
            'has_fixed_country_rate' => $hasFixedCountryRate,
            'selected_country_shipping_rate' => $selectedCountryShippingRate,
            'timestamp' => now()->timestamp,
        ];

        $data['hash'] = hash_hmac('sha256', json_encode($data), config('app.key'));

        return $data;
    }

    private function sanitizeInput($input)
    {
        if (!is_string($input)) {
            return $input;
        }

        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        $input = trim($input);
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);

        return $input;
    }

    private function sanitizeUrl($url)
    {
        if (empty($url) || !is_string($url)) {
            return null;
        }

        $url = html_entity_decode($url);
        $url = preg_replace('/[\r\n\t\x00-\x1F]/', '', $url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        if (!preg_match('/^https?:\/\//i', $url)) {
            return null;
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }

    private function calculateCartTotals($cartItems, $shippingMethod, $shippingCountry, $promoCode = null, $sale = null)
    {
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->price ?? $item->product->price ?? 0;
            return $price * $item->quantity;
        });

        $saleDiscount = $sale ? ($subtotal * $sale->discount) / 100 : 0;
        $promoDiscount = 0;
        $promoPercent = 0;

        if ($promoCode) {
            $promoPercent = $promoCode->discount_percent;
            $calculatedDiscount = ($subtotal * $promoPercent) / 100;

            if ($promoCode->max_discount_amount && $calculatedDiscount > $promoCode->max_discount_amount) {
                $calculatedDiscount = $promoCode->max_discount_amount;
            }
            $promoDiscount = round($calculatedDiscount, 2);
        }

        // Capture the detailed shipping info
        $shippingDetails = $this->calculateShippingCost($shippingMethod, $shippingCountry, $subtotal);

        $total = max(0.01, $subtotal - $promoDiscount + $shippingDetails['cost']);

        return [
            'subtotal' => round($subtotal, 2),
            'sale_discount' => round($saleDiscount, 2),
            'promo_discount' => $promoDiscount,
            'promo_percent' => $promoPercent,
            'shipping_cost' => round($shippingDetails['cost'], 2),
            'is_free' => $shippingDetails['is_free'], // New
            'free_source' => $shippingDetails['free_source'], // New
            'total' => round($total, 2)
        ];
    }

    private function calculateCheckoutSummary($cartItems, $sale, $shippingMethod, $promoCode = null, $shippingCountry = null, $promoCodeString = null)
    {
        $totals = $this->calculateCartTotals($cartItems, $shippingMethod, $shippingCountry, $promoCode, $sale);

        $cartHash = $this->generateCartHash(
            $cartItems,
            $shippingMethod?->id,
            $shippingCountry?->id,
            $promoCodeString
        );

        return array_merge($totals, [
            'currency' => 'PKR',
            'currency_symbol' => 'Rs',
            'cart_hash' => $cartHash,
        ]);
    }

    private function calculateShippingCost($shippingMethod, $shippingCountry, $subtotal)
    {
        $cost = 0;
        $isFree = false;
        $source = null;

        // 1. Priority: If a specific Shipping Method is selected
        if ($shippingMethod) {
            $cost = (float) $shippingMethod->cost;

            if ($shippingMethod->free_threshold > 0 && $subtotal >= $shippingMethod->free_threshold) {
                $cost = 0;
                $isFree = true;
                $source = 'method_threshold';
            }
        } elseif ($shippingCountry && $shippingCountry->shipping_rate > 0) {
            $cost = (float) $shippingCountry->shipping_rate;

            if ($shippingCountry->free_shipping_threshold > 0 && $subtotal >= $shippingCountry->free_shipping_threshold) {
                $cost = 0;
                $isFree = true;
                $source = 'country_threshold';
            }
        }

        return [
            'cost' => $cost,
            'is_free' => $isFree,
            'free_source' => $source
        ];
    }

    private function resolveUser(Request $request): array
    {
        if (Auth::check()) {
            return [Auth::id(), null];
        }

        $guestToken = $request->cookie('guest_token');
        if (!$guestToken) {
            $guestToken = Str::uuid()->toString();
            Cookie::queue('guest_token', $guestToken, 60 * 24 * 30, null, null, true, true, 'Strict');
        }

        return [null, $guestToken];
    }

    private function getCartQuery($userId, $guestToken)
    {
        if ($userId) {
            Log::info('Building cart query for user', ['user_id' => $userId]);
            $query = CartItem::where('user_id', $userId);
        } else {
            Log::info('Building cart query for guest', ['guest_token' => $guestToken]);
            $query = CartItem::where('guest_token', $guestToken);
        }

        Log::info('Cart query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query;
    }

    private function resolveCheckoutMode(Request $request, ?string $force = null): string
    {
        if ($force && in_array($force, ['cart', 'buy_now'])) {
            session(['checkout_mode' => $force]);
            return $force;
        }

        $mode = session('checkout_mode', 'cart');
        return in_array($mode, ['cart', 'buy_now']) ? $mode : 'cart';
    }

    private function getCartItems(string $mode, Request $request)
    {
        [$userId, $guestToken] = $this->resolveUser($request);

        Log::info('Fetching cart items', [
            'user_id' => $userId,
            'guest_token' => $guestToken,
            'mode' => $mode
        ]);

        $cartItems = $this->getCartQuery($userId, $guestToken)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'slug', 'price', 'stock_quantity', 'status')
                    ->with(['mainImage' => function ($q) {
                        $q->select('id', 'product_id', 'image_path');
                    }]);
            }])
            ->where('is_buy_now', $mode === 'buy_now' ? 1 : 0)
            ->get();


        Log::info('Cart items retrieved', [
            'user_id' => $userId,
            'guest_token' => $guestToken,
            'mode' => $mode,
            'count' => $cartItems->count(),
            'item_ids' => $cartItems->pluck('product_id')->toArray()
        ]);

        return $cartItems;
    }
}

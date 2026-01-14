<?php

namespace App\Http\Controllers\User\Checkouts;

use App\Models\Sale;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view('user.checkout.cart');
    }





    public function getCart(Request $request)
    {
        // 1. [User Auth Logic - Same as before]
        if (auth()->check()) {
            $userId = auth()->id();
            $guestToken = null;
        } else {
            $userId = null;
            $guestToken = $request->cookie('guest_token');
        }
        $cartItems = CartItem::with('product.mainImage')
        ->where('is_buy_now', 0)
        ->where(function ($query) use ($userId, $guestToken) {
            $query->when($userId, fn($q) => $q->where('user_id', $userId))
                  ->when($guestToken, fn($q) => $q->orWhere('guest_token', $guestToken));
        })
        ->get()
        ->map(function ($item) {
            // 1. Log the raw relationship data
            Log::info('Cart Item ID: ' . $item->id);
            Log::info('Product ID: ' . ($item->product->id ?? 'N/A'));
            
            $path = $item->product->mainImage->image_path ?? null;
            
            // 2. Log the path found
            Log::info('Found Path: ' . ($path ?? 'NULL'));
    
            $item->img = $path ? asset('storage/' . $path) : asset('images/placeholder.jpg');
    
            // 3. Log the final generated URL
            Log::info('Generated URL: ' . $item->img);
    
            return $item;
        });
        // 3. Calculate Subtotal
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        // 4. --- INTELLIGENT SHIPPING FILTER ---

        // Get all active methods sorted by cost (cheapest first)
        $methods = ShippingMethod::where('status', 1)->orderBy('cost', 'asc')->get();

        // Default values
        $shippingCost = 0;
        $shippingName = 'Shipping';
        $threshold    = 0;

        // A. Check if user qualifies for FREE SHIPPING on any method
        // We look for a method where the subtotal is greater than the free_threshold
        $qualifiedFreeMethod = $methods->first(function ($method) use ($subtotal) {
            return $method->free_threshold > 0 && $subtotal >= $method->free_threshold;
        });

        if ($qualifiedFreeMethod) {
            // CASE: User unlocked Free Shipping
            $shippingCost = 0;
            $shippingName = $qualifiedFreeMethod->name;
            $threshold    = $qualifiedFreeMethod->free_threshold; // Keep this so UI knows they met it
        } else {
            // CASE: User pays for shipping. 
            // We pick the Cheapest method (first one because we sorted by cost asc)
            $standardMethod = $methods->first();

            if ($standardMethod) {
                $shippingCost = $standardMethod->cost;
                $shippingName = $standardMethod->name;
                $threshold    = $standardMethod->free_threshold;
            }
        }
        // 4. --- END SHIPPING LOGIC ---

        // 5. Grand Total
        $grandTotal = $subtotal + $shippingCost;

        $sale = Sale::where('status', 'active')->first();

        return response()->json([
            'items'         => $cartItems,
            'subtotal'      => $subtotal,
            'shipping_cost' => $shippingCost,
            'shipping_name' => $shippingName,
            'threshold'     => $threshold,
            'grand_total'   => $grandTotal,
            'sale'          => $sale
        ]);
    }
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'change'       => 'required|integer'
        ]);

        $user = $request->user();
        $guestToken = $request->cookie('guest_token');

        return DB::transaction(function () use ($request, $user, $guestToken) {

            $query = CartItem::where('id', $request->cart_item_id);

            if ($user) {
                $query->where('user_id', $user->id);
            } elseif ($guestToken) {
                $query->where('guest_token', $guestToken)->whereNull('user_id');
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $cartItem = $query->with('product')->lockForUpdate()->first();

            if (!$cartItem) {
                return response()->json(['status' => 'error', 'message' => 'Item not found.'], 404);
            }

            $product = $cartItem->product;

            if (!$product) {

                $cartItem->delete();
                return response()->json(['status' => 'error', 'message' => 'Product no longer exists.'], 422);
            }


            $newQty = $cartItem->quantity + $request->change;
            if ($newQty < 1) $newQty = 1;

            $currentStock = (int) $product->stock_quantity;

            if ($newQty > $currentStock) {
                return response()->json([
                    'status'   => 'error',
                    'message'  => "Only {$currentStock} items available in stock.",
                    'quantity' => $cartItem->quantity
                ], 422);
            }

            $cartItem->quantity = $newQty;
            $cartItem->save();

            if ($user) {
                $user->unsetRelation('cart');
            }

            return $this->getCart($request);
        });
    }

    public function removeCart(Request $request)
    {
        $request->validate(['cart_item_id' => 'required|integer']);

        $user = $request->user();
        $guestToken = $request->cookie('guest_token');

        $query = CartItem::where('id', $request->cart_item_id);

        if ($user) {
            $query->where('user_id', $user->id);
        } elseif ($guestToken) {
            $query->where('guest_token', $guestToken)->whereNull('user_id');
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cartItem = $query->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return $this->getCart($request);
    }
}

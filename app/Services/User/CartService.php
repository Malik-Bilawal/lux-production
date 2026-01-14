<?php
namespace App\Services\User;

use Exception;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    const TYPE_CART = 0;
    const TYPE_BUY_NOW = 1;



    public function resolveIdentity()
    {
        $userId = Auth::id();
        $guestToken = request()->cookie('guest_token');
    
        if (!$userId && !$guestToken) {
            $guestToken = Str::uuid()->toString();
            Cookie::queue('guest_token', $guestToken, 60 * 24 * 30);
        }
    
        Log::info('resolveIdentity', ['userId' => $userId, 'guestToken' => $guestToken]);
    
        return [$userId, $guestToken];
    }
    
    public function addToCart(int $productId, int $quantity, int $type = self::TYPE_CART)
    {
        return DB::transaction(function () use ($productId, $quantity, $type) {
            
            [$userId, $guestToken] = $this->resolveIdentity();
    
            Log::info('Adding to cart', [
                'productId' => $productId,
                'quantity' => $quantity,
                'userId' => $userId,
                'guestToken' => $guestToken,
                'type' => $type,
            ]);
    
            $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
            Log::info('Product fetched', ['product' => $product->toArray()]);
    
            if ($product->stock_quantity < $quantity) {
                Log::warning('Not enough stock', ['available' => $product->stock_quantity, 'requested' => $quantity]);
                throw new Exception("Sorry, we only have {$product->stock_quantity} items left in stock.");
            }
    
            if ($type === self::TYPE_BUY_NOW) {
                $deleted = $this->getQuery($userId, $guestToken)
                    ->where('is_buy_now', self::TYPE_BUY_NOW)
                    ->delete();
                Log::info('Deleted previous buy_now items', ['count' => $deleted]);
            }
    
            $cartItem = $this->getQuery($userId, $guestToken)
                ->where('product_id', $product->id)
                ->where('is_buy_now', $type)
                ->first();
    
            if ($cartItem) {
                if (($cartItem->quantity + $quantity) > $product->stock_quantity) {
                    Log::warning('Quantity exceeds stock', [
                        'cartQuantity' => $cartItem->quantity,
                        'requested' => $quantity,
                        'stock' => $product->stock_quantity,
                    ]);
                    throw new Exception("You cannot add more of this item.");
                }
                $cartItem->increment('quantity', $quantity);
                Log::info('Incremented existing cart item', ['cartItemId' => $cartItem->id, 'newQuantity' => $cartItem->quantity + $quantity]);
            } else {
                $newItem = CartItem::create([
                    'user_id'     => $userId,
                    'guest_token' => $guestToken, 
                    'product_id'  => $product->id,
                    'quantity'    => $quantity,
                    'price'       => $product->price, 
                    'is_buy_now'  => $type,
                ]);
                Log::info('Created new cart item', ['cartItemId' => $newItem->id ?? null]);
            }
    
            $count = $this->count($userId, $guestToken);
            Log::info('Cart count after add', ['count' => $count]);
    
            return $count;
        });
    }
    
    public function count($userId, $guestToken)
    {
        return $this->getQuery($userId, $guestToken)
            ->where('is_buy_now', self::TYPE_CART)
            ->sum('quantity'); 
    }

    private function getQuery($userId, $guestToken)
    {
        $query = CartItem::query();
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        return $query->where('guest_token', $guestToken);
    }
}
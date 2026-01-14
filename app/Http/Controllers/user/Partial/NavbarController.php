<?php

namespace App\Http\Controllers\User\Partial;

use App\Models\Sale;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NavbarController extends Controller
{

    public function index(Request $request)
    {
        // Get cart items
        if (auth()->check()) {
            $userId = auth()->id();
            $guestToken = null;
        } else {
            $userId = null;
            $guestToken = $request->cookie('guest_token');
        }

        $cartItems = CartItem::where('is_buy_now', 0)
            ->where(function ($query) use ($userId, $guestToken) {
                $query->when($userId, fn($q) => $q->where('user_id', $userId))
                    ->when($guestToken, fn($q) => $q->orWhere('guest_token', $guestToken));
            })
            ->get();

        $cartCount = $cartItems->count();

        // Get active sale
        $sale = Sale::where('status', 'active')->first();

        return view('user.layouts.partial.navbar', compact('cartCount', 'sale'));
    }

    public static function getData()
    {

        $recentProducts = Product::where('status', 'active')
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->take(24)
            ->get(['id', 'name', 'slug', 'price', 'cut_price', 'image', 'category_id', 'created_at']);


        $newArrivals = $recentProducts->unique('category_id');

        if ($newArrivals->count() < 8) {
            $needed = 8 - $newArrivals->count();
            $remaining = $recentProducts->whereNotIn('id', $newArrivals->pluck('id'))->take($needed);
            $newArrivals = $newArrivals->merge($remaining);
        }

        $newArrivals = $newArrivals->sortByDesc('created_at')->take(8)->values();


        // $featuredProducts = Cache::remember('top_selling_products', 21600, function () {
        //     return Product::query()
        //         ->select('products.id', 'products.name', 'products.slug', 'products.price', 'products.image')
        //         ->join('order_items', 'products.id', '=', 'order_items.product_id')
        //         ->join('orders', 'orders.id', '=', 'order_items.order_id')
        //         ->where('orders.status', 'delivered')
        //         ->where('products.status', 'active')
        //         ->selectRaw('SUM(order_items.quantity) as total_sold')
        //         ->groupBy('products.id', 'products.name', 'products.slug', 'products.price', 'products.image')
        //         ->orderByDesc('total_sold')
        //         ->take(5)
        //         ->get();
        // });
        $featuredProducts = Product::where('is_top_selling', 1)
        ->select('products.id', 'products.name', 'products.slug', 'products.price', 'products.cut_price', 'products.description', 'products.image')
        ->take(5)
        ->get(); 
        return compact('featuredProducts', 'newArrivals');
    }
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = Product::with('mainImage')->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->where('status', 'active')
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            $products = Product::whereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
                ->where('status', 'active')
                ->take(10)
                ->get();
        }



        return view('user.components.search-result', compact('products'));
    }





    // Get cart data
 
}

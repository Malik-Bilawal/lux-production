<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use App\Models\WatchBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class WatchesController extends Controller
{
    public function index()
    {
        

        $watchCategory = Category::where('name', 'watches')->first();
        $categories = Category::where('status', 'active')->get();

        if ($watchCategory) {
            $products = Product::with('category')
                ->where('category_id', $watchCategory->id) // correct
                ->get();
        } else {
            $products = collect();
        }

        return view('user.watches', compact( 'products', 'watchCategory'));    
    }
}


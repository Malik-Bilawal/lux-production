<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\NewArrivalBanner;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->with('products')->firstOrFail();
        return view('user.category-show', compact('category'));
    }
    
}

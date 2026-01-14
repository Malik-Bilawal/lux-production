<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\NeckWristBanner;
use App\Http\Controllers\Controller;


class ObjectController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function($query) {
            $query->latest()
                  ->take(8); 
        }])
        ->whereHas('products')     
        ->orderBy('sort_order', 'asc') 
        ->get();

        return view('user.objects', compact('categories'));
    }
}
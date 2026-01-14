<?php
namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product): void
    {
        Cache::forget('dash_total_products'); 
    }

    public function deleted(Product $product): void
    {
        Cache::forget('dash_total_products');
    }
    
 
}
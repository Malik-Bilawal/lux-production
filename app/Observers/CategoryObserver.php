<?php
namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(Category $category): void
    {
        Cache::forget('all_categories_list');
        
       
    }

    public function updated(Category $category): void
    {
        Cache::forget('all_categories_list');
    }

    public function deleted(Category $category): void
    {
        Cache::forget('all_categories_list');
    }
}
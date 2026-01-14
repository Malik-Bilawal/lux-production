<?php

namespace App\Http\Controllers\User\CustomerSupport;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerSupportController extends Controller
{
   // In your controller
public function show($slug)
{
    $page = Page::where('slug', $slug)
        ->where('status', true)
        ->firstOrFail();
    
    $page->load(['sections' => function($q) {
        $q->where('is_visible', true)
          ->with(['items' => function($q2) {
              $q2->orderBy('sort_order');
          }])
          ->orderBy('sort_order');
    }]);
    
    
    $metaTitle = $page->meta_title ?? "{$page->title} | Luxorix";
    
    return view('user.customer-support.info-pages', [
        'page' => $page,
        'metaTitle' => $metaTitle
    ]);
}
    

   
}

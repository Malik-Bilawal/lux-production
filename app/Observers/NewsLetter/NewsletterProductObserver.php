<?php

namespace App\Observers\NewsLetter;

use App\Models\Product;
use App\Models\NewsletterCampaign;
use Illuminate\Support\Str;

class NewsletterProductObserver
{
    public function created(Product $product)
    {
        NewsletterCampaign::create([
            'name' => "New Product: {$product->name}",
            'type' => 'product',
            'product_id' => $product->id,
            'subject' => "🎁 New Product Alert: {$product->name}",
            'preview_text' => Str::limit($product->description, 100),
            'content' => $this->generateProductEmailContent($product),
            'template' => 'product_announcement',
            'status' => 'draft',
            'total_recipients' => \App\Models\NewsletterSubscriber::where('is_unsubscribed', false)->count(),
        ]);
    }

    private function generateProductEmailContent(Product $product)
    {
        return view('emails.templates.product_announcement', [
            'product' => $product
        ])->render();
    }
}
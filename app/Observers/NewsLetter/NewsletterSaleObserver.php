<?php

namespace App\Observers\NewsLetter;

use App\Models\Sale;
use App\Models\NewsletterCampaign;
use Illuminate\Support\Str;

class NewsletterSaleObserver
{
    public function created(Sale $sale)
    {
        NewsletterCampaign::create([
            'name' => "Sale: {$sale->name}",
            'type' => 'sale',
            'sale_id' => $sale->id,
            'subject' => "🔥 {$sale->discount_percentage}% OFF: {$sale->name}",
            'preview_text' => Str::limit($sale->description, 100),
            'content' => $this->generateSaleEmailContent($sale),
            'template' => 'sale_announcement',
            'status' => 'draft',
            'total_recipients' => \App\Models\NewsletterSubscriber::where('is_unsubscribed', false)->count(),
        ]);
    }

    private function generateSaleEmailContent(Sale $sale)
    {
        return view('emails.templates.sale_announcement', [
            'sale' => $sale
        ])->render();
    }
}
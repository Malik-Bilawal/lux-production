<?php

    namespace App\Mail;
    
    use App\Models\Product;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Support\Facades\Log;
    
    class ProductBackInStockMail extends Mailable implements ShouldQueue{


        use Queueable, SerializesModels;
    
        public $productId;
    
        public function __construct($productId)
        {
            $this->productId = $productId;
        }
    
        public function build()
        {
            Log::info("📩 Building ProductBackInStockMail for product_id={$this->productId}");
    
            $product = Product::find($this->productId);
    
            if (! $product) {
                Log::warning("⚠️ ProductBackInStockMail: Product with ID {$this->productId} not found.");
            } else {
                Log::info("✅ ProductBackInStockMail: Found product '{$product->name}' (ID: {$product->id})");
            }
    
            return $this->subject('Your product is back in stock!')
                        ->view('user.emails.product_back_in_stock', compact('product'));
        }
    }
    


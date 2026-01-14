<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id','guest_token','product_id','quantity','is_buy_now','price','meta'];
    protected $casts = ['meta'=>'array'];

    public function product(){ return $this->belongsTo(Product::class); }
    public function user(){ return $this->belongsTo(User::class); }

    protected $appends = ['img'];

    public function getImgAttribute()
    {
        $path = $this->product->mainImage->image_path ?? null;

        if ($path) {
            return asset('storage/' . $path);
        }

        return asset('images/placeholder.jpg');
    }
}

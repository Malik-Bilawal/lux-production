<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;


class ProductGallery extends Model
{
   

    protected $fillable = [
        'product_id',
        'add_to_cart_uri',
        'buy_now_uri',
        'banner',
        'craftsmanship_desc',
        'material_desc',
        'key_features',
    ];

    public function product()
{
    return $this->belongsTo(Product::class);
}


}
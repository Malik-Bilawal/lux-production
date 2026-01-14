<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'guest_token',
        'email',
        'notified',
    ];

    // Relation: notification belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

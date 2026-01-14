<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'approved' => 'boolean',
        'verified_purchase' => 'boolean',
        'responded_at' => 'datetime',

    ];



    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function orderItem() { return $this->belongsTo(OrderItem::class, 'order_item_id'); }
    public function images()
    {
        return $this->hasMany(\App\Models\ReviewImage::class, 'review_id', 'id');
    }
    }

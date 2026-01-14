<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id', 'type', 'first_name', 'last_name', 'email',
        'phone', 'address_1', 'address_2', 'city', 'state', 'zip', 'country'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use LogsActivity;


public const CACHE_TOTAL_ORDERS = 'stats:orders:count';
public const CACHE_TOTAL_VALUE  = 'stats:orders:value_sum';
public const CACHE_REVENUE      = 'stats:orders:revenue_delivered';

// STATUS SPECIFIC COUNTS
public const CACHE_COUNT_PENDING   = 'stats:orders:count_pending';
public const CACHE_COUNT_DELIVERED = 'stats:orders:count_delivered';
public const CACHE_COUNT_CANCELLED = 'stats:orders:count_cancelled';
    
protected $fillable = [
    'user_id',
    'guest_token',
    'order_code',
    'total_amount',
    'subtotal',          
    'shipping_cost',      
    'sale_discount',
    'promo_discount',    
    'status',
    'country_id',
    'shipping_country_name',
    'is_free_shipping' ,
    'shipping_free_source' ,
    'payment_method_id',
    'shipping_method_id',
    'shipping_method_name',
    'billing_same_as_shipping', 
    'referral_code',
    'promo_code_id',
    'notes',
    'placed_at',
    'tracking_code',
    'ip_address',
    'user_agent',         
    'fingerprint',        
    'estimated_delivery_time',
    'success_viewed',     
];

protected $casts = [
    'placed_at' => 'datetime',
    'estimated_delivery_time' => 'datetime',
    'billing_same_as_shipping' => 'boolean', 
    'success_viewed' => 'boolean',
    'total_amount' => 'decimal:2',
    'subtotal' => 'decimal:2',
    'shipping_cost' => 'decimal:2',
];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function cancellation()
{
    return $this->hasOne(OrderCancellation::class);
}


public function addresses()
{
    return $this->hasOne(OrderAddress::class, 'order_id', 'id');
}


    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config (Latest Spatie version)
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('order')
            ->logOnly(['status'])   // only track status changes
            ->logOnlyDirty()        // only log when status actually changes
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    return "Order #{$this->order_code} status changed to {$this->status}";
                }
                return "Order #{$this->order_code} was {$eventName}";
            });
    }

    /**
     * Custom log message
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName === 'updated' && $this->isDirty('status')) {
            return "Order #{$this->order_code} status changed to {$this->status}";
        }
        return "Order #{$this->order_code} was {$eventName}";
    }
}
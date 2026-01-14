<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoCode extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'promo_codes';

    protected $fillable = [
        'code',
        'discount_percent',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
    ];

    // Date fields
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isActive()
    {
        return $this->status === 'active'
            && $this->start_date <= now()
            && $this->end_date >= now()
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
    

    
    /**
     * Calculate discount amount based on total
     */
    public function calculateDiscount($total)
    {
        if (! $this->isActive()) {
            return 0;
        }
    
        if ($this->usage_limit > 0){
            return ($total * $this->discount_percent) / 100;
        
        }
        
    
        // fallback to fixed
        return $this->discount_amount ?? 0;
    }
    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // log all fields
            ->logOnlyDirty() // only log changed fields
            ->useLogName('promo_code')
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Promo code {$this->code} was {$eventName}"


                
            );


           
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function isValid($subtotal = 0)
    {
        if (!$this->is_active) {
            return 'Promo code is not active.';
        }
    
        if ($this->start_date && now()->lt($this->end_date)) {
            return 'Promo code is not yet valid.';
        }
    
        if ($this->start_date && now()->gt($this->end_date)) {
            return 'Promo code has expired.';
        }
    
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return 'Promo code usage limit reached.';
        }
    
        if ($this->min_order_amount && $subtotal < $this->min_order_amount) {
            return 'Minimum order amount not met.';
        }
    
        return true;
    }
}

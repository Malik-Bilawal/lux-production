<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ShippingMethod extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'icon',
        'delivery_time',
        'delivery_time_days',
        'cost',
        'free_threshold',
        'status'
    ];
    
    protected $casts = [
        'delivery_time_days' => 'integer',
        'cost' => 'float',
        'free_threshold' => 'float',
    ];

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
            ->useLogName('shipping_method')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    return "Shipping Method {$this->name} status changed to {$this->status}";
                }
                return "Shipping Method {$this->name} was {$eventName}";
            });
    }
}

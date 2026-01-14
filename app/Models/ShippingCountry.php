<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ShippingCountry extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'shipping_rate',
        'free_shipping_threshold',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('shipping_country')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    return "Shipping Country {$this->name} status changed to {$this->status}";
                }
                return "Shipping Country {$this->name} was {$eventName}";
            });
    }
}

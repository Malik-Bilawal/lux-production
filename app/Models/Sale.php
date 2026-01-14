<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'discount',
        'status',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];


      /**
     * Return the currently active global sale or null.
     */
    public static function getActiveSale()
    {
        return static::where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();
    }

    /**
     * Optional: instance helper to check if this sale is currently active.
     */
    public function isActive()
    {
        return $this->status === 'active'
            && $this->start_time <= now()
            && $this->end_time >= now();
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
            ->useLogName('sale')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    return "Sale {$this->title} status changed to {$this->status}";
                }
                return "Sale {$this->title} was {$eventName}";
            });
    }
}

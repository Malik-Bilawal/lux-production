<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OurJourney extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'journeys';

    protected $fillable = [
        'year',
        'title',
        'desc',
        'order_no',
    ];

    protected $casts = [
        'year' => 'integer',
        'order_no' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // track all fillable attributes
            ->logOnlyDirty() // only log when values actually change
            ->useLogName('our_journey') // log category name
            ->setDescriptionForEvent(fn(string $eventName) =>
                "OurJourney entry was {$eventName}"
            );
    }
}

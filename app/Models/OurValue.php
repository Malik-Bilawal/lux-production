<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OurValue extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'our_values';

    protected $fillable = [
        'title',
        'desc_front',
        'desc_back',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // track all fillable fields
            ->logOnlyDirty() // log only when values actually change
            ->useLogName('our_value')
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Our Value entry was {$eventName}"
            );
    }
}

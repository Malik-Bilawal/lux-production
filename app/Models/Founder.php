<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Founder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'position',
        'message',
        'image',
        'vision',
    ];

    protected $casts = [
        'vision' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // log all fillable changes
            ->logOnlyDirty() // only when values actually change
            ->useLogName('founder') // group logs under "founder"
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Founder entry was {$eventName}"
            );
    }
}

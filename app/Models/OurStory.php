<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OurStory extends Model
{
    use LogsActivity;

    protected $table = 'our_story'; 

    protected $fillable = [
        'text',
        'happy_customers',
        'cities_served',
        'products_launched',
        'years_in_business',
        'showcase_image'
    ];

    protected $casts = [
        'happy_customers'    => 'integer',
        'cities_served'      => 'integer',
        'products_launched'  => 'integer',
        'years_in_business'  => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // log all fillable attributes
            ->logOnlyDirty() // only when changes happen
            ->useLogName('our_story')
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Our Story entry was {$eventName}"
            );
    }
}

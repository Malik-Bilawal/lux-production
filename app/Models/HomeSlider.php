<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HomeSlider extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['image', 'status'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('home_slider')
            ->logOnly(['image', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Home Slider was {$eventName}"
            );
    }
}

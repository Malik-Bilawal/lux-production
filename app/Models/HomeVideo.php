<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HomeVideo extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'video_link',
        'thumbnail',
        'status',
    ];

 
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('home_video')
            ->logOnly(['video_link', 'thumbnail', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Home Video was {$eventName}"
            );
    }
}

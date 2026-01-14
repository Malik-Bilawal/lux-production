<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NeckWristBanner extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_url',
        'image',
        'tags',
        'status',
    ];

    /**
     * Spatie log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'description',
                'button_text',
                'button_url',
                'image',
                'tags',
                'status',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "NeckWristBanner has been {$eventName}");
    }
}

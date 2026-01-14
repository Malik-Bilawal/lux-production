<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ContactInfo extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'contact_info';

    protected $fillable = [
        'type',
        'label',
        'value',
        'icon',
        'platform',
    ];

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'label', 'value', 'icon', 'platform'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Contact info has been {$eventName}");
    }
}

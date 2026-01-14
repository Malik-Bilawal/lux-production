<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class NewArrivalBanner extends Model
{
    use LogsActivity;

    protected $fillable = ['image', 'status'];

    /**
     * Spatie activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['image', 'status'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "NewArrivalBanner has been {$eventName}");
    }
}

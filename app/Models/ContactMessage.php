<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ContactMessage extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'product_id',
        'status',
        'replied_at'
    ];

    protected $dates = ['deleted_at', 'replied_at'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(\App\Models\Category::class, 'product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'replied_at'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    if ($this->status === 'read') {
                        return "Message #{$this->id} was marked as READ by admin.";
                    }
                    if ($this->status === 'replied') {
                        return "Message #{$this->id} was marked as REPLIED by admin.";
                    }
                }

                return "Contact message #{$this->id} was {$eventName}.";
            });
    }
}

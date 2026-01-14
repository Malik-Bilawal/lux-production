<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Offer extends Model
{
    use LogsActivity;

    protected $fillable = [
        'timer_start',
        'timer_end',
        'tags',
        'description',
        'caption',
        'title',
        'product_id',
        'category_id',
    ];

    protected $casts = [
        'timer_start' => 'datetime',
        'timer_end'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['timer_start', 'timer_end', 'tags', 'description', 'caption', 'title', 'product_id', 'category_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Offer has been {$eventName}");
    }
}

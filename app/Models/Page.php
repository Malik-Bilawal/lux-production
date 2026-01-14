<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'hero_text',
        'meta_title',
        'meta_description',
        'og_image',
        'template',
        'status',
        'extra_attributes',
        'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'extra_attributes' => 'array',
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function activeSections()
    {
        return $this->hasMany(PageSection::class)
            ->where('is_visible', true)
            ->orderBy('sort_order');
    }
    
    // Helper method to get full URL
    public function getFullUrlAttribute()
    {
        return url('/' . $this->slug);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() 
            ->logOnlyDirty() 
            ->useLogName('page')
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Page record was {$eventName}"
            );
    }
}
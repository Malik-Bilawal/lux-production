<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageItem extends Model
{

    protected $fillable = [
        'section_id',
        'title',
        'content',
        'image_url',
        'icon',
        'cta_label',
        'cta_link',
        'width',
        'sort_order',
        'extra_attributes'
    ];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(PageSection::class);
    }

    public function getWidthClassAttribute()
    {
        return [
            'full' => 'w-full',
            'half' => 'w-full md:w-1/2',
            'third' => 'w-full md:w-1/3',
            'quarter' => 'w-full md:w-1/4',
        ][$this->width] ?? 'w-full';
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty() 
            ->useLogName('page_item')
            ->setDescriptionForEvent(fn(string $eventName) =>
                "Page Item record was {$eventName}"
            );
    }
}

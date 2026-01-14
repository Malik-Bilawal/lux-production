<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{

    protected $fillable = [
        'page_id',
        'heading',
        'subheading',
        'layout_type',
        'background_theme',
        'sort_order',
        'is_visible',
        'settings',
        'css_classes'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'settings' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // PageSection.php
    public function items()
    {
        return $this->hasMany(PageItem::class, 'section_id')->orderBy('sort_order');
    }



    public function activeItems()
    {
        return $this->hasMany(PageItem::class, 'section_id')
            ->orderBy('sort_order');
    }


    // Layout types available
    public static function layoutTypes()
    {
        return [
            'text_block' => 'Text Block',
            'accordion' => 'Accordion/FAQ',
            'grid_2_col' => '2 Column Grid',
            'grid_3_col' => '3 Column Grid',
            'grid_4_col' => '4 Column Grid',
            'hero_split'     => 'Hero: Split Image/Text',
            'marquee'        => 'Scrolling Text Marquee',
            'testimonials'   => 'Testimonial Carousel',
            'contact_form'   => 'Interactive Contact Form',
            'feature_showcase' => 'Detailed Product Feature',
            'stats_bar'      => 'Metric/Statistics Bar',
            'video_embed'    => 'Full-Width Video Background',
    'comparison_table' => 'Comparison Table',
        ];
    }

    // Get layout type name
    public function getLayoutTypeNameAttribute()
    {
        return self::layoutTypes()[$this->layout_type] ?? $this->layout_type;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('page_section')
            ->setDescriptionForEvent(
                fn(string $eventName) =>
                "Page Section record was {$eventName}"
            );
    }
}

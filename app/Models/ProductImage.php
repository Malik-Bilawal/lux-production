<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductImage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'image_path',
        'type',
        'sort_order',
        'alt_text',
        'title'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    // Scopes
    public function scopeMainImage(Builder $query)
    {
        return $query->where('type', 'main_image');
    }

    public function scopeSubImage(Builder $query)
    {
        return $query->where('type', 'sub_image');
    }

    public function scopeGallery(Builder $query)
    {
        return $query->where('type', 'gallery');
    }

    public function scopeDesktopDetail(Builder $query)
    {
        return $query->where('type', 'desktop_detail_images');
    }

    public function scopeMobileDetail(Builder $query)
    {
        return $query->where('type', 'mobile_detail_images');
    }

    public function scopeBySortOrder(Builder $query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('product_image')
            ->logOnly(['image_path', 'type', 'sort_order', 'alt_text'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Product image ({$this->type}) for Product ID {$this->product_id} was {$eventName}";
    }
}
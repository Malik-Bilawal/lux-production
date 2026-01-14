<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'title',
        'description',
        'category_id',
        'price',
        'cut_price',
        'stock_quantity',
        'rating',
        'status',
        'tags',
        'offer',
        'is_top_selling',
        'is_new_arrival',
        'is_feature_card',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cut_price' => 'decimal:2',
        'rating' => 'decimal:1',
        'stock_quantity' => 'integer',
        'is_top_selling' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_feature_card' => 'boolean',
        'sort_order' => 'integer',
        'tags' => 'array',
    ];

    protected $appends = [
        'discount_percentage',
        'is_in_stock',
        'formatted_price',
        'formatted_cut_price',
    ];

    // VALIDATION RULES
    
    public static function validationRules($productId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'cut_price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'sub_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'nullable|integer|min:0',
            'tags' => 'nullable|string|max:500',
            'rating' => 'nullable|numeric|min:0|max:5',
            'status' => 'required|in:active,inactive',
           'sort_order' => [
    'required',
    'min:1',
    function ($attribute, $value, $fail) use ($productId) {
        $categoryId = request()->input('category_id');

        if (!$categoryId) {
            return; // skip if category not provided
        }

        $value = (int) $value; // ensure integer

        $exists = \App\Models\Product::where('category_id', $categoryId)
            ->where('sort_order', $value)
            ->when($productId, fn($q) => $q->where('id', '!=', $productId))
            ->exists();

        if ($exists) {
            $fail('Sort order must be unique within the same category.');
        }
    }
],

            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,avif|max:5120',
        ];

        return $rules;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->whereIn('status', ['approved', 'responded']);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function notifications()
    {
        return $this->hasMany(ProductNotification::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('type', 'main_image')
            ->orderBy('sort_order');
    }

    public function subImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('type', 'sub_image')
            ->orderBy('sort_order');
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)
            ->where('type', 'gallery_images')
            ->orderBy('sort_order');
    }

    public function desktopDetailImages()
    {
        return $this->hasMany(ProductImage::class)
            ->where('type', 'desktop_detail_images')
            ->orderBy('sort_order');
    }

    public function mobileDetailImages()
    {
        return $this->hasMany(ProductImage::class)
            ->where('type', 'mobile_detail_images')
            ->orderBy('sort_order');
    }

    // Accessors
    public function getDiscountPercentageAttribute()
    {
        if ($this->cut_price > 0 && $this->cut_price > $this->price) {
            return round((($this->cut_price - $this->price) / $this->cut_price) * 100);
        }
        return 0;
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getFormattedPriceAttribute()
    {
        return 'PKR ' . number_format($this->price, 2);
    }

    public function getFormattedCutPriceAttribute()
    {
        return 'PKR ' . number_format($this->cut_price, 2);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    // Boot method for auto-slug generation
    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = \Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }
        });
    }

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('product')
            ->logOnly([
                'name', 'price', 'cut_price', 'description', 'category_id',
                'is_top_selling', 'is_new_arrival', 'is_feature_card',
                'status', 'rating', 'tags', 'slug', 'stock_quantity'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Product \"{$this->name}\" has been {$eventName}";
    }
}
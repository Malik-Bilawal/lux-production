<?php

namespace App\Models;           

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
'second_image_path', 'image', 'name','slug','home_sort_order', 'title', 'description', 'tagline', 'second_tagline', 'sort_order', 'status'
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config (Latest Spatie version)
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('category')
            ->logOnly(['name', 'title', 'description', 'status', 'image'])
            ->logOnlyDirty() // only log changed fields
            ->setDescriptionForEvent(fn(string $eventName) => "Category was {$eventName}");
    }


    public function sortedProducts()
    {
        return $this->hasMany(Product::class)
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    // app/Models/Category.php

public function homeProducts()
{
    return $this->hasMany(Product::class)
        ->where('status', 'active') // or 1, depending on your DB
        ->orderBy('sort_order', 'asc');
}
}

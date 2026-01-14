<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductDetail extends Model
{
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'model_name',
        'reference_number',
        'specs',
        'detailed_description'
    ];
// App/Models/ProductDetail.php - Update the casts
protected $casts = [
    'specs' => 'array',
];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('product_detail');
    }
}
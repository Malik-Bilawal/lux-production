<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsBlock extends Model
{
    use HasFactory;

    protected $table = 'about_us_blocks';

    protected $fillable = [
        'block_text',
        'title',
        'subtitle',
        'content',
        'signature_text',
        'image_url',
        'fig_label',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope for active blocks only
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}

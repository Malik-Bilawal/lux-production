<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    use HasFactory;

    protected $table = 'page_heroes';

    protected $fillable = [
        'page_type',
        'eyebrow_text',
        'main_heading',
        'highlight_text',
        'description',
        'cta_text',
        'cta_link',
        'is_active',
    ];
}
<?php

// app/Models/ReviewImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $table = 'review_images'; 
    protected $guarded = [];
    public function review()
    {
        return $this->belongsTo(\App\Models\Review::class, 'review_id', 'id');
    }
    }

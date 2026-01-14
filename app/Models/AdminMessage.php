<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_name',
        'message',
        'type',        // text/audio/image/video/file
        'file_path',   // storage path
        'file_name',   // optional original name
    ];
    }


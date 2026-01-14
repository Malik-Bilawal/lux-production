<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    use HasFactory;

    protected $table = 'chat_room_user';

    protected $fillable = ['chat_room_id', 'admin_id'];

    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(\App\Models\Admin::class, 'chat_room_user', 'chat_room_id', 'admin_id');
    }
    
    
    
}

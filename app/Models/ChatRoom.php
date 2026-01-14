<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type', 'created_by', 'image', 'last_activity'];

    // 1. The Users (Admins)
    public function users()
    {
        return $this->belongsToMany(Admin::class, 'chat_room_user', 'chat_room_id', 'admin_id')
                    ->withTimestamps();
    }

    // 2. All Messages
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_room_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
    
    public function unreadCountForUser($adminId)
    {

        return $this->messages()
            ->where('sender_id', '!=', $adminId) 
            ->whereDoesntHave('statuses', function($q) use ($adminId) {
                $q->where('user_id', $adminId) 
                  ->where('is_read', 1);
            })
            ->count();
    }
}



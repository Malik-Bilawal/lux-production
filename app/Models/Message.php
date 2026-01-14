<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id', 'sender_id', 'message', 'attachment', 'type', 'is_read'
    ];

    // 🔗 Relationships
    public function chatRoom() { return $this->belongsTo(ChatRoom::class, 'chat_room_id'); }
    public function sender() { return $this->belongsTo(Admin::class, 'sender_id'); }
    public function statuses() { return $this->hasMany(MessageStatus::class); }
    
}

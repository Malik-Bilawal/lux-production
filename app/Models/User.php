<?php

namespace App\Models;

use App\Models\Order;
use Spatie\Activitylog\LogOptions;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    public const CACHE_TOTAL_USERS  = 'stats:users:count';
    public const CACHE_ACTIVE_USERS = 'stats:users:active_count';
    
    public const CACHE_BLOCKED_USERS = 'stats:users:blocked_count';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // App\Models\User.php
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function orderCancellations()
{
    return $this->hasMany(OrderCancellation::class);
}
    

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // log all attributes
            ->logOnlyDirty() // only log when values actually change
            ->useLogName('user')
            ->setDescriptionForEvent(function (string $eventName) {
                return "User {$this->name} was {$eventName}";
            });
    }
}

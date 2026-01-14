<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use SoftDeletes; 

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'token',
        'is_unsubscribed',
        'unsubscribed_at',
        'subscribed_at',
        'last_contacted_at',
        'source',
        'metadata'
    ];

    protected $casts = [
        'is_unsubscribed' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->token)) {
                $subscriber->token = bin2hex(random_bytes(32));
            }
        });
    }

    // Relationships
    public function logs()
    {
        return $this->hasMany(NewsletterLog::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getStatusAttribute()
    {
        return $this->is_unsubscribed ? 'unsubscribed' : 'subscribed';
    }

    // Methods
    public function unsubscribe()
    {
        $this->update([
            'is_unsubscribed' => true,
            'unsubscribed_at' => now()
        ]);
    }

    public function resubscribe()
    {
        $this->update([
            'is_unsubscribed' => false,
            'unsubscribed_at' => null
        ]);
    }
}
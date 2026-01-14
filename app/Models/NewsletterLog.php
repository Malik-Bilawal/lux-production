<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'newsletter_campaign_id',
        'subscriber_id',
        'email',
        'message_id',
        'sent_at',
        'opened_at',
        'clicked_at',
        'clicked_url',
        'ip_address',
        'user_agent',
        'device_type',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(NewsletterCampaign::class);
    }

    public function subscriber()
    {
        return $this->belongsTo(NewsletterSubscriber::class);
    }

    // Methods
    public function markAsOpened($ip = null, $userAgent = null)
    {
        if (!$this->opened_at) {
            $this->update([
                'opened_at' => now(),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'device_type' => $this->detectDeviceType($userAgent)
            ]);
            
            $this->campaign->updateStats();
        }
    }

    public function markAsClicked($url, $ip = null, $userAgent = null)
    {
        if (!$this->clicked_at) {
            $this->update([
                'clicked_at' => now(),
                'clicked_url' => $url,
                'ip_address' => $ip ?? $this->ip_address,
                'user_agent' => $userAgent ?? $this->user_agent,
                'device_type' => $this->detectDeviceType($userAgent ?? $this->user_agent)
            ]);
            
            $this->campaign->updateStats();
        }
    }

    protected function detectDeviceType($userAgent)
    {
        if (!$userAgent) return 'unknown';
        
        $mobileAgents = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];
        
        foreach ($mobileAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return stripos($userAgent, 'Tablet') !== false ? 'tablet' : 'mobile';
            }
        }
        
        return 'desktop';
    }
}
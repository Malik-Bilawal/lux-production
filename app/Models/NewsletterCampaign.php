<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterCampaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'product_id',
        'sale_id',
        'offer_id',
        'subject',
        'preview_text',
        'content',
        'template',
        'status',
        'scheduled_at',
        'started_at',
        'completed_at',
        'total_recipients',
        'sent_count',
        'opens',
        'clicks',
        'open_rate',
        'click_rate',
        'created_by',
        'stats'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'stats' => 'array',
    ];

    protected $appends = ['sent_date', 'progress', 'is_scheduled'];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(NewsletterLog::class);
    }

    // Accessors
    public function getSentDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : '-';
    }

    public function getProgressAttribute()
    {
        if ($this->status === 'sending' && $this->total_recipients > 0) {
            return round(($this->sent_count / $this->total_recipients) * 100);
        }
        
        return $this->status === 'sent' ? 100 : 0;
    }

    public function getIsScheduledAttribute()
    {
        return $this->status === 'scheduled' && $this->scheduled_at > now();
    }

    public function getPerformanceAttribute()
    {
        return [
            'open_rate' => $this->open_rate,
            'click_rate' => $this->click_rate,
            'opens' => $this->opens,
            'clicks' => $this->clicks,
            'sent' => $this->sent_count,
            'total' => $this->total_recipients
        ];
    }

    // Methods
    public function updateStats()
    {
        $logs = $this->logs();
        
        $this->opens = $logs->whereNotNull('opened_at')->count();
        $this->clicks = $logs->whereNotNull('clicked_at')->count();
        $this->open_rate = $this->sent_count > 0 ? round(($this->opens / $this->sent_count) * 100, 2) : 0;
        $this->click_rate = $this->sent_count > 0 ? round(($this->clicks / $this->sent_count) * 100, 2) : 0;
        
        $this->stats = [
            'unique_opens' => $logs->whereNotNull('opened_at')->distinct('subscriber_id')->count(),
            'unique_clicks' => $logs->whereNotNull('clicked_at')->distinct('subscriber_id')->count(),
            'devices' => $logs->whereNotNull('device_type')->select('device_type')->distinct()->pluck('device_type'),
            'last_activity' => $logs->max('opened_at') ?? $logs->max('clicked_at') ?? $logs->max('sent_at')
        ];
        
        $this->save();
    }

    public function canSend()
    {
        return in_array($this->status, ['draft', 'scheduled']) && !$this->trashed();
    }

    public function schedule($datetime)
    {
        $this->update([
            'status' => 'scheduled',
            'scheduled_at' => $datetime
        ]);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
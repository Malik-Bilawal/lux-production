<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\NewsletterLog;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $subscriber;
    public $messageId;

    public function __construct(NewsletterCampaign $campaign, NewsletterSubscriber $subscriber)
    {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;
        $this->messageId = Str::uuid()->toString();

        Log::info("Initialized SendNewsletterEmail for subscriber ID: {$subscriber->id}, campaign ID: {$campaign->id}");
    }

    public function handle()
    {
        try {
            // Create log entry
            $log = NewsletterLog::create([
                'campaign_id' => $this->campaign->id,
                'subscriber_id' => $this->subscriber->id,
                'email' => $this->subscriber->email,
                'message_id' => $this->messageId,
                'sent_at' => now(),
            ]);
            Log::info("Newsletter log created for subscriber ID: {$this->subscriber->id}, log ID: {$log->id}");

            $trackingPixelUrl = url("/newsletter/track/open/{$log->id}");
            $unsubscribeUrl = url("/newsletter/unsubscribe/{$this->subscriber->token}?campaign={$this->campaign->id}");

            // Send email
            Mail::to($this->subscriber->email)
                ->send(new NewsletterMail(
                    $this->campaign,
                    $this->subscriber,
                    $log,
                    $trackingPixelUrl,
                    $unsubscribeUrl
                ));

            Log::info("Email sent successfully to {$this->subscriber->email}");

            $this->subscriber->update(['last_contacted_at' => now()]);

            // Increment campaign sent count
            $this->campaign->increment('sent_count');

            if ($this->campaign->sent_count >= $this->campaign->total_recipients) {
                $this->campaign->update([
                    'status' => 'sent',
                    'completed_at' => now()
                ]);
                Log::info("Campaign ID {$this->campaign->id} completed successfully");
            }

        } catch (\Exception $e) {
            Log::error("Failed to send email to {$this->subscriber->email}: " . $e->getMessage(), [
                'campaign_id' => $this->campaign->id,
                'subscriber_id' => $this->subscriber->id
            ]);
            throw $e;
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNewsletterCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $batchSize = 100;

    public function __construct(NewsletterCampaign $campaign)
    {
        $this->campaign = $campaign;
        Log::info("Initialized SendNewsletterCampaign job for campaign ID: {$campaign->id}");
    }

    public function handle()
    {
        try {
            Log::info("Starting campaign ID: {$this->campaign->id}");

            $this->campaign->update([
                'status' => 'sending',
                'started_at' => now()
            ]);

            Log::info("Campaign status updated to 'sending' for campaign ID: {$this->campaign->id}");

            NewsletterSubscriber::where('is_unsubscribed', false)
                ->chunkById($this->batchSize, function ($subscribersBatch) {
                    Log::info("Processing batch with " . count($subscribersBatch) . " subscribers for campaign ID: {$this->campaign->id}");

                    foreach ($subscribersBatch as $subscriber) {
                        SendNewsletterEmail::dispatch($this->campaign, $subscriber);
                        Log::info("Dispatched SendNewsletterEmail for subscriber ID: {$subscriber->id}");
                    }
                });

            Log::info("All batches dispatched for campaign ID: {$this->campaign->id}");

        } catch (\Exception $e) {
            Log::error("Failed to dispatch campaign ID {$this->campaign->id}: " . $e->getMessage());
            $this->campaign->update(['status' => 'failed']);
            throw $e;
        }
    }
}

<?php

namespace App\Mail;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\NewsletterLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $subscriber;
    public $log;
    public $unsubscribeUrl;
    public $trackingPixelUrl;

    public function __construct(
        NewsletterCampaign $campaign,
        NewsletterSubscriber $subscriber,
        NewsletterLog $log,
        string $trackingPixelUrl,
        string $unsubscribeUrl
    ) {
        $this->campaign = $campaign;
        $this->subscriber = $subscriber;

        $this->log = $log;
        $this->trackingPixelUrl = $trackingPixelUrl;
        $this->unsubscribeUrl = $unsubscribeUrl;
    }

    public function envelope()
    {
        return new Envelope(subject: $this->campaign->subject);
    }

    public function content()
    {
        $content = $this->processTrackingLinks($this->campaign->content);

        return new Content(
            view: 'emails.newsletter.template',
            with: [
                'content' => $content,
                'campaign' => $this->campaign,
                'subscriber' => $this->subscriber,
                'unsubscribeUrl' => $this->unsubscribeUrl,
                'preheader' => $this->campaign->preview_text,
                'trackingPixelUrl' => $this->trackingPixelUrl, // ✅ add this

            ]
        );
    }

    private function processTrackingLinks($content)
    {
        $trackingPixel = "<img src=\"{$this->trackingPixelUrl}\" width=\"1\" height=\"1\" style=\"display:none;\" />";

        return preg_replace_callback(
            '/<a[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/is',
            function ($matches) {
                $url = $matches[1];
                $text = $matches[2];

                if (strpos($url, 'unsubscribe') !== false) return $matches[0];

                $trackingUrl = url("/newsletter/track/click/{$this->log->id}?url=" . urlencode($url));

                return "<a href=\"{$trackingUrl}\" target=\"_blank\">{$text}</a>";
            },
            $content
        ) . $trackingPixel;
    }
}

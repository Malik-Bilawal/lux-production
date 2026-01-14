<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;
    public $status;
    public $dashboardLink;

    public function __construct($referral, $status = null)
    {
        $this->referral = $referral;
        $this->status = $status;
    }

    public function build()
    {
        $subject = $this->status == "Approved" 
            ? "Congratulations! Referral Approved 🎉" 
            : "Referral Application Update";

        return $this->subject($subject)
                    ->view('referral.email.referral-status');
    }
}

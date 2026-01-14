<?php

namespace App\Mail;
use Illuminate\Mail\Mailable;

class ReferralResetPasswordMail extends Mailable
{
    public $resetLink;

    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->subject('Referral Password Reset Link')
                    ->view('referral.email.referral-reset-password')
                    ->with(['resetLink' => $this->resetLink]);
    }
}

<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $user;

    public function __construct($link, $user)
    {
        $this->link = $link;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Verify Your Email Address')
                    ->view('user.emails.verify');
    }
}
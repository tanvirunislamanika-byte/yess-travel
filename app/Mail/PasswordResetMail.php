<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $otp, $expiresAt)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset Code - ' . config('app.name'))
                    ->view('emails.password-reset-code')
                    ->with([
                        'user' => $this->user,
                        'otp' => $this->otp,
                        'expiresAt' => $this->expiresAt,
                        'expiryMinutes' => $this->expiresAt->diffInMinutes(now())
                    ]);
    }
} 
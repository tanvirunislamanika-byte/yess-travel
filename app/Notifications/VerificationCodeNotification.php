<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Email Verification Code - ' . config('app.name'))
            ->view('emails.verification-code', [
                'user' => $notifiable,
                'verificationCode' => $this->verificationCode
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'verification_code' => $this->verificationCode,
            'user_id' => $notifiable->id,
        ];
    }
}

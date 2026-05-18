<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;
    public $user;
    public $submission;

    public function __construct($hotel, $user, $submission)
    {
        $this->hotel = $hotel;
        $this->user = $user;
        $this->submission = $submission;
    }

    public function build()
    {
        return $this->subject('Your Booking Status Has Changed')
            ->view('emails.booking-status')
            ->with([
                'hotel' => $this->hotel,
                'user' => $this->user,
                'submission' => $this->submission,
            ]);
    }
}

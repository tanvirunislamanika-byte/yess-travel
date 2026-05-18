<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $isAdmin;

    public function __construct($booking, $user, $isAdmin = false)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('New Flight Booking Confirmation')
            ->view('emails.flight-booking')
            ->with([
                'hotel' => $this->booking, // Keep 'hotel' for template compatibility
                'user' => $this->user,
                'isAdmin' => $this->isAdmin,
            ]);
    }
}

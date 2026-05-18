<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HotelBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;
    public $user;
    public $booking;
    public $isAdmin;

    public function __construct($booking, $hotel, $user, $isAdmin = false)
    {
        $this->booking = $booking;
        $this->hotel = $hotel;
        $this->user = $user;
        $this->isAdmin = $isAdmin;
    }

    public function build()
    {
        return $this->subject('New Hotel Booking Confirmation')
            ->view('emails.hotel-booking')
            ->with([
                'hotel' => $this->hotel,
                'user' => $this->user,
                'booking' => $this->booking,
                'isAdmin' => $this->isAdmin,
            ]);
    }
}

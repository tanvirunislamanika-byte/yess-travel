<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewPostedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $user;
    public $hotel;

    public function __construct($review, $user, $hotel)
    {
        $this->review = $review;
        $this->user = $user;
        $this->hotel = $hotel;
    }

    public function build()
    {
        return $this->subject('New Hotel Review Posted')
            ->view('emails.review-posted')
            ->with([
                'review' => $this->review,
                'user' => $this->user,
                'hotel' => $this->hotel,
            ]);
    }
}

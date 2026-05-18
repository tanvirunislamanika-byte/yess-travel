<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TourBooking;

class TourBookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tourBooking;

    public function __construct(TourBooking $tourBooking)
    {
        $this->tourBooking = $tourBooking;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $tour = $this->tourBooking->tour;
        $currencySymbol = \App\Helpers\CurrencyHelper::getSymbol();
        
        return (new MailMessage)
            ->subject('Tour Booking Confirmation - ' . $tour->title)
            ->view('emails.tour-booking-confirmation', [
                'user' => $notifiable,
                'tour' => $tour,
                'booking' => $this->tourBooking,
                'currencySymbol' => $currencySymbol
            ]);
    }

    protected function formatPassengerDetails()
    {
        $passengers = $this->tourBooking->passengers;
        $formatted = '';
        
        foreach ($passengers as $index => $passenger) {
            $formatted .= ($index + 1) . '. ' . $passenger['title'] . ' ' . $passenger['first_name'] . ' ' . $passenger['last_name'];
            if (isset($passenger['country'])) {
                $formatted .= ' (Country: ' . $passenger['country'] . ')';
            }
            $formatted .= "\n";
        }
        
        return $formatted;
    }

    protected function formatContactDetails()
    {
        $contact = $this->tourBooking->contact;
        $formatted = '';
        
        if (isset($contact['name'])) {
            $formatted .= 'Name: ' . $contact['name'] . "\n";
        }
        if (isset($contact['email'])) {
            $formatted .= 'Email: ' . $contact['email'] . "\n";
        }
        if (isset($contact['phone'])) {
            $formatted .= 'Phone: ' . $contact['phone'] . "\n";
        }
        
        return $formatted;
    }

    public function toArray($notifiable)
    {
        return [
            'tour_booking_id' => $this->tourBooking->id,
            'tour_title' => $this->tourBooking->tour->title,
            'booking_reference' => $this->tourBooking->booking_reference,
            'amount' => $this->tourBooking->total_amount,
        ];
    }
}

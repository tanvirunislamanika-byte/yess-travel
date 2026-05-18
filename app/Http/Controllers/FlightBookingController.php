<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use Illuminate\Http\Request;
use PDF;

class FlightBookingController extends Controller
{
    public function downloadPdf($id)
    {
        try {
            $booking = FlightBooking::with('user')->findOrFail($id);
            
            // Handle passenger_details which might be stored as string (JSON) or array
            if (is_string($booking->passenger_details)) {
                $passengers = json_decode($booking->passenger_details, true);
            } else {
                $passengers = $booking->passenger_details;
            }

            // Ensure passengers is an array even if decode fails or is null
            if (!is_array($passengers)) {
                $passengers = [];
            }

            $pdf = PDF::loadView('flights.booking-pdf', [
                'booking' => $booking,
                'passengers' => $passengers
            ]);

            return $pdf->download('booking-' . ($booking->booking_reference ?? $booking->id) . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error generating PDF for booking ' . $id . ': ' . $e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Unable to generate PDF. ' . $e->getMessage());
        }
    }
} 
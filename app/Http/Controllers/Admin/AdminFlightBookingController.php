<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlightBooking;

class AdminFlightOrderController extends Controller
{
    public function show($id)
    {
        $booking = FlightBooking::findOrFail($id);
        
        // Decode passenger details if it's a JSON string
        $passengerDetails = is_string($booking->passenger_details)
            ? json_decode($booking->passenger_details, true)
            : $booking->passenger_details;
            
        return view('admin.flight-orders.details', compact('booking', 'passengerDetails'));
    }
}
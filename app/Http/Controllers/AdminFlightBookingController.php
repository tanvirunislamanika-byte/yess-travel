<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlightBooking;

class AdminFlightBookingController extends Controller
{
    public function show($id)
    {
        $booking = FlightBooking::findOrFail($id);
        return view('admin.flight-booking-details', compact('booking'));
    }
} 
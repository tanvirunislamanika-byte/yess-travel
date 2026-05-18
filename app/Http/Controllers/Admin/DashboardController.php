<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelBooking;
use App\Models\User;
use App\Models\ModulesData;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch the latest 5 hotel bookings
        $hotel_bookings = HotelBooking::latest()->take(5)->get();

        return view('admin.dashboard', compact('hotel_bookings'));
    }
}

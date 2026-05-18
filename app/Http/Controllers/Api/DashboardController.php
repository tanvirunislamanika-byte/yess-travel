<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlightBooking;
use App\Models\Hotels;
use App\Models\ModulesData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard summary
     */
    public function getSummary()
    {
        try {
            $userId = auth()->id();

            // Get recent hotel bookings (pending and paid)
            $recentHotelBookings = Hotels::where('user_id', $userId)
                ->where('booking_status', 'Pending')
                ->whereDate('check_in', '>=', now())
                ->where('status', 'paid')
                ->count();

            // Get recent flight bookings
            $recentFlightBookings = FlightBooking::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->count();

            // Get total bookings
            $totalHotelBookings = Hotels::where('user_id', $userId)->count();
            $totalFlightBookings = FlightBooking::where('user_id', $userId)->count();

            // Get upcoming bookings
            $upcomingHotelBookings = Hotels::where('user_id', $userId)
                ->whereDate('check_in', '>=', now())
                ->where('status', 'paid')
                ->count();

            $upcomingFlightBookings = FlightBooking::where('user_id', $userId)
                ->where('booking_status', 'confirmed')
                ->whereDate('departure_date', '>=', now())
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'recent_hotel_bookings' => $recentHotelBookings,
                    'recent_flight_bookings' => $recentFlightBookings,
                    'total_hotel_bookings' => $totalHotelBookings,
                    'total_flight_bookings' => $totalFlightBookings,
                    'upcoming_hotel_bookings' => $upcomingHotelBookings,
                    'upcoming_flight_bookings' => $upcomingFlightBookings,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get dashboard summary'
            ], 500);
        }
        
        
    }

    /**
     * Get recent bookings
     */
   public function getRecentBookings()
{
    try {
        $userId = auth()->id();

        // Get recent hotel bookings
        $recentHotelBookings = Hotels::where('user_id', $userId)
            ->where('booking_status', 'Pending')
            ->whereDate('check_in', '>=', now())
            ->where('status', 'paid')
            ->with('hotel')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                $hotel = ModulesData::find($booking->hotel_id);
                return [
                    'id' => $booking->id,
                    'type' => 'hotel',
                    'title' => $hotel->title ?? 'Hotel',
                    'location' => $booking->travelling_from,
                    'check_in' => $booking->check_in,
                    'check_out' => $booking->check_out,
                    'price' => $booking->price,
                    'status' => $booking->status,
                    'booking_status' => $booking->booking_status,
                    'payment_via' => $booking->payment_via,
                    'created_at' => optional($booking->created_at)->format('d M Y h:ia'),
                    'hotel_details' => $hotel ? [
                        'id' => $hotel->id,
                        'title' => $hotel->title,
                        'image' => $hotel->image ? asset('images/' . $hotel->image) : null,
                        'location' => $hotel->extra_field_18,
                        'type' => $hotel->extra_field_2,
                        'stars' => $hotel->extra_field_23,
                    ] : null,
                ];
            });

        // Get recent flight bookings
        $recentFlightBookings = FlightBooking::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                $passengerDetails = is_string($booking->passenger_details)
                    ? json_decode($booking->passenger_details, true)
                    : ($booking->passenger_details ?? []);

                $passengers = array_values($passengerDetails);
                $familyNames = collect($passengers)->pluck('given_name')->unique()->implode(', ');

                return [
                    'id' => $booking->id,
                    'type' => 'flight',
                    'family_names' => $familyNames,
                    'booking_status' => $booking->booking_status,
                    'payment_status' => $booking->payment_status,
                    'airline_code' => $booking->airline_code,
                    'airline_name' => $booking->airline_name,
                    'airline_logo' => $booking->airline_logo,
                    'origin_code' => $booking->origin_code,
                    'origin_airport' => $booking->origin_airport,
                    'destination_code' => $booking->destination_code,
                    'destination_airport' => $booking->destination_airport,
                    'departure_date' => $booking->departure_time ? Carbon::parse($booking->departure_time)->format('d M Y h:ia') : null,
                    'departure_date_raw' => $booking->departure_time,
                    'arrival_date' => $booking->arrival_time ? Carbon::parse($booking->arrival_time)->format('d M Y h:ia') : null,
                    'arrival_date_raw' => $booking->arrival_time,
                    'currency' => $booking->currency,
                    'total_amount' => $booking->total_amount,
                    'order_expire_at' => $booking->order_expire_at,
                    'created_at' => $booking->created_at ? Carbon::parse($booking->created_at)->format('d M Y h:ia') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'hotel_bookings' => $recentHotelBookings,
                'flight_bookings' => $recentFlightBookings,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to get recent bookings',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Get upcoming bookings
     */
    public function getUpcomingBookings()
    {
        try {
            $userId = auth()->id();

            // Get upcoming hotel bookings
            $upcomingHotelBookings = Hotels::where('user_id', $userId)
                ->whereDate('check_in', '>=', now())
                ->where('status', 'paid')
                ->with('hotel')
                ->orderBy('check_in', 'asc')
                ->limit(10)
                ->get()
                ->map(function ($booking) {
                    $hotel = ModulesData::where('id', $booking->hotel_id)->first();
                    return [
                        'id' => $booking->id,
                        'type' => 'hotel',
                        'title' => $hotel ? $hotel->title : 'Hotel',
                        'location' => $booking->travelling_from,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'price' => $booking->price,
                        'status' => $booking->status,
                        'booking_status' => $booking->booking_status,
                        'payment_via' => $booking->payment_via,
                        'days_until' => Carbon::parse($booking->check_in)->diffInDays(now()),
                        'hotel_details' => $hotel ? [
                            'id' => $hotel->id,
                            'title' => $hotel->title,
                            'image' => $hotel->image ? asset('images/' . $hotel->image) : null,
                            'location' => $hotel->extra_field_18,
                            'type' => $hotel->extra_field_2,
                            'stars' => $hotel->extra_field_23,
                        ] : null,
                    ];
                });

            // Get upcoming flight bookings
            $upcomingFlightBookings = FlightBooking::where('user_id', $userId)
                ->where('booking_status', 'confirmed')
                ->whereDate('departure_date', '>=', now())
                ->orderBy('departure_date', 'asc')
                ->limit(10)
                ->get()
                ->map(function ($booking) {
                    $passengerDetails = is_string($booking->passenger_details)
                        ? json_decode($booking->passenger_details, true)
                        : $booking->passenger_details;
            
                    $passengers = array_values($passengerDetails ?? []);
            
                    // Fix: Correct pluck to only use given_name + family_name
                    $names = collect($passengers)->map(function ($p) {
                        return trim(($p['given_name'] ?? '') . ' ' . ($p['family_name'] ?? ''));
                    })->filter()->unique()->implode(', ');
            
                    return [
                        'id' => $booking->id,
                        'type' => 'flight',
                        'airline_code' => $booking->airline_code,
                        'airline_name' => $booking->airline_name,
                        'airline_logo' => $booking->airline_code 
                            ? "https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{$booking->airline_code}.svg"
                            : null,
                        'booking_status' => $booking->booking_status,
                        'payment_status' => $booking->payment_status,
                        'family_names' => $names,
                        'departure_airport' => $booking->departure_airport ?? null,
                        'arrival_airport' => $booking->arrival_airport ?? null,
                        'departure_date_raw' => $booking->departure_date,
                        'departure_date' => $booking->departure_date 
                            ? Carbon::parse($booking->departure_date)->format('d M Y h:ia')
                            : null,
                        'total_amount' => $booking->total_amount,
                        'currency' => $booking->currency,
                        'days_until' => $booking->departure_date 
                            ? Carbon::now()->diffInDays(Carbon::parse($booking->departure_date), false)
                            : null,
                        'created_at' => $booking->created_at 
                            ? Carbon::parse($booking->created_at)->format('d M Y h:ia')
                            : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'hotel_bookings' => $upcomingHotelBookings,
                    'flight_bookings' => $upcomingFlightBookings,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get upcoming bookings'
            ], 500);
        }
    }

    /**
     * Get booking statistics
     */
    public function getBookingStats()
    {
        try {
            $userId = auth()->id();

            // Get booking statistics for the last 12 months
            $stats = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M Y');
                
                // Hotel bookings for this month
                $hotelBookings = Hotels::where('user_id', $userId)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();

                // Flight bookings for this month
                $flightBookings = FlightBooking::where('user_id', $userId)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();

                // Tour bookings for this month
                $tourBookings = DB::table('tour_bookings')
                    ->where('user_id', $userId)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();

                $stats[] = [
                    'month' => $monthName,
                    'hotel_bookings' => $hotelBookings,
                    'flight_bookings' => $flightBookings,
                    'tour_bookings' => $tourBookings,
                    'total_bookings' => $hotelBookings + $flightBookings + $tourBookings,
                ];
            }

            // Get total statistics
            $totalHotelBookings = Hotels::where('user_id', $userId)->count();
            $totalFlightBookings = FlightBooking::where('user_id', $userId)->count();
            $totalTourBookings = DB::table('tour_bookings')
                ->where('user_id', $userId)
                ->count();
            $totalBookings = $totalHotelBookings + $totalFlightBookings + $totalTourBookings;

            // Get payment statistics
            $paidHotelBookings = Hotels::where('user_id', $userId)
                ->where('status', 'paid')
                ->count();

            $confirmedFlightBookings = FlightBooking::where('user_id', $userId)
                ->where('booking_status', 'confirmed')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'monthly_stats' => $stats,
                    'total_stats' => [
                        'total_bookings' => $totalBookings,
                        'total_hotel_bookings' => $totalHotelBookings,
                        'total_flight_bookings' => $totalFlightBookings,
                        'total_tour_bookings' => $totalTourBookings,
                        'paid_hotel_bookings' => $paidHotelBookings,
                        'confirmed_flight_bookings' => $confirmedFlightBookings,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get booking statistics'
            ], 500);
        }
    }
} 
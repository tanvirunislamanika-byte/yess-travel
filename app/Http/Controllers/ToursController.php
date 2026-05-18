<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;
use App\Models\Tours;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TourBookingMail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Services\PaystackService;
use App\Services\TourBookingNotificationService;

class ToursController extends Controller
{
    protected $paystackService;
    
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Display tours listing page
     */
    public function index(Request $request)
    {
        $query = ModulesData::where('module_id', 34)->where('status', 'active');

        // Search by keyword
        if ($request->has('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->keyword.'%')
                  ->orWhere('description', 'like', '%'.$request->keyword.'%');
            });
        }

        // Filter by tour type
        if ($request->has('tour_type')) {
            $query->where('extra_field_10', $request->tour_type);
        }

        // Filter by transport type
        if ($request->has('transport_type')) {
            $query->where('extra_field_11', $request->transport_type);
        }

        // Filter by departure location
        if ($request->has('departure_country')) {
            $query->where('extra_field_5', $request->departure_country);
        }

        if ($request->has('departure_state')) {
            $query->where('extra_field_6', $request->departure_state);
        }

        if ($request->has('departure_city')) {
            $query->where('extra_field_7', $request->departure_city);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) BETWEEN ? AND ?", [$request->min_price, $request->max_price]);
        } elseif ($request->has('min_price')) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) >= ?", [$request->min_price]);
        } elseif ($request->has('max_price')) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) <= ?", [$request->max_price]);
        }

        // Filter by duration
        if ($request->has('min_days') && $request->has('max_days')) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) BETWEEN ? AND ?", [$request->min_days, $request->max_days]);
        } elseif ($request->has('min_days')) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) >= ?", [$request->min_days]);
        } elseif ($request->has('max_days')) {
            $query->whereRaw("CAST(extra_field_3 AS UNSIGNED) <= ?", [$request->max_days]);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('extra_field_1', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('extra_field_2', '<=', $request->end_date);
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw("CAST(extra_field_8 AS UNSIGNED) ASC");
                break;
            case 'price_high':
                $query->orderByRaw("CAST(extra_field_8 AS UNSIGNED) DESC");
                break;
            case 'duration_short':
                $query->orderByRaw("CAST(extra_field_3 AS UNSIGNED) ASC");
                break;
            case 'duration_long':
                $query->orderByRaw("CAST(extra_field_3 AS UNSIGNED) DESC");
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $tours = $query->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                      ->paginate(12);

        // Get filter options
        $countries = Country::orderBy('name')->get();
        $tourTypes = ModulesData::where('module_id', 35)->where('status', 'active')->orderBy('title')->get();
        $transportTypes = ModulesData::where('module_id', 36)->where('status', 'active')->orderBy('title')->get();

        // Get recent tours for sidebar
        $recentTours = ModulesData::where('module_id', 34)
                                 ->where('status', 'active')
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

        return view('tours.index', compact('tours', 'countries', 'tourTypes', 'transportTypes', 'recentTours'));
    }

    /**
     * Display tour detail page
     */
    public function detail($slug)
    {
        $tour = ModulesData::where('module_id', 34)
                          ->where('slug', $slug)
                          ->where('status', 'active')
                          ->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                          ->first();

        if (!$tour) {
            abort(404, 'Tour not found');
        }

        // Get related tours
        $relatedTours = ModulesData::where('module_id', 34)
                                 ->where('status', 'active')
                                 ->where('id', '!=', $tour->id)
                                 ->where(function($query) use ($tour) {
                                     $query->where('extra_field_5', $tour->extra_field_5) // Same departure country
                                           ->orWhere('extra_field_10', $tour->extra_field_10); // Same tour type
                                 })
                                 ->orderBy('created_at', 'desc')
                                 ->take(6)
                                 ->get();

        // Get tour types and transport types for booking form
        $tourTypes = ModulesData::where('module_id', 35)->where('status', 'active')->orderBy('title')->get();
        $transportTypes = ModulesData::where('module_id', 36)->where('status', 'active')->orderBy('title')->get();

        return view('tours.detail', compact('tour', 'relatedTours', 'tourTypes', 'transportTypes'));
    }

    /**
     * Get states by country for AJAX
     */
    public function getStatesByCountry(Request $request)
    {
        $countryId = $request->country_id;
        $states = State::where('country_id', $countryId)
                      ->orderBy('name')
                      ->get(['id', 'name']);

        return response()->json($states);
    }

    /**
     * Get cities by state for AJAX
     */
    public function getCitiesByState(Request $request)
    {
        $stateId = $request->state_id;
        $cities = City::where('state_id', $stateId)
                     ->orderBy('name')
                     ->get(['id', 'name']);

        return response()->json($cities);
    }

    /**
     * Get cities by country for AJAX
     */
    public function getCitiesByCountry(Request $request)
    {
        $countryId = $request->country_id;
        $cities = City::where('country_id', $countryId)
                     ->orderBy('name')
                     ->get(['id', 'name']);

        return response()->json($cities);
    }

    /**
     * Search tours API endpoint
     */
    public function search(Request $request)
    {
        $query = ModulesData::where('module_id', 34)->where('status', 'active');

        // Apply filters
        if ($request->keyword) {
            $query->where('title', 'like', '%'.$request->keyword.'%');
        }

        if ($request->departure_country) {
            $query->where('extra_field_5', $request->departure_country);
        }

        if ($request->tour_type) {
            $query->where('extra_field_10', $request->tour_type);
        }

        if ($request->min_price) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) >= ?", [$request->min_price]);
        }

        if ($request->max_price) {
            $query->whereRaw("CAST(extra_field_8 AS UNSIGNED) <= ?", [$request->max_price]);
        }

        $tours = $query->with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                      ->orderBy('created_at', 'desc')
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $tours
        ]);
    }

    /**
     * Get tour types for filter
     */
    public function getTourTypes()
    {
        $tourTypes = ModulesData::where('module_id', 35)
                               ->where('status', 'active')
                               ->orderBy('title')
                               ->get(['id', 'title']);

        return response()->json([
            'success' => true,
            'data' => $tourTypes
        ]);
    }

    /**
     * Get transport types for filter
     */
    public function getTransportTypes()
    {
        $transportTypes = ModulesData::where('module_id', 36)
                                   ->where('status', 'active')
                                   ->orderBy('title')
                                   ->get(['id', 'title']);

        return response()->json([
            'success' => true,
            'data' => $transportTypes
        ]);
    }

    /**
     * Show tour booking form
     */
    public function showBookingForm($id)
    {
        $tour = ModulesData::with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                           ->where('module_id', 34)
                           ->where('id', $id)
                           ->where('status', 'active')
                           ->firstOrFail();

        $countries = Country::orderBy('name')->get();

        // Default passenger counts (will be overridden by JavaScript from sessionStorage)
        $adults = 1;
        $children = 0;

        return view('tours.booking-form', compact('tour', 'countries', 'adults', 'children'));
    }

    /**
     * Show payment selection page
     */
    public function showPaymentSelection(Request $request, $id)
    {
        $tour = ModulesData::with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                           ->where('module_id', 34)
                           ->where('id', $id)
                           ->where('status', 'active')
                           ->firstOrFail();

        // Get the latest pending booking for this tour and user
        $booking = \App\Models\TourBooking::where('tour_id', $id)
                                         ->where('user_id', auth()->id())
                                         ->where('payment_status', 'pending')
                                         ->latest()
                                         ->first();

        if (!$booking) {
            return redirect()->route('tour.detail', $tour->slug)->with('error', 'No pending booking found. Please create a booking first.');
        }

        return view('tours.payment-selection', compact('tour', 'booking'));
    }

    /**
     * Store tour booking and redirect to payment
     */
    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'required|integer|min:0|max:10',
            'departure_date' => 'required|date|after:today',
            'passengers' => 'required|array|min:1',
            'passengers.*.title' => 'required|in:Mr,Mrs,Miss',
            'passengers.*.first_name' => 'required|string|max:255',
            'passengers.*.last_name' => 'required|string|max:255',
            'passengers.*.country' => 'required|exists:countries,id',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:255',
        ]);

        $tour = ModulesData::with(['departureCountry', 'departureState', 'departureCity', 'tourType', 'transportType'])
                           ->where('module_id', 34)
                           ->where('id', $id)
                           ->where('status', 'active')
                           ->firstOrFail();

        try {
            // Generate unique booking reference
            $bookingReference = 'TOUR-' . strtoupper(uniqid());

            // Store booking in database
            $booking = \App\Models\TourBooking::create([
                'user_id' => auth()->id(),
                'tour_id' => $tour->id,
                'booking_reference' => $bookingReference,
                'adults' => $request->adults,
                'children' => $request->children,
                'departure_date' => $request->departure_date,
                'total_amount' => ($tour->extra_field_8 ?? 0) * $request->adults + ($tour->extra_field_9 ?? 0) * $request->children,
                'adult_price' => $tour->extra_field_8 ?? 0,
                'children_price' => $tour->extra_field_9 ?? 0,
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'passenger_details' => json_encode($request->passengers),
                'contact_details' => json_encode([
                    'name' => $request->contact_name,
                    'email' => $request->contact_email,
                    'phone' => $request->contact_phone,
                ]),
                'status' => 'confirmed',
            ]);

            // Redirect to payment selection
            return redirect()->route('tour.booking.payment-selection', $tour->id)
                            ->with('success', 'Tour booking created! Please complete payment to confirm.');

        } catch (\Exception $e) {
            \Log::error('Tour booking error: ' . $e->getMessage());
            \Log::error('Tour booking error trace: ' . $e->getTraceAsString());
            return back()->with('error', 'An error occurred while creating your booking: ' . $e->getMessage());
        }
    }

    /**
     * Process Stripe payment
     */
    public function processStripePayment(Request $request, $booking_id)
    {
        try {
            $booking = \App\Models\TourBooking::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Check if the booking is already paid
            if ($booking->payment_status === 'completed') {
                return redirect()->route('tour.booking.success', ['id' => $booking_id]);
            }

            // Get the tour details
            $tour = ModulesData::where('id', $booking->tour_id)->first();

            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Update booking with payment method
            $booking->update([
                'payment_method' => 'stripe',
                'payment_id' => null, // Will be set after successful payment
            ]);

            // Create a PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $booking->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'tour_id' => $tour->id,
                    'user_id' => auth()->id(),
                ],
            ]);

            return view('tours.stripe-payment', [
                'clientSecret' => $paymentIntent->client_secret,
                'tour' => $tour,
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe payment error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    /**
     * Process PayPal payment
     */
    public function processPayPalPayment(Request $request, $booking_id)
    {
        try {
            $booking = \App\Models\TourBooking::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Check if the booking is already paid
            if ($booking->payment_status === 'completed') {
                return redirect()->route('tour.booking.success', ['id' => $booking_id]);
            }

            // Get the tour details
            $tour = ModulesData::where('id', $booking->tour_id)->first();

            // Update booking with payment method
            $booking->update([
                'payment_method' => 'paypal',
                'payment_id' => null, // Will be set after successful payment
            ]);

            // Get PayPal access token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.paypal.mode') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_USERPWD, config('services.paypal.client_id') . ":" . config('services.paypal.secret'));
            $authHeaders = array();
            $authHeaders[] = 'Accept: application/json';
            $authHeaders[] = 'Accept-Language: en_US';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
            $result = curl_exec($ch);
            curl_close($ch);
            $accessToken = json_decode($result)->access_token;

            // Create PayPal order
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.paypal.mode') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders' : 'https://api-m.paypal.com/v2/checkout/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($booking->total_amount, 2, '.', ''),
                        ],
                        'description' => 'Tour Booking: ' . $tour->title,
                        'custom_id' => $booking->id,
                    ],
                ],
            ]));
            $orderHeaders = array();
            $orderHeaders[] = 'Content-Type: application/json';
            $orderHeaders[] = 'Authorization: Bearer ' . $accessToken;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $orderHeaders);
            $result = curl_exec($ch);
            curl_close($ch);
            $order = json_decode($result);

            if (isset($order->id)) {
                // Update booking with PayPal order ID
                $booking->update(['payment_id' => $order->id]);

                // Redirect to PayPal checkout
                $paypalUrl = config('services.paypal.mode') === 'sandbox' 
                    ? 'https://www.sandbox.paypal.com/checkoutnow?token=' . $order->id
                    : 'https://www.paypal.com/checkoutnow?token=' . $order->id;

                return redirect($paypalUrl);
            } else {
                throw new \Exception('Failed to create PayPal order');
            }

        } catch (\Exception $e) {
            \Log::error('PayPal payment error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your PayPal payment. Please try again.');
        }
    }

    /**
     * Handle Stripe payment success
     */
    public function handleStripeSuccess(Request $request, $booking_id)
    {
        try {
            $booking = \App\Models\TourBooking::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Update booking status
            $booking->update([
                'payment_status' => 'completed',
                'payment_id' => $request->payment_intent_id ?? null,
                'status' => 'confirmed'
            ]);

            // Send notifications to user and admin
            try {
                $notificationService = new TourBookingNotificationService();
                $notificationService->sendBookingNotifications($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to send notifications: ' . $e->getMessage());
                // Don't fail the payment process if notifications fail
            }

            return redirect()->route('tour.booking.success', ['id' => $booking_id])
                            ->with('success', 'Payment completed successfully!');

        } catch (\Exception $e) {
            \Log::error('Stripe success handling error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment success.');
        }
    }

    /**
     * Handle PayPal payment success
     */
    public function handlePayPalSuccess(Request $request, $booking_id)
    {
        try {
            $booking = \App\Models\TourBooking::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Update booking status
            $booking->update([
                'payment_status' => 'completed',
                'payment_id' => $request->token ?? null,
                'status' => 'confirmed'
            ]);

            // Send notifications to user and admin
            try {
                $notificationService = new TourBookingNotificationService();
                $notificationService->sendBookingNotifications($booking);
            } catch (\Exception $e) {
                \Log::error('Failed to send notifications: ' . $e->getMessage());
                // Don't fail the payment process if notifications fail
            }

            return redirect()->route('tour.booking.success', ['id' => $booking_id])
                            ->with('success', 'Payment completed successfully!');

        } catch (\Exception $e) {
            \Log::error('PayPal success handling error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment success.');
        }
    }

    /**
     * Show booking success page
     */
    public function showBookingSuccess($id)
    {
        $booking = \App\Models\TourBooking::with(['tour', 'user'])
                                           ->where('id', $id)
                                           ->where('user_id', auth()->id())
                                           ->firstOrFail();

        return view('tours.booking-success', compact('booking'));
    }

    /**
     * Show user's tour bookings
     */
    public function userBookings()
    {
        $bookings = \App\Models\TourBooking::with(['tour'])
                                           ->where('user_id', auth()->id())
                                           ->orderBy('created_at', 'desc')
                                           ->paginate(10);

        return view('tours.user-bookings', compact('bookings'));
    }
}

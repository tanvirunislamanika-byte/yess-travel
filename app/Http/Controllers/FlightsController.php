<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;
use App\Models\Tags;
use App\Services\DuffelService;
use Str;
use App\Models\Flights;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use App\Services\ApiException;
use App\Models\FlightBooking;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use App\Mail\FlightBookingMail;
use App\Mail\BookingStatusMail;
use App\Services\PaystackService;



class FlightsController extends Controller
{
    protected $duffelService;
    protected $paystackService;
    
    public function __construct(DuffelService $duffelService, PaystackService $paystackService)
    {
        $this->duffelService = $duffelService;
        $this->paystackService = $paystackService;
    }
    public function index(Request $request)
    {
        Log::info('Flights index method called');
        Log::info('Request parameters:', $request->all());

        $from_location = $request->input('from_location');
        $to_location = $request->input('to_location');
        $travelling_date = $request->input('travelling_date');
        $return_date = $request->input('return_date');
        $triptype = $request->input('triptype');
        $adults = $request->input('adults', 1);
        $children = $request->input('children', 0);
        $sort_by = $request->input('sort_by', 'total_amount'); // Default sort by price
        $order = $request->input('order', 'asc'); // Default order ascending

        $offerRequests = null; // Initialize offerRequests

        // Check if necessary parameters are present for a search
        if ($from_location && $to_location && $travelling_date) {
            try {
                // Convert location names to IATA codes if necessary
                $from_code = strlen($from_location) === 3 ? $from_location : $this->duffelService->getLocationCode($from_location);
                $to_code = strlen($to_location) === 3 ? $to_location : $this->duffelService->getLocationCode($to_location);

                if (!$from_code || !$to_code) {
                    Log::warning('Invalid location codes provided.', [
                        'from_location' => $from_location,
                        'to_location' => $to_location,
                        'from_code' => $from_code,
                        'to_code' => $to_code,
                    ]);
                    return view('flights.index')->with('error', 'Invalid location(s) provided. Please ensure you select from the suggestions.');
                }

                $passengers = [];
                for ($i = 0; $i < $adults; $i++) {
                    $passengers[] = ['type' => 'adult'];
                }
                for ($i = 0; $i < $children; $i++) {
                    $passengers[] = ['type' => 'child'];
                }

                $offerRequests = $this->duffelService->getOfferRequests($from_code, $to_code, $travelling_date, $triptype === 'twoway' ? $return_date : null, $passengers);

                Log::info('DuffelService getOfferRequests response:', ['response' => $offerRequests]);
            } catch (ApiException $e) {
                Log::error('Duffel API Error: ' . $e->getMessage(), ['errors' => $e->getErrors()]);
                return view('flights.index')->with('error', 'Failed to fetch flight offers: ' . $e->getMessage());
            } catch (Exception $e) {
                Log::error('Error fetching flight offers: ' . $e->getMessage());
                return view('flights.index')->with('error', 'An unexpected error occurred while fetching flight offers.');
            }
        } else {
            Log::info('Insufficient search parameters provided.');
        }

        // Filter and sort offers if offerRequests were retrieved successfully
        $offers = [];
        if (isset($offerRequests['offers']) && is_array($offerRequests['offers'])) {
            $offers = collect($offerRequests['offers']);

            // Apply filters
            if ($request->filled('airline')) {
                $offers = $offers->filter(function ($offer) use ($request) {
                    // Assuming the first slice and first segment contain the operating carrier
                    return isset($offer['slices'][0]['segments'][0]['operating_carrier']['name']) && $offer['slices'][0]['segments'][0]['operating_carrier']['name'] === $request->input('airline');
                });
            }

            // Sort offers
            $offers = $offers->sortBy($sort_by, SORT_REGULAR, $order === 'desc');
        }

        // Get unique airlines from the fetched offers for filtering
        $airlines = [];
        if (isset($offerRequests['offers']) && is_array($offerRequests['offers'])) {
            $airlines = collect($offerRequests['offers'])->pluck('slices.*.segments.*.operating_carrier.name')->flatten()->unique()->sort()->filter()->all();
        }

        // Paginate the results
        $perPage = 10; // Number of results per page
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $offers->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedOffers = new LengthAwarePaginator($currentItems, $offers->count(), $perPage, $currentPage, ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]);

        return view('flights.offers', [
            'flights' => $paginatedOffers,
            'request' => $request,
            'airlines' => $airlines,
        ]);
    }
    public function services($slug = '')
    {
        $arr['services'] = ModulesData::where('module_id', 3)->where('status', 'active')->orderBy('id', 'desc')->get();
        return view('services.services')->with($arr);
    }
    public function detail($slug)
    {
        $data['flight'] = ModulesData::where('slug', trim($slug))->where('module_id', 3)->where('status', 'active')->first();
        $data['recent_data'] = ModulesData::where('module_id', 3)->where('id', '!=', $data['flight']->id)->where('status', 'active')->orderBy('id', 'desc')->take(3)->get();
        $data['archives'] = $this->lastThreeMonths();
        return view('flights.detail')->with($data);
    }
    function lastThreeMonths()
    {
        return [date('F Y', time()), date('F Y', strtotime('-1 month')), date('F Y', strtotime('-2 month'))];
    }
    public function showBookingForm($offer_id)
    {
        try {
            Log::info('Showing booking form for offer ID:', ['offer_id' => $offer_id]);
            
            // Debug: Log the offer_id being passed
            Log::info('Offer ID received:', ['offer_id' => $offer_id, 'type' => gettype($offer_id)]);
            
            $offer = $this->duffelService->getOffer($offer_id);
            
            // Debug: Log the response from DuffelService
            Log::info('Mock offer created for testing:', ['offer' => $offer, 'has_error' => isset($offer['error'])]);

            //$offer = $this->duffelService->getAllGroupedFareOptionsFromOfferRequest($offer_id);

            if (!$offer || isset($offer['error'])) {
                Log::error('Offer not found for booking form:', ['offer_id' => $offer_id, 'offer' => $offer]);

                return redirect()->route('flights.list')->with('error', 'Flight offer not found or is no longer available.');
            }

            session(['offer' => json_decode(json_encode($offer), true)]);
            $countries = Country::all();

            Log::info('About to return booking form view', [
                'offer_id' => $offer_id,
                'countries_count' => $countries->count(),
                'view_path' => 'flights.booking-form'
            ]);

            return view('flights.booking-form', [
                'offer_id' => $offer_id,
                'offer' => $offer,
                'passengers' => $offer['passengers'],
                'countries' => $countries,
            ]);
        } catch (Exception $e) {
            Log::error('Error showing booking form: ' . $e->getMessage());
            return redirect()->route('flights.list')->with('error', 'An unexpected error occurred.');
        }
    }

    public function storeBooking(Request $request, $offer_id)
    {
        try {



            $booking = FlightBooking::where('offer_id',$offer_id)->first();


            if ($booking !=null &&  $booking->booking_status == 'hold') {

                return back()->with('error', 'This flight is already on hold for you. Please proceed to payment to confirm your booking.');
            }

            // Validate passenger information
            $validationRules = [
                'passengers' => 'required|array',
            ];

            // Get offer to determine passenger types
            $offer = $this->duffelService->getOffer($offer_id);
            $passengerTypes = [];
            foreach ($offer['passengers'] ?? [] as $offerPassenger) {
                $passengerTypes[$offerPassenger['id']] = strtolower($offerPassenger['type'] ?? 'adult');
            }
            
            foreach ($request->input('passengers', []) as $passengerId => $passenger) {
                $validationRules['passengers.' . $passengerId . '.title'] = 'required';
                $validationRules['passengers.' . $passengerId . '.given_name'] = 'required';
                $validationRules['passengers.' . $passengerId . '.family_name'] = 'required';
                $validationRules['passengers.' . $passengerId . '.email'] = 'required|email';
                $validationRules['passengers.' . $passengerId . '.phone_number'] = 'required';
                $validationRules['passengers.' . $passengerId . '.phonecode'] = 'required';
                
                // Date of birth validation based on passenger type
                $passengerType = $passengerTypes[$passengerId] ?? 'adult';
                if ($passengerType === 'adult') {
                    // Adults must be at least 18 years old
                    $maxDate = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                    $minDate = \Carbon\Carbon::now()->subYears(100)->format('Y-m-d');
                    $validationRules['passengers.' . $passengerId . '.born_on'] = 'required|date|before_or_equal:' . $maxDate . '|after_or_equal:' . $minDate;
                } else {
                    // Children/Infants must be under 18 years old
                    $maxDate = \Carbon\Carbon::now()->format('Y-m-d');
                    $minDate = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                    $validationRules['passengers.' . $passengerId . '.born_on'] = 'required|date|before_or_equal:' . $maxDate . '|after_or_equal:' . $minDate;
                }
                
                $validationRules['passengers.' . $passengerId . '.gender'] = 'required';
                
                // Passport Information Validation
                $validationRules['passengers.' . $passengerId . '.passport_number'] = 'required|string|max:50';
                $validationRules['passengers.' . $passengerId . '.passport_issue_date'] = 'required|date|before:today';
                $validationRules['passengers.' . $passengerId . '.passport_expiry_date'] = 'required|date|after:today';
                $validationRules['passengers.' . $passengerId . '.passport_issuing_country'] = 'required|exists:countries,id';
                $validationRules['passengers.' . $passengerId . '.country_of_residence'] = 'required|exists:countries,id';
            }

            $validated = Validator::make($request->all(), $validationRules)->validate();
            if($booking==null)
            {
                $booking = new FlightBooking();
            }
            
            if ($request->booking_type == 'hold') {
                $hold_data = $this->duffelService->createHeldDuffelOrder($offer, $request->passengers);
                if ($hold_data['success']) {
                    $orderData = $hold_data['data'];
                    $orderId = $orderData['id'] ?? null;
                    $paymentDeadline = $orderData['payment_status']['payment_required_by'] ?? null;
                    $booking->system_order_id = $orderId;
                    $booking->order_expire_at = $paymentDeadline;
                } else {
                    return back()->with('error', $hold_data['error']);
                }
            }

            $offer = $this->duffelService->getOffer($offer_id);

            if (!$offer || isset($offer['error'])) {
                return back()->with('error', 'The selected flight offer is no longer available.');
            }

            // Create booking record

            $booking->user_id = auth()->id();
            $booking->offer_id = $offer_id;
            $booking->trip_type = count($offer['slices']) > 1 ? 'two-way' : 'one-way';
            $booking->departure_date = $offer['slices'][0]['segments'][0]['departing_at'];
            if (count($offer['slices']) > 1) {
                $booking->return_date = $offer['slices'][1]['segments'][0]['departing_at'];
            }
            $booking->origin_code = $offer['slices'][0]['origin']['iata_code'];
            $booking->destination_code = $offer['slices'][0]['destination']['iata_code'];
            // Save airline code for logo display
            $booking->airline_code = $offer['slices'][0]['segments'][0]['operating_carrier']['iata_code'] ?? null;

            $offer_pre = session('offer');
            $booking->flight_info = json_encode($offer_pre);

            $selectedServiceIds = $request->input('selected_services', []); // array of service IDs

            $matchedServices = [];

            if (!empty($selectedServiceIds) && !empty($offer['available_services'])) {
                foreach ($offer['available_services'] as $service) {
                    if (in_array($service['id'], $selectedServiceIds)) {
                        $matchedServices[] = $service;
                    }
                }
            }

            $selectedServicesTotal = 0;

            if (count($matchedServices) > 0) {
                foreach ($matchedServices as $service) {
                    $selectedServicesTotal += (float) $service['total_amount'];
                }
            }

            // Count adults and children
            $adults = 0;
            $children = 0;
            foreach ($offer['passengers'] as $passenger) {
                if ($passenger['type'] === 'adult') {
                    $adults++;
                }
                if ($passenger['type'] === 'child') {
                    $children++;
                }
            }
            $booking->adults = $adults;
            $booking->children = $children;
            $booking->services_total = $selectedServicesTotal;

            // $booking->total_amount = $offer['total_amount'];

            // $booking->currency = $offer['total_currency'];

            $booking->selected_services = json_encode($matchedServices);
            $booking->selected_services_ids = json_encode($selectedServiceIds);

            $serviceFee = (float) (widget(29)->extra_field_2 ?? 0);
            $servicePercent = (float) (widget(29)->extra_field_3 ?? 0);

            $sub_total = $offer['total_amount'];

            $servicePercentAmount = ($sub_total * $servicePercent) / 100;

            $totalServiceAmount = $serviceFee + $servicePercentAmount;

            $booking->total_amount = (float) $offer['total_amount'] + $selectedServicesTotal + $totalServiceAmount;

            $booking->service_charges = $totalServiceAmount;

            $booking->currency = $offer['total_currency'] ?? 'USD';

            $booking->passenger_details = $validated['passengers'];

            $booking->payment_status = 'pending';
            if ($request->booking_type == 'hold') {
                $booking->booking_status = 'hold';
            } else {
                $booking->booking_status = 'pending';
            }

            // Save outbound flight details
            $outboundSlice = $offer['slices'][0];
            $outboundSegment = $outboundSlice['segments'][0];
            $booking->airline_name = $outboundSegment['operating_carrier']['name'] ?? null;
            $booking->airline_code = $outboundSegment['operating_carrier']['iata_code'] ?? null;
            $booking->flight_number = $outboundSegment['operating_carrier_flight_number'] ?? null;
            $booking->departure_terminal = $outboundSegment['departing_at_terminal'] ?? null;
            $booking->arrival_terminal = $outboundSegment['arriving_at_terminal'] ?? null;
            $booking->origin_airport = $outboundSegment['origin']['name'] ?? null;
            $booking->destination_airport = $outboundSegment['destination']['name'] ?? null;
            $booking->checked_baggage = json_encode($outboundSlice['baggages']['checked_bags'] ?? null); // Store as JSON
            $booking->carry_on_baggage = json_encode($outboundSlice['baggages']['carry_on'] ?? null); // Store as JSON
            $booking->cabin_class = $outboundSlice['cabin_class'] ?? null;
            $booking->departure_time = $outboundSegment['departing_at'] ?? null;
            $booking->arrival_time = $outboundSegment['arriving_at'] ?? null;

            // Save return flight details if available
            if (count($offer['slices']) > 1) {
                $returnSlice = $offer['slices'][1];
                $returnSegment = $returnSlice['segments'][0];
                $booking->return_airline_name = $returnSegment['operating_carrier']['name'] ?? null;
                $booking->return_airline_code = $returnSegment['operating_carrier']['iata_code'] ?? null;
                $booking->return_flight_number = $returnSegment['operating_carrier_flight_number'] ?? null;
                $booking->return_departure_terminal = $returnSegment['departing_at_terminal'] ?? null;
                $booking->return_arrival_terminal = $returnSegment['arriving_at_terminal'] ?? null;
                $booking->return_departure_airport = $returnSegment['origin']['name'] ?? null;
                $booking->return_arrival_airport = $returnSegment['destination']['name'] ?? null;
                $booking->return_checked_baggage = json_encode($returnSlice['baggages']['checked_bags'] ?? null); // Store as JSON
                $booking->return_carry_on_baggage = json_encode($returnSlice['baggages']['carry_on'] ?? null); // Store as JSON
                $booking->return_cabin_class = $returnSlice['cabin_class'] ?? null;
                $booking->return_departure_time = $returnSegment['departing_at'] ?? null;
                $booking->return_arrival_time = $returnSegment['arriving_at'] ?? null;
            }

            $booking->save();

            // Redirect to payment page
            if ($request->booking_type == 'hold') {
                return redirect()->route('flight-booking');
            } else {
                return redirect()->route('flights.payment', $booking->id);
            }
        } catch (Exception $e) {
            Log::error('Error processing booking: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while processing your booking. Please try again.' . $e->getMessage());
        }
    }

    public function showPayment($booking_id)
    {
        $booking = FlightBooking::findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($booking->payment_status == 'paid') {
            return back()->with('success', 'A payment has already been processed for this.');
        }


        if ($booking->booking_status == 'pending') {
            $offer = $this->duffelService->getOffer($booking->offer_id);

            if (!$offer || isset($offer['error'])) {
                return back()->with('error', 'The selected flight offer is no longer available.');
            }
        }

        if ($booking->booking_status == 'hold') {
            $check_order_hold = $this->duffelService->checkDuffelOrderStatus($booking->system_order_id);
            if (!$check_order_hold['success'] || isset($check_order_hold['error'])) {
                return back()->with('error', 'The hold on this flight is no longer available. Please search again to select a new offer.');
            }
        }

        return view('flights.payment', compact('booking'));
    }

    public function processStripePayment(Request $request, $booking_id)
    {
        try {
            $booking = FlightBooking::findOrFail($booking_id);
            // Ensure the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            // Log the Stripe configuration
            Log::info('Stripe configuration:', [
                'key_exists' => !empty(config('services.stripe.key')),
                'secret_exists' => !empty(config('services.stripe.secret')),
                'booking_amount' => $booking->total_amount,
                'currency' => $booking->currency,
            ]);

            // Initialize Stripe with the secret key
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => (int) ($booking->total_amount * 100), // Convert to cents
                'currency' => strtolower($booking->currency),
                'payment_method_types' => ['card'],
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
            ]);

            // Log the payment intent creation
            Log::info('Payment intent created:', [
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
            ]);

            // Update booking with payment information
            $booking->payment_method = 'stripe';
            $booking->transaction_id = $paymentIntent->id;
            $booking->save();

            return view('flights.stripe-payment', [
                'clientSecret' => $paymentIntent->client_secret,
                'booking' => $booking,
            ]);
        } catch (Exception $e) {
            Log::error('Stripe payment error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    public function processPayPalPayment(Request $request, $booking_id)
    {
        try {
            $booking = FlightBooking::findOrFail($booking_id);

            // Ensure the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            // Initialize PayPal with configuration
            $provider = new \Srmklive\PayPal\Services\PayPal([
                'mode' => config('services.paypal.mode'),
                'client_id' => config('services.paypal.client_id'),
                'client_secret' => config('services.paypal.client_secret'),
            ]);

            $data = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $booking->currency,
                            'value' => $booking->total_amount,
                        ],
                        'reference_id' => $booking->id,
                    ],
                ],
            ];

            $order = $provider->createOrder($data);

            // Update booking with payment information
            $booking->payment_method = 'paypal';
            $booking->transaction_id = $order['id'];
            $booking->save();

            return redirect($order['links'][1]['href']);
        } catch (Exception $e) {
            Log::error('PayPal payment error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    public function processPaystackPayment(Request $request, $booking_id)
    {
        try {
            Log::info('Paystack payment process started', ['booking_id' => $booking_id]);
            
            $booking = FlightBooking::findOrFail($booking_id);

            // Ensure the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                Log::error('Unauthorized access attempt for Paystack payment', ['booking_id' => $booking_id, 'user_id' => auth()->id()]);
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            // Check if the booking is already paid
            if ($booking->payment_status == 'paid') {
                Log::info('Booking already paid, redirecting to success', ['booking_id' => $booking_id]);
                return redirect()->route('flights.payment.success', ['booking_id' => $booking_id]);
            }

            // Check Paystack configuration
            $paystackConfig = [
                'public_key' => config('services.paystack.public_key'),
                'secret_key' => config('services.paystack.secret_key'),
                'merchant_email' => config('services.paystack.merchant_email'),
            ];
            
            Log::info('Paystack configuration', [
                'public_key_exists' => !empty($paystackConfig['public_key']),
                'secret_key_exists' => !empty($paystackConfig['secret_key']),
                'merchant_email_exists' => !empty($paystackConfig['merchant_email']),
            ]);

            if (empty($paystackConfig['secret_key'])) {
                Log::error('Paystack secret key not configured');
                return back()->with('error', 'Payment gateway not configured. Please contact support.');
            }

            // Generate a unique reference for this transaction
            $reference = $this->paystackService->generateReference('FLT');
            Log::info('Generated Paystack reference', ['reference' => $reference]);

            // Initialize Paystack transaction
            // Note: Paystack primarily supports NGN. For other currencies, consider using Stripe or PayPal
            $originalAmount = $booking->total_amount;
            $originalCurrency = $booking->currency ?? 'USD';
            
            // Convert USD to NGN using the service method
            $ngnAmount = $this->paystackService->convertCurrency($originalAmount, $originalCurrency, 'NGN');
            
            $transactionData = [
                'email' => auth()->user()->email,
                'amount' => $ngnAmount,
                'currency' => 'NGN', // Paystack primarily supports NGN
                'reference' => $reference,
                'callback_url' => route('flights.payment.paystack.callback', ['booking_id' => $booking_id]),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                    'booking_type' => 'flight',
                    'original_currency' => $originalCurrency,
                    'original_amount' => $originalAmount,
                    'conversion_rate' => $originalCurrency === 'USD' ? 1500 : 1,
                ],
            ];

            Log::info('Paystack transaction data', $transactionData);

            $result = $this->paystackService->initializeTransaction($transactionData);
            Log::info('Paystack initialization result', $result);

            if ($result['success']) {
                // Update booking with payment information
                $booking->payment_method = 'paystack';
                $booking->transaction_id = $reference;
                $booking->save();

                Log::info('Paystack payment initialized successfully', [
                    'booking_id' => $booking_id,
                    'reference' => $reference,
                    'authorization_url' => $result['authorization_url']
                ]);

                // Redirect to Paystack payment page
                return redirect($result['authorization_url']);
            } else {
                Log::error('Paystack initialization failed', [
                    'booking_id' => $booking_id,
                    'error' => $result['message'],
                    'result' => $result
                ]);
                return back()->with('error', 'Failed to initialize payment: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            Log::error('Paystack payment error: ' . $e->getMessage(), [
                'booking_id' => $booking_id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    public function paystackCallback(Request $request, $booking_id)
    {
        try {
            $booking = FlightBooking::findOrFail($booking_id);

            // Ensure the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            $reference = $request->query('reference');
            
            if (!$reference) {
                return redirect()->route('flights.payment.cancel', ['booking_id' => $booking_id])
                    ->with('error', 'Payment reference not found.');
            }

            // Verify the transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['success'] && $verification['status'] === 'success') {
                // Get the original amount from metadata
                $originalAmount = $verification['data']['metadata']['original_amount'] ?? $booking->total_amount;
                $originalCurrency = $verification['data']['metadata']['original_currency'] ?? 'USD';
                
                // Payment successful
                $booking->payment_status = 'paid';
                $booking->booking_status = 'confirmed';
                $booking->paid_amount = $originalAmount;
                $booking->paid_currency = $originalCurrency;
                $booking->save();

                // Send confirmation emails
                try {
                    Mail::to($booking->user->email)->send(new FlightBookingMail($booking, $booking->user));
                    
                    $adminEmail = widget(1)->extra_field_2;
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new FlightBookingMail($booking, $booking->user));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send Paystack confirmation emails: ' . $e->getMessage());
                }

                return redirect()->route('flights.booking.details', ['booking_id' => $booking_id])
                    ->with('success', 'Payment successful! Your booking has been confirmed.');
            } else {
                // Payment failed
                $booking->payment_status = 'failed';
                $booking->save();

                return redirect()->route('flights.payment.cancel', ['booking_id' => $booking_id])
                    ->with('error', 'Payment verification failed. Please try again.');
            }
        } catch (Exception $e) {
            Log::error('Paystack callback error: ' . $e->getMessage());
            return redirect()->route('flights.payment.cancel', ['booking_id' => $booking_id])
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    public function paymentSuccess($booking_id)
    {
        \Log::info('Payment success method reached', ['booking_id' => $booking_id]);
        try {
            $booking = FlightBooking::findOrFail($booking_id);

            \Log::info('Booking found for payment success', ['booking_id' => $booking->id]);

            // Update booking status
            $booking->booking_status = 'confirmed';
            $booking->payment_status = 'paid';
            \Log::info('Attempting to save booking status as confirmed/paid', ['booking_id' => $booking->id]);
            $booking->save();
            \Log::info('Booking status saved as confirmed/paid', ['booking_id' => $booking->id]);

            // Get the full booking details from Duffel after payment
            // Assuming transaction_id holds the Duffel booking ID, which is set in the book method now.
            // If transaction_id holds the payment intent ID instead, you'd need a different way to get the Duffel booking ID here.
            $duffelBookingId = $booking->booking_reference ?? ($booking->order_id ?? $booking->transaction_id); // Use saved Duffel ID if available

            if ($duffelBookingId && !Str::startsWith($duffelBookingId, 'pi_')) {
                // Avoid calling getBooking with a Stripe Payment Intent ID
                try {
                    $duffelBooking = $this->duffelService->getBooking($duffelBookingId);
                    \Log::info('Duffel booking details fetched after payment', ['duffel_booking_id' => $duffelBookingId, 'response' => $duffelBooking]);

                    // Save Duffel booking id as booking_reference/order_id if not already saved
                    if (isset($duffelBooking['data']['id'])) {
                        if (empty($booking->booking_reference)) {
                            $booking->booking_reference = $duffelBooking['data']['id'];
                        }
                        if (empty($booking->order_id)) {
                            $booking->order_id = $duffelBooking['data']['id'];
                        }
                        // Save again if we updated booking_reference/order_id
                        if (!empty($booking->getChanges())) {
                            \Log::info('Saving booking_reference/order_id after fetching Duffel details', ['booking_id' => $booking->id]);
                            $booking->save();
                            \Log::info('booking_reference/order_id saved', ['booking_id' => $booking->id]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Error fetching Duffel booking details after payment: ' . $e->getMessage(), ['duffel_booking_id' => $duffelBookingId]);
                    // Continue even if fetching Duffel details fails
                }
            }

            // Send confirmation emails to both user and admin
            try {
                // Send to user
                Mail::to($booking->user->email)->send(new FlightBookingMail($booking, $booking->user));

                // Send to admin
                $adminEmail = widget(1)->extra_field_2;
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new FlightBookingMail($booking, $booking->user));
                }

                \Log::info('Confirmation emails sent successfully', [
                    'booking_id' => $booking_id,
                    'user_email' => $booking->user->email,
                    'admin_email' => $adminEmail,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation emails: ' . $e->getMessage());
                // Continue with the flow even if email fails
            }

            // Redirect to booking details page
            return redirect()
                ->route('flights.booking.details', ['booking_id' => $booking_id])
                ->with('success', 'Payment successful! Your booking has been confirmed.');
        } catch (\Exception $e) {
            \Log::error('Payment success error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'There was an error processing your payment. Please contact support.');
        }
    }

    public function paymentCancel(Request $request, $booking_id)
    {
        try {
            $booking = FlightBooking::findOrFail($booking_id);

            // Ensure the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }

            // Update booking status
            $booking->payment_status = 'failed';
            $booking->booking_status = 'cancelled';
            $booking->save();

            return redirect()->route('flights.payment', $booking_id)->with('error', 'Payment was cancelled. Please try again or choose a different payment method.');
        } catch (Exception $e) {
            Log::error('Payment cancel error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'An error occurred. Please contact support.');
        }
    }

    public function showBookingDetails($bookingId)
    {
        try {
            // Fetch from FlightBooking model
            $booking = \App\Models\FlightBooking::find($bookingId);

            if (!$booking) {
                \Log::warning('Booking details not found for ID:', ['booking_id' => $bookingId]);
                return redirect()->route('dashboard')->with('error', 'Booking details not found.');
            }
            \Log::info('Showing booking details for ID:', ['booking_id' => $bookingId, 'booking_data' => $booking->toArray()]);

            // Decode passenger details
            $passengerDetails = is_string($booking->passenger_details) ? json_decode($booking->passenger_details, true) : $booking->passenger_details;
            $passengers = array_values($passengerDetails ?? []);

            // Optionally, fetch live Duffel data here if needed

            return view('flights.booking-details', [
                'booking' => $booking,
                'passengers' => $passengers,
            ]);
        } catch (Exception $e) {
            \Log::error('Error showing booking details: ' . $e->getMessage(), ['booking_id' => $bookingId, 'stacktrace' => $e->getTraceAsString()]);
            return view('flights.booking-details', ['error' => $e->getMessage()]);
        }
    }

    public function book(Request $request)
    {
        try {
            $validated = $request->validate([
                'offer_id' => 'required|string',
                'passengers' => 'required|array',
                'passengers.*.title' => 'required|string',
                'passengers.*.phone_number' => 'required|string',
                'passengers.*.email' => 'required|email',
                'passengers.*.given_name' => 'required|string',
                'passengers.*.family_name' => 'required|string',
                'passengers.*.gender' => 'required|string',
                'passengers.*.born_on' => 'required|date',
                'passengers.*.type' => 'required|string',
            ]);

            // Get the offer details first

            $offer = $this->duffelService->getOffer($request->offer_id);

            // Log the complete offer data for debugging
            \Log::info('Complete offer data from Duffel:', [
                'offer' => $offer,
            ]);

            // Create the booking using Duffel API
            $duffelBooking = $this->duffelService->createBooking($request->offer_id, $validated['passengers'], [
                'amount' => $offer['total_amount'],
                'currency' => $offer['total_currency'],
                'card_number' => $request->card_number,
                'card_holder' => $request->card_holder,
                'expiry_month' => $request->expiry_month,
                'expiry_year' => $request->expiry_year,
                'cvv' => $request->cvv,
            ]);

            // Log the complete Duffel booking response
            \Log::info('Complete Duffel booking response:', [
                'duffel_booking' => $duffelBooking,
            ]);

            // Create a new FlightBooking record in your database
            $booking = new \App\Models\FlightBooking(); // <-- Corrected Model
            $booking->user_id = auth()->id(); // Assuming user is authenticated
            $booking->offer_id = $request->offer_id;
            $booking->booking_reference = $duffelBooking['id'] ?? null; // Store Duffel booking ID
            $booking->order_id = $duffelBooking['id'] ?? null; // Store Duffel booking ID
            $booking->booking_status = $duffelBooking['status'] ?? 'pending'; // Use status from API response
            $booking->payment_status = $duffelBooking['payments'][0]['status'] ?? 'pending'; // Use payment status from API
            $booking->total_amount = $duffelBooking['total_amount'] ?? ($offer['total_amount'] ?? 0); // Use amount from booking response, fallback to offer
            $booking->currency = $duffelBooking['total_currency'] ?? ($offer['total_currency'] ?? 'USD'); // Use currency from booking response, fallback to offer
            $booking->passenger_details = json_encode($validated['passengers']); // Store validated passenger data as JSON
            $booking->transaction_id = $duffelBooking['payments'][0]['id'] ?? null; // Store transaction ID
            $booking->payment_method = $duffelBooking['payments'][0]['payment_method']['type'] ?? 'card'; // Store payment method

            // Store outbound flight details
            if (isset($duffelBooking['slices'][0])) {
                $outboundSlice = $duffelBooking['slices'][0];

                // Log the outbound slice data for debugging
                \Log::info('Processing outbound slice for saving:', [
                    'slice' => $outboundSlice,
                ]);

                $booking->trip_type = count($duffelBooking['slices']) > 1 ? 'two-way' : 'one-way';
                $booking->departure_date = $outboundSlice['segments'][0]['departing_at'] ?? null;
                $booking->origin_code = $outboundSlice['origin']['iata_code'] ?? null;
                $booking->destination_code = $outboundSlice['destination']['iata_code'] ?? null;
                $booking->airline_code = $outboundSlice['segments'][0]['operating_carrier']['iata_code'] ?? null;
                $booking->airline_name = $outboundSlice['segments'][0]['operating_carrier']['name'] ?? null;
                $booking->flight_number = $outboundSlice['segments'][0]['operating_carrier_flight_number'] ?? null;
                $booking->departure_terminal = $outboundSlice['segments'][0]['origin_terminal'] ?? null;
                $booking->arrival_terminal = $outboundSlice['segments'][0]['destination_terminal'] ?? null;
                $booking->origin_airport = $outboundSlice['segments'][0]['origin']['name'] ?? null;
                $booking->destination_airport = $outboundSlice['segments'][0]['destination']['name'] ?? null;

                // Store complete outbound segments data as JSON
                $booking->outbound_segments_data = json_encode($outboundSlice['segments'] ?? []);

                // Store outbound baggage information (checked and carry-on) as JSON strings
                if (isset($outboundSlice['segments'][0]['passengers'][0]['baggages'])) {
                    $outboundBaggages = $outboundSlice['segments'][0]['passengers'][0]['baggages'];
                    $checkedBags = array_filter($outboundBaggages, function ($bag) {
                        return ($bag['type'] ?? '') === 'checked';
                    });
                    $carryOnBags = array_filter($outboundBaggages, function ($bag) {
                        return ($bag['type'] ?? '') === 'carry_on';
                    });

                    $booking->checked_baggage = !empty($checkedBags) ? json_encode(array_values($checkedBags)) : null; // Store as JSON string
                    $booking->carry_on_baggage = !empty($carryOnBags) ? json_encode(array_values($carryOnBags)) : null; // Store as JSON string
                }

                $booking->cabin_class = $outboundSlice['cabin_class'] ?? ($outboundSlice['segments'][0]['passengers'][0]['cabin_class_marketing_name'] ?? null);
                $booking->departure_time = $outboundSlice['segments'][0]['departing_at'] ?? null;
                $booking->arrival_time = $outboundSlice['segments'][0]['arriving_at'] ?? null;

                // Log the stored outbound flight details for verification
                \Log::info('Stored outbound flight details:', [
                    'departure_date' => $booking->departure_date,
                    'origin_code' => $booking->origin_code,
                    'destination_code' => $booking->destination_code,
                    'airline_code' => $booking->airline_code,
                    'airline_name' => $booking->airline_name,
                    'flight_number' => $booking->flight_number,
                    'outbound_segments_data' => $booking->outbound_segments_data,
                ]);
            }

            // Store return flight details if available
            if (isset($duffelBooking['slices'][1])) {
                $returnSlice = $duffelBooking['slices'][1];
                \Log::info('Processing return slice:', [
                    'slice' => $returnSlice,
                ]);

                $booking->return_flight_number = $returnSlice['segments'][0]['operating_carrier_flight_number'];
                $booking->return_airline_code = $returnSlice['segments'][0]['operating_carrier']['iata_code'];
                $booking->return_airline_name = $returnSlice['segments'][0]['operating_carrier']['name'];
                $booking->return_departure_terminal = $returnSlice['segments'][0]['origin_terminal'] ?? null;
                $booking->return_arrival_terminal = $returnSlice['segments'][0]['destination_terminal'] ?? null;
                $booking->return_cabin_class = $returnSlice['segments'][0]['passengers'][0]['cabin_class_marketing_name'] ?? null;

                // Store complete return segments data
                $booking->return_segments_data = json_encode($returnSlice['segments']);

                \Log::info('Stored return flight details:', [
                    'return_flight_number' => $booking->return_flight_number,
                    'return_airline_code' => $booking->return_airline_code,
                    'return_airline_name' => $booking->return_airline_name,
                    'return_segments_data' => $booking->return_segments_data,
                ]);
            }

            // Count adults and children
            $booking->adults = collect($validated['passengers'])->where('type', 'adult')->count();
            $booking->childrens = collect($validated['passengers'])->where('type', 'child')->count();

            // Store transaction details
            $booking->transaction_id = $duffelBooking['payments'][0]['id'] ?? null;
            $booking->payment_method = 'card';

            // Log the final booking data before saving
            \Log::info('Final booking data before saving:', [
                'booking' => $booking->toArray(),
            ]);

            $booking->save();

            // Send confirmation email
            try {
                Mail::to($validated['passengers'][0]['email'])->send(new BookingStatusMail($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('flights.booking.success', ['id' => $booking->id]);
        } catch (\Exception $e) {
            \Log::error('Booking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function bookingSuccess(Request $request, $booking_id = null)
    {
        Log::info('Accessing booking success page.');
        Log::info('Current session data:', $request->session()->all());

        $localBookingId = $booking_id ?? session('booking_id');

        if (!$localBookingId) {
            Log::error('No booking ID found in session or URL for success page.');
            return redirect()->route('home')->with('error', 'Could not retrieve booking details. Please check your bookings list.');
        }

        try {
            $booking = Flights::find($localBookingId);

            if (!$booking) {
                Log::error('Booking not found in database for ID:', ['local_booking_id' => $localBookingId]);
                session()->forget('booking_id');
                return redirect()->route('home')->with('error', 'Booking details not found. Please check your bookings list.');
            }

            Log::info('Booking details retrieved from database:', ['booking' => $booking->toArray()]);

            // Decode passenger details JSON
            $passengerDetails = $booking->passenger_details ? json_decode($booking->passenger_details, true) : [];

            session()->forget('booking_id');
            Log::info('Booking ID cleared from session.');

            return view('flights.booking-success', [
                'booking' => $booking,
                'passengerDetails' => $passengerDetails,
            ])->with('success', session('success', 'Booking confirmed!'));
        } catch (Exception $e) {
            Log::error('Error retrieving booking details for success page: ' . $e->getMessage(), ['local_booking_id' => $localBookingId, 'stacktrace' => $e->getTraceAsString()]);
            return redirect()->route('home')->with('error', 'An error occurred while loading booking details. Please contact support.');
        }
    }

    public function search(Request $request)
    {
        Log::info('Flights search method called');
        Log::info('Search request parameters:', $request->all());

        $validatedData = $request->validate([
            'from_location' => 'required|string',
            'to_location' => 'required|string',
            'travelling_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:travelling_date',
            'triptype' => 'required|in:oneway,twoway',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
        ]);

        // Use the index method to handle the search and display results
        return $this->index($request);
    }

    // Helper to get location code (can be moved to service if needed elsewhere)
    protected function getLocationCode($locationName)
    {
        // This is a simplified example. A real implementation would use the DuffelService
        // or a local database of airports to find the IATA code.
        // For now, assuming a direct lookup or that the input is already a code.

        // Example: basic lookup (replace with actual logic)
        $airports = [
            'New York' => 'NYC',
            'Los Angeles' => 'LAX',
            'London' => 'LON',
            // Add more mappings
        ];

        $code = $airports[$locationName] ?? null;

        if (!$code && strlen($locationName) === 3) {
            // Assume input is already an IATA code if 3 characters
            $code = $locationName;
        }

        return $code;
    }

    public function testBookingSuccess(Request $request, $bookingId = null)
    {
        // For testing purposes, simulate setting a booking ID in the session
        if ($bookingId) {
            session()->put('booking_id', $bookingId);
            Log::info('Test booking ID set in session:', ['booking_id' => $bookingId]);
        } else {
            // Or simulate a scenario where no booking ID is found
            session()->forget('booking_id');
            Log::info('Test: Booking ID removed from session.');
        }

        // Then redirect to the actual booking success route
        return redirect()->route('flights.booking.success');
    }
}

<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModulesData;
use App\Models\Tags;
use App\Models\Hotels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\HotelBookingMail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Services\PaystackService;


class HotelsController extends Controller
{
    protected $paystackService;
    
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }
    public function index(Request $request){
        $data = ModulesData::where('module_id', 1)->where('status','active');

        if ($request->has('keyword')) {
            $data->where(function($query) use ($request) {
                $query->where('title', 'like', '%'.$request->keyword.'%')
                      ->orWhere('extra_field_18', 'like', '%'.$request->keyword.'%');
            });
        }
        

        if ($request->has('type')) {
            $data->where('extra_field_2', $request->type);
        }

        if ($request->has('searchlocation')) {
            $data->where('extra_field_18', $request->searchlocation);
        }

        if ($request->has('services') && is_array($request->services)) {
            foreach ($request->services as $service) {
                $data->whereJsonContains('services', $service);
            }
        }

        if ($request->has('cusine') && is_array($request->cusine)) {
            foreach ($request->cusine as $cuisine) {
                $data->whereJsonContains('cusine', $cuisine);
            }
        }

        
        if ($request->has('location')) {
            $data->where('extra_field_21', $request->location);
        }

        if ($request->has('destination')) {
            $data->where('extra_field_24', $request->destination);
        }

        if ($request->has('archive')) {
            $data->whereMonth('created_at', date('m', strtotime($request->archive)))->whereYear('created_at', date('Y', strtotime($request->archive)));
        }

        if ($request->has('tag')) {
            $data->whereRaw("FIND_IN_SET($request->tag,tag_ids)");
        }

        if ($request->has('min_price') && $request->has('max_price')) { 
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) BETWEEN ? AND ?", [$request->min_price, $request->max_price]); 
        } elseif ($request->has('min_price')) {
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) >= ?", [$request->min_price]);
        } elseif ($request->has('max_price')) {
            $data->whereRaw("CAST(extra_field_1 AS UNSIGNED) <= ?", [$request->max_price]);
        }
        


        if ($request->has('cate')) {
            $data->whereRaw("FIND_IN_SET($request->cate,category_ids)");
        }

        $arr['hotels'] = $data->paginate(10);

        $arr['recent_data'] = ModulesData::where('module_id', 1)->where('status','active')->orderBy('id','desc')->take(3)->get(); 
        $arr['archives'] = $this->lastThreeMonths();
        return view('hotels.index')->with($arr);
    }   

    public function services($slug='')
    {
        $service = ModulesData::where('slug',$slug)->first();
        $arr['services'] = ModulesData::where('module_id', 6)->where('status','active')->where('category',$service->id)->orderBy('id','desc')->get();
        return view('services.services')->with($arr);
    }

    public function detail($slug){
    	$data['hotel'] = ModulesData::where('slug',trim($slug))
    	 		->where('module_id', 1)
    	 		->where('status','active')
    	 		->first();

    	$data['recent_data'] = ModulesData::where('module_id', 1)->where('status','active')->orderBy('id','desc')->take(3)->get(); 
    	
		$data['archives'] = $this->lastThreeMonths();
    	 
    	return view('hotels.detail')->with($data); 		
    }

    function lastThreeMonths() {
	    return array(
	        date('F Y', time()),
	        date('F Y', strtotime('-1 month')),
	        date('F Y', strtotime('-2 month'))
	    );
	}

    public function showBookingForm($hotel_id)
    {
        $hotel = ModulesData::findOrFail($hotel_id);
        return view('hotels.booking', compact('hotel'));
    }

    public function storeBooking(Request $request, $hotel_id)
    {
        try {
            $validated = $request->validate([
                'hotel_id' => 'required|exists:modules_data,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1',
                'childrens' => 'required|integer|min:0',
                'rooms' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'travelling_from' => 'required|string',
            ]);

            $hotel = new Hotels();
            $hotel->hotel_id = $validated['hotel_id'];
            $hotel->user_id = auth()->id();
            $hotel->check_in = $validated['check_in'];
            $hotel->check_out = $validated['check_out'];
            $hotel->adults = $validated['adults'];
            $hotel->childrens = $validated['childrens'];
            $hotel->rooms = $validated['rooms'];
            $hotel->price = $validated['price'];
            $hotel->travelling_from = $validated['travelling_from'];
            $hotel->status = 'Pending';
            $hotel->save();

            return redirect()->route('hotel.payment.show', ['booking_id' => $hotel->id]);
        } catch (\Exception $e) {
            \Log::error('Hotel booking error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your booking. Please try again.');
        }
    }

    public function showPayment($booking_id)
    {
        $booking = Hotels::with('hotel')->findOrFail($booking_id);
        
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if the booking is already paid
        if ($booking->status === 'Paid') {
            return redirect()->route('hotel.payment.success', ['booking_id' => $booking_id]);
        }

        return view('hotel-payment', compact('booking'));
    }

    public function processStripePayment(Request $request, $booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Check if the booking is already paid
            if ($booking->status === 'Paid') {
                return redirect()->route('hotel.payment.success', ['booking_id' => $booking_id]);
            }

            // Get the hotel details
            $hotel = ModulesData::where('id', $booking->hotel_id)->first();

            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Create a PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $booking->price * 100, // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'hotel_id' => $hotel->id,
                    'user_id' => auth()->id(),
                ],
            ]);

            return view('stripe-hotel', [
                'clientSecret' => $paymentIntent->client_secret,
                'hotel' => $hotel,
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            \Log::error('Stripe payment error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    public function processPayPalPayment(Request $request, $booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Check if the booking is already paid
            if ($booking->status === 'Paid') {
                return redirect()->route('hotel.payment.success', ['booking_id' => $booking_id]);
            }

            // Get the hotel details
            $hotel = ModulesData::where('id', $booking->hotel_id)->first();

            // Get PayPal access token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.paypal.mode') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            curl_setopt($ch, CURLOPT_USERPWD, config('services.paypal.client_id') . ":" . config('services.paypal.client_secret'));
            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Accept-Language: en_US';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
                            'value' => number_format($booking->price, 2, '.', ''),
                        ],
                        'description' => 'Hotel Booking: ' . $hotel->title,
                        'custom_id' => $booking->id,
                    ],
                ],
            ]));
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $accessToken;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
            $order = json_decode($result);

            // Update booking with PayPal order ID
            $booking->payment_id = $order->id;
            $booking->save();

            return response()->json([
                'id' => $order->id,
                'links' => $order->links,
            ]);
        } catch (\Exception $e) {
            \Log::error('PayPal payment error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your payment. Please try again.'], 500);
        }
    }

    public function processPaystackPayment(Request $request, $booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Check if the booking is already paid
            if ($booking->status === 'Paid') {
                return redirect()->route('hotel.payment.success', ['booking_id' => $booking_id]);
            }

            // Get the hotel details
            $hotel = ModulesData::where('id', $booking->hotel_id)->first();

            // Generate a unique reference for this transaction
            $reference = $this->paystackService->generateReference('HTL');

            // Initialize Paystack transaction
            // Note: Paystack primarily supports NGN. For other currencies, consider using Stripe or PayPal
            $originalAmount = $booking->price;
            $originalCurrency = 'USD'; // Hotel bookings are typically in USD
            
            // Convert USD to NGN using the service method
            $ngnAmount = $this->paystackService->convertCurrency($originalAmount, $originalCurrency, 'NGN');
            
            $transactionData = [
                'email' => auth()->user()->email,
                'amount' => $ngnAmount,
                'currency' => 'NGN', // Paystack primarily supports NGN
                'reference' => $reference,
                'callback_url' => route('hotel.payment.paystack.callback', ['booking_id' => $booking_id]),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                    'booking_type' => 'hotel',
                    'hotel_id' => $hotel->id,
                    'original_currency' => $originalCurrency,
                    'original_amount' => $originalAmount,
                    'conversion_rate' => 1500,
                ],
            ];

            $result = $this->paystackService->initializeTransaction($transactionData);

            if ($result['success']) {
                // Update booking with payment information
                $booking->payment_method = 'paystack';
                $booking->transaction_id = $reference;
                $booking->save();

                // Redirect to Paystack payment page
                return redirect($result['authorization_url']);
            } else {
                \Log::error('Paystack initialization failed', [
                    'booking_id' => $booking_id,
                    'error' => $result['message']
                ]);
                return back()->with('error', 'Failed to initialize payment. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Paystack payment error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    public function paystackCallback(Request $request, $booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            $reference = $request->query('reference');
            
            if (!$reference) {
                return redirect()->route('hotel.payment.cancel', ['booking_id' => $booking_id])
                    ->with('error', 'Payment reference not found.');
            }

            // Verify the transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['success'] && $verification['status'] === 'success') {
                // Get the original amount from metadata
                $originalAmount = $verification['data']['metadata']['original_amount'] ?? $booking->price;
                $originalCurrency = $verification['data']['metadata']['original_currency'] ?? 'USD';
                
                // Payment successful
                $booking->status = 'Paid';
                $booking->payment_via = 'Paystack';
                $booking->transaction_id = $reference;
                $booking->paid_amount = $originalAmount;
                $booking->paid_currency = $originalCurrency;
                $booking->save();

                // Get hotel details
                $hotel = ModulesData::where('id', $booking->hotel_id)->first();

                // Send confirmation emails
                try {
                    Mail::to(auth()->user()->email)->send(new HotelBookingMail($booking, $hotel));
                    Mail::to(config('mail.from.address'))->send(new HotelBookingMail($booking, $hotel, true));
                } catch (\Exception $e) {
                    \Log::error('Failed to send Paystack confirmation emails: ' . $e->getMessage());
                }

                return view('hotel-payment-success', compact('booking', 'hotel'));
            } else {
                // Payment failed
                $booking->status = 'Failed';
                $booking->save();

                return redirect()->route('hotel.payment.cancel', ['booking_id' => $booking_id])
                    ->with('error', 'Payment verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            \Log::error('Paystack callback error: ' . $e->getMessage());
            return redirect()->route('hotel.payment.cancel', ['booking_id' => $booking_id])
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    public function paymentSuccess($booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Generate transaction ID
            $transactionId = 'HTL-' . strtoupper(uniqid()) . '-' . date('Ymd');

            // Update booking status, payment method and transaction ID
            $booking->status = 'Paid';
            $booking->payment_via = $booking->payment_id ? 'PayPal' : 'Stripe';
            $booking->transaction_id = $transactionId;
            $booking->save();

            // Get hotel details
            $hotel = ModulesData::where('id', $booking->hotel_id)->first();

            // Send confirmation email to user
            try {
                Mail::to(auth()->user()->email)->send(new HotelBookingMail($booking, $hotel));
            } catch (\Exception $e) {
                \Log::error('Error sending hotel booking confirmation email: ' . $e->getMessage());
            }

            // Send notification email to admin
            try {
                Mail::to(config('mail.from.address'))->send(new HotelBookingMail($booking, $hotel, true));
            } catch (\Exception $e) {
                \Log::error('Error sending hotel booking notification email to admin: ' . $e->getMessage());
            }

            return view('hotel-payment-success', compact('booking', 'hotel'));
        } catch (\Exception $e) {
            \Log::error('Hotel payment success error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing your payment. Please contact support.');
        }
    }

    public function paymentCancel($booking_id)
    {
        try {
            $booking = Hotels::findOrFail($booking_id);
            
            // Check if the booking belongs to the authenticated user
            if ($booking->user_id !== auth()->id()) {
                abort(403);
            }

            // Update booking status
            $booking->status = 'Cancelled';
            $booking->save();

            return view('hotel-payment-cancel', compact('booking'));
        } catch (\Exception $e) {
            \Log::error('Hotel payment cancel error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while cancelling your payment. Please contact support.');
        }
    }

    public function paypalWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $headers = getallheaders();
            $signature = $headers['Paypal-Transmission-Sig'] ?? '';
            $timestamp = $headers['Paypal-Transmission-Time'] ?? '';
            $webhookId = config('services.paypal.webhook_id');

            // Verify webhook signature
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('services.paypal.mode') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/notifications/verify-webhook-signature' : 'https://api-m.paypal.com/v1/notifications/verify-webhook-signature');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'auth_algo' => $headers['Paypal-Auth-Algo'] ?? '',
                'cert_url' => $headers['Paypal-Cert-Url'] ?? '',
                'transmission_id' => $headers['Paypal-Transmission-Id'] ?? '',
                'transmission_sig' => $signature,
                'transmission_time' => $timestamp,
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($payload),
            ]));
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . $this->getPayPalAccessToken();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
            $verification = json_decode($result);

            if ($verification->verification_status !== 'SUCCESS') {
                throw new \Exception('Invalid webhook signature');
            }

            $event = json_decode($payload);
            if ($event->event_type === 'PAYMENT.CAPTURE.COMPLETED') {
                $bookingId = $event->resource->custom_id;
                $booking = Hotels::findOrFail($bookingId);
                $booking->status = 'Paid';
                $booking->save();

                // Get hotel details
                $hotel = ModulesData::where('id', $booking->hotel_id)->first();

                // Send confirmation email to user
                try {
                    Mail::to($booking->user->email)->send(new HotelBookingMail($booking, $hotel));
                } catch (\Exception $e) {
                    \Log::error('Error sending hotel booking confirmation email: ' . $e->getMessage());
                }

                // Send notification email to admin
                try {
                    Mail::to(config('mail.from.address'))->send(new HotelBookingMail($booking, $hotel, true));
                } catch (\Exception $e) {
                    \Log::error('Error sending hotel booking notification email to admin: ' . $e->getMessage());
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('PayPal webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getPayPalAccessToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.paypal.mode') === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, config('services.paypal.client_id') . ":" . config('services.paypal.client_secret'));
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result)->access_token;
    }
}

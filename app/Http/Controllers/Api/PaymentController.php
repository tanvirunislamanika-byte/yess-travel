<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\FlightBooking;
use App\Models\Hotels;
use App\Services\PaystackService;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    protected $paystackService;
    
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Get payment credentials from admin panel
     */
    public function getCredentials()
    {
        try {
            // Get payment credentials from admin widgets
            $stripeSettings = widget(28);
            $paypalSettings = widget(27);
            $paystackSettings = widget(32);

            $credentials = [
                'stripe' => [
                    'public_key' => $stripeSettings->extra_field_1 ?? null,
                    'mode' => 'live', // or 'test' based on your configuration
                ],
                'paypal' => [
                    'client_id' => $paypalSettings->extra_field_1 ?? null,
                    'mode' => $paypalSettings->extra_field_3 ?? 'sandbox',
                ],
                'paystack' => [
                    'public_key' => $paystackSettings->extra_field_1 ?? null,
                    'mode' => 'live', // or 'test' based on your configuration
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $credentials
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting payment credentials: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment credentials'
            ], 500);
        }
    }

    /**
     * Create Stripe payment intent
     */
    public function createStripePaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer',
                'booking_type' => 'required|in:flight,hotel',
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'required|string|size:3'
            ]);

            $bookingId = $request->booking_id;
            $bookingType = $request->booking_type;
            $amount = $request->amount;
            $currency = strtolower($request->currency);

            // Verify booking exists and belongs to user
            if ($bookingType === 'flight') {
                $booking = FlightBooking::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            } else {
                $booking = Hotels::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            }

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or unauthorized'
                ], 404);
            }

            // Get Stripe credentials from admin
            $stripeSettings = widget(28);
            $stripeSecret = $stripeSettings->extra_field_2;

            if (!$stripeSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe configuration not found'
                ], 500);
            }

            // Initialize Stripe
            Stripe::setApiKey($stripeSecret);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($amount * 100), // Convert to cents
                'currency' => $currency,
                'metadata' => [
                    'booking_id' => $bookingId,
                    'booking_type' => $bookingType,
                    'user_id' => auth()->id(),
                ],
            ]);

            // Update booking with payment information
            $booking->payment_method = 'stripe';
            $booking->transaction_id = $paymentIntent->id;
            $booking->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'currency' => $currency
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Stripe payment intent error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent'
            ], 500);
        }
    }

    /**
     * Create PayPal order
     */
    public function createPayPalOrder(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer',
                'booking_type' => 'required|in:flight,hotel',
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'required|string|size:3'
            ]);

            $bookingId = $request->booking_id;
            $bookingType = $request->booking_type;
            $amount = $request->amount;
            $currency = strtoupper($request->currency);

            // Verify booking exists and belongs to user
            if ($bookingType === 'flight') {
                $booking = FlightBooking::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            } else {
                $booking = Hotels::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            }

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or unauthorized'
                ], 404);
            }

            // Get PayPal credentials from admin
            $paypalSettings = widget(27);
            $clientId = $paypalSettings->extra_field_1;
            $clientSecret = $paypalSettings->extra_field_2;
            $mode = $paypalSettings->extra_field_3 ?? 'sandbox';

            if (!$clientId || !$clientSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'PayPal configuration not found'
                ], 500);
            }

            // Get PayPal access token
            $auth = base64_encode("$clientId:$clientSecret");
            $tokenUrl = $mode === 'sandbox' 
                ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token'
                : 'https://api-m.paypal.com/v1/oauth2/token';

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Basic $auth",
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($tokenUrl, [
                'grant_type' => 'client_credentials',
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate with PayPal'
                ], 500);
            }

            $accessToken = $response->json()['access_token'];

            // Create PayPal order
            $orderUrl = $mode === 'sandbox'
                ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders'
                : 'https://api-m.paypal.com/v2/checkout/orders';

            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                        'description' => ucfirst($bookingType) . ' Booking #' . $bookingId,
                        'custom_id' => $bookingId,
                    ],
                ],
            ];

            $orderResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->post($orderUrl, $orderData);

            if (!$orderResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create PayPal order'
                ], 500);
            }

            $order = $orderResponse->json();

            // Update booking with PayPal order ID
            $booking->payment_method = 'paypal';
            $booking->transaction_id = $order['id'];
            $booking->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order['id'],
                    'approval_url' => $order['links'][1]['href'] ?? null,
                    'amount' => $amount,
                    'currency' => $currency
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('PayPal order creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create PayPal order'
            ], 500);
        }
    }

    /**
     * Initialize Paystack transaction
     */
    public function initializePaystackTransaction(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer',
                'booking_type' => 'required|in:flight,hotel',
                'amount' => 'required|numeric|min:100', // Paystack amount in kobo (smallest currency unit)
                'currency' => 'required|string|size:3',
                'email' => 'required|email',
                'reference' => 'nullable|string'
            ]);

            $bookingId = $request->booking_id;
            $bookingType = $request->booking_type;
            $amount = $request->amount;
            $currency = strtoupper($request->currency);
            $email = $request->email;
            $reference = $request->reference ?? 'PAY_' . uniqid();

            // Verify booking exists and belongs to user
            if ($bookingType === 'flight') {
                $booking = FlightBooking::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            } else {
                $booking = Hotels::where('id', $bookingId)
                    ->where('user_id', auth()->id())
                    ->first();
            }

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or unauthorized'
                ], 404);
            }

            // Initialize Paystack transaction
            $transactionData = [
                'email' => $email,
                'amount' => $amount,
                'currency' => $currency,
                'reference' => $reference,
                'callback_url' => config('app.url') . '/api/v1/payments/paystack/verify/' . $reference,
                'metadata' => [
                    'booking_id' => $bookingId,
                    'booking_type' => $bookingType,
                    'user_id' => auth()->id(),
                    'original_amount' => $amount,
                    'original_currency' => $currency,
                ],
            ];

            $result = $this->paystackService->initializeTransaction($transactionData);

            if ($result['success']) {
                // Update booking with Paystack reference
                $booking->payment_method = 'paystack';
                $booking->transaction_id = $reference;
                $booking->save();

                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to initialize Paystack transaction'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Paystack transaction initialization error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize Paystack transaction'
            ], 500);
        }
    }

    /**
     * Verify Paystack transaction
     */
    public function verifyPaystackTransaction($reference)
    {
        try {
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['success'] && $verification['status'] === 'success') {
                $metadata = $verification['data']['metadata'];
                $bookingId = $metadata['booking_id'] ?? null;
                $bookingType = $metadata['booking_type'] ?? null;

                if ($bookingId && $bookingType) {
                    // Update booking status
                    if ($bookingType === 'flight') {
                        $booking = FlightBooking::find($bookingId);
                        if ($booking) {
                            $booking->payment_status = 'paid';
                            $booking->booking_status = 'confirmed';
                            $booking->paid_amount = $metadata['original_amount'] ?? $booking->total_amount;
                            $booking->paid_currency = $metadata['original_currency'] ?? 'NGN';
                            $booking->save();
                        }
                    } else {
                        $booking = Hotels::find($bookingId);
                        if ($booking) {
                            $booking->status = 'Paid';
                            $booking->payment_via = 'Paystack';
                            $booking->save();
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'data' => $verification['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment verification failed'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Paystack transaction verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify Paystack transaction'
            ], 500);
        }
    }
} 
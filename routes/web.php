<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\WebPasswordResetController;
use App\Http\Controllers\FilerController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\FlightsController;
use App\Http\Controllers\ToursController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\ContactusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ModulesDataController;
use App\Http\Controllers\DuffelController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\FlightBookingController;
use App\Livewire\Report;
use App\Models\Hotels;
use App\Models\Tours;
use App\Models\Flights;
use App\Models\FlightBooking;
use Livewire\Controllers\HttpConnectionHandler;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Subscription;
use Stripe\PaymentMethod;
use Stripe\Error\Card;

use Stripe\PaymentIntent;
use App\Http\Controllers\Auth\VerificationController;

use App\Mail\HotelBookingMail;
use App\Mail\FlightBookingMail;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('services',[ServicesController::class, 'index'])->name('services.list');
Route::get('service/{slug}',[ServicesController::class, 'detail'])->name('services.detail');

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
});

Route::get('/link-storage', function () { 
    //dd(public_path());    
   $target = '/storage/app/public';
   $shortcut = '/public/storage';
   symlink($target, $shortcut);
});



use App\Http\Controllers\ReviewController;

Route::get('/hotels/{hotel}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/my-reviews', [ReviewController::class, 'reviews'])->name('reviews.my');
Route::post('/hotels/{hotel}/review', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');
Route::post('/hotels/review/reply', [ReviewController::class, 'store_reply'])->middleware('auth')->name('reviews.store_reply');


Route::get('/invoice/{booking_id}', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate');


Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('clear-compiled');
    Artisan::call('optimize:clear');

    return 'All caches cleared.';
});

use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/travelin/livewire/update', $handle);
});


Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'show'])->name('password.show');
Route::post('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'update'])->name('password.update');

Route::get('/admin/login', function () {
    return view('auth.admin');
})->name('admin.auth.login');

Route::get('/report', function () {
    return view('report');
});

Route::get('/flight-booking', function () {
    return view('flight-booking');
})->name('flight-booking');

Route::get('/hotel-booking', function () {
    return view('hotel-booking');
})->name('hotel-booking');

Route::get('/payment-history', function () {
    return view('history-booking');
})->name('payment-history');

Route::get('/payment-methods', function () {
    return view('payment-methods');
})->name('payment-methods');


Route::get('fetch-hotels',[DuffelController::class, 'getHotels'])->name('getHotels');
Route::get('fetch-flights',[DuffelController::class, 'getFlights'])->name('getFlights');
Route::get('get-offer-requests',[DuffelController::class, 'getOfferRequests'])->name('get-offer-requests');

Route::post('login', [LoginController::class,'login']);
Route::post('web/forgot-password', [WebPasswordResetController::class, 'sendResetLinkEmail'])->name('web.password.email');


//Route::get('report', Report::class)->name('report');

Route::post('ajax_upload_file',[FilerController::class, 'upload'])->name('filer.image-upload');
Route::post('ajax_remove_file',[FilerController::class, 'fileDestroy'])->name('filer.image-remove');

Route::get('blog',[BlogsController::class, 'index'])->name('blogs.list');
Route::get('blog/{slug}',[BlogsController::class, 'detail'])->name('blogs.detail');

Route::get('/flights', [App\Http\Controllers\DuffelController::class, 'getOfferRequests'])->name('flights.list');

Route::get('search-flights',[FlightsController::class, 'index'])->name('flights.search');
Route::get('flight/{slug}',[FlightsController::class, 'detail'])->name('flights.detail');
Route::get('hotels',[HotelsController::class, 'index'])->name('hotels.list');
Route::get('hotel/{slug}',[HotelsController::class, 'detail'])->name('hotel.detail');

Route::get('tours',[ToursController::class, 'index'])->name('tours.list');
Route::get('tour/bookings',[ToursController::class, 'userBookings'])->middleware('auth')->name('tour.bookings');
Route::get('tour/booking/{id}/success',[ToursController::class, 'showBookingSuccess'])->name('tour.booking.success');
Route::get('tour/{slug}',[ToursController::class, 'detail'])->name('tour.detail');
Route::get('tour/{id}/booking',[ToursController::class, 'showBookingForm'])->middleware('auth')->name('tour.booking.form');
Route::post('tour/{id}/booking',[ToursController::class, 'storeBooking'])->middleware('auth')->name('tour.booking.store');
Route::get('tour/{id}/booking/payment-selection',[ToursController::class, 'showPaymentSelection'])->middleware('auth')->name('tour.booking.payment-selection');
Route::post('tour/payment/stripe/{booking_id}',[ToursController::class, 'processStripePayment'])->middleware('auth')->name('tour.payment.stripe');
Route::post('tour/payment/paypal/{booking_id}',[ToursController::class, 'processPayPalPayment'])->middleware('auth')->name('tour.payment.paypal');
Route::get('tour/payment/stripe/{booking_id}/success',[ToursController::class, 'handleStripeSuccess'])->middleware('auth')->name('tour.payment.stripe.success');
Route::get('tour/payment/paypal/{booking_id}/success',[ToursController::class, 'handlePayPalSuccess'])->middleware('auth')->name('tour.payment.paypal.success');

// AJAX routes for tours
Route::get('tours/ajax/states-by-country', [ToursController::class, 'getStatesByCountry'])->name('tours.states-by-country');
Route::get('tours/ajax/cities-by-state', [ToursController::class, 'getCitiesByState'])->name('tours.cities-by-state');
Route::get('tours/ajax/cities-by-country', [ToursController::class, 'getCitiesByCountry'])->name('tours.cities-by-country');

// API routes for tours
Route::get('api/tours/search', [ToursController::class, 'search'])->name('api.tours.search');
Route::get('api/tours/tour-types', [ToursController::class, 'getTourTypes'])->name('api.tours.tour-types');
Route::get('api/tours/transport-types', [ToursController::class, 'getTransportTypes'])->name('api.tours.transport-types');


Route::get('contact-us',[ContactusController::class, 'index'])->name('contact.index');
Route::post('contact-us',[ContactusController::class, 'store'])->name('contact.post');


Route::get('/', function () {
    return view('welcome');
});

Route::post('/hotel-booking', function (Request $request) {

    $hotel = new Hotels();
    $hotel->hotel_id = $request->hotel_id;
    $hotel->user_id = auth()->user()->id;
    $hotel->travelling_from = $request->travelling_from;
    $hotel->check_in = $request->check_in;
    $hotel->check_out = $request->check_out;
    $hotel->adults = $request->adults;
    $hotel->childrens = $request->childrens;
    $hotel->rooms = $request->rooms;
    $hotel->price = $request->price;
    $hotel->status = 'Pending';
    $hotel->save();

    // Payment Redirection
    if ($request->payment_method == 'stripe') {
        return view('stripe-hotel', compact('hotel'));
    } else {
        return redirect()->route('hotel-paypal', ['id' => $hotel->id]);
    }
})->name('hotel-booking');


Route::get('/hotel-paypal/{id}', function ($id) {
    $hotel = Hotels::findOrFail($id);
    $clientId = widget(27)->extra_field_1;
    $secret = widget(27)->extra_field_2;

    // Step 1: Get Access Token
    $auth = base64_encode("$clientId:$secret");

    $response = Http::withHeaders([
        'Authorization' => "Basic $auth",
        'Content-Type' => 'application/x-www-form-urlencoded'
    ])->asForm()->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
        'grant_type' => 'client_credentials',
    ]);

    if (!$response->successful()) {
        return response()->json(['error' => 'Failed to authenticate with PayPal'], 500);
    }

    $accessToken = $response->json()['access_token'];

    // Step 2: Create PayPal Order
    $paypalOrder = Http::withToken($accessToken)
        ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                        'landing_page' => 'LOGIN',
                        'shipping_preference' => 'GET_FROM_FILE',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('paypal-success', ['id' => $hotel->id]),
                        'cancel_url' => route('paypal-cancel', ['id' => $hotel->id]),
                    ]
                ]
            ],
            'purchase_units' => [
                [
                    'invoice_id' => 'INV-' . $hotel->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $hotel->price,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => $hotel->price
                            ]
                        ]
                    ]
                ]
            ]
        ]);

    if (!$paypalOrder->successful()) {
        return response()->json(['error' => 'Failed to create PayPal order', 'details' => $paypalOrder->json()], 500);
    }

    $paypalOrderData = $paypalOrder->json();

    return redirect($paypalOrderData['links'][1]['href'] ?? '/');
})->name('hotel-paypal');


Route::get('/flight-paypal/{id}', function ($id) {
    $hotel = Flights::findOrFail($id);
    $clientId = widget(27)->extra_field_1;
    $secret = widget(27)->extra_field_2;

    // Step 1: Get Access Token
    $auth = base64_encode("$clientId:$secret");

    $response = Http::withHeaders([
        'Authorization' => "Basic $auth",
        'Content-Type' => 'application/x-www-form-urlencoded'
    ])->asForm()->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
        'grant_type' => 'client_credentials',
    ]);

    if (!$response->successful()) {
        return response()->json(['error' => 'Failed to authenticate with PayPal'], 500);
    }

    $accessToken = $response->json()['access_token'];

    // Step 2: Create PayPal Order
    $paypalOrder = Http::withToken($accessToken)
        ->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                        'landing_page' => 'LOGIN',
                        'shipping_preference' => 'GET_FROM_FILE',
                        'user_action' => 'PAY_NOW',
                        'return_url' => route('flight-paypal-success', ['id' => $hotel->id]),
                        'cancel_url' => route('paypal-cancel', ['id' => $hotel->id]),
                    ]
                ]
            ],
            'purchase_units' => [
                [
                    'invoice_id' => 'INV-' . $hotel->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $hotel->price,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => $hotel->price
                            ]
                        ]
                    ]
                ]
            ]
        ]);

    if (!$paypalOrder->successful()) {
        return response()->json(['error' => 'Failed to create PayPal order', 'details' => $paypalOrder->json()], 500);
    }

    $paypalOrderData = $paypalOrder->json();

    return redirect($paypalOrderData['links'][1]['href'] ?? '/');
})->name('flight-paypal');


Route::get('/paypal-success/{id}', function (Request $request, $id) {
    $hotel = Hotels::findOrFail($id);
    $clientId = widget(27)->extra_field_1;
    $secret = widget(27)->extra_field_2;

    // Encode client_id and secret
    $auth = base64_encode("$clientId:$secret");

    // Step 1: Get PayPal Access Token using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $auth",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    $accessToken = $responseData['access_token'] ?? null;

    if (!$accessToken) {
        return view('declined', ['error' => 'Error fetching PayPal access token.']);
    }

    // Step 2: Get Order ID from request
    $orderId = $request->query('token');

    if (!$orderId) {
        return view('declined', ['error' => 'Missing PayPal order token.']);
    }

    // Step 3: Capture Payment using cURL
    $paypalRequestId = uniqid(); // Unique ID for idempotency

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}/capture");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "PayPal-Request-Id: $paypalRequestId"
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}"); // Empty JSON payload
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $captureResponse = curl_exec($ch);
    curl_close($ch);

    $captureData = json_decode($captureResponse, true);

    // Debug response if something goes wrong
    if (!isset($captureData['status'])) {
        return view('declined', ['error' => 'Invalid PayPal capture response.']);
    }

    // Step 4: Process Successful Payment
    if ($captureData['status'] == 'COMPLETED') {
        $hotel->transaction_id = $captureData['id'];
        $hotel->status = 'Paid';
        $hotel->payment_via = 'PayPal';
        $hotel->save();

        // Send Confirmation Email
        Mail::to(widget(1)->extra_field_2)->send(new HotelBookingMail($hotel, auth()->user()));

        return view('confirmed');
    } else {
        return view('declined', ['error' => 'Payment failed.']);
    }
})->name('paypal-success');


Route::get('/flight-paypal-success/{id}', function (Request $request, $id) {
    $booking = FlightBooking::findOrFail($id);
    $clientId = widget(27)->extra_field_1;
    $secret = widget(27)->extra_field_2;

    // Encode client_id and secret
    $auth = base64_encode("$clientId:$secret");

    // Step 1: Get PayPal Access Token using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $auth",
        "Content-Type: application/x-www-form-urlencoded"
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    $accessToken = $responseData['access_token'] ?? null;

    if (!$accessToken) {
        return redirect()->route('flights.booking.details', ['booking_id' => $id])
                        ->with('error', 'Error fetching PayPal access token.');
    }

    // Step 2: Get Order ID from request
    $orderId = $request->query('token');

    if (!$orderId) {
        return redirect()->route('flights.booking.details', ['booking_id' => $id])
                        ->with('error', 'Missing PayPal order token.');
    }

    // Step 3: Capture Payment using cURL
    $paypalRequestId = uniqid(); // Unique ID for idempotency

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}/capture");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $accessToken",
        "PayPal-Request-Id: $paypalRequestId"
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}"); // Empty JSON payload
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $captureResponse = curl_exec($ch);
    curl_close($ch);

    $captureData = json_decode($captureResponse, true);

    // Debug response if something goes wrong
    if (!isset($captureData['status'])) {
        return redirect()->route('flights.booking.details', ['booking_id' => $id])
                        ->with('error', 'Invalid PayPal capture response.');
    }

    // Step 4: Process Successful Payment
    if ($captureData['status'] == 'COMPLETED') {
        $booking->transaction_id = $captureData['id'];
        $booking->booking_status = 'confirmed';
        $booking->payment_status = 'paid';
        $booking->payment_method = 'PayPal';
        $booking->save();

        // Send confirmation emails to both user and admin
        try {
            // Send to user
            Mail::to($booking->user->email)->send(new FlightBookingMail($booking, $booking->user));
            
            // Send to admin
            $adminEmail = widget(1)->extra_field_2;
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new FlightBookingMail($booking, $booking->user));
            }
            
            \Log::info('PayPal confirmation emails sent successfully', [
                'booking_id' => $id,
                'user_email' => $booking->user->email,
                'admin_email' => $adminEmail
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send PayPal confirmation emails: ' . $e->getMessage());
            // Continue with the flow even if email fails
        }

        return redirect()->route('flights.booking.details', ['booking_id' => $id])
                        ->with('success', 'Payment successful! Your booking has been confirmed.');
    } else {
        return redirect()->route('flights.booking.details', ['booking_id' => $id])
                        ->with('error', 'Payment failed.');
    }
})->name('flight-paypal-success');


Route::get('/paypal-cancel/{id}', function ($id) {
    return view('declined', ['error' => 'Payment was canceled.']);
})->name('paypal-cancel');


Route::post('/flight-booking', function (Request $request) {

    $flight = new Flights();
    $flight->flight_id = $request->flight_id;
    $flight->user_id = auth()->user()->id;
    $flight->one_way_or_two_way = $request->one_way_or_two_way;
    $flight->travelling_on = $request->travelling_on;
    $flight->return = $request->return;
    $flight->flight_from = $request->flight_from;
    $flight->flight_to = $request->flight_to;
    $flight->adults = $request->adults;
    $flight->childrens = $request->childrens;
    $flight->price = $request->price;
    $flight->save();

    if ($request->payment_method == 'stripe') {
        return view('stripe-flight', compact('flight'));
    } else {
        return redirect()->route('flight-paypal', ['id' => $flight->id]);
    }

    
    //return view('stripe-flight',compact('flight'));
})->name('flight-booking');

Route::get('/hotel-payment/{id}', function (Request $request, $id) {
        $skey = widget(28)->extra_field_2;
        Stripe::setApiKey($skey);

        $hotel = Hotels::where('id', $id)->first();
        $hotelM = App\Models\ModulesData::where('id', $hotel->hotel_id)->first();

        $paymentMethodId = $request->token;
        $returnUrl = route('payment-success');
        try {
            $charge = Charge::create(array(
                        "amount" => (int) filter_var($hotel->price, FILTER_SANITIZE_NUMBER_INT) * 100,
                        "currency" => "USD",
                        "source" => $paymentMethodId, // obtained with Stripe.js
                        "description" => $hotelM->title
            ));
            if ($charge['status'] == 'succeeded') {

                $hotel->transaction_id = $charge['id'];
                $hotel->status = 'Paid';
                $hotel->payment_via = 'Credit Card';
                $hotel->update();

                Mail::to(widget(1)->extra_field_2)->send(new HotelBookingMail($hotel, auth()->user()));
                
                return view('confirmed');
            } else {
                $error = __('subscription failed');
                return view('declined', compact('error'));
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            return view('declined', compact('error'));
        }
  

})->name('hotel-payment');

Route::get('/payment-success', function (Request $request) {
        dd($request);
})->name('payment-success');

// Test route for Paystack configuration
Route::get('/test-paystack-config', function () {
    $config = [
        'public_key' => config('services.paystack.public_key'),
        'secret_key' => config('services.paystack.secret_key'),
        'merchant_email' => config('services.paystack.merchant_email'),
        'url' => config('services.paystack.url'),
    ];
    
    $widget32 = widget(32);
    $widgetData = $widget32 ? [
        'title' => $widget32->title,
        'extra_field_1' => $widget32->extra_field_1,
        'extra_field_2' => $widget32->extra_field_2,
        'extra_field_3' => $widget32->extra_field_3,
    ] : null;
    
    return response()->json([
        'config' => $config,
        'widget_32' => $widgetData,
    ]);
})->name('test.paystack.config');

// Test route for PaystackService
Route::get('/test-paystack-service', function () {
    try {
        $paystackService = app(\App\Services\PaystackService::class);
        
        $testData = [
            'email' => 'test@example.com',
            'amount' => 1000, // 10 NGN
            'currency' => 'NGN',
            'reference' => 'TEST_' . uniqid(),
            'callback_url' => 'https://example.com/callback',
            'metadata' => ['test' => true],
        ];
        
        $result = $paystackService->initializeTransaction($testData);
        
        return response()->json([
            'test_data' => $testData,
            'result' => $result,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
})->name('test.paystack.service');

// Test route for Paystack currency
Route::get('/test-paystack-currency', function () {
    try {
        $paystackService = app(\App\Services\PaystackService::class);
        
        // Test with USD to NGN conversion
        $originalAmount = 10; // 10 USD
        $ngnAmount = $originalAmount * 1500; // Convert to NGN
        
        $testData = [
            'email' => 'test@example.com',
            'amount' => $ngnAmount, // 15000 NGN (10 USD * 1500)
            'currency' => 'NGN',
            'reference' => 'TEST_CURRENCY_' . uniqid(),
            'callback_url' => 'https://example.com/callback',
            'metadata' => [
                'test' => true,
                'original_currency' => 'USD',
                'original_amount' => $originalAmount,
                'conversion_rate' => 1500,
            ],
        ];
        
        $result = $paystackService->initializeTransaction($testData);
        
        return response()->json([
            'test_data' => $testData,
            'result' => $result,
            'note' => 'Testing NGN currency support',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
})->name('test.paystack.currency');

// Test route for currency conversion
Route::get('/test-currency-conversion', function () {
    $testCases = [
        ['amount' => 100, 'from' => 'USD', 'to' => 'NGN'],
        ['amount' => 50, 'from' => 'EUR', 'to' => 'NGN'],
        ['amount' => 25, 'from' => 'GBP', 'to' => 'NGN'],
        ['amount' => 1000, 'from' => 'NGN', 'to' => 'NGN'],
    ];
    
    $results = [];
    foreach ($testCases as $test) {
        $converted = \App\Helpers\CurrencyHelper::convert($test['amount'], $test['from'], $test['to']);
        $formatted = \App\Helpers\CurrencyHelper::formatAmount($converted, $test['to']);
        
        $results[] = [
            'original' => \App\Helpers\CurrencyHelper::formatAmount($test['amount'], $test['from']),
            'converted' => $formatted,
            'rate' => \App\Helpers\CurrencyHelper::getConversionRate($test['from'], $test['to']),
        ];
    }
    
    return response()->json([
        'test_cases' => $testCases,
        'results' => $results,
        'supported_currencies' => \App\Helpers\CurrencyHelper::getSupportedCurrencies(),
    ]);
})->name('test.currency.conversion');

Route::get('/flight-payment/{id}', function (Request $request,$id) {

    $skey = 'sk_test_Jc5YJMkPz81EuYgEy2eGPMdp';
    Stripe::setApiKey($skey);

    $hotel = Flights::where('id',$id)->first();
    $hotelM = App\Models\ModulesData::where('id', $hotel->flight_id)->first();

    $paymentMethodId = $request->token;
    $returnUrl = route('payment-success');
    try {
        $charge = Charge::create(array(
                    "amount" => (int) filter_var($hotel->price, FILTER_SANITIZE_NUMBER_INT) * 100,
                    "currency" => "USD",
                    "source" => $paymentMethodId, // obtained with Stripe.js
                    "description" => $hotelM->title
        ));
        if ($charge['status'] == 'succeeded') {

            $hotel->transaction_id = $charge['id'];
            $hotel->status = 'Paid';
            $hotel->payment_via = 'Credit Card';
            $hotel->update();

            // Send email to admin
            Mail::to(widget(1)->extra_field_2)->send(new FlightBookingMail($hotel, auth()->user()));
            
            // Send email to customer
            Mail::to(auth()->user()->email)->send(new FlightBookingMail($hotel, auth()->user()));
            
            return view('confirmed');
        } else {
            $error = __('subscription failed');
            return view('declined', compact('error'));
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        return view('declined', compact('error'));
    }
})->name('flight-payment');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/analysis/{slug}', function ($slug) {
        $analysis = App\Models\ModulesData::where('slug',$slug)->first();
        return view('video_detail',compact('analysis'));
    })->name('video_detail');
});



Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

Route::post('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->name('verification.verify');

Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])
    ->name('verification.resend');


Route::get('/{slug}',[CmsController::class, 'index'])->name('cms.page');

Route::get('/{module}/delete/{id}', [ModulesDataController::class,'destroy'])->name('modules.data.delete');
Route::get('/delete-file/{id}/{field}', [ModulesDataController::class,'destroyFile'])->name('modules.data.delete.file');
Route::get('/download-files/{id}/{module}', [ModulesDataController::class,'downloadFiles'])->name('modules.data.download.files');
Route::get('/data-status/{module}/{status}', [ModulesDataController::class,'update_status']);
Route::get('/module-data', [ModulesDataController::class,'fetchModulesData'])->name('modules.data.fetch');
Route::get('/{module}', [ModulesDataController::class,'index'])->name('modules.data');
Route::get('/{module}/add', [ModulesDataController::class,'add'])->name('modules.data.add');
Route::post('/{module}/store', [ModulesDataController::class,'store'])->name('modules.data.store');
Route::post('/{module}/update', [ModulesDataController::class,'update'])->name('modules.data.update');
Route::get('/{module}/edit/{id}', [ModulesDataController::class,'edit'])->name('modules.data.edit');
Route::get('/{module}/preview/{id}', [ModulesDataController::class,'preview'])->name('modules.data.preview');
Route::get('/filter-parties/{id}', [ModulesDataController::class,'filterParties'])->name('filter-parties');



Route::get('/{module}', [ModulesDataController::class,'index'])->name('modules.data');

// Flight Search Routes
// Flight Search Routes

Route::get('/flights/search', [App\Http\Controllers\DuffelController::class, 'getOfferRequests'])->name('flights.search');

Route::get('/flights/search/airports', [DuffelController::class, 'searchAirports'])->name('flights.search.airports');

Route::post('/flights/book', [FlightsController::class, 'book'])->name('flights.book');

Route::get('/flights/booking/{offer_id}', [FlightsController::class, 'showBookingForm'])->name('flights.booking.form');
Route::get('/flights/booking-details/{booking_id}', [FlightsController::class, 'showBookingDetails'])->name('flights.booking.details');
Route::get('/flights/booking/success/{booking_id?}', [FlightsController::class, 'bookingSuccess'])->name('flights.booking.success');

// Flight booking routes
Route::middleware(['auth'])->group(function () {
    Route::post('/flights/booking/{offer_id}', [FlightsController::class, 'storeBooking'])->name('flights.booking');
    
    // Payment routes
    Route::get('/flights/payment/{booking_id}', [FlightsController::class, 'showPayment'])->name('flights.payment');
    Route::post('/flights/payment/stripe/{booking_id}', [FlightsController::class, 'processStripePayment'])->name('flights.payment.stripe');
    Route::post('/flights/payment/paypal/{booking_id}', [FlightsController::class, 'processPayPalPayment'])->name('flights.payment.paypal');
    Route::post('/flights/payment/paystack/{booking_id}', [FlightsController::class, 'processPaystackPayment'])->name('flights.payment.paystack');
    Route::get('/flights/payment/paystack/callback/{booking_id}', [FlightsController::class, 'paystackCallback'])->name('flights.payment.paystack.callback');
    Route::get('/flights/payment/success/{booking_id}', [FlightsController::class, 'paymentSuccess'])->name('flights.payment.success');
    Route::get('/flights/payment/cancel/{booking_id}', [FlightsController::class, 'paymentCancel'])->name('flights.payment.cancel');
    
    // PayPal webhook
    Route::post('/flights/payment/paypal/webhook', [FlightsController::class, 'paypalWebhook'])->name('flights.payment.paypal.webhook');
});

Route::get('/flights/booking/{id}/pdf', [FlightBookingController::class, 'downloadPdf'])->name('flights.booking.pdf');

// Hotel booking routes
Route::middleware(['auth'])->group(function () {
    // Hotel Booking Routes
    Route::get('/hotel/booking/{hotel_id}', [HotelsController::class, 'showBookingForm'])->name('hotel.booking.show');
    Route::post('/hotel/booking/{hotel_id}', [HotelsController::class, 'storeBooking'])->name('hotel.booking.store');
    Route::get('/hotel/payment/{booking_id}', [HotelsController::class, 'showPayment'])->name('hotel.payment.show');
    Route::post('/hotel/payment/stripe/{booking_id}', [HotelsController::class, 'processStripePayment'])->name('hotel.payment.stripe');
    Route::post('/hotel/payment/paypal/{booking_id}', [HotelsController::class, 'processPayPalPayment'])->name('hotel.payment.paypal');
    Route::post('/hotel/payment/paystack/{booking_id}', [HotelsController::class, 'processPaystackPayment'])->name('hotel.payment.paystack');
    Route::get('/hotel/payment/paystack/callback/{booking_id}', [HotelsController::class, 'paystackCallback'])->name('hotel.payment.paystack.callback');
    Route::get('/hotel/payment/success/{booking_id}', [HotelsController::class, 'paymentSuccess'])->name('hotel.payment.success');
    Route::get('/hotel/payment/cancel/{booking_id}', [HotelsController::class, 'paymentCancel'])->name('hotel.payment.cancel');
    Route::post('/hotel/payment/paypal/webhook', [HotelsController::class, 'paypalWebhook'])->name('hotel.payment.paypal.webhook');
});

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/user/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user/profile/photo', [App\Http\Controllers\ProfileController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
    Route::get('/profile/states', [App\Http\Controllers\ProfileController::class, 'getStates'])->name('profile.states');
    Route::get('/profile/cities', [App\Http\Controllers\ProfileController::class, 'getCities'])->name('profile.cities');
});

// Language switching route
Route::get('language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');



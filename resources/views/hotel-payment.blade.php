<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Select Payment Method</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-credit-card fa-3x mb-3 text-primary"></i>
                                        <h5 class="card-title">Credit/Debit Card</h5>
                                        <p class="card-text">Pay securely with your credit or debit card</p>
                                        <form action="{{ route('hotel.payment.stripe', ['booking_id' => $booking->id]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Pay with Card</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fab fa-paypal fa-3x mb-3 text-primary"></i>
                                        <h5 class="card-title">PayPal</h5>
                                        <p class="card-text">Pay securely with your PayPal account</p>
                                        <form action="{{ route('hotel.payment.paypal', ['booking_id' => $booking->id]) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Pay with PayPal</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-credit-card fa-3x mb-3 text-success"></i>
                                        <h5 class="card-title">Paystack</h5>
                                                                                    <p class="card-text">Pay securely with Paystack (Cards, Bank Transfer, USSD)</p>
                                            <small class="text-info d-block mb-2">Payments processed in Nigerian Naira (NGN)</small>
                                            <small class="text-warning d-block mb-2">
                                                @php
                                                    $originalAmount = $booking->price;
                                                    $originalCurrency = 'USD';
                                                    $ngnAmount = \App\Helpers\CurrencyHelper::convert($originalAmount, $originalCurrency, 'NGN');
                                                @endphp
                                                Original: {{ \App\Helpers\CurrencyHelper::formatAmount($originalAmount, $originalCurrency) }} 
                                                → Paystack: {{ \App\Helpers\CurrencyHelper::formatAmount($ngnAmount, 'NGN') }}
                                            </small>
                                            <form action="{{ route('hotel.payment.paystack', ['booking_id' => $booking->id]) }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Pay with Paystack</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Summary</h4>
                    </div>
                    <div class="card-body">
                        @if($booking->hotel)
                            <h5>{{ $booking->hotel->title }}</h5>
                        @else
                            <h5>Hotel Information Unavailable</h5>
                        @endif
                        <p class="mb-2">
                            <i class="fas fa-calendar"></i> Check-in: {{ date('M d, Y', strtotime($booking->check_in)) }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-calendar"></i> Check-out: {{ date('M d, Y', strtotime($booking->check_out)) }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-users"></i> Guests: {{ $booking->adults }} Adults, {{ $booking->childrens }} Children
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-bed"></i> Rooms: {{ $booking->rooms }}
                        </p>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong>${{ number_format($booking->price, 2) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Hotel Details
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
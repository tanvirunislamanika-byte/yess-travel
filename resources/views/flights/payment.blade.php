<x-app-layout>
    <div class="payment-container">
        <div class="container">
            <!-- Header Section -->
            <div class="payment-header">
                <div class="payment-title">
                    <h1>Select Payment Method</h1>
                    <p>Choose your preferred payment method to complete your booking</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="payment-content">
                <!-- Left Column - Payment Methods -->
                <div class="payment-main">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Payment Methods Section -->
                    <div class="payment-methods-section">
                         <!-- Booking Summary -->
                    <div class="payment-summary-section">
                        <h4>Booking Summary</h4>
                        <div class="payment-summary-grid">
                            <div class="payment-summary-item">
                                <span class="label">From</span>
                                <span class="value">{{ $booking->origin_code }}</span>
                            </div>
                            <div class="payment-summary-item">
                                <span class="label">To</span>
                                <span class="value">{{ $booking->destination_code }}</span>
                            </div>
                            <div class="payment-summary-item">
                                <span class="label">Departure</span>
                                <span class="value">{{ $booking->departure_date->format('M d, Y H:i') }}</span>
                            </div>
                            @if($booking->return_date)
                            <div class="payment-summary-item">
                                <span class="label">Return</span>
                                <span class="value">{{ $booking->return_date->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                            <div class="payment-summary-item">
                                <span class="label">Passengers</span>
                                <span class="value">{{ $booking->adults }} Adult(s), {{ $booking->children }} Child(ren)</span>
                            </div>
                            <div class="payment-summary-item">
                                <span class="label">Airline</span>
                                <span class="value">{{ $booking->airline_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>


                        <h4>Choose Payment Method</h4>
                        <div class="payment-methods-grid">
                            <div class="payment-method-card" onclick="selectPaymentMethod('card')">
                                <div class="payment-method-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-method-title">Credit/Debit Card</div>
                                <div class="payment-method-description">
                                    Pay securely with your credit or debit card using Stripe
                                </div>
                                <form action="{{ route('flights.payment.stripe', $booking->id) }}" method="POST" id="card-form">
                                    @csrf
                                    <button type="submit" class="payment-btn payment-btn-primary">
                                        <i class="fas fa-lock"></i>
                                        Pay with Card
                                    </button>
                                </form>
                            </div>

                            <div class="payment-method-card" onclick="selectPaymentMethod('paypal')">
                                <div class="payment-method-icon">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <div class="payment-method-title">PayPal</div>
                                <div class="payment-method-description">
                                    Pay securely with your PayPal account
                                </div>
                                <form action="{{ route('flights.payment.paypal', $booking->id) }}" method="POST" id="paypal-form">
                                    @csrf
                                    <button type="submit" class="payment-btn payment-btn-primary">
                                        <i class="fab fa-paypal"></i>
                                        Pay with PayPal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Information -->
                    <div class="payment-security-info">
                        <i class="fas fa-shield-alt"></i>
                        <span>Your payment information is encrypted and secure</span>
                    </div>

                    <!-- Back Button -->
                    <div class="payment-actions">
                        <a href="{{ route('flights.booking.form', $booking->offer_id) }}" class="payment-btn payment-btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Booking
                        </a>
                    </div>
                </div>

                <!-- Right Column - Booking Summary -->
                <div class="payment-sidebar">
                   

                    <!-- Price Breakdown -->
                    <div class="payment-price-section">
                        <div class="payment-price-title">Price Breakdown</div>
                        <div class="payment-price-breakdown">
                            <div class="payment-price-item">
                                <span class="label">Base Fare</span>
                                <span class="value">{{ $booking->currency }} {{ number_format($booking->total_amount - $booking->service_charges, 2) }}</span>
                            </div>
                            <div class="payment-price-item">
                                <span class="label">Service Charges</span>
                                <span class="value">{{ $booking->currency }} {{ number_format($booking->service_charges, 2) }}</span>
                            </div>
                            <div class="payment-price-item">
                                <span class="label">Total Amount</span>
                                <span class="value">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>
                        <div class="payment-price-total">
                            <div class="amount">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</div>
                            <div>Total to Pay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function selectPaymentMethod(method) {
            // Remove selected class from all payment method cards
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Submit the corresponding form
            if (method === 'card') {
                document.getElementById('card-form').submit();
            } else if (method === 'paypal') {
                document.getElementById('paypal-form').submit();
            }
        }

        // Add hover effects for payment method cards
        document.addEventListener('DOMContentLoaded', function() {
            const paymentCards = document.querySelectorAll('.payment-method-card');
            
            paymentCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('selected')) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                
                card.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('selected')) {
                        this.style.transform = 'translateY(0)';
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 


<x-app-layout>
    <div class="payment-container">
        <div class="container">
            <!-- Header Section -->
            <div class="payment-header">
                <div class="payment-title">
                    <h1>Complete Tour Payment</h1>
                    <p>Enter your card details to complete your tour booking securely</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="payment-content">
                <!-- Left Column - Payment Form -->
                <div class="payment-main">
                    <!-- Payment Form Section -->
                    <div class="payment-form-section">
                        <h4>Card Details</h4>
                        <form id="payment-form">
                            <div class="payment-form-group">
                                <label for="card-element" class="payment-form-label">Credit or Debit Card</label>
                                <div id="card-element" class="payment-form-control"></div>
                                <div id="card-errors" class="payment-error" role="alert"></div>
                            </div>
                            
                            <div class="payment-actions">
                                <button type="submit" class="payment-btn payment-btn-primary" id="submit-button">
                                    <span class="payment-spinner d-none" role="status" aria-hidden="true"></span>
                                    <i class="fas fa-lock"></i>
                                    Pay {{ \App\Helpers\CurrencyHelper::formatPrice($booking->total_amount) }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Information -->
                    <div class="payment-security-info">
                        <i class="fas fa-shield-alt"></i>
                        <span>Your payment is secured by Stripe's advanced encryption</span>
                    </div>

                    <!-- Back Button -->
                    <div class="payment-actions">
                        <a href="{{ route('tour.booking.payment-selection', $tour->id) }}" class="payment-btn payment-btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Payment Methods
                        </a>
                    </div>
                </div>

                <!-- Right Column - Booking Summary -->
                <div class="payment-sidebar">
                    <!-- Tour Summary -->
                    <div class="payment-booking-summary">
                        <div class="payment-booking-title">Tour Summary</div>
                        <div class="payment-booking-details">
                            <div class="payment-booking-item">
                                <span class="label">Tour Name</span>
                                <span class="value">{{ $tour->title }}</span>
                            </div>
                            <div class="payment-booking-item">
                                <span class="label">Adults</span>
                                <span class="value">{{ $booking->adults }}x</span>
                            </div>
                            @if($booking->children > 0)
                                <div class="payment-booking-item">
                                    <span class="label">Children</span>
                                    <span class="value">{{ $booking->children }}x</span>
                                </div>
                            @endif
                            <div class="payment-booking-item">
                                <span class="label">Departure Date</span>
                                <span class="value">{{ $booking->departure_date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="payment-price-section">
                        <div class="payment-price-title">Price Breakdown</div>
                        <div class="payment-price-breakdown">
                            <div class="payment-price-item">
                                <span class="label">Adults ({{ $booking->adults }}x)</span>
                                <span class="value">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->adult_price * $booking->adults) }}</span>
                            </div>
                            @if($booking->children > 0)
                                <div class="payment-price-item">
                                    <span class="label">Children ({{ $booking->children }}x)</span>
                                    <span class="value">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->children_price * $booking->children) }}</span>
                                </div>
                            @endif
                            <div class="payment-price-item">
                                <span class="label">Total Amount</span>
                                <span class="value">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->total_amount) }}</span>
                            </div>
                        </div>
                        <div class="payment-price-total">
                            <div class="amount">{{ \App\Helpers\CurrencyHelper::formatPrice($booking->total_amount) }}</div>
                            <div>Total to Pay</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Debug information
        console.log('Stripe key:', '{{ config('services.stripe.key') }}');
        console.log('Client secret:', '{{ $clientSecret }}');

        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            }
        });

        card.mount('#card-element');

        // Handle real-time validation errors from the card Element
        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const spinner = document.querySelector('.payment-spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Disable submit button and show spinner
            submitButton.disabled = true;
            spinner.classList.remove('d-none');
            
            try {
                const { paymentIntent, error } = await stripe.confirmCardPayment('{{ $clientSecret }}', {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: '{{ auth()->user()->name }}',
                            email: '{{ auth()->user()->email }}'
                        }
                    }
                });

                if (error) {
                    // Show error to customer
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    
                    // Re-enable submit button and hide spinner
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');
                } else {
                    // Payment succeeded
                    if (paymentIntent.status === 'succeeded') {
                        // Redirect to success handler first to update database
                        window.location.href = '{{ route('tour.payment.stripe.success', $booking->id) }}?payment_intent_id=' + paymentIntent.id;
                    }
                }
            } catch (error) {
                console.error('Payment error:', error);
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = 'An unexpected error occurred. Please try again.';
                
                // Re-enable submit button and hide spinner
                submitButton.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
    @endpush

    @push('css')
    <style>
        .payment-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 40px 0;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .payment-title h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .payment-title p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .payment-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .payment-main {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }

        .payment-sidebar {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            height: fit-content;
        }

        .payment-form-section h4 {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .payment-form-group {
            margin-bottom: 25px;
        }

        .payment-form-label {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
        }

        .payment-form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            background: white;
        }

        .payment-error {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 8px;
        }

        .payment-actions {
            margin-top: 30px;
        }

        .payment-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .payment-btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .payment-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        }

        .payment-btn-outline {
            background: transparent;
            color: #6c757d;
            border: 2px solid #e9ecef;
        }

        .payment-btn-outline:hover {
            border-color: #6c757d;
            color: #495057;
        }

        .payment-security-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .payment-booking-summary,
        .payment-price-section {
            margin-bottom: 30px;
        }

        .payment-booking-title,
        .payment-price-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f3f4;
        }

        .payment-booking-item,
        .payment-price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .payment-booking-item:last-child,
        .payment-price-item:last-child {
            border-bottom: none;
        }

        .label {
            color: #6c757d;
            font-weight: 500;
        }

        .value {
            color: #2c3e50;
            font-weight: 600;
        }

        .payment-price-total {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        .payment-price-total .amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 5px;
        }

        .payment-price-total div:last-child {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .payment-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .d-none {
            display: none !important;
        }

        @media (max-width: 768px) {
            .payment-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .payment-main,
            .payment-sidebar {
                padding: 25px;
            }

            .payment-title h1 {
                font-size: 2rem;
            }
        }
    </style>
    @endpush
</x-app-layout>

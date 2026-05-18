<x-app-layout>
    <div class="payment-container">
        <div class="container">
            <!-- Header Section -->
            <div class="payment-header">
                <div class="payment-title">
                    <h1>Complete Payment</h1>
                    <p>Enter your card details to complete your booking securely</p>
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
                                    Pay {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
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
                        <a href="{{ route('flights.payment', $booking->id) }}" class="payment-btn payment-btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Payment Methods
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

        // Add error handling for card mounting
        try {
            card.mount('#card-element');
            console.log('Card element mounted successfully');
        } catch (error) {
            console.error('Error mounting card element:', error);
            document.getElementById('card-errors').textContent = 'Error initializing payment form. Please refresh the page.';
            document.getElementById('card-errors').classList.add('show');
        }

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const spinner = submitButton.querySelector('.payment-spinner');

        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.classList.add('show');
            } else {
                displayError.textContent = '';
                displayError.classList.remove('show');
            }
        });

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Disable the submit button and show spinner
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
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    errorElement.classList.add('show');
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');
                } else {
                    // Payment successful
                    window.location.href = '{{ route('flights.payment.success', $booking->id) }}';
                }
            } catch (e) {
                console.error('Payment error:', e);
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = 'An unexpected error occurred. Please try again.';
                errorElement.classList.add('show');
                submitButton.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
    @endpush
</x-app-layout> 
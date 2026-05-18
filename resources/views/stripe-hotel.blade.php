<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Complete Your Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div id="card-element" class="form-control"></div>
                            <div id="card-errors" class="invalid-feedback" role="alert"></div>
                        </div>
                        <form id="payment-form">
                            @csrf
                            <button id="submit-payment" class="btn btn-primary">
                                <span id="button-text">Pay Now</span>
                                <div id="spinner" class="spinner hidden"></div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Summary</h4>
                    </div>
                    <div class="card-body">
                        <h5>{{ $hotel->title }}</h5>
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
                    <a href="{{ route('hotel.payment.show', ['booking_id' => $booking->id]) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Payment Methods
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        // Get form elements
        const form = document.getElementById('payment-form');
        const cardErrors = document.getElementById('card-errors');
        const spinner = document.getElementById('spinner');
        const buttonText = document.getElementById('button-text');

        // Handle card element changes
        card.addEventListener('change', function(event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
                cardErrors.style.display = 'block';
            } else {
                cardErrors.textContent = '';
                cardErrors.style.display = 'none';
            }
        });

        // Handle form submission
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            setLoading(true);

            try {
                console.log('Processing payment...');
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
                    console.error('Payment error:', error);
                    cardErrors.textContent = error.message;
                    cardErrors.style.display = 'block';
                    setLoading(false);
                } else {
                    console.log('Payment successful:', paymentIntent);
                    if (paymentIntent.status === 'succeeded') {
                        window.location.href = '{{ route('hotel.payment.success', ['booking_id' => $booking->id]) }}';
                    }
                }
            } catch (err) {
                console.error('Unexpected error:', err);
                cardErrors.textContent = 'An unexpected error occurred. Please try again.';
                cardErrors.style.display = 'block';
                setLoading(false);
            }
        });

        function setLoading(isLoading) {
            if (isLoading) {
                buttonText.style.display = 'none';
                spinner.classList.remove('hidden');
                form.querySelector('button').disabled = true;
            } else {
                buttonText.style.display = 'block';
                spinner.classList.add('hidden');
                form.querySelector('button').disabled = false;
            }
        }
    </script>
    <style>
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        .hidden {
            display: none;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        #card-errors {
            color: #dc3545;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
    @endpush
</x-app-layout>

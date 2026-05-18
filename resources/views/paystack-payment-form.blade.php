<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Complete Payment with Paystack</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Payment Amount:</strong> {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                        </div>
                        
                        <form id="paystack-form">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount (NGN)</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ $booking->total_amount * 100 }}" readonly>
                                <small class="text-muted">Amount in kobo (smallest currency unit)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reference" class="form-label">Reference</label>
                                <input type="text" class="form-control" id="reference" name="reference" value="{{ $reference }}" readonly>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg" id="paystack-button">
                                    <i class="fas fa-credit-card"></i> Pay with Paystack
                                </button>
                            </div>
                        </form>
                        
                        <div class="mt-4 text-center">
                            <a href="{{ route('flights.payment', $booking->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Payment Methods
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.getElementById('paystack-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const handler = PaystackPop.setup({
                key: '{{ config("services.paystack.public_key") }}',
                email: document.getElementById('email').value,
                amount: document.getElementById('amount').value,
                currency: 'NGN',
                ref: document.getElementById('reference').value,
                callback: function(response) {
                    // Redirect to callback URL with reference
                    window.location.href = '{{ route("flights.payment.paystack.callback", ["booking_id" => $booking->id]) }}?reference=' + response.reference;
                },
                onClose: function() {
                    alert('Payment cancelled. Please try again.');
                }
            });
            handler.openIframe();
        });
    </script>
</x-app-layout> 
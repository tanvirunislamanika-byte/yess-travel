<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0"><i class="fas fa-check-circle"></i> Payment Successful!</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-success mb-3">Payment Completed Successfully</h4>
                        <p class="text-muted mb-4">Your payment has been processed successfully via Paystack. You will receive a confirmation email shortly.</p>
                        
                        <div class="alert alert-info">
                            <strong>Transaction Reference:</strong> {{ request()->query('reference') ?? 'N/A' }}
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-home"></i> Go to Dashboard
                            </a>
                            <a href="{{ route('flights.booking.details', ['booking_id' => request()->query('booking_id')]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plane"></i> View Booking Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
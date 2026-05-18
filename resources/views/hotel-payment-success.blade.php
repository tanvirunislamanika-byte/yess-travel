<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Booking Confirmation</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle text-success fa-4x"></i>
                            <h3 class="mt-3">Payment Successful!</h3>
                            <p class="text-muted">Your hotel booking has been confirmed.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Guest Information</h5>
                                <div class="mb-3">
                                    <label class="text-muted">Name</label>
                                    <p class="mb-0">{{ auth()->user()->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted">Email</label>
                                    <p class="mb-0">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted">Phone</label>
                                    <p class="mb-0">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Location</h5>
                                <div class="mb-3">
                                    <label class="text-muted">Country</label>
                                    <p class="mb-0">{{ auth()->user()->country->name ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted">State</label>
                                    <p class="mb-0">{{ auth()->user()->state ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted">City</label>
                                    <p class="mb-0">{{ auth()->user()->city ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i> A confirmation email has been sent to your email address.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Booking Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Transaction ID</label>
                                        <p class="mb-0">{{ $booking->transaction_id }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Hotel</label>
                                        <p class="mb-0">{{ $hotel->title }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Check-in Date</label>
                                        <p class="mb-0">{{ date('F d, Y', strtotime($booking->check_in)) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Check-out Date</label>
                                        <p class="mb-0">{{ date('F d, Y', strtotime($booking->check_out)) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Number of Rooms</label>
                                        <p class="mb-0">{{ $booking->rooms }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Number of Guests</label>
                                        <p class="mb-0">{{ $booking->adults }} Adults, {{ $booking->childrens }} Children</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Total Amount</label>
                                        <p class="mb-0">${{ number_format($booking->price, 2) }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted d-block">Payment Method</label>
                                        <p class="mb-0">{{ $booking->payment_via }}</p>
                                    </div>
                                </div>
                            </div>
            
                <div class="mt-3">
                    <a href="{{ route('hotels.list') }}" class="btn btn-primary w-100">
                        <i class="fas fa-home"></i> Back to Hotels
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
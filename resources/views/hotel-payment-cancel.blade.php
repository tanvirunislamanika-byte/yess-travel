<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-danger fa-5x"></i>
                        </div>
                        <h2 class="mb-4">Payment Cancelled</h2>
                        <p class="lead mb-4">Your payment was cancelled. No charges were made.</p>
                        
                        <div class="booking-details mb-4">
                            <h4>Booking Details</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Hotel:</strong> {{ $booking->hotel->title }}</p>
                                    <p><strong>Check-in:</strong> {{ date('M d, Y', strtotime($booking->check_in)) }}</p>
                                    <p><strong>Check-out:</strong> {{ date('M d, Y', strtotime($booking->check_out)) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Rooms:</strong> {{ $booking->rooms }}</p>
                                    <p><strong>Guests:</strong> {{ $booking->adults }} Adults, {{ $booking->childrens }} Children</p>
                                    <p><strong>Total Amount:</strong> ${{ number_format($booking->price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Your booking has been cancelled. You can try booking again.
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('hotel.payment.show', ['booking_id' => $booking->id]) }}" class="btn btn-primary">
                                <i class="fas fa-redo"></i> Try Again
                            </a>
                            <a href="{{ route('hotel-detail', $booking->hotel_id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-hotel"></i> Back to Hotel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
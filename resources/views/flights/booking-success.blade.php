<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0 text-white">Booking Confirmed!</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($booking))
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">Thank You for Your Booking</h3>
                            <p class="text-muted">Your booking has been confirmed and details have been sent to your email.</p>
                        </div>

                        <div class="booking-details">
                            <h5 class="border-bottom pb-2">Booking Details</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Booking Reference:</strong>
                                    <p>{{ $booking->transaction_id ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p><span class="badge bg-success">{{ $booking->status ?? 'N/A' }}</span></p>
                                </div>
                            </div>

                            @if(isset($booking->transaction_id))
                                <div class="mb-3">
                                    <strong>Ticket Number:</strong>
                                    <p>{{ $booking->transaction_id }}</p>
                                </div>
                            @endif

                            @if(isset($booking->slices) && is_array($booking->slices))
                                @foreach($booking->slices as $slice)
                                    <div class="mb-4">
                                        <h6>Flight Segment</h6>
                                        @php $isNonStop = count($slice['segments']) === 1; @endphp
                                        @foreach($slice['segments'] as $segment)
                                            <div class="border rounded p-2 mb-2">
                                                <p class="small">
                                                    <i class="far fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($segment['departing_at'])->format('H:i') }} - {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('H:i') }} ({{ $segment['duration'] ?? 'N/A' }})
                                                </p>
                                                <p class="small">
                                                    <i class="fas fa-plane"></i>
                                                    {{ $segment['operating_carrier']['name'] ?? '' }} - {{ $segment['operating_carrier']['flight_number'] ?? '' }}
                                                    @if(isset($segment['aircraft']['name']))<br>
                                                        <span>Aircraft: {{ $segment['aircraft']['name'] }} ({{ $segment['aircraft']['iata_code'] ?? '' }})</span>
                                                    @endif
                                                </p>
                                                <p class="small">
                                                    <i class="fas fa-plane-departure"></i> {{ $segment['origin']['name'] ?? '' }}
                                                    @if(isset($segment['origin_terminal']))
                                                        <span class="ms-2">Terminal: {{ $segment['origin_terminal'] }}</span>
                                                    @endif
                                                </p>
                                                <p class="small">
                                                    <i class="fas fa-plane-arrival"></i> {{ $segment['destination']['name'] ?? '' }}
                                                    @if(isset($segment['destination_terminal']))
                                                        <span class="ms-2">Terminal: {{ $segment['destination_terminal'] }}</span>
                                                    @endif
                                                </p>
                                                <p class="small">
                                                    <i class="fas fa-chair"></i>
                                                    Cabin: {{ $segment['passengers'][0]['cabin_class_marketing_name'] ?? 'N/A' }}
                                                </p>
                                                @if(isset($segment['passengers'][0]['baggages']) && is_array($segment['passengers'][0]['baggages']))
                                                    <p class="small">
                                                        <i class="fas fa-suitcase"></i>
                                                        @foreach($segment['passengers'][0]['baggages'] as $baggage)
                                                            {{ ucfirst($baggage['type']) }} bag: {{ $baggage['quantity'] ?? 1 }}
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                        <p class="small"><strong>Flight type:</strong> {{ $isNonStop ? 'Non-stop' : 'With stops' }}</p>
                                    </div>
                                @endforeach
                            @endif

                            <h5 class="border-bottom pb-2">Flight Information</h5>
                            <div class="flight-segment mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Route:</strong>
                                        <p>{{ $booking->flight_from ?? 'N/A' }} to {{ $booking->flight_to ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Travelling Date:</strong>
                                        <p>{{ $booking->travelling_on ? \Carbon\Carbon::parse($booking->travelling_on)->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                                @if($booking->one_way_or_two_way === 'two-way' && $booking->return)
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>Return Date:</strong>
                                            <p>{{ \Carbon\Carbon::parse($booking->return)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>Passengers:</strong>
                                        <p>Adults: {{ $booking->adults ?? 0 }}, Children: {{ $booking->childrens ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2">Payment Details</h5>
                            <div class="payment-info mb-3">
                                <p><strong>Total Price:</strong> {{ $booking->price ?? 'N/A' }} {{ $booking->total_currency ?? 'USD' }}</p>
                                <p><strong>Payment Method:</strong> {{ $booking->payment_via ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">View All Bookings</a>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary ms-2">Return to Home</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">Booking Not Found</h4>
                            <p>We couldn't find the booking details you're looking for.</p>
                            <div class="text-center mt-3">
                                <a href="" class="btn btn-primary">Return to Home</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout> 
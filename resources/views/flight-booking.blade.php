<x-app-layout>
    <!-- Page title start -->
   
    <section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.my_flights_bookings') }}</h1>
            </div>
        </div>
    </section>

    <!-- Page title end -->
    <!-- Page content start -->
    <div class="innerpagewrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('components.user-sidebar')
                </div>
                <div class="col-lg-9">
                    @php
                        $flight_bookings = \App\Models\FlightBooking::where('user_id', auth()->user()->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(9); // 9 bookings per page (3x3 grid)
                    @endphp

                    {{-- General system error --}}
                    @if (session('error'))
                    <div class="alert alert-danger">
                        <strong>{{ __('frontend.error') }}:</strong> {{ session('error') }}
                    </div>
                    @endif
                    
                    {{-- Success message (if needed) --}}
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="dashtask">
                     
                        
                        @if($flight_bookings->count() > 0)
                            <div class="flight-booking-cards">
                                @foreach($flight_bookings as $booking)
                                        @php
                                            $passengerDetails = is_string($booking->passenger_details)
                                                ? json_decode($booking->passenger_details, true)
                                                : $booking->passenger_details;
                                            $passengers = array_values($passengerDetails ?? []);
                                        
                                        // Get flight info
                                        $flightInfo = json_decode($booking->flight_info, true);
                                        $origin = $flightInfo['slices'][0]['origin']['iata_code'] ?? 'N/A';
                                        $destination = $flightInfo['slices'][0]['destination']['iata_code'] ?? 'N/A';
                                        $departureTime = $booking->departure_date ? \Carbon\Carbon::parse($booking->departure_date) : null;
                                        $returnTime = $booking->return_date ? \Carbon\Carbon::parse($booking->return_date) : null;
                                        
                                        // Check if booking is expired
                                        $isExpired = !empty($booking->order_expire_at) && 
                                                   \Carbon\Carbon::parse($booking->order_expire_at)->isPast();
                                        @endphp
                                    
                                    <div class="flight-booking-card">
                                        <!-- Card Header -->
                                        <div class="flight-card-header">
                                            <div class="airline-info">
                                                <div class="airline-logo">
                                                    @if ($booking->airline_code)
                                                    <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $booking->airline_code }}.svg"
                                                            alt="{{ $booking->airline_code }}">
                                                @else
                                                        <i class="fas fa-plane"></i>
                                                @endif
                                                </div>
                                                <div class="airline-name">{{ $booking->airline_name ?? __('frontend.airline') }}</div>
                                            </div>
                                            <div class="booking-status {{ $booking->booking_status }}">
                                                {{ ucfirst($booking->booking_status) }}
                                            </div>
                                        </div>

                                        <!-- Expiry Warning for Hold Bookings -->
                                        @if($booking->booking_status == 'hold' && !empty($booking->order_expire_at))
                                            @if($isExpired)
                                                <div class="expiry-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    This booking has expired on {{ \Carbon\Carbon::parse($booking->order_expire_at)->format('M j, Y h:i A') }}
                                                </div>
                                            @else
                                                <div class="expiry-warning">
                                                    <i class="fas fa-clock"></i>
                                                    Payment required by {{ \Carbon\Carbon::parse($booking->order_expire_at)->format('M j, Y h:i A') }}
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Route Section -->
                                        <div class="flight-route">
                                            <div class="route-point">
                                                <div class="airport-code">{{ $origin }}</div>
                                                <div class="city-name">{{ $flightInfo['slices'][0]['origin']['city_name'] ?? __('frontend.origin') }}</div>
                                            </div>
                                            <div class="route-arrow">
                                                <div class="plane-icon">
                                                    <i class="fas fa-plane"></i>
                                                </div>
                                            </div>
                                            <div class="route-point">
                                                <div class="airport-code">{{ $destination }}</div>
                                                <div class="city-name">{{ $flightInfo['slices'][0]['destination']['city_name'] ?? __('frontend.destination') }}</div>
                                            </div>
                                        </div>

                                        <!-- Flight Details -->
                                        <div class="flight-details">
                                            <div class="flight-detail-item">
                                                <i class="fas fa-calendar"></i>
                                                <div class="detail-content">
                                                    <div class="label">{{ __('frontend.departure') }}</div>
                                                    <div class="value">{{ $departureTime ? $departureTime->format('M j, Y h:i A') : 'N/A' }}</div>
                                                </div>
                                            </div>
                                            @if($returnTime)
                                            <div class="flight-detail-item">
                                                <i class="fas fa-calendar-check"></i>
                                                <div class="detail-content">
                                                    <div class="label">{{ __('frontend.return') }}</div>
                                                    <div class="value">{{ $returnTime->format('M j, Y h:i A') }}</div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="flight-detail-item">
                                                <i class="fas fa-clock"></i>
                                                <div class="detail-content">
                                                    <div class="label">{{ __('frontend.duration') }}</div>
                                                    <div class="value">
                                                        @if($departureTime && $returnTime)
                                                            {{ $departureTime->diffForHumans($returnTime, ['parts' => 2]) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flight-detail-item">
                                                <i class="fas fa-chair"></i>
                                                <div class="detail-content">
                                                    <div class="label">{{ __('frontend.class') }}</div>
                                                    <div class="value">{{ ucfirst($flightInfo['slices'][0]['passengers'][0]['cabin_class_marketing_name'] ?? 'Economy') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Passenger Info -->
                                        @if(!empty($passengers))
                                        <div class="passenger-info">
                                            <h6><i class="fas fa-users"></i> Passengers</h6>
                                            <div class="passenger-list">
                                                @foreach($passengers as $passenger)
                                                    @php
                                                        $givenName = $passenger['given_name'] ?? '';
                                                        $familyName = $passenger['family_name'] ?? '';
                                                        $fullName = trim($givenName . ' ' . $familyName);
                                                    @endphp
                                                    <span class="passenger-tag">{{ $fullName }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Price and Actions -->
                                        <div class="flight-card-footer">
                                            <div class="flight-price">
                                                <span class="amount">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                                <span class="currency">{{ __('frontend.total_amount') }}</span>
                                                    </div>
                                            <div class="flight-actions">
                                                <a href="{{ route('flights.booking.details', $booking->id) }}" class="btn btn-view">
                                                    <i class="fas fa-eye"></i> {{ __('frontend.view_details') }}
                                                </a>
                                                
                                                @if($booking->payment_status == 'pending' && $booking->booking_status == 'hold')
                                                    @if(!$isExpired)
                                                        <a href="{{ route('flights.payment', $booking->id) }}" class="btn btn-pay">
                                                            <i class="fas fa-credit-card"></i> {{ __('frontend.pay_now') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-wrapper">
                                {{ $flight_bookings->links() }}
                            </div>
                        @else
                            <div class="flight-booking-cards">
                                <div class="empty-state">
                                    <i class="fas fa-plane-slash"></i>
                                    <h5>{{ __('frontend.no_flight_bookings_title') }}</h5>
                                    <p>{{ __('frontend.no_flight_bookings_desc') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  end -->
</x-app-layout>

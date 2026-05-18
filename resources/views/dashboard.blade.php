<x-app-layout>
    <!-- Page title start -->
    
    <section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.dashboard_title') }}</h1>
            </div>
        </div>
    </section>
    <!-- Page title end -->
    <!-- Page content start -->
    <div class="innerpagewrap">
        <div class="container">
            @if (session('verified'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ {{ __('frontend.email_verified_successfully') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


            <div class="row">
                <div class="col-lg-3">
                    @include('components.user-sidebar')
                </div>
                <div class="col-lg-9">
                    <?php $hotel_bookings = App\Models\Hotels::where('user_id', auth()->user()->id)
                        ->where('booking_status', 'Pending')
                        ->whereDate('check_in', '>=', now())
                        ->where('status', 'paid')
                        ->get(); ?>
                    @php
                        $flight_bookings = \App\Models\FlightBooking::where('user_id', auth()->user()->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    @php
                        $tour_bookings = \App\Models\TourBooking::where('user_id', auth()->user()->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    <div class="twm-dash-b-blocks mb-3">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <div class="panel panel-default">
                                    <div class="panel-body wt-panel-body dashboard-card-2 block-gradient-4">
                                        <div class="wt-card-wrap-2">
                                            <div class="wt-card-icon-2"><i class="fas fa-hotel"></i></div>
                                            <div class="wt-card-right wt-total-active-listing counter ">
                                                {{ count($hotel_bookings) }}</div>
                                            <div class="wt-card-bottom-2 ">
                                                <h4 class="m-b0">{{ __('frontend.my_recent_hotels_bookings') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <div class="panel panel-default">
                                    <div class="panel-body wt-panel-body dashboard-card-2 block-gradient-3">
                                        <div class="wt-card-wrap-2">
                                            <div class="wt-card-icon-2"><i class="fas fa-paper-plane"></i></div>
                                            <div class="wt-card-right  wt-total-listing-view counter ">
                                                {{ count($flight_bookings) }}</div>
                                            <div class="wt-card-bottom-2">
                                                <h4 class="m-b0">{{ __('frontend.my_recent_flights_bookings') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <div class="panel panel-default">
                                    <div class="panel-body wt-panel-body dashboard-card-2 block-gradient-2">
                                        <div class="wt-card-wrap-2">
                                            <div class="wt-card-icon-2"><i class="fas fa-plane-departure"></i></div>
                                            <div class="wt-card-right  wt-total-listing-view counter ">
                                                {{ count($tour_bookings) }}</div>
                                            <div class="wt-card-bottom-2">
                                                <h4 class="m-b0">{{ __('frontend.my_recent_tour_bookings') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashtask pb-4">
                        <div class="sortby d-flex justify-content-between mb-3 align-items-center">
                            <h3>{{ __('frontend.recent_hotel_bookings') }}</h3>
                        </div>
                        @if (null !== $hotel_bookings && count($hotel_bookings))
                            <div id="dashboardHotelCarousel" class="carousel slide" data-bs-ride="carousel">
                              <div class="carousel-inner">
                                @foreach ($hotel_bookings->chunk(2) as $chunkIndex => $chunk)
                                  <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="hotel-booking-cards">
                                      @foreach ($chunk as $booking)
                                        @php 
                                          $hotel = App\Models\ModulesData::where('id', $booking->hotel_id)->first();
                                          $checkIn = \Carbon\Carbon::parse($booking->check_in);
                                          $checkOut = \Carbon\Carbon::parse($booking->check_out);
                                          $duration = $checkIn->diffInDays($checkOut);
                                        @endphp
                                        <div class="hotel-booking-card">
                                          <div class="hotel-card-header">
                                            <div class="hotel-info">
                                              <div class="hotel-logo">
                                                @if($hotel && $hotel->image)
                                                  <img src="{{ asset('images/' . $hotel->image) }}" alt="{{ $hotel->title }}" class="hotel-image">
                                                @else
                                                  <i class="fas fa-hotel"></i>
                                                @endif
                                              </div>
                                              <div class="hotel-name">
                                                <h5>{{ $hotel->title ?? 'Hotel' }}</h5>
                                                <span class="location"><i class="fas fa-map-marker-alt"></i> {{ $booking->travelling_from }}</span>
                                              </div>
                                            </div>
                                            <div class="booking-status {{ $booking->status }}">
                                              <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                                            </div>
                                          </div>
                                          <div class="hotel-details">
                                            <div class="hotel-detail-item">
                                              <i class="fas fa-calendar-check"></i>
                                              <div class="detail-content">
                                                <div class="label">{{ __('frontend.check_in') }}</div>
                                                <div class="value">{{ $checkIn->format('M j, Y') }}</div>
                                              </div>
                                            </div>
                                            <div class="hotel-detail-item">
                                              <i class="fas fa-calendar-times"></i>
                                              <div class="detail-content">
                                                <div class="label">{{ __('frontend.check_out') }}</div>
                                                <div class="value">{{ $checkOut->format('M j, Y') }}</div>
                                              </div>
                                            </div>
                                            <div class="hotel-detail-item">
                                              <i class="fas fa-moon"></i>
                                              <div class="detail-content">
                                                <div class="label">{{ __('frontend.nights') }}</div>
                                                <div class="value">{{ $duration }}</div>
                                              </div>
                                            </div>
                                            <div class="hotel-detail-item">
                                              <i class="fas fa-users"></i>
                                              <div class="detail-content">
                                                <div class="label">{{ __('frontend.guests') }}</div>
                                                <div class="value">{{ $booking->adults }} {{ __('frontend.adults') }}{{ $booking->childrens ? ', ' . $booking->childrens . ' ' . __('frontend.children') : '' }}</div>
                                              </div>
                                            </div>
                                            <div class="hotel-detail-item">
                                              <i class="fas fa-door-open"></i>
                                              <div class="detail-content">
                                                <div class="label">{{ __('frontend.rooms') }}</div>
                                                <div class="value">{{ $booking->rooms }}</div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="hotel-card-footer">
                                            <div class="hotel-price">
                                              <span class="amount">${{ number_format((float)$booking->price, 2) }}</span>
                                              <span class="currency">{{ __('frontend.total') }}</span>
                                            </div>
                                            <div class="hotel-actions">
                                              <a href="{{ route('invoice.generate', $booking->id) }}" class="btn btn-view"><i class="fas fa-file-invoice"></i> {{ __('frontend.invoice') }}</a>
                                              @if($hotel)
                                                <a href="{{ route('hotel.detail', $hotel->slug) }}" class="btn btn-details"><i class="fas fa-info-circle"></i> {{ __('frontend.details') }}</a>
                                              @endif
                                            </div>
                                          </div>
                                        </div>
                                      @endforeach
                                    </div>
                                  </div>
                                @endforeach
                              </div>    
                              <div class="carousel-controls">
                              <button class="carousel-control-prev" type="button" data-bs-target="#dashboardHotelCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                              </button>
                              <button class="carousel-control-next" type="button" data-bs-target="#dashboardHotelCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                              </button>
                              </div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-hotel"></i>
                                <h5>{{ __('frontend.no_hotel_bookings_title') }}</h5>
                                <p>{{ __('frontend.no_hotel_bookings_desc') }}</p>
                            </div>
                        @endif
                    </div>

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

                    <hr>

                    <div class="dashtask mt-4">
                        <div class="sortby d-flex justify-content-between mb-3 align-items-center">
                            <h3>{{ __('frontend.your_flight_bookings') }}</h3>
                        </div>

                        <div class="flight-booking-cards">
                            @forelse($flight_bookings as $booking)
                                @php
                                    $passengerDetails = is_string($booking->passenger_details)
                                        ? json_decode($booking->passenger_details, true)
                                        : $booking->passenger_details;
                                    $passengers = array_values($passengerDetails ?? []);
                                    $familyNames = collect($passengers)
                                        ->pluck('family_name')
                                        ->unique()
                                        ->implode(', ');
                                    $airlineLogo = null;
                                    $airlineName = null;
                                    $nextDeparture = $booking->departure_date
                                        ? \Carbon\Carbon::parse($booking->departure_date)->format('d/m/Y H:i')
                                        : '-';
                                    $departureDate = $booking->departure_date
                                        ? \Carbon\Carbon::parse($booking->departure_date)->format('M d, Y')
                                        : '-';
                                    $departureTime = $booking->departure_date
                                        ? \Carbon\Carbon::parse($booking->departure_date)->format('h:i A')
                                        : '-';
                                    $arrivalTime = $booking->arrival_time
                                        ? \Carbon\Carbon::parse($booking->arrival_time)->format('h:i A')
                                        : '-';
                                    $duration = '';
                                    if ($booking->departure_time && $booking->arrival_time) {
                                        $departure = \Carbon\Carbon::parse($booking->departure_time);
                                        $arrival = \Carbon\Carbon::parse($booking->arrival_time);
                                        $duration = $departure->diffForHumans($arrival, ['parts' => 2]);
                                    }
                                @endphp
                                
                                <div class="flight-booking-card">
                                   
                                    
                                    <!-- Card Header -->
                                    <div class="flight-card-header">
                                        <div class="airline-info">
                                            <div class="airline-logo">
                                                @if ($booking->airline_code ?? false)
                                                    <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $booking->airline_code }}.svg"
                                                        alt="{{ $booking->airline_code }}">
                                                @else
                                                    <i class="fas fa-plane"></i>
                                                @endif
                                            </div>
                                            <div class="airline-name">
                                                {{ $booking->airline_name ?? 'Airline' }}
                                            </div>
                                        </div>
                                        <div class="booking-status {{ $booking->booking_status }}">
                                            {{ ucfirst($booking->booking_status) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Route Section -->
                                    <div class="flight-route">
                                        <div class="route-point">
                                            <div class="airport-code">{{ $booking->origin_code ?? 'N/A' }}</div>
                                            <div class="city-name">{{ $booking->origin_airport ?? __('frontend.origin') }}</div>
                                        </div>
                                        <div class="route-arrow">
                                            <div class="plane-icon">
                                                <i class="fas fa-plane"></i>
                                            </div>
                                        </div>
                                        <div class="route-point">
                                            <div class="airport-code">{{ $booking->destination_code ?? 'N/A' }}</div>
                                            <div class="city-name">{{ $booking->destination_airport ?? __('frontend.destination') }}</div>
                                        </div>
                                    </div>

                                     <!-- Expiry Warning -->
                                     @if (!empty($booking->order_expire_at) && $booking->booking_status == 'hold')
                                        @php
                                            $formattedExpiry = \Carbon\Carbon::parse($booking->order_expire_at)->format('F j, Y h:i A');
                                        @endphp
                                        <div class="expiry-warning">
                                            <i class="fas fa-clock"></i>
                                            <strong>{{ __('frontend.expires') }}:</strong> {{ $formattedExpiry }}
                                        </div>
                                    @endif
                                    
                                    <!-- Flight Details -->
                                    <div class="flight-details">
                                        <div class="flight-detail-item">
                                            <i class="fas fa-calendar"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.date') }}:</span>
                                            <span class="value">{{ $departureDate }}</span>
                                            </div>
                                        </div>
                                        <div class="flight-detail-item">
                                            <i class="fas fa-clock"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.departure') }}:</span>
                                            <span class="value">{{ $departureTime }}</span>
                                            </div>
                                        </div>
                                        <div class="flight-detail-item">
                                            <i class="fas fa-clock"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.arrival') }}:</span>
                                            <span class="value">{{ $arrivalTime }}</span>
                                            </div>
                                        </div>
                                        <div class="flight-detail-item">
                                            <i class="fas fa-users"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.passengers') }}:</span>
                                            <span class="value">{{ $booking->adults + $booking->children }}</span>
                                            </div>
                                        </div>
                                        @if($duration)
                                        <div class="flight-detail-item">
                                            <i class="fas fa-hourglass-half"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.duration') }}:</span>
                                            <span class="value">{{ $duration }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="flight-detail-item">
                                            <i class="fas fa-suitcase"></i>
                                            <div class="flight-detail-item-content">
                                            <span class="label">{{ __('frontend.class') }}:</span>
                                            <span class="value">{{ ucfirst($booking->cabin_class ?? 'Economy') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Passenger Info -->
                                    @if(!empty($passengers))
                                    <div class="passenger-info">
                                        <h6><i class="fas fa-users"></i> {{ __('frontend.passengers') }}</h6>
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
                                            <span class="currency">{{ __('frontend.total_fare') }}</span>
                                        </div>
                                        <div class="flight-actions">
                                            <a href="{{ route('flights.booking.details', $booking->id) }}" class="btn btn-view">
                                                <i class="fas fa-eye"></i> {{ __('frontend.view') }}
                                            </a>
                                            @if (
                                                $booking->payment_status == 'pending' &&
                                                (!empty($booking->order_expire_at) && \Carbon\Carbon::parse($booking->order_expire_at)->isFuture())
                                            )
                                                <a href="{{ route('flights.payment', $booking->id) }}" class="btn btn-pay">
                                                    <i class="fas fa-credit-card"></i> {{ __('frontend.pay_now') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fas fa-plane-slash"></i>
                                    <h5>{{ __('frontend.no_flight_bookings_title') }}</h5>
                                    <p>{{ __('frontend.no_flight_bookings_desc') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  end -->
      <script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.remove();
    }, 4000); // 4 seconds
</script>


</x-app-layout>

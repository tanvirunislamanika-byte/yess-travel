<x-app-layout>
   <!-- Page title start -->

<section class="bookings-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.my_hotel_bookings') }}</h1>
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
                $hotel_bookings = App\Models\Hotels::where('user_id', auth()->user()->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(9); // 9 bookings per page (3x3 grid)
              @endphp
             

              


              <div class="dashtask">
              
              @if($hotel_bookings->count() > 0)
                <div class="hotel-booking-cards">
                  @foreach($hotel_bookings as $booking)
                    @php 
                      $hotel = App\Models\ModulesData::where('id', $booking->hotel_id)->first();
                      $checkIn = \Carbon\Carbon::parse($booking->check_in);
                      $checkOut = \Carbon\Carbon::parse($booking->check_out);
                      $duration = $checkIn->diffInDays($checkOut);
                    @endphp
                    
                    <div class="hotel-booking-card">
                      <!-- Card Header -->
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

                      <!-- Booking Details -->
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
                          <i class="fas fa-clock"></i>
                          <div class="detail-content">
                            <div class="label">{{ __('frontend.duration') }}</div>
                            <div class="value">{{ $duration }} {{ $duration == 1 ? __('frontend.night') : __('frontend.nights') }}</div>
                          </div>
                        </div>
                        <div class="hotel-detail-item">
                          <i class="fas fa-users"></i>
                          <div class="detail-content">
                            <div class="label">{{ __('frontend.guests') }}</div>
                            <div class="value">{{ $booking->adults }} {{ __('frontend.adults') }}, {{ $booking->childrens }} {{ __('frontend.children') }}</div>
                          </div>
                        </div>
                        <div class="hotel-detail-item">
                          <i class="fas fa-bed"></i>
                          <div class="detail-content">
                            <div class="label">{{ __('frontend.rooms') }}</div>
                            <div class="value">{{ $booking->rooms }} {{ $booking->rooms == 1 ? __('frontend.room') : __('frontend.rooms') }}</div>
                          </div>
                        </div>
                        <div class="hotel-detail-item">
                          <i class="fas fa-credit-card"></i>
                          <div class="detail-content">
                            <div class="label">{{ __('frontend.payment') }}</div>
                            <div class="value">{{ ucfirst($booking->payment_via) }}</div>
                          </div>
                        </div>
                      </div>

                      <!-- Guest Details -->
                      @if(!empty($booking->guest_details))
                        @php
                          $guestDetails = is_string($booking->guest_details) 
                            ? json_decode($booking->guest_details, true) 
                            : $booking->guest_details;
                        @endphp
                        @if(is_array($guestDetails) && count($guestDetails) > 0)
                          <div class="guest-info">
                            <h6><i class="fas fa-user-friends"></i> {{ __('frontend.guest_details') }}</h6>
                            <div class="guest-list">
                              @foreach(array_slice($guestDetails, 0, 3) as $guest)
                                @php
                                  $guestName = $guest['name'] ?? $guest['given_name'] ?? 'Guest';
                                @endphp
                                <span class="guest-tag">{{ $guestName }}</span>
                              @endforeach
                              @if(count($guestDetails) > 3)
                                <span class="guest-tag">+{{ count($guestDetails) - 3 }} {{ __('frontend.more') }}</span>
                              @endif
                            </div>
                          </div>
                        @endif
                      @endif

                      <!-- Price and Actions -->
                      <div class="hotel-card-footer">
                        <div class="hotel-price">
                          <span class="amount">${{ number_format($booking->price, 2) }}</span>
                          <span class="currency">{{ __('frontend.total_amount') }}</span>
                        </div>
                        <div class="hotel-actions">
                          <a href="{{ route('invoice.generate', $booking->id) }}" class="btn btn-view">
                            <i class="fas fa-file-invoice"></i> {{ __('frontend.view_invoice') }}
                          </a>
                          @if($hotel)
                            <a href="{{ route('hotel.detail', $hotel->slug) }}" class="btn btn-details">
                              <i class="fas fa-eye"></i> {{ __('frontend.hotel_details') }}
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                  {{ $hotel_bookings->links() }}
                </div>
              @else
               
                <div class="no-bookings">
                    <div class="no-bookings-icon">
                    <i class="fas fa-hotel"></i>
                    </div>
                    <h3 class="no-bookings-title">{{ __('frontend.no_hotel_bookings_title') }}</h3>
                    <p class="no-bookings-subtitle">{{ __('frontend.no_hotel_bookings_desc') }}</p>
                    <div class="no-bookings-actions">
                        <a href="{{ url('hotels') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            {{ __('frontend.browse_hotels') }}
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt"></i>
                            {{ __('frontend.back_to_dashboard') }}
                        </a>
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

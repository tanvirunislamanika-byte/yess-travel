<div class="profileDetails">
    <div class="profile-picture">
        <div class="mx-auto profile-radius">
            <div class="profile_img">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="img-fluid" src="{{ Auth::user()->profile_photo_url }}" />
                @else
                    <img class="img-fluid" src="https://ui-avatars.com/api/?name={{Auth::user()->name[0]}}" />
                @endif
            </div>
        </div>
    </div>
    
    <div class="authorDetail">
        <h3>{{auth()->user()->name}}</h3>
        <p><i class="fas fa-phone-alt" aria-hidden="true"></i> {{auth()->user()->phone}}</p>
        <p><i class="fas fa-map-marker-alt"></i> 
            {{ auth()->user()->state }}, {{ auth()->user()->city }},
            @if(isset(auth()->user()->countryData))
                {{ auth()->user()->countryData->title }}
            @endif
        </p>
    </div>

    <div class="editDetails">
        <ul>
            <li><a href="{{url('/dashboard')}}"><i class="fas fa-tachometer-alt"></i> {{ __('frontend.dashboard') }} </a></li>
            <li><a href="{{ route('profile.show') }}"><i class="fas fa-edit"></i> {{ __('frontend.edit_profile') }}</a></li>                                            
            <li><a href="{{ url('change-password') }}"><i class="fas fa-key"></i> {{ __('frontend.change_password') }}</a></li>
            <li><a href="{{ route('flight-booking') }}"><i class="fas fa-plane"></i> {{ __('frontend.my_flight_bookings') }}</a></li>
            <li><a href="{{ route('hotel-booking') }}"><i class="fas fa-hotel"></i> {{ __('frontend.my_hotel_bookings') }}</a></li>
            <li><a href="{{ route('tour.bookings') }}"><i class="fas fa-route"></i> {{ __('frontend.my_tour_bookings') }}</a></li>
            <li><a href="{{route('reviews.my')}}"><i class="fas fa-star"></i> {{ __('frontend.my_reviews') }}</a></li>
            <li><a href="{{route('payment-history')}}"><i class="fas fa-history"></i> {{ __('frontend.payment_history') }}</a></li>
            <li><a href="{{route('payment-methods')}}"><i class="fas fa-money-bill"></i> {{ __('frontend.payment_method') }}</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> {{ __('frontend.logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>

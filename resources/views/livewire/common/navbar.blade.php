<div class="header-wrap">
  <div class="container">
     <div class="row">
        <div class="col-lg-2 navbar-light">
           <div class="logo">
            <a href="{{url('/')}}">
            <!-- <img src="{{asset('images/'.widget(1)->extra_image_1)}}" alt="{{widget(1)->extra_field_1}}"> -->
               <img src="{{asset('images/logo/YessTrav.png')}}" style="height: 50px; width: auto;" alt="">
            </a>
         </div>
           <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <i class="fas fa-bars"></i> </button>
        </div>

        <div class="col-lg-10">                  
        <nav class="navbar navbar-expand-lg navbar-light mt-2">
           <div class="collapse navbar-collapse ms-auto" id="navbarSupportedContent">
              <button class="close-toggler" type="button" data-toggle="offcanvas"> <span><i class="fas fa-times-circle" aria-hidden="true"></i></span> </button>
                               
              <ul class="navbar-nav  mb-2 mb-lg-0 ms-auto">

              <li class="nav-item ps-2"><a href="{{url('/')}}" class="nav-link">{{ __('frontend.home') }}</a></li>

                 {!!get_menus(1)!!}

                


                 
                 @if(auth()->user())
                 <li class="nav-item dropdown ps-0 pe-0"> <a href="#" class=" dropdown-toggle" id="userdata" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@if (Laravel\Jetstream\Jetstream::managesProfilePhotos())

                        <img class="img-fluid" src="{{ Auth::user()->profile_photo_url }}" />

                        @else
                        <img class="img-fluid" src="https://ui-avatars.com/api/?name={{Auth::user()->name[0]}}" />
                        

                        @endif</a>
                    <ul class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="userdata">
                                              <li class="nav-item"><a href="{{url('/dashboard')}}" class="nav-link"><i class="fas fa-tachometer-alt"></i> {{ __('frontend.dashboard') }} </a></li>
                       <li class="nav-item"><a href="{{ route('profile.show') }}" class="nav-link"><i class="fas fa-edit"></i> {{ __('frontend.my_profile') }}</a></li>
                       <li class="nav-item"><a href="{{ route('hotel-booking') }}" class="nav-link"><i class="fas fa-hotel"></i> {{ __('frontend.my_hotel_bookings') }}</a></li>
                       <li class="nav-item"><a href="{{ route('flight-booking') }}" class="nav-link"><i class="fas fa-plane"></i> {{ __('frontend.my_flight_bookings') }}</a></li>
                       <li class="nav-item"><a href="{{ url('change-password') }}" class="nav-link"><i class="fas fa-key"></i> {{ __('frontend.change_password') }}</a></li>
                       <li class="nav-item"><a href="{{route('payment-history')}}" class="nav-link"><i class="fas fa-credit-card"></i> {{ __('frontend.payment_history') }}</a></li>
                       <li class="nav-item"><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link"><i class="fas fa-sign-out-alt"></i> {{ __('frontend.logout') }}</a></li>
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
    
                      </ul>

                  </li>
                 @endif  
                 
                 <li class="nav-item ps-2">
                    @include('components.language-switcher')
                 </li>    
              </ul>
              @if(!auth()->user())
              <a class="btn btn-sec ms-3" href="{{url('/login')}}"><i class="fas fa-sign-in-alt"></i> {{ __('frontend.login') }}</a>
              <a class="btn btn-primary ms-3" href="{{url('/register')}}" ><i class="fas fa-user"></i> {{ __('frontend.register') }}</a>
              @endif

              

           </div>                                  
        </nav>
        </div>
     </div>
  </div>
</div>

<footer>
  <div class="container"> 
    <div class="footerlogo"><img class="bg-white" style="border-radius: 5px;" src="{{asset('images/logo/YessTrav.png')}}"></div>
    <!-- social Section -->
    <?php $links = widget(30); ?>
    <div class="socialLinks" >
      <a href="{{$links->extra_field_1}}"><i class="fab fa-facebook-f"></i></a>
      <a href="{{$links->extra_field_2}}"><i class="fab fa-twitter"></i></a>
      <a href="{{$links->extra_field_3}}"><i class="fab fa-linkedin-in"></i></a>
      <a href="{{$links->extra_field_4}}"><i class="fab fa-instagram"></i></a>
      <a href="{{$links->extra_field_5}}"><i class="fab fa-youtube"></i></a>
    </div>

    <ul class="quicklinks">
      <li><a href="{{url('/')}}">{{ __('frontend.home') }}</a></li>
      <li><a href="{{url('/flights')}}">{{ __('frontend.menu.flights') }}</a></li>
      <li><a href="{{url('/hotels')}}">{{ __('frontend.menu.hotels') }}</a></li>
      <li><a href="{{url('/tours')}}">{{ __('frontend.menu.tours') }}</a></li>
      <li><a href="{{url('/about-us')}}">{{ __('frontend.menu.about-us') }}</a></li>
      <li><a href="{{url('/services')}}">{{ __('frontend.menu.services') }}</a></li>
      <li><a href="{{url('/blog')}}">{{ __('frontend.menu.blog') }}</a></li>
      <li><a href="{{url('/contact-us')}}">{{ __('frontend.menu.contact-us') }}</a></li>
    </ul>



    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="footer-copyright">
          <p>&copy; {{date('Y')}} YessTravel  | {{ __('frontend.all_rights_reserved') }}</p>
        </div>
      </div>
    </div>
  </div>
</footer>


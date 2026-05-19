<footer class="mt-5"
        style="background: rgb(3, 7, 19);
               backdrop-filter: blur(12px);
               -webkit-backdrop-filter: blur(12px);
               border-top: 1px solid rgba(255,255,255,0.08);
               padding: 60px 0 25px 0;">

    <div class="container">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-5">

            <!-- LOGO -->
            <div class="d-block align-items-start gap-3">
                <img src="{{asset('images/logo/YessTrav.png')}}"
                     style="height:50px; border-radius:4px;"
                     class="bg-white p-1">

                <p class="fw-bold text-white fs-5 mt-2 mb-0">
                    Yess Travel
                </p>
            </div>

            <!-- QUICK LINKS -->
            <div class="d-flex flex-wrap justify-content-center gap-4">

                <a href="{{url('/')}}" class="text-decoration-none text-white small">Home</a>
                <a href="{{url('/flights')}}" class="text-decoration-none text-white small">Flights</a>
                <a href="{{url('/hotels')}}" class="text-decoration-none text-white small">Hotels</a>
                <a href="{{url('/tours')}}" class="text-decoration-none text-white small">Tours</a>
                <a href="{{url('/about-us')}}" class="text-decoration-none text-white small">About</a>
                <a href="{{url('/services')}}" class="text-decoration-none text-white small">Services</a>
                <a href="{{url('/blog')}}" class="text-decoration-none text-white small">Blog</a>
                <a href="{{url('/contact-us')}}" class="text-decoration-none text-white small">Contact</a>

            </div>

            <!-- SOCIAL -->
            <?php $links = widget(30); ?>

            <div class="d-flex align-items-center gap-3 fs-5">

                <a href="{{$links->extra_field_1}}" class="text-white">
                    <i class="fab fa-facebook-f"></i>
                </a>

                <a href="{{$links->extra_field_2}}" class="text-white">
                    <i class="fab fa-twitter"></i>
                </a>

                <a href="{{$links->extra_field_3}}" class="text-white">
                    <i class="fab fa-linkedin-in"></i>
                </a>

                <a href="{{$links->extra_field_4}}" class="text-white">
                    <i class="fab fa-instagram"></i>
                </a>

                <a href="{{$links->extra_field_5}}" class="text-white">
                    <i class="fab fa-youtube"></i>
                </a>

            </div>

        </div>

        <!-- COPYRIGHT -->
        <div class="text-center mt-5 pt-3 border-top border-secondary small text-secondary">
            &copy; {{date('Y')}} YessTravel | {{ __('frontend.all_rights_reserved') }}
        </div>

    </div>

</footer>
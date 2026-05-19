<div class="header-wrap sticky-top"
     style="background: rgba(255,255,255,0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 6px 0;">

    <div class="container">

        <div class="row align-items-center">

            <!-- LOGO -->
            <div class="col-lg-2 navbar-light">

                <div class="logo">
                    <a href="{{url('/')}}">
                        <img src="{{asset('images/logo/YessTrav.png')}}"
                             style="height: 38px; width:auto;">
                    </a>
                </div>

                <button class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        style="padding: 4px 8px;">
                    <i class="fas fa-bars"></i>
                </button>

            </div>

            <!-- NAVBAR -->
            <div class="col-lg-10">

                <nav class="navbar navbar-expand-lg navbar-light"
                     style="padding: 0;">

                    <!-- COLLAPSE -->
                    <div class="collapse navbar-collapse ms-auto rounded-4"
                         id="navbarSupportedContent"
                         style="padding: 6px 10px;">

                        <button class="close-toggler" type="button"
                                style="padding: 2px 6px;">
                            <span><i class="fas fa-times-circle"></i></span>
                        </button>

                        <ul class="navbar-nav ms-auto align-items-center">

                            <!-- HOME -->
                            <li class="nav-item ps-2">
                                <a href="{{url('/')}}" class="nav-link py-1 fw-semibold">
                                    {{ __('frontend.home') }}
                                </a>
                            </li>

                            {!!get_menus(1)!!}

                            @if(auth()->user())

                            <!-- USER -->
                            <li class="nav-item dropdown ps-2">

                                <a href="#" class="nav-link dropdown-toggle py-1"
                                   data-bs-toggle="dropdown">

                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img src="{{ Auth::user()->profile_photo_url }}"
                                             class="rounded-circle shadow-sm"
                                             style="width:32px;height:32px;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{Auth::user()->name[0]}}"
                                             class="rounded-circle shadow-sm"
                                             style="width:32px;height:32px;">
                                    @endif

                                </a>

                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2"
                                    style="background: rgba(255,255,255,0.7);
                                           backdrop-filter: blur(12px);">

                                    <li><a class="dropdown-item py-1" href="{{url('/dashboard')}}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a></li>

                                    <li><a class="dropdown-item py-1" href="{{ route('profile.show') }}">
                                        <i class="fas fa-edit me-2"></i> Profile
                                    </a></li>

                                    <li><a class="dropdown-item py-1" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a></li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                </ul>

                            </li>

                            @endif

                            <!-- LANGUAGE -->
                            <li class="nav-item ps-2">
                                @include('components.language-switcher')
                            </li>

                        </ul>

                        @if(!auth()->user())

                        <!-- BUTTONS -->
                        <a class="btn btn-outline-dark ms-2 px-2 py-1 rounded-3"
                           href="{{url('/login')}}">
                            Login
                        </a>

                        <a class="btn btn-primary ms-2 px-2 py-1 rounded-3"
                           href="{{url('/register')}}">
                            Register
                        </a>

                        @endif

                    </div>

                </nav>

            </div>

        </div>

    </div>

</div>
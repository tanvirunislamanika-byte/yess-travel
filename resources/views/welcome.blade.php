<x-app-layout>
    <?php $widget = widget(31); ?>
    <?php $img = asset('images/' . $widget->extra_image_1); ?>
    <div id="home" class="parallax-section" style="background: url({{ $img }}) no-repeat;">
        <!--     <div class="overlay"></div>-->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="slide-text">
                        <h1>{{ $widget->getTranslatedExtraField(1) }}</h1>
                        <p>{{ $widget->getTranslatedDescription() }}</p>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#flightsearch" type="button" role="tab"
                                aria-controls="flight-tab-pane" aria-selected="true"><i class="fas fa-plane"></i>
                                {{ __('frontend.flights') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#hotelsearch"
                                type="button" role="tab" aria-controls="hotel-tab-pane" aria-selected="false"><i
                                    class="fas fa-hotel"></i> {{ __('frontend.hotels') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" href="{{ url('/tours') }}"><i
                                    class="fas fa-tree"></i> {{ __('frontend.tours') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="flightsearch" role="tabpanel"
                            aria-labelledby="flight-tab" tabindex="0">
                           <form action="{{ url('/flights/search') }}" method="GET">

    <div class="searchintbox rounded-4 shadow-lg p-4"
         style="
            background: rgba(255, 255, 255, 0.54);
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
         ">

        <!-- TRIP TYPE -->
        <div class="tripetype mb-3" role="group">

            <input type="radio" class="btn-check" name="triptype"
                   id="onewayflight" value="oneway" checked>

            <label class="btn btn-outline-light rounded-pill px-4"
                   for="onewayflight">
                {{ __('frontend.one_way') }}
            </label>

            <input type="radio" class="btn-check" name="triptype"
                   id="twowayflight" value="twoway">

            <label class="btn btn-outline-light rounded-pill px-4"
                   for="twowayflight">
                {{ __('frontend.round_trip') }}
            </label>

            <input type="radio" class="btn-check" name="triptype"
                   id="Multi-city" value="Multicity">

            <label class="btn btn-outline-light rounded-pill px-4"
                   for="Multi-city">
                {{ __('frontend.multi_city') }}
            </label>

        </div>

        @php
            $slices = request('slices');
        @endphp

        <!-- SINGLE CITY -->
        <div class="row single-city g-3">

            <!-- FROM -->
            <div class="col-md">

                <label class="text-white mb-2">
                    {{ __('frontend.from') }}
                </label>

                <input type="text"
                       class="form-control bg-transparent text-white border-light from_location"
                       name="slices[0][from_location]"
                       id="from_location"
                       placeholder="{{ request('from_type') == 'code' ? 'e.g. LHR' : 'e.g. London' }}"
                       value="{{ $slices[0]['from_location'] ?? '' }}"
                       style="height: 50px;">

                <input type="hidden"
                       name="slices[0][from]"
                       class="from_code"
                       id="from_code"
                       value="{{ $slices[0]['from'] ?? '' }}">

            </div>

            <!-- TO -->
            <div class="col-md">

                <label class="text-white mb-2">
                    {{ __('frontend.to') }}
                </label>

                <input type="text"
                       class="form-control bg-transparent text-white border-light to_location"
                       name="slices[0][to_location]"
                       id="to_location"
                       placeholder="{{ request('to_type') == 'code' ? 'e.g. JFK' : 'e.g. New York' }}"
                       value="{{ $slices[0]['to_location'] ?? '' }}"
                       style="height: 50px;">

                <input type="hidden"
                       name="slices[0][to]"
                       id="to_code"
                       class="to_code"
                       value="{{ $slices[0]['to'] ?? '' }}">

            </div>

            <!-- DATE -->
            <div class="col-md">

                <label class="text-white mb-2">
                    {{ __('frontend.travelling_on') }}
                </label>

                <input type="text"
                       name="slices[0][travelling_date]"
                       id="travelling_date"
                       class="form-control bg-transparent text-white border-light travelling_date"
                       placeholder="{{ __('frontend.select_from_date') }}"
                       value="{{ $slices[0]['travelling_date'] ?? '' }}"
                       autocomplete="off"
                       style="height: 50px;">

            </div>

            <!-- RETURN -->
            <div class="col-md return-date"
                 @if (request('triptype') != 'twoway') style="display:none;" @endif>

                <label class="text-white mb-2">
                    {{ __('frontend.return') }}
                </label>

                <input name="return_date"
                       type="text"
                       id="return_date"
                       class="form-control bg-transparent text-white border-light"
                       placeholder="{{ __('frontend.select_return_date') }}"
                       value="{{ request('return_date') }}"
                       autocomplete="off"
                       style="height: 50px;">

            </div>

        </div>

        <!-- MULTI CITY -->
        <div class="multiple-city mt-3"></div>

        <!-- BOTTOM SECTION -->
        <div class="row mt-4 g-3 align-items-end">

            <!-- ADD FLIGHT -->
            <div class="col-md-3 mutiple-button me-auto"
                 @if (request('triptype') != 'Multicity') style="display:none;" @endif>

                <button type="button"
                        id="add-city"
                        class="btn btn-outline-light rounded-pill px-4 w-100">
                    {{ __('frontend.add_flight') }}
                </button>

            </div>

            <!-- CABIN -->
            <div class="col-md-2">

                <label class="text-white mb-2">
                    {{ __('frontend.cabin_class') }}
                </label>

                <select class="form-control bg-transparent text-white border-light"
                        name="cabin_class"
                        style="height: 50px;">

                    <option value="" class="text-dark">
                        {{ __('frontend.any_class') }}
                    </option>

                    <option value="economy" class="text-dark">
                        {{ __('frontend.economy') }}
                    </option>

                    <option value="premium_economy" class="text-dark">
                        {{ __('frontend.premium_economy') }}
                    </option>

                    <option value="business" class="text-dark">
                        {{ __('frontend.business') }}
                    </option>

                    <option value="first" class="text-dark">
                        {{ __('frontend.first_class') }}
                    </option>

                </select>

            </div>

            <!-- ADULT -->
            <div class="col-md-2">

                <label class="text-white mb-2">
                    {{ __('frontend.adults') }}
                </label>

                <input type="number"
                       class="form-control bg-transparent text-white border-light"
                       name="adults"
                       min="1"
                       max="9"
                       value="{{ request('adults', 1) }}"
                       style="height: 50px;">

            </div>

            <!-- CHILD -->
            <div class="col-md-2">

                <label class="text-white mb-2">
                    {{ __('frontend.children') }}
                </label>

                <input type="number"
                       class="form-control bg-transparent text-white border-light"
                       name="children"
                       min="0"
                       max="9"
                       value="{{ request('children', 0) }}"
                       style="height: 50px;">

            </div>

            <!-- SEARCH -->
            <div class="col-md-2">

                <button class="btn btn-primary w-100 rounded-pill shadow"
                        style="height: 50px;">
                    <i class="fas fa-search me-2"></i>
                    {{ __('frontend.search') }}
                </button>

            </div>

        </div>

    </div>

</form>
                        </div>
                        <div class="tab-pane fade" id="hotelsearch" role="tabpanel" aria-labelledby="hotel-tab"
                            tabindex="0">
                          <form method="get" action="{{ url('/hotels') }}">

    <div class="searchintbox rounded-4 shadow-lg p-4"
         style=" background: rgba(255, 255, 255, 0.52)">

        <div class="row align-items-end g-3">

            <!-- DESTINATION -->
            <div class="col-lg-7">

                <div>
                    <label class="fw-semibold text-dark mb-2">
                        {{ __('frontend.destination') }}
                    </label>

                    <input type="text"
                           class="form-control border-0 shadow-sm"
                           name="keyword"
                           placeholder="{{ __('frontend.destination_placeholder') }}"
                           style="height: 50px;">
                </div>

            </div>

            <!-- DATE -->
            <div class="col-lg-3">

                <div>
                    <label class="fw-semibold text-dark mb-2">
                        {{ __('frontend.when') }}
                    </label>

                    <input type="date"
                           class="form-control border-0 shadow-sm"
                           name=""
                           placeholder="{{ __('frontend.when') }}"
                           style="height: 50px;">
                </div>

            </div>

            <!-- BUTTON -->
            <div class="col-lg-2">

                <button class="btn btn-primary w-100 shadow-sm"
                        type="submit"
                        style="height: 50px;">
                    <i class="fas fa-search me-2"></i>
                    {{ __('frontend.search') }}
                </button>

            </div>

        </div>

    </div>

</form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Top Destination Section -->
     <div class="container">
     <div class="fade-text">
                        <h3>{{ $widget->getTranslatedExtraField(2) }}
                            <a href="" class="typewrite" data-period="2000"
                                data-type='[ "{{ $widget->getTranslatedExtraField(3) }}", "{{ $widget->getTranslatedExtraField(4) }}", "{{ $widget->getTranslatedExtraField(5) }}", "{{ $widget->getTranslatedExtraField(6) }}" ]'>
                                <span class="wrap"></span> </a>
                        </h3>
                    </div>
     </div>
    <div class="parallax-section pt-5" id="places">
        <?php $widget = widget(7); ?>
        <div class="container">
            <div class="section-title">
                <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                <p>{{ $widget->getTranslatedDescription() }}</p>
            </div>
            <div class="row topdesti">
                @if (null !== ($locations = module(19)))
                    @foreach ($locations as $location)
                        <?php $num_hotels = App\Models\ModulesData::select('id')->where('extra_field_24', $location->id)->count(); ?>
                        <div class="col-lg-2 col-md-3 col-6">
                            <div class="destibox">
                                <a href="{{ url('/hotels?destination=' . $location->id) }}">
                                    <div class="destimg"><img src="{{ asset('images/' . $location->image) }}"
                                            alt=""></div>
                                    <h4>{{ $location->title }}</h4>
                                    <p>{{ $num_hotels }} {{ __('frontend.listings') }}</p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @if (null !== ($arilines = moduleF(4)))
        <!-- Popular Flights -->
        <div class="popflights pb-5">
            <div class="container">
                <div class="section-title">
                    <h3>{{ __('frontend.popular_airlines') }}</h3>
                    <p>{{ __('frontend.popular_airlines_description') }}</p>
                </div>
                <ul class="row topaircompany">
                    @foreach ($arilines as $airline)
                        <li class="col-lg-2 mb-3">
                            <div class="arilinebox">
                            <img src="{{ asset('images/' . $airline->image) }}">
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <!-- Widgets -->
    <div class="container pt-5">
        <div class="row">
            <div class="col-lg-6">
                <?php $widget = widget(5); ?>
                <?php $img = asset('images/' . $widget->extra_image_1); ?>
                <div class="hotelwidget" style="background: url({{ $img }}) no-repeat;">
                    <h2>{{ $widget->getTranslatedExtraField(1) }}</h2>
                    <h3>{{ $widget->getTranslatedDescription() }}</h3>
                    <a href="#" class="btn btn-sec">{{ __('frontend.book_now') }}</a>
                </div>
            </div>
            <div class="col-lg-6">
                <?php $widget = widget(6); ?>
                <?php $img = asset('images/' . $widget->extra_image_1); ?>
                <div class="hotelwidget" style="background: url({{ $img }}) no-repeat;">
                    <h2>{{ $widget->getTranslatedExtraField(1) }}</h2>
                    <h3>{{ $widget->getTranslatedDescription() }}</h3>
                    <a href="#" class="btn btn-sec">{{ __('frontend.book_now') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Hotels Section -->
    <div class="parallax-section" id="places">
        <?php $widget = widget(4); ?>
        <div class="container">
            <div class="section-title">
                <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                <p>{{ $widget->getTranslatedDescription() }}</p>
            </div>
            <div class="destinationList">
                <ul class="owl-carousel owl-theme hotelslist">
                    @if (null !== ($hotels = module(1)))
                        @foreach ($hotels as $hotel)
                            <li class="item">
                                <div class="locwrap">
                                    <div class="imgbox"><a href="{{ route('hotel.detail', $hotel->slug) }}"
                                            class="image-popup"><img src="{{ asset('images/' . $hotel->image) }}"
                                                alt=""></a></div>
                                    <h3>{{ $hotel->title }}</h3>
                                    <?php
                                    $averageRating = $hotel->reviews()->avg('rating');
                                    $averageRating = number_format($averageRating, 1);
                                    ?>
                                    <div class="comprating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i <= $averageRating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i>
                                        {{ $hotel->extra_field_18 }}</div>
                                    <div class="prices">${{ $hotel->extra_field_1 }}</div>
                                    <div class="meta">
                                        <span title="Hotel Type"><i class="fa fa-hotel" aria-hidden="true"></i>
                                            <strong>{{ title($hotel->extra_field_2) }}</strong></span>
                                        <span title="People"><i class="fa fa-users" aria-hidden="true"></i>
                                            <strong>{{ $hotel->extra_field_11 }}</strong></span>
                                        <span title="Room Type"><i class="fa fa-star" aria-hidden="true"></i>
                                            <strong>{{ title($hotel->extra_field_23) }}</strong></span>
                                    </div>
                                    <a href="{{ route('hotel.detail', $hotel->slug) }}" class="btn btn-white">{{ __('frontend.view_details') }}</a>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="text-center"><a href="{{ url('hotels') }}" class="btn btn-primary">{{ __('frontend.view_all_hotels') }}</a></div>
        </div>
    </div>

    <div class="appwraper text-center">
        <div class="container">
            <img src="images/mobile-app.jpg" alt="Mobile App" class="rounded-4">
        </div>
    </div>

    <!-- Latest Tours Section -->
    <div class="parallax-section" id="latest-tours">
        <div class="container">
            <div class="section-title">
                <h3>{{ __('frontend.latest_tours') }}</h3>
                <p>{{ __('frontend.discover_amazing_tours') }}</p>
            </div>
            <div class="row">
                @if (null !== ($latestTours = \App\Models\ModulesData::where('module_id', 34)->where('status', 'active')->orderBy('created_at', 'desc')->limit(3)->get()))
                    @foreach ($latestTours as $tour)
                        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                            <div class="tour-card">
                                <div class="tour-card-image">
                                    <a href="{{ route('tour.detail', $tour->slug) }}" class="tour-image-link">
                                        @if($tour->image)
                                            <img src="{{ asset('images/' . $tour->image) }}" alt="{{ $tour->getTranslatedTitle() }}" class="tour-img">
                                        @else
                                            <img src="{{ asset('images/tour-placeholder.jpg') }}" alt="{{ $tour->getTranslatedTitle() }}" class="tour-img">
                                        @endif
                                    </a>
                                    
                                    <!-- Tour Badges -->
                                    <div class="tour-badges">
                                        @if($tour->extra_field_3)
                                            <span class="tour-badge tour-days-badge">
                                                <i class="fas fa-calendar-day"></i>
                                                {{ $tour->getTranslatedExtraField(3) }} {{ __('frontend.days') }}
                                            </span>
                                        @endif
                                        @if($tour->departureCountry)
                                            <span class="tour-badge tour-location-badge">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $tour->departureCity->name ?? 'N/A' }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Tour Type Badge -->
                                    @if($tour->tourType)
                                        <div class="tour-type-badge">
                                            <span>{{ $tour->tourType->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="tour-card-content">
                                    <div class="tour-card-header">
                                        <h3 class="tour-card-title">
                                            <a href="{{ route('tour.detail', $tour->slug) }}">{{ $tour->getTranslatedTitle() }}</a>
                                        </h3>
                                    </div>

                                    <div class="tour-card-meta">
                                        @if($tour->extra_field_1)
                                            <div class="tour-meta-item">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>{{ __('frontend.starts') }}: {{ date('d M Y', strtotime($tour->getTranslatedExtraField(1))) }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($tour->extra_field_2)
                                            <div class="tour-meta-item">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>{{ __('frontend.ends') }}: {{ date('d M Y', strtotime($tour->getTranslatedExtraField(2))) }}</span>
                                            </div>
                                        @endif

                                        @if($tour->extra_field_4)
                                            <div class="tour-meta-item">
                                                <i class="fas fa-moon"></i>
                                                <span>{{ $tour->getTranslatedExtraField(4) }} {{ __('frontend.nights') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="tour-card-footer">
                                        <div class="tour-price-section">
                                            @if($tour->extra_field_8)
                                                <div class="tour-price">
                                                    <span class="price-amount">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->getTranslatedExtraField(8)) }}</span>
                                                    <span class="price-unit">{{ __('frontend.per_person') }}</span>
                                                </div>
                                            @else
                                                <div class="tour-price">
                                                    <span class="price-amount">{{ __('frontend.contact_for_price') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="tour-actions">
                                            <a href="{{ route('tour.detail', $tour->slug) }}" class="btn btn-primary btn-sm">
                                                {{ __('frontend.view_details') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-center">
                <a href="{{ url('tours') }}" class="btn btn-primary">{{ __('frontend.view_all_tours') }}</a>
            </div>
        </div>
    </div>

    <!-- About section -->
    <div id="about">
        <div class="container">
            <div class="about-desc">
                <div class="row">
                    <div class="col-lg-7">
                        <?php $widget = widget(9); ?>
                        <?php $img = asset('images/' . $widget->extra_image_1); ?>
                        <div class="section-title">
                            <div class="subtitle">{{ $widget->getTranslatedExtraField(2) }}</div>
                            <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                        </div>
                        {!! $widget->getTranslatedDescription() !!}
                    </div>
                    <div class="col-lg-5">
                        <div class="postimg"><img src="{{ $img }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="parallax-section bg-grey">
        <div class="container">
            <ul class="circleList row">
                <li class="col-md-3 col-sm-4">
                    <?php $widget = widget(10); ?>
                    <div class="cricle">{!! $widget->getTranslatedExtraField(2) !!}</div>
                    <div class="title">{{ $widget->getTranslatedExtraField(1) }}</div>
                    <p>{!! $widget->getTranslatedDescription() !!}</p>
                </li>
                <li class="col-md-3 col-sm-4">
                    <?php $widget = widget(11); ?>
                    <div class="cricle">{!! $widget->getTranslatedExtraField(2) !!}</div>
                    <div class="title">{{ $widget->getTranslatedExtraField(1) }}</div>
                    <p>{!! $widget->getTranslatedDescription() !!}</p>
                </li>
                <li class="col-md-3 col-sm-4">
                    <?php $widget = widget(12); ?>
                    <div class="cricle">{!! $widget->getTranslatedExtraField(2) !!}</div>
                    <div class="title">{{ $widget->getTranslatedExtraField(1) }}</div>
                    <p>{!! $widget->getTranslatedDescription() !!}</p>
                </li>
                <li class="col-md-3 col-sm-4">
                    <?php $widget = widget(13); ?>
                    <div class="cricle">{!! $widget->getTranslatedExtraField(2) !!}</div>
                    <div class="title">{{ $widget->getTranslatedExtraField(1) }}</div>
                    <p>{!! $widget->getTranslatedDescription() !!}</p>
                </li>
            </ul>
        </div>
    </div>
    <!-- Search Filter -->
    <div class="searchfilter parallax-section pb-0">
        <div class="container">
            <div class="row">
                @if (null !== ($cities = toCities()))
                    <div class="col-lg-6">
                        <h4>{{ __('frontend.flights_to_top_cities') }}</h4>
                        <ul class="row txtfilter">
                            @foreach ($cities as $city)
                                <li class="col-lg-4"><a href="{{ url('/flights?to_location=' . $city) }}"><i
                                            class="fas fa-caret-right"></i> {{ $city }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (null !== ($countries = toCountries()))
                    <div class="col-lg-6">
                        <h4>{{ __('frontend.flights_by_top_countries') }}</h4>
                        <ul class="row txtfilter">
                            @foreach ($countries as $key => $country)
                                <li class="col-lg-4"><a href="{{ url('/flights?to_country=' . $key) }}"><i
                                            class="fas fa-caret-right"></i> {{ $country }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="searchfilter parallax-section pt-4 pb-5">
        <div class="container">
            <div class="row">
                @if (null !== ($cities = toCitiesH()))
                    <div class="col-lg-6">
                        <h4>{{ __('frontend.hotels_to_top_cities') }}</h4>
                        <ul class="row txtfilter">
                            @foreach ($cities as $city)
                                <li class="col-lg-4"><a href="{{ url('/hotels?searchlocation=' . $city) }}"><i
                                            class="fas fa-caret-right"></i> {{ $city }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (null !== ($countries = toCountriesH()))
                    <div class="col-lg-6">
                        <h4>{{ __('frontend.hotels_to_top_countries') }}</h4>
                        <ul class="row txtfilter">
                            @foreach ($countries as $key => $country)
                                <li class="col-lg-4"><a href="{{ url('/hotels?location=' . $key) }}"><i
                                            class="fas fa-caret-right"></i> {{ $country }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Search Filter End -->
    <!-- Counter Section -->
    <div id="counter">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-6 counter-item">
                    <?php $widget = widget(14); ?>
                    <div class="counterbox">
                        <div class="counter-icon">{!! $widget->getTranslatedExtraField(2) !!}</div>
                        <span class="counter-number" data-from="1" data-to="{{ $widget->getTranslatedExtraField(1) }}"
                            data-speed="1000"></span> <span class="counter-text">{{ __('frontend.happy_client') }}</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6 counter-item">
                    <?php $widget = widget(15); ?>
                    <div class="counterbox">
                        <div class="counter-icon">{!! $widget->getTranslatedExtraField(2) !!}</div>
                        <span class="counter-number" data-from="1" data-to="{{ $widget->getTranslatedExtraField(1) }}"
                            data-speed="1000"></span> <span class="counter-text">{{ __('frontend.cars') }}</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6 counter-item">
                    <?php $widget = widget(16); ?>
                    <div class="counterbox">
                        <div class="counter-icon">{!! $widget->getTranslatedExtraField(2) !!}</div>
                        <span class="counter-number" data-from="1" data-to="{{ $widget->getTranslatedExtraField(1) }}"
                            data-speed="1000"></span> <span class="counter-text">{{ __('frontend.destinations') }}</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-6 counter-item">
                    <?php $widget = widget(17); ?>
                    <div class="counterbox">
                        <div class="counter-icon">{!! $widget->getTranslatedExtraField(2) !!}</div>
                        <span class="counter-number" data-from="1" data-to="{{ $widget->getTranslatedExtraField(1) }}"
                            data-speed="1000"></span> <span class="counter-text">{{ __('frontend.awards') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service 1 -->
    <?php $widget = widget(18); ?>
    <div class="homevideobox">
        <div class="ratio ratio-16x9">
            <iframe
                src="{{ $widget->getTranslatedExtraField(2) }}?autoplay=1&loop=1&controls=0&mute=1&rel=0&modestbranding=1&playlist={{ basename($widget->getTranslatedExtraField(2)) }}"
                title="YouTube video" allow="autoplay" allowfullscreen>
            </iframe>
        </div>
        <div class="homevideocontent">
            <div class="container">
                <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                <p>{{ $widget->getTranslatedDescription() }}</p>
            </div>
        </div>
    </div>
    <!-- Service Section -->
    <div id="service" class="parallax-section">
        <?php $widget = widget(19); ?>
        <div class="container">
            <div class="section-title">
                <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                <p>{{ $widget->getTranslatedDescription() }}</p>
            </div>
            <div class="row">
                <!-- Service 1 -->
                @if (null !== ($services = module(28)))
                    @foreach ($services as $service)
                        <div class="col-md-4 col-sm-6">
                            <div class="service-thumb">
                                <div class="thumb-icon">{!! $service->extra_field_1 !!}</div>
                                <h4>{!! $service->title !!}</h4>
                                <p>{!! Str::limit($service->description, 140) !!}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- Tagline Section -->
    <div class="taglinewrap">
        <?php $widget = widget(20); ?>
        <div class="container">
            <h4>{{ $widget->getTranslatedExtraField(2) }}</h4>
            <h2>{{ $widget->getTranslatedExtraField(1) }}</h2>
            <p>{{ $widget->getTranslatedDescription() }} </p>
            <a href="{{ url('contact-us') }}">{{ __('frontend.contact_us') }} <i class="fas fa-long-arrow-right"></i></a>
        </div>
    </div>
    <!-- Team Section -->
    <div id="team" class="parallax-section">
        <?php $widget = widget(21); ?>
        <div class="container">
            <div class="section-title">
                <h3>{{ $widget->getTranslatedExtraField(1) }}</h3>
                <p>{{ $widget->getTranslatedDescription() }}</p>
            </div>
            <div class="row">
                <!-- team 1 -->
                @if (null !== ($team = module(30)))
                    @foreach ($team as $member)
                        <div class="col-md-3 col-sm-6">
                            <div class="team-thumb">
                                <div class="thumb-image"><img src="{{ asset('images/' . $member->image) }}"
                                        alt=""></div>
                                <h4>{!! $member->title !!}</h4>
                                <h5>{!! $member->extra_field_1 !!}</h5>
                                <div class="contct"><i class="fas fa-phone-alt"></i> {!! $member->extra_field_2 !!}</div>
                                <div class="contct"><i class="fas fa-envelope"></i> {!! $member->extra_field_3 !!}</div>
                                <ul class="list-inline social">
                                    <li> <a href="{!! $member->extra_field_4 !!}" class="bg-twitter"><i
                                                class="fab fa-twitter"></i></a> </li>
                                    <li> <a href="{!! $member->extra_field_5 !!}" class="bg-facebook"><i
                                                class="fab fa-facebook-f"></i></a> </li>
                                    <li> <a href="{!! $member->extra_field_6 !!}" class="bg-linkedin"><i
                                                class="fab fa-linkedin-in"></i></a> </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- Testimonials Section -->
    <div id="testimonials">
        <div class="container">
            <!-- Section Title -->
            <div class="section-title">
                <h3>{{ __('frontend.testimonials') }}</h3>
            </div>
            <ul class="owl-carousel owl-theme testimonialsList">
                <!-- Client -->
                @if (null !== ($testimonials = module(31)))
                    @foreach ($testimonials as $testimonial)
                        <li class="item">
                            <div class="testibox">
                                <div class="rating">
                                    @for ($i = 1; $i <= intval(title($testimonial->extra_field_2)); $i++)
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <p>"{!! strip_tags($testimonial->getTranslatedDescription()) !!}"</p>
                                <div class="clientname">{!! $testimonial->getTranslatedTitle() !!}</div>
                                <div class="clientinfo">{!! $testimonial->getTranslatedExtraField(1) !!}</div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <!-- Blog Section -->
    <div class="hmblog parallax-section">
        <div class="container">
            <!-- SECTION TITLE -->
            <div class="section-title">
                <h3>{{ __('frontend.latest_from_blog') }}</h3>
                <p>{{ __('frontend.latest_from_blog_description') }}</p>
            </div>
            <div class="row">
                @if (null !== ($blogs = module(23)))
                    @foreach ($blogs->sortByDesc('created_at')->take(3) as $service)
                        <div class="col-lg-4">
                            <div class="subposts">
                                <div class="postimg">
                                    <img src="{{ asset('images/' . $service->image) }}">
                                </div>
                                <div class="postinfo">
                                    <h3>
                                        <a href="{{ route('blogs.detail', $service->slug) }}" class="pageLnks">
                                            {{ $service->getTranslatedTitle() }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="date">
                                    {{ date('d M Y', strtotime($service->created_at)) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @push('js')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        <script>
            function initDatepickers() {
                $('.travelling_date').each(function() {
                    // Prevent duplicate initialization
                    if (!$(this).hasClass('hasDatepicker')) {
                        $(this).datepicker({
                            dateFormat: 'yy-mm-dd',
                            minDate: 0
                        });
                    }
                });
            }

            function setupAutocomplete(inputSelector, codeSelector, typeSelector) {
                $(inputSelector).each(function() {
                    const $input = $(this);
                    const $code = $input.closest('.city-row, .single-city').find(codeSelector);
                    const $type = $(typeSelector); // or pass static value
                    $input.autocomplete({
                        source: function(request, response) {
                            const type = $type.val() || 'code';
                            $.ajax({
                                url: "{{ route('flights.search.airports') }}",
                                dataType: "json",
                                data: {
                                    query: request.term,
                                    type: type
                                },
                                success: function(data) {
                                    response(data);
                                }
                            });
                        },
                        minLength: 2,
                        select: function(event, ui) {
                            $input.val(ui.item.value);
                            $code.val(ui.item.code);
                            return false;
                        },
                        focus: function(event, ui) {
                            $input.val(ui.item.value);
                            return false;
                        }
                    });
                });
            }

            // --- ADDED LOGIC FOR DATE DEPENDENCIES ---
            function updateReturnMinDate() {
                let tripType = $('input[name="triptype"]:checked').val();
                let firstDate = $('.single-city .travelling_date').first().val();
                if (tripType === 'twoway' && firstDate) {
                    $('#return_date').datepicker('option', 'minDate', firstDate);
                } else {
                    $('#return_date').datepicker('option', 'minDate', 0);
                }
            }

            function updateMultiCityMinDates() {
                $('.multiple-city .single-city').each(function(index) {
                    if (index === 0) return; // skip the first, it's handled by main
                    let prevDate = $(this).prev('.single-city').find('.travelling_date').val();
                    let $currentDate = $(this).find('.travelling_date');
                    if (prevDate) {
                        $currentDate.datepicker('option', 'minDate', prevDate);
                    } else {
                        $currentDate.datepicker('option', 'minDate', 0);
                    }
                });
            }

            $(document).on('change', '.single-city .travelling_date', function() {
                updateReturnMinDate();
                updateMultiCityMinDates();
            });

            // Also call these after adding a new city
            $(document).on('click', '#add-city', function() {
                setTimeout(function() {
                    updateMultiCityMinDates();
                }, 100); // slight delay to ensure DOM is updated
            });

            // Call once on page load
            $(document).ready(function() {
                updateReturnMinDate();
                updateMultiCityMinDates();
            });
            // --- END ADDED LOGIC ---
        </script>

        <script>
            $(document).ready(function() {
                initDatepickers();
                setupAutocomplete('.from_location', '.from_code', '#from_type');
                setupAutocomplete('.to_location', '.to_code', '#to_type');
            });
            $(document).ready(function() {

                $('input[name="triptype"]').change(function() {
                    if ($(this).val() === 'twoway') {
                        $('.multiple-city').empty();
                        $(".mutiple-button,.hidden-button").hide();
                        $('.return-date').show();
                        $('#return_date').prop('required', true);
                    } else if ($(this).val() === 'oneway') {
                        $('.return-date').hide();
                        $(".mutiple-button,.hidden-button").hide();
                        $('.multiple-city').empty();
                        $('#return_date').prop('required', false);
                        $('#return_date').val('');
                    } else {
                        $(".mutiple-button,.hidden-button").show();
                        $('.return-date').hide();
                        $('#return_date').prop('required', false);
                        $('#return_date').val('');
                        if ($('.multiple-city .single-city').length === 0) {
                            addCityRow();
                        }
                    }
                });

                $('#return_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: 0
                });

                $('.select-flight-btn').click(function() {
                    const offerId = $(this).data('offer-id');
                    const origin = $(this).data('origin');
                    const destination = $(this).data('destination');
                    // Show loading state
                    $(this).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('frontend.processing') }}'
                    );
                    $(this).prop('disabled', true);
                    // Store the location codes in session storage
                    sessionStorage.setItem('origin_code', origin);
                    sessionStorage.setItem('destination_code', destination);
                    // Redirect to booking form
                    window.location.href = "{{ route('flights.booking.form', '') }}/" + offerId;
                });
                // Form submission validation

                $('form').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let hasError = false;


                    form.find('.text-danger.error-msg').remove();


                    form.find('.from_location:visible').each(function() {
                        let $input = $(this);
                        let fromCode = $input.closest('.single-city').find('.from_code').val();
                        if (!$input.val() || !fromCode) {
                            showError($input, '{{ __('frontend.please_select_valid_origin') }}');
                            hasError = true;
                        }
                    });


                    form.find('.to_location:visible').each(function() {
                        let $input = $(this);
                        let toCode = $input.closest('.single-city').find('.to_code').val();
                        if (!$input.val() || !toCode) {
                            showError($input, '{{ __('frontend.please_select_valid_destination') }}');
                            hasError = true;
                        }
                    });

                    let previousDate = null;

                    form.find('.travelling_date:visible').each(function() {
                        let $input = $(this);
                        let dateVal = $input.val();

                        if (!dateVal) {
                            showError($input, '{{ __('frontend.please_select_departure_date') }}');
                            hasError = true;
                            return;
                        }

                        let currentDate = new Date(dateVal);

                        if (previousDate !== null && currentDate < previousDate) {
                            showError($input, '{{ __('frontend.date_cannot_be_earlier') }}');
                            hasError = true;
                        }

                        previousDate = currentDate;
                    });



                    let tripType = $('input[name="triptype"]:checked').val();
                    let returnDateInput = $('#return_date');
                    if (tripType === 'twoway' && returnDateInput.is(':visible') && !returnDateInput.val()) {
                        showError(returnDateInput, '{{ __('frontend.please_select_return_date') }}');
                        hasError = true;
                    }

                    if (hasError) return false;


                    form.find('button[type="submit"]').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('frontend.searching') }}'
                    ).prop('disabled', true);

                    this.submit();

                    function showError($input, message) {
                        let errorDiv = $('<div class="text-danger mt-1 error-msg">' + message + '</div>');
                        $input.closest('.mt-3').append(errorDiv);
                    }
                });

                $(document).on('click', '#add-city', function() {
                    if ($('.single-city').length < 6) {
                        addCityRow();
                    }
                    checkFlightLimit();
                });

                $(document).on('click', '.remove-city', function() {
                    $(this).closest('.single-city').remove();
                    checkFlightLimit();
                });

                checkFlightLimit();
            });

            function addCityRow() {
                const sliceIndex = $('.multiple-city .single-city').length + 1;
                let new_html = `
            <div class="row single-city align-items-end">
            <div class="col-12"><h4 class="newflighttitle">{{ __('frontend.add_new_flight_details') }}</h4></div>
                <div class="col">
                    <div class="mt-3">
                        <label>From</label>
                        <input type="text" class="form-control from_location" name="slices[${sliceIndex}][from_location]"
                            placeholder="e.g. London">
                        <input type="hidden" name="slices[${sliceIndex}][from]" class="from_code" value="">
                    </div>
                </div>
                <div class="col">
                    <div class="mt-3">
                        <label>To</label>
                        <input type="text" class="form-control to_location" name="slices[${sliceIndex}][to_location]"
                            placeholder="e.g. New York">
                        <input type="hidden" name="slices[${sliceIndex}][to]" class="to_code" value="">
                    </div>
                </div>
                <div class="col">
                    <div class="mt-3">
                        <label>Travelling On</label>
                        <input type="text" name="slices[${sliceIndex}][travelling_date]" class="form-control travelling_date"
                            placeholder="Select From Date" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-city w-100" style="visibility: visible;"
                                                                                title="{{ __('frontend.remove_this_city') }}">
                            &times;
                        </button>
                    </div>
                </div>
            </div>`;

                $('.multiple-city').append(new_html);
                // Setup autocomplete for new fields
                initDatepickers();
                setupAutocomplete('.multiple-city .from_location:last', '.from_code:last', '#from_type');
                setupAutocomplete('.multiple-city .to_location:last', '.to_code:last', '#to_type');
            }

            function checkFlightLimit() {
                if ($('.single-city').length >= 6) {
                    $('#add-city').prop('disabled', true);
                } else {
                    $('#add-city').prop('disabled', false);
                }
            }
        </script>
    @endpush
</x-app-layout>

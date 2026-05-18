<x-app-layout>

 <!-- Tour Header -->
 <div class="tour-detail-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('frontend.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tours.list') }}">{{ __('frontend.tours') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $tour->getTranslatedTitle() }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>       
               
</div>

    <section class="tour-detail">
        <div class="container">
           

            <!-- Tour Content -->
            <div class="tour-content">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="tour-title">{{ $tour->getTranslatedTitle() }}</h1>
                        <div class="tour-meta">
                            @if($tour->departureCity && $tour->departureCountry)
                                <span class="departure-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ __('frontend.departure') }} {{ $tour->departureCity->name }}, {{ $tour->departureCountry->name }}
                                </span>
                            @endif
                            
                            @if($tour->extra_field_3 && $tour->extra_field_4)
                                <span class="duration">
                                    <i class="fas fa-clock"></i>
                                    {{ $tour->getTranslatedExtraField(3) }} {{ __('frontend.days') }} & {{ $tour->getTranslatedExtraField(4) }} {{ __('frontend.nights') }}
                                </span>
                            @endif

                            @if($tour->tourType)
                                <span class="tour-type">
                                    <i class="fas fa-tag"></i>
                                    {{ $tour->tourType->title }}
                                </span>
                            @endif
                        </div>
                        <!-- Tour Images Slider -->
                        <div class="tour-images">
                            <div class="gallery-container">
                                <div class="galleryview owl-carousel owl-theme">
                                    @if($tour->image)
                                        <div class="item">
                                            <a href="{{ asset('images/' . $tour->image) }}" class="image-popup">
                                                <img src="{{ asset('images/' . $tour->image) }}" alt="{{ $tour->getTranslatedTitle() }}">
                                            </a>
                                        </div>
                                    @endif
                                    
                                    @if($tour->images)
                                        @php
                                            $images = explode(',', $tour->images);
                                            // Filter out the main image to avoid duplication
                                            $mainImage = $tour->image;
                                            $filteredImages = array_filter($images, function($image) use ($mainImage) {
                                                return trim($image) && trim($image) !== $mainImage;
                                            });
                                            

                                        @endphp
                                        @if($filteredImages)
                                            @foreach($filteredImages as $image)
                                                @if(trim($image))
                                                    <div class="item">
                                                        <a href="{{ asset('images/' . trim($image)) }}" class="image-popup">
                                                            <img src="{{ asset('images/' . trim($image)) }}" alt="{{ $tour->getTranslatedTitle() }}">
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </div>

                                <div class="gallery-thumbnails owl-carousel owl-theme">
                                    @if($tour->image)
                                        <div class="item">
                                            <img src="{{ asset('images/' . $tour->image) }}" alt="{{ $tour->getTranslatedTitle() }}">
                                        </div>
                                    @endif
                                    
                                    @if($tour->images)
                                        @if($filteredImages)
                                            @foreach($filteredImages as $image)
                                                @if(trim($image))
                                                    <div class="item">
                                                        <img src="{{ asset('images/' . trim($image)) }}" alt="{{ $tour->getTranslatedTitle() }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tour Details Card -->
                        <div class="tour-details">
                            <div class="tour-details-header">                               
                                <h3>{{ __('frontend.tour_details') }}</h3>
                            </div>
                            
                                    <ul class="detail-list row">
                                        @if($tour->extra_field_1)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.start_date') }}</strong>
                                                    <span>{{ date('d M Y', strtotime($tour->getTranslatedExtraField(1))) }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->extra_field_2)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.end_date') }}</strong>
                                                    <span>{{ date('d M Y', strtotime($tour->getTranslatedExtraField(2))) }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->extra_field_3)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.duration') }}</strong>
                                                    <span>{{ $tour->getTranslatedExtraField(3) }} {{ __('frontend.days') }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->extra_field_4)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-moon"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.nights') }}</strong>
                                                    <span>{{ $tour->getTranslatedExtraField(4) }} {{ __('frontend.nights') }}</span>
                                                </div>
                                            </li>
                                        @endif
                                    
                                        @if($tour->transportType)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-bus"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.transport') }}</strong>
                                                    <span>{{ $tour->transportType->title }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->tourType)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-map-marked-alt"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.tour_type') }}</strong>
                                                    <span>{{ $tour->tourType->title }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->departureCountry)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-globe-americas"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.departure_country') }}</strong>
                                                    <span>{{ $tour->departureCountry->name }}</span>
                                                </div>
                                            </li>
                                        @endif
                                        
                                        @if($tour->departureState)
                                            <li class="detail-item col-lg-4 col-md-6">
                                                <div class="detail-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                                <div class="detail-content">
                                                    <strong>{{ __('frontend.departure_state') }}</strong>
                                                    <span>{{ $tour->departureState->name }}</span>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                               
                        </div>

                        <!-- Tour Overview -->
                        <div class="tour-description">
                            <h3>{{ __('frontend.tour_overview') }}</h3>
                            <div class="description-content">
                                {!! $tour->getTranslatedDescription() !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Booking Widget -->
                        <div class="booking-widget">
                            <h4>{{ __('frontend.book_this_tour') }}</h4>
                            <form id="tourBookingForm">
                                <div class="form-group">
                                    <label>{{ __('frontend.departure_city') }}</label>
                                    <input type="text" class="form-control" value="{{ $tour->departureCity->name ?? 'Lahore' }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('frontend.date') }}</label>
                                    <input type="text" class="form-control" id="tourDate" value="{{ date('d M, Y', strtotime($tour->getTranslatedExtraField(1))) }} - {{ date('d M, Y', strtotime($tour->getTranslatedExtraField(2))) }}" readonly>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                    <label>{{ __('frontend.adults') }} <br> <small>{{ __('frontend.years_plus') }}</small></label>
                                    <div class="passenger-counter">
                                        <button type="button" class="counter-btn" data-type="adults" data-action="decrement">-</button>
                                        <span id="adultsCount" class="count">1</span>
                                        <button type="button" class="counter-btn" data-type="adults" data-action="increment">+</button>
                                    </div>
                                </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                    <label>{{ __('frontend.children') }} <br> <small>{{ __('frontend.years_under') }}</small></label>
                                    <div class="passenger-counter">
                                        <button type="button" class="counter-btn" data-type="children" data-action="decrement">-</button>
                                        <span id="childrenCount" class="count">0</span>
                                        <button type="button" class="counter-btn" data-type="children" data-action="increment">+</button>
                                    </div>
                                </div>
                                        
                                    </div>
                                </div>

                               

                               

                                <div class="price-summary">
                                    <div class="price-breakdown p-0 mt-0">
                                        <div class="price-item">
                                            <span>Adults (1x)</span>
                                            <span>{{ \App\Helpers\CurrencyHelper::formatPrice($tour->getTranslatedExtraField(8)) }}</span>
                                        </div>
                                        <div class="price-item" id="childrenPriceItem" style="display: none;">
                                            <span>Children (0x)</span>
                                            <span>{{ \App\Helpers\CurrencyHelper::formatPrice($tour->getTranslatedExtraField(9) ?? 0) }}</span>
                                        </div>
                                    </div>
                                    <div class="total-price">
                                        <strong>{{ __('frontend.total') }} <span id="totalPrice">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->getTranslatedExtraField(8)) }}</span></strong>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary btn-block w-100" onclick="proceedToBooking()">{{ __('frontend.book_now') }}</button>
                            </form>
                        </div>

                        <!-- Terms & Conditions -->
                        @if($tour->extra_field_12)
                            <div class="terms-widget">
                                <h4>{{ __('frontend.terms_conditions') }}</h4>
                                <div class="terms-content">
                                    {!! $tour->getTranslatedExtraField(12) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Contact Widget -->
                        <div class="contact-widget">
                            <h4>{{ __('frontend.need_help') }}</h4>
                            <div class="contact-info">
                                <p><i class="fas fa-phone"></i> {{ __('frontend.call_us_at') }} <strong>+92 300 1234567</strong></p>
                                <p><i class="fas fa-envelope"></i> {{ __('frontend.email') }} <strong>info@travel.com</strong></p>
                                <p><i class="fas fa-clock"></i> {{ __('frontend.available_247') }}</p>
                            </div>
                        </div>

                         <!-- Related Tours -->
            @if($relatedTours && $relatedTours->count() > 0)
                <div class="related-tours">
                    <div class="related-tours-header">
                        <i class="fas fa-route"></i>
                        <h3>{{ __('frontend.related_tours') }}</h3>
                    </div>
                   
                        @foreach($relatedTours->take(3) as $relatedTour)
                           
                                <div class="tour-card">
                                    <div class="tour-card-image">
                                        <a href="{{ route('tour.detail', $relatedTour->slug) }}" class="tour-image-link">
                                            @if($relatedTour->image)
                                                <img src="{{ asset('images/' . $relatedTour->image) }}" alt="{{ $relatedTour->title }}" class="tour-img">
                                            @else
                                                <img src="{{ asset('images/tour-placeholder.jpg') }}" alt="{{ $relatedTour->title }}" class="tour-img">
                                            @endif
                                        </a>
                                        
                                        

                                        <!-- Tour Type Badge -->
                                        @if($relatedTour->tourType)
                                            <div class="tour-type-badge">
                                                <span>{{ $relatedTour->tourType->title }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="tour-card-content">
                                        <div class="tour-card-header">
                                            <h3 class="tour-card-title">
                                                <a href="{{ route('tour.detail', $relatedTour->slug) }}">{{ $relatedTour->title }}</a>
                                            </h3>
                                        </div>

                                        <!-- Tour Badges -->
                                        <div class="tour-badges">
                                        @if($relatedTour->departureCountry)
                                                <span class="tour-badge tour-location-badge">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $relatedTour->departureCity->name ?? 'N/A' }}
                                                </span>
                                            @endif
                                            @if($relatedTour->extra_field_3)
                                                <span class="tour-badge tour-days-badge">
                                                    <i class="fas fa-calendar-day"></i>
                                                    {{ $relatedTour->extra_field_3 }} {{ __('frontend.days') }}
                                                </span>
                                            @endif
                                            
                                        </div>

                                        

                                        <div class="tour-card-footer">
                                            <div class="tour-price-section">
                                                @if($relatedTour->extra_field_8)
                                                    <div class="tour-price">
                                                        <span class="price-amount">{{ \App\Helpers\CurrencyHelper::formatPrice($relatedTour->extra_field_8) }}</span>
                                                        <span class="price-unit">{{ __('frontend.per_person') }}</span>
                                                    </div>
                                                @else
                                                    <div class="tour-price">
                                                        <span class="price-amount">Contact for Price</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                          
                                        </div>
                                    </div>
                                </div>
                           
                        @endforeach
                    
                </div>
            @endif


                    </div>
                </div>
            </div>

           
        </div>
    </section>

    @push('js')
    <script>
        $(document).ready(function() {

            
            // Carousel initialization is handled by custom.js
            // No need to initialize here to avoid conflicts
            // The custom.js file handles:
            // - .galleryview carousel (main gallery)
            // - .gallery-thumbnails carousel (thumbnails)
            // - Gallery sync between main and thumbnails

            // Initialize Magnific Popup for image popup
            $('.image-popup').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                }
            });

            // Gallery sync is handled by custom.js
            // No need to duplicate sync code here

            // Check if there are stored passenger counts
            var storedPassengers = sessionStorage.getItem('tourPassengers');
            if (storedPassengers) {
                var passengers = JSON.parse(storedPassengers);
                if (passengers.tourId == {{ $tour->id }}) {
                    // Update the counters to match stored values
                    document.getElementById('adultsCount').textContent = passengers.adults || 1;
                    document.getElementById('childrenCount').textContent = passengers.children || 0;
                    
                    // Update price summary
                    updatePriceSummary();
                }
            }

            // Bind click events once
            $('.counter-btn').on('click', function() {
                const type = $(this).data('type');   // "adults" or "children"
                const action = $(this).data('action'); // "increment" or "decrement"
                
                let countElement = $('#' + type + 'Count');
                let currentCount = parseInt(countElement.text());
                
                if (action === 'increment') {
                    if (type === 'adults' && currentCount < 10) currentCount++;
                    if (type === 'children' && currentCount < 10) currentCount++;
                } else if (action === 'decrement') {
                    if (type === 'adults' && currentCount > 1) currentCount--;
                    if (type === 'children' && currentCount > 0) currentCount--;
                }
                
                countElement.text(currentCount);
                updatePriceSummary();
            });
        });



        function updatePriceSummary() {
            var adultCount = parseInt(document.getElementById('adultsCount').textContent);
            var childrenCount = parseInt(document.getElementById('childrenCount').textContent);
            
            var adultPrice = {{ $tour->getTranslatedExtraField(8) ?? 0 }};
            var childrenPrice = {{ $tour->getTranslatedExtraField(9) ?? 0 }};
            
            // Update price breakdown
            var adultPriceItem = document.querySelector('.price-item:first-child');
            adultPriceItem.innerHTML = '<span>Adults (' + adultCount + 'x)</span><span>' + formatPrice(adultPrice * adultCount) + '</span>';
            
            var childrenPriceItem = document.getElementById('childrenPriceItem');
            if (childrenCount > 0) {
                childrenPriceItem.style.display = 'flex';
                childrenPriceItem.innerHTML = '<span>Children (' + childrenCount + 'x)</span><span>' + formatPrice(childrenPrice * childrenCount) + '</span>';
            } else {
                childrenPriceItem.style.display = 'none';
            }
            
            // Calculate total
            var total = (adultPrice * adultCount) + (childrenPrice * childrenCount);
            document.getElementById('totalPrice').textContent = formatPrice(total);
        }

        function formatPrice(price) {
            return '{{ \App\Helpers\CurrencyHelper::getSymbol() }}' + price.toLocaleString();
        }

        function proceedToBooking() {
            var adultCount = parseInt(document.getElementById('adultsCount').textContent);
            var childrenCount = parseInt(document.getElementById('childrenCount').textContent);
            
            // Store passenger counts in session storage for the next step
            sessionStorage.setItem('tourPassengers', JSON.stringify({
                adults: adultCount,
                children: childrenCount,
                tourId: {{ $tour->id }},
                tourTitle: '{{ $tour->getTranslatedTitle() }}',
                totalPrice: document.getElementById('totalPrice').textContent
            }));
            
            // Redirect to booking form
            window.location.href = '{{ route("tour.booking.form", $tour->id) }}';
        }
    </script>
    @endpush
</x-app-layout>

<x-app-layout>
    <!-- Hero Section -->
    <section class="tours-hero">
        <div class="container">
           
                    <div class="hero-content">
                        <div class="row">
                            <div class="col-lg-7">
                            <h1 class="hero-title">{{ __('frontend.discover_amazing_tours') }}</h1>
                            <p class="hero-subtitle">{{ __('frontend.explore_destinations_subtitle') }}</p>
                            </div>
                            <div class="col-lg-5">
                            <div class="hero-search">
                            <form method="GET" action="{{ route('tours.list') }}" class="search-form">
                                <div class="search-input-group">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" name="keyword" placeholder="{{ __('frontend.search_tours_destinations') }}" value="{{ request('keyword') }}" class="search-input">
                                    <button type="submit" class="search-button">
                                        <span>{{ __('frontend.search') }}</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div> 
                            </div>
                        </div>
                       
                                                      
                    </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="tours-filters-section d-none">
        <div class="container">
            <div class="filters-card">
              
                <div class="filters-body">
                    <form method="GET" action="{{ route('tours.list') }}" id="filterForm">
                        <div class="row g-3">                           
                            <div class="col-lg-4 col-md-6">
                                <div class="filter-group">
                                    <label class="filter-label">{{ __('frontend.tour_type') }}</label>
                                    <select name="tour_type" class="filter-select">
                                        <option value="">{{ __('frontend.all_tour_types') }}</option>
                                        @foreach($tourTypes as $type)
                                            <option value="{{ $type->id }}" {{ request('tour_type') == $type->id ? 'selected' : '' }}>
                                                {{ $type->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="filter-group">
                                    <label class="filter-label">{{ __('frontend.departure_country') }}</label>
                                    <select name="departure_country" class="filter-select">
                                        <option value="">{{ __('frontend.all_countries') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ request('departure_country') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="filter-group">
                                    <label class="filter-label">{{ __('frontend.sort_by') }}</label>
                                    <select name="sort" class="filter-select">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('frontend.latest') }}</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('frontend.price_low_to_high') }}</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('frontend.price_high_to_low') }}</option>
                                        <option value="duration_short" {{ request('sort') == 'duration_short' ? 'selected' : '' }}>{{ __('frontend.duration_short') }}</option>
                                        <option value="duration_long" {{ request('sort') == 'duration_long' ? 'selected' : '' }}>{{ __('frontend.duration_long') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="filter-group">
                                    <label class="filter-label">&nbsp;</label>
                                    <button type="submit" class="filter-button">
                                        <i class="fas fa-search"></i>
                                        {{ __('frontend.apply') }}
                                    </button>
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Tours Grid Section -->
    <section class="tours-grid-section">
        <div class="container">
            <!-- Results Header -->
            <div class="results-header">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="results-title">
                            <i class="fas fa-map-marked-alt"></i>
                            {{ __('frontend.available_tours') }}
                        </h2>
                       
                    </div>
                    <div class="col-lg-6"> <p class="results-subtitle">{{ __('frontend.found_tours', ['count' => $tours->total()]) }}</p></div>
                </div>
            </div>

            <!-- Tours Grid -->
            <div class="tours-grid" id="toursGrid">
                <div class="row">
                    @forelse($tours as $tour)
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
                                            <a href="{{ route('tour.detail', $tour->slug) }}" class="tour-details-btn">
                                                <span>{{ __('frontend.view_details') }}</span>
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="no-tours-found">
                                <div class="no-tours-content text-center py-5">
                                    <div class="no-tours-icon">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <h3 class="no-tours-title">{{ __('frontend.no_tours_found') }}</h3>
                                    <p class="no-tours-message">{{ __('frontend.no_tours_found_message') }}</p>
                                    <a href="{{ route('tours.list') }}" class="no-tours-button">
                                        <i class="fas fa-refresh"></i>
                                        {{ __('frontend.view_all_tours') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($tours->hasPages())
                <div class="pagination-section">
                    <div class="pagination-wrapper">
                        <nav aria-label="Tours pagination" class="pagination-nav">
                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                @if ($tours->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tours->previousPageUrl() }}" rel="prev">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                <!-- Page Numbers -->
                                @foreach ($tours->getUrlRange(1, $tours->lastPage()) as $page => $url)
                                    @if ($page == $tours->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($tours->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tours->nextPageUrl() }}" rel="next">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                        
                        <!-- Page Info -->
                        <div class="page-info">
                            <p class="page-info-text">
                                {{ __('frontend.showing_tours', ['first' => $tours->firstItem() ?? 0, 'last' => $tours->lastItem() ?? 0, 'total' => $tours->total()]) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>


    @push('js')
    <script>
        $(document).ready(function() {
            // Auto submit form when filters change
            $('#filterForm select').change(function() {
                $('#filterForm').submit();
            });

            // View options functionality
            $('.view-option').click(function() {
                $('.view-option').removeClass('active');
                $(this).addClass('active');
                
                const view = $(this).data('view');
                if (view === 'list') {
                    $('#toursGrid .row').addClass('list-view');
                } else {
                    $('#toursGrid .row').removeClass('list-view');
                }
            });

            // Smooth scroll for pagination
            $('.pagination a').click(function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                if (url) {
                    window.location.href = url;
                    $('html, body').animate({
                        scrollTop: $('.tours-grid-section').offset().top - 100
                    }, 500);
                }
            });

            // Add loading animation for form submission
            $('#filterForm').submit(function() {
                $('.filter-button').html('<i class="fas fa-spinner fa-spin"></i> {{ __('frontend.loading') }}');
            });
        });
    </script>
    @endpush
</x-app-layout>
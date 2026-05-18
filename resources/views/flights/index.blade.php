<x-app-layout>
<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>{{ __('frontend.discover_your_next_adventure') }}</h1>
    </div>
</div>
<!-- Page title end -->

<!-- Top Hotels Section -->
<div class="innerpagewrap">
  <div class="container">

    <div class="row">
        <div class="col-lg-3">
        <div class="filtersidebar">
                    <form action="{{ route('flights.list') }}" method="GET">
                        <div class="filterbox">
                            <h5>{{ __('frontend.search_filter') }}</h5>
                            <div class="mb-3">
                                <label>{{ __('frontend.from') }}</label>
                                <select class="form-select mb-2" name="from_type" id="from_type">
                                    <option value="name" {{ request('from_type') == 'name' ? 'selected' : '' }}>{{ __('frontend.location_name') }}</option>
                                    <option value="code" {{ request('from_type') == 'code' ? 'selected' : '' }}>{{ __('frontend.iata_code') }}</option>
                                </select>
                                <input type="text" 
                                       class="form-control" 
                                       name="from_location" 
                                       id="from_location"
                                       placeholder="{{ request('from_type') == 'code' ? 'e.g. LHR' : 'e.g. London' }}"
                                       value="{{ request('from_location') }}">
                                <input type="hidden" name="from" id="from_code" value="{{ request('from') }}">
                            </div>
                            <div class="mb-3">
                                <label>{{ __('frontend.to') }}</label>
                                <select class="form-select mb-2" name="to_type" id="to_type">
                                    <option value="name" {{ request('to_type') == 'name' ? 'selected' : '' }}>{{ __('frontend.location_name') }}</option>
                                    <option value="code" {{ request('to_type') == 'code' ? 'selected' : '' }}>{{ __('frontend.iata_code') }}</option>
                                </select>
                                <input type="text" 
                                       class="form-control" 
                                       name="to_location" 
                                       id="to_location"
                                       placeholder="{{ request('to_type') == 'code' ? 'e.g. JFK' : 'e.g. New York' }}"
                                       value="{{ request('to_location') }}">
                                <input type="hidden" name="to" id="to_code" value="{{ request('to') }}">
                            </div>

                            <div class="mb-3">
                                <label>{{ __('frontend.trip_type') }}</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="triptype" id="onewayflight" value="oneway" checked>
                                    <label class="btn btn-outline-primary" for="onewayflight">{{ __('frontend.one_way') }}</label>
                                    <input type="radio" class="btn-check" name="triptype" id="twowayflight" value="twoway">
                                    <label class="btn btn-outline-primary" for="twowayflight">{{ __('frontend.round_trip') }}</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>{{ __('frontend.travelling_on') }}</label>
                                <input type="text" name="travelling_date" id="travelling_date" class="form-control" value="{{ request('travelling_date') }}" autocomplete="off">
                            </div>
                            <div class="mb-3 return-date" style="display: none;">
                                <label>{{ __('frontend.return') }}</label>
                                <input name="return_date" type="text" id="return_date" class="form-control" value="{{ request('return_date') }}" autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label>{{ __('frontend.cabin_class') }}</label>
                                <select class="form-select" name="cabin_class">
                                    <option value="">{{ __('frontend.any_class') }}</option>
                                    <option value="economy" {{ request('cabin_class') == 'economy' ? 'selected' : '' }}>{{ __('frontend.economy') }}</option>
                                    <option value="premium_economy" {{ request('cabin_class') == 'premium_economy' ? 'selected' : '' }}>{{ __('frontend.premium_economy') }}</option>
                                    <option value="business" {{ request('cabin_class') == 'business' ? 'selected' : '' }}>{{ __('frontend.business') }}</option>
                                    <option value="first" {{ request('cabin_class') == 'first' ? 'selected' : '' }}>{{ __('frontend.first_class') }}</option>
                                </select>
                            </div>

                                                  
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label>{{ __('frontend.adults') }}</label>
                                            <input type="number" class="form-control" name="adults" min="1" max="9" value="{{ request('adults', 1) }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label>{{ __('frontend.children') }}</label>
                                            <input type="number" class="form-control" name="children" min="0" max="9" value="{{ request('children', 0) }}">
                                        </div>
                                    </div>
                                </div>
                           
                        </div>

                        <button class="btn btn-primary w-100">{{ __('frontend.search_flights') }}</button>
                    </form>
                </div>
        </div>

        <div class="col-lg-9">

            <!-- Error Messages -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Success Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Search Results Header -->
            @if(isset($flights) && $flights->count() > 0)
                <div class="topsort">
                    <strong>{{ __('frontend.showing_pages') }} : {{ $flights->firstItem() }} - {{ $flights->lastItem() }} {{ __('frontend.total') }} {{ $flights->total() }}</strong>
                </div>
            @elseif(request()->has('from_location') || request()->has('to_location') || request()->has('travelling_date'))
                <div class="topsort">
                    <strong>{{ __('frontend.no_flights_found_criteria') }}</strong>
                </div>
            @endif

            <div class="hmflights row">

                @if(isset($flights) && $flights->count() > 0)
                    @foreach($flights as $flight)
                    
                        <div class="col-lg-4">
                            <div class="flightbox">
                                <div class="flightimg">
                                    <div class="flightcom">            
                                        <a href="{{route('flights.detail',$flight->slug)}}" title="{{title($flight->getTranslatedExtraField(1))}}">
                                        @if($flight->getTranslatedExtraField(20) == 1)  
                                            <img src="{{$flight->getTranslatedExtraField(21)}}" alt="{{title($flight->getTranslatedExtraField(1))}}">
                                            @else                                                 
                                                @php
                                                    $airline = \App\Models\ModulesData::find($flight->getTranslatedExtraField(1));
                                                @endphp
                                                @if($airline && $airline->image)
                                                    <img src="{{asset('images/'.$airline->image)}}" alt="{{title($flight->getTranslatedExtraField(1))}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" alt="{{title($flight->getTranslatedExtraField(1))}}">
                                                @endif
                                            @endif                          
                                        </a>
                                    </div>
                                </div>
                                <div class="flightint">
                                    <h3><a href="{{route('flights.detail',$flight->slug)}}">{{$flight->getTranslatedExtraField(3)}} <i class="fas fa-arrows-alt-h"></i> {{$flight->getTranslatedExtraField(6)}}</a></h3>
                                    <p><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($flight->getTranslatedExtraField(5))->format('d M, Y h:i A') }}</p>
                                    <div class="cabine"><i class="fas fa-user-tie"></i> {{$flight->getTranslatedExtraField(12)}}</div>
                                    <div class="price"><strong>${{$flight->getTranslatedExtraField(10)}}</strong></div>
                                    <a href="{{route('flights.detail',$flight->slug)}}" class="btn btn-primary">{{ __('frontend.view_details') }}</a>
                                </div>
                            </div>
                        </div>
                @endforeach
                @elseif(request()->has('from_location') || request()->has('to_location') || request()->has('travelling_date'))
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-plane-slash fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">{{ __('frontend.no_flights_found') }}</h4>
                            <p class="text-muted">{{ __('frontend.no_flights_matching_criteria') }}</p>
                            <p class="text-muted">{{ __('frontend.try_adjusting_search') }}</p>
                        </div>
                    </div>
                @endif

            </div>

            <div class="blog-pagination text-center">

    @if(isset($flights) && count($flights))
        <ul class="pagination justify-content-center">
            <li class="page-item {{ $flights->previousPageUrl() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $flights->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">{{ __('frontend.previous') }}</span>
                </a>
            </li>

            @php
                $totalPages = $flights->lastPage();
                $currentPage = $flights->currentPage();
                $numPagesToShow = 5; // Number of pages to show before and after the current page
                $startPage = max($currentPage - $numPagesToShow, 1);
                $endPage = min($currentPage + $numPagesToShow, $totalPages);
            @endphp

            @for ($i = $startPage; $i <= $endPage; $i++)
                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                    <a class="page-link" href="{{ $flights->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $flights->nextPageUrl() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $flights->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    @endif

</div>


              

        </div>
    </div>
    
    

  </div>
</div>

<!-- Widgets -->
<div class="container pb-5">
  <div class="row">
    <div class="col-lg-6">
        <div class="hotelwidget">
            <h2>25% Off</h2>
            <h3>Explore the World, One Destination at a Time</h3>
            <a href="#" class="btn btn-sec">Book Now</a>
        </div>
    </div>
    <div class="col-lg-6">
      <div class="fligtwidget">
          <h2>25% Off</h2>
          <h3>Experience the World in Extraordinary Ways</h3>
          <a href="#" class="btn btn-sec">Book Now</a>
      </div>
  </div>
  </div>
</div>

@push('js')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    // Function to setup autocomplete
    function setupAutocomplete(inputId, codeId, typeSelectId) {
        $(inputId).autocomplete({
            source: function(request, response) {
                const type = $(typeSelectId).val();
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
                // Update the input field with the selected value
                $(this).val(ui.item.value);
                // Store the IATA code in the hidden input
                $(codeId).val(ui.item.code);
                return false;
            },
            focus: function(event, ui) {
                // Show the selected value when focusing
                $(this).val(ui.item.value);
                return false;
            }
        });
    }

    // Setup autocomplete for both inputs
    setupAutocomplete('#from_location', '#from_code', '#from_type');
    setupAutocomplete('#to_location', '#to_code', '#to_type');

    // Handle type change
    $('#from_type, #to_type').change(function() {
        var inputId = $(this).attr('id') === 'from_type' ? '#from_location' : '#to_location';
        var codeId = $(this).attr('id') === 'from_type' ? '#from_code' : '#to_code';
        var placeholder = $(this).val() === 'code' ? 'e.g. LHR' : 'e.g. London';
        $(inputId).attr('placeholder', placeholder);
        
        // Clear the hidden code input when switching to name type
        if ($(this).val() === 'name') {
            $(codeId).val('');
        }
    });

    // Show/hide return date based on trip type
    $('input[name="triptype"]').change(function() {
        if ($(this).val() === 'twoway') {
            $('.return-date').show();
        } else {
            $('.return-date').hide();
        }
    });

    // Initialize Datepickers
    $('#travelling_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        onSelect: function(selectedDate) {
            // When travelling date is selected, set the minDate of return date
            $('#return_date').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#return_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0 // Initial minDate, will be updated by travelling_date
    });

    // Ensure return date minDate is updated if travelling_date has a value on load
    if ($('#travelling_date').val()) {
        $('#return_date').datepicker('option', 'minDate', $('#travelling_date').val());
    }

    // Handle flight selection and booking
    $('.select-flight-btn').click(function() {
        const offerId = $(this).data('offer-id');
        const origin = $(this).data('origin');
        const destination = $(this).data('destination');
        
        // Show loading state
        $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        $(this).prop('disabled', true);

        // Store the location codes in session storage
        sessionStorage.setItem('origin_code', origin);
        sessionStorage.setItem('destination_code', destination);

        // Redirect to booking form
        window.location.href = "{{ route('flights.booking.form', '') }}/" + offerId;
    });

    // Form submission validation
    $('form').on('submit', function(e) {
        var fromCode = $('#from_code').val();
        var toCode = $('#to_code').val();
        var fromLocation = $('#from_location').val();
        var toLocation = $('#to_location').val();
        
        if (!fromCode && !fromLocation) {
            e.preventDefault();
            alert('Please select a valid origin location');
            return false;
        }
        
        if (!toCode && !toLocation) {
            e.preventDefault();
            alert('Please select a valid destination location');
            return false;
        }

        // Show loading state
        $(this).find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...');
    });
});
</script>
@endpush

</x-app-layout>
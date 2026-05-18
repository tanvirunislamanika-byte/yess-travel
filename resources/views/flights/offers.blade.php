<x-app-layout>
    <!-- Page title start -->
    <form action="{{ url('/flights/search') }}" method="GET">
        <div class="flightinthero">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">

                   
                <h1>{{ __('frontend.search_flights') }}</h1>
                <div class="duffeltopsearch">

                    @php
                        $triptype = request('triptype');
                    @endphp

                    <div class="tripetype" role="group">
                        <input type="radio" class="btn-check" name="triptype" id="onewayflight" value="oneway"
                            {{ isset($triptype) && $triptype == 'oneway'
                                ? 'checked'
                                : (!in_array($triptype, ['twoway', 'Multicity'])
                                    ? 'checked'
                                    : '') }}>
                        <label class="btn btn-outline-primary" for="onewayflight">One Way</label>

                        <input type="radio" class="btn-check" name="triptype" id="twowayflight" value="twoway"
                            {{ isset($triptype) && $triptype == 'twoway' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="twowayflight">Round Trip</label>

                        <input type="radio" class="btn-check" name="triptype" id="Multi-city" value="Multicity"
                            {{ isset($triptype) && $triptype == 'Multicity' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary" for="Multi-city">Multi-city</label>
                    </div>

                    @php
                        $slices = request('slices');
                    @endphp

                    <div class="row single-city">
                        <div class="col">
                            <div class="mt-3">
                                <label>From</label>
                                <input type="text" class="form-control from_location" name="slices[0][from_location]"
                                    id="from_location"
                                    placeholder="{{ request('from_type') == 'code' ? 'e.g. LHR' : 'e.g. London' }}"
                                    value="{{ $slices[0]['from_location'] ?? '' }}">
                                <input type="hidden" name="slices[0][from]" class="from_code" id="from_code"
                                    value="{{ $slices[0]['from'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col">
                            <div class="mt-3">
                                <label>To</label>
                                <input type="text" class="form-control to_location" name="slices[0][to_location]"
                                    id="to_location"
                                    placeholder="{{ request('to_type') == 'code' ? 'e.g. JFK' : 'e.g. New York' }}"
                                    value="{{ $slices[0]['to_location'] ?? '' }}">
                                <input type="hidden" name="slices[0][to]" id="to_code" class="to_code"
                                    value="{{ $slices[0]['to'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col">
                            <div class="mt-3">
                                <label>Travelling On</label>
                                <input type="text" name="slices[0][travelling_date]" id="travelling_date"
                                    class="form-control travelling_date" placeholder="Select From Date"
                                    value="{{ $slices[0]['travelling_date'] ?? '' }}" autocomplete="off">
                            </div>
                        </div>



                        <div class="col-auto hidden-button"
                            @if (request('triptype') != 'Multicity') style="display: none;" @endif>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-city w-100"
                                    style="visibility: hidden;" title="Remove this city">
                                    &times;
                                </button>
                            </div>
                        </div>

                        <div class="col return-date" @if (request('triptype') != 'twoway') style="display: none;" @endif>
                            <div class="mt-3">
                                <label>Return</label>
                                <input name="return_date" type="text" id="return_date" class="form-control"
                                    placeholder="Select Return Date" value="{{ request('return_date') }}"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="multiple-city">
                    </div>

                   

                    <div class="row">

                        <div class="col-md-3 mutiple-button me-auto" @if (request('triptype') != 'Multicity') style="display: none;" @endif>                                           
                            <div class="addmoreflight">
                                <button type="button" id="add-city" class="btn btn-sec">
                                    + Add Flight
                                </button>
                            </div>                        
                        </div>


                        <div class="col-md-2">
                            <div class="mt-3">
                                <label>Cabin Class</label>
                                <select class="form-control" name="cabin_class">
                                    <option value="">Any Class</option>
                                    <option value="economy"
                                        {{ request('cabin_class') == 'economy' ? 'selected' : '' }}>
                                        Economy</option>
                                    <option value="premium_economy"
                                        {{ request('cabin_class') == 'premium_economy' ? 'selected' : '' }}>Premium
                                        Economy</option>
                                    <option value="business"
                                        {{ request('cabin_class') == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="first" {{ request('cabin_class') == 'first' ? 'selected' : '' }}>
                                        First Class</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mt-3">
                                <label>Adults</label>
                                <input type="number" class="form-control" name="adults" min="1"
                                    max="9" value="{{ request('adults', 1) }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mt-3">
                                <label>Children</label>
                                <input type="number" class="form-control" name="children" min="0"
                                    max="9" value="{{ request('children', 0) }}">
                            </div>
                        </div>
                        <div class="col-md-2 ms-auto text-right mt-3"><label>&nbsp;</label> <button
                                class="btn btn-primary d-block w-100">Search</button></div>
                    </div>

                </div>

                 </div>
                </div>

            </div>
        </div>
        <!-- Page title end -->
        <!-- Main Content -->
        <div class="innerpagewrap">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- Results Section -->

                   

                    <div class="col-lg-9">

                     <div class="sortingbox sticky-top" @if (request()->path() === 'flights' && count(request()->query()) === 0) style="display:none" @endif>

                        <div class="row">
                            <!-- Sort By -->


                            <div class="col-md-3">
                                    <label class="form-label d-block" for="sort_by">Sort By</label>
                                    <select class="form-select form-control" name="sort_by" id="sort_by">
                                        <option value="price_asc"
                                            {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Least Expensive
                                        </option>
                                        <option value="price_desc"
                                            {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Most Expensive
                                        </option>
                                        <option value="duration_asc"
                                            {{ request('sort_by') == 'duration_asc' ? 'selected' : '' }}>Shortest
                                            Duration
                                        </option>
                                        <option value="duration_desc"
                                            {{ request('sort_by') == 'duration_desc' ? 'selected' : '' }}>Longest
                                            Duration
                                        </option>
                                    </select>
                            </div>


                            <!-- Stops -->
                            <div class="col-md-3">
                                    <label class="form-label d-block" for="max_connections">Stops</label>
                                    <select name="max_connections" id="max_connections" class="form-control form-select">
                                        <option value=""
                                            {{ empty(request('max_connections')) ? 'selected' : '' }}>
                                            Any Number of Stops
                                        </option>
                                        <option value="0"
                                            {{ request('max_connections') === '0' ? 'selected' : '' }}>
                                            Direct Only
                                        </option>
                                        <option value="1"
                                            {{ request('max_connections') === '1' ? 'selected' : '' }}>
                                            1 Stop at Most
                                        </option>
                                        <option value="2"
                                            {{ request('max_connections') === '2' ? 'selected' : '' }}>
                                            2 Stops at Most
                                        </option>
                                    </select>
                            </div>

                            <!-- Airlines -->

                            <!-- Airline Select -->
                            <div class="col-md-4">
                                    <label for="airline">Airline</label>
                                    <select name="airline" id="airline" class="form-control">
                                        <option value="" {{ request('airline') == null ? 'selected' : '' }}>All
                                            Airlines</option>
                                        @if (count($airlines) > 0)
                                            @foreach ($airlines as $airline)
                                                @if (!empty($airline['id']))
                                                    <option value="{{ $airline['id'] }}"
                                                        {{ request('airline') == $airline['id'] ? 'selected' : '' }}>
                                                        {{ $airline['name'] }} ({{ $airline['iata_code'] }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif

                                    </select>
                            </div>

                             <div class="col-md-2 ms-auto text-right"><label>&nbsp;</label> <button
                                class="btn btn-sec d-block w-100">
                                Filters</button>
                            </div>

                        </div>

                       
                    </div>





                        <div id="flight-results">
                            @if (isset($error))
                                <div class="alert alert-warning text-center">
                                    {{ $error }}
                                </div>
                            @else
                                <div class="search-results-header mb-4 text-center">
                                    <h2>Showing {{ count($flights ?? []) }} Search Results</h2>
                                </div>

                                @if (count($flights ?? []) > 0)
                                    <div class="flights-list">
                                        @foreach ($flights as $index => $flight)
                                            <div class="flight-card mb-4 {{ $index >= 6 ? 'd-none extra-flight' : '' }}"
                                                data-index="{{ $index }}">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-2">
                                                                <div class="airline-logo text-center">
                                                                    <img src="{{ $flight['owner']['logo_symbol_url'] ?? '' }}"
                                                                        alt="{{ $flight['owner']['name'] ?? '' }}"
                                                                        class="img-fluid mb-2">
                                                                    <div class="airline-name">
                                                                        {{ $flight['owner']['name'] ?? '' }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-7">
                                                                <div class="flight-details">
                                                                    @foreach ($flight['slices'] as $slice)
                                                                        <div class="route mb-3">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="origin">
                                                                                    <h5 class="mb-1">
                                                                                        {{ $slice['origin']['city_name'] ?? '' }}
                                                                                    </h5>
                                                                                    <small>{{ date('D, M j, Y - h:i A', strtotime($slice['segments'][0]['departing_at'])) }}</small>
                                                                                </div>
                                                                                <div class="arrow mx-3">
                                                                                    <i
                                                                                        class="fas fa-arrow-right fa-lg text-primary"></i>
                                                                                </div>
                                                                                <div class="destination">
                                                                                    <h5 class="mb-1">
                                                                                        {{ $slice['destination']['city_name'] ?? '' }}
                                                                                    </h5>
                                                                                    <small>{{ date('D, M j, Y - h:i A', strtotime($slice['segments'][0]['arriving_at'])) }}</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                    <div class="flight-info mt-2">
                                                                        <span class="badge bg-primary">
                                                                            {{ $flight['slices'][0]['segments'][0]['passengers'][0]['cabin_class_marketing_name'] ?? '' }}
                                                                        </span>
                                                                        <span class="duration ms-2 text-muted">
                                                                            {{ $flight['slices'][0]['segments'][0]['duration'] ?? '' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3 text-end">
                                                                <div class="price-section">
                                                                    <div class="price mb-2">
                                                                        <h4 class="mb-0">
                                                                            {{ $flight['total_amount'] ?? '' }}
                                                                            {{ $flight['total_currency'] ?? '' }}
                                                                        </h4>
                                                                        <small class="text-muted">Total Fare</small>
                                                                    </div>
                                                                    <button
                                                                        class="btn btn-primary w-100 select-flight-btn"
                                                                        data-flight-id="{{ trim($flight['id']) }}"
                                                                        data-offer-id="{{ trim($flight['id']) }}"
                                                                        data-origin="{{ trim($flight['slices'][0]['origin']['iata_code'] ?? '') }}"
                                                                        data-destination="{{ trim($flight['slices'][0]['destination']['iata_code'] ?? '') }}">
                                                                        Select Flight
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if (count($flights) > 6)
                                        <div class="text-center mt-4">
                                            <button id="load-more-flights" class="btn btn-outline-secondary px-4"
                                                type="button">
                                                Show More Flights
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info text-center">
                                        No flights found for your search criteria.
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>



                @if (null !== ($arilines = moduleF(4)))
        <!-- Popular Flights -->
        <div class="popflights pt-5">
            <div class="container">              
                <ul class="row topaircompany">
                    @foreach ($arilines as $airline)
                        <li class="col-4 col-lg mb-3">
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
                                <h2>{{ $widget->extra_field_1 }}</h2>
                                <h3>{{ $widget->description }}</h3>
                                <a href="#" class="btn btn-sec">Book Now</a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php $widget = widget(6); ?>
                            <?php $img = asset('images/' . $widget->extra_image_1); ?>
                            <div class="hotelwidget" style="background: url({{ $img }}) no-repeat;">
                                <h2>{{ $widget->extra_field_1 }}</h2>
                                <h3>{{ $widget->description }}</h3>
                                <a href="#" class="btn btn-sec">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @push('js')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script>
            // Debug route resolution
            console.log('Route test:', "{{ route('flights.booking.form', 'TEST_ID') }}");
            
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

            function addCityRow() {
                const sliceIndex = $('.multiple-city .single-city').length + 1;
                let new_html = `
            <div class="row single-city align-items-end">
                <div class="col-12"><h4 class="newflighttitle">Add New Flight Details</h4></div>
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
                            title="Remove this city">
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

                $('.select-flight-btn').click(function(e) {
                    e.preventDefault(); // Prevent any form interference
                    e.stopPropagation(); // Stop event bubbling
                    
                    const offerId = $(this).data('offer-id');
                    const origin = $(this).data('origin');
                    const destination = $(this).data('destination');
                    
                    // Show loading state
                    $(this).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                    );
                    $(this).prop('disabled', true);
                    
                    // Store the location codes in session storage
                    sessionStorage.setItem('origin_code', origin);
                    sessionStorage.setItem('destination_code', destination);
                    
                    // Redirect to booking form
                    const bookingUrl = window.location.origin + '/travelnew/flights/booking/' + offerId;
                    
                    // Redirect immediately
                    window.location.href = bookingUrl;
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
                            showError($input, 'Please select a valid origin location');
                            hasError = true;
                        }
                    });


                    form.find('.to_location:visible').each(function() {
                        let $input = $(this);
                        let toCode = $input.closest('.single-city').find('.to_code').val();
                        if (!$input.val() || !toCode) {
                            showError($input, 'Please select a valid destination location');
                            hasError = true;
                        }
                    });


                    let previousDate = null;

                    form.find('.travelling_date:visible').each(function() {
                        let $input = $(this);
                        let dateVal = $input.val();

                        if (!dateVal) {
                            showError($input, 'Please select a departure date');
                            hasError = true;
                            return;
                        }

                        let currentDate = new Date(dateVal);

                        if (previousDate !== null && currentDate < previousDate) {
                            showError($input, 'This date cannot be earlier than the previous one');
                            hasError = true;
                        }

                        previousDate = currentDate;
                    });



                    let tripType = $('input[name="triptype"]:checked').val();
                    let returnDateInput = $('#return_date');
                    if (tripType === 'twoway' && returnDateInput.is(':visible') && !returnDateInput.val()) {
                        showError(returnDateInput, 'Please select a return date for a two-way trip');
                        hasError = true;
                    }

                    if (hasError) return false;


                    form.find('button[type="submit"]').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...'
                    ).prop('disabled', true);

                    this.submit();

                    function showError($input, message) {
                        let errorDiv = $('<div class="text-danger mt-1 error-msg">' + message + '</div>');
                        $input.closest('.mt-3').append(errorDiv);
                    }
                });
            });
        </script>
        <script>
            let page = 1;
            let loading = false;
            window.addEventListener('scroll', () => {
                if (loading) return;
                const scrollY = window.scrollY;
                const pageHeight = document.documentElement.scrollHeight;
                const clientHeight = window.innerHeight;
                if ((scrollY + clientHeight + 200) >= pageHeight) {
                    loading = true;
                    page++;
                    loadMoreFlights(page);
                }
            });

            function loadMoreFlights(page) {
                document.getElementById('loading-message').style.display = 'block';
                fetch(`{{ url()->current() }}?page=${page}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        const parser = new DOMParser();
                        const html = parser.parseFromString(data, 'text/html');
                        const newCards = html.querySelectorAll('#flight-results .flight-card');
                        if (newCards.length === 0) {
                            window.removeEventListener('scroll', loadMoreFlights);
                        }
                        newCards.forEach(card => {
                            document.getElementById('flight-results').appendChild(card);
                        });
                        loading = false;
                        document.getElementById('loading-message').style.display = 'none';
                    })
                    .catch(err => {
                        console.error(err);
                        loading = false;
                        document.getElementById('loading-message').style.display = 'none';
                    });
            }



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
        </script>


        <script>
            $(document).ready(function() {
                @if (is_array(request('slices')) && count(request('slices')) > 1)
                    @foreach (array_slice(request('slices'), 1) as $slice)
                        var new_html_ready = `
                <div class="row single-city align-items-end mt-3">
                    <div class="col">
                        <div class="mt-3">
                            <label>From</label>
                            <input type="text" class="form-control from_location" name="slices[${sliceIndex}][from_location]"
                                placeholder="e.g. London" value="{{ $slice['from_location'] }}">
                            <input type="hidden" name="slices[${sliceIndex}][from]" class="from_code" value="{{ $slice['from'] }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mt-3">
                            <label>To</label>
                            <input type="text" class="form-control to_location" name="slices[${sliceIndex}][to_location]"
                                placeholder="e.g. New York" value="{{ $slice['to_location'] }}">
                            <input type="hidden" name="slices[${sliceIndex}][to]" class="to_code" value="{{ $slice['to'] }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mt-3">
                            <label>Travelling On</label>
                            <input type="text" name="slices[${sliceIndex}][travelling_date]" class="form-control travelling_date"
                                placeholder="Select From Date" autocomplete="off" value="{{ $slice['travelling_date'] }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-city w-100" style="visibility: visible;"
                                title="Remove this city">
                                &times;
                            </button>
                        </div>
                    </div>
                </div>`;
                        $('.multiple-city').append(new_html_ready);
                        sliceIndex++;
                    @endforeach


                    // Initialize datepicker and autocomplete for preloaded slices
                    initDatepickers();
                    setupAutocomplete('.from_location', '.from_code', '#from_type');
                    setupAutocomplete('.to_location', '.to_code', '#to_type');
                @endif
                checkFlightLimit();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('load-more-flights');
                let flightsShown = 6;
                const increment = 6;

                if (loadMoreBtn) {
                    loadMoreBtn.addEventListener('click', function() {
                        const hiddenFlights = document.querySelectorAll('.extra-flight.d-none');
                        let count = 0;

                        hiddenFlights.forEach(flight => {
                            if (count < increment) {
                                flight.classList.remove('d-none');
                                count++;
                                flightsShown++;
                            }
                        });

                        const stillHidden = document.querySelectorAll('.extra-flight.d-none');
                        if (stillHidden.length === 0) {
                            loadMoreBtn.remove();
                        }
                    });
                }
            });
        </script>

        <script>
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
                let prevDate = $('.single-city .travelling_date').first().val();
                $('.multiple-city .travelling_date').each(function() {
                    let $currentDate = $(this);
                    if (prevDate) {
                        $currentDate.datepicker('option', 'minDate', prevDate);
                    } else {
                        $currentDate.datepicker('option', 'minDate', 0);
                    }
                    if ($currentDate.val()) {
                        prevDate = $currentDate.val();
                    }
                });
            }


            $(document).on('change', '.travelling_date', function() {
                updateReturnMinDate();
                updateMultiCityMinDates();
            });


            $(document).on('click', '#add-city', function() {
                setTimeout(function() {
                    updateMultiCityMinDates();
                }, 100);
            });


            $(document).ready(function() {
                updateReturnMinDate();
                updateMultiCityMinDates();
            });
            // --- END ADDED LOGIC ---
        </script>
    @endpush
</x-app-layout>

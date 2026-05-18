<x-app-layout>
    <!-- Hero Section -->
    <section class="booking-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">{{ __('frontend.complete_your_tour_booking') }}</h1>
                <p class="hero-subtitle">{{ __('frontend.fill_details_to_secure') }}</p>
                <div class="tour-info-badge">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>{{ $tour->title }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="booking-form-section">
        <div class="container">
            <div class="row">
                <!-- Main Form -->
                <div class="col-lg-8">
                    <div class="booking-form-card">
                        <div class="form-card-header">
                            <h2 class="form-title">
                                <i class="fas fa-users"></i>
                                {{ __('frontend.passenger_information') }}
                            </h2>
                            <p class="form-subtitle">{{ __('frontend.provide_passenger_details') }}</p>
                        </div>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('tour.booking.store', $tour->id) }}" method="POST" id="tourBookingForm">
                            @csrf
                            <input type="hidden" name="adults" id="adultsCount" value="{{ $adults }}">
                            <input type="hidden" name="children" id="childrenCount" value="{{ $children }}">
                            <input type="hidden" name="departure_date" value="{{ date('Y-m-d', strtotime($tour->extra_field_1)) }}">

                            <!-- Passenger Details -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h3 class="section-title">
                                        <i class="fas fa-user-friends"></i>
                                        {{ __('frontend.passenger_details') }}
                                    </h3>                                    
                                </div>
                                
                                <!-- Adults Section -->
                                <div id="adultsSection" class="passengers-infowrap">
                                    <!-- Adults will be dynamically generated here -->
                                </div>

                                <!-- Children Section -->
                                <div id="childrenSection" class="passengers-infowrap" style="display: {{ $children > 0 ? 'block' : 'none' }};">
                                    <!-- Children will be dynamically generated here -->
                                </div>
                            </div>

                            <!-- Contact Details -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h3 class="section-title">
                                        <i class="fas fa-address-book"></i>
                                        {{ __('frontend.contact_information') }}
                                    </h3>
                                    <p class="section-subtitle">{{ __('frontend.use_info_for_confirmations') }}</p>
                                </div>
                                
                                <div class="contact-form-grid">
                                    <div class="form-row">
                                        <div class="form-col">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('frontend.title') }}</label>
                                                <div class="title-options">
                                                    <input type="radio" class="title-radio" name="contact_title" id="contact_title_mr" value="Mr" checked>
                                                    <label class="title-option" for="contact_title_mr">{{ __('frontend.mr') }}</label>
                                                    
                                                    <input type="radio" class="title-radio" name="contact_title" id="contact_title_mrs" value="Mrs">
                                                    <label class="title-option" for="contact_title_mrs">{{ __('frontend.mrs') }}</label>
                                                    
                                                    <input type="radio" class="title-radio" name="contact_title" id="contact_title_miss" value="Miss">
                                                    <label class="title-option" for="contact_title_miss">{{ __('frontend.miss') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                    <div class="form-row">
                                    <div class="form-col">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('frontend.full_name') }}</label>
                                                <input type="text" class="form-input" name="contact_name" placeholder="{{ __('frontend.enter_full_name') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-col">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('frontend.email_address') }}</label>
                                                <input type="email" class="form-input" name="contact_email" placeholder="your.email@example.com" required>
                                            </div>
                                        </div>
                                        <div class="form-col">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('frontend.phone') }}</label>
                                                <input type="tel" class="form-input" name="contact_phone" placeholder="+92 300 1234567" required>
                                            </div>
                                        </div>
                                        <div class="form-col">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('frontend.country') }}</label>
                                                <select class="form-select" name="contact_country" required>
                                                    <option value="">{{ __('frontend.select_your_country') }}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $country->id == 167 ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    
                                   
                                </div>
                            </div>

                            <!-- Submit Section -->
                            <div class="form-submit-section">
                                <button type="submit" class="submit-button">
                                    <span>{{ __('frontend.complete_booking') }}</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <p class="submit-note">
                                    <i class="fas fa-shield-alt"></i>
                                    {{ __('frontend.info_secure_note') }}
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Tour Summary Card -->
                    <div class="summary-card sticky-top">
                        <div class="summary-header">
                            <h3 class="summary-title">
                                <i class="fas fa-receipt"></i>
                                {{ __('frontend.tour_summary') }}
                            </h3>
                        </div>
                        
                        <div class="summary-content">
                            <div class="tour-info">
                                <h4 class="tour-name">{{ $tour->title }}</h4>
                                @if($tour->extra_field_1 && $tour->extra_field_2)
                                    <div class="tour-dates">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>{{ date('d M Y', strtotime($tour->extra_field_1)) }} - {{ date('d M Y', strtotime($tour->extra_field_2)) }}</span>
                                    </div>
                                @endif
                                @if($tour->extra_field_3)
                                    <div class="tour-duration">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $tour->extra_field_3 }} {{ __('frontend.days') }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="passenger-summary">
                                <h5 class="summary-subtitle">{{ __('frontend.passenger_breakdown') }}</h5>
                                <div class="passenger-breakdown">
                                    <div class="breakdown-item">
                                        <span class="item-label">{{ __('frontend.adults') }}</span>
                                        <span class="item-count" id="summaryAdults">{{ $adults }}x</span>
                                        @if($tour->extra_field_8)
                                            <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->extra_field_8) }}</span>
                                        @endif
                                    </div>
                                    <div class="breakdown-item" id="summaryChildren" style="display: {{ $children > 0 ? 'flex' : 'none' }};">
                                        <span class="item-label">{{ __('frontend.children') }}</span>
                                        <span class="item-count" id="summaryChildrenCount">{{ $children }}x</span>
                                        @if($tour->extra_field_9)
                                            <span class="item-price">{{ \App\Helpers\CurrencyHelper::formatPrice($tour->extra_field_9) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="total-section">
                                <div class="total-line">
                                    <span class="total-label">{{ __('frontend.total_amount') }}</span>
                                    <span class="total-amount" id="summaryTotalPrice">
                                        @if($tour->extra_field_8)
                                            {{ \App\Helpers\CurrencyHelper::formatPrice($tour->extra_field_8 * $adults + ($tour->extra_field_9 ?? 0) * $children) }}
                                        @else
                                            {{ __('frontend.contact_for_price') }}
                                        @endif
                                    </span>
                                </div>
                                <p class="total-note">{{ __('frontend.prices_include_taxes') }}</p>
                            </div>
                        </div>
                    </div>

                  
                </div>
            </div>
        </div>
    </section>


    @push('js')
    <script>
        $(document).ready(function() {
            console.log('jQuery loaded, document ready');
            
            // Load passenger counts from session storage
            var passengers = JSON.parse(sessionStorage.getItem('tourPassengers') || '{}');
            
            if (passengers.adults) {
                updatePassengerCounts(passengers.adults, passengers.children || 0);
            }
            
            // Initialize the form with current passenger counts
            initializeForm();
            
            // Form submission handler
            $('#tourBookingForm').on('submit', function(e) {
                console.log('Form submitted');
                
                // Check if user is authenticated
                @guest
                    e.preventDefault();
                    alert('{{ __('frontend.please_login_to_continue') }}');
                    window.location.href = '{{ route("login") }}';
                    return false;
                @endguest
                
                @auth
                    // Check if all required fields are filled
                    var requiredFields = $(this).find('[required]');
                    var isValid = true;
                    var missingFields = [];
                    
                    requiredFields.each(function() {
                        if (!$(this).val()) {
                            console.log('Missing required field:', $(this).attr('name'));
                            missingFields.push($(this).attr('name'));
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('{{ __('frontend.fill_all_required_fields') }}: ' + missingFields.join(', '));
                        return false;
                    }
                    
                    console.log('Form validation passed, submitting...');
                    
                    // If validation passes, submit the form normally
                    return true; // Allow form to submit normally
                @endauth
            });
            
            // Also add click handler to submit button
            $('.submit-button').on('click', function(e) {
                console.log('Submit button clicked');
            });
        });

        function initializeForm() {
            var adults = parseInt($('#adultsCount').val());
            var children = parseInt($('#childrenCount').val());
            
            // Generate passenger sections
            generatePassengerSections(adults, children);
            
            // Update summary display
            $('#summaryAdults').text(adults + 'x');
            $('#summaryChildrenCount').text(children + 'x');
            $('#adultsCountDisplay').text(adults);
            $('#childrenCountDisplay').text(children);

            // Show/hide children section in passenger details and summary
            if (children > 0) {
                $('#childrenSection').show();
                $('.children-count').show();
                $('#summaryChildren').show();
            } else {
                $('#childrenSection').hide();
                $('.children-count').hide();
                $('#summaryChildren').hide();
            }

            // Calculate and update total price
            var adultPrice = {{ $tour->extra_field_8 ?? 0 }};
            var childrenPrice = {{ $tour->extra_field_9 ?? 0 }};
            var total = (adultPrice * adults) + (childrenPrice * children);
            
            $('#summaryTotalPrice').text(formatPrice(total));
        }

        function generatePassengerSections(adults, children) {
            var adultsSection = $('#adultsSection');
            var childrenSection = $('#childrenSection');
            
            // Clear existing sections
            adultsSection.empty();
            childrenSection.empty();
            
            // Generate adults sections
            for (var i = 0; i < adults; i++) {
                var adultHtml = generatePassengerHtml('adult', i, i);
                adultsSection.append(adultHtml);
            }
            
            // Generate children sections
            if (children > 0) {
                childrenSection.show();
                for (var i = 0; i < children; i++) {
                    var childHtml = generatePassengerHtml('child', i, adults + i);
                    childrenSection.append(childHtml);
                }
            } else {
                childrenSection.hide();
            }
        }

        function generatePassengerHtml(type, index, passengerIndex) {
            var typeLabel = type === 'adult' ? '{{ __('frontend.adult') }}' : '{{ __('frontend.child') }}';
            var checkedAttr = index === 0 ? 'checked' : '';
            
            return `
                <div class="passenger-group" data-passenger-type="${type}" data-index="${index}">
                    <h6>${typeLabel} ${index + 1}</h6>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">{{ __('frontend.title') }}</label>
                                <div class="title-options">
                                    <input type="radio" class="title-radio" name="passengers[${passengerIndex}][title]" id="title_${passengerIndex}_mr" value="Mr" ${checkedAttr}>
                                    <label class="title-option" for="title_${passengerIndex}_mr">{{ __('frontend.mr') }}</label>
                                    
                                    <input type="radio" class="title-radio" name="passengers[${passengerIndex}][title]" id="title_${passengerIndex}_mrs" value="Mrs">
                                    <label class="title-option" for="title_${passengerIndex}_mrs">{{ __('frontend.mrs') }}</label>
                                    
                                    <input type="radio" class="title-radio" name="passengers[${passengerIndex}][title]" id="title_${passengerIndex}_miss" value="Miss">
                                    <label class="title-option" for="title_${passengerIndex}_miss">{{ __('frontend.miss') }}</label>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="form-row">
                    <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">{{ __('frontend.first_name') }}</label>
                                <input type="text" class="form-input" name="passengers[${passengerIndex}][first_name]" placeholder="{{ __('frontend.enter_first_name') }}" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">{{ __('frontend.last_name') }}</label>
                                <input type="text" class="form-input" name="passengers[${passengerIndex}][last_name]" placeholder="{{ __('frontend.enter_last_name') }}" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">{{ __('frontend.country') }}</label>
                                <select class="form-select" name="passengers[${passengerIndex}][country]" required>
                                    <option value="">{{ __('frontend.select_country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $country->id == 167 ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updatePassengerCounts(adults, children) {
            // Update hidden inputs
            $('#adultsCount').val(adults);
            $('#childrenCount').val(children);

            // Update summary display
            $('#summaryAdults').text(adults + 'x');
            $('#summaryChildrenCount').text(children + 'x');
            $('#adultsCountDisplay').text(adults);
            $('#childrenCountDisplay').text(children);

            // Show/hide children section in passenger details
            if (children > 0) {
                $('#childrenSection').show();
                $('.children-count').show();
                $('#summaryChildren').show();
            } else {
                $('#childrenSection').hide();
                $('.children-count').hide();
                $('#summaryChildren').hide();
            }

            // Calculate and update total price
            var adultPrice = {{ $tour->extra_field_8 ?? 0 }};
            var childrenPrice = {{ $tour->extra_field_9 ?? 0 }};
            var total = (adultPrice * adults) + (childrenPrice * children);
            
            $('#summaryTotalPrice').text(formatPrice(total));
        }

        function formatPrice(price) {
            return '{{ \App\Helpers\CurrencyHelper::getSymbol() }}' + price.toLocaleString();
        }
    </script>
    @endpush
</x-app-layout>

<x-app-layout>
    <div class="booking-form-container">
        <div class="container">
                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>{{ __('frontend.validation_errors_title') }}:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Duffel API error --}}
                @if (session('api_error'))
                    <div class="alert alert-danger">
                        <strong>{{ __('frontend.api_error') }}:</strong> {{ session('api_error') }}
                    </div>
                @endif

                {{-- General system error --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        <strong>{{ __('frontend.error') }}:</strong> {{ session('error') }}
                    </div>
                @endif

                {{-- Success message (if needed) --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (!auth()->check())
                    <div class="alert alert-warning">
                        <h4>{{ __('frontend.authentication_required') }}</h4>
                        <p>{!! __('frontend.login_or_register_to_continue', ['login' => '<a href="'.route('login').'">'.__('frontend.login').'</a>', 'register' => '<a href="'.route('register').'">'.__('frontend.register').'</a>']) !!}</p>
                    </div>
                @else
                    @if (isset($offer))
                        <!-- Header Section -->
                        <div class="booking-form-header">
                            <div class="booking-title">
                                <h1>{{ __('frontend.flight_booking_details') }}</h1>
                            </div>
                        </div>

                        <!-- Main Content -->
                        <div class="booking-form-content">
                            <!-- Left Column - Main Content -->
                            <div class="booking-form-main">
                                <!-- Journey Section -->
                                <div class="booking-journey-section">
                                    <div class="booking-journey-header">
                                        <div class="airline-logo">
                                            @if (isset($offer['slices'][0]['segments'][0]['operating_carrier']['iata_code']))
                                                <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $offer['slices'][0]['segments'][0]['operating_carrier']['iata_code'] }}.svg"
                                                    alt="{{ $offer['slices'][0]['segments'][0]['operating_carrier']['iata_code'] }}">
                                            @else
                                                <i class="fas fa-plane"></i>
                                            @endif
                                        </div>
                                        <div class="journey-info">
                                            <h3>{{ $offer['slices'][0]['origin']['iata_code'] ?? 'N/A' }} - {{ $offer['slices'][0]['destination']['iata_code'] ?? 'N/A' }}</h3>
                                            <div class="route">{{ $offer['slices'][0]['origin']['city_name'] ?? __('frontend.origin') }} {{ __('frontend.to') }} {{ $offer['slices'][0]['destination']['city_name'] ?? __('frontend.destination') }}</div>
                                            <div class="class">{{ ucfirst($offer['slices'][0]['passengers'][0]['cabin_class_marketing_name'] ?? __('frontend.economy')) }} · {{ $offer['slices'][0]['segments'][0]['operating_carrier']['name'] ?? __('frontend.airline') }}</div>
                                        </div>
                                    </div>
                                
                                        @foreach ($offer['slices'] as $sliceIndex => $slice)
                                        @php
                                            $departureTime = \Carbon\Carbon::parse($slice['segments'][0]['departing_at']);
                                            $arrivalTime = \Carbon\Carbon::parse($slice['segments'][0]['arriving_at']);
                                            $duration = $departureTime->diffForHumans($arrivalTime, ['parts' => 2]);
                                            $isNonStop = count($slice['segments']) === 1;
                                        @endphp
                                        <div class="booking-journey-timeline">
                                            <!-- Departure -->
                                            <div class="timeline-item departure">
                                                <div class="time">{{ $departureTime->format('D, j M Y') }}, {{ $departureTime->format('H:i') }}</div>
                                                <div class="location">{{ __('frontend.depart_from') }}: {{ $slice['segments'][0]['origin']['name'] ?? __('frontend.airport') }} ({{ $slice['segments'][0]['origin']['iata_code'] ?? 'N/A' }})
                                                    @if (isset($slice['segments'][0]['origin_terminal']))
                                                        - Terminal {{ $slice['segments'][0]['origin_terminal'] }}
                                                    @endif
                                                </div>
                                            </div>
                                
                                            <!-- Flight Duration -->
                                            <div class="flight-duration">
                                                <div class="duration">{{ __('frontend.flight_duration') }}: {{ $duration }}</div>
                                                <div class="route">{{ $slice['segments'][0]['origin']['iata_code'] ?? 'N/A' }} - {{ $slice['segments'][0]['destination']['iata_code'] ?? 'N/A' }} - {{ $isNonStop ? __('frontend.non_stop') : __('frontend.with_stops') }}</div>
                                            </div>

                                            <!-- Arrival -->
                                            <div class="timeline-item arrival">
                                                <div class="time">{{ $arrivalTime->format('D, j M Y') }}, {{ $arrivalTime->format('H:i') }}</div>
                                                <div class="location">{{ __('frontend.arrive_at') }}: {{ $slice['segments'][0]['destination']['name'] ?? __('frontend.airport') }} ({{ $slice['segments'][0]['destination']['iata_code'] ?? 'N/A' }})
                                                    @if (isset($slice['segments'][0]['destination_terminal']))
                                                        - Terminal {{ $slice['segments'][0]['destination_terminal'] }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                </div>

                                <!-- Flight Details -->
                                <div class="booking-flight-details">
                                    <h4>{{ __('frontend.flight_information') }}</h4>
                                    <div class="booking-flight-details-grid">
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-plane"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.airline') }}</div>
                                                <div class="value">{{ $offer['slices'][0]['segments'][0]['operating_carrier']['name'] ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-plane-departure"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.flight_number') }}</div>
                                                <div class="value">{{ $offer['slices'][0]['segments'][0]['operating_carrier_flight_number'] ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-chair"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.cabin_class') }}</div>
                                                <div class="value">{{ ucfirst($offer['slices'][0]['passengers'][0]['cabin_class_marketing_name'] ?? 'Economy') }}</div>
                                            </div>
                                            </div>
                                
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-suitcase"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.checked_baggage') }}</div>
                                                <div class="value">
                                                    @if (!empty($offer['slices'][0]['passengers'][0]['baggages']))
                                                        @foreach ($offer['slices'][0]['passengers'][0]['baggages'] as $baggage)
                                                            @if ($baggage['type'] === 'checked')
                                                                {{ $baggage['quantity'] ?? 1 }} bag
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </div>
                                            </div>
                                
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-briefcase"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.carry_on') }}</div>
                                                <div class="value">
                                                    @if (!empty($offer['slices'][0]['passengers'][0]['baggages']))
                                                        @foreach ($offer['slices'][0]['passengers'][0]['baggages'] as $baggage)
                                                            @if ($baggage['type'] === 'carry_on')
                                                                {{ $baggage['quantity'] ?? 1 }} bag
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                
                                        @if (isset($offer['slices'][0]['segments'][0]['aircraft']['name']))
                                        <div class="booking-flight-detail-item">
                                            <i class="fas fa-plane"></i>
                                            <div class="detail-content">
                                                <div class="label">{{ __('frontend.aircraft') }}</div>
                                                <div class="value">{{ $offer['slices'][0]['segments'][0]['aircraft']['name'] ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>                                

                                <!-- Policies Section -->
                                <div class="booking-policies-section">
                                    <h4>{{ __('frontend.policies') }}</h4>
                                    <div class="booking-policy-item">
                                        <i class="fas fa-exchange-alt"></i>
                                        <div class="policy-content">
                                            <h6>{{ __('frontend.flight_change_policy') }}</h6>
                                            <p>{{ __('frontend.flight_change_policy_desc') }}</p>
                                        </div>
                                    </div>
                                    <div class="booking-policy-item">
                                        <i class="fas fa-edit"></i>
                                        <div class="policy-content">
                                            <h6>{{ __('frontend.order_change_policy') }}</h6>
                                            <p>{{ __('frontend.order_change_policy_desc') }}</p>
                                        </div>
                                    </div>
                                    <div class="booking-policy-item">
                                        <i class="fas fa-undo"></i>
                                        <div class="policy-content">
                                            <h6>{{ __('frontend.refund_policy') }}</h6>
                                            <p>{{ __('frontend.refund_policy_desc') }}</p>
                                        </div>
                                    </div>
                                </div>
                        </div>

                            <!-- Right Column - Sidebar -->
                            <div class="booking-form-sidebar">
                                

                                <!-- Booking Form Section -->
                                <div class="booking-form-section">
                                    <h4>{{ __('frontend.complete_your_booking') }}</h4>
                            <form id="bookingForm" action="{{ route('flights.booking', ['offer_id' => $offer_id]) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="offer_id" value="{{ $offer_id }}">

                                    @php
                                    $hold_option = 'No';
                                    
                                    if (
                                    isset($offer['payment_requirements']['requires_instant_payment']) &&
                                    $offer['payment_requirements']['requires_instant_payment'] == false
                                    ) {
                                    $hold_option = 'Yes';
                                    }
                                    @endphp

                                        <!-- Booking Type Selection -->
                                        <div class="booking-type-section" @if ($hold_option == 'No') style="display: none;" @endif>
                                            <label class="form-label required">{{ __('frontend.booking_type') }}</label>
                                            <div class="booking-type-options">
                                                <div class="booking-type-option" onclick="selectBookingType('pay')">
                                                    <input type="radio" name="booking_type" id="pay_now" value="pay" checked>
                                                    <div class="option-header">
                                                        <div class="option-icon">💳</div>
                                                        <h5>{{ __('frontend.pay_now') }}</h5>
                                                    </div>
                                                    <p>{{ __('frontend.complete_booking_immediately') }}</p>
                                                </div>
                                                <div class="booking-type-option" onclick="selectBookingType('hold')">
                                                    <input type="radio" name="booking_type" id="hold_booking" value="hold">
                                                    <div class="option-header">
                                                        <div class="option-icon">⏳</div>
                                                        <h5>{{ __('frontend.hold_on') }}</h5>
                                            </div>
                                                    <p>{{ __('frontend.reserve_pay_later') }}</p>
                                            </div>
                                        </div>
                                        </div>

                                        <!-- Passenger Information -->
                                        <div class="passenger-form-section">
                                    @php
                                        $typeCounters = [];
                                    @endphp
                                    @foreach ($passengers as $passenger)
                                        @php
                                            $type = strtolower($passenger['type']);
                                            $typeCounters[$type] = ($typeCounters[$type] ?? 0) + 1;
                                            $passengerLabel =
                                                ucfirst($type) . ' ' . $typeCounters[$type] . ' Information';
                                        @endphp
                                                <div class="passenger-card">
                                                    <h5>{{ $passengerLabel }}</h5>
                                                    
                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label for="title_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.title') }}</label>
                                                            <select class="form-select @error('passengers.' . $passenger['id'] . '.title') is-invalid @enderror"
                                                        id="title_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][title]" required>
                                                        <option value="">{{ __('frontend.select_title') }}</option>
                                                        <option value="mr">{{ __('frontend.mr') }}</option>
                                                        <option value="mrs">{{ __('frontend.mrs') }}</option>
                                                        <option value="ms">{{ __('frontend.ms') }}</option>
                                                        <option value="dr">{{ __('frontend.dr') }}</option>
                                                    </select>
                                                    @error('passengers.' . $passenger['id'] . '.title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                        <div class="form-group">
                                                            <label for="gender_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.gender') }}</label>
                                                            <select class="form-select @error('passengers.' . $passenger['id'] . '.gender') is-invalid @enderror"
                                                        id="gender_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][gender]" required>
                                                        <option value="">{{ __('frontend.select_gender') }}</option>
                                                        <option value="m">{{ __('frontend.male') }}</option>
                                                        <option value="f">{{ __('frontend.female') }}</option>
                                                    </select>
                                                    @error('passengers.' . $passenger['id'] . '.gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                        </div>
                                                </div>

                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label for="given_name_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.given_name') }}</label>
                                                            <input type="text" class="form-control @error('passengers.' . $passenger['id'] . '.given_name') is-invalid @enderror"
                                                        id="given_name_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][given_name]" required>
                                                    @error('passengers.' . $passenger['id'] . '.given_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                        <div class="form-group">
                                                            <label for="family_name_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.family_name') }}</label>
                                                            <input type="text" class="form-control @error('passengers.' . $passenger['id'] . '.family_name') is-invalid @enderror"
                                                        id="family_name_{{ $passenger['id'] }}"
                                                                name="passengers[{{ $passenger['id'] }}][family_name]" required>
                                                    @error('passengers.' . $passenger['id'] . '.family_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group full-width">
                                                            <label for="email_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.email') }}</label>
                                                            <input type="email" class="form-control @error('passengers.' . $passenger['id'] . '.email') is-invalid @enderror"
                                                        id="email_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][email]" required>
                                                    @error('passengers.' . $passenger['id'] . '.email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group full-width">
                                                            <label for="phone_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.phone_number') }}</label>
                                                            <div class="phone-input-container">
                                                @php
                                                    $selectedCode = old(
                                                        'passengers.' . $passenger['id'] . '.phonecode',
                                                        '+92',
                                                    );
                                                @endphp
                                                        <select name="passengers[{{ $passenger['id'] }}][phonecode]"
                                                                    class="form-select select2-country-code country-select">
                                                            @foreach ($countries as $country)
                                                                                                <option value="{{ $country->phonecode }}"
                                    {{ $selectedCode == $country->phonecode ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->phonecode }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                                <input type="tel" class="form-control phone-input phone_valid @error('passengers.' . $passenger['id'] . '.phone_number') is-invalid @enderror"
                                                            id="phone_{{ $passenger['id'] }}"
                                                            name="passengers[{{ $passenger['id'] }}][phone_number]"
                                                                    value="{{ old('passengers.' . $passenger['id'] . '.phone_number') }}" required>
                                                    </div>
                                                    @error('passengers.' . $passenger['id'] . '.phone_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                        </div>
                                                </div>

                                                    <div class="form-row">
                                                        <div class="form-group">
                                                @php
                                                    // Set date limits based on passenger type
                                                    // Adults: minimum 18 years old (max date = 18 years ago)
                                                    // Children/Infants: no minimum age (max date = today)
                                                    $passengerType = strtolower($passenger['type'] ?? 'adult');
                                                    
                                                    if ($passengerType === 'adult') {
                                                        $maxDob = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d');
                                                        $minDob = \Carbon\Carbon::now()->subYears(100)->format('Y-m-d'); // Max 100 years old
                                                    } else {
                                                        // For children and infants
                                                        $maxDob = \Carbon\Carbon::now()->format('Y-m-d'); // Can be today
                                                        $minDob = \Carbon\Carbon::now()->subYears(18)->format('Y-m-d'); // Must be under 18
                                                    }
                                                @endphp
                                                            <label for="born_on_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.date_of_birth') }}</label>
                                                            <input type="date" class="form-control @error('passengers.' . $passenger['id'] . '.born_on') is-invalid @enderror"
                                                        id="born_on_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][born_on]"
                                                        min="{{ $minDob }}"
                                                        max="{{ $maxDob }}" required>
                                                    @error('passengers.' . $passenger['id'] . '.born_on')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>

                                                    <!-- Passport Information Section -->
                                                    <div class="passport-info-header mt-3 mb-2">
                                                        <h6><i class="fas fa-passport"></i> {{ __('frontend.passport_information') }}</h6>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group full-width">
                                                            <label for="passport_number_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.passport_number') }}</label>
                                                            <input type="text" class="form-control @error('passengers.' . $passenger['id'] . '.passport_number') is-invalid @enderror"
                                                        id="passport_number_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][passport_number]"
                                                        value="{{ old('passengers.' . $passenger['id'] . '.passport_number') }}" required>
                                                    @error('passengers.' . $passenger['id'] . '.passport_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label for="passport_issue_date_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.passport_issue_date') }}</label>
                                                            <input type="date" class="form-control @error('passengers.' . $passenger['id'] . '.passport_issue_date') is-invalid @enderror"
                                                        id="passport_issue_date_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][passport_issue_date]"
                                                        value="{{ old('passengers.' . $passenger['id'] . '.passport_issue_date') }}" required>
                                                    @error('passengers.' . $passenger['id'] . '.passport_issue_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                        <div class="form-group">
                                                            <label for="passport_expiry_date_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.passport_expiry_date') }}</label>
                                                            <input type="date" class="form-control @error('passengers.' . $passenger['id'] . '.passport_expiry_date') is-invalid @enderror"
                                                        id="passport_expiry_date_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][passport_expiry_date]"
                                                        value="{{ old('passengers.' . $passenger['id'] . '.passport_expiry_date') }}" required>
                                                    @error('passengers.' . $passenger['id'] . '.passport_expiry_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label for="passport_issuing_country_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.passport_issuing_country') }}</label>
                                                            <select class="form-select select2-country @error('passengers.' . $passenger['id'] . '.passport_issuing_country') is-invalid @enderror"
                                                        id="passport_issuing_country_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][passport_issuing_country]" required>
                                                        <option value="">{{ __('frontend.select_country') }}</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ old('passengers.' . $passenger['id'] . '.passport_issuing_country') == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('passengers.' . $passenger['id'] . '.passport_issuing_country')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                        <div class="form-group">
                                                            <label for="country_of_residence_{{ $passenger['id'] }}" class="form-label required">{{ __('frontend.country_of_residence') }}</label>
                                                            <select class="form-select select2-country @error('passengers.' . $passenger['id'] . '.country_of_residence') is-invalid @enderror"
                                                        id="country_of_residence_{{ $passenger['id'] }}"
                                                        name="passengers[{{ $passenger['id'] }}][country_of_residence]" required>
                                                        <option value="">{{ __('frontend.select_country') }}</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ old('passengers.' . $passenger['id'] . '.country_of_residence') == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('passengers.' . $passenger['id'] . '.country_of_residence')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>

                                        <!-- Optional Add-ons -->
                                             @if (!empty($offer['available_services']))
                                        <div class="addons-section">
                                            <h4>{{ __('frontend.optional_add_ons') }}</h4>
                                                    @foreach ($offer['available_services'] as $service)
                                                        @php
                                                if ($service['type'] == 'baggage') {
                                                    $description = 'Allowed Maximum Quantity ' . $service['maximum_quantity'] . ' and allowd Maximum Weight kg ' . $service['metadata']['maximum_weight_kg'];
                                                        } else {
                                                    $description = $service['description'] ?? 'No description available';
                                                        }
                                                            $currency = $service['total_currency'] ?? '';
                                                            $amount = $service['total_amount'] ?? '';
                                                            $type = ucfirst($service['type'] ?? 'Service');
                                                        @endphp
                                                <div class="addon-item" onclick="toggleAddon(this, '{{ $service['id'] }}')">
                                                    <div class="addon-checkbox">
                                                        <input class="form-check-input service-checkbox" type="checkbox" 
                                                            name="selected_services[]" value="{{ $service['id'] }}"
                                                                    id="service_{{ $service['id'] }}"
                                                                    data-description="{{ $description }}"
                                                                    data-amount="{{ $amount }}"
                                                                    data-currency="{{ $currency }}">
                                                        <div class="addon-content">
                                                            <div class="addon-title">{{ $type }}</div>
                                                            <div class="addon-description">{{ $description }}</div>
                                                            <div class="addon-price">+ {{ $currency }} {{ $amount }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        <!-- Form Actions -->
                                        <div class="form-actions">
                                            <a href="{{ route('flights.search') }}" class="btn btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                                {{ __('frontend.cancel') }}
                                            </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        {{ __('frontend.continue_to_payment') }}
                                    </button>
                                </div>
                            </form>
                                </div>




                                <!-- Price Breakdown -->
                                <div class="booking-summary-section">
                                    <h4>{{ __('frontend.price_breakdown') }}</h4>
                                    <div class="booking-price-breakdown">
                                        <h5>{{ __('frontend.fare_details') }}</h5>
                                        @php
                                            $baseAmount = $offer['base_amount'] ?? 0;
                                            $taxAmount = $offer['tax_amount'] ?? 0;
                                            $currency = $offer['total_currency'] ?? 'USD';
                                            
                                            $serviceFee = (float) (widget(29)->extra_field_2 ?? 0);
                                            $servicePercent = (float) (widget(29)->extra_field_3 ?? 0);
                                            
                                            $subTotal = $baseAmount + $taxAmount;
                                            $servicePercentAmount = ($subTotal * $servicePercent) / 100;
                                            $totalServiceAmount = $serviceFee + $servicePercentAmount;
                                            $totalWithServiceFee = $subTotal + $totalServiceAmount;
                                        @endphp
                                        
                                        <div class="booking-price-item">
                                            <span class="label">{{ __('frontend.base_fare') }}</span>
                                            <span class="value">{{ $currency }} {{ number_format($baseAmount, 2) }}</span>
                                        </div>
                                        <div class="booking-price-item">
                                            <span class="label">{{ __('frontend.taxes_and_fees') }}</span>
                                            <span class="value">{{ $currency }} {{ number_format($taxAmount, 2) }}</span>
                                        </div>
                                        <div class="booking-price-item">
                                            <span class="label">{{ __('frontend.service_fee') }}</span>
                                            <span class="value">{{ $currency }} {{ number_format($totalServiceAmount, 2) }}</span>
                                        </div>
                                        
                                        <!-- Selected Services Breakdown (dynamically updated) -->
                                        <div id="serviceBreakdownContainer"></div>
                                        
                                        <div class="booking-price-item total">
                                            <span class="label">{{ __('frontend.total') }}</span>
                                            <span class="value">{{ $currency }} {{ number_format($totalWithServiceFee, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    @if (isset($offer))
    @php
        // Make these variables available for JavaScript
        $baseAmount = $offer['base_amount'] ?? 0;
        $taxAmount = $offer['tax_amount'] ?? 0;
        $currency = $offer['total_currency'] ?? 'USD';
        
        $serviceFee = (float) (widget(29)->extra_field_2 ?? 0);
        $servicePercent = (float) (widget(29)->extra_field_3 ?? 0);
        
        $subTotal = $baseAmount + $taxAmount;
        $servicePercentAmount = ($subTotal * $servicePercent) / 100;
        $totalServiceAmount = $serviceFee + $servicePercentAmount;
        $totalWithServiceFee = $subTotal + $totalServiceAmount;
    @endphp
    @endif
    
    @push('scripts')
        <!-- Select2 Assets -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2-country-code').select2({
                    placeholder: 'Select Country Code',
                    allowClear: true
                });
                
                $('.select2-country').select2({
                    placeholder: 'Select Country',
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Format card number input
                const cardNumber = document.getElementById('card_number');
                cardNumber.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/(\d{4})/g, '$1 ').trim();
                    e.target.value = value;
                });
                // Format expiry month
                const expiryMonth = document.getElementById('expiry_month');
                expiryMonth.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value > 12) value = 12;
                    if (value.length > 2) value = value.slice(0, 2);
                    e.target.value = value;
                });
                // Format expiry year
                const expiryYear = document.getElementById('expiry_year');
                expiryYear.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 4) value = value.slice(0, 4);
                    e.target.value = value;
                });
                // Format CVV
                const cvv = document.getElementById('cvv');
                cvv.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 3) value = value.slice(0, 3);
                    e.target.value = value;
                });
                // Show loading state on form submit
                const form = document.getElementById('bookingForm');
                const submitBtn = document.getElementById('submitBtn');
                const spinner = submitBtn.querySelector('.spinner-border');
                form.addEventListener('submit', function(event) {
                    console.log('Booking form submitted!');
                    // Show loading spinner
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    submitBtn.childNodes[1].nodeValue = ' Processing...'; // Update button text
                    // If you were using AJAX before, ensure default submission is NOT prevented here if you want regular form submit
                    // event.preventDefault(); // Uncomment this line if you want AJAX submission
                    console.log('Submit handler finished. Default form submission should occur.');
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const bookingRadios = document.querySelectorAll('input[name="booking_type"]');
                const submitBtn = document.getElementById('submitBtn');

                function updateButtonText() {
                    const selected = document.querySelector('input[name="booking_type"]:checked');
                    if (!selected || !submitBtn) return;
                    if (selected.value === 'pay') {
                        submitBtn.innerHTML =
                            `<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Continue to Payment`;
                    } else if (selected.value === 'hold') {
                        submitBtn.innerHTML =
                            `<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Hold Booking`;
                    }
                }
                bookingRadios.forEach(radio => {
                    radio.addEventListener('change', updateButtonText);
                });
                updateButtonText();
            });
        </script>

        <script>
            // Booking Type Selection
            function selectBookingType(type) {
                // Remove selected class from all options
                document.querySelectorAll('.booking-type-option').forEach(option => {
                    option.classList.remove('selected');
                });
                
                // Add selected class to clicked option
                event.currentTarget.classList.add('selected');
                
                // Check the radio button
                const radio = event.currentTarget.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update button text
                updateButtonText();
            }

            // Update Button Text Function
            function updateButtonText() {
                const selected = document.querySelector('input[name="booking_type"]:checked');
                const submitBtn = document.getElementById('submitBtn');
                
                if (!selected || !submitBtn) return;
                
                const spinner = submitBtn.querySelector('.spinner-border');
                
                if (selected.value === 'pay') {
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Continue to Payment
                    `;
                } else if (selected.value === 'hold') {
                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Hold Booking
                    `;
                }
            }

            // Add-on Toggle
            function toggleAddon(element, serviceId) {
                const checkbox = element.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                
                if (checkbox.checked) {
                    element.classList.add('selected');
                } else {
                    element.classList.remove('selected');
                }
                
                // Update service breakdown
                updateServiceBreakdown();
            }

            // Service Breakdown Update
            function updateServiceBreakdown() {
                const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
                const breakdownContainer = document.getElementById('serviceBreakdownContainer');
                const totalPriceEl = document.querySelector('.booking-price-item.total .value');
                
                if (!breakdownContainer || !totalPriceEl) return;

                @if(isset($totalWithServiceFee) && isset($currency))
                const initialTotal = parseFloat('{{ $totalWithServiceFee }}');
                const currency = '{{ $currency }}';

                    let addedAmount = 0;
                    breakdownContainer.innerHTML = ''; // clear existing

                    serviceCheckboxes.forEach(cb => {
                        if (cb.checked) {
                            const amount = parseFloat(cb.dataset.amount);
                            const desc = cb.dataset.description;
                            addedAmount += amount;

                            const row = document.createElement('div');
                        row.className = 'booking-price-item';
                        row.innerHTML = `<span class="label">+ ${desc}</span><span class="value">${currency} ${amount.toFixed(2)}</span>`;
                            breakdownContainer.appendChild(row);
                        }
                    });

                    const newTotal = initialTotal + addedAmount;
                totalPriceEl.textContent = `${currency} ${newTotal.toFixed(2)}`;
                @endif
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize booking type selection
                const payNowOption = document.querySelector('#pay_now').closest('.booking-type-option');
                if (payNowOption) {
                    payNowOption.classList.add('selected');
                }

                // Initialize button text
                updateButtonText();

                // Initialize service breakdown
                updateServiceBreakdown();

                // Add event listeners for service checkboxes
                document.querySelectorAll('.service-checkbox').forEach(cb => {
                    cb.addEventListener('change', updateServiceBreakdown);
                });
            });
        </script>
    @endpush
</x-app-layout>
